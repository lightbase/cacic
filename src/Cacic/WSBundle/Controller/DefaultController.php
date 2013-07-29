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
        if( $request->isMethod('POST') )
        {
            $data = new \DateTime('NOW');

            $insucesso =  new InsucessoInstalacao();

            $insucesso->setTeIpComputador( $_SERVER["REMOTE_ADDR"] );
            $insucesso->setTeSo( $request->get('te_so') );
            $insucesso->setIdUsuario( ($request->get('id_usuario') ? $request->get('id_usuario')  : null) );
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
        OldCacicHelper::autenticaAgente( $request ) ;
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
        OldCacicHelper::autenticaAgente($request);

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

        //definição de variaveis locais.
        $v_te_fila_ftp = '0'; //Fila do FTP
        $versao_modulo = '';
        $pacote_py = '';
        $pacote_py_hash = '';
        $v_retorno_MONITORADOS = '';
        $strCollectsDefinitions = '';

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
            $patrimonioConfigInterfaces = $this->getDoctrine()->getRepository('CacicCommomBundle:PatrimonioConfigInterface')->findBy(array('idLocal'=>$local));
            $strConfigsPatrimonioInterface = '';
            foreach ($$patrimonioConfigInterfaces as $patrimonioConfigInterface)
            {
                $strConfigsPatrimonioInterface .= '[te_'		. $patrimonioConfigInterface->getIdEtiqueta() . ']'	. $patrimonioConfigInterface->getTeEtiqueta()		. '[/te_' 			.	$patrimonioConfigInterface->getIdEtiqueta() . ']';
                $strConfigsPatrimonioInterface .= '[in_exibir_' . $patrimonioConfigInterface->getIdEtiqueta() . ']'	. $patrimonioConfigInterface->getInExibirEtiqueta()	. '[/in_exibir_' 	. 	$patrimonioConfigInterface->getIdEtiqueta() . ']';
                $strConfigsPatrimonioInterface .= '[te_help_'   . $patrimonioConfigInterface->getIdEtiqueta() . ']'	. $patrimonioConfigInterface->getTeHelpEtiqueta() 	. '[/te_help_'   	. 	$patrimonioConfigInterface->getIdEtiqueta() . ']';
            }

            if ($strConfigsPatrimonioInterface)
                $strConfigsPatrimonioInterface .= '[Configs_Patrimonio_Interface]' .OldCacicHelper::enCrypt($request, $strConfigsPatrimonioInterface) . '[/Configs_Patrimonio_Interface]';

            /*
            Consulta que devolve os itens das tabelas de U.O. níveis 1, 1a e 2
            ==================================================================
            */
           /* $dadosUsuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario')->findOneBy('idUsuario', OldCacicHelper::deCrypt($request, $request->get('id_usuario')));

            if ($dadosUsuario->getTeLocaisSecundarios() <> '')
                $unidades = $this->getDoctrine()->getRepository('CacicCommonBundle:Uorg')->findAll();//' AND (loc.id_local = '.$dadosUsuario[0]['id_local'] . ' OR loc.id_local in ('.$dadosUsuario[0]['te_locais_secundarios'].')) ';
            else
                $unidades = $this->getDoctrine()->getRepository('CacicCommonBundle:Uorg')->findAll();//' AND loc.id_local = '.$dadosUsuario[0]['id_local'];

            $strConfigsPatrimonioCombos	= '';
            $intCountTagUO1				= 0;
            $intCountTagUO1a			= 0;
            $intCountTagUO2				= 0;

            $arrUO1						= array();
            $arrUO1a					= array();


            foreach ($unidades as $unidade)
            {
                GravaTESTES('arrUnidades[' . $unidade['idUnidade'] . '][uo1_id]: ' .     $unidade['idUnidade'] );
                GravaTESTES('arrUnidades[' . $unidade['idUnidade']. '][uo1_nm]: ' .      $unidade['uo1_nm']);
                GravaTESTES('arrUnidades[' . $unidade-['idUnidade'] . '][uo1a_id]: ' .   $unidade['uo1a_id']);
                GravaTESTES('arrUnidades[' . $unidade['idUnidade']. '][uo1a_nm]: ' .     $unidade['uo1a_nm']);
                GravaTESTES('arrUnidades[' . $unidade['idUnidade'] . '][uo2_id]: ' .     $unidade['uo2_id']);
                GravaTESTES('arrUnidades[' . $unidade-['idUnidade'] . '][uo2_nm]: ' .    $unidade['uo2_nm']);
                GravaTESTES('arrUnidades[' . $unidade-['idUnidade'] . '][uo2_id_local]:'.$unidade['uo2_id_local']);
                GravaTESTES('arrUnidades[' . $unidade['idUnidade'] . '][loc_sg]: ' .     $unidade['loc_sg']);


                GravaTESTES('Verificando arrUO['.$unidade->getIdUnidade().']: '.$unidade->getIdUnidade());
                if (!$unidade->getIdUnidade())
                {
                    GravaTESTES('NÃO EXISTE!');
                    $arrUO1[$unidade->getIdUnidade()] = 1;

                    $intCountTagUO1++;
                    $strConfigsPatrimonioCombos .= '[UO1#'   	. $intCountTagUO1 								. ']';
                    $strConfigsPatrimonioCombos .= '[UO1_ID]'   . $unidade['uo1_id']  	. '[/UO1_ID]';
                    $strConfigsPatrimonioCombos .= '[UO1_NM]'	. $unidade['uo1_nm']  	. '[/UO1_NM]';
                    $strConfigsPatrimonioCombos .= '[/UO1#'  	. $intCountTagUO1  								. ']';
                }
                else
                    GravaTESTES('EXISTE!');

                if (!$arrUO1a[$arrUnidades[$unidade['uo1a_id']])
                {
                    $arrUO1a[$arrUnidades[$unidade['uo1a_id']] = 1;

                    $intCountTagUO1a++;
                    $strConfigsPatrimonioCombos .= '[UO1a#'  		. $intCountTagUO1a								. ']';
                    $strConfigsPatrimonioCombos .= '[UO1a_ID]'  	. $arrUnidades[$unidade['uo1a_id'] 	. '[/UO1a_ID]';
                    $strConfigsPatrimonioCombos .= '[UO1a_IdUO1]'  	. $arrUnidades[$unidade['uo1_id'] 	. '[/UO1a_IdUO1]';
                    $strConfigsPatrimonioCombos .= '[UO1a_NM]'  	. $arrUnidades[$unidade['uo1a_nm'] 	. '[/UO1a_NM]';
                    $strConfigsPatrimonioCombos .= '[/UO1a#'  		. $intCountTagUO1a  							. ']';
                }

                $intCountTagUO2++;

                $strConfigsPatrimonioCombos .= '[UO2#'   		. $intCountTagUO2  									. ']';
                $strConfigsPatrimonioCombos .= '[UO2_IdUO1a]'   . $arrUnidades[$unidade['uo1a_id']  	. '[/UO2_IdUO1a]';
                $strConfigsPatrimonioCombos .= '[UO2_ID]'   	. $arrUnidades[$unidade['uo2_id']  		. '[/UO2_ID]';
                $strConfigsPatrimonioCombos .= '[UO2_NM]'   	. $arrUnidades[$unidade['uo2_nm']  		. '[/UO2_NM]';
                $strConfigsPatrimonioCombos .= '[UO2_IdLocal]' 	. $arrUnidades[$unidade['uo2_id_local'] . '[/UO2_IdLocal]';
                $strConfigsPatrimonioCombos .= '[UO2_SgLocal]' 	. $arrUnidades[$unidade['loc_sg'] 		. '[/UO2_SgLocal]';
                $strConfigsPatrimonioCombos .= '[/UO2#'  		. $intCountTagUO2 									. ']';
            }


            if ($strConfigsPatrimonioCombos)
                $strConfigsPatrimonioCombos = '[Configs_Patrimonio_Combos]' . OldCacicHelper::enCrypt($request, $strConfigsPatrimonioCombos) . '[/Configs_Patrimonio_Combos]';

            // Envio os valores já existentes no banco, referentes ao ID_SO+TE_NODE_ADDRESS da estação chamadora...
            $dadosPatrimonio = $this->getDoctrine()->getRepository('CacicCommonBundle:ComputadorColeta')->findBy(array('idClass'=>'Patrimonio', 'idComputador'=>$computador));

            if ($dadosPatrimonio->getTeClassValue())
                 $strConfigsPatrimonioCombos .= '[Collects_Patrimonio_Last]' . OldCacicHelper::enCrypt($request, $dadosPatrimonio->getTeClassValues()) . '[/Collects_Patrimonio_Last]';

           */
        }

        else
        {
            if ($request->get('te_palavra_chave') <> '')
            {
                $computador->setTePalavraChave( OldCacicHelper::deCrypt( $request, $request->get('te_palavra_chave') ));
                $this->getDoctrine()->getManager()->persist($computador);
            }

            //verifica se computador coletado é exceção
            $excecao = $this->getDoctrine()->getRepository('CacicCommonBundle:AcaoExcecao')->findOneBy( array('teNodeAddress' => $te_node_adress) );

            //Aplicativos Monitorados
            $monitorados = $this->getDoctrine()->getRepository('CacicCommonBundle:Aplicativo')->listarAplicativosMonitorados( $rede->getIdRede() );
            $arrPerfis	= explode('#',OldCacicHelper::deCrypt($request, $request->get('te_tripa_perfis')));
            $v_retorno_MONITORADOS 	= null;
            $strAcoesSelecionadas = null;

            //Coleta Forçada
            $v_tripa_coleta = explode('#', $computador->getTeNomesCurtosModulos() );

            //Ações de Coletas
            $acoes = $this->getDoctrine()->getRepository('CacicCommonBundle:Acao')->listaAcaoRedeComputador($rede, $so);

            foreach($acoes as $acao)
            {
                $strCollectsDefinitions = '['.$acao['idAcao'].']';
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
                        $v_dt_hr_coleta_forcada = '';
                        foreach ($detalhesClasses as $detalheClasse)
                        {
                            if (empty($arrClassesNames[$detalheClasse['nmClassName']]))
                                $arrClassesNames[$detalheClasse['nmClassName']] = $detalheClasse['nmClassName'];

                            if (($detalheClasse['teWhereClause']) && ($detalheClasse['teWhereClause'] <> 'NULL') && !$arrClassesWhereClauses[$detalheClasse['nmClassName'].'.WhereClause'])
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

                        if (!empty($acao['dtHrColetaForcada']) || $computador->getDtHrColetaForcadaEstacao())
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
                                for($i = 0; $i < count($arrPerfis); $i++ )
                                {
                                    $arrPerfis2 = explode(',',$arrPerfis[$i]);
                                    if ($monitorado["IdAplicativo"]==$arrPerfis2[0] &&
                                        $monitorado["DtAtualizacao"]==$arrPerfis2[1])
                                        $v_achei = 1;
                                }

                                if ($v_achei==0 && ($monitorado["IdSo"] == 0 || $monitorado["IdSo"] == $computador->getIdSo()))
                                {
                                    if ($v_retorno_MONITORADOS <> '') $v_retorno_MONITORADOS .= '#';

                                    $v_te_ide_licenca = trim($monitorado["TeIdeLicenca"]);
                                    if ($monitorado["TeIdeLicenca"]=='0')
                                        $v_te_ide_licenca = '';

                                    $v_retorno_MONITORADOS .= $monitorado["IdAplicativo"]	.	','.
                                        $monitorado["DtAtualizacao"]			.	','.
                                        $monitorado["CsIdeLicenca"] 			. 	','.
                                        $v_te_ide_licenca								.	',';

                                    if (in_array($so->getSgSo(),$arrSgSOtoOlds))
                                    {
                                        $v_te_arq_ver_eng_w9x 	= trim($monitorado["TeArqVerEngW9x"]);
                                        if ($v_te_arq_ver_eng_w9x=='') 	$v_te_arq_ver_eng_w9x 	= '.';

                                        $v_te_arq_ver_pat_w9x 	= trim($monitorado["TeArqVerPatW9x"]);
                                        if ($v_te_arq_ver_pat_w9x=='') 	$v_te_arq_ver_pat_w9x 	= '.';

                                        $v_te_car_inst_w9x 	    = trim($monitorado["TeCarInstW9x"]);
                                        if ($monitorado["TeCarInstW9x"]=='0') 	$v_te_car_inst_w9x 	= '';

                                        $v_te_car_ver_w9x 	    = trim($monitorado["TeCarInstW9x"]);
                                        if ($monitorado["CsCarVerWnt"]=='0') 	$v_te_car_ver_w9x 	= '';

                                        $v_retorno_MONITORADOS .= '.'                                     	.','.
                                            $monitorado["TeCarInstW9x"]	.','.
                                            $v_te_car_inst_w9x						.','.
                                            $$monitorado["CsCarVerWnt"]		.','.
                                            $v_te_car_ver_w9x						.','.
                                            $v_te_arq_ver_eng_w9x					.','.
                                            $v_te_arq_ver_pat_w9x						;
                                    }
                                    else
                                    {

                                        $v_te_arq_ver_eng_wnt 	= trim($monitorado["TeArqVerEngWnt"]);
                                        if ($v_te_arq_ver_eng_wnt=='') 	$v_te_arq_ver_eng_wnt 				= '.';

                                        $v_te_arq_ver_pat_wnt 	= trim($monitorado["TeArqVerPatWnt"]);
                                        if ($v_te_arq_ver_pat_wnt=='') 	$v_te_arq_ver_pat_wnt 				= '.';

                                        $v_te_car_inst_wnt 	    = trim($monitorado["TeCarInstWnt"]);
                                        if ($monitorado["CsCarInstWnt"]=='0') 	$v_te_car_inst_wnt 	= '';

                                        $v_te_car_ver_wnt 	    = trim($monitorado["TeCarVerWnt"]);
                                        if ($monitorado["TeCarInstWnt"]=='0') 	$v_te_car_ver_wnt 	= '';

                                        $v_retorno_MONITORADOS .=   '.'                    					.','.
                                            $monitorado["TeCarInstWnt"]	.','.
                                            $v_te_car_inst_wnt                 		.','.
                                            $$monitorado["TeCarVerWnt"]		.','.
                                            $v_te_car_ver_wnt               		.','.
                                            $v_te_arq_ver_eng_wnt					.','.
                                            $v_te_arq_ver_pat_wnt;

                                    }

                                    $v_retorno_MONITORADOS .=   ',' . $monitorado["InDisponibilizaInfo"];

                                    if ($monitorado["InDisponibilizaInfo"]=='S')
                                    {
                                        $v_retorno_MONITORADOS .= ',' . $monitorado["NmAplicativo"];
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
            $strCollectsDefinitions  = '';
            $strCollectsDefinitions .= '[Actions]' . $strAcoesSelecionadas . '[/Actions]';
        }
        if (!empty($strCollectsDefinitions))
            $strCollectsDefinitions = OldCacicHelper::enCrypt($request, $strCollectsDefinitions);

        $configs = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->listar();

        $redes_versoes_modulos = $this->getDoctrine()->getRepository('CacicCommonBundle:RedeVersaoModulo')->findBy( array( 'idRede'=>$rede->getIdRede() ) );
        foreach($redes_versoes_modulos as $rede_versao_modulo)
        {
            if (!$request->get('AgenteLinux'))
            {
                $versao_modulo = '<'.strtoupper($rede_versao_modulo->getNmModulo() ).'_VER>'.$rede_versao_modulo->getTeVersaoModulo(). '</' . strtoupper($rede_versao_modulo->getNmModulo()) ."_VER>\n";
                $versao_modulo .= '<'.strtoupper($rede_versao_modulo->getNmModulo() ).'_HASH>'. OldCacicHelper::enCrypt( $request, $rede_versao_modulo->getTeVersaoModulo(),true).'</'.strtoupper($rede_versao_modulo->getNmModulo()) .'_HASH>';
            }
            else
            {
                $versao_modulo = '<' . 'TE_PACOTE_PYCACIC_DISPONIVEL>' . $rede_versao_modulo->getNmModulo().'<'."/TE_PACOTE_PYCACIC_DISPONIVEL>\n";
                $versao_modulo .= '<' . 'TE_HASH_PYCACIC>'. $rede_versao_modulo->getTeVersaoModulo().'<'.'/TE_HASH_PYCACIC>';
            }
        }

        if ($request->get('AgenteLinux'))
        {
            $pacote_py = OldCacicHelper::getTest( $request );
            $pacote_py = $pacote_py['te_pacote_PyCACIC'];
            $pacote_py_hash =  OldCacicHelper::getTest( $request );
            $pacote_py_hash = $pacote_py_hash['te_pacote_PyCACIC_HASH'];
        }
        $nm_user_login_updates = OldCacicHelper::enCrypt($request, $rede->getNmUsuarioLoginServUpdates());
        $senha_serv_updates = OldCacicHelper::enCrypt($request, $rede->getTeSenhaLoginServUpdates());

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        return  $this->render('CacicWSBundle:Default:config.xml.twig', array(
            'configs'=>$configs,
            'rede'=> $rede,
            'versao_modulo'=>$versao_modulo,
            'pacote_py'=>$pacote_py,
            'pacote_py_hash'=>$pacote_py_hash,
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
            'rede_grupos_ftp'=>$rede_grupos_ftp
        ), $response);
    }
}