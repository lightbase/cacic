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
    					->getRepository('CacicCommonBundle:ComputadorColeta')
    					->gerarRelatorioConfiguracoes( $request->get('rel_filtro_hardware') );
        $locale = $request->getLocale();
    	return $this->render(
        	'CacicRelatorioBundle:Hardware:rel_configuracoes.html.twig', 
        	array(
                'idioma'=>$locale,
        		'dados' => $dados
        	)
        );
    }

    /*
     * Relatório genérico para qualquer classe WMI
     */

    public function wmiAction( Request $request, $classe)
    {
        $conf = $this->getDoctrine()->getRepository('CacicCommonBundle:ComputadorColeta')->listarPropriedades($classe);
        $locais = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->listar();
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->listar();
        $redes = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->listar();

        return $this->render(
            'CacicRelatorioBundle:Hardware:wmi_filtro.html.twig',
            array(
                'conf' 		=> $conf,
                'locais' 	=> $locais,
                'so'		=> $so,
                'redes'     => $redes,
                'classe'    => $classe
            )
        );
    }

    /**
     * [RELATÓRIO] Relatório de atributos da classe WMI gerado à partir dos filtros informados
     */
    public function wmiRelatorioAction( Request $request, $classe )
    {
        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:ComputadorColeta')
            ->gerarRelatorioWMI( $filtros = $request->get('rel_filtro_hardware'), $classe = $classe );

        $locale = $request->getLocale();
        return $this->render(
            'CacicRelatorioBundle:Hardware:rel_wmi.html.twig',
            array(
                'idioma'=> $locale,
                'dados' => $dados,
                'classe' => $classe
            )
        );
    }

    /**
     * [RELATÓRIO] Relatório de atributos da classe WMI gerado à partir dos filtros informados detalhado
     */
    public function wmiRelatorioDetalheAction( Request $request, $classe, $propriedade )
    {
        $filtros['conf'] = $propriedade;
        $rede = $request->get('rede');
        $local = $request->get('local');
        $so = $request->get('so');

        // Adiciona rede à lista de filtros se for fornecido
        if (!empty($rede)) {
            $filtros['rede'] = $rede;
        }

        // Adiciona local à lista de filtros se for fornecido
        if (!empty($local)) {
            $filtros['locais'] = $local;
        }

        // Adiciona SO à lista de filtros se for fornecido
        if (!empty($so)) {
            $filtros['so'] =  $so;
        }

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:ComputadorColeta')
            ->gerarRelatorioWMIDetalhe( $filtros = $filtros, $classe = $classe );

        $locale = $request->getLocale();
        return $this->render(
            'CacicRelatorioBundle:Hardware:rel_wmi_detalhe.html.twig',
            array(
                'idioma'=> $locale,
                'dados' => $dados,
                'propriedade' => $propriedade,
                'classe' => $classe
            )
        );
    }
    
}
