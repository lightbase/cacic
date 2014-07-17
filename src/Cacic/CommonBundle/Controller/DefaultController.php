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
        $usuario = $this->getUser()->getIdUsuario();
        $nivel = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario' )->nivel($usuario);

		$estatisticas = array(
			'totalCompMonitorados' => $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->countAll(),
			'totalInsucessosInstalacao' => $this->getDoctrine()->getRepository('CacicCommonBundle:InsucessoInstalacao')->countAll(),
			'totalCompPorSO' => $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->countPorSO30Dias(),
			'totalComp' => $this->getDoctrine()->getRepository('CacicCommonBundle:LogAcesso')->countPorComputador(),
            'totalComp7Dias' => $this->getDoctrine()->getRepository('CacicCommonBundle:LogAcesso')->countComputadorDias('0','7'),
            'totalComp14Dias' => $this->getDoctrine()->getRepository('CacicCommonBundle:LogAcesso')->countComputadorDias('7','14')
        );
		
		return $this->render(
			'CacicCommonBundle:Default:index.html.twig',
			array(
				'estatisticas' => $estatisticas,
                'nivel' => $nivel[0]
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
