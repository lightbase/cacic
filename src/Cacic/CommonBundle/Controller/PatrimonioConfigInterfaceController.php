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
use Cacic\CommonBundle\Form\Type\PatrimonioType;
use Cacic\CommonBundle\Entity\ClassProperty;
use Cacic\CommonBundle\Entity\ComputadorColeta;
use Cacic\CommonBundle\Entity\ComputadorColetaHistorico;


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

    public function cadastrarAction(Request $request, $idComputador) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();

        // Cria array com dados anteriores de patrimonio
        $patrimonio_list = $em->getRepository('CacicCommonBundle:ComputadorColeta')
            ->patrimonioComputador($idComputador);
        $patrimonio = array(
            'ComputerName' => $patrimonio_list[0]['nmComputador']
        );
        foreach($patrimonio_list as $elm) {
            $patrimonio[$elm['nmPropertyName']] = $elm['teClassPropertyValue'];
        }

        $form = $this->createForm( new PatrimonioType(), $patrimonio );

        if ( $request->isMethod('POST') ) {

            $form->handleRequest($request);

            if ( $form->isValid() ) {

                $data = $form->getData();

                // Acha computador
                $computador = $em->getRepository('CacicCommonBundle:Computador')->find($idComputador);

                foreach($data as $classPropertyName => $classProperty) {
                    // Processa um campo do formulário de cada vez
                    $classPropertyObject = $em->getRepository('CacicCommonBundle:ClassProperty')->findOneBy(array(
                       'nmPropertyName' => $classPropertyName
                    ));

                    if (empty($classPropertyObject)) {

                        // Cria a propriedade se não existe
                        $classPropertyObject = new ClassProperty();
                        $idClass = $patrimonio_list[0]['idClass'];

                        $classe = $em->getRepository('CacicCommonBundle:Classe')->find($idClass);

                        $classPropertyObject->setIdClass($classe);
                        $classPropertyObject->setNmPropertyName($classPropertyName);
                        $classPropertyObject->setTePropertyDescription('On the fly created Property');

                        $em->persist($classPropertyObject);
                    }

                    $computadorColeta = $em->getRepository('CacicCommonBundle:ComputadorColeta')
                        ->findOneBy(array(
                            'computador'=> $computador,
                            'classProperty'=> $classPropertyObject->getIdClassProperty()
                        ));

                    //$idClass = $classPropertyObject->getIdClass()->getIdClass();

                    if (empty($computadorColeta)) {
                        // Se não existir nenhuma ocorrência para esse atributo, apenas adiciono
                        //error_log("3333333333333333333333333333333333333333333: Criando objeto");
                        $computadorColeta = new ComputadorColeta();

                        $computadorColeta->setComputador( $computador );

                        // Armazena no banco o objeto
                        $computadorColeta->setClassProperty($classPropertyObject);
                        $computadorColeta->setTeClassPropertyValue($classProperty);
                        $computadorColeta->setDtHrInclusao( new \DateTime() );

                        // Mando salvar os dados do computador
                        $em->persist( $computadorColeta );

                        // Persistencia de Historico
                        $computadorColetaHistorico = new ComputadorColetaHistorico();
                        $computadorColetaHistorico->setComputadorColeta( $computadorColeta );
                        $computadorColetaHistorico->setComputador( $computador );
                        $computadorColetaHistorico->setClassProperty( $classPropertyObject );
                        $computadorColetaHistorico->setTeClassPropertyValue($classProperty);
                        $computadorColetaHistorico->setDtHrInclusao( new \DateTime() );
                        $em->persist( $computadorColetaHistorico );

                    } else {
                        //error_log("444444444444444444444444444444444444444444444444: Criando histórico");
                        // Caso exista, registro um histórico e atualiza o valor atual
                        $coletaOld = "Classe WMI: ".$computadorColeta->getClassProperty()->getIdClass()->getNmClassName()." | "."Propriedade: ".$computadorColeta->getClassProperty()->getNmPropertyName()." | Valor: ".$computadorColeta->getTeClassPropertyValue();
                        $computadorColeta->setComputador( $computador );
                        // Pega o objeto para gravar
                        $classPropertyObject = $em->getRepository('CacicCommonBundle:ClassProperty')->findOneBy(array(
                            'idClassProperty'=> $classPropertyObject->getIdClassProperty()
                        ));

                        // Armazena no banco o objeto
                        $computadorColeta->setClassProperty($classPropertyObject);
                        $computadorColeta->setTeClassPropertyValue($classProperty);
                        $computadorColeta->setDtHrInclusao( new \DateTime() );

                        // Mando salvar os dados do computador
                        $em->persist( $computadorColeta );

                        // Persistencia de Historico
                        $computadorColetaHistorico = new ComputadorColetaHistorico();
                        $computadorColetaHistorico->setComputadorColeta( $computadorColeta );
                        $computadorColetaHistorico->setComputador( $computador );
                        $computadorColetaHistorico->setClassProperty( $classPropertyObject );
                        $computadorColetaHistorico->setTeClassPropertyValue($classProperty);
                        $computadorColetaHistorico->setDtHrInclusao( new \DateTime() );

                        $em->persist( $computadorColetaHistorico );

                        // Commit
                        $this->getDoctrine()->getManager()->flush();

                        // Notifica alteração
                        $coletaNew = "Classe WMI: ".$computadorColeta->getClassProperty()->getIdClass()->getNmClassName()." | "."Propriedade: ".$computadorColeta->getClassProperty()->getNmPropertyName()." | Valor: ".$computadorColeta->getTeClassPropertyValue();
                        //$this->notificaAlteracao($coletaOld, $coletaNew, $computador);
                    }

                }

                // Commit
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Dados salvos com sucesso!');
            }

        }

        return $this->render('CacicCommonBundle:PatrimonioConfigInterface:cadastrar.html.twig', array(
            'form' => $form->createView(),
            'idComputador' => $idComputador
        ));
    }
	
}
