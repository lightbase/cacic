<?php

namespace Cacic\RelatorioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Cacic\WSBundle\Tests\BaseTestCase;

class HardwareControllerTest extends BaseTestCase
{

    public function setUp()
    {
        // Load setup from BaseTestCase method
        parent::setUp();

        $this->client = static::createClient(
            array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => '123456',
        ));

        // Criar dados de coleta
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta
        );
    }

    /**
     * Testa o controller que gerar a página inicial de um relatório WMI
     */

    public function testWmiAction() {

        //$this->logIn();

        $crawler = $this->client->request(
            'GET',
            '/relatorio/hardware/NetworkAdapterConfiguration/serial',
            array(),
            array(),
            array(),
            '{}'
        );

        $response = $this->client->getResponse();

        $this->assertNotEquals(500, $response->getStatusCode());

        // Verifica se a classe NetworkAdapterConfiguration coletou
        $em = $this->container->get('doctrine')->getManager();
        $logger = $this->container->get('logger');

        $classe = $em->getRepository("CacicCommonBundle:Classe")->findOneBy(array(
            'nmClassName' => 'NetworkAdapterConfiguration'
        ));
        $this->assertNotEmpty($classe);

        $propriedade = $em->getRepository("CacicCommonBundle:ClassProperty")->findOneBy(array(
            'idClass' => $classe->getIdClass(),
            'nmPropertyName' => 'serial'
        ));
        $this->assertNotEmpty($propriedade);

        //$logger->debug("!111111111111111111111111111111111111 | ".$propriedade->getIdClassProperty());

        $coletas = $em->getRepository("CacicCommonBundle:ComputadorColeta")->findBy(array(
            'classProperty' => $propriedade->getIdClassProperty()
        ));
        $this->assertGreaterThan(
            0,
            sizeof($coletas)
        );

        $logger->debug(print_r($response->getContent(), true));

        //$this->assertGreaterThan(
        //    0,
        //    $crawler->filter('span:contains("Windows_NT")')->count()
        //);
    }


    /**
     * Remove dados de teste
     */

    public function tearDown() {

        // Call terDown from root
        parent::tearDown();
    }
}
