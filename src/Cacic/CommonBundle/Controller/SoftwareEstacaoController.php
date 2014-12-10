<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\SoftwareEstacao;
use Cacic\CommonBundle\Form\Type\SoftwareEstacaoType;


class SoftwareEstacaoController extends Controller
{
	/**
	 * 
	 * Tela de listagem dos softwares por estação
	 * @param int $page
	 */
    public function indexAction( $page )
    {
        return $this->render(
            'CacicCommonBundle:SoftwareEstacao:index.html.twig',
            array( 'SoftwareEstacao' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:SoftwareEstacao' )->paginar( $this->get( 'knp_paginator' ), $page ) ));
    }
    
    /**
     * 
     * Tela de cadastro de software por estação
     * @param Request $request
     */
    public function cadastrarAction(Request $request)
    {
        $SoftwareEstacao = new SoftwareEstacao();
        $form = $this->createForm( new SoftwareEstacaoType(), $SoftwareEstacao );
        
   		if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $SoftwareEstacao );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Software Estacao

				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect(
                	$this->generateUrl( 'cacic_software_estacao_index',
                		array(
                			'idComputador' => $SoftwareEstacao->getIdComputador()->getIdComputador()
                		)
                	)
                );
            }
        }

        return $this->render( 'CacicCommonBundle:SoftwareEstacao:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    
    /**
     *  Página de editar dados do Software Estacao
     *  @param int $idComputador
     *  @param int $idSoftware
     */
    public function editarAction( $idComputador, Request $request )
    {
        $SoftwareEstacao = $this->getDoctrine()->getRepository('CacicCommonBundle:SoftwareEstacao')
                    ->find( array( 'idComputador'=>$idComputador ) );
		
        if ( ! $SoftwareEstacao )
            throw $this->createNotFoundException( 'Software de Estacao não encontrado' );

        $form = $this->createForm( new SoftwareEstacaoType(), $SoftwareEstacao );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $SoftwareEstacao );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Software Estacao


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect(
                	$this->generateUrl( 'cacic_software_estacao_editar',
                		array(
                			'idComputador' => $SoftwareEstacao->getIdComputador()->getIdComputador()
                		)
                	)
                );
            }
        }

        return $this->render( 'CacicCommonBundle:SoftwareEstacao:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de Software Estacao já cadastrado
     * @param integer $idSoftwareEstacao
     */
    public function excluirAction( Request $request )
    {

        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $SoftwareEstacao = $this->getDoctrine()->getRepository('CacicCommonBundle:SoftwareEstacao')->find( $request->get('compositeKeys') );
        if ( ! $SoftwareEstacao )
            throw $this->createNotFoundException( 'Software Estação não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $SoftwareEstacao );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
   }

    /**
     *
     * Relatorio de Autorizacoes Cadastradas
     */
    public function autorizacoesAction()
    {
        return $this->render( 'CacicCommonBundle:SoftwareEstacao:autorizacoes.html.twig',
            array(
                'registros' => $this->getDoctrine()->getRepository('CacicCommonBundle:SoftwareEstacao')->gerarRelatorioAutorizacoes()
            )
        );
    }

}