<?php

namespace Cacic\RelatorioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HardwareControllerTest extends WebTestCase
{
    /**
     * Cria dados necessários aos testes
     */

    public function setUp() {

    }

    /**
     * Testa login funcional
     */

    public function testLogin() {

    }

    /**
     * Testa o controller que gerar a página inicial de um relatório WMI
     */

    public function testWmiAction() {

        $client = static::createClient();
        $crawler = $client->request(
            'GET',
            '/relatorio/hardware/NetworkAdapterConfiguration',
            array(),
            array(),
            array(),
            '{}'
        );

        $this->assertTrue($crawler->filter('option:contains("DefaultIPGateway")')->count() > 0);
    }


    /**
     * Remove dados de teste
     */

    public function tearDown() {

    }
}
