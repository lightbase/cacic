<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Common;

class DefaultController extends Controller
{
    public function instaladorAction()
    {
	set_include_path("/srv/cacic/cacic/branches/3.0/gerente/src/Cacic/CommonBundle/Common/:.");
	require_once("/srv/cacic/cacic/branches/3.0/gerente/src/Cacic/CommonBundle/Common/instalador/index.php");
        //return $this->render('CacicCommonBundle:Default:index.html.twig', array('name' => $name));
    }
}
