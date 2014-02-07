<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
		$arrUsuarios = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Usuario' )->paginar( $this->get( 'knp_paginator' ), $page );
		return $this->render( 'CacicCommonBundle:Usuario:index.html.twig', array( 'usuarios' => $arrUsuarios ) );
	}
	
	/**
	 *
	 * Página de alteraçõo da PRÓPRIA senha
	 */
	public function trocarpropriasenhaAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		$usuario = $this->getUser(); // Recupera o usuário logado
		
		$hash = $this->container->getParameter('cacic_senha_algorithm'); // Algoritmo de encripitação da senha
		
		if ( $usuario->getTeSenha() != hash( $hash, $request->get('senha_atual') ) )
		{ // Verifica se a senha atual informada confere
			$response = new Response( json_encode( array('status' => 'Senha atual não confere' ) ), 401 );
			$response->headers->set('Content-Type', 'application/json');
			
			return $response;
		}
		
		# Configura a SENHA do usuário já aplicando o Algoritmo definido (ver services.yml)
		$usuario->setTeSenha( hash( $hash, $request->get('senha_nova') ) );
		
		$em = $this->getDoctrine()->getManager();
		$em->persist( $usuario );
		$em->flush();
		
		$response = new Response( json_encode( array('status' => 'ok') ) );
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
    
    /**
     * 
     * Página de alteraçõo de senha
     */
	public function trocarsenhaAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		$usuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario')->find( $request->get('id') );
		if ( ! $usuario )
			throw $this->createNotFoundException( 'Usuário não encontrado' );
		
		# Configura a SENHA do usuário já aplicando o Algoritmo definido (ver services.yml)
		$usuario->setTeSenha( hash( $this->container->getParameter('cacic_senha_algorithm'), $request->get('senha') ) );
		
		$em = $this->getDoctrine()->getManager();
		$em->persist( $usuario );
		$em->flush();
		
		$response = new Response( json_encode( array('status' => 'ok') ) );
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}

    /**
     * Página de Cadastrar novo usuário.
     *
     */
    public function cadastrarAction( Request $request)
    {
		$usuario = new Usuario();
		$form = $this->createForm( new UsuarioType(), $usuario );

		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			
			# Gera uma senha aleatória para o novo Usuário
			$form->getData()->_gerarSenhaAleatoria( $this->container->getParameter('cacic_senha_algorithm') );
			
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
        		'grupoDesc' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:GrupoUsuario' )->listar()
        	)
        );
	}

    /**
     *  Página de editar dados do Usuário
     *  @param int $idusuario
     */
	public function editarAction( $idUsuario, Request $request )
	{
		$usuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario')->find( $idUsuario );
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
        		'grupoDesc' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:GrupoUsuario' )->listar()
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
		
		$usuario = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario')->find( $request->get('id') );
        $nivelUser = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario')->nivel($usuario);
        $csNivel = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario')->csNivelAdm();

		if ( ! $usuario )
			throw $this->createNotFoundException( 'Usuário não encontrado' );

        if($csNivel[0]["cont"] == 1 && $nivelUser[0]["teGrupoUsuarios"] == "Administração"){
            $this->get('session')->getFlashBag()->add('error', 'Exclusão não permitida, deve haver ao menos um usuario Administrador');
        }else
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove( $usuario );
            $em->flush();

            $response = new Response( json_encode( array('status' => 'ok') ) );
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

    }
    
    /**
     * [TELA] de exibição dos dados do USUÁRIO logado
     */
    public function meusdadosAction()
    {
    	return $this->render(
    			'CacicCommonBundle:Usuario:meusdados.html.twig',
    			array(
    				'usuario' => $this->getUser()
    			)
    	);
    }
}
