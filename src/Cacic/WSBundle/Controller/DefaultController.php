<?php

namespace Cacic\WSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\CommonBundle\Entity\InsucessoInstalacao;
use Cacic\WSBundle\Helper\Criptografia;

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
    public function getTestAction(){


        $request = new Request();/*
        $common_ws = new CommonWs( );
        $strXML_Values = $common_ws->commonTop( $request );
        $strPaddingKey = $request->request->get('padding_key');

        $strPaddingKey   = empty( $strPaddingKey ) ?  $strPaddingKey : '';*/    $strXML_Values = '<? xml version="1.0" encoding="iso-8859-1" ?>';
        $strXML_Values .= '<Comm_Status>' . 'OK' . '<'	.	'/Comm_Status>';

        if ( file_exists( Constantes::CACIC_PATH . Constantes::CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini') ) //adptar ao symfony!!!
        {
            $arrVersionsAndHashes = parse_ini_file( Constantes::CACIC_PATH . Constantes::CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');
            $strXML_Values .= '<INSTALLCACIC.EXE_HASH>'	. 	Criptografia::enCrypt( $request, $arrVersionsAndHashes['installcacic.exe_HASH'],
                    $request->request->get('cs_cipher'),
                    $request->request->get('cs_compress') ,
                    $strPaddingKey ,
                    true ) 	. '<' 	. 	'/INSTALLCACIC.EXE_HASH>';
            $strXML_Values .= '<MainProgramName>'  		. 	Constantes::CACIC_MAIN_PROGRAM_NAME.'.exe'	. '<' 	. 	'/MainProgramName>';
            $strXML_Values .= '<LocalFolderName>' 		. 	Constantes::CACIC_LOCAL_FOLDER_NAME			. '<' 	. 	'/LocalFolderName>';
        }

        //$strXML_Values .= CommonWs::commonBottom( $request );

        return new Response( $strXML_Values );
    }

    /**
     *  Método responsável por retornar configurações necessarias ao Agente CACIC
     *
     */
    public function configAction( Request $request )
    {
    	/*
        $this->autenticaAgente();
        
        $fp = fopen('/Users/ecio/Sites/cacic/web/ws/get_config_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
        	$postVal = Criptografia();
        	fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);
        */

        $configs = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->listar();
        
        return $this->render('CacicWSBundle:Default:config.xml.twig', array('configs'=>$configs));
    }

    /*
     * Método responsável por coletar verificar dados de rede
     */
    protected function getDadosRedePreColeta( Request $request , $te_node_adress, $id_so )
    {
        //obtem IP da maquina coletada
        $ip_computador = $request->request->get('te_ip_computador');
        $ip_computador = empty( $ip_computador ) ? $_SERVER['REMOTE_ADDR'] : $ip_computador;

        //obtem IP da Rede que a maquina coletada pertence
        $ip = explode( '.', $ip_computador );
        $te_ip_rede = $ip[0].".".$ip[1].".".$ip[2].".0"; //Pega ip da REDE sendo esse X.X.X.0

        //procura computador pelo MACADRESS e SO, se nao existir na base instancio um novo
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle: Computador')->findBy( array( 'te_node_adress'=> $te_node_adress, 'id_so'=>$id_so));
        $computador = empty($computador) ? new Computador() : $computador;
        $rede =  $this->getDoctrine()->getRepository('CacicCommonBundle: Rede')->findBy( array( 'te_ip_rede'=> $te_ip_rede ) ); //procura rede
        $rede = empty( $rede ) ? new Rede() : $rede;  // se rede não existir instancio uma nova rede

        return $rede;
    }

    /*
     * Responsável por autenticação do agente CACIC
     */
    protected function autenticaAgente(Request $request, $p_PaddingKey='')
    {
        if( ( strtoupper( Criptografia::deCrypt( $request, $request->request->get('HTTP_USER_AGENT') , $request->request->get('cs_cipher'),  $request->request->get('cs_compress') ,$p_PaddingKey , true ) ) != 'AGENTE_CACIC') ||
            ( strtoupper( Criptografia::deCrypt( $request, $request->request->get('PHP_AUTH_USER'  ) , $request->request->get('cs_cipher'),  $request->request->get('cs_compress') ,$p_PaddingKey , true ) ) != 'USER_CACIC') ||
            ( strtoupper( Criptografia::deCrypt( $request, $request->request->get('PHP_AUTH_PW'    ) , $request->request->get('cs_cipher'),  $request->request->get('cs_compress') ,$p_PaddingKey , true ) ) != 'PW_CACIC'))
        {
        	throw $this->createNotFoundException( 'Acesso Não Autorizado' ); // deve ser mostrado no browser
        }
    }
}