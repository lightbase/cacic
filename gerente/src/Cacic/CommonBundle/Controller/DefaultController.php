<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Common;

class DefaultController extends Controller
{
	
	public function __construct()
	{
		$root_cacic = realpath( dirname(__FILE__) .'/../' ). '/Common';
		set_include_path( "." . PATH_SEPARATOR . $root_cacic );
	}
	
    public function instaladorAction()
    {
		require_once( "instalador/index.php" );
        //return $this->render('CacicCommonBundle:Default:index.html.twig', array('name' => $name));
    }
}
