<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Local;
use Cacic\CommonBundle\Form\Type\LocalType;

/**
 * 
 * CRUD da Entidade Locais
 * @author lightbase
 *
 */
class LocalController extends Controller
{

    /**
     *
     * Tela de listagem
     * @param $page
     */
    public function indexAction( $page )
    {
    	return $this->render(
        	'CacicCommonBundle:Local:index.html.twig',
        	array( 
        		'locais' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Local' )->paginar( $this->get( 'knp_paginator' ), $page )
        	)
        );
    }
    
	/**
	 * 
	 * Tela de cadastro de novo Local
	 */
	public function cadastrarAction( Request $request )
	{
		$local = new Local();
		$form = $this->createForm( new LocalType(), $local );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			
			if ( $form->isValid() )
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist( $local );
				$em->flush(); // Persiste os dados do Local
				
				/**
				 * @todo Otimizar rotina através de Post-Commit / Verificar UNIDADE TRANSACIONAL
				 * @todo Criar Configurações de Interface do Aplicativo de Coleta de Dados Patrimoniais
				 */
				$this->getDoctrine()->getRepository( 'CacicCommonBundle:ConfiguracaoLocal' )->configurarLocalFromConfigPadrao( $local );
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect( $this->generateUrl( 'cacic_local_index' ) );
			}
		}
		
		return $this->render( 
			'CacicCommonBundle:Local:cadastrar.html.twig',
			array( 'form' => $form->createView() )
		);
	}
	
	/**
	 * 
	 * Tela de edição de Local já cadastrado
	 * @param integer $idLocal
	 */
	public function editarAction( $idLocal, Request $request )
	{
		$local = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Local' )->find( $idLocal );
		if ( ! $local )
			throw $this->createNotFoundException( 'Local não encontrado' );
		
		$form = $this->createForm( new LocalType(), $local );
		
		if ( $request->isMethod('POST') )
		{
			$form->bind( $request );
			
			if ( $form->isValid() )
			{
				$this->getDoctrine()->getManager()->persist( $local );
				$this->getDoctrine()->getManager()->flush(); // Efetua a edição do Local
				
				$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
				
				return $this->redirect($this->generateUrl('cacic_local_editar', array( 'idLocal'=>$local->getIdLocal() ) ) );
			}
		}
		
		return $this->render(
			'CacicCommonBundle:Local:editar.html.twig',
			array( 'form' => $form->createView() )
		);
	}
	
	/**
	 * 
	 * [AJAX] Exclusão de Local já cadastrado
	 * @param integer $idLocal
	 */
	public function excluirAction( Request $request )
	{

        $local = $this->getDoctrine()->getRepository('CacicCommonBundle:Local')->find( $request->get('id') );

        if ( ! $local )
			throw $this->createNotFoundException( 'Local não encontrado' );

        try
        {
        	$em = $this->getDoctrine()->getManager();
        	$em->remove( $local ); // Tenta excluir o registro da base de dados
        	$em->flush();
        	
        	$out = array('status' => 'ok');
        }
        catch ( \Doctrine\DBAL\DBALException $e )
        {
        	$out = array('status' => 'error', 'code' => false);
        	if ( preg_match('#SQLSTATE\[(\d+)\]#', $e->getMessage(), $tmp) )
        		$out['code'] = $tmp[1];
        }
		
        $response = new Response( json_encode( $out ) );
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
	/**
	 * 
	 * [GRID] Redes associadas ao Local
	 */
	public function redesAction( $idLocal )
	{
		return $this->render(
        	'CacicCommonBundle:Local:redes.html.twig',
        	array( 'redes' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Rede' )->listarPorLocal( $idLocal ) )
        );
	}
	
	/**
	 * 
	 * [GRID] Usuários associados ao Local
	 */
    public function usuariosAction( $idLocal )
    {
        return $this->render(
            'CacicCommonBundle:Local:usuarios.html.twig',
            array(
                'usuarios' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:Usuario' )->listarPorLocal( $idLocal ),
                'idLocal' => $idLocal
            )
        );
    }
	
	/**
	 * 
	 * [FORM] Configurações associadas ao Local
	 */
	public function configuracoesAction( $idLocal )
	{
		return $this->render(
        	'CacicCommonBundle:Local:configuracoes.html.twig',
        	array(
        		'configuracoes' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:ConfiguracaoLocal' )->listarPorLocal( $idLocal ),
        		'idLocal' => $idLocal
        	)
        );
	}
	
	/**
	 * 
	 * Tela de importação de arquivo CSV com registros de Locais
	 */
	public function importarcsvAction( Request $request )
	{
		$form = $this->createFormBuilder()
			        ->add('arquivocsv', 'file', array('label' => 'Arquivo', 'attr' => array( 'accept' => '.csv' )))
			        ->getForm();
		
		$form->handleRequest( $request );
		
		if ( $form->isValid() )
		{
			//if ( $form['arquivocsv']->getData() instanceof \Symfony\Component\HttpFoundation\File\UploadedFile )
			{
				// Executa a importação do arquivo - grava no diretório web/upload/migracao
				$dirMigracao = realpath( dirname(__FILE__) .'/../../../../web/upload/migracao/' );
				$fileName = 'Locais_U'.$this->getUser()->getIdUsuario().'T'.time().'.csv';
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
					
					$local = new Local();
					
					// desabilita a geracao automatica do id
		            $metadata = $em->getClassMetaData(get_class($local));
		            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
					
					$local->setIdLocal( (int) $v[0] );
					$local->setNmLocal( $v[1] );
					$local->setSgLocal( $v[2] );
					$local->setTeObservacao( $v[3] );
					$em->persist( $local );
				}
				$em->flush(); // Persiste os dados dos Locais
				
				$this->get('session')->getFlashBag()->add('success', 'Importação realizada com sucesso!');
			}
			//else $this->get('session')->getFlashBag()->add('error', 'Arquivo CSV inválido!');
			
			return $this->redirect( $this->generateUrl( 'cacic_migracao_local') );
	    }
		
		return $this->render(
        	'CacicCommonBundle:Local:importarcsv.html.twig',
        	array( 'form' => $form->createView() )
        );
	}
	
}