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
	 * Relatório de Informações Patrimoniais
	 */
	public function patrimonioAction()
	{
    	$locais = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->listar();
    	$so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->listar();
		
		return $this->render(
			'CacicRelatorioBundle:Default:patrimonio.html.twig',
        	array(
        		'locais' 	=> $locais,
        		'so'		=> $so
        	)
        );
	}
	
}
