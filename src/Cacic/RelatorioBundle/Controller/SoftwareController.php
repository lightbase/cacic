<?php

namespace Cacic\RelatorioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Reader\ArrayReader;
use Ddeboer\DataImport\Writer\CsvWriter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Ddeboer\DataImport\ValueConverter\CallbackValueConverter;
use Ddeboer\DataImport\ValueConverter\CharsetValueConverter;
use Cacic\RelatorioBundle\Form\Type\SoftwareRelatorioType;
use Cacic\CommonBundle\Entity\SoftwareRelatorio;
use Symfony\Component\Form\FormError;
use Cacic\CommonBundle\Entity\Log;

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

    /**
     * Listar computadores com o software
     *
     * @param Request $request
     * @param $idSoftware
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
     * [TELA] Filtros para relatório de Softwares Licenciados
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
     * @param Request $request
     * @param $nmSoftware
     * @param $nmLocal
     * @param $idRede
     * @return \Symfony\Component\HttpFoundation\Response
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

    /**
     * Relatório detalhado de softwares adquiridos
     *
     * @param Request $request
     * @param $idAquisicao
     * @param $idTipoLicenca
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * CSV do relatório de softwares adquiridos
     *
     * @param Request $request
     * @param $idAquisicao
     * @param $idTipoLicenca
     * @return BinaryFileResponse
     * @throws \Ddeboer\DataImport\Exception\ExceptionInterface
     * @throws \Exception
     */
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

    /**
     * Cadastrar relatório de software
     *
     * @param Request $request
     * @param $idRelatorio
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function cadastrarAction(Request $request, $idRelatorio) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        if (empty($idRelatorio)) {
            $acao = "INS";
            $software_relatorio = new SoftwareRelatorio();
        } else {

            $acao = "UPD";
            $software_relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->find($idRelatorio);

            if (empty($software_relatorio)) {
                return $this->createNotFoundException( 'Relatório não encontrado' );
            }

            // Usuário só pode editar seus próprios relatórios
            if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                if ($this->getUser()->getIdUsuario() != $software_relatorio->getIdUsuario()->getIdUsuario()) {
                    throw $this->createAccessDeniedException("Usuário só pode editar seus próprios relatórios");
                }
            }
        }

        $form = $this->createForm(
            new SoftwareRelatorioType(
                $this->get('security.authorization_checker')
            ),
            $software_relatorio
        );

        if (!empty($idRelatorio)) {
            $form->add(
                'usuario',
                'text',
                array(
                    'label' => 'Usuário que criou',
                    'read_only' => true,
                    'data' => $software_relatorio->getIdUsuario()->getNmUsuarioCompleto(),
                    'mapped' => false,
                    'required' => false
                )
            );
        }

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {

                // Verificação de perfil
                if (!$this->get('security.context')->isGranted('ROLE_GESTAO')) {
                    // Nesse caso o relatório não pode ser restrito nem público
                    if ($software_relatorio->getNivelAcesso() != "pessoal") {
                        $form->addError(new FormError("Somente os relatórios pessoais são permitidos"));
                    }
                }

                if (!empty($idRelatorio)) {
                    $this->get('logger')->debug("Removendo softwares para id_relatorio = $idRelatorio");
                    $sql = "DELETE FROM relatorios_software
                    WHERE id_relatorio = $idRelatorio";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();

                    $em->flush();
                }

                // Pega usuário da conexão
                $software_relatorio->setIdUsuario($this->getUser());

                $em->persist($software_relatorio);
                $em->flush();

                $idRelatorio = $software_relatorio->getIdRelatorio();

                $software_list = $request->get('idSoftware');

                // Garante qus os elementos do array são únicos
                if (!empty($software_list)) {
                    $software_list = array_unique($software_list);

                    foreach ($software_list as $software) {
                        $this->get('logger')->debug("Adicionando software ".$software);
                        $sql = "INSERT INTO relatorios_software (id_relatorio, id_software)
                      VALUES ($idRelatorio, $software)";
                        $stmt = $em->getConnection()->prepare($sql);
                        $stmt->execute();
                    }
                }

                // Registra no log de atividades
                $log = new Log();
                $log->setIdUsuario($this->getUser());
                $log->setCsAcao($acao);
                $log->setDtAcao(new \DateTime());
                $log->setNmScript("Cadastro de relatórios");
                $log->setNmTabela("software_relatorio");
                $log->setTeIpOrigem($request->getClientIp());

                $em->persist($log);

                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_relatorio_software_cadastrado_listar') );
            } else {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    "<p>Erro na validação do formulário</p>
                    <p>".$form->getErrors()."</p>"
                );
            }
        }

        // Mensagem de ajuda
        if ($this->get('security.context')->isGranted('ROLE_GESTAO')) {
            // Nesse caso o relatório não pode ser restrito
            $ajuda = "As opções válidas são:
                <dl>
                    <dt>Público</dt>
                    <dd>O relatório está disponível para todos os usuários</dd>
                    <dt>Restrito</dt>
                    <dd>Relatório restrito as usuários com perfis Gestor e Administrador</dd>
                    <dt>Pessoal</dt>
                    <dd>Somente você pode ver esse relatório</dd>
                </dl>
            ";
        } else {
            $ajuda = "As opções válidas são:
                <dl>
                    <dt>Pessoal</dt>
                    <dd>Somente você pode ver esse relatório</dd>
                </dl>
            ";
        }

        return $this->render(
            'CacicRelatorioBundle:Software:cadastrar.html.twig',
            array(
                'idioma' => $locale,
                'form' => $form->createView(),
                'software_list' => $software_relatorio->getSoftwares(),
                'ajuda' => $ajuda
            )
        );

    }

    /**
     * Relatório customizado de software
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function softwaresAction()
    {
        $em = $this->getDoctrine()->getManager();
        $locais = $em->getRepository('CacicCommonBundle:Local')->listar();
        $so = $em->getRepository('CacicCommonBundle:So')->listar();
        $redes = $em->getRepository('CacicCommonBundle:Rede')->listar();

        $user = $this->getUser();

        if ($this->get('security.context')->isGranted('ROLE_GESTAO')) {
            $sw = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->findAll();
        } else {
            $sw = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->findUser($user->getIdUsuario());
        }

        return $this->render(
            'CacicRelatorioBundle:Software:softwares_filtro.html.twig',
            array(
                'softwares'	=> $sw,
                'locais' 	=> $locais,
                'redes'     => $redes,
                'so'		=> $so
            )
        );
    }

    /**
     * Relatório de software customizado
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nomeAction(Request $request) {
        $locale = $request->getLocale();
        $filtros = $request->get('rel_filtro_software');

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:SoftwareRelatorio')
            ->gerarRelatorio( $filtros );


        $TotalnumComp = array();
        foreach ($dados as $cont){
            if (!array_key_exists($cont['nomeRelatorio'], $TotalnumComp)) {
                $TotalnumComp[$cont['nomeRelatorio']] = $cont['numComp'];
            } else {
                $TotalnumComp[$cont['nomeRelatorio']] += $cont['numComp'];
            }
        }

        return $this->render(
            'CacicRelatorioBundle:Software:rel_softwares.html.twig',
            array(
                'idioma' =>$locale,
                'dados' => $dados,
                'totalnumcomp' => $TotalnumComp,
                'filtros' => $filtros
            )
        );
    }

    /**
     * Lista de computadores do relatório de software customizado
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detalharAction(Request $request) {
        $locale = $request->getLocale();
        $em = $this->getDoctrine()->getManager();

        $idRelatorio = $request->get('idRelatorio');
        $nomeRelatorio = $request->get('nomeRelatorio');
        $idLocal = $request->get('idLocal');
        $idRede = $request->get('idRede');
        $idSo = $request->get('idSo');

        $filtros = array(
            'softwares' => $idRelatorio,
            'nomeRelatorio' => $nomeRelatorio,
            'locais' => $idLocal,
            'redes' => $idRede,
            'so' => $idSo
        );

        $saida = array();
        if (is_array($idRelatorio) && !empty($idRelatorio)) {
            foreach ($idRelatorio as $elm) {
                $relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->find($elm);
                if (!empty($relatorio)) {
                    array_push($saida, $relatorio);
                }
            }
        } elseif(!empty($idRelatorio)) {
            $relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->find($idRelatorio);
            if (!empty($relatorio)) {
                array_push($saida, $relatorio);
            }
        }

        if (is_array($nomeRelatorio) && !empty($nomeRelatorio)) {
            foreach ($nomeRelatorio as $elm) {
                $relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->findOneBy(array(
                    'nomeRelatorio' => $elm
                ));
                if (!empty($relatorio)) {
                    array_push($saida, $relatorio);
                }
            }
        } elseif(!empty($nomeRelatorio)) {
            $relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->findOneBy(array(
                'nomeRelatorio' => $nomeRelatorio
            ));
            if (!empty($relatorio)) {
                array_push($saida, $relatorio);
            }
        }

        $dados = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->gerarRelatorioDetalhar($filtros);


        return $this->render(
            'CacicRelatorioBundle:Software:rel_softwares_detalhar.html.twig',
            array(
                'idioma' =>$locale,
                'dados' => $dados,
                'softwares' => $saida,
                'filtros' => $filtros
            )
        );
    }

    /**
     * CSV dos relatórios com base no nome
     *
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \Ddeboer\DataImport\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function nomeCsvAction(Request $request) {
        $locale = $request->getLocale();

        $idRelatorio = $request->get('softwares');
        $nomeRelatorio = $request->get('nomeRelatorio');
        $idLocal = $request->get('locais');
        $idRede = $request->get('redes');
        $idSo = $request->get('so');

        $filtros = array(
            'softwares' => $idRelatorio,
            'nomeRelatorio' => $nomeRelatorio,
            'locais' => $idLocal,
            'redes' => $idRede,
            'so' => $idSo
        );

        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:SoftwareRelatorio')
            ->gerarRelatorioCsv( $filtros );

        // Gera CSV
        $reader = new ArrayReader($dados);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'Relatorio-Software.csv');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $writer->writeItem(array('Nome do relatório', 'Local', 'IP da Subrede', 'Rede', 'Sistema Operacional', 'Total de Estações'));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Relatório-Software.csv"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;

    }

    /**
     * CSV do relatório detalhado
     *
     * @param Request $request
     * @return BinaryFileResponse
     * @throws \Ddeboer\DataImport\Exception\ExceptionInterface
     * @throws \Exception
     */
    public function detalharCsvAction(Request $request) {
        $locale = $request->getLocale();
        $em = $this->getDoctrine()->getManager();

        $idRelatorio = $request->get('softwares');
        $nomeRelatorio = $request->get('nomeRelatorio');
        $idLocal = $request->get('locais');
        $idRede = $request->get('redes');
        $idSo = $request->get('so');

        $filtros = array(
            'softwares' => $idRelatorio,
            'nomeRelatorio' => $nomeRelatorio,
            'locais' => $idLocal,
            'redes' => $idRede,
            'so' => $idSo
        );

        $saida = array();
        if (is_array($idRelatorio) && !empty($idRelatorio)) {
            foreach ($idRelatorio as $elm) {
                $relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->find($elm);
                if (!empty($relatorio)) {
                    array_push($saida, $relatorio);
                }
            }
        } elseif(!empty($idRelatorio)) {
            $relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->find($idRelatorio);
            if (!empty($relatorio)) {
                array_push($saida, $relatorio);
            }
        }

        if (is_array($nomeRelatorio) && !empty($nomeRelatorio)) {
            foreach ($nomeRelatorio as $elm) {
                $relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->findOneBy(array(
                    'nomeRelatorio' => $elm
                ));
                if (!empty($relatorio)) {
                    array_push($saida, $relatorio);
                }
            }
        } elseif(!empty($nomeRelatorio)) {
            $relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->findOneBy(array(
                'nomeRelatorio' => $nomeRelatorio
            ));
            if (!empty($relatorio)) {
                array_push($saida, $relatorio);
            }
        }

        $dados = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->gerarRelatorioDetalharCsv($filtros);

        // Gera CSV
        $reader = new ArrayReader($dados);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $converter = new CallbackValueConverter(function ($input) {
            return $input->format('d/m/Y H:m:s');
        });
        $workflow->addValueConverter('dtHrUltAcesso', $converter);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'Relatorio-Software-Detalhado.csv');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $writer->writeItem(array(
            'Computador',
            'MAC Address',
            'Endereço IP',
            'Sistema Operacional',
            'Local',
            'Ip da Rede',
            'Nome da Rede',
            'Data/Hora do Último Acesso'
        ));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Relatório-Software-Detalhado.csv"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * Lista relatórios de software cadastrados
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listarCadastradosAction(Request $request) {
        $locale = $request->getLocale();
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        if ($this->get('security.context')->isGranted('ROLE_GESTAO')) {
            $dados = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->findAll();
        } else {
            $dados = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->findUser($user->getIdUsuario());
        }

        return $this->render(
            'CacicRelatorioBundle:Software:listar_cadastrados.html.twig',
            array(
                'idioma' =>$locale,
                'dados' => $dados
            )
        );
    }

    /**
     * Excluir softwares cadastrados
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function excluirCadastradosAction( Request $request )
    {
        $em = $this->getDoctrine()->getManager();
        if ( ! $request->isXmlHttpRequest() ) {
            throw $this->createNotFoundException( 'Página não encontrada' );
        }

        $relatorio = $em->getRepository('CacicCommonBundle:SoftwareRelatorio')->find( $request->get('id') );

        if (empty($relatorio)) {
            throw $this->createNotFoundException( 'Relatório não encontrado' );
        }

        // Usuário só pode editar seus próprios relatórios
        if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            if ($this->getUser()->getIdUsuario() != $relatorio->getIdUsuario()->getIdUsuario()) {
                throw $this->createAccessDeniedException("Usuário só pode editar seus próprios relatórios");
            }
        }

        $em->remove( $relatorio );

        // Registra no log de atividades
        $log = new Log();
        $log->setIdUsuario($this->getUser());
        $log->setCsAcao("DEL");
        $log->setDtAcao(new \DateTime());
        $log->setNmScript("Cadastro de relatórios");
        $log->setNmTabela("software_relatorio");
        $log->setTeIpOrigem($request->getClientIp());

        $em->persist($log);

        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Relatório removido com sucesso!');

        $response = new JsonResponse();
        $response->setContent(json_encode(array(
            'status' => 'ok'
        )));
        $response->setStatusCode(200);

        return $response;
    }

}
