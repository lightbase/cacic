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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Console\Output\NullOutput;
use Doctrine\ORM\Tools\SchemaTool;

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


        $site = $em
            ->getRepository('CacicMultiBundle:Sites')
            ->findOneBy(
                array(
                    'username' => $username
                )
            );

        // Debug
        $logger->debug("MULTI-SITE DEBUG: detected domain $dbname");

        $siteManager = $this->container->get('site_manager');

        if (empty($site)) {
            // Se for nulo retorna false

            return false;
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
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro no fixturesLoad");
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

        // Altera chave de API e Senha do usuário padrão
        $usuario = $om->getRepository("CacicCommonBundle:Usuario")->findOneBy(array(
            'nmUsuarioAcesso' => 'cacic-adm'
        ));

        // Gera uma senha aleatória
        $senha = $usuario->randomPassword();
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($usuario);
        $usuario->setTeSenha($encoder->encodePassword($senha, $usuario->getSalt()));

        // A chave de API é um UUID
        $usuario->setApiKey(uniqid());

        // O nome do usuário é o nome do site
        $siteManager = $this->container->get('site_manager');
        $site = $siteManager->getCurrentSite();
        $usuario->setNmUsuarioAcesso($site);

        // Grava e devolve os valores no JSON
        $om->persist($usuario);
        $om->flush();

        $saida = array(
            'password' => $senha,
            'api_key' => $usuario->getApiKey()
        );

        // Finaliza retornando o resultado
        $response = new JsonResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($saida, true));
        return $response;
    }

    /**
     * Cria o site solicitado
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createSiteAction(Request $request) {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();
        $container = $this->get('kernel')->getContainer();
        $status = $request->getContent();
        $dados = json_decode($status, true);

        if (empty($dados)) {
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro no createSite");
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

        if (!array_key_exists('site', $dados)) {
            $logger->error("Objeto do site não encontrado");
            // Retorna erro se o JSON for inválido
            $error_msg = '{
                "message": "Site não encontrado",
                "codigo": 2
            }';

            $response = new JsonResponse();
            $response->setStatusCode(500);
            $response->setContent($error_msg);
            return $response;
        }

        // Serializa o objeto do site
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $site_json = json_encode($dados['site'], true);

        $site = $serializer->deserialize(
            $site_json,
            'Cacic\MultiBundle\Entity\Sites',
            'json'
        );

        // Garante que o site ainda não existe
        $site2 = $em
            ->getRepository('CacicMultiBundle:Sites')
            ->findOneBy(
                array(
                    'username' => $site->getUsername()
                )
            );

        if (!empty($site2)) {
            $logger->error("O site solicitado já existe! username = ".$site->getUsername());

            // Retorna erro com a mensagem
            $error_msg = '{
                "message": "O site solicitado já existe! username = '.$site->getUsername().'",
                "codigo": 3
            }';

            $response = new JsonResponse();
            $response->setStatusCode(500);
            $response->setContent($error_msg);
            return $response;
        }

        // Agora salva o objeto
        $em->persist($site);
        $em->flush();

        // Agora retorna a resposta
        $response = new JsonResponse();
        $response->setStatusCode(200);
        return $response;
    }

    public function schemaUpdateAction(Request $request) {
        $logger = $this->get('logger');
        $container = $this->get('kernel')->getContainer();
        $status = $request->getContent();
        $dados = json_decode($status, true);

        if (empty($dados)) {
            $logger->error("JSON INVÁLIDO!!!!!!!!!!!!!!!!!!! Erro no createSite");
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

        // Ajusta o EntityManager específico com base na URL
        $result = $this->getSite($request);

        if (!$result) {
            $logger->error("Objeto do site não encontrado");
            // Retorna erro se o JSON for inválido
            $error_msg = '{
                "message": "Site não encontrado",
                "codigo": 2
            }';

            $response = new JsonResponse();
            $response->setStatusCode(500);
            $response->setContent($error_msg);
            return $response;
        }

        // Aqui executa o comando de atualizar o schema
        $em = $this->getDoctrine()->getManager();
        $metadatas = $em->getMetadataFactory()->getAllMetadata();

        // A opção true manda consolidar as alterações no banco
        $tool = new SchemaTool($em, true);
        $tool->updateSchema($metadatas);

        // Agora retorna a resposta
        $response = new JsonResponse();
        $response->setStatusCode(200);
        return $response;
    }

}