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

        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
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

    public function tearDown() {
        parent::tearDown();
    }

}