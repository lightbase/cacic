<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
		$local = $this->getUser()->getIdLocal(); // Recupera o Local da sessão do usuário logado
		
		$modulos = $this->getDoctrine()->getRepository('CacicCommonBundle:Acao')->listarModulosOpcionais( $local->getIdLocal() );
		$totalRedes = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->countByLocal( $local->getIdLocal() );
		
		return $this->render(
			'CacicCommonBundle:Modulo:index.html.twig', 
			array('modulos'=>$modulos, 'local'=>$local, 'totalRedes'=>$totalRedes)
		);
	}
	
	/**
	 * 
	 * Tela de configuração do módulo informado
	 */
	public function editarAction( $idAcao )
	{
		$modulo = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Acao' )->find( $idAcao );
		if ( ! $modulo )
			throw $this->createNotFoundException( 'Página não encontrada' );
		
		/**
		 * @todo Verificar padrão de codificação do Symfony
		 */
		
		
		return $this->render(
			'CacicCommonBundle:Modulo:editar.html.twig',
			array( 'modulo'=>$modulo )
		);
	}
	
}
