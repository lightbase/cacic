<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Log;
use Cacic\CommonBundle\Form\Type\LogType;

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
	 * @param Request $request
	 */
    public function acessoAction(Request $request)
    {
    	$form = $this->createFormBuilder(array('message' => 'Type your message here'))
    				->add('dt_acao_inicio', 'text')
    				->add('dt_acao_fim', 'text')
    				->getForm();
    	
        if ( $request->isMethod('POST') )
        {
        	$form->bind( $request );
        	$data = $form->getData();
        	
			//var_dump($data['dt_acao_inicio']);
			die;
			$this->getDoctrine()->getRepository('CacicCommonBundle:Log')->pesquisar( $data );
        }

        return $this->render( 'CacicCommonBundle:Log:acesso.html.twig', array( 'form' => $form->createView() ) );
    }

    public function atividadeAction()
    {

    }

    public function insucessoinstalacaoAction()
    {

    }

    public function suporteremotoAction()
    {

    }
}
