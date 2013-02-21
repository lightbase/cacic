<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Form\Type\SoType;


class SoController extends Controller
{
    public function indexAction( $page )
    {
        $arrso = $this->getDoctrine()->getRepository( 'CacicCommonBundle:So' )->listar();
        return $this->render( 'CacicCommonBundle:So:index.html.twig', array( 'So' => $arrso ) );

    }
    public function cadastrarAction(Request $request)
    {
        $so = new So();
        $form = $this->createForm(new SoType(), $so);

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $so );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do sistema operacional

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_sistemaoperacional_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:So:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *  Página de editar dados do sistema operacional
     *  @param int $idSo
     */
    public function editarAction( $idSo, Request $request )
    {
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->find( $idSo );
        if ( ! $so )
            throw $this->createNotFoundException( 'sistema operacional não encontrado' );
        $form = $this->createForm( new SoType(), $so);

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $so );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do sistema operacional


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_sistemaoperacional_editar', array( 'idSo'=>$so->getIdSo() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:So:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de sistema operacional já cadastrado
     * @param integer $idSo
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $So = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->find( $request->get('id') );
        if ( ! $So )
            throw $this->createNotFoundException( ' sistema operacional não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $So );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}