<?php

namespace Cacic\RelatorioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\RelatorioBundle\Form\Type\CompartilhamentosType;

class DefaultController extends Controller
{
    
	/**
	 * 
	 * Relatorio de Autorizacoes Cadastradas
	 */
	public function autorizacoesAction()
	{
		return $this->render( 'CacicRelatorioBundle:Default:autorizacoes.html.twig', 
								array( 'registros' => $this->getDoctrine()->getRepository('CacicCommonBundle:SoftwareEstacao')->gerarRelatorioAutorizacoes() )
							);
	}
	
	/**
	 * 
	 * Relatório de Pastas Compartilhadas
	 */
	public function compartilhamentosAction()
	{
		$form = $this->createForm( new CompartilhamentosType() );
		
		return $this->render( 'CacicRelatorioBundle:Default:compartilhamentos.html.twig', array( 'form' => $form->createView() ) );
	}
	
}
