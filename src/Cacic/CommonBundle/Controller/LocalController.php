<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Locais;
use Cacic\CommonBundle\Form\Type\LocalType;

/**
 * 
 * CRUD da Entidade Locais
 * @author lightbase
 *
 */
class LocalController extends Controller
{

    /**
     *
     * Tela de listagem
     * @param $page
     */
    public function indexAction( $page )
    {
        return $this->render(
        	'CacicCommonBundle:Local:index.html.twig',
        	array( 'locais' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Locais' )->listar() )
        );
    }
    
	/**
	 * 
	 * Tela de cadastro de novo Local
	 */
	public function cadastrarAction( Request $request )
	{
		$local = new Locais();
		$form = $this->createForm( new LocalType(), $local );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $local );
				$this->getDoctrine()->getManager()->flush(); // Persiste os dados do Local
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect( $this->generateUrl( 'cacic_local_index' ) );
			}
		}
		
		return $this->render( 
			'CacicCommonBundle:Local:cadastrar.html.twig',
			array( 'form' => $form->createView() )
		);
	}
	
	/**
	 * 
	 * Tela de edição de Local já cadastrado
	 * @param integer $idLocal
	 */
	public function editarAction( $idLocal, Request $request )
	{
		$local = $this->getDoctrine()->getRepository('CacicCommonBundle:Locais')->find( $idLocal );
		if ( ! $local )
			throw $this->createNotFoundException( 'Local não encontrado' );
		
		$form = $this->createForm( new LocalType(), $local );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $local );
				$this->getDoctrine()->getManager()->flush(); // Efetua a edição do Local
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect($this->generateUrl('cacic_local_editar', array( 'idLocal'=>$local->getIdLocal() ) ) );
			}
		}
		
		return $this->render(
			'CacicCommonBundle:Local:cadastrar.html.twig',
			array( 'form' => $form->createView() )
		);
	}
	
	/**
	 * 
	 * [AJAX] Exclusão de Local já cadastrado
	 * @param integer $idLocal
	 */
	public function excluirAction( $idLocal )
	{
		
	}
	
}