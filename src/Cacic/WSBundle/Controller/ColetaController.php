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
            $resConfigsLocais = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoPropertyLocal($rede->getIdLocal());
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
            'status'=> 'OK'
        ), $response);
    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
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
     *
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

            //TODO Parei aqui
            $arrTeUsbFilter  = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->findOneBy( array ('te_usb_filter'=> 1, 'idLocal'=>$local->getIdLocal()));
            $arrTeNotificarUtilizacaoUSB = '1';//getArrFromSelect('configuracoes_locais', 'te_notificar_utilizacao_usb','id_local='.$arrDadosRede['id_local']);

            $arrDeviceData = getArrFromSelect('usb_devices', 'id_device,nm_device','trim(id_device)="'.$arrUsbInfo[3].'" AND trim(id_vendor)="'.$arrUsbInfo[2].'"');

            if (trim($arrDeviceData[0]['nm_device'])=='')
            {
                $usb_device = new UsbDevice();
                $usb_device->setIdUsbVendor($arrUsbInfo[2]);
                $usb_device->setIdUsbDevice($arrUsbInfo[3]);
                $usb_device->getNmUsbDevice('Dispositivo USB Desconhecido');
            }

            $arrVendorData = getArrFromSelect('usb_vendors', 'id_vendor,nm_vendor','trim(id_vendor)="'.$arrUsbInfo[2].'"');
            if (trim($arrVendorData[0]['nm_vendor'])=='')
            {
                $usb_vendor = new UsbVendor();
                $usb_vendor->setIdUsbVendor($arrUsbInfo[2]);
                $usb_vendor->setNmUsbVendor('Fabricante de Dispositivos USB Desconhecido');
            }

            if ((trim($arrTeUsbFilter[0]['te_usb_filter'])<>'') && (trim($arrTeNotificarUtilizacaoUSB[0]['te_notificar_utilizacao_usb']) <> ''))
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
                    $strCorpoMail .= " Endere�o IP: ". $computador->getTeIpComputador() . "\n";
                    $strCorpoMail .= " Rede............: ". $rede['nmRede'] ." ('" .$rede['teIpRede']. "')\n";

                    $strCorpoMail .= "\n\nPara visualizar mais informa��es sobre esse computador, acesse o endere�o\nhttp://";
                    $strCorpoMail .= CACIC_PATH . '/relatorios/computador/computador.php?id_computador=' . $computador->getIdComputador();
                    $strCorpoMail .= "\n\n\n________________________________________________\n";
                    $strCorpoMail .= "CACIC - " . date('d/m/Y H:i') . "h \n";
                    $strCorpoMail .= "Desenvolvido pela Dataprev - Unidade Regional Esp�rito Santo";

                    // Manda mail para os administradores.
                    mail($arrTeNotificarUtilizacaoUSB[0]['te_notificar_utilizacao_usb'], "[Sistema CACIC] ".($arrUsbInfo[0] == 'I'?'Inser��o':'Remo��o')." de Dispositivo USB Detectada", "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
                }
            }
            $nm_device = OldCacicHelper::enCrypt($request, '('.$arrVendorData[0]['id_vendor'].')'.$arrVendorData[0]['nm_vendor'].' - (' .$arrDeviceData[0]['id_device'].')'.$arrDeviceData[0]['nm_device']);
        }
        $this->getDoctrine()->getManager()->flush(); //persistencia dos dados no BD

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        return  $this->render('CacicWSBundle:Coleta:setusb.xml.twig', array(
            'status'=> 'OK',
            'nm_device'=>$nm_device
        ), $response);
    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
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

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function srCacicSetSessionAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function srCacicAuthClientAction()
    {

    }


}