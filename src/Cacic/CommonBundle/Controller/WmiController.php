<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 28/08/15
 * Time: 11:07
 */

namespace Cacic\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\CommonBundle\Entity\Classe;
use Cacic\CommonBundle\Form\Type\ClasseType;

/**
 * Controllers para configuração de coleta
 *
 * Class WmiController
 * @package Cacic\CommonBundle\Controller
 */
class WmiController extends Controller
{
    /**
     * Lista propriedades já coletadas
     *
     * @return Response
     */
    public function listarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ativos = $em->getRepository('CacicCommonBundle:ClassProperty')->listarAtivos();
        $inativos = $em->getRepository('CacicCommonBundle:ClassProperty')->listarAtivos(false);

        return $this->render(
            'CacicCommonBundle:Wmi:listar.html.twig',
            array(
                'ativos' => $ativos,
                'inativos' => $inativos
            )
        );
    }

    /**
     * Ativa propriedades selecionadas
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function ativarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ativar = $request->request->get('property');

        if (empty($ativar)) {
            $this->get('session')->getFlashBag()->add('error', 'Elemento não encontrado!');

            return $this->redirectToRoute('cacic_wmi_listar');
        }

        foreach ($ativar as $idClassProperty) {
            $prop = $em->getRepository("CacicCommonBundle:ClassProperty")->find($idClassProperty);
            $prop->setAtivo(true);

            $em->persist($prop);
        }

        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'Elementos ativados com sucesso!');

        return $this->redirectToRoute('cacic_wmi_listar');

    }

    /**
     * Desativa propriedades selecionadas
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function desativarAction(Request $request)
    {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $ativar = $request->request->get('property');

        if (empty($ativar)) {
            $this->get('session')->getFlashBag()->add('error', 'Elemento não encontrado!');

            return $this->redirectToRoute('cacic_wmi_listar');
        }

        foreach ($ativar as $idClassProperty) {
            $prop = $em->getRepository("CacicCommonBundle:ClassProperty")->find($idClassProperty);
            $prop->setAtivo(false);

            $em->persist($prop);
        }

        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'Elementos desativados com sucesso!');

        return $this->redirectToRoute('cacic_wmi_listar');
    }

    public function listarClasseAction() {
        $em = $this->getDoctrine()->getManager();

        $classes = $em->getRepository("CacicCommonBundle:Classe")->findAll();

        return $this->render('CacicCommonBundle:Wmi:listarClasse.html.twig', array(
            'classes' => $classes
        ));
    }

    public function classeAction(Request $request, $idClass) {
        $em = $this->getDoctrine()->getManager();

        if (empty($idClass)) {
            $classe = new Classe();
        } else {
            $classe = $em->getRepository("CacicCommonBundle:Classe")->find($idClass);

            if (empty($classe)) {
                throw $this->createNotFoundException("Classe não encontrada");
            }
        }

        $form = $this->createForm( new ClasseType(), $classe);

        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            if ($form->isValid()) {
                // Cadastra classe
                $em->persist($classe);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Classe cadastrada com sucesso!');
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Erro no cadastro da classe!');
            }
            return $this->redirectToRoute('cacic_wmi_classe_listar');
        }

        return $this->render('CacicCommonBundle:Wmi:classe.html.twig', array(
            'form' => $form->createView()
        ));
    }

}