<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\SoftwareEstacao;
use Cacic\CommonBundle\Form\Type\SoftwareEstacaoType;


class SoftwareEstacaoController extends Controller
{
    public function indexAction( $page )
    {
        return $this->render(
            'CacicCommonBundle:SoftwareEstacao:index.html.twig',
            array( 'SoftwareEstacao' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:SoftwareEstacao' )->listar() ));
    }
    public function cadastrarAction(Request $request)
    {
        $SoftwareEstacao = new SoftwareEstacao();
        $form = $this->createForm( new SoftwareEstacaoType(), $SoftwareEstacao );
        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $data = $form->get('idSoftware')->getData();
                $idSoftware = $data->getIdSoftware();
                $nrPatrimonio = $form->get('nrPatrimonio')->getData();
                //Debug::dump($nrPatrimonio);die;
                $software = $this->getDoctrine()->getRepository('CacicCommonBundle:SoftwareEstacao')
                    ->find(
                        array(
                            'idSoftware' => $idSoftware,
                            'nrPatrimonio' =>$nrPatrimonio
                        )   );

                if($software != null){
                $form = $this->createForm( new SoftwareEstacaoType(), $software );
                $form->bind( $request );
                    $this->getDoctrine()->getManager()->persist( $software);
                    $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Software Estacao
                 }

                    $this->getDoctrine()->getManager()->persist( $SoftwareEstacao );
                    $this->getDoctrine()->getManager()->flush();



                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_software_estacao_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:SoftwareEstacao:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *  Página de editar dados do Software Estacao
     *  @param int $idSoftware Estacao
     */
    public function editarAction( $nrPatrimonio, Request $request )
    {
        $SoftwareEstacao = $this->getDoctrine()->getRepository('CacicCommonBundle:SoftwareEstacao')->find( $nrPatrimonio );
        if ( ! $SoftwareEstacao )
            throw $this->createNotFoundException( 'Software de Estacao não encontrado' );

        $form = $this->createForm( new SoftwareEstacaoType(), $SoftwareEstacao );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $SoftwareEstacao );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do Software Estacao


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_software_estacao_editar', array( 'nrPatrimonio'=>$SoftwareEstacao->getNrPatrimonio() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:SoftwareEstacao:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de Software Estacao já cadastrado
     * @param integer $idSoftwareEstacao
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $SoftwareEstacao = $this->getDoctrine()->getRepository('CacicCommonBundle:SoftwareEstacao')->find( $request->get('id') );
        if ( ! $SoftwareEstacao )
            throw $this->createNotFoundException( 'Software Estacao não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $SoftwareEstacao );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}