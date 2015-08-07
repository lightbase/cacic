<?php

namespace Cacic\CommonBundle\Controller;

use Cacic\CommonBundle\Entity\ConfiguracaoLocal;
use Cacic\CommonBundle\Entity\ConfiguracaoPadrao;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\ConfiguracoesPadrao;
use Cacic\CommonBundle\Form\Type\ConfiguracaoPadraoType;

/**
 * 
 * CRUD da Entidade Locais
 * @author lightbase
 *
 */
class ConfiguracaoController extends Controller
{
	/**
	 * 
	 * Tela de edição das configurações-padrão do sistema
	 */
	public function padraoAction()
	{
		return $this->render(
        	'CacicCommonBundle:Configuracao:padrao.html.twig',
        	array( 'configuracoes' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:ConfiguracaoPadrao' )->findAll() )
        );
	}
	
	/**
	 * 
	 * Tela de edição das configurações dos agentes, por local
	 */
	public function agenteAction()
	{
		$repository = $this->getDoctrine()->getRepository( 'CacicCommonBundle:ConfiguracaoLocal' );
		
		/**
		 * @todo no caso de ser um usuário administrativo, exibir lista com todos os locais cadastrados
		 * @var int
		 */
		$local = $this->getUser()->getIdLocal(); // Recupera o Local da sessão do usuário logado
		
		/**
		 * Verifica se o Local possui configurações próprias e, se não possuir, cria-as a partir das configurações-padrão
		 */
		if ( ! $repository->hasConfiguracoes($local) )
			$repository->configurarLocalFromConfigPadrao( $local );
		
		return $this->render(
        	'CacicCommonBundle:Configuracao:agente.html.twig',
        	array(
        		'configuracoes' => $repository->getArrayChaveValor( $local ),
        		'local' => $local
        	)
        );
	}
	
	/**
	 *
	 * Tela de edição das configurações do Gerente, por Local
	 */
	public function gerenteAction()
	{
		$repository = $this->getDoctrine()->getRepository( 'CacicCommonBundle:ConfiguracaoLocal' );
		
		/**
		 *
		 * @todo no caso de ser um usuário administrativo, exibir lista com todos os locais cadastrados
		 * @var int
		 */
		$local = $this->getUser()->getIdLocal(); // Recupera o Local da sessão do usuário logado
		
		/**
		 * Verifica se o Local possui configurações próprias e, se não possuir, cria-as a partir das configurações-padrão
		 */
		if ( ! $repository->hasConfiguracoes($local) )
			$repository->configurarLocalFromConfigPadrao( $local );
	
		return $this->render(
				'CacicCommonBundle:Configuracao:gerente.html.twig',
				array(
						'configuracoes' => $repository->getArrayChaveValor( $local ),
						'local' => $local
				)
		);
	}
	
	/**
	 * 
	 * [AJAX] Salva a configuração padrão parametrizada via POST
	 */
	public function salvarconfiguracaoAction( Request $request )
	{
		if ( ! $request->isXmlHttpRequest() ) {
            throw $this->createNotFoundException( 'Página não encontrada' );
        }

        $idConfiguracao = $request->get('idConfiguracao');
        if (empty($idConfiguracao)) {
            $this->get('session')->getFlashBag()->add(
                'error',
                'Erro na alteração da configuração.'
            );
            throw $this->createNotFoundException( 'Configuração não encontrada' );
        }
        $vlConfiguracao = $request->get('vlConfiguracao');
        $nmConfiguracao = $request->get('nmConfiguracao');

        $idLocal = $request->get('idLocal');

        $em = $this->getDoctrine()->getManager();
		
		if ( !empty($idLocal) )
		{ // No caso de ter sido parametrizado um Local, trata-se de edição do local informado
			/**
			 * @todo Checar se o usuário tem privilégios para alterar o local parametrizado
			 */
			$configuracao_local = $em->getRepository('CacicCommonBundle:ConfiguracaoLocal')
												->findOneBy( array(
															'idConfiguracao' => $idConfiguracao,
															'idLocal' => $idLocal
														)
												);
            if ( empty($configuracao_local )) {

                if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        'Erro na alteração da configuração.'
                    );

                    throw $this->createNotFoundException( 'Configuração não encontrada' );
                } else {
                    $configuracao = $em->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->find( $idConfiguracao );
                    if (empty($configuracao)) {
                        $configuracao = new ConfiguracaoPadrao();
                        $configuracao->setIdConfiguracao($idConfiguracao);
                        $configuracao->setVlConfiguracao($vlConfiguracao);

                        if (empty($nmConfiguracao)) {
                            $configuracao->setNmConfiguracao($idConfiguracao);
                        } else {
                            $configuracao->setNmConfiguracao($nmConfiguracao);
                        }

                        $em->persist($configuracao);

                    }

                    $local = $em->getRepository("CacicCommonBundle:Local")->find($idLocal);
                    $configuracao_local = new ConfiguracaoLocal();
                    $configuracao_local->setIdConfiguracao($idConfiguracao);
                    $configuracao_local->setIdLocal($local);
                }
            }

            $configuracao_local->setVlConfiguracao( $vlConfiguracao );
            $em->persist($configuracao_local);

		} else {
            // ... do contrário, altera a configuração padrão
            $configuracao = $em->getRepository('CacicCommonBundle:ConfiguracaoPadrao')->find( $idConfiguracao );
            if ( empty($configuracao )) {
                if(!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        'Erro na alteração da configuração.'
                    );

                    throw $this->createNotFoundException( 'Configuração não encontrada' );
                } else {
                    $configuracao = new ConfiguracaoPadrao();
                    $configuracao->setIdConfiguracao($idConfiguracao);

                    if (empty($nmConfiguracao)) {
                        $configuracao->setNmConfiguracao($idConfiguracao);
                    } else {
                        $configuracao->setNmConfiguracao($nmConfiguracao);
                    }
                }
            }
            $configuracao->setVlConfiguracao( $request->get('vlConfiguracao') );
            $em->persist($configuracao);

            // E atualiza o padrão para todos os locais
            $locais_list = $em->getRepository('CacicCommonBundle:Local')->findAll();

            foreach ($locais_list as $local) {
                $configuracao_local = $em->getRepository('CacicCommonBundle:ConfiguracaoLocal')
                    ->findOneBy( array(
                            'idConfiguracao' => $request->get('idConfiguracao'),
                            'idLocal' => $local->getIdLocal()
                        )
                    );

                if (empty($configuracao_local)) {
                    $configuracao_local = new ConfiguracaoLocal();
                    $configuracao_local->setIdLocal($local);
                    $configuracao_local->setIdConfiguracao($configuracao);
                }
                $configuracao_local->setVlConfiguracao($vlConfiguracao);
                $em->persist($configuracao_local);
            }
        }

		$em->flush();

        $this->get('session')->getFlashBag()->add(
            'success',
            'Configuração alterada com sucesso.'
        );
		
		$response = new Response( json_encode( array('status' => 'ok') ) );
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
}
