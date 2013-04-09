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
                    ->add( 'idLocal', 'entity',
                                        array(
                                            'class' => 'CacicCommonBundle:Local',
                                            'property' => 'nmLocal',
                                            'multiple' => true,
                                            'required'  => false,
                                            'expanded'  => true,
                                            'label'=> 'Disponíveis:'))
    				->getForm();

        if ( $request->isMethod('POST') )
        {
        	$form->bind( $request );
        	$data = $form->getData();

            $dataInicio = implode("".'/'."",array_reverse(explode("".'/'."", $data['dt_acao_inicio'])));

            $dataFim = implode("".'/'."",array_reverse(explode("".'/'."",$data['dt_acao_fim'])));
            $idLocal =($data['idLocal']);
            foreach ($idLocal as &$locais){
                $locais = $locais;
                            }


            $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Log')->pesquisar( $dataInicio,$dataFim,$locais );


        }
        unset( $locais );
        return $this->render( 'CacicCommonBundle:Log:acesso.html.twig',
        						array(
        							'form' => $form->createView(),
        							'logs' => ( isset( $logs ) ? $logs : array() ) )
        					);
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
