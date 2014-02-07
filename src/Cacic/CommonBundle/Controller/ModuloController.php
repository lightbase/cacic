<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cacic\CommonBundle\Entity\Acao;
use Cacic\CommonBundle\Entity\AcaoRede;
use Cacic\CommonBundle\Entity\AcaoSo;
use Cacic\CommonBundle\Entity\AcaoExcecao;
use Doctrine\Common\Util\Debug;

class ModuloController extends Controller
{
	
	/**
	 * 
	 * Tela de exibição dos módulos do CACIC
	 */
	public function indexAction()
	{
        $usuario = $this->getUser()->getIdUsuario();
        $nivel = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario' )->nivel($usuario);

		$local = $this->getUser()->getIdLocal(); // Recupera o Local da sessão do usuário logado
		
		$modulos = $this->getDoctrine()->getRepository('CacicCommonBundle:Acao')->listarModulosOpcionais( $nivel, $local->getIdLocal() );
		$totalRedes = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->countByLocal( $local->getIdLocal() );
        //Debug::dump($modulos);die;
		return $this->render(
			'CacicCommonBundle:Modulo:index.html.twig', 
			array('modulos'=>$modulos, 'local'=>$local, 'totalRedes'=>$totalRedes)
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
        $usuario = $this->getUser()->getIdUsuario();
        $nivel = $this->getDoctrine()->getRepository('CacicCommonBundle:Usuario' )->nivel($usuario);


		$modulo = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Acao' )->find( $idAcao );
		if ( ! $modulo )
			throw $this->createNotFoundException( 'Página não encontrada' );

		$local = $this->getUser()->getIdLocal(); /* @todo Em caso de usuário administrativo, escolher o Local */

		if ( $request->isMethod('POST') )
		{
			$_data = $request->get('modulo');
			
			$novasRedes = array_key_exists( 'rede', $_data ) ? $_data['rede'] : array();
			$this->getDoctrine()->getRepository( 'CacicCommonBundle:AcaoRede' )->atualizarPorLocal( $idAcao, $local, $novasRedes );
			
			$novosSO = array_key_exists( 'so', $_data ) ? $_data['so'] : array();
			$this->getDoctrine()->getRepository( 'CacicCommonBundle:AcaoSo' )->atualizarPorLocal( $idAcao, $local, $novasRedes, $novosSO );
			
			$novasExcecoes = array_key_exists( 'mac', $_data ) ? explode(',', $_data['mac'] ) : array();
			$this->getDoctrine()->getRepository( 'CacicCommonBundle:AcaoExcecao' )->atualizarPorLocal( $idAcao, $local, $novasRedes, $novasExcecoes );
			
			$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');	
			return $this->redirect( $this->generateUrl( 'cacic_modulo_editar', array('idAcao'=>$idAcao) ) );
		}
         if($nivel[0]['teGrupoUsuarios'] != "Administração")
          {
              $redes = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Rede' )->listarPorLocal( $local );
          }else
              {
                 $redes = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Rede' )->listarPorLocalADM();
              }

		$so = $this->getDoctrine()->getRepository( 'CacicCommonBundle:So' )->listar(); // Recupera a lista de SOs cadastrados
		
		$redesSelecionadas = array_keys( // Recupera as Redes já associadas à Ação
			$this->getDoctrine()->getRepository( 'CacicCommonBundle:AcaoRede' )->getArrayChaveValorRedesPorAcao( $idAcao )
		);
		
		$soSelecionados = array_keys( // Recupera os SOs já associados à Ação
			$this->getDoctrine()->getRepository( 'CacicCommonBundle:AcaoSo' )->getArrayChaveValorSoPorAcao( $idAcao )
		);
		
		$excecoes = $this->getDoctrine()->getRepository( 'CacicCommonBundle:AcaoExcecao' )->getArrayExcecoesPorAcao( $idAcao ); // Recupera a lista de excecoes
		
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
	
}
