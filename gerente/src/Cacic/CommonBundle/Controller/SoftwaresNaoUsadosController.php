<?php

namespace Cacic\CommonBundle\Controller;

use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Software;
use Cacic\CommonBundle\Form\Type\SoftwareType;


class SoftwaresNaoUsadosController extends Controller
{
    public function indexAction()
    {
       $arrSoftwaresTodosSoftwares = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Software' )->listar();
       return $this->render( 'CacicCommonBundle:SoftwaresNaoUsados:index.html.twig', array( 'TodosSoftwares' => $arrSoftwaresTodosSoftwares ) );

    }

        public function excluirAction( Request $request )
    {

        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        if ( ! $request->get('arrId') )
            throw $this->createNotFoundException( 'Software não encontrado' );


        foreach($request->get('arrId') as $id)
        {
            $software = $this->getDoctrine()->getRepository('CacicCommonBundle:Software')->find( $id );
            $em = $this->getDoctrine()->getManager();
            $em->remove( $software );
        }
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
