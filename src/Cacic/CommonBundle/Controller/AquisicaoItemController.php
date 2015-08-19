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
        	array(
                'Aquisicao' => $this->getDoctrine()
                    ->getRepository( 'CacicCommonBundle:AquisicaoItem' )
                    ->paginar(
                        $this->get( 'knp_paginator' ),
                        $page
                    )
            )
        );
    }
    
    public function cadastrarAction(Request $request, $idAquisicaoItem = null)
    {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        $logger->debug("000000000000000000000000000000 $idAquisicaoItem");

        $Aquisicao = $em->getRepository("CacicCommonBundle:AquisicaoItem")->find($idAquisicaoItem);

        if (empty($Aquisicao)) {
            $Aquisicao = new AquisicaoItem();
        }

        $form = $this->createForm( new AquisicaoItemType(), $Aquisicao );

        if ( $request->isMethod('POST') )
        {
            $form->handleRequest( $request );
            if ( $form->isValid() )
            {

                // Primeiro remove os softwares que estavam cadastrados
                // Manually delete all entries

                if (!empty($Aquisicao)) {
                    $idAquisicaoItem = $Aquisicao->getIdAquisicaoItem();
                    $logger->debug("Removendo softwares para id_aquisicao_item = $idAquisicaoItem");

                    $sql = "DELETE FROM aquisicoes_software
                    WHERE id_aquisicao_item = $idAquisicaoItem";

                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();

                    $em->flush();
                }

                $software_list = $form->get('idSoftwareRelatorio')->getData();

                foreach ($software_list as $software_relatorio) {
                    $software = $software_relatorio->getIdRelatorio();
                    $logger->debug("Adicionando software ".$software);

                    $Aquisicao->addIdSoftwareRelatorio($software_relatorio);
                    $software_relatorio->addAquisico($Aquisicao);
                    $em->persist( $software_relatorio );
                    $em->persist($Aquisicao);

                    $em->flush();
                }

                $em->persist( $Aquisicao );
                $em->flush();// Efetuar a edição do Aquisicao

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
                return $this->redirect( $this->generateUrl( 'cacic_aquisicao_item_index') );
            }
        }

        return $this->render(
            'CacicCommonBundle:AquisicaoItem:cadastrar.html.twig',
            array(
                'form' => $form->createView(),
                'software_list' => $Aquisicao->getIdSoftwareRelatorio()
            )
        );
    }

    /**
     * [AJAX] Exclusão de Aquisicao já cadastrado
     *
     * @param Request $request id obrigatório
     * @return Response
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) {
            // Verifica se se trata de uma requisição AJAX
            $this->get('session')->getFlashBag()->add('error', 'Página não encontrada!');
            throw $this->createNotFoundException( 'Página não encontrada' );
        }

        $itemAquisicao = $this->getDoctrine()->getRepository('CacicCommonBundle:AquisicaoItem')->find( $request->get('id') );
        if ( ! $itemAquisicao ) {
            $this->get('session')->getFlashBag()->add('error', 'Item de aquisicção não encontrado!');
            throw $this->createNotFoundException( 'Item de Aquisição não encontrado' );
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove( $itemAquisicao );
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Item removido com sucesso!');

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}