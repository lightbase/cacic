<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\TipoLicenca;
use Cacic\CommonBundle\Form\Type\TipoLicencaType;


class TipoLicencaController extends Controller
{
    public function indexAction( $page )
    {
        return $this->render(
            'CacicCommonBundle:TipoLicenca:index.html.twig',
            array( 'TipoLicenca' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:TipoLicenca' )
                ->paginar( $this->get( 'knp_paginator' ), $page )
            )
        );

    }
    public function cadastrarAction(Request $request)
    {
        $TipoLicenca = new TipoLicenca();
        $form = $this->createForm( new TipoLicencaType(), $TipoLicenca );
        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $TipoLicenca );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do TipoLicenca

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_tipo_licenca_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:TipoLicenca:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *  Página de editar dados do TipoLicenca
     *  @param int $idTipoLicenca
     */
    public function editarAction( $idTipoLicenca, Request $request )
    {
        $TipoLicenca = $this->getDoctrine()->getRepository('CacicCommonBundle:TipoLicenca')->find( $idTipoLicenca );
        if ( ! $TipoLicenca )
            throw $this->createNotFoundException( 'Tipo Licenca não encontrado' );

        $form = $this->createForm( new TipoLicencaType(), $TipoLicenca );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $TipoLicenca );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do TipoLicenca


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_tipo_licenca_editar', array( 'idTipoLicenca'=>$TipoLicenca->getIdTipoLicenca() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:TipoLicenca:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de Tipo Licenca já cadastrado
     * @param integer $idTipoLicenca
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $TipoLicenca = $this->getDoctrine()->getRepository('CacicCommonBundle:TipoLicenca')->find( $request->get('id') );
        if ( ! $TipoLicenca )
            throw $this->createNotFoundException( 'Tipo Licenca não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $TipoLicenca );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}