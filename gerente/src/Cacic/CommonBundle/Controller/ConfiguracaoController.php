<?php

namespace Cacic\CommonBundle\Controller;

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
	
	public function padraoAction()
	{
		return $this->render(
        	'CacicCommonBundle:Configuracao:padrao.html.twig',
        	array( 'configuracoes' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:ConfiguracaoPadrao' )->getArrayChaveValor() )
        );
	}
	
	public function agenteAction()
	{
		
	}
	
	public function gerenteAction()
	{
		
	}
	
}
