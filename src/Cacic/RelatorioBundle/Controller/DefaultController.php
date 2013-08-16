<?php

namespace Cacic\RelatorioBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\RelatorioBundle\Form\Type\CompartilhamentosType;
use Symfony\Component\HttpFoundation\Request;

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
        $sw = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->listar();
        
		return $this->render(
			'CacicRelatorioBundle:Default:patrimonio.html.twig',
        	array(
                'sw'        => $sw,
        		'locais' 	=> $locais,
        		'so'		=> $so
        	)
        );
	}

    /**
     * [RELATÓRIO] Relatório de Patrimônio gerado à partir dos filtros informados
     */
    public function patrimonioRelatorioAction( Request $request )
    {
    	$filtros = $request->get('rel_filtro_hardware');
    	
    	$dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:Software')
            ->gerarRelatorioPatrimonio( $filtros );
        $locale = $request->getLocale();

        return $this->render(
            'CacicRelatorioBundle:Default:rel_patrimonio.html.twig',
            array(
                'idioma'=>$locale,
                'dados' => $dados,
            	'exibirColunaSoftware' => (bool) strlen( $filtros['softwares']
		        )
            )
        );
    }
	
}
