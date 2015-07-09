<?php

namespace Cacic\CommonBundle\Controller;

use Cacic\CommonBundle\Form\Type\ComputadorConsultaType;
use Cacic\WSBundle\Helper\TagValueHelper;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Computador;

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
        $agrupar = $request->get('agrupar');

        return $this->render(
            'CacicCommonBundle:Computador:navegar.html.twig',
            array(
                'locais' => $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->countPorLocal(),
                'agrupar' => $agrupar
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
        $listaClasses = array();
        $listaSoftwares = array();
        foreach ( $coleta as $v )
        {
            //$idClass = $v->getClassProperty()->getIdClass()->getIdClass();
            // Vamos tratar primeiro a exceção, para o caso da classe ser software
            $propriedade = $v[0]->getClassProperty();
            $classe = $propriedade->getIdClass()->getNmClassName();
            $nome_propriedade = $propriedade->getNmPropertyName();
            if ($classe == 'SoftwareList') {
                // O identificador do software está armazenado na propriedade
                // Coleta de Software
                $software[$nome_propriedade]['displayName'] = $v['displayName'];
                if (empty($v['displayName'])) {
                    // Alguns softwares não têm nome. É absurdo mas acontece
                    $software[$nome_propriedade]['displayName'] = $nome_propriedade;
                }
                $software[$nome_propriedade]['displayVersion'] = $v['displayVersion'];
                $software[$nome_propriedade]['URLInfoAbout'] = $v['URLInfoAbout'];
                $software[$nome_propriedade]['publisher'] = $v['publisher'];
            } else {
                // Outras coletas
                $dadosColeta[$classe][$nome_propriedade]['nmPropertyName'] = $propriedade->getNmPropertyName();
                $dadosColeta[$classe][$nome_propriedade]['tePropertyDescription'] = $propriedade->getTePropertyDescription();

                // Trata o valor antes de enviar
                $valor = TagValueHelper::getTableValues($v[0]->getTeClassPropertyValue());
                $dadosColeta[$classe][$nome_propriedade]['teClassPropertyValue'] = $valor;

            }


            //if ( array_key_exists( $idClass, $dadosColeta ) )
            //    $dadosColeta[ $idClass ] = array();

            //$dadosColeta[ $idClass ][] = $v;
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

        return $this->render(
            'CacicCommonBundle:Computador:detalhar.html.twig',
            array(
                'computador'    => $computador,
                'ultimoAcesso'  => $ultimo_acesso,
                'dadosColeta'   => $dadosColeta,
                'software'      => $software,
                'ativo'         => $ativo,
                'nivel'         => $nivel
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
        $form = $this->createForm( new ComputadorConsultaType() );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            $data = $form->getData();
            $locale = $request->getLocale();
            $filtroLocais = array(); // Inicializa array com locais a pesquisar
            foreach ( $data['idLocal'] as $locais )
                array_push( $filtroLocais, $locais->getIdLocal() );
            $computadores = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador')
                ->selectIpAvancada($data['teIpComputador'],$data['nmComputador'] ,$data['teNodeAddress'],$data['dtHrInclusao'],$data['dtHrInclusaoFim'] );
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
        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            $data = $form->getData();


            $computadores = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador')
                ->selectIp($data['teIpComputador'],$data['nmComputador'],$data['teNodeAddress'],$data['idComputador'] );

        }

        return $this->render( 'CacicCommonBundle:Computador:coletar.html.twig',
            array(
                'form' => $form->createView(),
                'computadores' => $computadores));

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

            return $this->redirect($this->generateUrl('cacic_computador_coletar') );
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
                $label = "{$rede['teIpRede']} ({$rede['nmRede']}) [{$rede['numMac']}]";
            } else {
                $label = "{$rede['teIpRede']} ({$rede['nmRede']}) [{$rede['numComp']}]";
            }

            $_tree[] = array(
                'id'				=> $rede['idRede'],
                'label' 			=> $label,
                'url'				=> $this->generateUrl( 'cacic_computador_loadcompnodes', array('idSubrede'=>$rede['idRede']) ),
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
        if ( ! $request->isXmlHttpRequest() )
            throw $this->createNotFoundException( 'Página não encontrada!' );

        $comps = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador' )->listarPorSubrede( $request->get('idSubrede') );

        # Monta um array no formato suportado pelo plugin-in jqTree (JQuery)
        $_tree = array();
        foreach ( $comps as $comp )
        {
            $_label = ($comp->getNmComputador()?:'###') .' - '. $comp->getTeIpComputador();
            if ( $comp->getIdSo() )	$_label .= ' - ' .$comp->getIdSo()->getSgSo();

            $_tree[] = array(
                'id'				=> $comp->getIdComputador(),
                'label' 			=> $_label,
                'type'				=> 'computador',
                'load_on_demand' 	=> false
            );
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