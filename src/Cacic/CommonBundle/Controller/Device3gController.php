<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\ComputadorColetaRepository;


class Device3gController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'CacicCommonBundle:Device3g:index.html.twig',
            array(
                'UsbDevice' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:ComputadorColeta' )->listar3g())
        );
    }

}