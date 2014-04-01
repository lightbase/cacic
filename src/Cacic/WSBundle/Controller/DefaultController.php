<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\AcaoSo;
use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\ConfiguracaoLocal;
use Cacic\CommonBundle\Entity\ConfiguracaoPadrao;
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

            $insucesso =  new InsucessoInstalacao();

            $insucesso->setTeIpComputador( $_SERVER["REMOTE_ADDR"] );
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
        $te_so = $request->get( 'te_so' );
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole( $request, $te_so, $te_node_address );
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));

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
        if (empty($ultimo_acesso)) {
            // Se for o primeiro registro grava o acesso do computador
            $logger->debug("Último acesso não encontrado. Registrando acesso para o computador $computador em $hoje");

            $log_acesso = new LogAcesso();
            $log_acesso->setIdComputador($computador);
            $log_acesso->setData($data_acesso);

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

                // Grava o log
                $this->getDoctrine()->getManager()->persist($log_acesso);
                $this->getDoctrine()->getManager()->flush();
            }
        }


        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        $cacic_helper = new OldCacicHelper($this->get('kernel'));
        return  $this->render('CacicWSBundle:Default:test.xml.twig', array(
           'configs'=> $cacic_helper->getTest( $request ),
            'computador' => $computador,
            'rede' => $rede,
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

        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration')));
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole( $request, $request->get( 'te_so' ),$te_node_adress );
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findOneBy( array('teSo'=>$request->get( 'te_so' )));
        $local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));
        $data = new \DateTime('NOW');

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
		    $strPatrimonio ='[ip]'     . $servidorAutenticacao->getTeIpServidorAutenticacao() . '[/ip]'           .
							'[usuario]'        . $servidorAutenticacao->getUsuario()                  . '[/usuario]'       .
							'[senha]'          . $servidorAutenticacao->getSenha()                    . '[/senha]'         .
							'[base]'           . $servidorAutenticacao->getTeAtributoIdentificador()  . '[/base]'          .
							'[identificador1]' . $servidorAutenticacao->getTeAtributoRetornaNome()    . '[/identificador1]'.
							'[identificador2]' . $servidorAutenticacao->getTeAtributoRetornaEmail()   . '[/identificador2]'.
							'[identificador3]' . $servidorAutenticacao->getTeAtributoRetornaTelefone(). '[/identificador3]'.
							'[tipo_protocolo]' . $servidorAutenticacao->getIdTipoProtocolo()          . '[/tipo_protocolo]'.
							'[porta]'          . $servidorAutenticacao->getNuPortaServidorAutenticacao. '[/porta]'         ;
			$strPatrimonio ='[dados_ldap]'     . OldCacicHelper::enCrypt($request, $strPatrimonio)    . '[/dados_ldap]'    ;

            /*
            if ($dadosPatrimonio->getTeClassValue())
               $strConfigsPatrimonioCombos = '[Collects_Patrimonio_Last]' . OldCacicHelper::enCrypt($request, $dadosPatrimonio->getTeClassValues()) . '[/Collects_Patrimonio_Last]';

          //Coloca tudo numa string só para devolver
          $strPatrimonio = $strPatrimonio . $strConfigsPatrimonioCombos;
       	  error_log('2222222222222222222222222222222222222222222222'.$strPatrimonio);
            */
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
            $excecao = $this->getDoctrine()->getRepository('CacicCommonBundle:AcaoExcecao')->findOneBy( array('teNodeAddress' => $te_node_adress) );

            //Aplicativos Monitorados
            $monitorados = $this->getDoctrine()->getRepository('CacicCommonBundle:Aplicativo')->listarAplicativosMonitorados( $rede->getIdRede() );
            $arrPerfis	= ( $request->get('te_tripa_perfis') ? explode('#',OldCacicHelper::deCrypt($request, $request->get('te_tripa_perfis'))) : '');
            $v_retorno_MONITORADOS 	= null;
            $strAcoesSelecionadas = null;
            $strCollectsDefinitions = '';

            //Coleta Forçada para computador
            $v_tripa_coleta = explode('#', $computador->getTeNomesCurtosModulos() );

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

                        $detalhesClasses = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaDetalhesClasse( $acao['idAcao'] );
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
                            else
                                $strPropertiesNames .= ',';

                            $strPropertiesNames .= $detalheClasse['nmPropertyName'];
                        }

                        $strPropertiesNames 	.= ($strActualClassName ? '[/' . $strActualClassName . '.Properties]' : '');

                        $strCollectsDefinitions .= '[Classes]' 	  	. implode(',',$arrClassesNames) . '[/Classes]';
                        $strCollectsDefinitions .= '[Properties]' 	. $strPropertiesNames  			. '[/Properties]';
                        $strCollectsDefinitions .= '[/ClassesAndProperties]';

                        //caso coleta forçada tenha sido marcada em algum momento
                        $coleta_forcada_computador = $computador->getDtHrColetaForcadaEstacao();
                        if ( !empty($acao['dtHrColetaForcada']) ||  !empty($coleta_forcada_computador))
                        {
                            $v_dt_hr_coleta_forcada = $acao["dt_hr_coleta_forcada"];
                            if (count($v_tripa_coleta) > 0 and
                                $v_dt_hr_coleta_forcada < $computador->getDtHrColetaForcadaEstacao() and
                                in_array($acao["te_nome_curto_modulo"],$v_tripa_coleta))
                            {
                                $v_dt_hr_coleta_forcada = $computador->getDtHrColetaForcadaEstacao();
                            }
                            $strCollectsDefinitions .= '[DT_HR_COLETA_FORCADA]' . $v_dt_hr_coleta_forcada . '[/DT_HR_COLETA_FORCADA]';
                        }
                        if ( !$request->get('AgenteLinux') && trim($acao['idAcao']) == "col_moni" && !empty($monitorados))
                        {
                            $arrSgSOtoOlds = array(	'W95',
                                'W95OSR',
                                'W98',
                                'W98SE',
                                'WME');
                            foreach ($monitorados as $monitorado )
                            {
                                $v_achei = 0;
                                if($arrPerfis <> null)
                                {
                                    for($i = 0; $i < count($arrPerfis); $i++ )
                                    {
                                        $arrPerfis2 = explode(',',$arrPerfis[$i]);
                                        if ($monitorado["idAplicativo"]==$arrPerfis2[0] &&
                                            $monitorado["dtAtualizacao"]==$arrPerfis2[1])
                                            $v_achei = 1;
                                    }
                                }

                                if ( $v_achei==0 && ( $monitorado["idSo"] == 0 || $monitorado["idSo"] == $computador->getIdSo()) )
                                {
                                    if ($v_retorno_MONITORADOS <> '') $v_retorno_MONITORADOS .= '#';

                                    $v_te_ide_licenca = trim($monitorado["teIdeLicenca"]);
                                    if ($monitorado["teIdeLicenca"]=='0')
                                        $v_te_ide_licenca = '';

                                    $v_retorno_MONITORADOS .= $monitorado["idAplicativo"]	.	','.
                                        $monitorado["dtAtualizacao"]			.	','.
                                        $monitorado["csIdeLicenca"] 			. 	','.
                                        $v_te_ide_licenca								.	',';

                                    if (in_array($so->getSgSo(),$arrSgSOtoOlds))
                                    {
                                        $v_te_arq_ver_eng_w9x 	= trim($monitorado["teArqVerEngW9x"]);
                                        if ($v_te_arq_ver_eng_w9x=='') 	$v_te_arq_ver_eng_w9x 	= '.';

                                        $v_te_arq_ver_pat_w9x 	= trim($monitorado["teArqVerPatW9x"]);
                                        if ($v_te_arq_ver_pat_w9x=='') 	$v_te_arq_ver_pat_w9x 	= '.';

                                        $v_te_car_inst_w9x 	    = trim($monitorado["TeCarInstW9x"]);
                                        if ($monitorado["teCarInstW9x"]=='0') 	$v_te_car_inst_w9x 	= '';

                                        $v_te_car_ver_w9x 	    = trim($monitorado["teCarInstW9x"]);
                                        if ($monitorado["csCarVerWnt"]=='0') 	$v_te_car_ver_w9x 	= '';

                                        $v_retorno_MONITORADOS .= '.'                                     	.','.
                                            $monitorado["teCarInstW9x"]	.','.
                                            $v_te_car_inst_w9x						.','.
                                            $$monitorado["csCarVerWnt"]		.','.
                                            $v_te_car_ver_w9x						.','.
                                            $v_te_arq_ver_eng_w9x					.','.
                                            $v_te_arq_ver_pat_w9x						;
                                    }
                                    else
                                    {

                                        $v_te_arq_ver_eng_wnt 	= trim($monitorado["teArqVerEngWnt"]);
                                        if ($v_te_arq_ver_eng_wnt=='') 	$v_te_arq_ver_eng_wnt 				= '.';

                                        $v_te_arq_ver_pat_wnt 	= trim($monitorado["teArqVerPatWnt"]);
                                        if ($v_te_arq_ver_pat_wnt=='') 	$v_te_arq_ver_pat_wnt 				= '.';

                                        $v_te_car_inst_wnt 	    = trim($monitorado["teCarInstWnt"]);
                                        if ($monitorado["csCarInstWnt"]=='0') 	$v_te_car_inst_wnt 	= '';

                                        $v_te_car_ver_wnt 	    = trim($monitorado["teCarVerWnt"]);
                                        if ($monitorado["teCarInstWnt"]=='0') 	$v_te_car_ver_wnt 	= '';

                                        $v_retorno_MONITORADOS .=   '.'                    					.','.
                                            $monitorado["teCarInstWnt"]	.','.
                                            $v_te_car_inst_wnt                 		.','.
                                            $$monitorado["teCarVerWnt"]		.','.
                                            $v_te_car_ver_wnt               		.','.
                                            $v_te_arq_ver_eng_wnt					.','.
                                            $v_te_arq_ver_pat_wnt;

                                    }

                                    $v_retorno_MONITORADOS .=   ',' . $monitorado["inDisponibilizaInfo"];

                                    if ($monitorado["inDisponibilizaInfo"]=='S')
                                    {
                                        $v_retorno_MONITORADOS .= ',' . $monitorado["nmAplicativo"];
                                    }
                                    else
                                    {
                                        $v_retorno_MONITORADOS .= ',.';
                                    }

                                }
                            }
                            if ($v_retorno_MONITORADOS <> '')
                                $v_retorno_MONITORADOS = OldCacicHelper::replaceInvalidHTTPChars($v_retorno_MONITORADOS);

                            $strCollectsDefinitions .= $v_retorno_MONITORADOS;
                        }
                    }
                    else
                        $strCollectsDefinitions .= 'OK';
                }

                $strCollectsDefinitions .= '[/' . $acao['idAcao'] . ']';
            }
            $strCollectsDefinitions .= '[Actions]' . $strAcoesSelecionadas . '[/Actions]';
        }

        //error_log("333333333333333333333333333333333333333333: $strCollectsDefinitions");

        if (!empty($strCollectsDefinitions))
            $strCollectsDefinitions = OldCacicHelper::enCrypt($request, $strCollectsDefinitions);

        if($request->get('AgenteLinux'))
            $agente_py = true;

        $configs = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarPorLocal($local->getIdLocal());

        //informações dos modulos do agente, nome, versao, hash
        $redes_versoes_modulos = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeVersaoModulo')->findBy( array( 'idRede'=>$rede->getIdRede() ) );

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
        ), $response);
    }
}
