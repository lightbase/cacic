<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cacic\CommonBundle\Entity\ServidorAutenticacao;
use Cacic\CommonBundle\Form\Type\ServidorAutenticacaoType;

class ServidorAutenticacaoController extends Controller
{
     public function indexAction( $page )
     {
         $arrServidor = $this->getDoctrine()->getRepository( 'CacicCommonBundle:ServidorAutenticacao' )->paginar( $this->get( 'knp_paginator' ), $page );
         return $this->render( 'CacicCommonBundle:ServidorAutenticacao:index.html.twig', array( 'servidor' => $arrServidor ) );

     }

    public function cadastrarAction(Request $request)
    {
        $servidor = new ServidorAutenticacao();
        $form = $this->createForm( new ServidorAutenticacaoType(), $servidor );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $servidor );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Usuário

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_servidorautenticacao_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:ServidorAutenticacao:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *  Página de editar dados do ServidorAutenticacao
     *  @param int $idServidor
     */
    public function editarAction( $idServidorAutenticacao, Request $request )
    {
        $servidor = $this->getDoctrine()->getRepository('CacicCommonBundle:ServidorAutenticacao')->find( $idServidorAutenticacao );
        if ( ! $servidor )
            throw $this->createNotFoundException( 'Servidor Autenticacao não encontrado' );

        $form = $this->createForm( new ServidorAutenticacaoType(), $servidor );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $servidor );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do ServidorAutenticacao


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_servidorautenticacao_editar', array( 'idServidorAutenticacao'=>$servidor->getIdServidorAutenticacao() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:ServidorAutenticacao:editar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de ServidorAutenticacao já cadastrado
     * @param integer $idServidor
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $servidor = $this->getDoctrine()->getRepository('CacicCommonBundle:ServidorAutenticacao')->find( $request->get('id') );
        if ( ! $servidor )
            throw $this->createNotFoundException( 'Servidor Autenticacao não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $servidor );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     *
     * [GRID] Redes associadas ao Servidores de Autenticação
     */
    public function redesAction( $idServidorAutenticacao )
    {
        return $this->render(
            'CacicCommonBundle:ServidorAutenticacao:redes.html.twig',
            array( 'rede' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Rede' )->listarPorServidorAutenticacao( $idServidorAutenticacao ) )
        );
    }

}