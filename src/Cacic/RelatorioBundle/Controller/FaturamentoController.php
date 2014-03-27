<?php

    namespace Cacic\RelatorioBundle\Controller;

    use Cacic\CommonBundle\Entity\ComputadorColetaRepository;
    use Doctrine\Common\Util\Debug;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Cacic\CommonBundle\Form\Type\LogPesquisaType;


    class FaturamentoController extends Controller {


        public function indexAction(Request $request) {

            $locale = $request->getLocale();

            $form = $this->createForm( new LogPesquisaType() );

            return $this->render( 'CacicRelatorioBundle:Faturamento:index.html.twig',
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
            }

            return $this->render( 'CacicRelatorioBundle:Faturamento:acessoResultado.html.twig',
                array(
                    'idioma'=> $locale,
                    'form' => $form->createView(),
                    'data' =>$data,
                    'logs' => ( isset( $logs ) ? $logs : null )
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

            }

            return $this->render( 'CacicRelatorioBundle:Faturamento:inativosResultado.html.twig',
                array(
                    'idioma'=> $locale,
                    'form' => $form->createView(),
                    'data' =>$data,
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


    }
