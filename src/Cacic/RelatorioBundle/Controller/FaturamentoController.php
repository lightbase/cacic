<?php
 namespace Cacic\RelatorioBundle\Controller;
 use Cacic\CommonBundle\Entity\ComputadorColetaRepository;
 use Ddeboer\DataImport\ValueConverter\ArrayValueConverterMap;
 use Ddeboer\DataImport\ValueConverter\CharsetValueConverter;
 use Doctrine\Common\Util\Debug;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Bundle\FrameworkBundle\Controller\Controller;
 use Cacic\CommonBundle\Form\Type\LogPesquisaType;
 use Ddeboer\DataImport\Workflow;
 use Ddeboer\DataImport\Reader\ArrayReader;
 use Ddeboer\DataImport\Writer\CsvWriter;
 use Ddeboer\DataImport\ValueConverter\CallbackValueConverter;
 use Symfony\Component\HttpFoundation\BinaryFileResponse;


    class FaturamentoController extends Controller {


        public function faturamentoAction(Request $request) {

            $locale = $request->getLocale();

            $form = $this->createForm( new LogPesquisaType() );
            return $this->render( 'CacicRelatorioBundle:Faturamento:faturamento.html.twig',
                array(
                    'locale'=> $locale,
                    'form' => $form->createView()
                )
            );
        }

        public function faturamentoRelatorioAction(Request $request){

            $locale = $request->getLocale();
            $form = $this->createForm( new LogPesquisaType() );
            if ( $request->isMethod('POST') )
            {
                $form->bind( $request );
                $data = $form->getData();
                $filtroLocais = array(); // Inicializa array com locais a pesquisar
                foreach ( $data['idLocal'] as $locais ) {
                    array_push( $filtroLocais, $locais->getIdLocal() );
                }


                $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:LogAcesso')
                    ->pesquisar( $data['dtAcaoInicio'], $data['dtAcaoFim'], $filtroLocais);

		foreach ($logs as $cont  ){
                $TotalnumComp = $cont['numComp'];
		}

            }

            return $this->render( 'CacicRelatorioBundle:Faturamento:faturamentoResultado.html.twig',
                array(
                    'idioma'=> $locale,
                    'form' => $form->createView(),
                    'data' =>$data,
                    'logs' => ( isset( $logs ) ? $logs : null ),
                    'totalnumcomp' => $TotalnumComp
                )
            );
        }

        public function listarAction( Request $request, $idRede) {


            $dataInicio = $request->get('dtAcaoInicio');
            $dataFim = $request->get('dtAcaoFim');


            $locale = $request->getLocale();
            $dados = $this->getDoctrine()
                ->getRepository('CacicCommonBundle:LogAcesso')
                ->gerarRelatorioRede($filtros = array(),$idRede, $dataInicio, $dataFim);

            return $this->render(
                'CacicRelatorioBundle:Faturamento:listar.html.twig',
                array(
                    'rede'=> $dados[0]['nmRede'],
                    'idioma'=> $locale,
                    'dados' => $dados
                )
            );
        }
        public function listarCsvAction( Request $request) {


            $dataInicio = $request->get('dtAcaoInicio');
            $dataFim = $request->get('dtAcaoFim');
            $idRede = $request->get('idRede');

            $printers = $this->getDoctrine()
                ->getRepository('CacicCommonBundle:LogAcesso')
                ->listarCsv($filtros = array(), $idRede, $dataInicio, $dataFim);


            // Gera CSV
            $reader = new ArrayReader($printers);

            // Create the workflow from the reader
            $workflow = new Workflow($reader);



            // Create the workflow from the reader
            $workflow = new Workflow($reader);

            $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

            // Add the writer to the workflow
            $tmpfile = tempnam(sys_get_temp_dir(), 'Faturamento');
            $file = new \SplFileObject($tmpfile, 'w');
            $writer = new CsvWriter($file);
            $writer->writeItem(array('Computador', 'Mac Address','Endereço IP','Sistema Operacional','Local','Subrede','Data/Hora da Última Coleta'));
            $workflow->addWriter($writer);

            // Process the workflow
            $workflow->process();

            // Retorna o arquivo
            $response = new BinaryFileResponse($tmpfile);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="Faturamento.csv"');
            $response->headers->set('Content-Transfer-Encoding', 'binary');

            return $response;
        }
        public function inativosAction(Request $request) {

            $locale = $request->getLocale();

            $form = $this->createForm( new LogPesquisaType() );

            return $this->render( 'CacicRelatorioBundle:Faturamento:inativos.html.twig',
                array(
                    'locale'=> $locale,
                    'form' => $form->createView()
                )
            );
        }

        public function inativosRelatorioAction(Request $request){
            $locale = $request->getLocale();

            $form = $this->createForm( new LogPesquisaType() );


            if ( $request->isMethod('POST') )
            {
                $form->bind( $request );
                $data = $form->getData();
                $filtroLocais = array(); // Inicializa array com locais a pesquisar
                foreach ( $data['idLocal'] as $locais ) {
                    array_push( $filtroLocais, $locais->getIdLocal() );
                }


                $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador')
                    ->pesquisarInativos( $data['dtAcaoInicio'], $data['dtAcaoFim'], $filtroLocais);

                foreach ($logs as $cont  ){
                    $TotalnumComp = $cont['numComp'];
                }
            }

            return $this->render( 'CacicRelatorioBundle:Faturamento:inativosResultado.html.twig',
                array(
                    'idioma'=> $locale,
                    'form' => $form->createView(),
                    'data' => $data,
                    'totalnumcomp' =>$TotalnumComp,
                    'logs' => ( isset( $logs ) ? $logs : null )
                )
            );
        }

        public function listarInativosAction( Request $request, $idRede) {


            $dataInicio = $request->get('dtAcaoInicio');
            $dataFim = $request->get('dtAcaoFim');


            $locale = $request->getLocale();
            $dados = $this->getDoctrine()
                ->getRepository('CacicCommonBundle:Computador')
                ->gerarRelatorioRede($filtros = array(),$idRede, $dataInicio, $dataFim);

            return $this->render(
                'CacicRelatorioBundle:Faturamento:listarInativos.html.twig',
                array(
                    'rede'=> $dados[0]['nmRede'],
                    'idioma'=> $locale,
                    'dados' => $dados
                )
            );
        }
        public function listarInativosCsvAction( Request $request) {


            $dataInicio = $request->get('dtAcaoInicio');
            $dataFim = $request->get('dtAcaoFim');
            $idRede = $request->get('idRede');

            $printers = $this->getDoctrine()
                    ->getRepository('CacicCommonBundle:Computador')
                    ->listarInativosCsv($filtros = array(),$idRede, $dataInicio, $dataFim);


            // Gera CSV
            $reader = new ArrayReader($printers);

            // Create the workflow from the reader
            $workflow = new Workflow($reader);

            $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

            // Add the writer to the workflow
            $tmpfile = tempnam(sys_get_temp_dir(), 'Maquinas Sem Coletas');
            $file = new \SplFileObject($tmpfile, 'w');
            $writer = new CsvWriter($file);
            $writer->writeItem(array('Computador', 'Mac Address','Endereço IP','Sistema Operacional','Local','Subrede','Range IP'));
            $workflow->addWriter($writer);

            // Process the workflow
            $workflow->process();

            // Retorna o arquivo
            $response = new BinaryFileResponse($tmpfile);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="Máquinas Sem Coletas.csv"');
            $response->headers->set('Content-Transfer-Encoding', 'binary');

            return $response;
        }

        public function faturamentoCsvAction(Request $request)
        {
            $em = $this->getDoctrine()->getManager();

            $form = $this->createForm( new LogPesquisaType() );

                $form->bind( $request );
                $data = $form->getData();
                $filtroLocais = array(); // Inicializa array com locais a pesquisar
                foreach ( $data['idLocal'] as $locais ) {
                    array_push( $filtroLocais, $locais->getIdLocal() );
                }

                $printers = $em->getRepository( 'CacicCommonBundle:LogAcesso')
                    ->faturamentoCsv( $data['dtAcaoInicio'], $data['dtAcaoFim'], $filtroLocais);

            // Gera CSV
            $reader = new ArrayReader($printers);

            // Create the workflow from the reader
            $workflow = new Workflow($reader);

            $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

            // Add the writer to the workflow
            $tmpfile = tempnam(sys_get_temp_dir(), 'Faturamento');
            $file = new \SplFileObject($tmpfile, 'w');
            $writer = new CsvWriter($file);
            $writer->writeItem(array('Local', 'Subrede','Endereço IP','Estações'));
            $workflow->addWriter($writer);

            // Process the workflow
            $workflow->process();

            // Retorna o arquivo
            $response = new BinaryFileResponse($tmpfile);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="Faturamento.csv"');
            $response->headers->set('Content-Transfer-Encoding', 'binary');

            return $response;
        }
        public function inativosCsvAction(Request $request)
        {
            $em = $this->getDoctrine()->getManager();

            $form = $this->createForm( new LogPesquisaType() );

            $form->bind( $request );
            $data = $form->getData();
            $filtroLocais = array(); // Inicializa array com locais a pesquisar
            foreach ( $data['idLocal'] as $locais ) {
                array_push( $filtroLocais, $locais->getIdLocal() );
            }

            $printers = $em->getRepository( 'CacicCommonBundle:Computador')
                ->inativosCsv( $data['dtAcaoInicio'], $data['dtAcaoFim'], $filtroLocais);

            // Gera CSV
            $reader = new ArrayReader($printers);

            // Create the workflow from the reader
            $workflow = new Workflow($reader);

            $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

            // Add the writer to the workflow
            $tmpfile = tempnam(sys_get_temp_dir(), 'Não_Coletada');
            $file = new \SplFileObject($tmpfile, 'w');
            $writer = new CsvWriter($file);
            $writer->writeItem(array('Local', 'Subrede','Endereço IP','Estações'));
            $workflow->addWriter($writer);

            // Process the workflow
            $workflow->process();

            // Retorna o arquivo
            $response = new BinaryFileResponse($tmpfile);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="Não Coletadas.csv"');
            $response->headers->set('Content-Transfer-Encoding', 'binary');
}
}
