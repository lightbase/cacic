<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\Classe;
use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\Rede;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\So;
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
     *  Método responsável por inserir coletas  do Agente CACIC
     *
     */
    public function gerColsSetColletAction( Request $request )
    {

        $resConfigsLocais = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoPropertyLocal('3');
        $resConfigsLocaisEmail = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoEmailLocal('3');

        $arr = explode(',',$resConfigsLocais[0]['vlConfiguracao']);
        $arrClassesAndProperties = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaPorPropertyNotificacao( $arr )  ;

        Debug::dump($arrClassesAndProperties);die;

        //Escrita do post
        $fp = fopen( OldCacicHelper::CACIC_PATH.'web/ws/GERCOLS_SETCOL_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
            $postVal = OldCacicHelper::deCrypt( $request, $postVal );
            fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);

        $resConfigsLocais = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')->listarNotificacaoPorLocal( '1');
        Debug::dump($resConfigsLocais);die;
        OldCacicHelper::autenticaAgente( $request ) ;
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request );
        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $strComputerSystem  			 = OldCacicHelper::deCrypt( $request, $request->get('ComputerSystem') );
        $strOperatingSystem  			 = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem') );

        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);

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

            $arrClassesAndProperties = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listaPorPropertyNotificacao( $resConfigsLocais['vlConfiguracao'] )  ;
//            $arrClassesAndProperties = getArrFromSelect('classes cl,
//					 					   classes_properties cp',
//                'cp.id_property,
//                 cp.nm_property_name,
//                 cp.te_property_description,
//                 cl.nm_class_name',
//                'cp.id_property in (' . $resConfigsLocais[0]['te_notificar_mudancas_properties']. ') AND cl.id_class = cp.id_class');

            for ($intLoopArrClassesAndProperties = 0; $intLoopArrClassesAndProperties < count($arrClassesAndProperties); $intLoopArrClassesAndProperties++)
                $arrClassesPropertiesToNotificate[$arrClassesAndProperties[$intLoopArrClassesAndProperties]['nm_class_name'] . '.' . $arrClassesAndProperties[$intLoopArrClassesAndProperties]['nm_property_name']] = $arrClassesAndProperties[$intLoopArrClassesAndProperties]['te_property_description'];

            $strInsertedItems_Text 	= '';
            $strDeletedItems_Text 	= '';
            $strUpdatedItems_Text 	= '';

            foreach($request as $strClassName => $strClassValues)
            {
                if ($arrClassesNames[$strClassName])
                {
                    $arrOldClassValues = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->findBy( array ('nmClassName'=> $strClassName, 'idComputador' => $computador) );
                    $strNewClassValues = OldCacicHelper::deCrypt($request, $strClassValues);
                    if (($arrOldClassValues['teClassValue'] == '') || ($arrOldClassValues['teClassValue'] <> $strNewClassValues))
                    {
                        $arrNewTagsNames = getTagsFromValues($strNewClassValues);
                        $arrOldTagsNames = getTagsFromValues($arrOldClassValues['teClassValues']);
                        $intReferencial = max(count($arrOldTagsNames),count($arrNewTagsNames));
                        $arrTagsNames   = (count($arrOldTagsNames) > count($arrNewTagsNames) ? $arrOldTagsNames : $arrNewTagsNames);
                        for ($intLoopArrTagsNames = 0; $intLoopArrTagsNames < count($arrTagsNames); $intLoopArrTagsNames ++)
                        {
                            $strOldPropertyValue = getValueFromTags($arrTagsNames[$intLoopArrTagsNames],$arrOldClassValues['teClassValues']);
                            $strNewPropertyValue = getValueFromTags($arrTagsNames[$intLoopArrTagsNames],$strNewClassValues);

                            if ($arrCollectsDefClasses[$strCollectType . '.' . $strClassName . '.' . $arrTagsNames[$intLoopArrTagsNames] . '.nm_function_pre_db'])
                            {
                                OldCacicHelper::gravaTESTES('Achei pre_db');
                                OldCacicHelper::gravaTESTES('1: arrTagsNames['.$intLoopArrTagsNames.'] => ' . $arrTagsNames[$intLoopArrTagsNames]);
                                OldCacicHelper::gravaTESTES('1: strNewClassValues: ' . $strNewClassValues);
                                OldCacicHelper::gravaTESTES('1: getValueFromTags('.$arrTagsNames[$intLoopArrTagsNames].','.$strNewClassValues.'): ' . getValueFromTags($arrTagsNames[$intLoopArrTagsNames],$strNewClassValues));

                                $strNewClassValues = setValueToTags($arrTagsNames[$intLoopArrTagsNames], getValueFromFunction($arrCollectsDefClasses[$strCollectType . '.' . $strClassName . '.' . $arrTagsNames[$intLoopArrTagsNames]],getValueFromTags($arrTagsNames[$intLoopArrTagsNames],$strNewClassValues),$arrCollectsDefClasses[$strCollectType . '.' . $strClassName . '.' . $arrTagsNames[$intLoopArrTagsNames] . '.nm_function_pre_db']), $strNewClassValues);
                                OldCacicHelper::gravaTESTES('2: strNewClassValues: ' . $strNewClassValues);
                            }

                            if ($arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsNames[$intLoopArrTagsNames]])
                            {
                                if 	   ($strNewPropertyValue == '')
                                    $strDeletedItems_Text  .= $arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsNames[$intLoopArrTagsNames]] . chr(13);
                                elseif ($strOldPropertyValue == '')
                                    $strInsertedItems_Text .= $arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsNames[$intLoopArrTagsNames]] . chr(13);
                                else
                                    $strUpdatedItems_Text  .= $arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsNames[$intLoopArrTagsNames]] . chr(13);
                            }
                        }
                        if ((trim($arrOldClassValues['teClassValues']) <> '') && (trim($arrOldClassValues['teClassValues']) <> trim($strNewClassValues)))
                        {
                            OldCacicHelper::gravaTESTES('***********************************************************');
                            OldCacicHelper::gravaTESTES('Inserindo em computadores_collects_historico:');
                            OldCacicHelper::gravaTESTES('getVarType(arrOldClassValues[0][te_class_values]): ' . getVarType($arrOldClassValues[0]['teClassValues']));
                            OldCacicHelper::gravaTESTES('getVarType(strNewClassValues): ' . getVarType($strNewClassValues));
                            OldCacicHelper::gravaTESTES('***********************************************************');
                            $queryINS = "INSERT INTO computadores_collects_historico(id_computador,nm_class_name,te_class_values,dt_hr_inclusao) VALUES (" . $computador->getidComputador() . ",'" . $strClassName . "','" . $arrOldClassValues['teClassValue'] ."',NOW())";
                            mysql_query($queryINS,$DBConnectSC);
                        }

                        if (trim($arrOldClassValues['teClassValues']) <> '')
                        {
                            OldCacicHelper::gravaTESTES('***********************************************************');
                            OldCacicHelper::gravaTESTES('UPDATE em computadores_collects:');
                            OldCacicHelper::gravaTESTES('arrOldClassValues[te_class_value]: ' . $arrOldClassValues['teClassValues']);
                            OldCacicHelper::gravaTESTES('strNewClassValues: ' . $strNewClassValues);
                            OldCacicHelper::gravaTESTES('***********************************************************');

                            // ATEN��O: Registro j� foi criado durante a obten��o das configura��es, no script get_config.php.
                            $queryUPD = "UPDATE computadores_collects SET te_class_values = '" . $strNewClassValues . "' WHERE id_computador = " . $computador->getIdComputador() . " AND nm_class_name = '" . $strClassName . "'";
                            mysql_query($queryUPD,$DBConnectSC);
                        }
                        else
                        {
                            OldCacicHelper::gravaTESTES('***********************************************************');
                            OldCacicHelper::gravaTESTES('INSERT em computadores_collects:');
                            OldCacicHelper::gravaTESTES('arrOldClassValues[0][te_class_values]: ' . $arrOldClassValues['teClassValue']);
                            OldCacicHelper::gravaTESTES('strNewClassValues: ' . $strNewClassValues);
                            OldCacicHelper::gravaTESTES('***********************************************************');

                            // ATEN��O: Registro j� foi criado durante a obten��o das configura��es, no script get_config.php.
                            $queryINS = "INSERT INTO computadores_collects(id_computador,nm_class_name,te_class_values) VALUES (" . $computador->getIdComputador() . ",'" . $strClassName . "','" . $strNewClassValues ."')";
                            mysql_query($queryINS,$DBConnectSC);
                        }
                    }
                }
            }

            // Caso a string acima n�o esteja vazia, monto o email para notifica��o
            if ($strDeletedItems_Text || $strInsertedItems_Text || $strUpdatedItems_Text )
            {
                if ($strDeletedItems_Text)
                    $strDeletedItems_Text 	= chr(13) . 'Itens Removidos:' . chr(13) . $strDeletedItems_Text 	. chr(13);

                if ($strInsertedItems_Text)
                    $strInsertedItems_Text 	= chr(13) . 'Itens Inseridos:' . chr(13) . $strInsertedItems_Text 	. chr(13);

                if ($strUpdatedItems_Text)
                    $strUpdatedItems_Text 	= chr(13) . 'Itens Alterados:' . chr(13) . $strUpdatedItems_Text 	. chr(13);


                $strCorpoMail = '';
                $strCorpoMail .= " Prezado administrador,\n\n";
                $strCorpoMail .= " uma alteração foi identificada no computador cujos detalhes encontram-se abaixo discriminados:\n\n";
                $strCorpoMail .= " Nome do Host: ". getComponentValue($$computador->getIdComputador(), 'ComputerSystem', 'Caption')  ."\n";
                $strCorpoMail .= " Endereço IP....: ". getComponentValue($$computador->getIdComputador(), 'NetworkAdapterConfiguration', 'IPAddress') . "\n";
                $strCorpoMail .= " Local...............: ". $rede['nmLocal']."\n";
                $strCorpoMail .= " Rede................: ". $rede['nmRede'] . ' (' . $rede['teIpRede'] .")\n\n";
                $strCorpoMail .= $strDeletedItems_Text . $strInsertedItems_Text . $strUpdatedItems_Text;
                $strCorpoMail .= "\n\nPara visualizar mais informações sobre esse computador, acesse o endereço\nhttp://";
                $strCorpoMail .= CACIC_PATH . '/relatorios/computador/computador.php?id_computador=' . $$computador->getIdComputador();
                $strCorpoMail .= "\n\n\n________________________________________________\n";
                $strCorpoMail .= "CACIC - e" . date('d/m/Y H:i') . "h \n";

                // Manda mail para os administradores.
                mail($resConfigsLocais['te_notificar_mudancas_emails'], "[Sistema CACIC] Alteração Detectada - " . $arrCollectsDefClasses[$strCollectType], "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
            }
        }
        OldCacicHelper::gravaTESTES('Final');

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
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function gerColsSetUsbDetectAction(Request $request)
    {
        //Escrita do post
        $fp = fopen( OldCacicHelper::CACIC_PATH.'web/ws/SETUSB_'.date('Ymd_His').'.txt', 'w+');
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