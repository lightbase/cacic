<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
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
        $Aquisicao = new AquisicaoItem();
        $form = $this->createForm( new AquisicaoItemType(), $Aquisicao );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {

                $data = $form->get('idSoftware')->getData();
                $idSoftware = $data->getIdSoftware();

                $data = $form->get('idAquisicao')->getData();
                $idAquisicao = $data->getIdAquisicao();

                $data = $form->get('idTipoLicenca')->getData();
                $idTipoLicenca = $data->getIdTipoLicenca();

                $AquisicaoItem = $this->getDoctrine()->getRepository('CacicCommonBundle:AquisicaoItem')
                    ->find(
                        array(
                            'idSoftware' => $idSoftware,
                            'idAquisicao' =>$idAquisicao,
                            'idTipoLicenca' => $idTipoLicenca
                        )   );
                //Codição para update
                if($AquisicaoItem != null){
                $form = $this->createForm( new AquisicaoItemType(), $AquisicaoItem );
                   $data = $form->getData();

                    $software_list = $data['idSoftware'];

                    foreach ($software_list as $software) {
                        $this->get('logger')->debug("Adicionando software ".$software);
                        $software_obj = $this->getDoctrine()->getManager()->getRepository('CacicCommonBundle:Software')->find($software);
                        $Aquisicao->addIdSoftware($software_obj);
                    }

                    $this->getDoctrine()->getManager()->persist( $AquisicaoItem );
                    $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Aquisicao
                }
                else
                //Inserção de aquisição item
                $this->getDoctrine()->getManager()->persist( $Aquisicao );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Aquisicao

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
                return $this->redirect( $this->generateUrl( 'cacic_aquisicao_item_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:AquisicaoItem:cadastrar.html.twig', array(
            'form' => $form->createView()
        ));
    }
    /**
     *  Página de editar dados do Aquisicao
     *  @param int $idAquisicao
     */
    public function editarAction( $idAquisicao, $idTipoLicenca, Request $request )
    {
        $Aquisicao = $this->getDoctrine()->getRepository('CacicCommonBundle:AquisicaoItem')
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

                //$Aquisicao = $form->getData();

                // Primeiro remove os softwares que estavam cadastrados
                foreach ($Aquisicao->getIdSoftware() as $software) {
                    $Aquisicao->removeIdSoftware($software);
                }

                $software_list = $request->get('idSoftware');
                foreach ($software_list as $software) {
                    $this->get('logger')->debug("Adicionando software ".$software);
                    $software_obj = $this->getDoctrine()->getManager()->getRepository('CacicCommonBundle:Software')->find($software);
                    $Aquisicao->addIdSoftware($software_obj);
                    $this->getDoctrine()->getManager()->persist( $software_obj );
                }

                $this->getDoctrine()->getManager()->persist( $Aquisicao );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Aquisicao


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