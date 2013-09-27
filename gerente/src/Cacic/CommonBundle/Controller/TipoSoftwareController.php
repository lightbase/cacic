<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\TipoSoftware;
use Cacic\CommonBundle\Form\Type\TipoSoftwareType;


class TipoSoftwareController extends Controller
{
    public function indexAction( $page )
    {
        $arrTipoSoftware = $this->getDoctrine()->getRepository( 'CacicCommonBundle:TipoSoftware' )->paginar( $this->get( 'knp_paginator' ), $page );
        return $this->render( 'CacicCommonBundle:TipoSoftware:index.html.twig', array( 'tipoSoftware' => $arrTipoSoftware ) );

    }
    public function cadastrarAction(Request $request)
    {
        $tipoSoftware = new TipoSoftware();
        $form = $this->createForm( new TipoSoftwareType(), $tipoSoftware );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $tipoSoftware );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do TiposSoftware

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_tiposoftware_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:TipoSoftware:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *  Página de editar dados do TipoSoftware
     *  @param int $idTipoSoftware
     */
    public function editarAction( $idTipoSoftware, Request $request )
    {
        $tipoSoftware = $this->getDoctrine()->getRepository('CacicCommonBundle:TipoSoftware')->find( $idTipoSoftware );
        if ( ! $tipoSoftware )
            throw $this->createNotFoundException( 'Tipo Software não encontrado' );

        $form = $this->createForm( new TipoSoftwareType(), $tipoSoftware );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $tipoSoftware );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do TiposSoftware


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_tiposoftware_editar', array( 'idTipoSoftware'=>$tipoSoftware->getIdTipoSoftware() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:TipoSoftware:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de TiposSoftware já cadastrado
     * @param integer $idTipoSoftware
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $tipoSoftware = $this->getDoctrine()->getRepository('CacicCommonBundle:TipoSoftware')->find( $request->get('id') );
        if ( ! $tipoSoftware )
            throw $this->createNotFoundException( 'Tipo Software não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $tipoSoftware );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}