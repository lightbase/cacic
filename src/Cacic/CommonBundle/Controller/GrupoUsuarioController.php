<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\GrupoUsuario;
use Cacic\CommonBundle\Form\Type\GrupoUsuarioType;
use Doctrine\Common\Util\Debug;

/**
 *
 * CRUD da Entidade  Grupo de Usuario
 * @author lightbase
 *
 */
class GrupoUsuarioController extends Controller
{

    /**
     *
     * Tela de listagem
     * @param $page
     */
    public function indexAction( $page )
    {
        return $this->render(
            'CacicCommonBundle:GrupoUsuario:index.html.twig',
            array( 'GrupoUsuario' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:GrupoUsuario' )->paginar( $this->get( 'knp_paginator' ), $page ))
        );
    }
    public function cadastrarAction(Request $request)
    {
        $GrupoUsuario = new GrupoUsuario();
        $form = $this->createForm( new GrupoUsuarioType(), $GrupoUsuario );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $GrupoUsuario );
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_grupo_usuario_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:GrupoUsuario:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *  Página de editar dados do Grupo de Usuarios
     *  @param int $idGrupoUsuario
     */
    public function editarAction( $idGrupoUsuario, Request $request )
    {
        $GrupoUsuario = $this->getDoctrine()->getRepository('CacicCommonBundle:GrupoUsuario')->find( $idGrupoUsuario );
        if ( ! $GrupoUsuario )
            throw $this->createNotFoundException( 'Grupo de Usuario não encontrado' );

        $form = $this->createForm( new GrupoUsuarioType(), $GrupoUsuario );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $GrupoUsuario );
                $this->getDoctrine()->getManager()->flush();


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_grupo_usuario_editar', array( 'idGrupoUsuario'=>$GrupoUsuario->getIdGrupoUsuario() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:GrupoUsuario:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *
     * [AJAX] Exclusão de Grupo de Usuario já cadastrado
     * @param integer $idGrupoUsuario
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
        throw $this->createNotFoundException( 'Página não encontrada' );

        $GrupoUsuario = $this->getDoctrine()->getRepository('CacicCommonBundle:GrupoUsuario')->find( $request->get('id') );
        if ( ! $GrupoUsuario )
            throw $this->createNotFoundException( 'Grupo de Usuario não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $GrupoUsuario );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}