<?php

namespace Cacic\RelatorioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Cacic\CommonBundle\Tests\BaseTestCase;

class HardwareControllerTest extends BaseTestCase
{
    private $client = null;

    public function setUp()
    {
        // Load setup from BaseTestCase method
        parent::setUp();

        $this->client = static::createClient(
            array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => '123456',
        ));
    }

    /**
     * Testa o controller que gerar a página inicial de um relatório WMI
     */

    public function testWmiAction() {

        //$this->logIn();

        $crawler = $this->client->request(
            'GET',
            '/relatorio/hardware/NetworkAdapterConfiguration/DefaultIPGateway',
            array(),
            array(),
            array(),
            '{}'
        );

        $response = $this->client->getResponse();

        $this->assertNotEquals(500, $response->getStatusCode());
    }


    /**
     * Remove dados de teste
     */

    public function tearDown() {

        // Call terDown from root
        parent::tearDown();
    }
}
