<?php

namespace Cacic\RelatorioBundle\Controller;

use Doctrine\Common\Util\Debug;
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
        $redes = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->listar();
    	$sw = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->listarSoftware();
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:inventariados_filtro.html.twig', 
        	array(
        		'softwares'	=> $sw,
        		'locais' 	=> $locais,
                'redes'     => $redes,
        		'so'		=> $so
        	)
        );
    }
    
    /**
     * [RELATÓRIO] Relatório de Softwares Inventariados gerado à partir dos filtros informados
     */
    public function inventariadosRelatorioAction( Request $request )
    {
        $locale = $request->getLocale();
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Software')
    					->gerarRelatorioSoftwaresInventariados( $request->get('rel_filtro_software') );
       
    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_inventariados.html.twig', 
        	array(
                'idioma'=>$locale,
        		'dados' => $dados
        	)
        );
    }
    public function listarAction( Request $request, $idSoftware )
    {
        $locale = $request->getLocale();
        $dados = $this->getDoctrine()
                        ->getRepository('CacicCommonBundle:Software')
                        ->getSoftwareDadosComputador( $idSoftware );

        return $this->render( 'CacicRelatorioBundle:Software:listar.html.twig',
            array(
                'idioma' =>$locale,
                'dados' =>  $dados
            )
        );
    }
    
	/**
	 * 
	 * [TELA] Filtros para relatório de Softwares Licenciados 
	 */
    public function licenciadosAction()
    {
    	$sw = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->listarSoftware();
    	
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
        $locale = $request->getLocale();
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Software')
    					->gerarRelatorioSoftwaresLicenciados( $request->get('rel_filtro_software') );
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_licenciados.html.twig', 
        	array(
                'idioma'=>$locale,
        		'dados' => $dados
        	)
        );
    }
    
	/**
     * [RELATÓRIO] Relatório de Softwares por Processos de Aquisição
     */
    public function aquisicoesRelatorioAction( Request $request )
    {
        $locale = $request->getLocale();
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Aquisicao')
    					->gerarRelatorioAquisicoes();
    	//\Doctrine\Common\Util\Debug::dump($dados);die;
    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_aquisicoes.html.twig', 
        	array(
                'idioma'=>$locale,
        		'dados' => $dados
        	)
        );
    }
    
	/**
	 * 
	 * [TELA] Filtros para relatório de Softwares Associados a Estações 
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
        $locale = $request->getLocale();
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Software')
    					->gerarRelatorioSoftwaresPorOrgao( $request->get('form') );

    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_orgao.html.twig', 
        	array(
                'idioma'=>$locale,
        		'dados' => $dados
        	)
        );
    }
    
	/**
	 * 
	 * [TELA] Filtros para relatório de Softwares Por Tipo associados a Estações
	 */
    public function tipoAction()
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
    					->getForm();
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:tipo_filtro.html.twig', 
        	array(
        		'form'	=> $form->createView()
        	)
        );
    }
    
	/**
     * [RELATÓRIO] Relatório de Softwares por tipo Associados a Estações
     * - Filtros: Tipos de Software
     */
    public function tipoRelatorioAction( Request $request )
    {
        $locale = $request->getLocale();
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Software')
    					->gerarRelatorioSoftwaresPorTipo( $request->get('form') );

    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_tipo.html.twig', 
        	array(
                'idioma'=>$locale,
        		'dados' => $dados
        	)
        );
    }
    
	/**
     * [RELATÓRIO] Relatório de Softwares cadastrados mas não vinculados a nenhuma máquina
     */
    public function naoVinculadosRelatorioAction( Request $request )
    {
        $locale = $request->getLocale();
    	$dados = $this->getDoctrine()
    					->getRepository('CacicCommonBundle:Software')
    					->gerarRelatorioSoftwaresNaoVinculados();
    	
    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_naovinculados.html.twig', 
        	array(
                'idioma'=>$locale,
        		'dados' => $dados
        	)
        );
    }

    /**
     * [RELATÓRIO] Lista de máquinas que possuem o software instalado
     *
     * @param software O Nome do software a ser listado
     */

    public function listaAction(Request $request, $nmSoftware, $nmLocal) {
        $locale = $request->getLocale();

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:ComputadorColeta')
            ->gerarRelatorioSoftware($filtros = array(), $nmSoftware, $nmLocal);

        return $this->render(
            'CacicRelatorioBundle:Software:rel_software_lista.html.twig',
            array(
                'idioma'=> $locale,
                'software' => $nmSoftware,
                'dados' => $dados
            )
        );
    }
}
