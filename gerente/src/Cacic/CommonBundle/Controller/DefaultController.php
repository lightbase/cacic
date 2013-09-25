<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	
	/**
	 * 
	 * Tela inicial do CACIC
	 */
	public function indexAction()
	{
		return $this->render('CacicCommonBundle:Default:index.html.twig');
	}
	
}
