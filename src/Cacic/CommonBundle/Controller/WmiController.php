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
        $ativos = $em->getRepository('CacicCommonBundle:ClassProperty')->listarTodos();

        return $this->render(
            'CacicCommonBundle:Wmi:listar.html.twig',
            array(
                'ativos' => $ativos
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

    public function ativarBulkAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ativar = $request->request->get('property');

        if (empty($ativar)) {
            $this->get('session')->getFlashBag()->add('error', 'Elemento não encontrado!');

            return $this->redirectToRoute('cacic_wmi_listar');
        }

        // Primeiro marca como inativas todas as propredades
        $sql = "UPDATE class_property
                SET ativo = FALSE";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $em->flush();

        // Agora ativa as marcadas
        foreach ($ativar as $idClassProperty) {
            $prop = $em->getRepository("CacicCommonBundle:ClassProperty")->find($idClassProperty);
            $prop->setAtivo(true);

            $em->persist($prop);
        }

        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'Elementos ativados com sucesso!');

        return $this->redirectToRoute('cacic_wmi_listar');

    }

    public function softwareListarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ativos = $em->getRepository('CacicCommonBundle:SoftwareRelatorio')->findBy(array(
            'tipo' => 'excluir'
        ));

        return $this->render(
            'CacicCommonBundle:Wmi:softwareListar.html.twig',
            array(
                'ativos' => $ativos
            )
        );
    }

    public function softwareAtivarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
        $ativar = $request->request->get('relatorio');

        $classe = $em->getRepository("CacicCommonBundle:Classe")->findOneBy(array(
            'nmClassName' => 'SoftwareList'
        ));

        // Primeiro marca como ativas todas as propriedades
        $sql = "UPDATE class_property
                SET ativo = TRUE
                WHERE id_class = ".$classe->getIdClass();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $sql = "UPDATE software_relatorio
                SET ativo = TRUE
                WHERE tipo = 'excluir'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $em->flush();

        if (empty($ativar)) {
            $this->get('session')->getFlashBag()->add('warning', 'Nenhum elemento selecionado. Todas as copletas de software foramd desativadas!');

            return $this->redirectToRoute('cacic_wmi_software_listar');
        }

        // Agora inativa as que foram marcadas
        foreach ($ativar as $idRelatorio) {
            $relatorio = $em->getRepository("CacicCommonBundle:SoftwareRelatorio")->find($idRelatorio);
            $relatorio->setAtivo(false);

            foreach ($relatorio->getSoftwares() as $software) {
                $prop = $software->getIdClassProperty();
                if (!empty($prop)) {
                    $prop->setAtivo(false);
                    $em->persist($prop);
                } else {
                    $logger->error("ERRO NA DESATIVAÇÃO DO SOFTWARE: id_class_property vazio para o software ".$software->getNmSoftware());
                }
            }
            $em->persist($relatorio);
        }

        $em->flush();
        $this->get('session')->getFlashBag()->add('success', 'Elementos ativados com sucesso!');

        return $this->redirectToRoute('cacic_wmi_software_listar');

    }

}