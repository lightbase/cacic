<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Common;

class BaseController extends Controller
{
	
	public function __construct()
	{
		$root_cacic = realpath( dirname(__FILE__) .'/../' ). '/Common';
		set_include_path( "." . PATH_SEPARATOR . $root_cacic );
		
		require_once 'include/library.php';
	}
	
}