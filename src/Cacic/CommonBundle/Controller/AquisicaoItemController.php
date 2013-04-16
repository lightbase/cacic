<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\AquisicaoItem;
use Cacic\CommonBundle\Form\Type\AquisicaoItemType;


class AquisicaoItemController extends Controller
{
    public function indexAction( $page )
    {
        $arrAquisicao = $this->getDoctrine()->getRepository( 'CacicCommonBundle:AquisicaoItem' )->listar();
        return $this->render( 'CacicCommonBundle:AquisicaoItem:index.html.twig', array( 'Aquisicao' => $arrAquisicao ) );

    }
    public function cadastrarAction(Request $request)
    {
        $Aquisicao = new AquisicaoItem();
        $form = $this->createForm( new AquisicaoItemType(), $Aquisicao );
        // $Aquisicao = $dataInicio;
        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $Aquisicao );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Aquisicao

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_aquisicao_item_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:AquisicaoItem:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *  Página de editar dados do Aquisicao
     *  @param int $idAquisicao
     */
    public function editarAction( $id, Request $request )
    {
        $Aquisicao = $this->getDoctrine()->getRepository('CacicCommonBundle:AquisicaoItem')->find( $id );
        if ( ! $Aquisicao )
            throw $this->createNotFoundException( 'Aquisicao não encontrado' );

        $form = $this->createForm( new AquisicaoItemType(), $Aquisicao );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $Aquisicao );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Aquisicao


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_aquisicao_item_editar', array( 'id'=>$Aquisicao->getId() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:AquisicaoItem:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de Aquisicao já cadastrado
     * @param integer $id
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $Aquisicao = $this->getDoctrine()->getRepository('CacicCommonBundle:AquisicaoItem')->find( $request->get('id') );
        if ( ! $Aquisicao )
            throw $this->createNotFoundException( 'Aquisicao não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $Aquisicao );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}