<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 01/12/14
 * Time: 15:25
 */

namespace Cacic\RelatorioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\WSBundle\Helper\TagValueHelper;

class ComputadorController extends Controller {

    /**
     * Gera relatório com histórico de coletas para o computador/classe escolhidos
     *
     * @param Request $request
     * @param $idComputador
     * @param $classe
     * @return Response
     */
    public function historicoAction(Request $request, $idComputador, $classe) {

        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();
        $logger = $this->get('logger');

        $historico = $em->getRepository('CacicCommonBundle:ComputadorColetaHistorico')->listar(
            $limit = 10,
            $idComputador,
            $classe
        );

        $saida = array();
        foreach ($historico as $coletas) {
            #$logger->debug("333333333333333333333333333333 ".$coletas['nmPropertyName']. " ".$coletas['teClassPropertyValue']);
            $saida[$coletas['dtHrInclusao']->format('d-m-Y')][$coletas['nmPropertyName']] = TagValueHelper::getTableValues($coletas['teClassPropertyValue']);
        }

        return $this->render('CacicRelatorioBundle:Computador:historico.html.twig', array(
            'saida' => $saida,
            'classe' => $classe,
            'idioma' => $locale
        ));

    }
} 