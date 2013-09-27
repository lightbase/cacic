<?php

namespace Cacic\CommonBundle\Controller;

use Cacic\CommonBundle\Entity\PatrimonioConfigInterfaceRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\PatrimonioConfigInterface;
use Cacic\CommonBundle\Form\Type\PatrimonioConfigInterfaceType;
use Cacic\CommonBundle\Form\Type\OpcoesType;
use Doctrine\Common\Util\Debug;


class PatrimonioConfigInterfaceController extends Controller
{

    public function indexAction()
    {
        return $this->render( 'CacicCommonBundle:PatrimonioConfigInterface:index.html.twig' );
    }
	
	/**
	 * 
	 * Tela de edição de interface de coleta
	 */
	public function interfaceAction($idEtiqueta, Request $request)
	{

        /**
         *
         * @todo no caso de ser um usuário administrativo, exibir lista com todos os locais cadastrados
         * @var int
         */
        $local = $this->getUser()->getIdLocal(); // Recupera o Local da sessão do usuário logado
        $patrimonio = $this->getDoctrine()->getRepository( 'CacicCommonBundle:PatrimonioConfigInterface' )
                                            ->find(
                                                array(
                                                        'idEtiqueta' => $idEtiqueta,
                                                        'local' => $local->getIdLocal()
                                                    )
        );

        if (empty($patrimonio)) {
            $patrimonio = new PatrimonioConfigInterface();
            $patrimonio->setIdEtiqueta($idEtiqueta);
            $patrimonio->setLocal($local);
        }


        $form = $this->createForm( new PatrimonioConfigInterfaceType(), $patrimonio );
        if ( $request->isMethod('POST') )
        {

            $form->bind( $request );

            //if ($form->isValid()) {

                // Salva
                $this->getDoctrine()->getManager()->persist( $patrimonio );
                $this->getDoctrine()->getManager()->flush(); //Persiste os dados

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');

                return $this->redirect( $this->generateUrl( 'cacic_patrimonio_index' ));
            //}

            return $this->redirect( $this->generateUrl( 'cacic_patrimonio_index' ));

        }

        return $this->render('CacicCommonBundle:PatrimonioConfigInterface:'.$idEtiqueta.'.html.twig', array( 'form' => $form->createView() ) );
	}
	
	/**
	 * 
	 * Tela de edição de opções de Coleta de informações patrimoniais e localização física
	 */
	public function opcoesAction( Request $request )
	{
		if ( $request->isMethod('POST') )
        { // Se dados foram submetidos
        	$_data = $request->get('config');
        	
        	$this->getDoctrine()->getRepository( 'CacicCommonBundle:PatrimonioConfigInterface' )->atualizarOpcoesDestacarDuplicidade( array_keys($_data), $_data['idLocal'] );
        	$this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
        }
		
		/**
         *
         * @todo no caso de ser um usuário administrativo, exibir lista com todos os locais cadastrados
         * @var int
         */
        $local = $this->getUser()->getIdLocal(); // Recupera o Local da sessão do usuário logado

        return $this->render(
        	'CacicCommonBundle:PatrimonioConfigInterface:opcoes.html.twig', 
        	array(
        		'opcoes' => $this->getDoctrine()->getRepository( 'CacicCommonBundle:PatrimonioConfigInterface' )->getOpcoesDestaqueDuplicidade( $local ),
        		'local' => $local
        	)
        );
	}
	
}
