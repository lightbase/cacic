<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\AcaoSo;
use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\ConfiguracaoLocal;
use Cacic\CommonBundle\Entity\ConfiguracaoPadrao;
use Cacic\CommonBundle\Entity\LogUserLogado;
use Cacic\CommonBundle\Entity\Rede;
use Cacic\CommonBundle\Entity\RedeGrupoFtp;
use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\CommonBundle\Entity\InsucessoInstalacao;
use Cacic\CommonBundle\Entity\RedeVersaoModulo;
use Cacic\WSBundle\Helper\OldCacicHelper;
use Cacic\WSBundle\Helper\TagValueHelper;
use Cacic\CommonBundle\Entity\LogAcesso;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\NoResultException;


/**
 *
 * Classe responsável por Rerceber as coletas Agente
 * @author lightbase
 *
 */
class DefaultController extends Controller
{
    /**
     *  Método responsável por inserir falhas na instalação do Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function installAction( Request $request )
    {
        if( $request->isMethod('POST') )
        {
            $data = new \DateTime('NOW');

            $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
            $ip_computador = $request->get('te_ip_computador');
            if ( empty($ip_computador) ){
                $ip_computador = TagValueHelper::getValueFromTags( 'IPAddress', $strNetworkAdapterConfiguration );
            }
            if (empty($ip_computador)) {
                $ip_computador = $request->getClientIp();
            }

            $insucesso =  new InsucessoInstalacao();
            $insucesso->setTeIpComputador( $ip_computador );
            $insucesso->setTeSo( $request->get('te_so') );
            $insucesso->setIdUsuario( $request->get('id_usuario') );
            $insucesso->setCsIndicador( $request->get('cs_indicador') );
            $insucesso->setDtDatahora( $data  );

            $this->getDoctrine()->getManager()->persist( $insucesso );
            $this->getDoctrine()->getManager()->flush();

            $response = new Response();
            $response->headers->set('Content-Type', 'xml');
            return  $this->render('CacicWSBundle:Default:instala.xml.twig',array(), $response);

        }

    }

    /**
     *  Método responsável por Verificar comunicação entre Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function testAction( Request $request )
    {
        $logger = $this->get('logger');
        OldCacicHelper::autenticaAgente( $request ) ;
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $strComputerSystem  			 = OldCacicHelper::deCrypt( $request, $request->get('ComputerSystem') );
        $strOperatingSystem  			 = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem') );


        $te_node_address = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $netmask = TagValueHelper::getValueFromTags( 'IPSubnet', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);
        $ip_computador = $request->get('te_ip_computador');
        $versaoAgente = $request->get('te_versao_cacic');

        if ( empty($ip_computador) ){
            $ip_computador = TagValueHelper::getValueFromTags( 'IPAddress', $strNetworkAdapterConfiguration );

        }

        if (empty($ip_computador)) {
            $ip_computador = $request->getClientIp();
        }

        $logger->debug("Teste de Conexão GET-TEST! Ip do computador: $ip_computador Máscara da rede: $netmask");

        // Caso não tenha encontrado, tenta pegar a variável da requisição
        if (empty($te_node_address)) {
            $te_node_address = $request->get('te_node_address');
        }

        if (empty($netmask)) {
            $netmask = $request->get('netmask');
        }

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );

        if (empty($te_node_address) || empty($so)) {
            $this->get('logger')->error("Erro na operação de getTest. IP = $ip_computador Máscara = $netmask. MAC = $te_node_address. SO = $te_so");

            $response = new Response();
            $response->headers->set('Content-Type', 'xml');
            $cacic_helper = new OldCacicHelper($this->get('kernel'));

            return  $this->render('CacicWSBundle:Default:testUpdate.xml.twig', array(
                'configs'=> $cacic_helper->getTest( $request ),
                //'computador' => $computador,
                'rede' => $rede,
                //'debugging' => $debugging,
                'ws_folder' => OldCacicHelper::CACIC_WEB_SERVICES_FOLDER_NAME,
                'cs_cipher' => $request->get('cs_cipher'),
                'cs_compress' => $request->get('cs_compress')
            ), $response);
        }

        #$logger->debug("444444444444444444444444444444444444: $netmask | ".$rede->getNmRede());
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole( $request, $te_so, $te_node_address, $rede, $so, $ip_computador );
        //$local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));
        $local = $rede->getIdLocal();

        //Debugging do Agente
        $debugging = (  TagValueHelper::getValueFromTags('DateToDebugging',$computador->getTeDebugging() )  == date("Ymd") ? $computador->getTeDebugging()  	:
            (TagValueHelper::getValueFromTags('DateToDebugging',$local->getTeDebugging() )  == date("Ymd") ? $local->getTeDebugging()  :
                ( TagValueHelper::getValueFromTags('DateToDebugging',$rede->getTeDebugging() )  == date("Ymd") ? $rede->getTeDebugging() :	'') ) );
        $debugging = ( $debugging ? TagValueHelper::getValueFromTags('DetailsToDebugging', $debugging ) : '' );

        // Adiciona no log de acesso. REGRA: só adiciona se o último registro foi em data diferente da de hoje
        // TODO: Colocar um parâmetro que diz quantas vezes deve ser registrado o acesso por dia
        $data_acesso = new \DateTime();
        $hoje = $data_acesso->format('Y-m-d');

        $ultimo_acesso = $this->getDoctrine()->getRepository('CacicCommonBundle:LogAcesso')->ultimoAcesso( $computador->getIdComputador() );
        //$ultimo_user_logado = $this->getDoctrine()->getRepository('CacicCommonBundle:LogUserLogado')->ultimoAcesso( $computador->getIdComputador() );

        /**
         * Grava os registros na Tabela Log_User_Logado
         */
        if (!empty($ultimo_login)) {
            $ultimo_user_logado = new LogUserLogado();
            $ultimo_user_logado->setIdComputador($computador);
            $ultimo_user_logado->setData($data_acesso);
            $ultimo_user_logado->setUsuario($ultimo_login);
            $this->getDoctrine()->getManager()->persist($ultimo_user_logado);
            $this->getDoctrine()->getManager()->flush();
        } else {
            $logger->error("ERRO NO GET-TEST: usuário logado não encontrado para o computador $ip_computador");
        }

        if (empty($ultimo_acesso)) {
            // Se for o primeiro registro grava o acesso do computador
            $logger->debug("Último acesso não encontrado. Registrando acesso para o computador $computador em $hoje");

            $log_acesso = new LogAcesso();
            $log_acesso->setIdComputador($computador);
            $log_acesso->setData($data_acesso);

            /*
             * Grava o último usuário logado no banco apenas se não estiver vazio
             */
            if (!empty($ultimo_login)) {
                $log_acesso->setUsuario($ultimo_login);
            }

            // Grava o log
            $this->getDoctrine()->getManager()->persist($log_acesso);
            $this->getDoctrine()->getManager()->flush();
        } else {
            $dt_ultimo_acesso = $ultimo_acesso->getData()->format('Y-m-d');

            // Só adiciono se a data de útimo acesso for diferente do dia de hoje
            if ($hoje != $dt_ultimo_acesso) {
                $logger->debug("Inserindo novo registro de acesso para o computador $computador em $hoje");

                $log_acesso = new LogAcesso();
                $log_acesso->setIdComputador($computador);
                $log_acesso->setData($data_acesso);

                /*
                 * Grava o último usuário logado no banco apenas se não estiver vazio
                 */
                if (!empty($ultimo_login)) {
                    $log_acesso->setUsuario($ultimo_login);
                }

                // Grava o log
                $this->getDoctrine()->getManager()->persist($log_acesso);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        $cacic_helper = new OldCacicHelper($this->get('kernel'));

        $testcoleta = '[forca_coleta]' . $computador->getForcaColeta() . '[/forca_coleta]';
        return  $this->render('CacicWSBundle:Default:test.xml.twig', array(
           'configs'=> $cacic_helper->getTest( $request ),
            'computador' => $computador,
            'rede' => $rede,
            'testcoleta' => $testcoleta,
            'debugging' => $debugging,
            'ws_folder' => OldCacicHelper::CACIC_WEB_SERVICES_FOLDER_NAME,
            'cs_cipher' => $request->get('cs_cipher'),
            'cs_compress' => $request->get('cs_compress')
        ), $response);
    }

    /**
     *  Método responsável por retornar configurações necessarias ao Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function configAction( Request $request )
    {
        OldCacicHelper::autenticaAgente($request);
        $logger = $this->get('logger');
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $netmask = TagValueHelper::getValueFromTags( 'IPSubnet', $strNetworkAdapterConfiguration );
        $ip_computador = $request->get('te_ip_computador');
        if ( empty($ip_computador) ){
            $ip_computador = TagValueHelper::getValueFromTags( 'IPAddress', $strNetworkAdapterConfiguration );
        }
        if (empty($ip_computador)) {
            $ip_computador = $request->getClientIp();
        }

        $te_node_address = TagValueHelper::getValueFromTags( 'MACAddress', OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration')));

        // Caso não tenha encontrado, tenta pegar a variável da requisição
        if (empty($te_node_address)) {
            $te_node_address = $request->get('te_node_address');
        }

        if (empty($netmask)) {
            $netmask = $request->get('netmask');
        }

        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findOneBy( array('teSo'=>$request->get( 'te_so' )));

        /**
         * Se a máscara de subrede ou o mac address estiver vazio, força o redirecionamento para provável atualização
         */
        if (empty($te_node_address) || empty($so)) {
            $this->get('logger')->error("Erro na operação de getConfig. IP = $ip_computador Máscara = $netmask. MAC = $te_node_address. SO =" . $request->get( 'te_so' ));

            return $this->forward('CacicWSBundle:Default:update', $this->getRequest()->request->all());
        }

        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole( $request, $request->get( 'te_so' ),$te_node_address, $rede, $so, $ip_computador );
        //$local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));
        $local = $rede->getIdLocal();
        $data = new \DateTime('NOW');
        $logger->debug("Teste de Conexão GET-CONFIG! Ip do computador: $ip_computador Máscara da rede: $netmask MAC Address: $te_node_address");

        //Debugging do Agente
        $debugging = (  TagValueHelper::getValueFromTags('DateToDebugging',$computador->getTeDebugging() )  == date("Ymd") ? $computador->getTeDebugging()  	:
            (TagValueHelper::getValueFromTags('DateToDebugging',$local->getTeDebugging() )  == date("Ymd") ? $local->getTeDebugging()  :
                ( TagValueHelper::getValueFromTags('DateToDebugging',$rede->getTeDebugging() )  == date("Ymd") ? $rede->getTeDebugging() :	'') ) );
        $debugging = ( $debugging ? TagValueHelper::getValueFromTags('DetailsToDebugging', $debugging ) : '' );

        //definição de variaveis locais.
        $v_te_fila_ftp = '0'; //Fila do FTP
        $v_retorno_MONITORADOS = '';
        $strCollectsDefinitions = '';
        $agente_py = false;
        $strPatrimonio = '';

        // Se o computador ainda não está em um grupo FTP, insere
        $rede_grupos_ftp = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeGrupoFtp')->findOneBy(array('idRede'=> $rede, 'idComputador'=> $computador));
        if(empty($rede_grupos_ftp)) {
            $rede_grupos_ftp = new RedeGrupoFtp();
            $rede_grupos_ftp->setIdComputador($computador);
            $rede_grupos_ftp->setIdRede($rede);
            $rede_grupos_ftp->setNuHoraFim($data);
        }

        //Se instalação realizada com sucesso.
        if (trim($request->get('in_instalacao')) == 'OK' )
        {
            $v_id_ftp      = ( $request->get('id_ftp') ? trim( $request->get('id_ftp') ) : '');

            if (trim($request->get('te_fila_ftp'))=='1' && !$v_id_ftp)
            {
                // TimeOut definido para 5 minutos
                $v_timeout = time() - (5 * 60000);

                // Exclusão por timeout
                //remoção de RedeGrupoFtp
                if (!empty($rede_grupos_ftp)) {
                    $this->getDoctrine()->getManager()->remove($rede_grupos_ftp);
                } else {
                    $rede_grupos_ftp = new RedeGrupoFtp();
                }

                // Contagem por subrede
                $rede_grupos_ftp_repository = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeGrupoFtp')->findBy(array('idRede' => $rede->getIdRede()));
                $soma_redes_grupo_ftp = count($rede_grupos_ftp_repository);

                // Caso o grupo de estações esteja cheio, retorno o tempo de 5 minutos para espera e nova tentativa...
                // Posteriormente, poderemos calcular uma média para o intervalo, em função do link da subrede
                if($soma_redes_grupo_ftp >= $rede->getNuLimiteFtp()) // Se for maior que o Limite FTP, configurado em Administração/Cadastros/SubRedes
                $v_te_fila_ftp = '5'; // Tempo em minutos
                else
                {
                    $rede_grupos_ftp->setIdComputador($computador);
                    $rede_grupos_ftp->setIdRede($rede);
                    $rede_grupos_ftp->setNuHoraInicio($data);
                    $this->getDoctrine()->getManager()->persist($rede_grupos_ftp);
                }
            }
            elseif( trim($request->get('te_fila_ftp')) == '2') // Operação concluída com sucesso!
            {
                $rede_grupos_ftp->setIdComputador($computador);
                $rede_grupos_ftp->setIdRede($rede);
                $rede_grupos_ftp->setNuHoraInicio($data);
                $this->getDoctrine()->getManager()->remove($rede_grupos_ftp);

            }
            $this->getDoctrine()->getManager()->flush();
        }

        //Implementação MapaCacic
        elseif( OldCacicHelper::deCrypt( $request, $request->get('ModuleProgramName') ) == 'mapacacic.exe')
        {

		    $servidorAutenticacao = $rede->getIdServidorAutenticacao();
			if (!empty($servidorAutenticacao) and $servidorAutenticacao->getInAtivo() == 'S'){
			    $strPatrimonio =
								'[ip]'             . $servidorAutenticacao->getTeIpServidorAutenticacao()    . '[/ip]'            .
								'[usuario]'        . $servidorAutenticacao->getUsuario()                     . '[/usuario]'       .
								'[senha]'          . $servidorAutenticacao->getSenha()                       . '[/senha]'         .
								'[base]'           . $servidorAutenticacao->getNmServidorAutenticacaoDns()   . '[/base]'          .
								'[identificador]'  . $servidorAutenticacao->getTeAtributoIdentificador()     . '[/identificador]' .
								'[retorno1]'       . $servidorAutenticacao->getTeAtributoRetornaNome()       . '[/retorno1]'      .
								'[retorno2]'       . $servidorAutenticacao->getTeAtributoRetornaEmail()      . '[/retorno2]'      .
								'[retorno3]'       . $servidorAutenticacao->getTeAtributoRetornaTelefone()   . '[/retorno3]'      .
								'[tipo_protocolo]' . $servidorAutenticacao->getIdTipoProtocolo()             . '[/tipo_protocolo]'.
                				'[porta]'          . $servidorAutenticacao->getNuPortaServidorAutenticacao() . '[/porta]'         ;
;
				$strPatrimonio ='[dados_ldap]'     . OldCacicHelper::enCrypt($request, $strPatrimonio)       . '[/dados_ldap]'    ;
			}
			/*$dadosPatrimonio = $this->getDoctrine()->getRepository('CacicCommonBundle:ComputadorColeta')->findBy(array('idClass'=>'Patrimonio', 'idComputador'=>$computador->getIdComputador()));
			
			if ($dadosPatrimonio->getTeClassValue())
               $strConfigsPatrimonioCombos = '[Collects_Patrimonio_Last]' . OldCacicHelper::enCrypt($request, $dadosPatrimonio->getTeClassValues()) . '[/Collects_Patrimonio_Last]';

          //Coloca tudo numa string só para devolver
			$strPatrimonio = $strPatrimonio . $strConfigsPatrimonioCombos;*/
		
        }

        else
        {
            //caso não seja agente Linux
            if ($request->get('te_palavra_chave') <> '')
            {
                $computador->setTePalavraChave( OldCacicHelper::deCrypt( $request, $request->get('te_palavra_chave') ));
                $this->getDoctrine()->getManager()->persist($computador);
                $this->getDoctrine()->getManager()->flush();
            }

            //verifica se computador coletado é exceção
            $excecao = $this->getDoctrine()->getRepository('CacicCommonBundle:AcaoExcecao')->findOneBy( array('teNodeAddress' => $te_node_address) );

            //Aplicativos Monitorados
            $monitorados = $this->getDoctrine()->getRepository('CacicCommonBundle:Aplicativo')->listarAplicativosMonitorados( $rede->getIdRede() );
            $arrPerfis	= ( $request->get('te_tripa_perfis') ? explode('#',OldCacicHelper::deCrypt($request, $request->get('te_tripa_perfis'))) : '');
            $v_retorno_MONITORADOS 	= null;
            $strAcoesSelecionadas = null;
            $strCollectsDefinitions = '';

            //Coleta Forçada para computador
//            $v_tripa_coleta = explode('#', $computador->getTeNomesCurtosModulos() );

            //Ações de Coletas por rede
            $acoes = $this->getDoctrine()->getRepository('CacicCommonBundle:Acao')->listaAcaoRedeComputador($rede, $so);

            foreach($acoes as $acao)
            {
                $strCollectsDefinitions .= '['.$acao['idAcao'].']';
                if(empty($excecao))
                {
                    if (substr($acao['idAcao'],0,4) == 'col_')
                    {
                        $strCollectsDefinitions .= '[te_descricao_breve]' . $acao['teDescricaoBreve'] . '[/te_descricao_breve]';
                        $strAcoesSelecionadas .= ($strAcoesSelecionadas ? ',' : '') . $acao['idAcao'];

                        // Obtendo Defini��es de Classes para Coletas
                        $strCollectsDefinitions .= '[ClassesAndProperties]';

                        $detalhesClasses = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaClasses( $acao['idAcao'] );
                        $arrClassesNames 		= array();
                        $arrClassesWhereClauses = array();
                        $strActualClassName		= '';
                        $strPropertiesNames		= '';

                        //percorre detalhes das classes, ex. DiskDriver, CD-ROM, FisicalMemory
                        foreach ($detalhesClasses as $detalheClasse)
                        {
                            if (empty($arrClassesNames[$detalheClasse['nmClassName']]))
                                $arrClassesNames[$detalheClasse['nmClassName']] = $detalheClasse['nmClassName'];

                            if ( !empty($detalheClasse['teWhereClause']) && !isset($detalheClasse['teWhereClause']) && !isset($arrClassesWhereClauses[$detalheClasse['nmClassName'].'.WhereClause']))
                            {
                                $arrClassesWhereClauses[$detalheClasse['nmClassName'] . '.WhereClause'] = '.';
                                $strCollectsDefinitions .= '[' . $detalheClasse['nmClassName'] . '.WhereClause]' . $detalheClasse['teWhereClause'] .'[/' . $detalheClasse['nmClassName'] . '.WhereClause]';
                            }

                            if ($strActualClassName <> $detalheClasse['nmClassName'])
                            {
                                $strPropertiesNames .= ($strActualClassName ? '[/' . $strActualClassName . '.Properties]' : '');
                                $strPropertiesNames .= '[' . $detalheClasse['nmClassName'] . '.Properties]';
                                $strActualClassName  = $detalheClasse['nmClassName'];
                            }
                            //Removento o envio de propriedades de softwares para o CollectsDefinitions
                            /*
                            else
                            $strPropertiesNames .= ',';
                            $strPropertiesNames .= $detalheClasse['nmPropertyName'];
                            */
                        }

                        $strPropertiesNames 	.= ($strActualClassName ? '[/' . $strActualClassName . '.Properties]' : '');

                        $strCollectsDefinitions .= '[Classes]' 	  	. implode(',',$arrClassesNames) . '[/Classes]';
                        $strCollectsDefinitions .= '[/ClassesAndProperties]';

                        //Removento o envio de propriedades de softwares para o CollectsDefinitions
                        //$strCollectsDefinitions .= '[Properties]' 	. $strPropertiesNames  			. '[/Properties]';

                    }
                    else
                        $strCollectsDefinitions .= 'OK';
                }

                $strCollectsDefinitions .= '[/' . $acao['idAcao'] . ']';
            }
            $strCollectsDefinitions .= '[Actions]' . $strAcoesSelecionadas . '[/Actions]';
        }

        if (!empty($strCollectsDefinitions))
            $strCollectsDefinitions = OldCacicHelper::enCrypt($request, $strCollectsDefinitions);

        if($request->get('AgenteLinux'))
            $agente_py = true;

        $em = $this->getDoctrine()->getManager();
        /*
         * Buscando primeiro API Key válido
         */
        $apikey = $em->getRepository('CacicCommonBundle:Usuario')->firstApiKey();

        // Configurações do local
        $configs = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarPorLocal($local->getIdLocal());
        //$logger->debug("Configurações encontradas:\n".print_r($configs, true));

        /*
         * Força coleta timer
         */
        $timerForcaColeta = null;
        $i = 0;
        foreach ($configs as $elm) {
            if ($elm['idConfiguracao'] == 'nu_intervalo_forca_coleta') {
                $timerForcaColeta = $elm['nu_intervalo_forca_coleta'];
            }

            if ($elm['idConfiguracao'] == 'nu_intervalo_exec') {
                if (intval($elm['vlConfiguracao']) > 4) {
                    $configs[$i]['vlConfiguracao'] = intval($elm['vlConfiguracao']) / 60;
                }
            }
            $i = $i + 1;
        }
        if (empty($timerForcaColeta)) {
            $timerForcaColeta = 15;
        }
        $logger->debug("GET-CONFIG: timerForcaColeta = $timerForcaColeta");

        //informações dos modulos do agente, nome, versao, hash
        $te_versao_cacic = $request->request->get('te_versao_cacic');
        $redes_versoes_modulos = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeVersaoModulo')->getUpdate(
            $rede->getIdRede(),
            $te_versao_cacic
        );

        // Força pasta do servidor de updates para a versão 2.8.1.23
        $tePathServUpdates = null;
        preg_match("/^2.(.*)/", $te_versao_cacic, $arrResult);
        if (!empty($arrResult)) {
            $tePathServUpdates = 'update28';
        }

        //$logger->debug("GET-CONFIG: Redes, versões e módulos:\n".print_r($redes_versoes_modulos, true));

        $nm_user_login_updates = OldCacicHelper::enCrypt($request, $rede->getNmUsuarioLoginServUpdates());
        $senha_serv_updates = OldCacicHelper::enCrypt($request, $rede->getTeSenhaLoginServUpdates());
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        return  $this->render('CacicWSBundle:Default:config.xml.twig', array(
            'configs'=>$configs,
            'rede'=> $rede,
            'agente_py'=>$agente_py,
            'redes_versoes_modulos'=>$redes_versoes_modulos,
            'main_program'=>OldCacicHelper::CACIC_MAIN_PROGRAM_NAME.'.exe',
            'folder_name'=>OldCacicHelper::CACIC_LOCAL_FOLDER_NAME,
            'nm_user_login_updates'=>$nm_user_login_updates,
            'senha_serv_updates'=>$senha_serv_updates,
            'v_retorno_MONITORADOS'=>$v_retorno_MONITORADOS,
            'strCollectsDefinitions'=>$strCollectsDefinitions,
            'computador'=>$computador,
            'cs_compress'=>$request->get('cs_compress'),
            'cs_cipher'=>$request->get('cs_cipher'),
            'ws_folder'=>OldCacicHelper::CACIC_WEB_SERVICES_FOLDER_NAME,
            'debugging'=>$debugging,
            'v_te_fila_ftp'=>$v_te_fila_ftp,
            'rede_grupos_ftp'=>$rede_grupos_ftp,
		    'strPatrimonio'=>$strPatrimonio,
            'timerForcaColeta'=>$timerForcaColeta,
            'apikey' => $apikey,
 //           'modPatrimonio'=> $modPatrimonio,
            'tePathServUpdates' => $tePathServUpdates
        ), $response);
    }

    /**
     *  Método responsável por verificar e e enviar os Hashes ao Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function updateAction( Request $request )
    {
        $logger = $this->get('logger');
        OldCacicHelper::autenticaAgente( $request ) ;
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );

        $te_node_address = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $netmask = TagValueHelper::getValueFromTags( 'IPSubnet', $strNetworkAdapterConfiguration );
        $ip_computador = $request->get('te_ip_computador');
        if ( empty($ip_computador) ){
            $ip_computador = TagValueHelper::getValueFromTags( 'IPAddress', $strNetworkAdapterConfiguration );
        }
        if (empty($ip_computador)) {
            $ip_computador = $request->getClientIp();
        }

        /**
         * Caso não tenha encontrado, tenta pegar a variável da requisição
         */
        if (empty($te_node_address)) {
            $te_node_address = $request->get('te_node_address');
        }

        if (empty($netmask)) {
            $netmask = $request->get('netmask');
        }

        /**
         * Executa atualização forçada se algum dos parâmetros obrigatórios estiver vazio
         */
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );
        $local = $rede->getIdLocal();
        $configs = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarPorLocal($local->getIdLocal());
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );
        $redes_versoes_modulos = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeVersaoModulo')->findBy( array( 'idRede'=>$rede->getIdRede() ) );
        $nm_user_login_updates = OldCacicHelper::enCrypt($request, $rede->getNmUsuarioLoginServUpdates());
        $senha_serv_updates = OldCacicHelper::enCrypt($request, $rede->getTeSenhaLoginServUpdates());
        $logger->debug("Teste de Conexão GET-UPDATE! Ip do computador: $ip_computador Máscara da rede: $netmask MAC Address: $te_node_address");

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        return  $this->render('CacicWSBundle:Default:update.xml.twig', array(
            'configs'=>$configs,
            'rede'=> $rede,
            'redes_versoes_modulos'=>$redes_versoes_modulos,
            'main_program'=>OldCacicHelper::CACIC_MAIN_PROGRAM_NAME.'.exe',
            'folder_name'=>OldCacicHelper::CACIC_LOCAL_FOLDER_NAME,
            'nm_user_login_updates'=>$nm_user_login_updates,
            'senha_serv_updates'=>$senha_serv_updates,
            'cs_compress'=>$request->get('cs_compress'),
            'cs_cipher'=>$request->get('cs_cipher'),
            'ws_folder'=>OldCacicHelper::CACIC_WEB_SERVICES_FOLDER_NAME,
        ), $response);
    }
}
