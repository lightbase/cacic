<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\PerfisAplicativosMonitorados;
use Cacic\CommonBundle\Form\Type\PerfilAplicativoMonitoradoType;

/**
 *
 * CRUD da Entidade Perfil Aplicativos Monitorados
 * @author lightbase
 *
 */
class PerfilAplicativoMonitoradoController extends Controller
{

    /**
     *
     * Tela de listagem
     * @param $page
     */
    public function indexAction( $page )
    {
        return $this->render(
            'CacicCommonBundle:PerfilAplicativoMonitorado:index.html.twig',
            array( 'perfisaplicativosmonitorados' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:PerfisAplicativosMonitorados' )->listar() )
        );
    }
    public function cadastrarAction(Request $request)
    {
        $perfil = new perfisaplicativosmonitorados();
        $form = $this->createForm( new PerfilAplicativoMonitoradoType(), $perfil );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $perfil );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do perfis aplicativos monitorados

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_perfilaplicativomonitorado_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:PerfilAplicativoMonitorado:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *  Página de editar dados do subrede
     *  @param int $idRede
     */
    public function editarAction( $idAplicativo, Request $request )
    {
        $perfil = $this->getDoctrine()->getRepository('CacicCommonBundle:Redes')->find( $idAplicativo );
        if ( ! $perfil )
            throw $this->createNotFoundException( 'Perfil Aplicativo Monitorado não encontrado' );

        $form = $this->createForm( new PerfilAplicativoMonitoradoType(), $perfil );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $perfil );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Perfil Aplicativo Monitorado


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_perfilaplicativomonitorado_editar', array( 'idAplicativo'=>$perfil->getIdAplicativo() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:PerfilAplicativoMonitorado:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
}