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

/**
 * Controllers para configuração de coleta
 *
 * Class WmiController
 * @package Cacic\CommonBundle\Controller
 */
class WmiController extends Controller
{
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

}