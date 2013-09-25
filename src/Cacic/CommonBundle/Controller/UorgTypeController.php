<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Cacic\CommonBundle\Entity\TipoUorg;
use Cacic\CommonBundle\Form\Type\UorgTypeType;
use Doctrine\Common\Util\Debug;

/**
 * 
 * UNIDADES ORGANIZACIONAIS
 * @author LightBase
 *
 */
class UorgTypeController extends Controller
{
	
	/**
	 * 
	 * Tela de listagem das Unidades Organizacionais cadastradas
	 */
	public function indexAction()
	{
        $uorgTypes = $this->getDoctrine()->getRepository( 'CacicCommonBundle:TipoUorg' )->findAll(); // Carrega o primeiro nível de UOrgs
		return $this->render( 'CacicCommonBundle:UorgType:index.html.twig', array('uorgTypes' => $uorgTypes) );
	}
	

	
	/**
	 * 
	 * Tela de cadastro de Unidade Organizacional
	 * 
	 * @param int $idUorgPai
	 * @param Symfony\Component\HttpFoundation\Request $request
	 */
	public function cadastrarAction( $idUorgType = null, Request $request )
	{
        if ($idUorgType) {
            $uorgType = $this->getDoctrine()->getRepository( 'CacicCommonBundle:TipoUorg' )->find( $idUorgType );
        } else {
            $uorgType = new TipoUorg();
        }

		$form = $this->createForm( new UorgTypeType(), $uorgType );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $uorgType );
				$this->getDoctrine()->getManager()->flush(); // Efetua o cadastro da Unidade
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect($this->generateUrl('cacic_uorg_type_index') );
			}
		}
		
		return $this->render(
			'CacicCommonBundle:UorgType:cadastrar.html.twig',
			array( 'form' => $form->createView(), 'idUorgType' => $idUorgType )
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
		
		$uorg = $this->getDoctrine()->getRepository('CacicCommonBundle:TipoUorg')->find( $request->get('id') );
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