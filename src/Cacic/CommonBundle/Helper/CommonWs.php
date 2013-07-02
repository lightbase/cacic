<?php

namespace Cacic\CommonBundle\Helper;

use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\Rede;
use Cacic\CommonBundle\Helper\Criptografia;
use Cacic\CommonBundle\Helper\TagValue;
use Symfony\Component\HttpFoundation\Request;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Entity\ComputadorColeta;

class CommonWs {

    //Contrutor privado para evitar instanciação da classe.
    private function  __construct(){}

    public function checkSoExistInsetNew( $te_so )
    {
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findBy( array ( 'te_so' => $te_so ) );
        if( empty( $so ) )
        {
            $so = new So();
            $so->setTeSo($te_so);
            $so->setSgSo("Sigla a Cadastrar");
            $so->getTeDescSo("S.O. a Cadastrar");
            $this->getDoctrine()->getManager()->persist( $so );

            $this->getDoctrine()->getManager()->flush();

        }

        return $so;

    }

    public function getDadosPreColeta( Request $request , $te_so , $te_node_adress, $dt_hr_ult_acesso )
    {
        $so = $this->checkSoExistInsetNew( $te_so );
        $id_so= $so['id_so'];

        $ip = explode( '.', $request->request->get('te_ip_computador') );
        $te_ip_rede = $ip[0].$ip[1].$ip[2].".0"; //Pega ip da REDE sendo esse X.X.X.0

        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle: Computador')->findBy( array( 'te_node_adress'=> $te_node_adress, 'id_so'=>$id_so));
        $computador = empty($computador) ? new Computador() : $computador;
        $rede =  $this->getDoctrine()->getRepository('CacicCommonBundle: Rede')->findBy( array( 'te_ip_rede'=> $te_ip_rede ) );

        if( empty($computador['dt_hr_inclusao']) )
        {
            $computador->setTeNodeAddress( $te_node_adress );
            $computador->getIdSo( $id_so );
            $computador->setIdRede( $rede['id_rede'] );
            $computador->setDtHrInclusao( \DateTime('NOW') );
        }

        $computadorColeta = new ComputadorColeta();
        $computadorColeta->setIdComputador( $computador->getIdComputador() );
        $computadorColeta->setTeClassValues( $request->request->get('NetworkAdapterConfiguration') );
        $computadorColeta->setIdClass(
            $this->getDoctrine()->getRepository('CacicCommonBundle: Classe')->findByIdClass( array( 'nm_class_name'=> 'NetworkAdapterConfiguration') )
        );
        $this->getDoctrine()->getManager()->persist( $computadorColeta );

        $computadorColeta = new ComputadorColeta();
        $computadorColeta->setIdComputador( $computador->getIdComputador() );
        $computadorColeta->setTeClassValues( $request->request->get('OperatingSystem') );
        $computadorColeta->setIdClass(
            $this->getDoctrine()->getRepository('CacicCommonBundle: Classe')->findByIdClass( array( 'nm_class_name'=> 'OperatingSystem') )
        );
        $this->getDoctrine()->getManager()->persist( $computadorColeta );

        $computadorColeta = new ComputadorColeta();
        $computadorColeta->setIdComputador( $computador->getIdComputador() );
        $computadorColeta->setTeClassValues( $request->request->get('ComputerSystem') );
        $computadorColeta->setIdClass(
            $this->getDoctrine()->getRepository('CacicCommonBundle: Classe')->findByIdClass( array( 'nm_class_name'=> 'ComputerSystem') )
        );
        $this->getDoctrine()->getManager()->persist( $computadorColeta );

        $computador->setDtHrUltAcesso( \DateTime('NOW') );
        $computador->setTeVersaoCacic( $request->request->get('te_versao_cacic') );
        $computador->setTeVersaoGercols( $request->request->get('te_versao_gercols') );


    }

    public static function autenticaAgente($p_PaddingKey='', Request $request)
    {
        if( ( strtoupper( Criptografia::deCrypt( $request, $request->request->get('HTTP_USER_AGENT') , $request->request->get('cs_cipher'),  $request->request->get('cs_compress') ,$p_PaddingKey , true ) ) != 'AGENTE_CACIC') ||
            ( strtoupper( Criptografia::deCrypt( $request, $request->request->get('PHP_AUTH_USER'  ) , $request->request->get('cs_cipher'),  $request->request->get('cs_compress') ,$p_PaddingKey , true ) ) != 'USER_CACIC') ||
            ( strtoupper( Criptografia::deCrypt( $request, $request->request->get('PHP_AUTH_PW'    ) , $request->request->get('cs_cipher'),  $request->request->get('cs_compress') ,$p_PaddingKey , true ) ) != 'PW_CACIC'))
        {
            echo 'Acesso Não Autorizado.'; // deve ser mostrado no browser //verificar Mensagem padrão de erro no Symfony
        }
    }

    /*
     * Método responsável por retornar TOPO do XML das coletas.
     */
    public static function commonTop( Request $request, $v_compress_level = 0 )
    {
        $v_cs_cipher	= ( trim( $request->request->get('cs_cipher') )   <> '' ? trim( $request->request->get('cs_cipher') )   : '4');
        $v_cs_compress	= ( trim( $request->request->get('cs_compress') ) <> '' ? trim( $request->request->get('cs_compress') ) : '4');

        // O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decriptação/encriptação
        // Valores específicos para trabalho com o PyCACIC - 04 de abril de 2008 - Rogério Lino - Dataprev/ES
        // A versão inicial do agente em Python exige esse complemento na chave...
        $strPaddingKey   = ( $request->request->get('padding_key') ?  $request->request->get('padding_key') : '');
        $boolAgenteLinux = ( trim( $request->request->get('AgenteLinux') ) <> '' ? true : false );

        // Autenticação da chamada:
        self::autenticaAgente( $strPaddingKey, $request );

        $strNetworkAdapterConfiguration  = Criptografia::deCrypt( $request, $request->request->get('NetworkAdapterConfiguration')   , $v_cs_cipher, $v_cs_compress,$strPaddingKey );
        $strComputerSystem  			 = Criptografia::deCrypt( $request, $request->request->get('ComputerSystem')				, $v_cs_cipher, $v_cs_compress,$strPaddingKey );
        $strOperatingSystem  			 = Criptografia::deCrypt( $request, $request->request->get('OperatingSystem')			    , $v_cs_cipher, $v_cs_compress,$strPaddingKey );

        $arrDadosComputador 			 = getDadosComputador(
            TagValue::getValueFromTags('MACAddress', $strNetworkAdapterConfiguration), // reescrever getDadosComputador no Library
            $request->request->get('te_so'),
            TagValue::getValueFromTags('UserName'  , $strComputerSystem)
        );

        $arrDadosRede 					 = getDadosRede($arrDadosComputador[0]['id_rede']); // reescrever getDadosRede no Library

        if ( $request->request->get('te_palavra_chave') )
            $strTePalavraChave = Criptografia::deCrypt( $request, $request->request->get('te_palavra_chave') , $v_cs_cipher,$v_cs_compress,$strPaddingKey );

        // --------------- Retorno de Classificador de CRIPTOGRAFIA --------------------------------------------- //
        if ($v_cs_cipher <> '1') $v_cs_cipher --;
        // Comente/Descomente a linha abaixo para habilitar/desabilitar a criptografia de informa��es trafegadas
        //$v_cs_cipher = '0';
        // ----------------------------------------------------------------------------------------------------- //

        // --------------- Retorno de Classificador de COMPRESS�O ---------------------------------------------- //
        $pos = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate");
        if ( $pos <> -1 && $v_cs_compress <>'1' ) $v_cs_compress -= 1;

        // Caso o n�vel de compress�o sera setado para 0(zero) o indicador deve retornar 0(zero)
        if ( $v_compress_level == '0' ) $v_cs_compress = '0';

        // Comente/Descomente a linha abaixo para habilitar/desabilitar a compacta��o de informa��es trafegadas
        //$v_cs_compress = '0';
        // ----------------------------------------------------------------------------------------------------- //

        $strXML_Begin  	 = 	'<? xml version="1.0" encoding="iso-8859-1" ?><CONFIGS>';
        $strXML_Values 	 = 	'';

        $strTeDebugging	 = 	( TagValue::getValueFromTags('DateToDebugging',$arrDadosComputador[0]['te_debugging'] )  == date("Ymd") ? $arrDadosComputador[0]['te_debugging']  	:
            ( TagValue::getValueFromTags('DateToDebugging',$arrDadosRede[0]['te_debugging_local'] )  == date("Ymd") ?
            $arrDadosRede[0]['te_debugging_local']  	:
            ( TagValue::getValueFromTags('DateToDebugging',$arrDadosRede[0]['te_debugging_subnet'] ) == date("Ymd") ? $arrDadosRede[0]['te_debugging_subnet'] 	: 	'') ) );

        $strXML_Values  .= 	( $strTeDebugging ? '<TeDebugging>' 																										: 	'');
        $strXML_Values  .= 	( $strTeDebugging ? TagValue::getValueFromTags('DetailsToDebugging',$strTeDebugging)																:	'');
        $strXML_Values  .= 	( $strTeDebugging ? '</TeDebugging>' 																									: 	'');

        $strXML_Values  .= 	'<IdComputador>' 		 . 	$arrDadosComputador[0]['id_computador']	. '<'	.	'/IdComputador>';
        $strXML_Values  .= 	'<WebManagerAddress>'     .	$arrDadosRede[0]['te_serv_cacic']		. '<' 	. 	'/WebManagerAddress>';
        $strXML_Values  .= 	'<WebServicesFolderName>' . CACIC_WEB_SERVICES_FOLDER_NAME		    . '<' 	. 	'/WebServicesFolderName>';

        return $strXML_Begin.$strXML_Values;

    }

    /*
     * Método responsável por retornar FIM do XML das coletas.
     */
    public static function commonBottom( Request $request )
    {
        $v_cs_cipher	= ( trim( $request->request->get('cs_cipher') )   <> '' ? trim( $request->request->get('cs_cipher') )   : '4');
        $v_cs_compress	= ( trim( $request->request->get('cs_compress') ) <> '' ? trim( $request->request->get('cs_compress') ) : '4');

        $strXML_Values = self::commonTop( $request ).'<Comm_Status>' . 'OK' . '<'	.	'/Comm_Status>';

        $strXML_Values = str_replace('+','[[MAIS]]'  , $strXML_Values);
        $strXML_Values = str_replace(' ','[[ESPACE]]', $strXML_Values);

        $strXML_End 	 = 	'<cs_compress>'			 . 	$v_cs_compress . '<' 	.	'/cs_compress>';
        $strXML_End 	.= 	'<cs_cipher>'			 . 	$v_cs_cipher   . '<'	.	'/cs_cipher>';
        $strXML_End		.= 	'</CONFIGS>';

        return $strXML_Values . $strXML_End;
    }

}