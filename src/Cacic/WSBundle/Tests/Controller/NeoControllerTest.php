<?php
/**
 * Created by PhpStorm.
 * User: gabi
 * Date: 25/07/14
 * Time: 11:47
 */

namespace Cacic\WSBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class NeoControllerTest extends WebTestCase
{
    /**
     * Método que cria dados comuns a todos os testes
     */
    public function setUp() {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
    }

    /**
     * Testa a comunicação SSL
     */
    public function testCommunication()
    {
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


        $logger = $this->container->get('logger');
        //$logger->debug("11111111111111111111111111111111111111 ".print_r($client->getRequest()->getUriForPath('/'), true));

        $this->assertEquals(301,$client->getResponse()->getStatusCode());
    }

    /**
     * test login
     */
    public function testLogin()
    {

        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/login',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            '{  "user" : "cacic-adm",
                "password": "123456"
            }'
        );
        $logger->debug("Dados JSON de login enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $data = $response->getContent();
        $logger->debug("Response data: \n".print_r($data,true));
        // JSON Serialization
        $json = json_decode($data, true);
        $session = $json['session'];

        $this->assertTrue(is_string($session));


    }

      /**
       *Teste da sessão
       */
     public function testSession() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/login',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            '{  "user" : "cacic-adm",
                "password": "123456"
            }'
        );
        $logger->debug("Dados JSON de login enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $data = $response->getContent();
        $logger->debug("Response data: \n".print_r($data,true));
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
            '{  "session" : $session
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
            '{  "so" : "2.6.1",
                "rede": [
                    {   "mac" : "e0:3f:49:e4:72:75",
                        "ip" : "10.1.0.137",
                        "interface": "Rede Local"
                    },
                    {   "mac" : "e0:3f:49:e4:72:76",
                        "ip" : "10.1.0.138",
                        "interface": "Rede Local"
                    }
                ]
            }'
        );
        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals($status, 200);

    }


    /**
     * Método que apaga todos os dados criados no teste
     */
    public function tearDown() {

    }

}