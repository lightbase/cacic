<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/04/15
 * Time: 12:47
 */

namespace Cacic\WSBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Cacic\CommonBundle\Entity\ErrosAgente;

class NeoLogController extends NeoController {

    /**
     * Registra erros do Agente
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logErroAction(Request $request){

        $logger = $this->get('logger');
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();

        $dados = json_decode($status, true);

        $response = new JsonResponse();

        if (empty($dados)) {
            $logger->error("LOG ERRO: JSON INVÁLIDO!!!!!!!!!!!!!!!!!!!");
            // Retorna erro se o JSON for inválido
            $error_msg = '{
                "message": "JSON Inválido",
                "codigo": 1
            }';


            $response = new JsonResponse();
            $response->setStatusCode('500');
            $response->setContent($error_msg);
            return $response;
        }

        // Identifica computador
        $computador = $this->getComputador($dados, $request);

        if (empty($computador)) {
            $logger->error("LOG ERRO: Erro na identificação do computador no registro de erros.");

            $error_msg = '{
                "message": "Computador não identificado",
                "codigo": 2
            }';

            $response = new JsonResponse();
            $response->setStatusCode('500');
            $response->setContent($error_msg);
            return $response;
        }

        $info = @$dados['logInfo'];
        if (!empty($info)) {
            foreach ($info as $entry) {
                $timestamp = \DateTime::createFromFormat('d-m-Y H:i:s.u', $entry['timestamp']);

                $log_erro = $em->getRepository("CacicCommonBundle:ErrosAgente")->findOneBy(array(
                    'computador' => $computador->getIdComputador(),
                    'timestampErro' => $timestamp
                ));

                if (empty($log_erro)) {
                    $logger->debug("Inserindo novo log para o computador = ".$computador->getTeIpComputador()." timestamp = ".$timestamp->format('d-m-Y H:i:s.u'));

                    $log_erro = new ErrosAgente();
                    $log_erro->setTimestampErro($timestamp);
                    $log_erro->setComputador($computador);
                    $log_erro->setNivelErro('info');
                    $log_erro->setMensagem($entry['message']);

                    // Eduardo: 2015-08-14
                    // FIXME: Adicionar um parâmetro para habilitar isso.
                    // Por enquanto desabilita gravação de logs de info

                    //$em->persist($log_erro);

                }
            }
        }

        $erros = @$dados['logError'];
        if (!empty($erros)) {
            foreach ($erros as $entry) {
                $timestamp = \DateTime::createFromFormat('d-m-Y H:i:s.u', $entry['timestamp']);

                $log_erro = $em->getRepository("CacicCommonBundle:ErrosAgente")->findOneBy(array(
                    'computador' => $computador->getIdComputador(),
                    'timestampErro' => $timestamp
                ));

                if (empty($log_erro)) {
                    $logger->debug("Inserindo novo log para o computador = ".$computador->getTeIpComputador()." timestamp = ".$timestamp->format('d-m-Y H:i:s.u'));

                    $log_erro = new ErrosAgente();
                    $log_erro->setTimestampErro($timestamp);
                    $log_erro->setComputador($computador);
                    $log_erro->setNivelErro('error');
                    $log_erro->setMensagem($entry['message']);

                    $em->persist($log_erro);

                }
            }
        }

        $em->flush();

        $response->setStatusCode('200');
        return $response;

    }

}