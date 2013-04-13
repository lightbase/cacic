<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Util\Debug;

/**
 * 
 * UNIDADES ORGANIZACIONAIS
 * @author LightBase
 *
 */
class UorgController extends Controller
{
	
	/**
	 * 
	 * Tela de listagem das Unidades Organizacionais cadastradas
	 */
	public function indexAction()
	{
		$treesUorg = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Uorg' )->getPrimeiroNivel(); // Carrega o primeiro nível de UOrgs
		return $this->render( 'CacicCommonBundle:Uorg:index.html.twig', array( 'uorgs' => $treesUorg ) );
	}
	
	/**
	 * 
	 * [AJAX][jqTree] Carrega as Unidades Organizacionais filhas da Unidade Organizacional informada
	 */
	public function loadnodesAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() )
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		$uorgs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Uorg' )->getFolhasDoNo( $request->get( 'idUorgPai' ) );
		
		# Monta um array no formato suportado pelo plugin-in jqTree (JQuery)
		$_tree = array();
		foreach ( $uorgs as $uorg )
		{
			$_tree[] = array(
				'label' 			=> $uorg[0]['nmUorg'],
				'id'				=> $uorg[0]['idUorg'],
				'load_on_demand' 	=> (bool) $uorg['numFilhas']
			);
		}
		
		$response = new Response( json_encode( $_tree ) );
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
	
	/**
	 * 
	 * Tela de cadastro de Unidade Organizacional
	 */
	public function cadastrarAction()
	{
		
	}
	
	/**
	 * 
	 * Tela de edição de Unidade Organizacional
	 */
	public function editarAction( $idUorg )
	{
		
	}
	
}