<?php

namespace Cacic\WSBundle\Controller;

use Cacic\CommonBundle\Entity\AcaoSo;
use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\Rede;
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

        OldCacicHelper::autenticaAgente( $request ) ; //Autentica Agente;

        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->get('NetworkAdapterConfiguration') );
        $strComputerSystem  			 = OldCacicHelper::deCrypt( $request, $request->get('ComputerSystem') );
        $strOperatingSystem  			 = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem') );

        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $te_so = $request->get( 'te_so' );
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );
        $computador = $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->getComputadorPreCole( $request, $te_so, $te_node_adress );
        $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->getPrimeiraRedeValida();//getDadosRedePreColeta( $request );
        $local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->findOneBy(array( 'idLocal' => $rede->getIdLocal() ));

        //Debugging do Agente
        $debugging = (  TagValueHelper::getValueFromTags('DateToDebugging',$computador->getTeDebugging() )  == date("Ymd") ? $computador->getTeDebugging()  	:
            (TagValueHelper::getValueFromTags('DateToDebugging',$local->getTeDebugging() )  == date("Ymd") ? $local->getTeDebugging()  :
            ( TagValueHelper::getValueFromTags('DateToDebugging',$rede->getTeDebugging() )  == date("Ymd") ? $rede->getTeDebugging() :	'') ) );
        $debugging = ( $debugging ? TagValueHelper::getValueFromTags('DetailsToDebugging', $debugging ) : '' );

        $response = new Response();
		$response->headers->set('Content-Type', 'xml');
		return  $this->render('CacicWSBundle:Default:test.xml.twig', array(
            'configs'=> OldCacicHelper::getTest( $request ),
            'computador' => $computador,
            'rede' => $rede,
            'debugging' => $debugging,
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