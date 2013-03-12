<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cacic\CommonBundle\Entity\Usuario;
use Cacic\CommonBundle\Entity\GrupoUsuario;
use Cacic\CommonBundle\Form\Type\UsuarioType;

class UsuarioController extends Controller
{
	
	/**
	 * 
	 * Listagem dos usuários
	 * @param $page
	 */
	public function indexAction( $page )
	{
		$arrUsuarios = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Usuario' )->listar();
		return $this->render( 'CacicCommonBundle:Usuario:index.html.twig', array( 'usuarios' => $arrUsuarios ) );
	}
    
    /**
     * 
     * Página de alteraçõo de senha
     */
	public function trocarsenhaAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		$usuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuarios')->find( $request->get('id') );
		if ( ! $usuario )
			throw $this->createNotFoundException( 'Usuário não encontrado' );
		
		# Configura a SENHA do usuário já aplicando o Algoritmo definido (ver services.yml)
		$usuario->setTeSenha( hash( $this->container->getParameter('cacic_senha_algorithm'), $request->get('senha') ) );
		
		$em = $this->getDoctrine()->getManager();
		$em->persist( $usuario );
		$em->flush();
		
		$response = new Response( json_encode( array('status' => 'ok') ) );
		$response->headers->set('Content-Type', 'application/json');
		
		return $this->render( 'CacicCommonBundle:Usuario:trocarsenha.html.twig');
	}

    /**
     * Página de Cadastrar novo usuário.
     *
     */
    public function cadastrarAction( Request $request)
    {
		$usuario = new Usuarios();
		$form = $this->createForm( new UsuarioType(), $usuario );

		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			$form->getData()->gerarSenhaAleatoria( $this->container->getParameter('cacic_senha_algorithm'), 8 ); // Gera uma senha aleatória para o novo Usuário
			
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $usuario );
				$this->getDoctrine()->getManager()->flush(); //Persiste os dados do Usuário
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
			
				return $this->redirect( $this->generateUrl( 'cacic_usuario_index') );
			}
		}

        return $this->render(
        	'CacicCommonBundle:Usuario:cadastrar.html.twig',
        	array(
        		'form' => $form->createView(),
        		'grupoDesc' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:GrupoUsuarios' )->listar()
        	)
        );
	}

    /**
     *  Página de editar dados do Usuário
     *  @param int $idusuario
     */
	public function editarAction( $idUsuario, Request $request )
	{
		$usuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuarios')->find( $idUsuario );
		if ( ! $usuario )
			throw $this->createNotFoundException( 'Usuário não encontrado' );
		
		$form = $this->createForm( new UsuarioType(), $usuario );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $usuario );
				$this->getDoctrine()->getManager()->flush();// Efetua a edição do Usuário
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect($this->generateUrl('cacic_usuario_editar', array( 'idUsuario'=>$usuario->getIdUsuario() ) ) );
			}
		}
		
		return $this->render(
        	'CacicCommonBundle:Usuario:cadastrar.html.twig',
        	array(
        		'form' => $form->createView(),
        		'grupoDesc' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:GrupoUsuarios' )->listar()
        	)
        );
	}

    /**
     *  Página de recuperação de senha
     */
	public function recuperarsenhaAction()
	{

	}

    /**
     *
     * [AJAX] Exclusão de Usuarios já cadastrado
     * @param Request $request
     */
	public function excluirAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		$usuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuarios')->find( $request->get('id') );
		if ( ! $usuario )
			throw $this->createNotFoundException( 'Usuário não encontrado' );
		
		$em = $this->getDoctrine()->getManager();
		$em->remove( $usuario );
		$em->flush();
		
		$response = new Response( json_encode( array('status' => 'ok') ) );
		$response->headers->set('Content-Type', 'application/json');

		return $response;
    }
}
