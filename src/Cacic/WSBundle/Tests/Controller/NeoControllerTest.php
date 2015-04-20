<?php
/**
 * Created by PhpStorm.
 * User: gabi
 * Date: 25/07/14
 * Time: 11:47
 */

namespace Cacic\WSBundle\Tests\Controller;

use Cacic\CommonBundle\Tests\BaseTestCase;
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

        // Load specific fixtures
        $fixtures = array_merge(
            $this->classes,
            array(
                'Cacic\WSBundle\DataFixtures\ORM\LoadRedeVersaoModuloData',
                'Cacic\WSBundle\DataFixtures\ORM\LoadTipoSo'
            )
        );
        $this->loadFixtures($fixtures);

        // Basic data
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->apiKey = $this->container->getParameter('test_api_key');
        $this->computador = '{
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
                        "nomeOs": "Windows_NT",
                        "tipo": "windows"
                    },
                    "usuario": "Eric Menezes",
                    "nmComputador": "Notebook-XPTO",
                    "versaoAgente": "2.8.0",
                    "versaoGercols": "2.8.0"
                }
            }';
        $this->sem_mac = '{
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
                        "nomeOs": "Windows_NT",
                        "tipo": "windows"
                    },
                    "usuario": "Eric Menezes",
                    "nmComputador": "Notebook-XPTO",
                    "versaoAgente": "2.8.0",
                    "versaoGercols": "2.8.0"
                }
            }';

        $this->computador_http = '{
                "computador": {
                    "networkDevices": [
                        {
                            "ipv4": "0.0.0.1",
                            "ipv6": "fe80::295b:a8db:d433:ebe%4",
                            "mac": "9C:D2:1E:EA:E0:89",
                            "netmask_ipv4": "255.255.255.255",
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
                        "nomeOs": "Windows_NT",
                        "tipo": "windows"
                    },
                    "usuario": "Eric Menezes",
                    "nmComputador": "Notebook-XPTO",
                    "versaoAgente": "2.8.0",
                    "versaoGercols": "2.8.0"
                }
            }';
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
     * Testa erroAgente
     */
    public function testErroAgente() {
        $logger = $this->container->get('logger');
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/neo/logs',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '{
                    "computador": {
                    "networkDevices": [
                    {
                        "ipv4": "10.0.2.15",
                        "ipv6": "fe80::a00:27ff:fe11:caec%eth0",
                        "mac": "08:00:27:11:CA:EC",
                        "netmask_ipv4": "255.255.255.0",
                        "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                        "nome": "eth0"
                    },
                    {
                        "ipv4": "192.168.56.102",
                        "ipv6": "fe80::a00:27ff:fe7d:b57e%eth1",
                        "mac": "08:00:27:7D:B5:7E",
                        "netmask_ipv4": "255.255.255.0",
                        "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                        "nome": "eth1"
                    }
                    ],
                        "nmComputador": "virtualbox-ubuntu",
                        "operatingSystem": {
                        "idOs": 2,
                        "nomeOs": "Ubuntu 14.04.1 LTS-x86_64",
                        "tipo": "linux-x86_64",
                        "upTime": 2125
                    },
                        "usuario": "virtualbox",
                        "versaoAgente": "3.1.9",
                        "versaoGercols": "3.1.9"
                    },
                        "logInfo": [],
                        "logError": [
                        {
                            "timestamp": "25-03-2015 12:20:54.094",
                            "message": "[Error] {Cacic Daemon (Timer)} Erro no login: Host  not found"
                        },
                        {
                            "timestamp": "25-03-2015 12:20:54.095",
                            "message": "[Error] {Cacic Daemon (Timer)} Problemas ao comunicar com gerente."
                    ]
                    }'
        );

        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals(200,$status);
    }

    /*
     * Fazer outra funcao de teste com o Json errado, para poder
     * validar a tarefa #936.
     *
     * Processar a volta(response) que vem do Controller que valida este teste.
     *
     * */


    /**
     * Método que apaga todos os dados criados no teste
     */
    public function tearDown() {
        // Executa método de limpeza de todos os casos de teste
        parent::tearDown();

    }

}