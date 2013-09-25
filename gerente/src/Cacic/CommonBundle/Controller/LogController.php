<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\InsucessoInstalacao;
use Cacic\CommonBundle\Entity\Log;
use Cacic\CommonBundle\Form\Type\LogPesquisaType;
use Cacic\CommonBundle\Form\Type\InsucessoInstalacaoPesquisaType;
use Cacic\CommonBundle\Form\Type\SrcacicConexaoPesquisaType;

/**
 *
 * CRUD da Entidade Log
 * @author lightbase
 *
 */
class LogController extends Controller
{

	/**
	 * 
	 * Tela de pesquisa dos LOGS de ACESSO
	 * @param Symfony\Component\HttpFoundation\Request $request
	 */
    public function acessoAction( Request $request )
    {
    	$form = $this->createForm( new LogPesquisaType() );
    				
        if ( $request->isMethod('POST') )
        {
        	$form->bind( $request );
        	$data = $form->getData();

            $filtroLocais = array(); // Inicializa array com locais a pesquisar
            foreach ( $data['idLocal'] as $locais )
                array_push( $filtroLocais, $locais->getIdLocal() );
			
            $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Log')
            							->pesquisar( 'ACE', $data['dtAcaoInicio'], $data['dtAcaoFim'], $filtroLocais);

        }
        
        return $this->render( 'CacicCommonBundle:Log:acesso.html.twig',
			array(
				'form' => $form->createView(),
				'logs' => ( isset( $logs ) ? $logs : null )
			)
		);
    }
    
    /**
     * 
     * Tela de pesquisa dos LOGs de Atividades
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    public function atividadeAction( Request $request )
    {
        $form = $this->createForm( new LogPesquisaType() );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
        	$data = $form->getData();

            $filtroLocais = array(); // Inicializa array com locais a pesquisar
            foreach ( $data['idLocal'] as $locais )
                array_push( $filtroLocais, $locais->getIdLocal() );

            $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Log')
            							->pesquisar( array('INS', 'UPD', 'DEL'), $data['dtAcaoInicio'], $data['dtAcaoFim'], $filtroLocais );
            
            if ( count( $logs ) )
            {
	            /**
	             * Contabilização dos dados (Resumo das operações)
	             * @todo implementar esta rotina no twig
	             */
	            $_operacoes = $_tabelas = $_programas = $_usuarios = array();
	            foreach ( $logs as $log )
	            {
	            	// Operações
	            	if( array_key_exists($log[0]->getCsAcao(), $_operacoes) )
	            		$_operacoes[$log[0]->getCsAcao()]++;
	            	else $_operacoes[$log[0]->getCsAcao()] = 1;
	            	
	            	// Tabelas
	            	if( array_key_exists($log[0]->getNmTabela(), $_tabelas) )
	            		$_tabelas[$log[0]->getNmTabela()]++;
	            	else $_tabelas[$log[0]->getNmTabela()] = 1;
	            	
	            	// Programas
	            	if( array_key_exists($log[0]->getNmScript(), $_programas) )
	            		$_programas[$log[0]->getNmScript()]++;
	            	else $_programas[$log[0]->getNmScript()] = 1;
	            	
	            	// Usuários
	            	if( array_key_exists($log['nmUsuarioCompleto'], $_usuarios) )
	            		$_usuarios[$log['nmUsuarioCompleto']]++;
	            	else $_usuarios[$log['nmUsuarioCompleto']] = 1;
	            }
	            
	            $resumo = array( 'operacoes' => $_operacoes, 'tabelas' => $_tabelas, 'programas' => $_programas, 'usuarios' => $_usuarios );
            }
        }
        
        return $this->render( 'CacicCommonBundle:Log:atividade.html.twig',
            array(
                'form' => $form->createView(),
                'logs' => ( isset( $logs ) ? $logs : null ),
            	'resumo' => ( isset( $resumo ) ? $resumo : null )
            )
        );
    }

    /**
     * 
     * Tela de pesquisa de LOGs de Insucessos de Instalação
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    public function insucessoinstalacaoAction( Request $request )
    {
        $form = $this->createForm( new InsucessoInstalacaoPesquisaType() );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            $data = $form->getData();

            $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:InsucessoInstalacao')
            							->pesquisar( $data['dtAcaoInicio'], $data['dtAcaoFim'] );
        }
        
        return $this->render( 'CacicCommonBundle:Log:insucesso.html.twig',
            array(
                'form' => $form->createView(),
                'logs' => ( isset( $logs ) ? $logs : null )
            )
        );
    }

    /**
     * 
     * Tela de pesquisa de LOGs de Suporte Remoto realizados
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    public function suporteremotoAction(Request $request)
    {
		$form = $this->createForm( new SrcacicConexaoPesquisaType() );
    				
        if ( $request->isMethod('POST') )
        {
        	$form->bind( $request );
        	$data = $form->getData();

            $filtroLocais = array(); // Inicializa array com locais a pesquisar
            foreach ( $data['idLocal'] as $locais )
                array_push( $filtroLocais, $locais->getIdLocal() );
			
            $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:SrcacicConexao')
            							->pesquisar( $data['dtAcaoInicio'], $data['dtAcaoFim'], $filtroLocais);

        }
        
        return $this->render( 'CacicCommonBundle:Log:suporte.html.twig',
			array(
				'form' => $form->createView(),
				'logs' => ( isset( $logs ) ? $logs : null )
			)
		);
    }
}