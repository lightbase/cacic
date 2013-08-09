<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\Classe;
use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\Rede;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Entity\UsbDevice;
use Cacic\CommonBundle\Entity\UsbLog;
use Cacic\CommonBundle\Entity\UsbVendor;
use Cacic\CommonBundle\Helper\Constantes;
use Cacic\CommonBundle\Helper\TagValue;
use Cacic\CommonBundle\Entity\ComputadorColetaHistorico;
use Cacic\WSBundle\Helper\OldCacicHelper;
use Cacic\WSBundle\Helper\TagValueHelper;
use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\CommonBundle\Entity\InsucessoInstalacao;
use Symfony\Component\Validator\Constraints\Date;
use Cacic\CommonBundle\Helper\Criptografia;
use Cacic\CommonBundle\Entity\AcaoSo;
/**
 *
 * Classe responsável por Rerceber as coletas Agente
 * @author lightbase
 *
 */
class ColetaController extends Controller
{
    /**
     *  Método responsável por persistir coletas  do Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function gerColsSetColletAction( Request $request )
    {
        OldCacicHelper::autenticaAgente( $request ) ;
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $strComputerSystem  			 = OldCacicHelper::deCrypt( $request, $request->get('ComputerSystem') );
        $strOperatingSystem  			 = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem') );
        $data = new \DateTime('NOW');

        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);
        $grava_teste = '';

        //vefifica se existe SO coletado se não, insere novo SO
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findOneBy( array('teSo'=>$te_so, 'teNodeAddress'=>$te_node_adress) );
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));
        $strCollectType  = OldCacicHelper::deCrypt($request, $request->get('CollectType'));

        // Defino os dois arrays que conterão as configurações para Coletas, Classes e Propriedades
        $arrClassesNames 		= array();
        $arrCollectsDefClasses 	= array();

        $detalhesClasses = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaDetalhesClasseAcoes($strCollectType);

        foreach ($detalhesClasses as $detalhesClasse)
        {
            $arrClassesNames[$detalhesClasse['nmClassName']] = $detalhesClasse['teClassDescription'];
            $arrCollectsDefClasses[$strCollectType]= ($detalhesClasse[$strCollectType] == '' ? $detalhesClasse['te_descricao_breve'] : $arrCollectsDefClasses[$strCollectType]);
            $arrCollectsDefClasses[$strCollectType . '.' . $detalhesClasse['nmClassName'] . '.' . $detalhesClasse['nmPropertyName']] = $detalhesClasse['idProperty'];
            $arrCollectsDefClasses[$strCollectType . '.' . $detalhesClasse['nmClassName'] . '.' . $detalhesClasse['nmPropertyName'] . '.nm_function_pre_db'] = $detalhesClasse['nmFunctionPreDb'];
        }

        if ($arrCollectsDefClasses[$strCollectType])
        {
            // Obtenho configuração para notificação de alterações
            $resConfigsLocais = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoPropertyLocal($rede->getIdLocal(), 'te_notificar_mudancas_properties');
            $resConfigsLocaisEmail = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoEmailLocal($rede->getIdLocal());

            $arrClassesAndProperties = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaPorPropertyNotificacao( $resConfigsLocais->getVlConfiguracao() )  ;

            foreach ($arrClassesAndProperties as $arrClassesAndProperty)
                $arrClassesPropertiesToNotificate[$arrClassesAndProperty['nmClassName'] . '.' . $arrClassesAndProperty['nmPropertyName']] = $arrClassesAndProperty['tePropertyDescription'];

            $strInsertedItems_Text 	= '';
            $strDeletedItems_Text 	= '';
            $strUpdatedItems_Text 	= '';

            foreach( $request->request->all() as $strClassName => $strClassValues)
            {
                if ($arrClassesNames[$strClassName])
                {
                    $arrOldClassValues = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->findBy( array ('nmClassName'=> $strClassName, 'idComputador' => $computador) );
                    $strNewClassValues = OldCacicHelper::deCrypt($request, $strClassValues);
                    if (($arrOldClassValues['teClassValue'] == '') || ($arrOldClassValues['teClassValue'] <> $strNewClassValues))
                    {
                        $arrNewTagsNames = TagValueHelper::getTagsFromValues($strNewClassValues);
                        $arrOldTagsNames = TagValueHelper::getTagsFromValues($arrOldClassValues['teClassValues']);
                        $arrTagsNames   = (count($arrOldTagsNames) > count($arrNewTagsNames) ? $arrOldTagsNames : $arrNewTagsNames);
                        foreach ($arrTagsNames as $arrTagsName)
                        {
                            $strOldPropertyValue = TagValueHelper::getValueFromTags($arrTagsName,$arrOldClassValues['teClassValues']);
                            $strNewPropertyValue = TagValueHelper::getValueFromTags($arrTagsName,$strNewClassValues);

                            if ($arrCollectsDefClasses[$strCollectType . '.' . $strClassName . '.' . $arrTagsName. '.nm_function_pre_db'])
                            {
                                $grava_teste .= 'Achei pre_db'."\n";
                                $grava_teste .= '1: arrTagsName => ' . $arrTagsName."\n";
                                $grava_teste .= '1: strNewClassValues: ' . $strNewClassValues."\n";
                                $grava_teste .= '1: getValueFromTags('.$arrTagsName.','.$strNewClassValues.'): ' .TagValueHelper::getValueFromTags($arrTagsName,$strNewClassValues)."\n";
                            }

                            if ($arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsName])
                            {
                                if 	   ($strNewPropertyValue == '')
                                    $strDeletedItems_Text  .= $arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsName] . chr(13);
                                elseif ($strOldPropertyValue == '')
                                    $strInsertedItems_Text .= $arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsName] . chr(13);
                                else
                                    $strUpdatedItems_Text  .= $arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsName] . chr(13);
                            }
                        }
                        //inserção de dados na tabela computador_coleta
                        $computadorColeta = $this->getEntityManager()->getRepository('CacicCommonBundle:ComputadorColeta')->findOneBy( array( 'idComputador'=>$computador, 'idClass'=>$arrOldClassValues['idClass'] ) );
                        $computadorColeta = empty( $computadorColeta ) ? new ComputadorColeta() : $computadorColeta ;
                        $computadorColeta->setIdComputador( $computador );
                        $computadorColeta->setTeClassValues( OldCacicHelper::deCrypt( $request, $request->request->get($arrOldClassValues['nmClassName']), true  ) );
                        $computadorColeta->setIdClass( $arrOldClassValues['idClass'] );
                        $this->getEntityManager()->persist( $computadorColeta );
                        $grava_teste .= "3: Persistindo na tabela computador_coleta, id_computador:".$computador->getIdComutador().", class_value: ". $arrOldClassValues['idClass']."\n";
                        // Persistencia de Historico
                        $computadorColetaHistorico = new ComputadorColetaHistorico();
                        $computadorColetaHistorico->setIdClass( $arrOldClassValues['idClass'] );
                        $computadorColetaHistorico->setIdComputadorColeta( $computadorColeta );
                        $computadorColetaHistorico->setIdComputador( $computador );
                        $computadorColetaHistorico->setTeClassValues( OldCacicHelper::deCrypt( $request, $request->request->get($arrOldClassValues['nmClassName']), true  ) );
                        $computadorColetaHistorico->setDtHrInclusao( $data);
                        $this->getEntityManager()->persist( $computadorColetaHistorico );
                        $grava_teste .= "4: Persistindo na tabela computador_coleta_historico, id_computador:".$computador->getIdComutador().", class_value: ". $arrOldClassValues['idClass'].', id_coleta_computador: '.$computadorColeta->getIdComputadorColeta() .', dt_hr_inlcusao: '.$data."\n";
                    }
                }
            }

            // Caso a string acima não esteja vazia, monto o email para notificação
            if ($strDeletedItems_Text || $strInsertedItems_Text || $strUpdatedItems_Text )
            {
                if ($strDeletedItems_Text)
                    $strDeletedItems_Text 	= chr(13) . 'Itens Removidos:' . chr(13) . $strDeletedItems_Text 	. chr(13);

                if ($strInsertedItems_Text)
                    $strInsertedItems_Text 	= chr(13) . 'Itens Inseridos:' . chr(13) . $strInsertedItems_Text 	. chr(13);

                if ($strUpdatedItems_Text)
                    $strUpdatedItems_Text 	= chr(13) . 'Itens Alterados:' . chr(13) . $strUpdatedItems_Text 	. chr(13);


                $strCorpoMail = '';
                $strCorpoMail .= " Prezado Administrador,\n\n";
                $strCorpoMail .= " uma alteração foi identificada no computador cujos detalhes encontram-se abaixo discriminados:\n\n";
                $strCorpoMail .= " Nome do Host: ". $computador->getNmComputador()  ."\n";
                $strCorpoMail .= " Endereço IP....: ".$computador->getTeIpComputador() . "\n";
                $strCorpoMail .= " Local...............: ". $rede['nmLocal']."\n";
                $strCorpoMail .= " Rede................: ". $rede['nmRede'] . ' (' . $rede['teIpRede'] .")\n\n";
                $strCorpoMail .= $strDeletedItems_Text . $strInsertedItems_Text . $strUpdatedItems_Text;
                $strCorpoMail .= "\n\nPara visualizar mais informações sobre esse computador, acesse o endereço\nhttp://";
                $strCorpoMail .= CACIC_PATH . '/relatorios/computador/computador.php?id_computador=' . $computador->getIdComputador();
                $strCorpoMail .= "\n\n\n________________________________________________\n";
                $strCorpoMail .= "CACIC - e" . date('d/m/Y H:i') . "h \n";
// Manda mail para os administradores.
  //TODO              mail($resConfigsLocais['te_notificar_mudancas_emails'], "[Sistema CACIC] Alteração Detectada - " . $arrCollectsDefClasses[$strCollectType], "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
            }
        }
        OldCacicHelper::gravaTESTES($grava_teste."\nFinal");
        $this->getDoctrine()->getManager()->flush(); //persistencia dos dados no BD

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        return  $this->render('CacicWSBundle:Coleta:setcollects.xml.twig', array(
            'configs'=> OldCacicHelper::getTest( $request ),
            'computador' => $computador,
            'rede' => $rede,
            'ws_folder' => OldCacicHelper::CACIC_WEB_SERVICES_FOLDER_NAME,
            'cs_cipher' => $request->get('cs_cipher'),
            'cs_compress' => $request->get('cs_compress'),
            'status'=> 'OK'
        ), $response);
    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function gerColsSetSrcacicAction(Request $request)
    {
        //Escrita do post
        $fp = fopen( OldCacicHelper::CACIC_PATH.'web/ws/SRCACIC_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
            $postVal = OldCacicHelper::deCrypt( $request, $postVal );
            fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);

    }

    /**
     *  Método responsável por informar e coletar informações sobre dispositivos USB plugados na estação
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function gerColsSetUsbDetectAction(Request $request)
    {
        OldCacicHelper::autenticaAgente( $request ) ;
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $data = new \DateTime('NOW');

        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );

        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findOneBy( array('teSo'=>$te_so, 'teNodeAddress'=>$te_node_adress) );
        $local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));

        $te_usb_info = OldCacicHelper::deCrypt($request, $request->get('te_usb_info'));
        if ($te_usb_info <> '')
        {
            $arrUsbInfo = explode('_',$te_usb_info);

            $usb_log = new UsbLog();
            $usb_log->setIdComputador($computador);
            $usb_log->getCsEvent($arrUsbInfo[0]);
            $usb_log->setDtEvent($arrUsbInfo[1]);
            $usb_log->setIdUsbVendor($arrUsbInfo[2]);
            $usb_log->setIdUsbDevice($arrUsbInfo[3]);

            $arrTeUsbFilter  = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoPropertyLocal( $local->getIdLocal() , 'te_usb_filter' );
            $arrTeNotificarUtilizacaoUSB = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoPropertyLocal( $rede->getIdLocal(), 'te_notificar_utilizacao_usb' );

            $arrDeviceData = $this->getDoctrine()->getRepository('CacicCommonBundle:UsbDevice')->findBy( array('idDevice'=>$arrUsbInfo[3], 'idVendor'=>$arrUsbInfo[2] ));

            if (trim($arrDeviceData[0]['nmDevice'])=='')
            {
                $usb_device = new UsbDevice();
                $usb_device->setIdUsbVendor($arrUsbInfo[2]);
                $usb_device->setIdUsbDevice($arrUsbInfo[3]);
                $usb_device->getNmUsbDevice('Dispositivo USB Desconhecido');
            }

            $arrVendorData =  $this->getDoctrine()->getRepository('CacicCommonBundle:UsbVendor  ')->findBy( array('idVendor'=>$arrUsbInfo[2]));
            if (trim($arrVendorData[0]['nmVendor'])=='')
            {
                $usb_vendor = new UsbVendor();
                $usb_vendor->setIdUsbVendor($arrUsbInfo[2]);
                $usb_vendor->setNmUsbVendor('Fabricante de Dispositivos USB Desconhecido');
            }

            if ((trim($arrTeUsbFilter[0]['teUsbFilter'])<>'') && (trim($arrTeNotificarUtilizacaoUSB[0]['teNotificarUtilizacaoUsb']) <> ''))
            {
                $arrUSBfilter = explode('#',$arrTeUsbFilter[0]['te_usb_filter']);
                $strUSBkey    = $arrUsbInfo[2] . "." . $arrUsbInfo[3];
                $indexOf 	  = array_search($strUSBkey,$arrUSBfilter);
                if ($indexOf <> -1)
                {
                    $strCorpoMail = '';
                    $strCorpoMail .= " Prezado administrador,\n\n";
                    $strCorpoMail .= " foi " . ($arrUsbInfo[0] == 'I'?'inserido':'removido'). " o dispositivo '(".$arrVendorData[0]['id_vendor'].")".$arrVendorData[0]['nm_vendor']." / (".$arrDeviceData[0]['id_device'].")".$arrDeviceData[0]['nm_device'].($arrUsbInfo[0] == 'I'?'n':'d')."a esta��o de trabalho abaixo:\n\n";
                    $strCorpoMail .= " Nome...........: ".$computador->getNmComputador() ."\n";
                    $strCorpoMail .= " Endereço IP: ". $computador->getTeIpComputador() . "\n";
                    $strCorpoMail .= " Rede............: ". $rede['nmRede'] ." ('" .$rede['teIpRede']. "')\n";

                    $strCorpoMail .= "\n\nPara visualizar mais informações sobre esse computador, acesse o endereço\nhttp://";
                    $strCorpoMail .= CACIC_PATH . '/relatorios/computador/computador.php?id_computador=' . $computador->getIdComputador();
                    $strCorpoMail .= "\n\n\n________________________________________________\n";
                    $strCorpoMail .= "CACIC - " . date('d/m/Y H:i') . "h \n";

                    // Manda mail para os administradores.
                   //TODO verificar Boas Praticas mail($arrTeNotificarUtilizacaoUSB[0]['te_notificar_utilizacao_usb'], "[Sistema CACIC] ".($arrUsbInfo[0] == 'I'?'Inser��o':'Remo��o')." de Dispositivo USB Detectada", "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
                }
            }
            $nm_device = OldCacicHelper::enCrypt($request, '('.$arrVendorData[0]['id_vendor'].')'.$arrVendorData[0]['nm_vendor'].' - (' .$arrDeviceData[0]['id_device'].')'.$arrDeviceData[0]['nm_device']);
        }
        $this->getDoctrine()->getManager()->flush(); //persistencia dos dados no BD

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        return  $this->render('CacicWSBundle:Coleta:setusb.xml.twig', array(
            'configs'=> OldCacicHelper::getTest( $request ),
            'computador' => $computador,
            'rede' => $rede,
            'ws_folder' => OldCacicHelper::CACIC_WEB_SERVICES_FOLDER_NAME,
            'cs_cipher' => $request->get('cs_cipher'),
            'cs_compress' => $request->get('cs_compress'),
            'status'=> 'OK',
            'nm_device'=>$nm_device
        ), $response);
    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function mapaCacicAcessoAction(Request $request)
    {
        //Escrita do post
        $fp = fopen( OldCacicHelper::CACIC_PATH.'web/ws/MAPA_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
            $postVal = OldCacicHelper::deCrypt( $request, $postVal );
            fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);
        $strXML_Values = '';
        if ( trim(OldCacicHelper::deCrypt($request, $request->get('ModuleProgramName')) == 'mapacacic.exe') )
        {
            if ($request->get('te_operacao') == 'CheckVersion')
            {
                if (file_exists(OldCacicHelper::CACIC_PATH . OldCacicHelper::CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini'))
                {
                    $arrVersionsAndHashes = parse_ini_file(OldCacicHelper::CACIC_PATH . OldCacicHelper::CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');

                    if ($arrVersionsAndHashes['mapacacic.exe_HASH'] <> $request->get('MAPACACIC_EXE_HASH'))
                        $strXML_Values	 	.= '[MAPACACIC.EXE_HASH]'.$arrVersionsAndHashes['mapacacic.exe_HASH'].'[/MAPACACIC.EXE_HASH]';
                }
            }
            elseif ($request->get('te_operacao') == 'Autentication')
            {
                // Autenticação do agente MapaCacic.exe:
                OldCacicHelper::autenticaAgente($request);

                // Essa condição testa se a chamada trouxe o valor de cs_mapa-cacic, enviado por MapaCacic.exe
                if (trim(OldCacicHelper::deCrypt($request,$request->get('ModuleProgramName')) == 'mapacacic.exe'))
                {
//                    $strSelect 	= "	a.id_usuario,
//							a.nm_usuario_completo,
//							a.id_local,
//							a.te_locais_secundarios,
//							c.sg_local";
//
//                    $strFrom	= "	usuarios a,
//							locais c";
//
//                    $strWhere 	= "	(a.id_local = c.id_local OR ('," . $arrDadosRede[0]['id_local'] . ",' in (CONCAT(',',a.te_locais_secundarios,',')))) AND
//							a.nm_usuario_acesso = '". trim(DeCrypt($request->get('nm_acesso'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."' AND
//							a.te_senha = ";

                    // Solução temporária, até total convergência para vers�es 4.0.2 ou maior de MySQL
                    // Anderson Peterle - Dataprev/ES - 04/09/2006
//                    $v_AUTH_SHA1 	 = " SHA1('". trim(DeCrypt($request->get('te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";
//                    $v_AUTH_PASSWORD = " PASSWORD('". trim(DeCrypt($request->get('te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";

                    // Para MySQL 4.0.2 ou maior
                    // Anderson Peterle - Dataprev/ES - 04/09/2006
//                    $arrUsuario = getArrFromSelect( $strFrom,
//                        $strSelect,
//                        $strWhere . $v_AUTH_SHA1);

                    if ($arrUsuario[0]['id_usuario'] == '')
                    {
                        // Para MySQL at� 4.0
                        // Anderson Peterle - Dataprev/ES - 04/09/2006
//                        $arrUsuario = getArrFromSelect( $strFrom,
//                            $strSelect,
//                            $strWhere . $v_AUTH_PASSWORD);
                    }

                    if ($arrUsuario[0]['id_usuario'] <> '')
                    {
//                        $strXML_Values .= '[ID_USUARIO]'			. EnCrypt($arrUsuario[0]['id_usuario'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '[/ID_USUARIO]';
//                        $strXML_Values .= '[NM_USUARIO_COMPLETO]'	. EnCrypt($arrUsuario[0]['nm_usuario_completo'].' ('.$arrUsuario[0]['sg_local'].')',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '[/NM_USUARIO_COMPLETO]';
//                        GravaLog('ACE',$_SERVER['SCRIPT_NAME'],'acesso',$arrUsuario[0]["id_usuario"]);
                    }
                }
            }
        }

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function srCacicSetSessionAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function srCacicAuthClientAction()
    {

    }


}