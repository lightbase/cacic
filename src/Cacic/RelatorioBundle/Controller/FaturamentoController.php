<?php
 namespace Cacic\RelatorioBundle\Controller;
 use Cacic\CommonBundle\Entity\ComputadorColetaRepository;
 use Cacic\CommonBundle\Form\Type\UserPesquisaType;
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
                'filtroLocais' => $filtroLocais,
                'logs' => ( isset( $logs ) ? $logs : null ),
                'totalnumcomp' => $TotalnumComp
            )
        );
    }

    //botão csv de todas faturamento
    public function faturamentoCsvAction(Request $request)
    {

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

            $printers = $this->getDoctrine()->getRepository( 'CacicCommonBundle:LogAcesso')
                ->faturamentoCsv( $data['dtAcaoInicio'], $data['dtAcaoFim'], $filtroLocais);

        }

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

    //botão csv de todas faturamento
    public function faturamentoCsvInternoAction(Request $request)
    {

        $dataInicio = $request->get('dtAcaoInicio');
        $dataFim = $request->get('dtAcaoFim');
        $idLocal = $request->get('idLocal');

        foreach ( $idLocal as $locais ) {
        }

        $printers = $this->getDoctrine()->getRepository( 'CacicCommonBundle:LogAcesso')
            ->faturamentoCsv( $dataInicio, $dataFim, $locais);

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

        $em = $this->getDoctrine()->getManager();

        $dataInicio = $request->get('dtAcaoInicio');
        $dataFim = $request->get('dtAcaoFim');

        if ($request->isMethod('POST'))  {
            // Ativa computadores
            foreach($request->request->get('idComputador') as $id_computador) {

                $this->get('logger')->debug("Ativando computador = $id_computador");

                // Atualiza subrede para o computador
                $computador = $em->find('CacicCommonBundle:Computador', $id_computador);
                $computador->setAtivo(true);
                $computador->setDtHrExclusao(null);
                $computador->setIdUsuarioExclusao(null);
                $em->persist($computador);
            }
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Computadores ativados com sucesso!');
        }

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

        $converter = new CallbackValueConverter(function ($input) {
            return $input->format('d/m/Y H:m:s');
        });
        $workflow->addValueConverter('dtHrUltAcesso', $converter);

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'Máquinas sem Coletas');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $writer->writeItem(array('Computador', 'Mac Address','Endereço IP','Sistema Operacional','Local','Subrede','IP Subrede', 'Último Acesso'));
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

    /*
     * Página inicial do Relatório de usuário logado
     */
    public function usuarioAction(Request $request) {

        $locale = $request->getLocale();

        $form = $this->createForm( new UserPesquisaType() );
        return $this->render( 'CacicRelatorioBundle:Faturamento:usuario.html.twig',
            array(
                'locale'=> $locale,
                'form' => $form->createView()
            )
        );
    }

    /*
     * Página que exibe o resultado da consulta de usuário logado
     */
    public function usuarioDetalharAction( Request $request) {

        $locale = $request->getLocale();
        $form = $this->createForm( new UserPesquisaType() );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            $data = $form->getData();

            $filtroLocais = array(); // Inicializa array com locais a pesquisar
            foreach ( $data['idLocal'] as $locais ) {
                array_push( $filtroLocais, $locais->getIdLocal() );
            }

            $filtro_geral = array();

            $dataInicio = $data['dtAcaoInicio'];
            $dataFim = $data['dtAcaoFim'];
            $usuario = $data['usuario'];
            $nmComputador = $data['nmComputador'];
            $teIpComputador = $data['teIpComputador'];
            $teNodeAddress = $data['teNodeAddress'];
            $usuarioPatrimonio = $data['usuarioPatrimonio'];
            $usuarioName = $data['usuarioName'];
            $coordenacao = $data['coordenacao'];
            $sala = $data['sala'];
            $usuarioLogado = $data['usuarioLogado'];
            $macCompDinamico = $data['macCompDinamico'];
            $ipCompDinamico = $data['ipCompDinamico'];
            $semData = 'N';

            //verifica se a busca é pelo campo "usuario dinamico"
            if(empty($usuarioLogado) AND empty($macCompDinamico) AND empty($ipCompDinamico)){
                $dados = $this->getDoctrine()
                    ->getRepository('CacicCommonBundle:LogUserLogado')
                    ->gerarRelatorioUsuario($filtros = array(),$filtroLocais, $dataInicio, $dataFim, $usuario, $nmComputador, $teIpComputador, $teNodeAddress, $usuarioPatrimonio, $usuarioName, $coordenacao, $sala);
                return $this->render(
                    'CacicRelatorioBundle:Faturamento:usuarioDetalhar.html.twig',
                    array(
                        'idioma'        => $locale,
                        'dados'         => ( isset( $dados ) ? $dados : null ),
                        'idRede'        => $filtroLocais,
                        'dtAcaoInicio'  => $dataInicio,
                        'dtAcaoFim'     => $dataFim,
                        'data'          => $data
                    )
                );
            } else {
                $dados = $this->getDoctrine()->getRepository('CacicCommonBundle:LogUserLogado')->gerarRelatorioUsuarioHistorico($usuarioLogado, $dataFim, $dataInicio, $semData, $macCompDinamico, $ipCompDinamico);
                return $this->render(
                    'CacicRelatorioBundle:Faturamento:usuarioHistorico.html.twig',
                    array(
                        'idioma'        => $locale,
                        'dados'         => ( isset( $dados ) ? $dados : null ),
                        'dtAcaoInicio'  => $dataInicio,
                        'dtAcaoFim'     => $dataFim,
                        'semData'       => $semData,
                        'usuarioLogado' => $usuarioLogado,
                        'data'          => $data
                    )
                );
            }

        }

    }

    /*
     * Gera o CSV do relatório de usuário logado
     */
    public function usuarioCsvAction( Request $request) {

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

            $dataInicio = $request->get('dtAcaoInicio');
            $dataFim = $request->get('dtAcaoFim');
            $usuario = $request->get('usuario');
            $nmComputador = $request->get('nmComputador');
            $teIpComputador = $request->get('teIpComputador');
            $teNodeAddress = $request->get('teNodeAddress');
            $usuarioPatrimonio = $request->get('usuarioPatrimonio');
            $usuarioName = $request->get('usuarioName');
            $coordenacao = $request->get('coordenacao');
            $sala = $request->get('sala');

            $dados = $this->getDoctrine()
                ->getRepository('CacicCommonBundle:LogUserLogado')
                ->gerarRelatorioUsuario($filtros = array(),$filtroLocais, $dataInicio, $dataFim, $usuario, $nmComputador, $teIpComputador, $teNodeAddress, $usuarioPatrimonio, $usuarioName, $coordenacao, $sala);

        }

        // Gera CSV
        $reader = new ArrayReader($dados);

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
        $tmpfile = tempnam(sys_get_temp_dir(), 'usuario_estatico_');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $writer->writeItem(array( 'Nome computador', 'Mac Address', 'IP computador', 'Sistema Operacional', 'Local', 'Sub Rede', 'Último Usuário Logado', 'Data Último Usuário Logado', 'Nome do Responsável', 'CPF Responsável', 'Coordenacao Responsável', 'Sala do Responsável', 'Data do Pop-up'));
        $workflow->addWriter($writer);



        // Process the workflow
        $workflow->process();

        // Gera data e adiciona no nome do arquivo
        $today = date("Ymd");
        $nameArquivo = "usuario_estatico_".$today.".csv";

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$nameArquivo.'');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /*
     * Realiza a busca do user logado somente pelo nome do usuário (link da página usuarioDetalhar)
     */
    public function usuarioHistoricoAction( Request $request) {

        $locale = $request->getLocale();
        $teUltimoLogin = $request->get('te_ultimo_login');
        $semData = 'S';
        $usuarioLogado = strstr($teUltimoLogin, substr('\ ',0,1));
        $size = strlen($usuarioLogado);
        $usuarioLogado = substr($usuarioLogado,1, $size);
        $data = array();
        $data['usuarioLogado'] = $usuarioLogado;
        $data['semData'] = $semData;

        $dados = $this->getDoctrine()->getRepository('CacicCommonBundle:LogUserLogado')->gerarRelatorioUsuarioHistorico($usuarioLogado,$semData);

        return $this->render(
            'CacicRelatorioBundle:Faturamento:usuarioHistorico.html.twig',
            array(
                'idioma'        => $locale,
                'dados'         => ( isset( $dados ) ? $dados : null ),
                'usuarioLogado' => $usuarioLogado,
                'semData'       => $semData,
                'data'          => $data
            )
        );
    }
    /*
     * Gera o CSV completo e filtrado dos registros dos usuários dinâmicos ( user logado)
     */

    public function usuarioCsvDinamicoAction( Request $request) {

        if ( $request->isMethod('POST') )
        {
            $dataInicio = $request->get('dtAcaoInicio');
            $dataFim = $request->get('dtAcaoFim');
            $usuarioLogado = $request->get('usuarioLogado');
            $macCompDinamico = $request->get('macCompDinamico');
            $ipCompDinamico = $request->get('ipCompDinamico');

            /*
             * Verifica se existe filtro por data do CSV
             */
            if(empty($dataInicio)){
                $semData = 'S';
                $dados = $this->getDoctrine()
                    ->getRepository('CacicCommonBundle:LogUserLogado')
                    ->gerarRelatorioUsuarioHistoricoCompleto($usuarioLogado, $semData);
            }else{
                $semData = 'N';
                $dados = $this->getDoctrine()
                    ->getRepository('CacicCommonBundle:LogUserLogado')
                    ->gerarRelatorioUsuarioHistorico($usuarioLogado, $dataFim, $dataInicio, $semData, $macCompDinamico, $ipCompDinamico);
            }
        }

        // Gera CSV
        $reader = new ArrayReader($dados);

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
        $tmpfile = tempnam(sys_get_temp_dir(), 'usuario_estatico_');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        if ($semData == 'S'){
            $writer->writeItem(array('Nome computador', 'Mac Address','IP computador','Local', 'Sub Rede', 'Usuário', 'Data', 'ID Computador'));
        }else{
            $writer->writeItem(array('Nome computador', 'Mac Address','IP computador','Local', 'Sub Rede', 'CPF Responsável', 'Data Pup-up', 'Usuário', 'Data', 'ID Computador'));
        }
        $workflow->addWriter($writer);



        // Process the workflow
        $workflow->process();

        // Gera data e adiciona no nome do arquivo
        $today = date("Ymd");
        $nameArquivo = "usuario_dinamico_".$today.".csv";

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$nameArquivo.'');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }
}