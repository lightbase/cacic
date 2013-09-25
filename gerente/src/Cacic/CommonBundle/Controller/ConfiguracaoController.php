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
	 * Tela de edição das configurações dos agentes, por local
	 */
	public function agenteAction()
	{
		/**
		 * 
		 * @todo no caso de ser um usuário administrativo, exibir lista com todos os locais cadastrados
		 * @var int
		 */
		$local = $this->getUser()->getIdLocal(); // Recupera o Local da sessão do usuário logado
		
		return $this->render(
        	'CacicCommonBundle:Configuracao:agente.html.twig',
        	array(
        		'configuracoes' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:ConfiguracaoLocal' )->getArrayChaveValor( $local ),
        		'local' => $local
        	)
        );
	}
	
	/**
	 * 
	 * [AJAX] Salva a configuração padrão parametrizada via POST
	 */
	public function salvarconfiguracaoAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() )
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		if ( $request->get('idLocal') )
		{ // No caso de ter sido parametrizado um Local, trata-se de edição do local informado
			/**
			 * @todo Checar se o usuário tem privilégios para alterar o local parametrizado
			 */
			$configuracao = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoLocal')
												->find( array(
															'idConfiguracao' => $request->get('idConfiguracao'),
															'idLocal' => $request->get('idLocal')
														)
												);
		}
		else // ... do contrário, altera a configuração padrão
			$configuracao = $this->getDoctrine()->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->find( $request->get('idConfiguracao') );
		
		if ( ! $configuracao )
			throw $this->createNotFoundException( 'Configuração não encontrada' );
		
		$em = $this->getDoctrine()->getManager();
		$configuracao->setVlConfiguracao( $request->get('vlConfiguracao') );
		$em->flush();
		
		$response = new Response( json_encode( array('status' => 'ok') ) );
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
}
