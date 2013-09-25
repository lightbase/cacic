<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\CommonBundle\Entity\Uorg;
use Cacic\CommonBundle\Form\Type\UorgType;
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
	 * @param Symfony\Component\HttpFoundation\Request $request
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
	 * 
	 * @param int $idUorgPai
	 * @param Symfony\Component\HttpFoundation\Request $request
	 */
	public function cadastrarAction( $idUorgPai, Request $request )
	{
		$uorgPai = null; // Inicializa o UOrgPai
		
		if ( $idUorgPai !== null )
		{ // Caso o idUorgPai seja informado
			$uorgPai = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Uorg' )->find( $idUorgPai );
			if ( ! $uorgPai ) // UOrgPai não é válida
				throw $this->createNotFoundException( 'Unidade Organizacional não encontrada' );
		}
		
		$uorg = new Uorg();
		$uorg->setUorgPai( $uorgPai ); // Relaciona a nova UOrg à UOrgPai
		$form = $this->createForm( new UorgType(), $uorg );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $uorg );
				$this->getDoctrine()->getManager()->flush(); // Efetua o cadastro da Unidade
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect($this->generateUrl('cacic_uorg_index') );
			}
		}
		
		return $this->render(
			'CacicCommonBundle:Uorg:cadastrar.html.twig',
			array( 'form' => $form->createView(), 'uorgPai' => $uorgPai )
		);
	}
	
	/**
	 * 
	 * Tela de edição de Unidade Organizacional
	 * @param int $idUorg
	 * @param Symfony\Component\HttpFoundation\Request $request
	 */
	public function editarAction( $idUorg, Request $request )
	{
		$uorg = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Uorg' )->find( $idUorg );
		if ( ! $uorg ) // UOrg não é válida
				throw $this->createNotFoundException( 'Unidade Organizacional não encontrada' );
				
		$form = $this->createForm( new UorgType(), $uorg );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $uorg );
				$this->getDoctrine()->getManager()->flush(); // Efetua o cadastro da Unidade
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect($this->generateUrl('cacic_uorg_editar', array( 'idUorg'=>$uorg->getIdUorg() ) ) );
			}
		}
		
		return $this->render(
			'CacicCommonBundle:Uorg:cadastrar.html.twig',
			array( 'form' => $form->createView(), 'uorgPai' => $uorg->getUorgPai() )
		);
	}
	
	/**
	 * 
	 * [AJAX][MODAL] Tela de visualização dos dados da UNIDADE parametrizada
	 * @param int $idUorg
	 * @param Symfony\Component\HttpFoundation\Request $request
	 */
	public function visualizarAction( $idUorg, Request $request )
	{
		/*if ( ! $request->isXmlHttpRequest() ) // Verifica se é uma requisição AJAX
			throw $this->createNotFoundException( 'Página não encontrada' );
			*/
		$uorg = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Uorg' )->find( $idUorg );
		if ( ! $uorg ) // UOrg não é válida
				throw $this->createNotFoundException( 'Unidade Organizacional não encontrada' );
		
		return $this->render(
			'CacicCommonBundle:Uorg:visualizar.html.twig',
			array( 'uorg' => $uorg )
		);
	}
	
	/**
	 * 
	 * [AJAX] Remove a UNIDADE ORGANIZACIONAL INFORMADA E TODAS AS UNIDADES A ELA RELACIONADAS
	 * @param int $idUorg
	 * @param Symfony\Component\HttpFoundation\Request $request
	 */
	public function excluirAction( $idUorg, Request $request )
	{
		if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		$uorg = $this->getDoctrine()->getRepository('CacicCommonBundle:Uorg')->find( $request->get('id') );
		if ( ! $uorg )
			throw $this->createNotFoundException( 'Unidade Organizacional não encontrada' );
		
		$em = $this->getDoctrine()->getManager();
		$em->remove( $uorg );
		$em->flush();
		
		$response = new Response( json_encode( array('status' => 'ok') ) );
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
}