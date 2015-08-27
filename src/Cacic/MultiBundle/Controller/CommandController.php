<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 26/08/15
 * Time: 11:35
 */

namespace Cacic\MultiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Cacic\MultiBundle\Helper\FixturesHelper;

/**
 * Class CommandController
 * @package Cacic\MultiBundle\Controller
 *
 * Permite a execução de comandos via Controller
 */
class CommandController extends Controller
{
    /**
     * Busca site com base no nome de usuário enviado na requisição
     *
     * @param $request Request Deve ser um arquivo JSON no seguinte formato:
     *  {'username': 'fulano'}
     * @return bool
     */
    public function getSite($request) {
        $logger = $this->get('logger');;
        $hostMethod = $this->getParameter('host_method');

        // Tenta encontrar o site
        $status = $request->getContent();
        $em = $this->getDoctrine()->getManager();
        $dados = json_decode($status, true);

        // TODO: Adicionar autenticação
        $username = @$dados['username'];
        if (empty($username)) {
            return false;
        }

        // Primeiro pega o repositório padrão
        $dbname = $this->getParameter('database_name');
        $dbuser = $this->getParameter('database_user');
        $dbpass = $this->getParameter('database_password');
        $dbhost = $this->getParameter('database_host');

        $this->get('doctrine.dbal.dynamic_connection')->forceSwitch($dbname, $dbuser, $dbpass, $dbhost);

        // Agora verifica o método de login
        if ($hostMethod == 'subdomain') {
            $site = $em
                ->getRepository('CacicMultiBundle:Sites')
                ->findOneBy(
                    array(
                        'subdomain' => $username
                    )
                );

        } else {
            $site = $em
                ->getRepository('CacicMultiBundle:Sites')
                ->findOneBy(
                    array(
                        'subdir' => $username
                    )
                );
        }

        // Debug
        $logger->debug("MULTI-SITE DEBUG: detected domain $dbname");

        $siteManager = $this->container->get('site_manager');

        if (empty($site)) {
            // Se for nulo, pega o valor que está no parâmetro
            $dbname = $this->getParameter('database_name');
            $dbuser = $this->getParameter('database_user');
            $dbpass = $this->getParameter('database_password');
            $dbhost = $this->getParameter('database_host');
        } else {
            // Ajusta o site para o nome do usuário encontrado
            $siteManager->setCurrentSite($site->getUsername());

            $dbname = $site->getUsername();
            $dbuser = $site->getDbUser();
            $dbpass = $site->getDbPassword();
            $dbhost = $site->getDbHost();
        }

        $this->container->get('doctrine.dbal.dynamic_connection')->forceSwitch($dbname, $dbuser, $dbpass, $dbhost);

        return true;
    }

    /**
     * Comando para realizar o FixturesLoad do Site
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fixturesLoadAction(Request $request) {
        $logger = $this->get('logger');

        // Ajusta o EntityManager específico com base na URL
        $result = $this->getSite($request);

        if (!$result) {
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro no getTest");
            // Retorna erro se o JSON for inválido
            $error_msg = '{
                "message": "JSON Inválido",
                "codigo": 1
            }';

            $response = new JsonResponse();
            $response->setStatusCode(500);
            $response->setContent($error_msg);
            return $response;
        }

        // Carrega fixtures e executa no banco
        $fixturesHelper = new FixturesHelper();
        $container = $this->get('kernel')->getContainer();
        $om = $this->getDoctrine()->getManager();
        $type = 'ORM';
        $fixturesHelper->loadFixtures($type, $om, $container);

        // Finaliza retornando o resultado
        $response = new JsonResponse();
        $response->setStatusCode(200);
        return $response;
    }

}