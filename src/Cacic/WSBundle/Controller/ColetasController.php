<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\Classe;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Helper\CommonWs;
use Cacic\CommonBundle\Helper\TagValue;
use Cacic\CommonBundle\Entity\ComputadorColetaHistorico;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\CommonBundle\Entity\InsucessoInstalacao;
use Symfony\Component\Validator\Constraints\Date;
use Cacic\CommonBundle\Helper\Criptografia;
/**
 *
 * Classe responsável por Rerceber as coletas Agente
 * @author lightbase
 *
 */
class ColetasController extends Controller
{
    /**
     *  Método responsável por inserir falhas na instalação do Agente CACIC
     *
     */
    public function instalaCacicAction( )
    {
        $request = new Request();
        if( $request->isMethod('POST')  )
        {
            $data = new \DateTime('NOW');

            $insucesso =  new InsucessoInstalacao();

            $insucesso->setTeIpComputador( $_SERVER["REMOTE_ADDR"] );
            $insucesso->setTeSo( $request->request->get('te_so') );
            $insucesso->setIdUsuario( $request->request->get('id_usuario') );
            $insucesso->setCsIndicador( $request->request->get('cs_indicador') );
            $insucesso->setDtDatahora( $data  );

            $this->getDoctrine()->getManager()->persist( $insucesso );
            $this->getDoctrine()->getManager()->flush();
        }

    }

    /**
     *  Método responsável por Verificar se houve comunicação com o Agente CACIC
     *
     */
    public function getTestAction( ){

        $request = new Request();

        $strXML_Values = CommonWs::commonTop( $request );

        $strPaddingKey   = ( $request->request->get('padding_key') ?  $request->request->get('padding_key') : '');

        if ( file_exists(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini') ) //adptar ao symfony!!!
        {
            $arrVersionsAndHashes = parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');
            $strXML_Values .= '<INSTALLCACIC.EXE_HASH>'	. 	Criptografia::enCrypt( $arrVersionsAndHashes['installcacic.exe_HASH'],
                    $request->request->get('cs_cipher'),
                    $request->request->get('cs_compress') ,
                    $strPaddingKey ,
                    true ) 	. '<' 	. 	'/INSTALLCACIC.EXE_HASH>';
            $strXML_Values .= '<MainProgramName>'  		. 	CACIC_MAIN_PROGRAM_NAME.'.exe'	. '<' 	. 	'/MainProgramName>';
            $strXML_Values .= '<LocalFolderName>' 		. 	CACIC_LOCAL_FOLDER_NAME			. '<' 	. 	'/LocalFolderName>';
        }

        $strXML_Values .= CommonWs::commonBottom( $request );
        return new Response( $strXML_Values);
    }

    /**
     *  Método responsável por inserir coletas  do Agente CACIC
     *
     */
    public function gerColsSetColletAction( )
    {
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
            $computador_coleta_historico->setDtHrInclusao( \DateTime('NOW') );
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
     *  Método responsável por retornar configurações necessarias ao Agente CACIC
     *
     */
    public function getConfigAction( )
    {
        $request = new Request();


    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function gerColsSetSrcacicAction( )
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function gerColsSetUsbDetect( )
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function mapaCacicAcesso( )
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function srCacicSetSession( )
    {

    }

    /**
     *  Método responsável por ************ do Agente CACIC
     *
     */
    public function srCacicAuthClient( )
    {

    }

}