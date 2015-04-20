<?php
/**
 * Created by PhpStorm.
 * User: gabi
 * Date: 25/07/14
 * Time: 11:47
 */

namespace Cacic\WSBundle\Tests\Controller;

use Cacic\WSBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;



class NeoControllerTest extends BaseTestCase
{

    /**
     * Método que cria dados comuns a todos os testes
     */
    public function setUp() {
        // Load setup from BaseTestCase method
        parent::setUp();

    }

    /**
     * Testa a comunicação SSL
     */
    public function testCommunication()
    {
        $logger = $this->container->get('logger');
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/neo',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '{}'
        );


        //$logger->debug("11111111111111111111111111111111111111 ".print_r($client->getResponse()->getStatusCode(), true));

        $this->assertEquals(200,$client->getResponse()->getStatusCode());
    }


    /**
     * test login
     */
    public function testGetLogin()
    {

        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/getLogin',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '{  "user" : "cacic-adm",
                "password": "'.$this->apiKey.'"
            }'
        );
        $logger->debug("Dados JSON de login enviados\n".$this->client->getRequest()->getcontent());//user e password

        $response = $this->client->getResponse();
        //$logger->debug("Response:\n".print_r($response,true)); // arrays session e chavecrip
        $data = $response->getContent();
        //$logger->debug("Response data:\n".print_r($data,true)); //session e chavecrip
        // JSON Serialization
        $json = json_decode($data, true);
        $logger->debug("Response json: \n".print_r($json,true)); //session e chavecrip
        $session = $json['session'];
        $chavecrip= $json['chavecrip'];

        $this->assertTrue(is_string($session));

        $this->assertTrue(is_string($chavecrip));

    }

      /**
       *Teste da sessão
       */
     public function testSession() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/getLogin',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            '{  "user" : "cacic-adm",
                "password": "'.$this->apiKey.'"
            }'
        );
        $logger->debug("Dados JSON de login enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $data = $response->getContent();

        // JSON Serialization
        $json = json_decode($data, true);
        $session = $json['session'];

        // Testa a sessão
        $this->client->request(
            'POST',
            '/ws/neo/checkSession',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            '{  "session" : '.$session.'
            }'
        );

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals($status, 200);
    }

    /**
     * Testa inserção do computador se não existir
     */
    public function testGetTest() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/getTest',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador
        );
        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals($status, 200);

    }

    /**
     * Testconfig
     */
    public function testConfig() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/config',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador
        );
        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $logger->debug("JSON do getConfig: \n".$response->getContent());

        $this->assertEquals($status, 200);

    }

    /**
     * Essa função vai falhar se não tiver nenhum computador cadastrado
     */
    public function testSemMac() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/update',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->sem_mac
        );
        $logger->debug("Dados JSON do computador enviados sem MAC para o getUpdate \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON do getConfig: \n".$response->getContent());

        $this->assertEquals($status, 500);

    }

    /**
     * Retorna o último computador cadastrado caso seja enviado um sem Mac
     */
    public function testUpdate() {
        $logger = $this->container->get('logger');
        // Primeiro insere um computador válido
        $this->client->request(
            'POST',
            '/ws/neo/getTest',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador
        );
        $logger->debug("Dados JSON do computador enviados antes do getUpdate \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals($status, 200);

        // Agora tenta inserir um sem MAC
        $this->client->request(
            'POST',
            '/ws/neo/update',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->sem_mac
        );
        $logger->debug("Dados JSON do computador enviados sem MAC para o getUpdate \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $logger->debug("JSON do getUpdate: \n".$response->getContent());

        $this->assertEquals($status, 200);

    }


    public function  testHttpUpdate() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/config',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador
        );
        //$logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON do getConfig: \n".$response->getContent());

        $this->assertEquals($status, 200);

        $status = $response->getContent();
        $dados = json_decode($status, true);

        // Checa se método de download foi informado
        $this->assertEquals(
            $dados['agentcomputer']['metodoDownload']['tipo'],
            'http'
        );

        // Verifica se o hash correto foi enviado
        $result = false;
        foreach($dados['agentcomputer']['modulos']['cacic'] as $modulo) {
            //$logger->debug("99999999999999999999999999999999999 ".print_r($modulo, true));
            if ($modulo['hash'] == '50cf34bf584880fd401619eb367b2c2d') {
                $result = true;
            }
        }
        $this->assertTrue($result);

    }

    public function testUsuarioLogado() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/getTest',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->computador
        );
        //$logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON do getConfig: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Testa se o usuário Eric foi persistido
        $em =$this->container->get('doctrine')->getManager();
        $results = $em->getRepository('CacicCommonBundle:LogUserLogado')->findBy(array(
            'usuario' => 'Eric Menezes'
        ));

        $this->assertFalse(empty($results));

        $results = $em->getRepository('CacicCommonBundle:LogUserLogado')->findBy(array(
            'usuario' => 'Joãzinho'
        ));

        $this->assertTrue(empty($results));
    }


    /**
     * Método que apaga todos os dados criados no teste
     */
    public function tearDown() {
        // Executa método de limpeza de todos os casos de teste
        parent::tearDown();

    }

}