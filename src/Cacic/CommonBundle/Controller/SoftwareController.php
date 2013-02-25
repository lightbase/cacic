<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Softwares;
use Cacic\CommonBundle\Form\Type\SoftwareType;


class SoftwareController extends Controller
{
    public function indexAction( $page )
    {
        $arrSoftware = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Softwares' )->listar();
        return $this->render( 'CacicCommonBundle:Software:index.html.twig', array( 'Software' => $arrSoftware ) );

    }
    public function cadastrarAction(Request $request)
    {
        $Software = new Softwares();
        $form = $this->createForm( new SoftwareType(), $Software );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $Software );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Software

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_software_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:Software:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *  Página de editar dados do Software
     *  @param int $idSoftware
     */
    public function editarAction( $idSoftware, Request $request )
    {
        $Software = $this->getDoctrine()->getRepository('CacicCommonBundle:Softwares')->find( $idSoftware );
        if ( ! $Software )
            throw $this->createNotFoundException( 'Software não encontrado' );

        $form = $this->createForm( new SoftwareType(), $Software );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $Software );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Software


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_software_editar', array( 'idSoftware'=>$Software->getIdSoftware() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:Software:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de Software já cadastrado
     * @param integer $idSoftware
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $tipoSoftware = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->find( $request->get('id') );
        if ( ! $tipoSoftware )
            throw $this->createNotFoundException( 'Software não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $tipoSoftware );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}