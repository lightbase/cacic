<?php

namespace Cacic\RelatorioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HardwareController extends Controller
{

	/**
	 * 
	 * [TELA] Filtros para relatório de Configurações de Hardware 
	 */
    public function configuracoesAction()
    {
    	$conf = $this->getDoctrine()->getRepository('CacicCommonBundle:Classe')->listar();
    	$locais = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->listar();
    	$so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->listar();
    	
    	return $this->render(
        	'CacicRelatorioBundle:Hardware:configuracoes_filtro.html.twig', 
        	array(
        		'conf' 		=> $conf,
        		'locais' 	=> $locais,
        		'so'		=> $so
        	)
        );
    }
    
    /**
     * [RELATÓRIO] Relatório de Configurações de Hardware gerado à partir dos filtros informados
     */
    public function configuracoesRelatorioAction( Request $request )
    {
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Computador')
    					->gerarRelatorioConfiguracoes( $request->get('rel_filtro_hardware') );
    	
    	return $this->render(
        	'CacicRelatorioBundle:Hardware:rel_configuracoes.html.twig', 
        	array(
        		'dados' => $dados
        	)
        );
    }
    
}
