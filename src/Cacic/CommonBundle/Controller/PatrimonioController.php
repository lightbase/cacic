<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	
	/**
	 * 
	 * Tela de edição de interface de coleta
	 */
	public function interfaceAction()
	{
		return $this->render('CacicCommonBundle:Patrimonio:interface.html.twig');
	}
	
	/**
	 * 
	 * Tela de edição de opções de Coleta de informações patrimoniais e localização física
	 */
	public function opcoesAction()
	{
		return $this->render('CacicCommonBundle:Patrimonio:interface.html.twig');
	}
	
	/**
	 * 
	 * Tela de manutenção das UNIDADES ORGANIZACIONAIS - Entidades, Linhas de negócio, Órgãos, etc
	 */
	public function uorgAction()
	{
		return $this->render('CacicCommonBundle:Patrimonio:interface.html.twig');
	}
	
}
