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
    				->add('dt_acao_inicio', 'text',array('data'=>date('d/m/Y'),'label'=>' ',))
    				->add('dt_acao_fim',    'text',array('data'=>date('d/m/Y'),'label'=>' '))
                    ->add('idLocal', 'entity',array('empty_value' => ' ',
                                                    'class' => 'CacicCommonBundle:Local',
                                                    'property' => 'nmlocal',
                                                    'multiple' => true,
                                                    'required'  => false,

                                                    'label'=> 'Disponíveis:'))
                    ->add('idLocal1', 'entity',array('empty_value' => ' ',
                                                    'class' => 'CacicCommonBundle:Rede',
                                                    'property' => 'nmrede',
                                                    'multiple' => true,
                                                    'required'  => false,
                                                    'mapped'=>false,
                                                    'label'=> 'Selecionada:'))
    				->getForm();
    	
        if ( $request->isMethod('POST') )
        {
        	$form->bind( $request );
        	$data = $form->getData();

			echo($data['dt_acao_inicio']);
            echo($data['dt_acao_fim']);
            echo($data['idLocal']);

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
