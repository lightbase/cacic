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


class NeoInstallController extends Controller {

    public function hashAction(Request $request) {
        //1 - Verificar se computador existe
        $logger = $this->get('logger');
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();

        $dados = json_decode($status, true);

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

}