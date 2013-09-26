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
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $strComputerSystem  = OldCacicHelper::deCrypt( $request, $request->get('ComputerSystem') );
        $strOperatingSystem  = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem') );
        $data = new \DateTime('NOW');

        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);
        $grava_teste = '';

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findOneBy( array('teSo'=>$te_so) );
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findOneBy( array('idSo'=>$so, 'teNodeAddress'=>$te_node_adress) );
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        //$local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));
        $strCollectType  = OldCacicHelper::deCrypt($request, $request->get('CollectType'));

        // Defino os dois arrays que conterão as configurações para Coletas, Classes e Propriedades
        $arrClassesNames = array();
        $arrCollectsDefClasses 	= array();

        $detalhesClasses = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaDetalhesClasseAcoes($strCollectType);

        // Variável para corrigir o erro do Doctrine
        $className = '';

        foreach ($detalhesClasses as $detalhe)
        {
            // Adiciona classe no Array de classes que estão no banco
            if ($detalhe['nmClassName']) {
                array_push($arrClassesNames, $detalhe['nmClassName']);
            }

            //$arrCollectsDefClasses[$strCollectType] = $detalhesClasse[$strCollectType] == '' ? $detalhesClasse['nmClassName'] : $arrCollectsDefClasses[$strCollectType];
            //$teste1 = $detalhe['nmPropertyName'];
            //$teste2 = $detalhe['idClassProperty'];
            //$teste3 = $detalhe['nmClassName'];

            // Tem que corrigir o erro do Doctrine que não traz o nome da classe para todos os resultados
            if (!empty($detalhe['nmClassName'])) {
                // Vou inserir na variável o valor da classe quando ela não for vazia
                $className = $detalhe['nmClassName'];
            }

            //error_log("444444444444444444444444444444444444444444444444444444 $teste3 | $teste1 | $teste2 | $className");

            // Primeiro cria array com as informações das propriedades
            $property = array(
                'idClassProperty' => $detalhe['idClassProperty'],
                'nmFunctionPreDb' => $detalhe['nmFunctionPreDb']
            );

            // Adiciona as classes no Array geral
            if ($arrCollectsDefClasses[$strCollectType][$className]) {
                // Aqui o array já existe. Só substituo pelo novo valor
                $arrCollectsDefClasses[$strCollectType][$className][$detalhe['nmPropertyName']] = $property;
            } else if ($className) {
                // Aqui adiciona a classe no array
                $arrCollectsDefClasses[$strCollectType][$className] = array();

                // Adiciona as propriedades no array de classes
                $arrCollectsDefClasses[$strCollectType][$className][$detalhe['nmPropertyName']] = $property;
            }

        }

        //$teste = print_r($arrCollectsDefClasses, true);
        //$teste = print_r($arrClassesNames, true);
        //error_log("22222222222222222222222222222222222222222222222222222222222222 $teste");

        if ($arrCollectsDefClasses[$strCollectType])
        {
            // Obtenho configuração para notificação de alterações
            //$resConfigsLocais = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoPropertyLocal($rede->getIdLocal(), 'te_notificar_mudancas_properties');
            //$resConfigsLocaisEmail = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoEmailLocal($rede->getIdLocal());

            //$arrClassesAndProperties = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaPorPropertyNotificacao( $resConfigsLocais->getVlConfiguracao() )  ;

            //foreach ($arrClassesAndProperties as $arrClassesAndProperty)
            //    $arrClassesPropertiesToNotificate[$arrClassesAndProperty['nmClassName'] . '.' . $arrClassesAndProperty['nmPropertyName']] = $arrClassesAndProperty['tePropertyDescription'];

            $strInsertedItems_Text 	= '';
            $strDeletedItems_Text 	= '';
            $strUpdatedItems_Text 	= '';

            //error_log("00000000000000000000000000000000000000000000000000000000: $strCollectType");

            foreach( $request->request->all() as $strClassName => $strClassValues)
            {
                //$teste = OldCacicHelper::deCrypt($request, $strClassValues);
                //error_log("444444444444444444444444444444444444444444444444444444444: $strClassName | \n $teste");
                //error_log("444444444444444444444444444444444444444444444444444444: $strClassName");
                // Aqui executo uma linha para cada atributo definido na coleta


                // Verifico se o atributo sendo verificado é uma classe de coleta.
                // Se for, insiro os dados da coleta no objeto
                if (in_array($strClassName, $arrClassesNames)) {
                    // Descriptografando os valores da requisição
                    $strNewClassValues = OldCacicHelper::deCrypt($request, $strClassValues);

                    //error_log("55555555555555555555555555555555555555555555: Entrei | $strClassName");
                    //error_log("77777777777777777777777777777777777777777777777: Entrei | $strNewClassValues");

                    // A propriedade da coleta de software é multi valorada. Preciso tratar diferente
                    if ($strClassName == "SoftwareList") {
                        //error_log("77777777777777777777777777777777777777777777777: Entrei | $strNewClassValues");
                        //error_log("77777777777777777777777777777777777777777777777: Entrei");

                        // Primeiro preciso pegar todas as tags qure forem software
                        $arrSoftware = TagValueHelper::getSoftwareTags($strNewClassValues);

                        // Agora insere cada registro de software individualmente
                        foreach ($arrSoftware as $software) {
                            // Armazeno todas as propriedades dessa classe enviadas pela requisição
                            $arrTags = TagValueHelper::getTagsFromValues($software);

                            //error_log("6666666666666666666666666666666666666666666666: Encontrei a classe no array $software");

                            // Crio um array multidimensional com as tags e os valores
                            foreach ($arrTags as $tagNames) {
                                //error_log("55555555555555555555555555555555555555555555555: $tagNames");
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
                            //error_log("888888888888888888888888888888888888888888888: $strClassName | $idClassProperty");

                            // Chama função que grava a propriedade
                            $this->gerColsSetProperty('IDSoftware', $software, $idClassProperty, $computador);

                            // Agora gravo todas as propriedades para o software na tabela propriedade_software
                            $classPropertyObject = $this->getDoctrine()->getRepository('CacicCommonBundle:ClassProperty')->findOneBy( array( 'idClassProperty'=> $idClassProperty ) );
                            $propriedadeSoftware = $this->getDoctrine()->getRepository('CacicCommonBundle:PropriedadeSoftware')->findOneBy( array('classProperty'=> $idClassProperty, 'computador' => $computador) );

                            if (empty($propriedadeSoftware)) {
                                $propriedadeSoftware = new PropriedadeSoftware();

                                $propriedadeSoftware->setClassProperty($classPropertyObject);
                                $propriedadeSoftware->setComputador($computador);

                                // Adiciona referência à tabela de softwares
                                $softwareObject = new Software();
                                // Se for fazio coloco o ID Software no nome
                                if (empty($arrTagsNames['DisplayName'])) {
                                    $softwareObject->setNmSoftware($softwareName);
                                } else {
                                    $softwareObject->setNmSoftware($arrTagsNames['DisplayName']);
                                }

                                // Grava no banco de dados
                                $this->getDoctrine()->getManager()->persist($propriedadeSoftware);
                                $this->getDoctrine()->getManager()->persist($softwareObject);
                                $this->getDoctrine()->getManager()->flush();
                            } else {
                                // Ajusta valores coletados
                                $propriedadeSoftware->setDisplayName($arrTagsNames['DisplayName']);
                                $propriedadeSoftware->setDisplayVersion($arrTagsNames['DisplayVersion']);
                                $propriedadeSoftware->setURLInfoAbout($arrTagsNames['URLInfoAbout']);

                                // Salva valor da coleta
                                $this->getDoctrine()->getManager()->persist($propriedadeSoftware);
                                $this->getDoctrine()->getManager()->flush();
                            }

                        }

                    } else {
                        // Armazeno todas as propriedades dessa classe enviadas pela requisição
                        $arrTagsNames = TagValueHelper::getTagsFromValues($strNewClassValues);

                        //error_log("6666666666666666666666666666666666666666666666: Encontrei a classe no array $strNewClassValues");

                        // Agora gravo todas as propriedades dessa classe na tabela de computadores
                        foreach ($arrTagsNames as $classPropertyName) {
                            //error_log("9999999999999999999999999999999999999999999999999999: $classPropertyName");

                            // Pego o Id da classe cadastrada no Banco de Dados para gravar
                            $idClassProperty = $arrCollectsDefClasses[$strCollectType][$strClassName][$classPropertyName]['idClassProperty'];

                            // Caso a propriedade ainda não esteja cadastrada no banco, crio na hora
                            if (empty($idClassProperty)) {
                                error_log("Criando propriedade $classPropertyName para a classe $strClassName");
                                // Pega classe
                                $idClass = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->findOneBy( array('nmClassName'=>$strClassName) );

                                $classPropertyObject = new ClassProperty();
                                $classPropertyObject->setIdClass($idClass);
                                $classPropertyObject->setNmPropertyName($classPropertyName);
                                $classPropertyObject->setTePropertyDescription('On the fly created Property');

                                $this->getDoctrine()->getManager()->persist($classPropertyObject);
                                $this->getDoctrine()->getManager()->flush();

                                // Finalmente adiciono no array de classes e propriedades
                                $idClassProperty = $classPropertyObject->getIdClassProperty();
                                $arrCollectsDefClasses[$strCollectType][$className][$classPropertyName] = $idClassProperty;
                            }
                            //error_log("888888888888888888888888888888888888888888888: $strClassName | $idClassProperty | $classPropertyName");

                            // Chama função que grava a propriedade
                            $this->gerColsSetProperty($classPropertyName, $strNewClassValues, $idClassProperty, $computador);
                        }
                    }
                }
            }

            // Caso a string acima não esteja vazia, monto o email para notificação
            // FIXME: Detectar alteração de dispositivos de hardware e notificar o Administrador
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
        $teste_object = $this->gravaTESTES($grava_teste."\nFinal");
        $em = $this->getDoctrine()->getManager();
        $em->persist($teste_object);

        // Aqui grava tudo
        $em->flush();
        //$this->getDoctrine()->getManager()->flush(); //persistencia dos dados no BD

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
        // pego o valor da classe presente na requisição
        $classProperty = TagValueHelper::getValueFromTags($classPropertyName, $strNewClassValues);

        // Se não encontrar o valor, loga o erro e sai
        if (empty($classProperty)) {
            error_log("ERRO NA COLETA! Propriedade $classPropertyName não encontrada na requisição");
            return;
        }

        //error_log("888888888888888888888888888888888888888888888: $strNewClassValues | $idClassProperty | $classPropertyName | $classProperty");

        // Preparo o objeto da coleta para gravação
        $computadorColeta = $this->getDoctrine()->getRepository('CacicCommonBundle:ComputadorColeta')->findOneBy( array( 'computador'=> $computador, 'classProperty'=>$$idClassProperty ) );
        if (empty($computadorColeta)) {
            // Se não existir nenhuma ocorrência para esse atributo, apenas adiciono
            //error_log("3333333333333333333333333333333333333333333: Criando objeto");
            $computadorColeta = new ComputadorColeta();

            $computadorColeta->setComputador( $computador );

            // Pega o objeto para gravar
            $classPropertyObject = $this->getDoctrine()->getRepository('CacicCommonBundle:ClassProperty')->findOneBy( array( 'idClassProperty'=> $idClassProperty ) );

            if (!$classPropertyObject) {
                error_log("FALHA! Propriedade não encontrada: $idClassProperty");
            }

            // Armazena no banco o objeto
            $computadorColeta->setClassProperty($classPropertyObject);
            $computadorColeta->setTeClassPropertyValue($classProperty);

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
            $computadorColeta->setComputador( $computador );
            // Pega o objeto para gravar
            $classPropertyObject = $this->getDoctrine()->getRepository('CacicCommonBundle:ClassProperty')->findOneBy( array( 'idClassProperty'=> $idClassProperty ) );

            // Armazena no banco o objeto
            $computadorColeta->setClassProperty($classPropertyObject);
            $computadorColeta->setTeClassPropertyValue($classProperty);

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
        }
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

        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findOneBy( array('teSo'=>$te_so) );
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findOneBy( array('idSo'=>$so, 'teNodeAddress'=>$te_node_adress) );
        $local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));

        $te_usb_info = OldCacicHelper::deCrypt($request, $request->get('te_usb_info'));
        if ($te_usb_info <> '')
        {
            $arrUsbInfo = explode('_',$te_usb_info);

            $usb_log = new UsbLog();
            $usb_log->setIdComputador($computador);
            $usb_log->getCsEvent($arrUsbInfo[0]);
            $usb_log->setDtEvent($arrUsbInfo[1]);
            //$usb_log->setIdUsbVendor($arrUsbInfo[2]);
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


}
