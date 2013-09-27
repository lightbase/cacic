<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Aplicativo;
use Cacic\CommonBundle\Form\Type\AplicativoType;

/**
 *
 * CRUD da Entidade  Aplicativos
 * @author lightbase
 *
 */
class AplicativoController extends Controller
{

    /**
     *
     * Tela de listagem
     * @param $page
     */
    public function indexAction( $page )
    {
        return $this->render(
            'CacicCommonBundle:Aplicativo:index.html.twig',
            array( 'aplicativo' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Aplicativo' )->paginar( $this->get( 'knp_paginator' ), $page ))
        );
    }
    public function cadastrarAction(Request $request)
    {
        $aplicativo = new Aplicativo();
        $form = $this->createForm( new AplicativoType(), $aplicativo );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $aplicativo );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do perfis aplicativos monitorados

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_aplicativo_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:Aplicativo:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *  Página de editar dados do Aplicativo
     *  @param int $idAplicativo
     */
    public function editarAction( $idAplicativo, Request $request )
    {
        $aplicativo = $this->getDoctrine()->getRepository('CacicCommonBundle:Aplicativo')->find( $idAplicativo );
        if ( ! $aplicativo )
            throw $this->createNotFoundException( 'Aplicativo não encontrado' );

        $form = $this->createForm( new AplicativoType(), $aplicativo );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $aplicativo );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Perfil Aplicativo Monitorado


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_aplicativo_editar', array( 'idAplicativo'=>$aplicativo->getIdAplicativo() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:Aplicativo:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *
     * [AJAX] Exclusão de Aplicativo já cadastrado
     * @param integer $idAplicativo
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $aplicativo = $this->getDoctrine()->getRepository('CacicCommonBundle:Aplicativo')->find( $request->get('id') );
        if ( ! $aplicativo )
            throw $this->createNotFoundException( 'Aplicativos não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $aplicativo );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}