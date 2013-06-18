<?php

namespace Cacic\CommonBundle\Controller;

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
	

	public function filtrarAction()
	{
		
	}
	
	/**
	 * 
	 * Tela que exibe os computadores dentro da estrutura hierárquica da organização
	 */
	public function navegarAction()
	{
		return $this->render(
        	'CacicCommonBundle:Computador:navegar.html.twig',
        	array( 'locais' => $this->getDoctrine()->getRepository('CacicCommonBundle:Computador')->countPorLocal() )
        );
	}
	
	public function consultarAction()
	{
		
	}
	
	public function excluirAction()
	{
		
	}
	
	public function detalharAction()
	{
		
	}
	
	/**
	 * 
	 * [AJAX][jqTree] Carrega as subredes, do local informado, com computadores monitorados
	 */
	public function loadredenodesAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() )
			throw $this->createNotFoundException( 'Página não encontrada!' );
		
		$redes = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Computador' )->countPorSubrede( $request->get('idLocal') );
		
		# Monta um array no formato suportado pelo plugin-in jqTree (JQuery)
		$_tree = array();
		foreach ( $redes as $rede )
		{
			$_tree[] = array(
				'id'				=> $rede['idRede'],
				'label' 			=> "{$rede['teIpRede']} ({$rede['nmRede']}) [{$rede['numComp']}]",
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
			$_label = $comp->getTeIpComputador();
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
					if ( count( $v ) != 12 )
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