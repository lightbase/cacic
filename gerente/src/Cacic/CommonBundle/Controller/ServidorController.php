<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cacic\CommonBundle\Entity\ServidoresAutenticacao;
#use Cacic\CommonBundle\Form\Type\ServidoresType;

class ServidorController extends Controller
{
     public function indexAction( $page )
     {
         $arrServidor = $this->getDoctrine()->getRepository( 'CacicCommonBundle:ServidoresAutenticacao' )->listar();
         return $this->render( 'CacicCommonBundle:Servidor:index.html.twig', array( 'servidores' => $arrServidor ) );

     }

}