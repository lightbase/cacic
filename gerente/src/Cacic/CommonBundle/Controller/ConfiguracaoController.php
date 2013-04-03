<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\ConfiguracoesPadrao;
use Cacic\CommonBundle\Form\Type\ConfiguracaoPadraoType;

/**
 * 
 * CRUD da Entidade Locais
 * @author lightbase
 *
 */
class ConfiguracaoController extends Controller
{
	/**
	 * 
	 * Tela de edição das configurações-padrão do sistema
	 */
	public function padraoAction()
	{
		return $this->render(
        	'CacicCommonBundle:Configuracao:padrao.html.twig',
        	array( 'configuracoes' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:ConfiguracaoPadrao' )->getArrayChaveValor() )
        );
	}
	
	/**
	 * 
	 * [AJAX] Salva a configuração padrão parametrizada via POST
	 */
	public function salvarconfiguracaopadraoAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() )
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		$configuracao = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->findOneBySgVariavel( $request->get('sgVariavel') );
		//Debug::dump($configuracao);die;
		if ( ! $configuracao )
			throw $this->createNotFoundException( 'Configuração não encontrada' );
		
		$em = $this->getDoctrine()->getManager();
		$configuracao->setVlConfiguracao( $request->get('vlConfiguracao') );
		$em->flush();
		
		$response = new Response( json_encode( array('status' => 'ok') ) );
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
	public function agenteAction()
	{
		
	}
	
	public function gerenteAction()
	{
		
	}
	
}
