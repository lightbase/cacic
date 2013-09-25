<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\UsbDevice;
use Cacic\CommonBundle\Form\Type\UsbDeviceType;


class UsbDeviceController extends Controller
{
    public function indexAction( $page )
    {
        $arrUsbDevice = $this->getDoctrine()->getRepository( 'CacicCommonBundle:UsbDevice' )->listar();
        return $this->render( 'CacicCommonBundle:UsbDevice:index.html.twig', array( 'UsbDevice' => $arrUsbDevice ) );

    }

    public function cadastrarAction(Request $request)
    {
        $UsbDevice = new UsbDevice();
        $form = $this->createForm( new UsbDeviceType(), $UsbDevice );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $UsbDevice );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados do Usb Device

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_usbdevice_index') );
            }
        }

        return $this->render( 'CacicCommonBundle:UsbDevice:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }
    /**
     *  Página de editar dados do Usb Device
     *  @param int $idUsbDevice
     */
    public function editarAction( $idUsbDevice, Request $request )
    {
        $UsbDevice = $this->getDoctrine()->getRepository('CacicCommonBundle:UsbDevice')->find( $idUsbDevice );
        if ( ! $UsbDevice )
            throw $this->createNotFoundException( 'Dispositivo USB não encontrado' );

        $form = $this->createForm( new UsbDeviceType(), $UsbDevice );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );

            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $UsbDevice );
                $this->getDoctrine()->getManager()->flush();// Efetuar a edição do TiposSoftware


                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect($this->generateUrl('cacic_usbdevice_editar', array( 'idUsbDevice'=>$UsbDevice->getIdUsbDevice() ) ) );
            }
        }

        return $this->render( 'CacicCommonBundle:UsbDevice:cadastrar.html.twig', array( 'form' => $form->createView() ) );
    }

    /**
     *
     * [AJAX] Exclusão de UsbDevice já cadastrado
     * @param integer $idUsbDevice
     */
    public function excluirAction( Request $request )
    {
        if ( ! $request->isXmlHttpRequest() ) // Verifica se se trata de uma requisição AJAX
            throw $this->createNotFoundException( 'Página não encontrada' );

        $UsbDevice = $this->getDoctrine()->getRepository('CacicCommonBundle:UsbDevice')->find( $request->get('id') );
        if ( ! $UsbDevice )
            throw $this->createNotFoundException( 'Dispositivo USB não encontrado' );

        $em = $this->getDoctrine()->getManager();
        $em->remove( $UsbDevice );
        $em->flush();

        $response = new Response( json_encode( array('status' => 'ok') ) );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}