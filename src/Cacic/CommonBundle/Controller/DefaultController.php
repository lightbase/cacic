<?php

namespace Cacic\CommonBundle\Controller;

use Cacic\CommonBundle\Common;

class DefaultController extends BaseController
{
	
	/**
	 * 
	 * Tela inicial do CACIC
	 */
	public function indexAction()
	{
		return $this->render('CacicCommonBundle:Default:index.html.twig');
	}
	
	public function instaladorAction()
	{
		require_once 'instalador/index.php';
		die ('pronto');
	}
	
}
