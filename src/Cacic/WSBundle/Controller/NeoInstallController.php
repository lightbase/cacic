<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/05/15
 * Time: 11:08
 */

namespace Cacic\WSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

use Cacic\CommonBundle\Entity\InsucessoInstalacao;

class NeoInstallController extends Controller {

    /**
     * Método que verifica o hash do último serviço válido para a subrede
     * @param Request $request
     * @return JsonResponse
     */
    public function hashAction(Request $request) {
        //1 - Verificar se computador existe
        $logger = $this->get('logger');
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();

        $dados = json_decode($status, true);

        if (empty($dados)) {
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro no getConfig");
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

        $ip_address = @$dados['ip_address'];
        $netmask = @$dados['netmask'];

        if (empty($ip_address) || empty($netmask)) {
            // Sem essas informações não dá pra identificar o Hash
            $logger->error("IP ou máscara vazios. IP = |$ip_address| Máscara = |$netmask|");

            $error_msg = '{
                "message": "IP ou máscara vazios",
                "codigo": 4
            }';

            $response = new JsonResponse();
            $response->setStatusCode('500');
            $response->setContent($error_msg);
            return $response;
        }

        $rede = $em->getRepository("CacicCommonBundle:Rede")->getDadosRedePreColeta(
            $ip_address,
            $netmask
        );

        // FIXME: No momento essa operação só funcionar pra Windows
        $tipo = 'windows';
        $cacic_service = $em->getRepository("CacicCommonBundle:RedeVersaoModulo")->getModulo(
            $rede->getIdRede(),
            'cacic-service.exe',
            $tipo
        );
        //$logger->debug(print_r($cacic_service, true));

        if (empty($cacic_service)) {
            // Sem essas informações não dá pra identificar o Hash
            $logger->error("Módulo não encontrado para a rede ".$rede->getTeIpRede());

            $error_msg = '{
                "message": "Módulo não encontrado para a rede",
                "codigo": 4
            }';

            $response = new JsonResponse();
            $response->setStatusCode('500');
            $response->setContent($error_msg);
            return $response;
        }

        $retorno = array(
            'valor' => $cacic_service[0]['teHash']
        );

        $modulo = json_encode($retorno, true);

        $response = new JsonResponse();
        $response->setStatusCode('200');
        $response->setContent($modulo);
        return $response;
    }

    public function erroAction(Request $request) {
        $logger = $this->get('logger');
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();

        $dados = json_decode($status, true);

        if (empty($dados)) {
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro no getConfig");
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

        $ip_computador = $request->getClientIp();

        $insucesso = new InsucessoInstalacao();
        $insucesso->setDtDatahora(new \DateTime());
        $insucesso->setTeIpComputador($ip_computador);
        $insucesso->setCsIndicador($dados['codigo']);
        $insucesso->setMensagem($dados['message']);

        if (array_key_exists('user', $dados)) {
            $insucesso->setIdUsuario($dados['user']);
        }

        if (array_key_exists('so', $dados)) {
            $insucesso->setTeSo($dados['so']);
        }

        $em->persist($insucesso);
        $em->flush();

        $response = new JsonResponse();
        $response->setStatusCode('200');
        return $response;
    }

}