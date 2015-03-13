<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\AquisicaoItem;
use Cacic\CommonBundle\Form\Type\AquisicaoItemType;


class AquisicaoItemController extends Controller
{
    public function indexAction( $page )
    {
        return $this->render(
        	'CacicCommonBundle:AquisicaoItem:index.html.twig',
        	array( 'Aquisicao' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:AquisicaoItem' )->paginar( $this->get( 'knp_paginator' ), $page ))
        );
    }
    
    public function cadastrarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Aquisicao = new AquisicaoItem();
        $form = $this->createForm( new AquisicaoItemType(), $Aquisicao );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {

                // Primeiro remove os softwares que estavam cadastrados
                foreach ($Aquisicao->getIdSoftware() as $software) {
                    $this->get('logger')->debug("Removendo software ".$software->getIdSoftware());
                    $Aquisicao->removeIdSoftware($software);
                    $em->persist( $software );

                }
                $em->persist( $Aquisicao );

                $software_list = $request->get('idSoftware');

                // Garante qus os elementos do array são únicos
                $software_list = array_unique($software_list);

                foreach ($software_list as $software) {
                    $this->get('logger')->debug("Adicionando software ".$software);
                    $software_obj = $this->getDoctrine()->getManager()->getRepository('CacicCommonBundle:Software')->find($software);
                    $Aquisicao->addIdSoftware($software_obj);
                    $em->persist( $software_obj );
                }

                $em->persist( $Aquisicao );
                $em->flush();// Efetuar a edição do Aquisicao

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
                return $this->redirect( $this->generateUrl( 'cacic_aquisicao_item_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:AquisicaoItem:cadastrar.html.twig', array(
            'form' => $form->createView(),
            'software_list' => $Aquisicao->getIdSoftware()
        ));
    }

    /**
     *  Página de editar dados do Aquisicao
     *  @param int $idAquisicao
     */
    public function editarAction( $idAquisicao, $idTipoLicenca, Request $request )
    {
        $em = $this->getDoctrine()->getManager();
        $Aquisicao = $em->getRepository('CacicCommonBundle:AquisicaoItem')
                                            ->find(
                                                array(
                                                    'idAquisicao' =>$idAquisicao,
                                                    'idTipoLicenca' => $idTipoLicenca
                                            )   );
        if ( !$Aquisicao )
            throw $this->createNotFoundException( 'Aquisicao não encontrado' );

        $form = $this->createForm( new AquisicaoItemType(), $Aquisicao );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                // Primeiro remove os softwares que estavam cadastrados
                foreach ($Aquisicao->getIdSoftware() as $software) {
                    $this->get('logger')->debug("Removendo software ".$software->getIdSoftware());
                    $Aquisicao->removeIdSoftware($software);
                    $em->persist( $software );

                }
                $em->persist( $Aquisicao );

                $software_list = $request->get('idSoftware');

                // Garante qus os elementos do array são únicos
                $software_list = array_unique($software_list);

                foreach ($software_list as $software) {
                    $this->get('logger')->debug("Adicionando software ".$software);
                    $software_obj = $this->getDoctrine()->getManager()->getRepository('CacicCommonBundle:Software')->find($software);
                    $Aquisicao->addIdSoftware($software_obj);
                    $em->persist( $software_obj );
                }

                $em->persist( $Aquisicao );
                $em->flush();// Efetuar a edição do Aquisicao

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
            }
        }

        return $this->render( 'CacicCommonBundle:AquisicaoItem:cadastrar.html.twig', array(
            'form' => $form->createView(),
            'software_list' => $Aquisicao->getIdSoftware()
        ));
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

        $itemAquisicao = $this->getDoctrine()->getRepository('CacicCommonBundle:AquisicaoItem')->find( $request->get('compositeKeys') );
        if ( ! $itemAquisicao )
            throw $this->createNotFoundException( 'Item de Aquisição não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $itemAquisicao );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}