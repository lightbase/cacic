<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/04/15
 * Time: 12:47
 */

namespace Cacic\WSBundle\Tests\Controller;

use Cacic\WSBundle\Tests\BaseTestCase;

class NeoLogControllerTest extends BaseTestCase {

    public function setUp() {
        // Carrega dados da classe de cima
        parent::setUp();
    }

    /**
     * Testa erroAgente
     */
    public function testErroAgente() {
        $logger = $this->container->get('logger');
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/neo/log',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            '["comp"]["1"]'
        );

        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals(500,$status);

        // Verifica se o sistema informou JSON inválido
        $dados = json_decode($response->getContent(), true);
        $this->assertEquals(1, $dados['codigo']);
        $this->assertEquals("JSON Inválido", $dados['message']);

        $client = $this->client;
        $client->request(
            'POST',
            '/ws/neo/log',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            $this->computador_erro
        );

        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals(200,$status);

        // Testa se o erro foi inserido com sucesso para o computador
        $em =$this->container->get('doctrine')->getManager();
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Ubuntu 14.04.1 LTS-x86_64'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '08:00:27:11:CA:EC',
            'idSo' => $so->getIdSo()
        ));
        $this->assertNotEmpty($computador);

        $log_erro = $em->getRepository("CacicCommonBundle:ErrosAgente")->findBy(array(
            'computador' => $computador->getIdComputador()
        ));
        $this->assertGreaterThan(0, sizeof($log_erro));

        // Verifica se gravou com o nível certo
        $log_erro = $em->getRepository("CacicCommonBundle:ErrosAgente")->findBy(array(
            'computador' => $computador->getIdComputador(),
            'nivelErro' => 'error'
        ));
        $this->assertGreaterThan(0, sizeof($log_erro));

        // Finalmente garante que não vai duplicar o erro
        $client = $this->client;
        $client->request(
            'POST',
            '/ws/neo/log',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                'HTTPS'         => true
            ),
            $this->computador_erro
        );

        $logger->debug("Dados JSON do computador enviados \n".$this->client->getRequest()->getcontent());
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");

        $this->assertEquals(200,$status);

        $log_erro = $em->getRepository("CacicCommonBundle:ErrosAgente")->findBy(array(
            'computador' => $computador->getIdComputador(),
            'nivelErro' => 'error'
        ));
        $this->assertEquals(2, sizeof($log_erro));

    }

    public function tearDown() {

        // Apaga dados da classe de cima
        parent::tearDown();
    }
}