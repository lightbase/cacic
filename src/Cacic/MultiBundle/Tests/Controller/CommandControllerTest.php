<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 27/08/15
 * Time: 10:54
 */

namespace Cacic\MultiBundle\Tests\Controller;

use Cacic\MultiBundle\Tests\BaseTestCase as DefaultTestCase;

class CommandControllerTest extends DefaultTestCase
{
    public function setUp() {
        parent::setup();
    }

    /**
     * Testa carga dos fixtures via Ajax
     */
    public function testLoadFixtures() {
        $logger = $this->container->get('logger');

        // Primeiro o login
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
                "password": "cacic123"
            }'
        );
        $logger->debug("Dados JSON de login enviados\n".$this->client->getRequest()->getcontent());//user e password

        $response = $this->client->getResponse();
        $data = $response->getContent();

        // JSON Serialization
        $json = json_decode($data, true);
        $session = $json['session'];
        $this->assertNotEmpty($session, "O JSON do login não retornou a sessão: |$session|");

        // Agora tenta carregar
        $this->client->request(
            'POST',
            '/multi/command/fixtures',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '{
                "username": "test",
                "password": "cacic123"
            }'
        );

        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Testa criação de site para o usuário fornecido
     */
    public function testCreateSite() {
        $logger = $this->container->get('logger');

        // Cria o array contendo o objeto do site e a chave de API do usuário
        $dados = array(
            "password" => "cacic123",
            "site" => json_decode($this->site, true)
        );

        // Tenta criar o site
        $this->client->request(
            'POST',
            '/multi/command/site',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            json_encode($dados, true)
        );

        // Testa status da resposta
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Testa execução do schema:update pelo Controller
     */
    public function testSchemaUpdate() {
        $logger = $this->container->get('logger');

        $this->client->request(
            'POST',
            '/multi/command/schema',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '{
                "username": "test",
                "password": "cacic123"
            }'
        );

        // Testa status da resposta
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function tearDown() {
        parent::tearDown();
    }

}