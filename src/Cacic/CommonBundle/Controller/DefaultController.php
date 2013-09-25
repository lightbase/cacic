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
		$estatisticas = array(
			'totalCompMonitorados' => $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->countAll(),
			'totalInsucessosInstalacao' => $this->getDoctrine()->getRepository('CacicCommonBundle:InsucessoInstalacao')->countAll(),
			'totalCompPorSO' => $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->countPorSO()
		);
		
		return $this->render(
			'CacicCommonBundle:Default:index.html.twig',
			array(
				'estatisticas' => $estatisticas 
			)
		);
	}

    /*
     * Página de download dos agentes
     */

    public function downloadsAction() {
        return $this->render('CacicCommonBundle:Default:downloads.html.twig');
    }
	
}
