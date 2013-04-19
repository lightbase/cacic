<?php

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\PatrimonioConfigInterface;
use Cacic\CommonBundle\Form\Type\PatrimonioType;


class PatrimonioController extends Controller
{
	
	/**
	 * 
	 * Tela de edição de interface de coleta
	 */
	public function interfaceAction(Request $request)
	{
        $patrimonio = new PatrimonioConfigInterface();
        $form = $this->createForm( new PatrimonioType(), $patrimonio );

        if ( $request->isMethod('POST') )
        {
            $form->bind( $request );
            if ( $form->isValid() )
            {
                $this->getDoctrine()->getManager()->persist( $patrimonio );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_patrimonio_interface') );
            }
        }

        return $this->render('CacicCommonBundle:Patrimonio:interface.html.twig', array( 'form' => $form->createView() ) );
	}
	
	/**
	 * 
	 * Tela de edição de opções de Coleta de informações patrimoniais e localização física
	 */
	public function opcoesAction()
	{
		return $this->render('CacicCommonBundle:Patrimonio:opcoes.html.twig');
	}
	
}
