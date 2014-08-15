<?php

namespace Cacic\RelatorioBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Reader\ArrayReader;
use Ddeboer\DataImport\Writer\CsvWriter;
use Ddeboer\DataImport\ValueConverter\CallbackValueConverter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
        $filtros = $request->get('rel_filtro_hardware');

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:ComputadorColeta')
            ->gerarRelatorioWMI($filtros , $classe = $classe );


        $locale = $request->getLocale();
        return $this->render(
            'CacicRelatorioBundle:Hardware:rel_wmi.html.twig',
            array(
                'idioma'=> $locale,
                'dados' => $dados,
                'filtros' => $filtros,
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
            ->gerarRelatorioWMIDetalhe( $filtros, $classe );

        $locale = $request->getLocale();

        // Pega o idClassProperty
        $idClassProperty = $this
            ->getDoctrine()
            ->getManager()
            ->createQuery("SELECT p.idClassProperty FROM CacicCommonBundle:ClassProperty p WHERE p.nmPropertyName = :propriedade")
            ->setParameter('propriedade', $propriedade)
            ->getArrayResult();

        // Corrige para fazer o parsing da variável
        $item = array();
        foreach ($idClassProperty as $elm) {
            array_push($item, $elm['idClassProperty']);
        }
        $filtros['conf'] = join($item, ",");

        return $this->render(
            'CacicRelatorioBundle:Hardware:rel_wmi_detalhe.html.twig',
            array(
                'idioma'=> $locale,
                'dados' => $dados,
                'propriedade' => $propriedade,
                'filtros' => $filtros,
                'classe' => $classe
            )
        );
    }

    /**
     * [RELATÓRIO] Relatório CSV de atributos da classe WMI gerado à partir dos filtros informados
     */
    public function csvWMIRelatorioAction( Request $request, $classe )
    {
        $conf = $request->get('conf');
        error_log('Variavel $conf: '.$conf);
        $rede = $request->get('rede');
        error_log('Variavel $rede: '.$rede);
        $local = $request->get('locais');
        error_log('Variavel $local: '.$local);
        $so = $request->get('so');
        error_log('Variavel $so: '.$so);

        // Adiciona rede à lista de filtros se for fornecido
        if (!empty($rede)) {
            $filtros['redes'] = $rede;
        }

        // Adiciona local à lista de filtros se for fornecido
        if (!empty($local)) {
            $filtros['locais'] = $local;
        }

        // Adiciona SO à lista de filtros se for fornecido
        if (!empty($so)) {
            $filtros['so'] =  $so;
        }

        // Adiciona Propriedades à lista de filtros se for fornecido
        if (!empty($conf)) {
            $filtros['conf'] =  $conf;
        }

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:ComputadorColeta')
            ->gerarRelatorioWMI( $filtros, $classe );

        $locale = $request->getLocale();

        // Gera cabeçalho
        $cabecalho = array();
        foreach($dados as $elm) {
            array_push($cabecalho, array_keys($elm));
            break;
        }
        // Gera CSV
        $reader = new ArrayReader(array_merge($cabecalho, $dados));

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), $classe.".csv");
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=$classe.csv");
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    public function csvWMIRelatorioDetalheAction( Request $request, $classe, $propriedade )
    {
        $filtros['conf'] = $propriedade;
        $rede = $request->get('rede');
        $local = $request->get('local');
        $so = $request->get('so');

        // Adiciona rede à lista de filtros se for fornecido
        if (!empty($rede)) {
            $filtros['redes'] = $rede;
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
            ->gerarRelatorioWMIDetalhe( $filtros, $classe );

        $locale = $request->getLocale();

        // Gera cabeçalho
        $cabecalho = array();
        foreach($dados as $elm) {
            array_push($cabecalho, array_keys($elm));
            break;
        }
        // Gera CSV
        $reader = new ArrayReader(array_merge($cabecalho, $dados));

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), $propriedade.".csv");
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=$propriedade.csv");
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }
    
}
