<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Software;
use Cacic\CommonBundle\Form\Type\SoftwareType;


class SoftwareController extends Controller
{
	
	/**
	 * 
	 * Tela de listagem de Softwares cadastrados
	 * @param int $page
	 */
    public function indexAction( $page )
    {
        return $this->render(
        	'CacicCommonBundle:Software:index.html.twig', 
        	array( 'softwares' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Software' )->listar() ) 
        );
    }
    
    /**
     * 
     * Tela de Cadastro de novo Software
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    public function cadastrarAction(Request $request)
    {
        $Software = new Software();
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
     *  Tela de editar dados do Software
     *  @param int $idSoftware
     *  @param Symfony\Component\HttpFoundation\Request $request
     */
    public function editarAction( $idSoftware, Request $request )
    {
        $Software = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->find( $idSoftware );
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
     * @param Symfony\Component\HttpFoundation\Request $request
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
    
    /**
     * 
     * Tela de classificação EM LOTE de Softwares
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    public function naoClassificadosAction( Request $request )
    {
    	if ( $request->isMethod('POST') )
        {
			if ( count( $request->get('software') ) )
			{
				foreach ( $request->get('software') as $idSoftware => $idTipo )
				{
					$software = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->find( (int) $idSoftware );
					$tipoSoftware = $this->getDoctrine()->getRepository('CacicCommonBundle:TipoSoftware')->find( (int) $idTipo );
					
					if ( ! $software || ! $tipoSoftware )
					{ // Impede injection verificando a existência tanto do Software quanto do Tipo de Software
						$this->get('session')->getFlashBag()->add('error', 'Dados inválidos');
						break;
					}
					
					$software->setIdTipoSoftware( $tipoSoftware ); // Associa o Tipo de Software ao Software
					$this->getDoctrine()->getManager()->persist( $software );
				}
				
				$this->getDoctrine()->getManager()->flush(); // Efetiva a edição dos dados na base
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
			}
			else
				$this->get('session')->getFlashBag()->add('error', 'Nenhum software informado!');
			
            return $this->redirect( $this->generateUrl( 'cacic_software_naoclassificados') );
        }
    	
    	return $this->render(
        	'CacicCommonBundle:Software:naoclassificados.html.twig', 
        	array(
        		'softwares' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Software' )->listarNaoClassificados(),
        		'tipos' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:TipoSoftware' )->findAll()
        	) 
        );
    }
    
    /**
     * 
     * Tela de exclusão de Softwares não associados a nenhuma máquina
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    public function naoUsadosAction( Request $request )
    {
    	if ( $request->isMethod('POST') )
        {
			if ( count( $request->get('software') ) )
			{
				foreach ( $request->get('software') as $idSoftware )
				{
					$software = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->find( (int) $idSoftware );
					
					if ( ! $software )
					{ // Impede injection verificando a existência do Software 
						$this->get('session')->getFlashBag()->add('error', 'Dados inválidos');
						break;
					}
					
					$this->getDoctrine()->getManager()->remove( $software );
				}
				
				$this->getDoctrine()->getManager()->flush(); // Efetiva a exclusão dos dados na base
				
				$this->get('session')->getFlashBag()->add('success', 'Softwares excluídos com sucesso!');
			}
			else
				$this->get('session')->getFlashBag()->add('error', 'Nenhum software informado!');
			
            return $this->redirect( $this->generateUrl( 'cacic_software_naousados') );
        }
        
    	return $this->render(
        	'CacicCommonBundle:Software:naousados.html.twig', 
        	array(
        		'softwares' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Software' )->listarNaoUsados()
        	) 
        );
    }
    
}