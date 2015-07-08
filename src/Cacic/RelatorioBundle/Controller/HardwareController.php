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
use Cacic\CommonBundle\Form\Type\ClassPropertyPesquisaType;
use Ddeboer\DataImport\ValueConverter\CharsetValueConverter;
use Cacic\CommonBundle\Form\Type\ClassePesquisaType;


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
            ->getRepository('CacicCommonBundle:Computador')
            ->gerarRelatorioWMI($filtros , $classe );


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
        $em = $this->getDoctrine()->getManager();
        $filtros['conf'] = $propriedade;

        $rede = $request->get('rede');
        $local = $request->get('local');
        $so = $request->get('so');
        $valor = $request->get('valor');

        // Pega o idClass
        $idClass = $em->getRepository("CacicCommonBundle:Classe")->findOneBy(array(
            'nmClassName' => $classe
        ));

        $item = array();
        array_push($item, $idClass->getIdClass());
        $filtros['idClass'] = $idClass->getIdClass();

        $idClassProperty = $request->get('idClassProperty');
        if ($propriedade == "Não identificado" && !empty($idClassProperty)) {
            $filtros['conf'] = $idClassProperty;
        }

        // Pega o idClassProperty
        $idClassProperty = $em->getRepository("CacicCommonBundle:ClassProperty")->findBy(array(
            'nmPropertyName' => $propriedade
        ));

        // Corrige para fazer o parsing da variável
        $item = array();
        foreach ($idClassProperty as $elm) {
            array_push($item, $elm->getIdClassProperty());
        }

        if (!empty($item)) {
            $filtros['conf'] = join($item, ",");
        }

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

        // Adiciona valor à lista de filtros se for fornecido
        if (!empty($valor)) {
            $filtros['valor'] =  $valor;
        }

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:Computador')
            ->gerarRelatorioWMIDetalhe( $filtros );

        $locale = $request->getLocale();

        return $this->render(
            'CacicRelatorioBundle:Hardware:rel_wmi_detalhe.html.twig',
            array(
                'idioma'=> $locale,
                'dados' => $dados,
                'propriedade' => $propriedade,
                'filtros' => $filtros,
                'classe' => $classe,
                'valor' => $valor
            )
        );
    }

    /**
     * [RELATÓRIO] Relatório CSV de atributos da classe WMI gerado à partir dos filtros informados
     */
    public function csvWMIRelatorioAction( Request $request, $classe )
    {
        $conf = $request->get('conf');
        $rede = $request->get('rede');
        $local = $request->get('locais');
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

        // Adiciona Propriedades à lista de filtros se for fornecido
        if (!empty($conf)) {
            $filtros['conf'] =  $conf;
        }

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:Computador')
            ->gerarRelatorioWMICsv( $filtros, $classe );

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
        $em = $this->getDoctrine()->getManager();
        $filtros['conf'] = $propriedade;

        $rede = $request->get('rede');
        $local = $request->get('local');
        $so = $request->get('so');
        $valor = $request->get('valor');

        // Pega o idClass
        $idClass = $em->getRepository("CacicCommonBundle:Classe")->findOneBy(array(
            'nmClassName' => $classe
        ));

        $item = array();
        array_push($item, $idClass->getIdClass());
        $filtros['idClass'] = $idClass->getIdClass();

        $idClassProperty = $request->get('idClassProperty');
        if ($propriedade == "Não identificado" && !empty($idClassProperty)) {
            $filtros['conf'] = $idClassProperty;
        }

        // Pega o idClassProperty
        $idClassProperty = $em->getRepository("CacicCommonBundle:ClassProperty")->findBy(array(
            'nmPropertyName' => $propriedade
        ));

        // Corrige para fazer o parsing da variável
        $item = array();
        foreach ($idClassProperty as $elm) {
            array_push($item, $elm->getIdClassProperty());
        }

        if (!empty($item)) {
            $filtros['conf'] = join($item, ",");
        }

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

        // Adiciona valor à lista de filtros se for fornecido
        if (!empty($valor)) {
            $filtros['valor'] =  $valor;
        }

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:Computador')
            ->gerarRelatorioWMIDetalhe( $filtros );

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
        if (!empty($valor)) {
            $filename = $propriedade . "_" . $valor . ".csv";
        } else {
            $filename = "$propriedade.csv";
        }
        $response->headers->set('Content-Disposition', "attachment; filename=$filename");
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     *
     * Relatório de Configurações das Classes WMI Dinâmico
     */
    public function relWmiDinamicoAction()
    {
        $form = $this->createForm( new ClassPropertyPesquisaType());

        //$locais = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->listar();
        //$so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->listar();
        $classe = $this->getDoctrine()->getRepository('CacicCommonBundle:ClassProperty')->listar();

        return $this->render(
            'CacicRelatorioBundle:Hardware:rel_wmi_dinamico.html.twig',
            array(
                //'locais' 	=> $locais,
                //'so'		=> $so,
                'classe'    => $classe,
                'form'      => $form->createView()
            )
        );
    }

    /**
     *
     * Relatório de Configurações das Classes WMI Dinâmico Detalhes
     */

    public function relWmiDinamicoDetalharAction(Request $request)
    {

        $form = $this->createForm( new ClassPropertyPesquisaType());
        $property = $request->get('property');

        //Recupera as propriedades da classe WMI selecionadas para a pesquisa
        if ( $request->isMethod('POST') ){
            $form->handleRequest( $request );

            $dataInicio = $form['dataColetaInicio']->getData();
            $dataFim = $form['dataColetaFim']->getData();
            //$property = $form['property']->getData();
        }

        //relatorioWmiDinamico --> realiza a pesquisa das propriedades das classes WMI selecionadas
        $relDinamico = $this->getDoctrine()->getRepository('CacicCommonBundle:ClassProperty')
            ->relatorioWmiDinamico($property, $dataInicio, $dataFim);

        return $this->render(
            'CacicRelatorioBundle:Hardware:rel_wmi_dinamico_detalhar.html.twig',
            array(
                'relDinamico'   => $relDinamico,
                'saida'         => $property,
                'dataInicio'    => $dataInicio,
                'dataFim'       => $dataFim
            )
        );

    }

    /**
     * Gera CSV do relatório dinâmico
     *
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \Ddeboer\DataImport\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function csvRelWmiDinamicoAction(Request $request) {
        $logger = $this->get('logger');

        $form = $request->get('ClassPropertyPesquisa');

        if (empty($form)) {
            $dataInicio = $request->get('dataColetaInicio');
            $dataFim = $request->get('dataColetaFim');
        } else {
            $dataInicio = $form['dataColetaInicio'];
            $dataFim = $form['dataColetaFim'];
        }

        $property = $request->get('property');

        $relDinamico = $this->getDoctrine()->getRepository('CacicCommonBundle:ClassProperty')
            ->relatorioWmiDinamico($property, $dataInicio, $dataFim);

        // Gera cabeçalho
        $cabecalho = array(
            'idComputador',
            'Nome do Computador',
            'MAC',
            'IP do Computador',
            'IP da Rede',
            'Nome da Rede',
            'Data do último acesso'
        );
        foreach($property as $elm) {
            array_push($cabecalho, $elm);
        }



        $dados = array_merge(array($cabecalho), $relDinamico);

        // Gera CSV
        $reader = new ArrayReader($dados);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), "Relatório-WMI-Dinâmico.csv");
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $filename = "Relatório-WMI-Dinâmico.csv";

        $response->headers->set('Content-Disposition', "attachment; filename=$filename");
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     *
     * Relatório de Classes WMI sem coleta
     */
    public function semColetaAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        $form = $this->createForm( new ClassePesquisaType() );

        return $this->render( 'CacicRelatorioBundle:Hardware:wmi_sem_coleta.html.twig',
            array(
                'locale'=> $locale,
                'form' => $form->createView()
            )
        );
    }

    /**
     *
     * Relatório de Classes WMI sem coleta
     */
    public function semColetaListarAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();
        $logger = $this->get('logger');

        // Aqui vem via POST pelo formulário
        $data = $request->request->all();
        if (!empty($data)) {
            $idClasse = $data['classe_pesquisa']['nmClassName'];
        } else {
            $idClasse = null;
        }

        $computadores = $em->getRepository("CacicCommonBundle:Computador")->listarClasse($idClasse);
        $cont['numComp'] = count($computadores);

        $classes = $em->getRepository("CacicCommonBundle:Classe")->nomes($idClasse);

        return $this->render( 'CacicRelatorioBundle:Hardware:wmi_sem_coleta_listar.html.twig',
            array(
                'idioma'=> $locale,
                'filtroClasses' => $idClasse,
                'logs' => ( isset( $computadores ) ? $computadores : null ),
                'totalnumcomp' => $cont['numComp'],
                'classes' => $classes
            )
        );
    }

    public function semColetaDetalharAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        $idSo = $request->get('idSo');
        $idRede = $request->get('idRede');
        $idLocal = $request->get('idLocal');
        $idClasse = $request->get('idClasse');

        $computadores = $em->getRepository("CacicCommonBundle:Computador")->detalharClasse($idClasse, $idRede, $idLocal, $idSo);

        $classes = $em->getRepository("CacicCommonBundle:Classe")->nomes($idClasse);

        return $this->render('CacicRelatorioBundle:Hardware:wmi_sem_coleta_detalhar.html.twig',
            array(
                'idioma' => $locale,
                'idClasse' => $idClasse,
                'computadores' => $computadores,
                'idSo' => $idSo,
                'idRede' => $idRede,
                'idLocal' => $idLocal,
                'classes' => $classes
            )
        );
    }

    public function listarWmiSemColetaCsvAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();
        $logger = $this->get('logger');

        $idClasse = $request->get('idClasse');

        $computadores = $em->getRepository("CacicCommonBundle:Computador")->listarClasseCsv($idClasse);

        $TotalnumComp = 0;

        foreach ($computadores as $cont  ){
            $TotalnumComp += $cont['numComp'];
        }

        // Gera CSV
        $reader = new ArrayReader($computadores);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'Computadores-Subredes');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $writer->writeItem(array('SO', 'Local', 'Rede', 'IP da Subrede', 'Total de Estações'));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="WMI-sem-coleta.csv"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;

    }

    public function semColetaDetalharCsvAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        $idSo = $request->get('idSo');
        $idRede = $request->get('idRede');
        $idLocal = $request->get('idLocal');
        $idClasse = $request->get('idClasse');

        $computadores = $em->getRepository("CacicCommonBundle:Computador")->detalharClasseCsv($idClasse, $idRede, $idLocal, $idSo);

        // Gera CSV
        $reader = new ArrayReader($computadores);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        // As you can see, the first names are not capitalized correctly. Let's fix
        // that with a value converter:
        $converter = new CallbackValueConverter(function ($input) {
            return $input->format('d/m/Y H:m:s');
        });
        $workflow->addValueConverter('dtHrUltAcesso', $converter);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'Computadores-Subredes');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $writer->writeItem(array('Computador', 'MAC Address', 'Endereço IP', 'SO', 'Local', 'Sub Rede', 'Data e Hora do último Acesso'));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="WMI-sem-coleta.csv"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }
}
