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
                "password": "123456"
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
                "password": "123456"
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
            '{
                "computador": {
                    "networkDevices": [
                        {
                            "ipv4": "10.1.0.56",
                            "ipv6": "fe80::295b:a8db:d433:ebe%4",
                            "mac": "9C:D2:1E:EA:E0:89",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "Wi-Fi"
                        },
                        {
                            "ipv4": "192.168.56.1",
                            "ipv6": "fe80::19f2:4739:8a9e:45e4%16",
                            "mac": "08:00:27:00:14:2B",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "VirtualBox Host-Only Network"
                        }
                    ],
                    "operatingSystem": {
                        "idOs": 176,
                        "nomeOs": "Windows_NT"
                    },
                    "usuario": "Eric Menezes"
                }
            }'
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
            '{
                "computador": {
                    "networkDevices": [
                        {
                            "ipv4": "10.1.0.56",
                            "ipv6": "fe80::295b:a8db:d433:ebe%4",
                            "mac": "9C:D2:1E:EA:E0:89",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "Wi-Fi"
                        },
                        {
                            "ipv4": "192.168.56.1",
                            "ipv6": "fe80::19f2:4739:8a9e:45e4%16",
                            "mac": "08:00:27:00:14:2B",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "VirtualBox Host-Only Network"
                        }
                    ],
                    "operatingSystem": {
                        "idOs": 176,
                        "nomeOs": "Windows_NT"
                    },
                    "usuario": "Eric Menezes"
                }
            }'
        );
        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $logger->debug("JSON do getConfig: \n".$response->getContent());

        $this->assertEquals($status, 200);

    }

    public function testUpdate() {
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
            '{
                "computador": {
                    "networkDevices": [
                        {
                            "ipv4": "10.1.0.56",
                            "ipv6": "fe80::295b:a8db:d433:ebe%4",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "Wi-Fi"
                        },
                        {
                            "ipv4": "192.168.56.1",
                            "ipv6": "fe80::19f2:4739:8a9e:45e4%16",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "VirtualBox Host-Only Network"
                        }
                    ],
                    "operatingSystem": {
                        "idOs": 176,
                        "nomeOs": "Windows_NT"
                    },
                    "usuario": "Eric Menezes"
                }
            }'
        );
        $logger->debug("Dados JSON do computador enviados sem MAC para o getUpdate \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        $logger->debug("JSON do getConfig: \n".$response->getContent());

        $this->assertEquals($status, 200);

    }

    /**
     * Método que apaga todos os dados criados no teste
     */
    public function tearDown() {

    }

}