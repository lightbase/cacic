<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\AcaoSo;
use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\ComputadorColeta;
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
     *
     */
    public function installAction( Request $request )
    {

        //Escrita do post
        $fp = fopen( OldCacicHelper::CACIC_PATH.'web/ws/insucesso_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
            $postVal = OldCacicHelper::deCrypt( $request, $postVal );
            fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);
       if( $request->isMethod('POST')  )
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
            return  $this->render('CacicWSBundle::common.xml.twig',array(), $response);

        }

    }

    /**
     *  Método responsável por Verificar se houve comunicação com o Agente CACIC
     *
     */
    public function testAction( Request $request )
    {
        OldCacicHelper::autenticaAgente( $request ) ; //Autentica Agente;

        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $strComputerSystem  			 = OldCacicHelper::deCrypt( $request, $request->get('ComputerSystem') );
        $strOperatingSystem  			 = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem') );

        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole( $request, $te_so, $te_node_adress );
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));

        //Debugging do Agente
        $debugging = (  TagValueHelper::getValueFromTags('DateToDebugging',$computador->getTeDebugging() )  == date("Ymd") ? $computador->getTeDebugging()  	:
            (TagValueHelper::getValueFromTags('DateToDebugging',$local->getTeDebugging() )  == date("Ymd") ? $local->getTeDebugging()  :
            ( TagValueHelper::getValueFromTags('DateToDebugging',$rede->getTeDebugging() )  == date("Ymd") ? $rede->getTeDebugging() :	'') ) );
        $debugging = ( $debugging ? TagValueHelper::getValueFromTags('DetailsToDebugging', $debugging ) : '' );

        $response = new Response();
		$response->headers->set('Content-Type', 'xml');
		return  $this->render('CacicWSBundle:Default:test.xml.twig', array(
            'configs'=> OldCacicHelper::getTest( $request ),
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
     *
     */
    public function configAction( Request $request )
    {
        /* TESTEEES *
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->findOneBy(array('idRede'=>'1'));
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findOneBy( array('teSo'=>'2.5.1.1.256.32'));
        $acao = $this->getDoctrine()->getRepository('CacicCommonBundle:Acao')->findOneBy( array('idAcao'=>'col_hard'));
        $teste = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaDetalhesClasse( $acao );
        Debug::dump($teste);die;*/


		//OldCacicHelper::autenticaAgente($request);
        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration')));
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole( $request, $request->get( 'te_so' ),$te_node_adress );
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findOneBy( array('teSo'=>$request->get( 'te_so' )));
        $local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));
        $rede_grupos_ftp = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeGrupoFtp')->findOneBy(array('idRede'=> $rede, 'idComputador'=> $computador));
        $data = new \DateTime('NOW');

        //Debugging do Agente
        $debugging = (  TagValueHelper::getValueFromTags('DateToDebugging',$computador->getTeDebugging() )  == date("Ymd") ? $computador->getTeDebugging()  	:
            (TagValueHelper::getValueFromTags('DateToDebugging',$local->getTeDebugging() )  == date("Ymd") ? $local->getTeDebugging()  :
                ( TagValueHelper::getValueFromTags('DateToDebugging',$rede->getTeDebugging() )  == date("Ymd") ? $rede->getTeDebugging() :	'') ) );
        $debugging = ( $debugging ? TagValueHelper::getValueFromTags('DetailsToDebugging', $debugging ) : '' );


        //Escrita do post
        $fp = fopen( OldCacicHelper::CACIC_PATH.'web/ws/get_config_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
        	$postVal = OldCacicHelper::deCrypt( $request, $postVal );
        	fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);


        $v_te_fila_ftp = '0'; //Fila do FTP

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
                $this->getDoctrine()->getManager()->remove($rede_grupos_ftp);

                // Contagem por subrede
                $soma_redes_grupo_ftp = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeGrupoFtp')->countRedeGrupoFtp( $rede->getIdRede() );

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
        }

        //Implementação MapaCacic
        elseif( OldCacicHelper::deCrypt( $request, $request->get('ModuleProgramName') ) == 'mapacacic.exe')
        {

        }

        else
        {
            if ($request->get('te_palavra_chave') <> '')
            {
                $computador->setTePalavraChave( OldCacicHelper::deCrypt( $request, $request->get('te_palavra_chave') ));
                $this->getDoctrine()->getManager()->persist($computador);
            }

            //verifica se computador coletado é exceção
            $excecao = $this->getDoctrine()->getRepository('CacicCommonBundle:AcaoExcecao')->findOneBy( array('teNodeAdress' => $te_node_adress) );

            //Aplicativos Monitorados
            $monitorados = $this->getDoctrine()->getRepository('CacicCommonBundle:Aplicativo')->listarAplicativosMonitorados( $rede->getIdRede() );
            $arrPerfis	= explode('#',OldCacicHelper::deCrypt($request, $request->get('te_tripa_perfis')));
            $v_retorno_MONITORADOS 	= '';
            $strAcoesSelecionadas = '';

            //Coleta Forçada
            $v_tripa_coleta = explode('#', $computador->getTeNomesCurtosModulos() );

            //Ações de Coletas
            $acoes = $this->getDoctrine()->getRepository('acicCommonBundle:Acao')->listaAcaoRedeComputador($rede, $so);

            foreach($acoes as $acao)
            {
                $strCollectsDefinitions = '['.$acao->getidAcao().']';
                if(!$excecao)
                {
                    if(substr($acao->getIdAcao(),0,4) == 'col_')
                    {
                        $strCollectsDefinitions .= '[te_descricao_breve]' . $acao->getTeDescricaoBreve() . '[/te_descricao_breve]';
                        $strAcoesSelecionadas .= ($strAcoesSelecionadas ? ',' : '') . $acao->getIdAcao();

                        // Obtendo Definições de Classes para Coletas
                        $strCollectsDefinitions .= '[ClassesAndProperties]';

                        $arrClassesNames = array();
                        $arrClassesWhereClauses = array();
                        $strActualClassName		= '';
                        $strPropertiesNames		= '';
                        $detalheClasses = $this->getDoctrine()->getRepository('acicCommonBundle:Classe')->listaDetalhesClasse( $acao );
                        foreach ( $detalheClasses as $detalheClasse )
                        {
                            if (!$arrClassesNames[$detalheClasse->getNmClassName()])
                                $arrClassesNames[$detalheClasse->getNmClassName()] = $$detalheClasse->getNmClassName();

                            if (($detalheClasse->getTeWhereClause()) && ($detalheClasse->getTeWhereClause() <> 'NULL') && !$arrClassesWhereClauses[$detalheClasse->getNmClassName() . '.WhereClause'])
                            {
                                $arrClassesWhereClauses[$detalheClasse->getNmClassName() . '.WhereClause'] = '.';
                                $strCollectsDefinitions .= '[' . $detalheClasse->getNmClassName() . '.WhereClause]' . $detalheClasse->getTeWhereClause() .'[/' . $detalheClasse->getNmClassName() . '.WhereClause]';
                            }
                            if ($strActualClassName <> $detalheClasse->getNmClassName())
                            {
                                $strPropertiesNames .= ($strActualClassName ? '[/' . $strActualClassName . '.Properties]' : '');
                                $strPropertiesNames .= '[' . $$detalheClasse->getNmClassName() . '.Properties]';
                                $strActualClassName  = $detalheClasse->getNmClassName();
                            }
                            else
                                $strPropertiesNames .= ',';

                            $strPropertiesNames .= $detalheClasse->getNmPropertyName();
                        }
                        $strPropertiesNames 	.= ($strActualClassName ? '[/' . $strActualClassName . '.Properties]' : '');

                        $strCollectsDefinitions .= '[Classes]' 	  	. implode(',',$arrClassesNames) . '[/Classes]';
                        $strCollectsDefinitions .= '[Properties]' 	. $strPropertiesNames  			. '[/Properties]';
                        $strCollectsDefinitions .= '[/ClassesAndProperties]';

                        if ($acao->getDtHrColetaForcada() || $computador->getDtHrColetaForcadaEstacao())
                        {
                            $v_dt_hr_coleta_forcada = $acao->getDtHrColetaForcada();
                            if (count($v_tripa_coleta) > 0 and
                                $v_dt_hr_coleta_forcada < $computador->getDtHrColetaForcadaEstacao() and
                                in_array($acao->getTeNomeCurtoModulo(),$v_tripa_coleta))
                            {
                               $v_dt_hr_coleta_forcada = $$computador->getDtHrColetaForcadaEstacao();
                            }
                            $strCollectsDefinitions .= '[DT_HR_COLETA_FORCADA]' . $v_dt_hr_coleta_forcada . '[/DT_HR_COLETA_FORCADA]';
                        }

                        if ( !$request->get('AgenteLinux') && trim($acao->getIdAcao() == "col_moni" && !empty($monitorados))
                        {
                            // ***************************************************
                            // TODO: Melhorar identificação do S.O. neste ponto!!!
                            // ***************************************************
                            // Apenas catalogo as versões anteriores aos NT Like
                            // Colocar abaixo, como elementos do array as identificações internas dos MS-Windows menores que WinNT
                            $arrSgSOtoOlds = array(	'W95','W95OSR','W98','W98SE','WME');

                            foreach ($monitorados as $monitorado )
                            {
                                $v_achei = 0;
                                for($i = 0; $i < count($arrPerfis); $i++ )
                                {
                                    $arrPerfis2 = explode(',',$arrPerfis[$i]);
                                    if ($monitorado->getIdAplicativo()==$arrPerfis2[0] &&
                                        $monitorado->getDtAtualizacao()==$arrPerfis2[1])
                                        $v_achei = 1;
                                }

                                if ($v_achei==0 && ($monitorado->getIdSo() == 0 || $monitorado->getIdSo() == $computador->getIdSo()))
                                {
                                    if ($v_retorno_MONITORADOS <> '') $v_retorno_MONITORADOS .= '#';

                                    $v_te_ide_licenca = trim($monitorado->getTeIdeLicenca());
                                    if ($monitorado->getTeIdeLicenca()=='0')
                                        $v_te_ide_licenca = '';

                                    $v_retorno_MONITORADOS .= $monitorado->getIdAplicativo()	.	','.
                                        $monitorado->getDtAtualizacao()			.	','.
                                        $monitorado->getCsIdeLicenca() 			. 	','.
                                        $v_te_ide_licenca								.	',';

                                    if (in_array($so->getSgSo(),$arrSgSOtoOlds))
                                    {
                                        $v_te_arq_ver_eng_w9x 	= trim($monitorado->getTeArqVerEngW9x());
                                        if ($v_te_arq_ver_eng_w9x=='') 	$v_te_arq_ver_eng_w9x 	= '.';

                                        $v_te_arq_ver_pat_w9x 	= trim($monitorado->getTeArqVerPatW9x());
                                        if ($v_te_arq_ver_pat_w9x=='') 	$v_te_arq_ver_pat_w9x 	= '.';

                                        $v_te_car_inst_w9x 	    = trim($monitorado->getTeCarInstW9x());
                                        if ($monitorado->getTeCarInstW9x()=='0') 	$v_te_car_inst_w9x 	= '';

                                        $v_te_car_ver_w9x 	    = trim($monitorado->getTeCarInstW9x());
                                        if ($monitorado->getCsCarVerWnt()=='0') 	$v_te_car_ver_w9x 	= '';

                                        $v_retorno_MONITORADOS .= '.'                                     	.','.
                                            $monitorado->getTeCarInstW9x()	.','.
                                            $v_te_car_inst_w9x						.','.
                                            $$monitorado->getCsCarVerWnt()		.','.
                                            $v_te_car_ver_w9x						.','.
                                            $v_te_arq_ver_eng_w9x					.','.
                                            $v_te_arq_ver_pat_w9x						;
                                    }
                                    else
                                    {

                                        $v_te_arq_ver_eng_wnt 	= trim($monitorado->getTeArqVerEngWnt());
                                        if ($v_te_arq_ver_eng_wnt=='') 	$v_te_arq_ver_eng_wnt 				= '.';

                                        $v_te_arq_ver_pat_wnt 	= trim($monitorado->getTeArqVerPatWnt());
                                        if ($v_te_arq_ver_pat_wnt=='') 	$v_te_arq_ver_pat_wnt 				= '.';

                                        $v_te_car_inst_wnt 	    = trim($monitorado->getTeCarInstWnt());
                                        if ($monitorado->getCsCarInstWnt()=='0') 	$v_te_car_inst_wnt 	= '';

                                        $v_te_car_ver_wnt 	    = trim($monitorado->getTeCarVerWnt());
                                        if ($monitorado->getTeCarInstWnt()=='0') 	$v_te_car_ver_wnt 	= '';

                                        $v_retorno_MONITORADOS .=   '.'                    					.','.
                                            $monitorado->getTeCarInstWnt()	.','.
                                            $v_te_car_inst_wnt                 		.','.
                                            $$monitorado->getTeCarVerWnt()		.','.
                                            $v_te_car_ver_wnt               		.','.
                                            $v_te_arq_ver_eng_wnt					.','.
                                            $v_te_arq_ver_pat_wnt;

                                    }

                                    $v_retorno_MONITORADOS .=   ',' . $monitorado->getInDisponibilizaInfo();

                                    if ($monitorado->getInDisponibilizaInfo()=='S')
                                    {
                                        $v_retorno_MONITORADOS .= ',' . $monitorado->getNmAplicativo();
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

                $strCollectsDefinitions .= '[/' . $acao->getIdAcao() . ']';
            }
            $strCollectsDefinitions .= '[Actions]' . $strAcoesSelecionadas . '[/Actions]';
        }
        if ($strCollectsDefinitions)
            $strCollectsDefinitions = OldCacicHelper::enCrypt($request, $strCollectsDefinitions);


        $configs = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->listar();
        
        $response = new Response();
		$response->headers->set('Content-Type', 'xml');
		return  $this->render('CacicWSBundle:Default:config.xml.twig', array(
            'configs'=>$configs,
            'rede'=> $rede,
            'v_retorno_MONITORADOS'=>$v_retorno_MONITORADOS,
            'strCollectsDefinitions'=>$strCollectsDefinitions,
            'computador'=>$computador,
            'cs_compress'=>$request->get('cs_compress'),
            'cs_cipher'=>$request->get('cs_cipher'),
            'ws_folder'=>OldCacicHelper::CACIC_WEB_SERVICES_FOLDER_NAME,
            'debugging'=>$debugging,
            'v_te_fila_ftp'=>$v_te_fila_ftp,
            'rede_grupos_ftp'=>$rede_grupos_ftp
        ), $response);
    }
}