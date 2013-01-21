<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
		$arrLocais = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Locais' )->listar();
		return $this->render( 'CacicCommonBundle:Local:index.html.twig', array( 'locais' => $arrLocais ) );
	}
	
}