<?php

namespace Cacic\RelatorioBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cacic\RelatorioBundle\Form\Type\FiltroSoftwareType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Reader\ArrayReader;
use Ddeboer\DataImport\Writer\CsvWriter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Ddeboer\DataImport\ValueConverter\CallbackValueConverter;
use Ddeboer\DataImport\ValueConverter\CharsetValueConverter;

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
    					->getRepository('CacicCommonBundle:ComputadorColeta')
    					->gerarRelatorioSoftwaresInventariados( $request->get('rel_filtro_software') );

        $TotalnumComp = 0;

        foreach ($dados as $cont  ){
            $TotalnumComp += $cont['numComp'];
        }

    	return $this->render(
        	'CacicRelatorioBundle:Software:rel_inventariados.html.twig', 
        	array(
                'idioma'=>$locale,
        		'dados' => $dados,
                'totalnumcomp' => $TotalnumComp
            )
        );
    }

    /**
     * [CSV] Relatório de Softwares Inventariados gerado à partir dos filtros informados
     */
    public function inventariadosRelatorioCsvAction( Request $request )
    {
        $rede = implode(',',$request->get('teIpRede'));
        $software = implode(',',$request->get('idSoftware'));
        $local = implode(',',$request->get('idLocal'));

        // Adiciona rede à lista de filtros se for fornecido
        if (!empty($rede)) {
            $filtros['redes'] = $rede;
        }

        // Adiciona local à lista de filtros se for fornecido
        if (!empty($local)) {
            $filtros['local'] = $local;
        }

        // Adiciona Software à lista de filtros se for fornecido
        if (!empty($software)) {
            $filtros['softwares'] =  $software;
        }

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:ComputadorColeta')
            ->gerarRelatorioSoftwaresInventariados( $filtros );

        $locale = $request->getLocale();

        // Gera cabeçalho
        $cabecalho = array();
        foreach($dados as $elm) {
                array_push($cabecalho, array_keys($elm));
                break;
            }
            // Gera CSV
        $reader = new ArrayReader(array_merge($dados));

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), "SoftwareInventariado.csv");
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=SoftwareInventariado.csv");
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
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

    public function listaAction(Request $request, $nmSoftware, $nmLocal, $idRede) {
        $locale = $request->getLocale();

        $filtros = array(
            'locais' => $nmLocal,
            'redes' => $idRede
        );

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:ComputadorColeta')
            ->gerarRelatorioSoftware($filtros, $nmSoftware);

        return $this->render(
            'CacicRelatorioBundle:Software:rel_software_lista.html.twig',
            array(
                'idioma'=> $locale,
                'software' => $nmSoftware,
                'dados' => $dados
            )
        );
    }

    /**
     * Retorna JSON dos softwares encontrados
     *
     * @param Request $request Requisiçao
     * @param $name Nome do software para buscar
     * @return JsonResponse Resposta no formato JSON:
     * {
     *      'idSoftware': ID do Software
     *      'nmSoftware': Nome do software
     *      'teDescricaoSoftware': Descriçao do software
     * }
     */
    public function searchAction(Request $request, $name) {

        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $response = new JsonResponse();
        $response->setStatusCode(200);

        // Serializers
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $software = $em->getRepository('CacicCommonBundle:Software')->findByName($name);

        $saida = array();
        foreach($software as $elm) {
            $array_elm = array(
                'idSoftware' => $elm[0]->getIdSoftware(),
                'nmSoftware' => $elm[0]->getNmSoftware(),
                'teDescricaoSoftware' => $elm[0]->getTeDescricaoSoftware(),

            );
            //$logger->debug("11111111111111111111111111111111111 ".$elm[0]->getNmSoftware());
            array_push($saida, $array_elm);
        }

        $response->setContent(json_encode($saida));

        return $response;
    }

    public function aquisicoesDetalhadoAction( Request $request, $idAquisicao, $idTipoLicenca )
    {
        $locale = $request->getLocale();
        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:AquisicaoItem')
            ->aquisicoesDetalhado($idAquisicao, $idTipoLicenca);

        return $this->render(
            'CacicRelatorioBundle:Software:rel_aquisicoes_det.html.twig',
            array(
                'idioma'=>$locale,
                'dados' => $dados
            )
        );
    }

    public function aquisicoesDetalhadoCsvAction( Request $request, $idAquisicao, $idTipoLicenca )
    {
        $locale = $request->getLocale();
        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:AquisicaoItem')
            ->aquisicoesDetalhadoCsv($idAquisicao, $idTipoLicenca);

        $cabecalho = array(array(
            'ID',
            'Nome da máquina',
            'IP',
            'MAC Address',
            'Sistema Operacional',
            'Local',
            'Subrede',
            'Data último acesso'
        ));
        // Gera CSV
        $reader = new ArrayReader(array_merge($cabecalho, $dados));

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $converter = new CallbackValueConverter(function ($input) {
            return $input->format('d/m/Y H:m:s');
        });
        $workflow->addValueConverter('dtHrUltAcesso', $converter);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        // Add the writer to the workflow
        $filename = "relatorio-software.csv";
        $tmpfile = tempnam(sys_get_temp_dir(), $filename);
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=$filename");
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }
}
