<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\InsucessoInstalacao;
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
                                            'required'  => true,
                                            'expanded'  => true,
                                            'label'=> 'Selecione o Local:'))
    				->getForm();

        if ( $request->isMethod('POST') )
        {
        	$form->bind( $request );
        	$data = $form->getData();

            $dataInicio = implode("".'/'."",array_reverse(explode("".'/'."", $data['dt_acao_inicio'])));

            $dataFim = implode("".'/'."",array_reverse(explode("".'/'."",$data['dt_acao_fim'])));

            $locais_enviar = array(0);
            foreach ($data['idLocal'] as $locais){
                array_push($locais_enviar,$locais->getIdLocal());
            }

            $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Log')->pesquisar( $dataInicio,$dataFim,$locais_enviar);


        }
        return $this->render( 'CacicCommonBundle:Log:acesso.html.twig',
        						array(
        							'form' => $form->createView(),
        							'logs' => ( isset( $logs ) ? $logs : array() ) )
        					);
    }
    public function atividadeAction(Request $request)
    {
        $form = $this->createFormBuilder(array('message' => 'Type your message here'))
            ->add('dt_acao_inicio', 'text',array('data'=>date('d/m/Y'),'label'=>' ',))
            ->add('dt_acao_fim',    'text',array('data'=>date('d/m/Y'),'label'=>' '))
            ->add( 'idLocal', 'entity',
            array(
                'class' => 'CacicCommonBundle:Local',
                'property' => 'nmLocal',
                'multiple' => true,
                'required'  => true,
                'expanded'  => true,
                'label'=> 'Selecione o Local:'))
            ->getForm();

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            $data = $form->getData();

            $dataInicio = implode("".'/'."",array_reverse(explode("".'/'."", $data['dt_acao_inicio'])));

            $dataFim = implode("".'/'."",array_reverse(explode("".'/'."",$data['dt_acao_fim'])));

            $locais_enviar = array(0);
            foreach ($data['idLocal'] as $locais){
                array_push($locais_enviar,$locais->getIdLocal());
            }

            $logs = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Log')->pesquisar( $dataInicio,$dataFim,$locais_enviar);


        }
        return $this->render( 'CacicCommonBundle:Log:atividade.html.twig',
            array(
                'form' => $form->createView(),
                'logs' => ( isset( $logs ) ? $logs : array() ) )
        );
    }

    public function insucessoinstalacaoAction(Request $request)
    {
        $form = $this->createFormBuilder(array('message' => 'Type your message here'))
            ->add('dt_acao_inicio', 'text',array('data'=>date('d/m/Y'),'label'=>' ',))
            ->add('dt_acao_fim',    'text',array('data'=>date('d/m/Y'),'label'=>' '))
            ->getForm();

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            $data = $form->getData();

            $dataInicio = implode("".'/'."",array_reverse(explode("".'/'."", $data['dt_acao_inicio'])));

            $dataFim = implode("".'/'."",array_reverse(explode("".'/'."",$data['dt_acao_fim'])));


            $insucesso = $this->getDoctrine()->getRepository( 'CacicCommonBundle:InsucessoInstalacao')->pesquisar( $dataInicio,$dataFim);


        }
        return $this->render( 'CacicCommonBundle:Log:insucessoInstalacao.html.twig',
            array(
                'form' => $form->createView(),
                'insucessos' => ( isset( $insucesso ) ? $insucesso : array() ) )
        );
    }

    public function suporteremotoAction(Request $request)
    {

        $form = $this->createFormBuilder(array('message' => 'Type your message here'))
            ->add('dt_acao_inicio', 'text',array('data'=>date('d/m/Y'),'label'=>' ',))
            ->add('dt_acao_fim',    'text',array('data'=>date('d/m/Y'),'label'=>' '))
            ->add( 'idLocal', 'entity',
            array(
                'class' => 'CacicCommonBundle:Local',
                'property' => 'nmLocal',
                'multiple' => true,
                'required'  => true,
                'expanded'  => true,
                'label'=> 'Selecione o Local:'))
            ->getForm();

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            $data = $form->getData();

            $dataInicio = implode("".'/'."",array_reverse(explode("".'/'."", $data['dt_acao_inicio'])));

            $dataFim = implode("".'/'."",array_reverse(explode("".'/'."",$data['dt_acao_fim'])));

            $locais_enviar = array(0);
            foreach ($data['idLocal'] as $locais){
                array_push($locais_enviar,$locais->getIdLocal());
            }

            $suporte = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Log')->pesquisar( $dataInicio,$dataFim,$locais_enviar);


        }
        return $this->render( 'CacicCommonBundle:Log:suporteRemoto.html.twig',
            array(
                'form' => $form->createView(),
                'suportes' => ( isset( $suporte ) ? $suporte : array() ) )
        );
    }
}
