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
	
	public function navegarAction()
	{
		
	}
	
	public function consultarAction()
	{
		
	}
	
	public function excluirAction()
	{
		
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
					$v = explode( ';', trim( str_replace( '"', '', $v ) ) );
					if ( count( $v ) != 4 )
						continue;
					
					$so = $this->getDoctrine()->getRepository('CacicCommonBundle:So')->find( (int) $v[1] );
					$rede = $this->getDoctrine()->getRepository('CacicCommonBundle:Rede')->findOneByTeIpRede( $v[2] );
					
					$comp = new Computador();
					
					if ( $so ) $comp->setIdSo( $so );
					if ( $rede ) $comp->setIdRede( $rede );
					
					$comp->setTeNodeAddress( $v[0] );
					$comp->setTePalavraChave( $v[3] );
					
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