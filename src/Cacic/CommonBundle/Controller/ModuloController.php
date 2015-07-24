<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ModuloController extends Controller
{
	
	/**
	 * 
	 * Tela de exibição dos módulos do CACIC
	 */
	public function indexAction()
	{
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser()->getIdUsuario();
        $nivel = $em->getRepository('CacicCommonBundle:Usuario' )->nivel($usuario);

        $local = $this->getUser()->getIdLocal(); // Recupera o Local da sessão do usuário logado


        if($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $totalRedes = $em->getRepository('CacicCommonBundle:Rede')->countByLocalADM();
			$inativos = $em->getRepository("CacicCommonBundle:Acao")->listarModulosInativos();
            $modulos = $em->getRepository('CacicCommonBundle:Acao')->listarModulosOpcionais( );
        } else {
            $totalRedes = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->countByLocal( $local->getIdLocal() );
            $modulos = $em->getRepository('CacicCommonBundle:Acao')->listarModulosOpcionais( $local->getIdLocal() );
        }

        //Debug::dump($modulos);die;
		return $this->render(
			'CacicCommonBundle:Modulo:index.html.twig', 
			array(
                'modulos' => $modulos,
                'local' => $local,
                'totalRedes' => $totalRedes,
                'inativos' => $inativos
            )
		);
	}
	
	/**
	 * 
	 * Tela de configuração do módulo informado
	 * @todo Verificar padrão de codificação do Symfony
	 * @param int $idAcao
	 * @param Request $request
	 */
	public function editarAction( $idAcao, Request $request )
	{
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
		$modulo = $em->getRepository( 'CacicCommonBundle:Acao' )->find( $idAcao );
		if ( ! $modulo )
			throw $this->createNotFoundException( 'Página não encontrada' );

        if(!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            $local = $this->getUser()->getIdLocal();
            $redes = $em->getRepository( 'CacicCommonBundle:Rede' )->listarPorLocal( $local );
        } else
        {
            $local = $em->getRepository('CacicCommonBundle:Local')->findAll(); /* @todo Em caso de usuário administrativo, escolher o Local */
            $redes = $em->getRepository( 'CacicCommonBundle:Rede' )->listarPorLocalADM();
        }

		if ( $request->isMethod('POST') )
		{
			$_data = $request->get('modulo');
			
			$novasRedes = array_key_exists( 'rede', $_data ) ? $_data['rede'] : array();
			$em->getRepository( 'CacicCommonBundle:AcaoRede' )->atualizarPorLocal( $idAcao, $local, $novasRedes );
           // if (  $novasRedes )
           //     throw $this->createNotFoundException( 'Subrede não encontrado' );

			$novosSO = array_key_exists( 'so', $_data ) ? $_data['so'] : array();
			$em->getRepository( 'CacicCommonBundle:AcaoSo' )->atualizarPorLocal( $idAcao, $local, $novasRedes, $novosSO );
          //  if (  $novosSO )
            //    throw $this->createNotFoundException( 'Subrede não encontrado' );

			$novasExcecoes = array_key_exists( 'mac', $_data ) ? explode(',', $_data['mac'] ) : array();
			$em->getRepository( 'CacicCommonBundle:AcaoExcecao' )->atualizarPorLocal( $idAcao, $local, $novasRedes, $novasExcecoes );


			$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');	
			return $this->redirect( $this->generateUrl( 'cacic_modulo_editar', array('idAcao'=>$idAcao) ) );
		}


		$so = $em->getRepository( 'CacicCommonBundle:So' )->listar(); // Recupera a lista de SOs cadastrados
		
		$redesSelecionadas = array_keys( // Recupera as Redes já associadas à Ação
			$em->getRepository( 'CacicCommonBundle:AcaoRede' )->getArrayChaveValorRedesPorAcao( $idAcao )
		);
		
		$soSelecionados = array_keys( // Recupera os SOs já associados à Ação
			$em->getRepository( 'CacicCommonBundle:AcaoSo' )->getArrayChaveValorSoPorAcao( $idAcao )
		);
		
		$excecoes = $em->getRepository( 'CacicCommonBundle:AcaoExcecao' )->getArrayExcecoesPorAcao( $idAcao ); // Recupera a lista de excecoes

		return $this->render(
			'CacicCommonBundle:Modulo:editar.html.twig',
			array( 
				'modulo' => $modulo, 
				'redes' => $redes, 
				'so' => $so, 
				'redesSelecionadas' => $redesSelecionadas, 
				'soSelecionados' => $soSelecionados, 
				'excecoesCadastradas' => $excecoes
			)
		);
	}

    public function desativarAction(Request $request, $idAcao) {
        if ( ! $request->isXmlHttpRequest() ) {
            $this->get('session')->getFlashBag()->add('error', 'Página não encontrada');
            throw $this->createNotFoundException( 'Página não encontrada' );
        }

        $em = $this->getDoctrine()->getManager();

        $acao = $em->getRepository("CacicCommonBundle:Acao")->find($idAcao);

        if ( empty($acao) ) {
            $this->get('session')->getFlashBag()->add('error', 'Ação não encontrada!');
            throw $this->createNotFoundException( 'Ação não encontrada' );
        }

        $acao->setAtivo(false);
        $em->persist($acao);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Módulo desativado com sucesso');

        $response = new JsonResponse();
        $response->setContent(json_encode(array(
            'status' => 'ok'
        )));
        $response->setStatusCode(200);

        return $response;
    }

    public function ativarAction(Request $request, $idAcao) {
        if ( ! $request->isXmlHttpRequest() ) {
            $this->get('session')->getFlashBag()->add('error', 'Página não encontrada');
            throw $this->createNotFoundException( 'Página não encontrada' );
        }

        $em = $this->getDoctrine()->getManager();

        $acao = $em->getRepository("CacicCommonBundle:Acao")->find($idAcao);

        if ( empty($acao) ) {
            $this->get('session')->getFlashBag()->add('error', 'Ação não encontrada');
            throw $this->createNotFoundException( 'Ação não encontrada' );
        }

        $acao->setAtivo(true);
        $em->persist($acao);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Módulo ativado com sucesso');

        $response = new JsonResponse();
        $response->setContent(json_encode(array(
            'status' => 'ok'
        )));
        $response->setStatusCode(200);

        return $response;
    }
	
}
