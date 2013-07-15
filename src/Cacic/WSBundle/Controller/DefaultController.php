<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\AcaoSo;
use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\ComputadorColeta;
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
    public function instalaCacicAction( )
    {
        $request = new Request();
       if( $request->isMethod('POST')  )
        {
            $data = new \DateTime('NOW');

            $insucesso =  new InsucessoInstalacao();

            $insucesso->setTeIpComputador( $_SERVER["REMOTE_ADDR"] );
            $insucesso->setTeSo( $request->get('te_so') );
            $insucesso->setIdUsuario( $request->get('id_usuario') );
            $insucesso->setCsIndicador( $request->get('cs_indicador') );
            $insucesso->setDtDatahora( $data  );

            $this->getDoctrine()->getManager()->persist( $insucesso );
            $this->getDoctrine()->getManager()->flush();
        }

    }

    /**
     *  Método responsável por Verificar se houve comunicação com o Agente CACIC
     *
     */
    public function testAction( Request $request )
    {

            // Função para DEBUG
            //\Doctrine\Common\Util\Debug::dump($so); die;
    	// arquivo de debug
        $fp = fopen( OldCacicHelper::CACIC_PATH.'web/ws/get_test_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
        	$postVal = OldCacicHelper::deCrypt( $request, $postVal );
        	fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);
        //

        //OldCacicHelper::autenticaAgente( $request ) ; //Autentica Agente;

        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $strComputerSystem  			 = OldCacicHelper::deCrypt( $request, $request->get('ComputerSystem') );
        // não enviado via post //$strOperatingSystem  			 = Criptografia::deCrypt( $request, $request->request->get('OperatingSystem') );

        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration ); // '08:00:27:A1:4E:59';//
        $te_so = $request->get( 'te_so' ); //'2.5.1.1.256.32'; //
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem); //'CAICIC-2CEAC447\cacic';//


        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );

        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole( $request, $te_so, $te_node_adress );

        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getDadosRedePreColeta( $request  );

        //\Doctrine\Common\Util\Debug::dump($rede); die;

        //$configs = RedeVersaoModulo::getConfig();

        $response = new Response();
		$response->headers->set('Content-Type', 'xml');
		return  $this->render('CacicWSBundle:Default:test.xml.twig', array( 'configs'=> OldCacicHelper::getTest($request),
            'computador' => $computador,
            'rede' => $rede,
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
		//$this->autenticaAgente();
        
        $fp = fopen( OldCacicHelper::CACIC_PATH.'web/ws/get_config_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
        	$postVal = OldCacicHelper::deCrypt( $request, $postVal );
        	fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);
        
        $configs = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->listar();
        
        $response = new Response();
		$response->headers->set('Content-Type', 'xml');
		return  $this->render('CacicWSBundle:Default:config.xml.twig', array('configs'=>$configs), $response);
    }

}