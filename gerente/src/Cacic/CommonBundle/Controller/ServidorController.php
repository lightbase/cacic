<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cacic\CommonBundle\Entity\ServidoresAutenticacao;
use Cacic\CommonBundle\Form\Type\ServidorType;

class ServidorController extends Controller
{
     public function indexAction( $page )
     {
         $arrServidor = $this->getDoctrine()->getRepository( 'CacicCommonBundle:ServidoresAutenticacao' )->listar();
         return $this->render( 'CacicCommonBundle:Servidor:index.html.twig', array( 'servidores' => $arrServidor ) );

     }

    public function cadastrarAction(Request $request)
    {
        $servidor = new ServidoresAutenticacao();
        $form = $this->createForm( new ServidorType(), $servidor );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $servidor );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Usuário

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_servidor_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:Servidor:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *  Página de editar dados do Servidor
     *  @param int $idServidor
     */
    public function editarAction( $idServidorAutenticacao, Request $request )
    {
        $servidor = $this->getDoctrine()->getRepository('CacicCommonBundle:ServidoresAutenticacao')->find( $idServidorAutenticacao );
        if ( ! $servidor )
            throw $this->createNotFoundException( 'Servidor não encontrado' );

        $form = $this->createForm( new ServidorType(), $servidor );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $servidor );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Servidor


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_servidor_editar', array( 'idServidorAutenticacao'=>$servidor->getIdServidorAutenticacao() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:Servidor:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de Servidor já cadastrado
     * @param integer $idServidor
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $servidor = $this->getDoctrine()->getRepository('CacicCommonBundle:ServidoresAutenticacao')->find( $request->get('idServidorAutenticacao') );
        if ( ! $servidor )
            throw $this->createNotFoundException( 'Servidor não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $servidor );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}