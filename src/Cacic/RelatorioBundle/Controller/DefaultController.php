<?php

namespace Cacic\RelatorioBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\RelatorioBundle\Form\Type\CompartilhamentosType;
use Cacic\RelatorioBundle\Form\Type\PatrimonioType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	/**
	 * 
	 * Relatório de Informações Patrimoniais
	 */
	public function patrimonioAction()
	{
        $locais = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->listar();
        $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->listar();
        $uorg = $this->getDoctrine()->getRepository('CacicCommonBundle:Uorg')->listar();
        $conf = $this->getDoctrine()->getRepository('CacicCommonBundle:ComputadorColeta')->listarPropriedades('Patrimonio');
        $sw = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->listarSoftware();



        return $this->render(
            'CacicRelatorioBundle:Default:patrimonio.html.twig',
            array(
                'locais' 	=> $locais,
                'so'		=> $so,
                'conf'      => $conf,
                'softwares'	=> $sw,
                'uorg'      => $uorg
            )
        );
    }
    /**
     * [RELATÓRIO] Relatório de Patrimônio gerado à partir dos filtros informados
     */
    public function patrimonioRelatorioAction( Request $request )
    {
      	  $filtros = $request->get('rel_filtro_patrimonio');
          $locale = $request->getLocale();

        if($filtros['conf'] <> ""){
            $dados = $this->getDoctrine()
                ->getRepository('CacicCommonBundle:ComputadorColeta')
                ->gerarRelatorioPatrimonio( $filtros );
        }
        else{
            $dados = $this->getDoctrine()
                ->getRepository('CacicCommonBundle:ComputadorColeta')
                ->gerarRelatorioSemPatrimonio( $filtros );
        }

        return $this->render(
            'CacicRelatorioBundle:Default:rel_patrimonio.html.twig',
            array(
                'idioma'=>$locale,
                'dados' => $dados,
                'menu' => (bool) strlen( $filtros['conf'])
            )
        );
    }
}
