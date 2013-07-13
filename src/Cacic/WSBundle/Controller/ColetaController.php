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
class ColetasController extends Controller
{
    /**
     *  Método responsável por inserir coletas  do Agente CACIC
     *
     */
    public function gerColsSetColletAction()
    {
        $data = new \DateTime('NOW');
        $request = new Request();

        $coleta = $request->request->get('strFieldsAndValuesToRequest'); //atribuido String coletada a varivel $coleta que será enviado via POST pelo Agente_Cacic
        $te_node_address = TagValue::getValueFromTags( 'MACAddress',TagValue::getClassValue( 'NetworkAdapterConfiguration', $coleta ) ); //extraio MacAdess de coleta para futura compara

        $classes = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->findAll(); //lista de todas classes
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->findBy( array ( 'te_node_address' => $te_node_address ) ); //pesquiso pelo MacAddress e atribuo o resultado a computador
        $computador = empty( $computador ) ? new Computador() : $computador;

        foreach ( $classes as $classe )
        {
            $computador_coleta_historico = new ComputadorColetaHistorico();
            $computador_coleta = $this->getDoctrine()->getRepository('CacicCommonBundle:ComputadorColeta')->findBy( array( 'id' => $computador->getIdComputador() , 'id_class' => $classe->getIdClass() ) ); //procura pelo IdComputador e idClasse
            $computador_coleta = empty( $computador_coleta ) ? new ComputadorColeta() : $computador_coleta; // se o computador não existir sera instanciado um novo Computador()

            //persistindo coleta de computador
            $computador_coleta->setIdClass( $classe->getIdClass() );
            $computador_coleta->setTeClassValues( TagValue::getClassValue( $classe->getNmClassName(), $coleta ) );
            $computador_coleta->setIdComputador( $computador->getIdComputador() );
            $this->getDoctrine()->getManager()->persist( $computador_coleta );

            //persistendo Historico de Coletas
            $computador_coleta_historico->setIdComputador( $computador->getIdComputador() );
            $computador_coleta_historico->setTeClassValues( TagValue::getClassValue( $classe->getNmClassName(), $coleta ) );
            $computador_coleta_historico->setIdComputador( $computador->getIdComputador() );
            $computador_coleta_historico->setDtHrInclusao( $data );
            $this->getDoctrine()->getManager()->persist( $computador_coleta_historico );

            $this->getDoctrine()->getManager()->flush(); //efetua alterações no Banco de Dados

        }


        //persistindo em Computador
        $te_so = TagValue::getTagsFromValues( 'Version' ,TagValue::getClassValue('OperatingSystem', $coleta) ); //extraido da coleta versão do Sitema Operacional
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->findBy( array( 'te_so' => $te_so ) );
        $so = empty( $so ) ? new So() : $so;
//        $computador->set

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function gerColsSetSrcacicAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function gerColsSetUsbDetectAction()
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function mapaCacicAcessoAction()
    {

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



    /*
     * Método responsável por retornar TOPO do XML das coletas.
     */
    protected function commonTop( Request $request, $v_compress_level = 0 )
    {
        $v_cs_cipher	= ( trim( $request->request->get('cs_cipher') )   <> '' ? trim( $request->request->get('cs_cipher') )   : '4');
        $v_cs_compress	= ( trim( $request->request->get('cs_compress') ) <> '' ? trim( $request->request->get('cs_compress') ) : '4');

        // O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decriptação/encriptação
        // Valores específicos para trabalho com o PyCACIC - 04 de abril de 2008 - Rogério Lino - Dataprev/ES
        // A versão inicial do agente em Python exige esse complemento na chave...
        $strPaddingKey   = ( $request->request->get('padding_key') ?  $request->request->get('padding_key') : '');
        $boolAgenteLinux = ( trim( $request->request->get('AgenteLinux') ) <> '' ? true : false );

        // Autenticação da chamada:
        $this->autenticaAgente( $strPaddingKey, $request );

        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->request->get('NetworkAdapterConfiguration')   , $v_cs_cipher, $v_cs_compress,$strPaddingKey );
        $strComputerSystem  			 = OldCacicHelper::deCrypt( $request, $request->request->get('ComputerSystem')				, $v_cs_cipher, $v_cs_compress,$strPaddingKey );
        $strOperatingSystem  			 = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem')			    , $v_cs_cipher, $v_cs_compress,$strPaddingKey );

        $arrDadosComputador 			 = $this->getDadosPreColeta(
            $request,
            TagValue::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration ),
            $request->request->get( 'te_so' ),
            TagValue::getValueFromTags( 'UserName'  , $strComputerSystem)
        );

        $arrDadosRede  = $this->getDadosRedePreColeta( $request , $arrDadosComputador['te_node_adress'] , $arrDadosComputador['id_so'] ); // reescrever getDadosRede no Library

        if ( $request->request->get('te_palavra_chave') )
            $strTePalavraChave = OldCacicHelper::deCrypt( $request, $request->request->get('te_palavra_chave') , $v_cs_cipher,$v_cs_compress,$strPaddingKey );

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

        $strTeDebugging	 = 	( TagValue::getValueFromTags('DateToDebugging',$arrDadosComputador['te_debugging'] )  == date("Ymd") ? $arrDadosComputador['te_debugging']  	:
            ( TagValue::getValueFromTags('DateToDebugging',$arrDadosRede['te_debugging_local'] )  == date("Ymd") ?
                $arrDadosRede['te_debugging_local']  	:
                ( TagValue::getValueFromTags('DateToDebugging',$arrDadosRede['te_debugging_subnet'] ) == date("Ymd") ? $arrDadosRede['te_debugging_subnet'] 	: 	'') ) );

        $strXML_Values  .= 	( $strTeDebugging ? '<TeDebugging>' 																										: 	'');
        $strXML_Values  .= 	( $strTeDebugging ? TagValue::getValueFromTags('DetailsToDebugging',$strTeDebugging)																:	'');
        $strXML_Values  .= 	( $strTeDebugging ? '</TeDebugging>' 																									: 	'');

        $strXML_Values  .= 	'<IdComputador>' 		 . 	$arrDadosComputador['id_computador']	. '<'	.	'/IdComputador>';
        $strXML_Values  .= 	'<WebManagerAddress>'     .	$arrDadosRede['te_serv_cacic']		. '<' 	. 	'/WebManagerAddress>';
        $strXML_Values  .= 	'<WebServicesFolderName>' . CACIC_WEB_SERVICES_FOLDER_NAME		    . '<' 	. 	'/WebServicesFolderName>';

        return $strXML_Begin.$strXML_Values;

    }

    /*
     * Método responsável por retornar FIM do XML das coletas.
     */
    protected function commonBottom( Request $request )
    {
        $v_cs_cipher	= ( trim( $request->request->get('cs_cipher') )   <> '' ? trim( $request->request->get('cs_cipher') )   : '4');
        $v_cs_compress	= ( trim( $request->request->get('cs_compress') ) <> '' ? trim( $request->request->get('cs_compress') ) : '4');

        $strXML_Values = $this->commonTop( $request ).'<Comm_Status>' . 'OK' . '<'	.	'/Comm_Status>';

        $strXML_Values = str_replace('+','[[MAIS]]'  , $strXML_Values);
        $strXML_Values = str_replace(' ','[[ESPACE]]', $strXML_Values);

        $strXML_End 	 = 	'<cs_compress>'			 . 	$v_cs_compress . '<' 	.	'/cs_compress>';
        $strXML_End 	.= 	'<cs_cipher>'			 . 	$v_cs_cipher   . '<'	.	'/cs_cipher>';
        $strXML_End		.= 	'</CONFIGS>';

        return $strXML_Values . $strXML_End;
    }
}