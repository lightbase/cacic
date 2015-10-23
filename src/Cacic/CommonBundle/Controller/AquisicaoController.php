<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Aquisicao;
use Cacic\CommonBundle\Form\Type\AquisicaoType;


class AquisicaoController extends Controller
{
    public function indexAction( $page )
    {
        return $this->render(
            'CacicCommonBundle:Aquisicao:index.html.twig',
            array(
                'Aquisicao' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Aquisicao' )->paginar( $this->get( 'knp_paginator' ), $page ),
                'idioma' => $this->getRequest()->getLocale()
            )
        );

    }
    public function cadastrarAction(Request $request)
    {
        $Aquisicao = new Aquisicao();
        $form = $this->createForm( new AquisicaoType(), $Aquisicao );
        $locale = $request->getLocale();

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );


            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $Aquisicao );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Aquisicao

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_aquisicao_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:Aquisicao:cadastrar.html.twig',
            array(
                'idioma'=> $locale,
                'form' => $form->createView() ) );
    }
    /**
     *  Página de editar dados do Aquisicao
     *  @param int $idAquisicao
     */
    public function editarAction( $idAquisicao, Request $request )
    {
        $Aquisicao = $this->getDoctrine()->getRepository('CacicCommonBundle:Aquisicao')->find( $idAquisicao );
        if ( ! $Aquisicao )
            throw $this->createNotFoundException( 'Aquisicao não encontrado' );

        $form = $this->createForm( new AquisicaoType(), $Aquisicao );
        $locale = $request->getLocale();

        if ( $request->isMethod('POST') )
        {
           // Debug::dump($form["dtAquisicao"]);die;
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $Aquisicao );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Aquisicao


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_aquisicao_editar', array( 'idAquisicao'=>$Aquisicao->getIdAquisicao() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:Aquisicao:cadastrar.html.twig', array('idioma'=> $locale, 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de Aquisicao já cadastrado
     * @param integer $idAquisicao
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $Aquisicao = $this->getDoctrine()->getRepository('CacicCommonBundle:Aquisicao')->find( $request->get('id') );
        if ( ! $Aquisicao )
            throw $this->createNotFoundException( 'Aquisicao não encontrado' );

        $em = $this->getDoctrine()->getManager();

        foreach ($Aquisicao->getItens() as $item) {
            $Aquisicao->removeIten($item);
            $em->flush($Aquisicao);
            $sql = "DELETE FROM aquisicoes_software
                    WHERE id_aquisicao_item = ".$item->getIdAquisicaoItem();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
        }
        $sql = "DELETE FROM software_licencas
                WHERE id_aquisicao = ".$Aquisicao->getIdAquisicao();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $sql = "DELETE FROM aquisicao_item
                WHERE id_aquisicao = ".$Aquisicao->getIdAquisicao();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $em->remove( $Aquisicao );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
