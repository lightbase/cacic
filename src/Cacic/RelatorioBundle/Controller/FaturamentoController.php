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
 use Cacic\CommonBundle\Form\Type\ComputadorConsultaType;


    class FaturamentoController extends Controller {

        //Página inicial faturamento
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

        //Página que exibe todas as regionais faturamento
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

                $TotalnumComp = 0;

                foreach ($logs as $cont  ){
                    $TotalnumComp += $cont['numComp'];
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

        //botão csv de todas faturamento
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

        //Página que exibe cada regional faturamento
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
                    'dados' => ( isset( $dados ) ? $dados : null ),
                    'idRede' => $idRede,
                    'dtAcaoInicio' => $dataInicio,
                    'dtAcaoFim' => $dataFim

                )
            );
        }

        //Botão csv cada faturamento
        public function listarCsvAction( Request $request) {

            $dataInicio = $request->get('dataInicio');
            $dataFim = $request->get('dataFim');
            $idRede = $request->get('idRede');

            $printers = $this->getDoctrine()
                ->getRepository('CacicCommonBundle:LogAcesso')
                ->listarCsv($filtros = array(),$idRede, $dataInicio , $dataFim);

            // Gera CSV
            $reader = new ArrayReader($printers);

            // Create the workflow from the reader
            $workflow = new Workflow($reader);

            $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

            // Add the writer to the workflow
            $tmpfile = tempnam(sys_get_temp_dir(), 'Faturamento');
            $file = new \SplFileObject($tmpfile, 'w');
            $writer = new CsvWriter($file);
            $writer->writeItem(array('Computador','Mac Address','Endereço IP','Sistema Operacional','Local','Subrede','IP Subrede','Data/Hora da Última Coleta'));
            $workflow->addWriter($writer);

            // Process the workflow
            $workflow->process();

            // Retorna o arquivo
            $response = new BinaryFileResponse($tmpfile);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="Faturamento_subrede.csv"');
            $response->headers->set('Content-Transfer-Encoding', 'binary');

            return $response;

        }


        //Página inicial inativos
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

        //Página que exibe todas as regionais inativos
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

                $TotalnumComp = 0;

                foreach ($logs as $cont  ){
                    $TotalnumComp = $cont['numComp']+$TotalnumComp;
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

        //Página que exibe cada regional inativos
        public function listarInativosAction( Request $request, $idRede) {


            $dataInicio = $request->get('dtAcaoInicio');
            $dataFim = $request->get('dtAcaoFim');


            $locale = $request->getLocale();
            $dados = $this->getDoctrine()
                ->getRepository('CacicCommonBundle:Computador')
                ->gerarRelatorioRede($filtros = array(),$idRede, $dataInicio, $dataFim);


            return $this->render( 'CacicRelatorioBundle:Faturamento:listarInativos.html.twig',
                array(
                    'rede'=> $dados[0]['nmRede'],
                    'idioma'=> $locale,
                    'dados' => ( isset( $dados ) ? $dados : null ),
                    'idRede' => $idRede,
                    'dtAcaoInicio' => $dataInicio,
                    'dtAcaoFim' => $dataFim

                )
            );
        }

       //botão csv de cada inativos
        public function listarInativosCsvAction ( Request $request) {

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
            $tmpfile = tempnam(sys_get_temp_dir(), 'Máquinas sem Coletas');
            $file = new \SplFileObject($tmpfile, 'w');
            $writer = new CsvWriter($file);
            $writer->writeItem(array('Computador', 'Mac Address','Endereço IP','Sistema Operacional','Local','Subrede','IP Subrede'));
            $workflow->addWriter($writer);

                // Process the workflow
            $workflow->process();

                // Retorna o arquivo
            $response = new BinaryFileResponse($tmpfile);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="Não_Coletadas_subrede.csv"');
            $response->headers->set('Content-Transfer-Encoding', 'binary');

            return $response;
            }


        // botão csv todos inativo
        public function inativosCsvAction (Request $request)
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
            $tmpfile = tempnam(sys_get_temp_dir(), 'Máquinas sem Coletas');
            $file = new \SplFileObject($tmpfile, 'w');
            $writer = new CsvWriter($file);
            $writer->writeItem(array('Local', 'Subrede','Endereço IP','Estações'));
            $workflow->addWriter($writer);

            // Process the workflow
            $workflow->process();

            // Retorna o arquivo
            $response = new BinaryFileResponse($tmpfile);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="Não_Coletadas.csv"');
            $response->headers->set('Content-Transfer-Encoding', 'binary');

            return $response;
        }


        /**
         * Search computer with params
         *
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function computadorAction( Request $request )
        {
            $locale = $request->getLocale();
            $data = $request->query->all();

            $form = $this->createForm( new ComputadorConsultaType() );

            $computadores = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador')
                ->selectIp($data['teIpComputador'],$data['nmComputador'] ,$data['teNodeAddress'] );


            return $this->render( 'CacicCommonBundle:Computador:buscar.html.twig',
                array(
                    'local'=>$locale ,
                    'form' => $form->createView(),
                    'computadores' => ( $computadores )
                )
            );
        }
}