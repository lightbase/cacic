<?php

namespace Cacic\CommonBundle\Controller;

use Cacic\CommonBundle\Form\Type\ComputadorConsultaType;
use Cacic\WSBundle\Helper\TagValueHelper;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Computador;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 *
 * @author lightbase
 *
 */
class ComputadorController extends Controller
{

    /**
     * Tela que exibe os computadores dentro da estrutura hierárquica da organização
     *
     * @param Request $request
     * @return Response
     */
    public function navegarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $agrupar = $request->get('agrupar');
        $computadores = $em->getRepository("CacicCommonBundle:Computador")->countAll();
        $computadores_mac = $em->getRepository("CacicCommonBundle:Computador")->countMac();

        return $this->render(
            'CacicCommonBundle:Computador:navegar.html.twig',
            array(
                'locais' => $em->getRepository('CacicCommonBundle:Computador')->countPorLocal(),
                'agrupar' => $agrupar,
                'computadores' => $computadores,
                'computadores_mac' => $computadores_mac
            )
        );
    }



    public function excluirAction()
    {
    }

    /**
     * [MODAL] Exibe dados do computador e informações sobre coleta
     */
    public function detalharAction( $idComputador, Request $request)
    {
        $logger = $this->container->get('logger');
        //if ( ! $request->isXmlHttpRequest() ) // Verifica se é uma requisição AJAX
        //	throw $this->createNotFoundException( 'Página não encontrada!' );
        $d = $this->getDoctrine();

        $computador = $d->getRepository('CacicCommonBundle:Computador')->find( (int) $idComputador );
        $ultimo_acesso = $d->getRepository('CacicCommonBundle:LogAcesso')->ultimoUserName( $idComputador );

        if ( ! $computador ) {
            throw $this->createNotFoundException( 'Página não encontrada!' );
        }

        $usuario = $this->getUser()->getIdUsuario();
        $nivel = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario' )->nivel($usuario);

        if ($request->get('ativar')) {

            if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException('Operação disponível somente para administradores');
            }

            $computador->setAtivo(true);
            $computador->setIdUsuarioExclusao(null);
            $computador->setDtHrExclusao(null);

            $d->getManager()->persist($computador);
            $d->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'Computador ativado com sucesso!');
            return $this->redirect($this->generateUrl($request->get('_route'), $request->get('_route_params')));
        }

        if ($request->get('desativar')) {

            if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException('Operação disponível somente para administradores');
            }

            $computador->setAtivo(false);
            $computador->setIdUsuarioExclusao($this->getUser());
            $computador->setDtHrExclusao(new \DateTime());

            $d->getManager()->persist($computador);
            $d->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'Computador ativado com sucesso!');
            return $this->redirect($this->generateUrl($request->get('_route'), $request->get('_route_params')));
        }

        if ($request->get('forcarColeta')) {

            if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException('Operação disponível somente para administradores');
            }

            $computador->setAtivo(true);
            $computador->setForcaColeta('true');

            $d->getManager()->persist($computador);
            $d->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'Coleta forçada com sucesso!');
            return $this->redirect($this->generateUrl($request->get('_route'), $request->get('_route_params')));
        }

        $coleta = $d->getRepository('CacicCommonBundle:ComputadorColeta')->getDadosColetaComputador( $computador );

        $isNotebook = $computador->getIsNotebook();
        //$logger->debug("isNotebook%%%%%%%%%%% $isNotebook");

        $dadosColeta = array(); // Inicializa o array que agrupa os dados de coleta por Classe
        $software = array(); // Coloca a coleta de software num array separado
        $hardwares_excluidos = array(); // Grupo de coletas removidas
        $softwares_excluidos = array(); // Grupo de softwares removidos
        $excluidos = false;

        foreach ($computador->getHardwares() as $hardware) {
            if ($hardware->getClassProperty()->getIdClass()->getNmClassName() == 'SoftwareList') {
                // Ignora classe SoftwareList nesse array
                continue;
            }

            // Verifica se deve estar na lista de excluídos
            if ($hardware->getAtivo() === false) {
                $excluidos = true;
                // Aqui estará na lista de excluídos
                if (!array_key_exists($hardware->getClassProperty()->getIdClass()->getNmClassName(), $hardwares_excluidos)) {
                    $hardwares_excluidos[$hardware->getClassProperty()->getIdClass()->getNmClassName()] = array();
                }

                if (!array_key_exists($hardware->getClassProperty()->getNmPropertyName(), $hardwares_excluidos[$hardware->getClassProperty()->getIdClass()->getNmClassName()])) {
                    $hardwares_excluidos[$hardware->getClassProperty()->getIdClass()->getNmClassName()][$hardware->getClassProperty()->getNmPropertyName()] = array();
                }

                $hardwares_excluidos[$hardware->getClassProperty()->getIdClass()->getNmClassName()][$hardware->getClassProperty()->getNmPropertyName()] = $hardware;
            } else {
                // Aqui lista de coletas normais
                if (!array_key_exists($hardware->getClassProperty()->getIdClass()->getNmClassName(), $dadosColeta)) {
                    $dadosColeta[$hardware->getClassProperty()->getIdClass()->getNmClassName()] = array();
                }

                if (!array_key_exists($hardware->getClassProperty()->getNmPropertyName(), $dadosColeta[$hardware->getClassProperty()->getIdClass()->getNmClassName()])) {
                    $dadosColeta[$hardware->getClassProperty()->getIdClass()->getNmClassName()][$hardware->getClassProperty()->getNmPropertyName()] = array();
                }

                $dadosColeta[$hardware->getClassProperty()->getIdClass()->getNmClassName()][$hardware->getClassProperty()->getNmPropertyName()] = $hardware;
            }

        }

        $software['inventariados'] = array();
        $software['relatorios'] = array();
        $softwares_excluidos['inventariados'] = array();
        $softwares_excluidos['relatorios'] = array();
        foreach ($computador->getSoftwareColetado() as $propSoftware) {

            if ($propSoftware->getAtivo() === false) {
                $excluidos = true;
                // Aqui estará na lista de excluídos
                $relatorios = $propSoftware->getSoftware()->getRelatorios();
                if (sizeof($relatorios) > 0) {
                    // Primeiro agrupa por relatórios cadastrados
                    foreach ($relatorios as $elm) {
                        // Somente os softwares que não estiverem em lista de exclusão
                        if ($elm->getTipo() != 'excluir') {
                            $softwares_excluidos['relatorios'][$elm->getNomeRelatorio()] = $elm;
                        }
                    }
                } else {
                    // Se nao houver relatorio cadastrado, adiciona nos inventariados
                    $softwares_excluidos['inventariados'][$propSoftware->getClassProperty()->getNmPropertyName()] = $propSoftware;
                }
            } else {
                // Aqui está na lista normal de coletados
                $relatorios = $propSoftware->getSoftware()->getRelatorios();
                if (sizeof($relatorios) > 0) {
                    // Primeiro agrupa por relatórios cadastrados
                    foreach ($relatorios as $elm) {
                        // Somente os softwares que não estiverem em lista de exclusão
                        if ($elm->getTipo() != 'excluir') {
                            if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                                // Admin pode ver todos os relatórios
                                $software['relatorios'][$elm->getNomeRelatorio()] = $elm;
                            } elseif ($this->get('security.context')->isGranted('ROLE_GESTAO') && $elm->getTipo() == 'restrito') {
                                // Relatórios de nível restrito são para administrador e gestor
                                $software['relatorios'][$elm->getNomeRelatorio()] = $elm;
                            } elseif ($elm->getNivelAcesso() == 'pessoal' && $elm->getIdUsuario() == $this->getUser()) {
                                // Relatórios pessoais só podem ser acessados pelo próprio usuário
                                $software['relatorios'][$elm->getNomeRelatorio()] = $elm;
                            } elseif ($elm->getNivelAcesso() == 'publico') {
                                // Relatórios públicos são acessíveis para todos
                                $software['relatorios'][$elm->getNomeRelatorio()] = $elm;
                            }
                        }
                    }
                } else {
                    // Se nao houver relatorio cadastrado, adiciona nos inventariados
                    $software['inventariados'][$propSoftware->getClassProperty()->getNmPropertyName()] = $propSoftware;
                }
            }

        }

        // Única maneira de verificar se é ativo. Bug bizarro do PHP
        $sql = "SELECT (
            CASE WHEN ativo = 'f' THEN 'falso'
             ELSE 'verdadeiro'
             END) as show_ativo FROM computador WHERE id_computador = $idComputador
        ";

        $stmt = $d->getManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $ativo =  $stmt->fetchAll();
        $ativo = $ativo[0]['show_ativo'];

        // Controle de licenças
        $licencas = $d->getManager()->getRepository("CacicCommonBundle:Aquisicao")->licencasComputador($idComputador);
        $licencas_removidas = $d->getManager()->getRepository("CacicCommonBundle:Aquisicao")->removidosComputador($idComputador);


        return $this->render(
            'CacicCommonBundle:Computador:detalhar.html.twig',
            array(
                'computador'    => $computador,
                'ultimoAcesso'  => $ultimo_acesso,
                'dadosColeta'   => $dadosColeta,
                'software'      => $software,
                'ativo'         => $ativo,
                'nivel'         => $nivel,
                'softwares_excluidos' => $softwares_excluidos,
                'hardwares_excluidos' => $hardwares_excluidos,
                'licencas' => $licencas,
                'licencas_removidas' => $licencas_removidas,
                'excluidos' => $excluidos
            )
        );
    }
    public function consultarAction( Request $request )
    {
        $form = $this->createForm( new ComputadorConsultaType() );

        $computadores = array();
        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            $data = $form->getData();


            $computadores = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador')
                ->selectIp($data['teIpComputador'],$data['nmComputador'] ,$data['teNodeAddress'] );
        }


        return $this->render( 'CacicCommonBundle:Computador:consultar.html.twig',
            array(
                'form' => $form->createView(),
                'computadores' => ( $computadores )
            )
        );
    }

    public function buscarAction( Request $request )
    {
        $locale = $request->getLocale();
        $form = $this->createForm( new ComputadorConsultaType() );

        if ( $request->isMethod('POST') )
        {
            $form->handleRequest( $request );
            $data = $form->getData();
            $filtroLocais = array(); // Inicializa array com locais a pesquisar

            if (array_key_exists('idLocal', $data)) {
                foreach ( $data['idLocal'] as $locais ) {
                    array_push( $filtroLocais, $locais->getIdLocal() );
                }
            }

            $computadores = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador')
                ->selectIpAvancada(
                    $data['teIpComputador'],
                    $data['nmComputador'],
                    $data['teNodeAddress'],
                    $data['dtHrInclusao'],
                    $data['dtHrInclusaoFim']
                );
        }

        return $this->render( 'CacicCommonBundle:Computador:buscar.html.twig',
            array(
                'local'=>$locale ,
                'form' => $form->createView(),
                'computadores' => ( $computadores )
            )
        );
    }

    public function coletarAction(Request $request)
    {
        $form = $this->createForm( new ComputadorConsultaType() );
        $computadores = array();
        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            $data = $form->getData();

            $computadores = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador')
                ->selectIp($data['teIpComputador'],$data['nmComputador'],$data['teNodeAddress'],@$data['idComputador'] );

        }

        return $this->render( 'CacicCommonBundle:Computador:coletar.html.twig',
            array(
                'form' => $form->createView(),
                'computadores' => $computadores
            )
        );

    }
    /**
     *  @param int $idComputador
     */
    public function updateAction( Request $request, $idComputador)
    {
        $computador = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador' )->find( $idComputador );

        if ( !$computador )
            throw $this->createNotFoundException( 'Computador não encontrado' );
        else
        {
            $computador->setForcaColeta('true');
            $this->getDoctrine()->getManager()->persist( $computador );
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('cacic_computador_coletar'));
        }

    }

    public function updatePatrimonioAction( Request $request, $idComputador)
    {
        $computador = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador' )->find( $idComputador );

        if ( !$computador )
            throw $this->createNotFoundException( 'Computador não encontrado' );
        else
        {
            $computador->setForcaPatrimonio('S');
            $this->getDoctrine()->getManager()->persist( $computador );
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('cacic_computador_coletar') );
        }

    }

    public function versaoagenteAction()
    {
        $estatisticas = array(
            'totalVersaoAgente' => $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->countPorVersaoCacic(),
            'VersaoAgente30dias' => $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->countPorVersao30dias());

        return $this->render(
            'CacicCommonBundle:Computador:versaoagente.html.twig',
            array(
                'estatisticas' => $estatisticas
            )
        );
    }

    //Gera lista com os computadores da versão selecionada
    public function versaoagenteDetalharAllAction( Request $request) {

        $versaoAgente = $request->get('teVersaoCacic');

        $locale = $request->getLocale();
        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:Computador')
            ->versaoAgenteDetalharAll($versaoAgente);


        return $this->render(
            'CacicCommonBundle:Computador:versaoAgenteDetalhar.html.twig',
            array(
                'idioma'=> $locale,
                'dados' => ( isset( $dados ) ? $dados : null )
            )
        );
    }

    //Gera lista com os computadores da versão selecionada no período de 30 dias
    public function versaoagenteDetalharAction( Request $request) {

        $versaoAgente = $request->get('teVersaoCacic');

        $locale = $request->getLocale();
        $dados = $this->getDoctrine()
            ->getRepository('CacicCommonBundle:Computador')
            ->versaoAgenteDetalhar($versaoAgente);


        return $this->render(
            'CacicCommonBundle:Computador:versaoAgenteDetalhar.html.twig',
            array(
                'idioma'=> $locale,
                'dados' => ( isset( $dados ) ? $dados : null )
            )
        );
    }

    /**
     *
     * [AJAX][jqTree] Carrega as subredes, do local informado, com computadores monitorados
     */
    public function loadredenodesAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) {
            throw $this->createNotFoundException( 'Página não encontrada!' );
        }

        $idLocal = $request->get('idLocal');
        $agrupar = $request->get('agrupar');

        $redes = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador' )->countPorSubrede( $idLocal );

        # Monta um array no formato suportado pelo plugin-in jqTree (JQuery)
        $_tree = array();
        foreach ( $redes as $rede )
        {
            if (!empty($agrupar)) {
                $url = $this->generateUrl( 'cacic_computador_loadcompnodes', array(
                        'idSubrede' => $rede['idRede'],
                        'agrupar' => true
                    )
                );
                $label = "{$rede['teIpRede']} ({$rede['nmRede']}) [{$rede['numMac']}]";
            } else {
                $url = $this->generateUrl( 'cacic_computador_loadcompnodes', array(
                    'idSubrede'=>$rede['idRede']
                    )
                );
                $label = "{$rede['teIpRede']} ({$rede['nmRede']}) [{$rede['numComp']}]";
            }

            $_tree[] = array(
                'id'				=> $rede['idRede'],
                'label' 			=> $label,
                'url'				=> $url,
                'type'				=> 'rede',
                'load_on_demand' 	=> (bool) $rede['numComp']
            );
        }

        $response = new Response( json_encode( $_tree ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     *
     * [AJAX][jqTree] Carrega os computadores da subrede informada
     */
    public function loadcompnodesAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) {
            throw $this->createNotFoundException( 'Página não encontrada!' );
        }

        $logger = $this->get('logger');

        $agrupar = $request->get('agrupar');
        $idRede = $request->get('idSubrede');

        $date = new \DateTime();

        # Monta um array no formato suportado pelo plugin-in jqTree (JQuery)
        $_tree = array();

        if (!empty($agrupar)) {
            $comps = $this->getDoctrine()->getRepository( 'CacicCommonBundle:LogAcesso' )->gerarRelatorioRede(
                $filtros = null,
                $idRede,
                $dataInicio = null,
                $dataFim = null
            );

            foreach ( $comps as $comp )
            {
                #$computadores = implode(", ", $comp['idComputador']);
                if (is_array($comp['nmComputador'])) {
                    $nomes = implode(", ", $comp['nmComputador']);
                } else {
                    $nomes = $comp['nmComputador'];
                }

                if (is_array($comp['teIpComputador'])) {
                    $ips = implode(", ", $comp['teIpComputador']);
                } else {
                    $ips = $comp['teIpComputador'];
                }

                if (is_array($comp['teDescSo'])) {
                    $so = implode(", ", $comp['sgSo']);
                } else {
                    $so = $comp['teDescSo'];
                }

                $_label = ($nomes?:'###') .' - ';
                $_label .= $comp['teNodeAddress'] . ' - ' .$so;

                // Calculate age
                $now = new \DateTime($comp['data']);
                $interval = intval($now->diff($date)->format('%R%a'));

                if ($interval > 60) {
                    $color = "alert alert-danger";
                } elseif ($interval > 30) {
                    $color = "alert alert-warning";
                } else {
                    $color = null;
                }

                $_tree[] = array(
                    'id'				=> $comp['teNodeAddress'],
                    'label' 			=> $_label,
                    'type'				=> 'computador',
                    'load_on_demand' 	=> false,
                    'alert_class'       => $color
                );
            }
        } else {
            $comps = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador' )->listarPorSubrede( $idRede );

            foreach ( $comps as $comp )
            {
                $_label = ($comp->getNmComputador()?:'###') .' - '. $comp->getTeIpComputador();
                if ( $comp->getIdSo() )	$_label .= ' - ' .$comp->getIdSo()->getSgSo();

                // Calculate age
                $interval = intval($comp->getDtHrUltAcesso()->diff($date)->format('%R%a'));

                // Calculate age
                if ($interval > 60) {
                    $color = "alert alert-danger";
                } elseif ($interval > 30) {
                    $color = "alert alert-warning";
                } else {
                    $color = null;
                }

                $_tree[] = array(
                    'id'				=> $comp->getIdComputador(),
                    'label' 			=> $_label,
                    'type'				=> 'computador',
                    'load_on_demand' 	=> false,
                    'alert_class'       => $color
                );
            }
        }


        $response = new Response( json_encode( $_tree ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     *
     * Tela de importação de arquivo CSV com registros de Computadores
     */
    public function importarcsvAction( Request $request )
    {
        $form = $this->createFormBuilder()
            ->add('arquivocsv', 'file', array('label' => 'Arquivo', 'attr' => array( 'accept' => '.csv' )))
            ->getForm();

        if ( $request->isMethod('POST') )
        {
            $form->bindRequest( $request );
            if ( $form['arquivocsv']->getData() instanceof \Symfony\Component\HttpFoundation\File\UploadedFile )
            {
                // Executa a importação do arquivo - grava no diretório web/upload/migracao
                $dirMigracao = realpath( dirname(__FILE__) .'/../../../../web/upload/migracao/' );
                $fileName = 'Comp_U'.$this->getUser()->getIdUsuario().'T'.time().'.csv';
                $form['arquivocsv']->getData()->move( $dirMigracao, $fileName );

                $em = $this->getDoctrine()->getManager();

                // Abre o arquivo salvo e começa a rotina de importação dos dados do CSV
                $csv = file( $dirMigracao.'/'.$fileName );
                foreach( $csv as $k => $v )
                {
                    // Valida a linha
                    $v = explode( ';', trim( str_replace( array('"','\N'), '', $v ) ) );
                    if ( count( $v ) != 13 )
                        continue;

                    $so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->find( (int) $v[1] );
                    $rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->findOneByTeIpRede( $v[2] );

                    $comp = new Computador();

                    if ( $so ) $comp->setIdSo( $so );
                    if ( $rede ) $comp->setIdRede( $rede );

                    $comp->setTeNodeAddress( $v[0] );
                    $comp->setTePalavraChave( $v[3] );
                    $comp->setTeIpComputador( $v[4] );
                    $comp->setDtHrInclusao( $v[5] ? new \Datetime( $v[5] ) : null );
                    $comp->setDtHrUltAcesso( $v[6] ? new \Datetime( $v[6] ) : null );
                    $comp->setTeVersaoCacic( $v[7] );
                    $comp->setTeVersaoGercols( $v[8] );
                    $comp->setDtHrColetaForcadaEstacao( $v[9] ? new \Datetime( $v[9] ) : null );
                    $comp->setTeNomesCurtosModulos( $v[10] );
                    $comp->setIdConta( $v[11] );
                    $comp->setNmComputador( $v[12] );

                    $em->persist( $comp );
                }
                $em->flush(); // Persiste os dados dos Computadores

                $this->get('session')->getFlashBag()->add('success', 'Importação realizada com sucesso!');
            }
            else $this->get('session')->getFlashBag()->add('error', 'Arquivo CSV inválido!');

            return $this->redirect( $this->generateUrl( 'cacic_migracao_computador') );
        }

        return $this->render(
            'CacicCommonBundle:Computador:importarcsv.html.twig',
            array( 'form' => $form->createView() )
        );
    }

}

