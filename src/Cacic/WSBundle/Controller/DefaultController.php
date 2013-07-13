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
    public function testAction( Request $request )
    {
    	$so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->createIfNotExist( '123456' );
            \Doctrine\Common\Util\Debug::dump($so); die;
    	// arquivo de debug
        $fp = fopen( OldCacicHelper::CACIC_PATH.'web/ws/get_test_'.date('Ymd_His').'.txt', 'w+');
        foreach( $request->request->all() as $postKey => $postVal )
        {
        	$postVal = OldCacicHelper::deCrypt( $request, $postVal );
        	fwrite( $fp, "[{$postKey}]: {$postVal}\n");
        }
        fclose($fp);
        //

        $strNetworkAdapterConfiguration  = OldCacicHelper::deCrypt( $request, $request->request->get('NetworkAdapterConfiguration') );
        $strComputerSystem  			 = OldCacicHelper::deCrypt( $request, $request->request->get('ComputerSystem') );
        // não enviado via post //$strOperatingSystem  			 = Criptografia::deCrypt( $request, $request->request->get('OperatingSystem') );

        $te_node_adress = TagValueHelper::getValueFromTags( 'MACAddress', $strNetworkAdapterConfiguration );
        $te_so = $request->request->get( 'te_so' );
        $ultimo_login = TagValueHelper::getValueFromTags( 'UserName'  , $strComputerSystem);


        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );

        $computador = $this->getComputadorPreCole( $request, $te_node_adress, $te_so, $ultimo_login );

        $rede = $this->getDadosRedePreColeta( $request , $te_node_adress, $so->getIdSo() );

        $configs = RedeVersaoModulo::getConfig();
        
        $response = new Response();
		$response->headers->set('Content-Type', 'xml');
		return  $this->render('CacicWSBundle:Default:test.xml.twig', array( 'configs'=> OldCacicHelper::getTest($request), 'computador' => $computador, 'rede' => $rede  ), $response);
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

    /*
     * Metodo responsável por inserir coletas iniciais, assim que o cacic é instalado
     */
    protected function getDadosPreColeta( Request $request , $te_so , $te_node_adress )
    {
        //recebe dados via POST, deCripata dados, e attribui a variaveis
        $computer_system   = OldCacicHelper::deCrypt( $request, $request->request->get('ComputerSystem'), true  );
        $te_versao_cacic   = OldCacicHelper::deCrypt( $request, $request->request->get('te_versao_cacic'), true  );
        $te_versao_gercols = OldCacicHelper::deCrypt( $request, $request->request->get('te_versao_gercols'), true  );
        $network_adapter   = OldCacicHelper::deCrypt( $request, $request->request->get('NetworkAdapterConfiguration'), true  );
        $operating_system  = OldCacicHelper::deCrypt( $request, $request->request->get('OperatingSystem'), true  );
        $data = new \DateTime('NOW'); //armazena data Atual

        //vefifica se existe SO coletado se não, insere novo SO
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->createIfNotExist( $te_so );
        $id_so= $so->getIdSo();

        $rede = $this->getDadosRedePreColeta( $request , $te_node_adress, $id_so );

        //inserção de dado se for um novo computador
        if( empty($computador['dt_hr_inclusao']) )
        {
            $computador->setTeNodeAddress( $te_node_adress );
            $computador->setIdSo( $id_so );
            $computador->setIdRede( $rede['id_rede'] );
            $computador->setDtHrInclusao( $data);
        }

        //inserção de dados na tabela computador_coleta
        $computadorColeta = new ComputadorColeta();
        $computadorColeta->setIdComputador( $computador->getIdComputador() );
        $computadorColeta->setTeClassValues( $network_adapter );
        $computadorColeta->setIdClass(
            $this->getDoctrine()->getRepository('CacicCommonBundle: Classe')->findBy( array( 'nm_class_name'=> 'NetworkAdapterConfiguration') )
        );
        $this->getDoctrine()->getManager()->persist( $computadorColeta );

        $computadorColeta = new ComputadorColeta();
        $computadorColeta->setIdComputador( $computador->getIdComputador() );
        $computadorColeta->setTeClassValues( $operating_system );
        $computadorColeta->setIdClass(
            $this->getDoctrine()->getRepository('CacicCommonBundle: Classe')->findBy( array( 'nm_class_name'=> 'OperatingSystem') )
        );
        $this->getDoctrine()->getManager()->persist( $computadorColeta );

        $computadorColeta = new ComputadorColeta();
        $computadorColeta->setIdComputador( $computador->getIdComputador() );
        $computadorColeta->setTeClassValues( $computer_system );
        $computadorColeta->setIdClass(
            $this->getDoctrine()->getRepository('CacicCommonBundle: Classe')->findBy( array( 'nm_class_name'=> 'ComputerSystem') )
        );
        $this->getDoctrine()->getManager()->persist( $computadorColeta );


        $computador->setDtHrUltAcesso( $data );
        $computador->setTeVersaoCacic( $te_versao_cacic );
        $computador->setTeVersaoGercols( $te_versao_gercols );
        $computador->setTeUltimoLogin( TagValueHelper::getValueFromTags( 'UserName' ,$computer_system ) );
        $this->getDoctrine()->getManager()->persist( $computador );

        $acoes = $this->getDoctrine()->getRepository('CacicCommonBundle: Acao')->findAll();

        //inserção ações de coleta a nova maquina
        $acao_so = new AcaoSo();
        $acao_so->setRede( $rede->getIdRede() );
        $acao_so->setSo( $so->getIdSo() );
        $acao_so->setAcao( $acoes );

        //persistir dados
        $this->getDoctrine()->getManager()->flush();

        return $computador;
    }

    /*
     * Responsável por autenticação do agente CACIC
     */
    protected function autenticaAgente($p_PaddingKey='', Request $request)
    {
        if( ( strtoupper( OldCacicHelper::deCrypt( $request, $request->request->get('HTTP_USER_AGENT') , true ) ) != 'AGENTE_CACIC') ||
            ( strtoupper( OldCacicHelper::deCrypt( $request, $request->request->get('PHP_AUTH_USER'  ) , true ) ) != 'USER_CACIC') ||
            ( strtoupper( OldCacicHelper::deCrypt( $request, $request->request->get('PHP_AUTH_PW'    ) , true ) ) != 'PW_CACIC'))
        {
            echo ' Acesso Não Autorizado.'; // deve ser mostrado no browser //verificar Mensagem padrão de erro no Symfony
        }
    }

}