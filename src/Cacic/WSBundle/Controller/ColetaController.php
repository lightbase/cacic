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
use Cacic\CommonBundle\Entity\ClassProperty;
use Cacic\CommonBundle\Entity\PropriedadeSoftware;
use Cacic\WSBundle\Helper\OldCacicHelper;
use Cacic\WSBundle\Helper\TagValueHelper;
use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\CommonBundle\Entity\InsucessoInstalacao;
use Symfony\Component\Validator\Constraints\Date;
use Cacic\CommonBundle\Helper\Criptografia;
use Cacic\CommonBundle\Entity\Software;
use Cacic\CommonBundle\Entity\Teste;
use Decoda;

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
        $logger = $this->get('logger');
        //$rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        //$strComputerSystem  = OldCacicHelper::deCrypt( $request, $request->get('ComputerSystem') );
        //$strOperatingSystem  = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem') );
        $data = new \DateTime('NOW');
        $data_inicio = $data->format('d/m/Y H:i:s');
        $data = microtime();
        $logger->debug("%%% Início da operação de coleta: $data_inicio %%%");

        $te_node_address = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );
        //$ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);
        $grava_teste = '';

        // Caso não tenha encontrado, tenta pegar a variável da requisição
        if (empty($te_node_address)) {
            $te_node_address = $request->get('te_node_address');
        }

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findOneBy( array('teSo'=>$te_so) );
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findOneBy( array('idSo'=>$so, 'teNodeAddress'=>$te_node_address) );
        $netmask = TagValueHelper::getValueFromTags( 'IPSubnet', $strNetworkAdapterConfiguration );
        if (empty($netmask)) {
            $netmask = $request->get('netmask');
        }
        $ip_computador = $request->get('te_ip_computador');
        if ( empty($ip_computador) ){
            $ip_computador = TagValueHelper::getValueFromTags( 'IPAddress', $strNetworkAdapterConfiguration );
        }
        if (empty($ip_computador)) {
            $ip_computador = $request->getClientIp();
        }

        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );
        //$local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));
        $strCollectType  = OldCacicHelper::deCrypt($request, $request->get('CollectType'));

        // Defino os dois arrays que conterão as configurações para Coletas, Classes e Propriedades
        $arrClassesNames = array();

        $detalhesClasses = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaDetalhesClasseAcoes($strCollectType);
        $output = $this->arrayClasses($detalhesClasses, $arrClassesNames);

        if ($output) {
            $arrCollectsDefClasses[$strCollectType] = &$output[0];
            $arrClassesNames = $output[1];
        } else {
            $arrCollectsDefClasses[$strCollectType] = array();
        }

        //$teste = print_r($arrCollectsDefClasses, true);
        //$teste = print_r($arrClassesNames, true);
        //$logger->debug("22222222222222222222222222222222222222222222222222222222222222 $teste");

        if ($arrCollectsDefClasses[$strCollectType])
        {
            //error_log("00000000000000000000000000000000000000000000000000000000: $strCollectType");

            foreach( $request->request->all() as $strClassName => $strClassValues)
            {
                // Descriptografando os valores da requisição
                $strNewClassValues = OldCacicHelper::deCrypt($request, $strClassValues);

                //$teste = OldCacicHelper::deCrypt($request, $strClassValues);
                //$logger->debug("444444444444444444444444444444444444444444444444444444444: $strClassName | \n $teste");
                //$logger->debug("444444444444444444444444444444444444444444444444444444444: $strClassName");
                //error_log("44444444444444444444444444444444444444444444444444444: $strClassName");

                //Verifica se é notebook ou não o computador através da bateria
                if ($strClassName == "isNotebook"){
                    $computador->setIsNotebook($strNewClassValues);
                    $this->getDoctrine()->getManager()->persist($computador);
                    $this->getDoctrine()->getManager()->flush();
                }

                // Verifico se o atributo sendo verificado é uma classe de coleta.
                // Se for, insiro os dados da coleta no objeto
                if (in_array($strClassName, $arrClassesNames)) {

                    // A propriedade da coleta de software é multi valorada. Preciso tratar diferente
                    if ($strClassName == "SoftwareList") {

                        $this->coletaSoftware($strNewClassValues, $arrCollectsDefClasses, $strCollectType, $strClassName, $computador);

                    } elseif (!empty($strNewClassValues)) {

                        $this->coletaGeral($strNewClassValues, $arrCollectsDefClasses, $strCollectType, $strClassName, $computador);

                    }
                }
            }
        }
        $teste_object = $this->gravaTESTES($grava_teste."\nFinal");
        $em = $this->getDoctrine()->getManager();
        $em->persist($teste_object);

        // Aqui grava tudo
        $em->flush();
        //$this->getDoctrine()->getManager()->flush(); //persistencia dos dados no BD

        $data_fim = new \DateTime('NOW');
        $tempo = (microtime() - $data);
        $data_fim = $data_fim->format('d/m/Y H:i:s');
        $logger->debug("%%% Final da operação de coleta: $data_fim. Tempo de execução da coleta: $tempo %%%");

        //Verifica se a coleta foi forçada
        if ($computador->getForcaColeta() == 'S') {
            $computador->setForcaColeta('N');
            $this->getDoctrine()->getManager()->persist( $computador );
            $this->getDoctrine()->getManager()->flush();
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        $cacic_helper = new OldCacicHelper( $this->get('kernel') );
        return  $this->render('CacicWSBundle:Coleta:setcollects.xml.twig', array(
            'configs'=> $cacic_helper->getTest( $request ),
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
        $fp = fopen( $this->get('kernel')->getRootDir() .'web/ws/SRCACIC_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
            $postVal = OldCacicHelper::deCrypt( $request, $postVal );
            fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);

    }

    /*
     * Classe que grava a propriedade ou armazena o histórico
     */

    public function gerColsSetProperty($classPropertyName, $strNewClassValues, $idClassProperty, $computador)
    {
        $logger = $this->get('logger');
        $grava_teste = "";
        // pego o valor da classe presente na requisição
        $classProperty = TagValueHelper::getValueFromTags($classPropertyName, $strNewClassValues);

        // Se não encontrar o valor, loga o erro e sai
        if (is_null($classProperty) || $classProperty == "") {
            $logger->debug("ERRO NA COLETA! Propriedade $classPropertyName não encontrada na requisição ou valor vazio");
            return;
        }

        //error_log("888888888888888888888888888888888888888888888: $strNewClassValues | $idClassProperty | $classPropertyName | $classProperty");

        // Preparo o objeto da coleta para gravação
        $computadorColeta = $this->getDoctrine()->getRepository('CacicCommonBundle:ComputadorColeta')->findOneBy( array( 'computador'=> $computador, 'classProperty'=> $idClassProperty ) );
        if (empty($computadorColeta)) {
            // Se não existir nenhuma ocorrência para esse atributo, apenas adiciono
            //error_log("3333333333333333333333333333333333333333333: Criando objeto");
            $computadorColeta = new ComputadorColeta();

            $computadorColeta->setComputador( $computador );

            // Pega o objeto para gravar
            $classPropertyObject = $this->getDoctrine()->getRepository('CacicCommonBundle:ClassProperty')->findOneBy( array( 'idClassProperty'=> $idClassProperty ) );

            if (empty($classPropertyObject)) {
                $logger->error("FALHA! Propriedade não encontrada: $idClassProperty");
            }

            // Armazena no banco o objeto
            $computadorColeta->setClassProperty($classPropertyObject);
            $computadorColeta->setTeClassPropertyValue($classProperty);
            $computadorColeta->setDtHrInclusao( new \DateTime() );

            // Mando salvar os dados do computador
            $this->getDoctrine()->getManager()->persist( $computadorColeta );
            $grava_teste .= "3: Persistindo na tabela computador_coleta, id_computador:".$computador->getIdComputador().", class_value: ". $strNewClassValues."\n";

            // Persistencia de Historico
            $computadorColetaHistorico = new ComputadorColetaHistorico();
            $computadorColetaHistorico->setComputadorColeta( $computadorColeta );
            $computadorColetaHistorico->setComputador( $computador );
            $computadorColetaHistorico->setClassProperty( $classPropertyObject );
            $computadorColetaHistorico->setTeClassPropertyValue($classProperty);
            $computadorColetaHistorico->setDtHrInclusao( new \DateTime() );
            $this->getDoctrine()->getManager()->persist( $computadorColetaHistorico );
            $grava_teste .= "4: Persistindo na tabela computador_coleta_historico, id_computador:".$computador->getIdComputador().", class_value: ". $strNewClassValues.', id_coleta_computador: '.$computadorColeta->getIdComputadorColeta() ."\n";

            // Commit
            $this->getDoctrine()->getManager()->flush();

        } else {
            //error_log("444444444444444444444444444444444444444444444444: Criando histórico");
            // Caso exista, registro um histórico e atualiza o valor atual
            $coletaOld = "Classe WMI: ".$computadorColeta->getClassProperty()->getIdClass()->getNmClassName()." | "."Propriedade: ".$computadorColeta->getClassProperty()->getNmPropertyName()." | Valor: ".$computadorColeta->getTeClassPropertyValue();
            $computadorColeta->setComputador( $computador );
            // Pega o objeto para gravar
            $classPropertyObject = $this->getDoctrine()->getRepository('CacicCommonBundle:ClassProperty')->findOneBy( array( 'idClassProperty'=> $idClassProperty ) );

            // Armazena no banco o objeto
            $computadorColeta->setClassProperty($classPropertyObject);
            $computadorColeta->setTeClassPropertyValue($classProperty);
            $computadorColeta->setDtHrInclusao( new \DateTime() );

            // Mando salvar os dados do computador
            $this->getDoctrine()->getManager()->persist( $computadorColeta );
            $grava_teste .= "3: Persistindo na tabela computador_coleta, id_computador:".$computador->getIdComputador().", class_value: ". $strNewClassValues."\n";

            // Persistencia de Historico
            $computadorColetaHistorico = new ComputadorColetaHistorico();
            $computadorColetaHistorico->setComputadorColeta( $computadorColeta );
            $computadorColetaHistorico->setComputador( $computador );
            $computadorColetaHistorico->setClassProperty( $classPropertyObject );
            $computadorColetaHistorico->setTeClassPropertyValue($classProperty);
            $computadorColetaHistorico->setDtHrInclusao( new \DateTime() );

            $this->getDoctrine()->getManager()->persist( $computadorColetaHistorico );
            $grava_teste .= "4: Persistindo na tabela computador_coleta_historico, id_computador:".$computador->getIdComputador().", class_value: ". $strNewClassValues.', id_coleta_computador: '.$computadorColeta->getIdComputadorColeta();

            // Commit
            $this->getDoctrine()->getManager()->flush();

            // Notifica alteração
            $coletaNew = "Classe WMI: ".$computadorColeta->getClassProperty()->getIdClass()->getNmClassName()." | "."Propriedade: ".$computadorColeta->getClassProperty()->getNmPropertyName()." | Valor: ".$computadorColeta->getTeClassPropertyValue();
            //$this->notificaAlteracao($coletaOld, $coletaNew, $computador);
        }
    }

    /**
     *  Método responsável por informar e coletar informações sobre dispositivos USB plugados na estação
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function gerColsSetUsbDetectAction(Request $request)
    {
        OldCacicHelper::autenticaAgente( $request ) ;
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $data = new \DateTime('NOW');
        $netmask = TagValueHelper::getValueFromTags( 'IPSubnet', $strNetworkAdapterConfiguration );
        $ip_computador = $request->get('te_ip_computador');
        if ( empty($ip_computador) ){
            $ip_computador = TagValueHelper::getValueFromTags( 'IPAddress', $strNetworkAdapterConfiguration );
        }
        if (empty($ip_computador)) {
            $ip_computador = $request->getClientIp();
        }

        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $ip_computador, $netmask );

        $te_node_address = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );

        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findOneBy( array('teSo'=>$te_so) );
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findOneBy( array('idSo'=>$so, 'teNodeAddress'=>$te_node_address) );
        $local = $computador->getIdRede()->getIdLocal();

        $te_usb_info = OldCacicHelper::deCrypt($request, $request->get('te_usb_info'));
        if ($te_usb_info <> '')
        {
            $arrUsbInfo = explode('_',$te_usb_info);

            $arrTeUsbFilter  = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoPropertyLocal( $local->getIdLocal() , 'te_usb_filter' );
            $arrTeNotificarUtilizacaoUSB = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoPropertyLocal( $rede->getIdLocal(), 'te_notificar_utilizacao_usb' );

            $arrVendorData =  $this->getDoctrine()->getRepository('CacicCommonBundle:UsbVendor  ')->findBy( array('idVendor'=>$arrUsbInfo[2]));
            if (empty($arrVendorData))
            {
                $usb_vendor = new UsbVendor();
                $usb_vendor->setIdUsbVendor($arrUsbInfo[2]);
                $usb_vendor->setNmUsbVendor('Fabricante de Dispositivos USB Desconhecido');

                $arrVendorData = $usb_vendor;
            }

            $arrDeviceData = $this->getDoctrine()->getRepository('CacicCommonBundle:UsbDevice')->findBy( array('idDevice'=>$arrUsbInfo[3], 'idVendor'=>$arrUsbInfo[2] ));
            if (empty($arrDeviceData))
            {
                $usb_device = new UsbDevice();
                $usb_device->setIdUsbVendor($arrVendorData);
                $usb_device->setIdDevice($arrUsbInfo[3]);
                $usb_device->setNmUsbDevice('Dispositivo USB Desconhecido');

                $arrDeviceData = $usb_device;
            }

            $usb_log = new UsbLog();
            $usb_log->setIdComputador($computador);
            $usb_log->getCsEvent($arrUsbInfo[0]);
            $usb_log->setDtEvent($arrUsbInfo[1]);
            $usb_log->setIdUsbDevice($arrDeviceData);


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
        $cacic_helper = new OldCacicHelper($this->get('kernel'));
        return  $this->render('CacicWSBundle:Coleta:setusb.xml.twig', array(
            'configs'=> $cacic_helper->getTest( $request ),
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
        $cacic_helper = new OldCacicHelper($this->get('kernel'));
        $fp = fopen( $cacic_helper->getRootDir(). $cacic_helper::CACIC_WEB_SERVICES_FOLDER_NAME .'MAPA_'.date('Ymd_His').'.txt', 'w+');
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

                $iniFile = $cacic_helper->iniFile();
                if (file_exists($iniFile))
                {
                    $arrVersionsAndHashes = parse_ini_file($iniFile);

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

    //________________________________________________________________________________________________
    // Limpa a tabela TESTES, utilizada para depura��o de c�digo
    //________________________________________________________________________________________________

    public function limpaTESTES()
    {
        /* conecta_bd_cacic();
         $queryDEL  = 'DELETE from testes';
         $resultDEL = mysql_query($queryDEL);*/
    }

    //___________________________________
    // Grava informa��es na tabela TESTES
    //___________________________________
    public static function gravaTESTES($p_Valor)
    {
        $v_Valor 		= str_replace('"','[AD]',$p_Valor);
        $v_Valor 		= str_replace("'",'[AS]',$v_Valor);
        $date 			= @getdate();
        $arrScriptName 	= explode('/',$_SERVER['SCRIPT_NAME']);
        $teste = new Teste();
        $teste->setTeLinha($arrScriptName[count($arrScriptName)-1] . ": (".$date['mday'].'/'.$date['mon'].' - '.$date['hours'].':'.$date['minutes'].")Svr " .$_SERVER['HTTP_HOST']." Sta: ".$_SERVER['REMOTE_ADDR']." - ".$v_Valor );

        return $teste;
        //Doctrine\ORM\EntityManager::
        //Doctrine\ORM\EntityManagergetEntityManager()->flush();
    }

    /**
     *  Método responsável por persistir dados do patrimônio
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function mapaCacicSetAction()
    {

    }

    /**
     *  Método responsável por retornar informações do patrimônio
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function mapaCacicGetAction()
    {

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

    /**
     * Cria array de classes e propriedades para coletar
     *
     * @param $detalhesClasses
     * @return array
     */

    public function arrayClasses($detalhesClasses, $arrClassesNames) {
        $logger = $this->get('logger');
        $output = array();

        foreach ($detalhesClasses as $detalhe)
        {
            // Adiciona classe no Array de classes que estão no banco
            if (!in_array($detalhe['nmClassName'], $arrClassesNames)) {
                array_push($arrClassesNames, $detalhe['nmClassName']);
            }
            // Primeiro cria array com as informações das propriedades
            $property = array(
                'idClassProperty' => $detalhe['idClassProperty'],
                'nmFunctionPreDb' => $detalhe['nmFunctionPreDb']
            );


            $nmPropertyName = $detalhe['nmPropertyName'];
            $logger->debug("Adicionando propriedade $nmPropertyName no array de propriedades");
            // Aqui o array já existe. Só substituo pelo novo valor
            $output[0][$detalhe['nmClassName']][$detalhe['nmPropertyName']] = $property;
        }
        $output[1] = $arrClassesNames;

        return $output;
    }

    /**
     * Processa parâmetros da coleta de software
     *
     * @param $strNewClassValues
     * @param $arrCollectsDefClasses
     * @param $strCollectType
     * @param $strClassName
     */

    public function coletaSoftware($strNewClassValues, $arrCollectsDefClasses, $strCollectType, $strClassName, $computador) {
        $logger = $this->get('logger');

        // Primeiro preciso pegar todas as tags que forem software
        $arrSoftware = TagValueHelper::getSoftwareTags($strNewClassValues);

        // Agora insere cada registro de software individualmente
        foreach ($arrSoftware as $software) {
            // Armazeno todas as propriedades dessa classe enviadas pela requisição
            $arrTags = TagValueHelper::getTagsFromValues($software);

            // Crio um array multidimensional com as tags e os valores
            foreach ($arrTags as $tagNames) {
                // Essa função garante que só serão retornados caracteres com UTF8 Válido
                $texto = TagValueHelper::UTF8Sanitize(TagValueHelper::getValueFromTags($tagNames, $software));
                $arrTagsNames[$tagNames] = $texto;
            }

            // Para software, cada identificador será uma propriedade
            $softwareName = $arrTagsNames['IDSoftware'];

            // Remove o IDSoftware do array
            unset($arrTagsNames['IDSoftware']);

            // Armazeno o IDSoftware como Propriedade
            $idClassProperty = $arrCollectsDefClasses[$strCollectType][$strClassName][$softwareName]['idClassProperty'];

            // Se o IDSoftware não existir, cria
            if (empty($idClassProperty)) {
                $logger->debug("Software $softwareName não encontrado. Adicionando um novo software");
                // Pega o Id da classe
                $idClass = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->findOneBy( array('nmClassName'=>$strClassName) );

                $property = new ClassProperty();
                $property->setNmPropertyName($softwareName);
                $property->setTePropertyDescription($arrTagsNames['DisplayName']);

                // Referência à classe
                $property->setIdClass($idClass);

                // Grava a propriedade nova
                $this->getDoctrine()->getManager()->persist($property);
                $this->getDoctrine()->getManager()->flush();

                // Retorna o novo ID
                $idClassProperty = $property->getIdClassProperty();
            }

            // Chama função que grava a propriedade
            $this->gerColsSetProperty('IDSoftware', $software, $idClassProperty, $computador);

            // Agora gravo todas as propriedades para o software na tabela propriedade_software
            $propriedadeSoftware = $this->getDoctrine()->getRepository('CacicCommonBundle:PropriedadeSoftware')->findOneBy( array('classProperty'=> $idClassProperty, 'computador' => $computador) );
            $classPropertyObject = $this->getDoctrine()->getRepository('CacicCommonBundle:ClassProperty')->findOneBy( array( 'idClassProperty'=> $idClassProperty ) );

            if (empty($propriedadeSoftware)) {

                // Se não tiver nome coloco o ID Software no nome
                if (empty($arrTagsNames['DisplayName'])) {
                    $nmSoftware = $softwareName;
                } else {
                    $nmSoftware = $arrTagsNames['DisplayName'];
                }


                $softwareObject = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->findOneBy( array( 'nmSoftware' => $nmSoftware ) );
                if (empty($softwareObject)) {
                    $softwareObject = new Software();
                    // Se não tiver nome coloco o ID Software no nome
                    if (empty($arrTagsNames['DisplayName'])) {
                        $softwareObject->setNmSoftware($softwareName);
                    } else {
                        $softwareObject->setNmSoftware($arrTagsNames['DisplayName']);
                    }

                    // Grava software recém inserido
                    $this->getDoctrine()->getManager()->persist($softwareObject);
                    $this->getDoctrine()->getManager()->flush();
                }

                // Depois adiciono as propriedades
                $propriedadeSoftware = new PropriedadeSoftware();

                $propriedadeSoftware->setClassProperty($classPropertyObject);
                $propriedadeSoftware->setComputador($computador);

                // Ajusta valores coletados
                $propriedadeSoftware->setDisplayName($arrTagsNames['DisplayName']);
                $propriedadeSoftware->setDisplayVersion($arrTagsNames['DisplayVersion']);
                $propriedadeSoftware->setURLInfoAbout($arrTagsNames['URLInfoAbout']);
                $propriedadeSoftware->setSoftware($softwareObject);

                // Grava no banco de dados
                $this->getDoctrine()->getManager()->persist($propriedadeSoftware);
                $this->getDoctrine()->getManager()->flush();
            } else {

                // Se não tiver nome coloco o ID Software no nome
                if (empty($arrTagsNames['DisplayName'])) {
                    $nmSoftware = $softwareName;
                } else {
                    $nmSoftware = $arrTagsNames['DisplayName'];
                }

                // Adiciona referência à tabela de softwares
                $softwareObject = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->findOneBy( array( 'nmSoftware' => $nmSoftware ) );

                if (empty($softwareObject)) {
                    $softwareObject = new Software();
                    // Se não tiver nome coloco o ID Software no nome
                    if (empty($arrTagsNames['DisplayName'])) {
                        $softwareObject->setNmSoftware($softwareName);
                    } else {
                        $softwareObject->setNmSoftware($arrTagsNames['DisplayName']);
                    }

                    // Grava software recém inserido
                    $this->getDoctrine()->getManager()->persist($softwareObject);
                    $this->getDoctrine()->getManager()->flush();
                }

                // Ajusta valores coletados
                $propriedadeSoftware->setDisplayName($arrTagsNames['DisplayName']);
                $propriedadeSoftware->setDisplayVersion($arrTagsNames['DisplayVersion']);
                $propriedadeSoftware->setURLInfoAbout($arrTagsNames['URLInfoAbout']);
                $propriedadeSoftware->setSoftware($softwareObject);

                // Salva valor da coleta
                $this->getDoctrine()->getManager()->persist($propriedadeSoftware);
                $this->getDoctrine()->getManager()->flush();
            }

        }
    }

    /**
     * Processa informações de coleta
     *
     * @param $strNewClassValues
     * @param $arrCollectsDefClasses
     * @param $strCollectType
     * @param $strClassName
     * @param $computador
     */

    public function coletaGeral($strNewClassValues, $arrCollectsDefClasses, $strCollectType, $strClassName, $computador) {
        $logger = $this->get('logger');
        $logger->debug("Processando classe WMI: $strClassName");

        // Armazeno todas as propriedades dessa classe enviadas pela requisição
        $arrTagsNames = TagValueHelper::getTagsFromValues($strNewClassValues);

        // Agora gravo todas as propriedades dessa classe na tabela de computadores
        foreach ($arrTagsNames as $classPropertyName) {
            $logger->debug("Processando a proriedade WMI $classPropertyName para a classe $strClassName");
            // Pega classe
            $idClass = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->findOneBy( array('nmClassName'=>$strClassName) );

            // Caso a propriedade ainda não esteja cadastrada no banco, crio na hora
            if (array_key_exists($classPropertyName, $arrCollectsDefClasses[$strCollectType][$strClassName])) {
                // Somente armazeno o valor que já existe
                $idClassProperty = $arrCollectsDefClasses[$strCollectType][$strClassName][$classPropertyName]['idClassProperty'];
                $logger->debug("Propriedade encontrada: $classPropertyName id_class_property = $idClassProperty. Apenas atualizar");
            } else {
                // Se não existir cria a propriedade
                $logger->info("Criando propriedade $classPropertyName para a classe $strClassName");

                $classPropertyObject = new ClassProperty();
                $classPropertyObject->setIdClass($idClass);
                $classPropertyObject->setNmPropertyName($classPropertyName);
                $classPropertyObject->setTePropertyDescription('On the fly created Property');

                $this->getDoctrine()->getManager()->persist($classPropertyObject);
                $this->getDoctrine()->getManager()->flush();

                // Finalmente adiciono no array de classes e propriedades
                $idClassProperty = $classPropertyObject->getIdClassProperty();
                $property = array(
                    'idClassProperty' => $idClassProperty,
                    'nmFunctionPreDb' => null
                );

                $arrCollectsDefClasses[$strCollectType][$strClassName][$classPropertyName] = $property;
            }
            //error_log("888888888888888888888888888888888888888888888: $strClassName | $idClassProperty | $classPropertyName");

            // Chama função que grava a propriedade
            $this->gerColsSetProperty($classPropertyName, $strNewClassValues, $idClassProperty, $computador);
        }
    }

    /**
     * Envia notificação de alteração para o administrador do sistema
     *
     * @param $coletaOld
     * @param $coletaNew
     * @param $computador
     */

    public function notificaAlteracao($coletaOld, $coletaNew, $computador) {
        $configuracoes = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->findBy( array('idLocal' => $computador->getIdRede()->getIdLocal()) );
        $emailNotificacao = $this->container->getParameter('swiftmailer.sender_address');
        $organizacao = 'Administradores Cacic';

        foreach ($configuracoes as $detalhe) {
            if ($detalhe->getIdConfiguracao() == 'te_notificar_mudanca_hardware') {
                $emailNotificacao = $detalhe->getVlConfiguracao();
            }

            if ($detalhe->getIdConfiguracao() == 'nm_organizacao') {
                $organizacao = $detalhe->getVlConfiguracao();
            }
        }


        $message = \Swift_Message::newInstance()
            ->setSubject("Notificação de alteração de configurações: $organizacao")
            ->setFrom($this->container->getParameter('swiftmailer.sender_address'))
            ->setTo($emailNotificacao)
            ->setBody(
                $this->renderView(
                    'CacicWSBundle:Coleta:alteracaoMail.txt.twig',
                    array(
                        'coletaOld' => $coletaOld,
                        'coletaNew' => $coletaNew,
                        'computador' => $computador
                    )
                )
            )
        ;

        $this->get('mailer')->send($message);
    }

}
