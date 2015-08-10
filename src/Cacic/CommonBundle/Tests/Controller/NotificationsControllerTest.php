<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 29/07/15
 * Time: 17:10
 */

namespace Cacic\CommonBundle\Tests\Controller;

use Cacic\CommonBundle\Entity\Computador;
use Cacic\CommonBundle\Tests\BaseTestCase;

class NotificationsControllerTest extends BaseTestCase
{
    public function setUp()
    {
        // Load base data
        parent::setUp();

        $this->fixtures = $this->loadFixtures($this->classes)->getReferenceRepository();

        // Cliente que simula o agente
        $this->static_client = static::createClient();

        // Cliente autenticado
        $this->client = static::makeClient(true);

        $this->container = $this->client->getContainer();
        $this->logger = $this->container->get('logger');

        $this->em = $this->container->get('doctrine')->getManager();

        $kernel = $this->container->get('kernel');
        $this->ws_data_dir = $kernel->locateResource("@CacicWSBundle/Resources/data/fixtures/");
        $this->modifications = file_get_contents($this->ws_data_dir."modifications.json");
        $this->coleta_modifications = file_get_contents($this->ws_data_dir."coleta/coleta-modifications.json");
    }

    /**
     * Testa recuperação de lista de notificações
     */
    public function testGetNotifications() {
        // Cria dados para testar
        $this->static_client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_modifications
        );

        $response = $this->static_client->getResponse();
        $status = $response->getStatusCode();
        $this->logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em = $this->container->get('doctrine')->getManager();

        // Agora envia a modificação
        $this->static_client->request(
            'POST',
            '/ws/neo/modifications',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->modifications
        );

        $response = $this->static_client->getResponse();
        $status = $response->getStatusCode();
        $this->logger->debug("Response status: $status");
        $this->assertEquals($status, 200);

        // Limpa o cache para garantir o resultado
        $em->clear();

        // Conecta à rota
        $crawler = $this->client->request(
            'GET',
            '/notifications/get',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            '{}'
        );

        // Testa rota
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $this->logger->debug("Response status: $status");
        $this->assertEquals(200, $status);
        $this->assertNotEmpty($response->getContent(), "Conteúdo de notificações vazio");

        //$this->logger->debug("NOTIFICATIONS:\n".$response->getContent());
        // Debug JSON Content
        $tmpfile = sys_get_temp_dir(). '/notifications.json';
        file_put_contents($tmpfile, $response->getContent());
        $this->logger->debug("NOTIFICATIONS: file temp = $tmpfile");

    }


    public function tearDown() {
        // Remove default data
        parent::tearDown();
    }

}