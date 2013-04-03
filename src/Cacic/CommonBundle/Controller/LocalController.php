<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Local;
use Cacic\CommonBundle\Form\Type\LocalType;

/**
 * 
 * CRUD da Entidade Locais
 * @author lightbase
 *
 */
class LocalController extends Controller
{

    /**
     *
     * Tela de listagem
     * @param $page
     */
    public function indexAction( $page )
    {
    	
        return $this->render(
        	'CacicCommonBundle:Local:index.html.twig',
        	array( 'locais' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Local' )->listar() )
        );
    }
    
	/**
	 * 
	 * Tela de cadastro de novo Local
	 */
	public function cadastrarAction( Request $request )
	{
		$local = new Local();
		$form = $this->createForm( new LocalType(), $local );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $local );
				$this->getDoctrine()->getManager()->flush(); // Persiste os dados do Local
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect( $this->generateUrl( 'cacic_local_index' ) );
			}
		}
		
		return $this->render( 
			'CacicCommonBundle:Local:cadastrar.html.twig',
			array( 'form' => $form->createView() )
		);
	}
	
	/**
	 * 
	 * Tela de edição de Local já cadastrado
	 * @param integer $idLocal
	 */
	public function editarAction( $idLocal, Request $request )
	{
		$local = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Local' )->find( $idLocal );
		if ( ! $local )
			throw $this->createNotFoundException( 'Local não encontrado' );
		
		$form = $this->createForm( new LocalType(), $local );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $local );
				$this->getDoctrine()->getManager()->flush(); // Efetua a edição do Local
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect($this->generateUrl('cacic_local_editar', array( 'idLocal'=>$local->getIdLocal() ) ) );
			}
		}
		
		return $this->render(
			'CacicCommonBundle:Local:editar.html.twig',
			array( 'form' => $form->createView() )
		);
	}
	
	/**
	 * 
	 * [AJAX] Exclusão de Local já cadastrado
	 * @param integer $idLocal
	 */
	public function excluirAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() )
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		$local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->find( $request->get('id') );
		if ( ! $local )
			throw $this->createNotFoundException( 'Local não encontrado' );
		
		$em = $this->getDoctrine()->getManager();
		$em->remove( $local );
		$em->flush();
		
		$response = new Response( json_encode( array('status' => 'ok') ) );
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
	/**
	 * 
	 * [GRID] Redes associadas ao Local
	 */
	public function redesAction( $idLocal )
	{
		return $this->render(
        	'CacicCommonBundle:Local:redes.html.twig',
        	array( 'redes' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Rede' )->listarPorLocal( $idLocal ) )
        );
	}
	
	/**
	 * 
	 * [GRID] Usuários associados ao Local
	 */
	public function usuariosAction( $idLocal )
	{
		return $this->render(
        	'CacicCommonBundle:Local:usuarios.html.twig',
        	array(
        		'usuarios' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Usuario' )->listarPorLocal( $idLocal ),
        		'idLocal' => $idLocal
        	)
        );
	}
	
	/**
	 * 
	 * [FORM] Configurações associadas ao Local
	 */
	public function configuracoesAction( $idLocal )
	{
		return $this->render(
        	'CacicCommonBundle:Local:configuracoes.html.twig',
        	array(
        		'configuracoes' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:ConfiguracaoLocal' )->listarPorLocal( $idLocal ),
        		'idLocal' => $idLocal
        	)
        );
	}
	
}