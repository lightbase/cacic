<?php

namespace Cacic\RelatorioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cacic\RelatorioBundle\Form\Type\FiltroSoftwareType;

class SoftwareController extends Controller
{

	/**
	 * 
	 * [TELA] Filtros para relatório de Softwares Inventariados 
	 */
    public function inventariadosAction()
    {
    	$locais = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->listar();
    	$so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->listar();
    	$sw = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->listar();
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:inventariados_filtro.html.twig', 
        	array(
        		'softwares'	=> $sw,
        		'locais' 	=> $locais,
        		'so'		=> $so
        	)
        );
    }
    
    /**
     * [RELATÓRIO] Relatório de Softwares Inventariados gerado à partir dos filtros informados
     */
    public function inventariadosRelatorioAction( Request $request )
    {
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Software')
    					->gerarRelatorioSoftwaresInventariados( $request->get('rel_filtro_software') );
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_inventariados.html.twig', 
        	array(
        		'dados' => $dados
        	)
        );
    }
    
	/**
	 * 
	 * [TELA] Filtros para relatório de Softwares Licenciados 
	 */
    public function licenciadosAction()
    {
    	$sw = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->listar();
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:licenciados_filtro.html.twig', 
        	array(
        		'softwares'	=> $sw
        	)
        );
    }
    
	/**
     * [RELATÓRIO] Relatório de Softwares Licenciados gerado à partir dos filtros informados
     */
    public function licenciadosRelatorioAction( Request $request )
    {
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Software')
    					->gerarRelatorioSoftwaresLicenciados( $request->get('rel_filtro_software') );
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_licenciados.html.twig', 
        	array(
        		'dados' => $dados
        	)
        );
    }
    
	/**
     * [RELATÓRIO] Relatório de Softwares por Processos de Aquisição
     */
    public function aquisicoesRelatorioAction( Request $request )
    {
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Aquisicao')
    					->gerarRelatorioAquisicoes();
    	//\Doctrine\Common\Util\Debug::dump($dados);die;
    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_aquisicoes.html.twig', 
        	array(
        		'dados' => $dados
        	)
        );
    }
    
	/**
	 * 
	 * [TELA] Filtros para relatório de Softwares Licenciados 
	 */
    public function orgaoAction()
    {
    	$form = $this->createFormBuilder()
    					->add(
    						'TipoSoftware',
    						'entity',
    						array(
    							'label'=>'Tipo de Software', 
    							'class'=>'CacicCommonBundle:TipoSoftware', 
    							'empty_value'=>'--Todos--', 
    							'required'=>false
    						)
    					)
    					->add('nmComputador', 'text', array('label'=>'Órgão/Máquina'))
    					->getForm();
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:orgao_filtro.html.twig', 
        	array(
        		'form'	=> $form->createView()
        	)
        );
    }
    
	/**
     * [RELATÓRIO] Relatório de Softwares Associados a Estações
     * - Filtros: Tipos de Software e Nome da máquina/Órgão
     */
    public function orgaoRelatorioAction( Request $request )
    {
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Software')
    					->gerarRelatorioSoftwaresPorOrgao( $request->get('form') );
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_orgao.html.twig', 
        	array(
        		'dados' => $dados
        	)
        );
    }
}
