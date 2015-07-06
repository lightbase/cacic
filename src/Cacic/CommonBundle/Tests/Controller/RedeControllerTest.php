<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 06/07/15
 * Time: 10:32
 */

namespace Cacic\CommonBundle\Tests\Controller;

class RedeControllerTest extends DefaultControllerTest {

    public function setUp() {
        // Load base data
        parent::setUp();
    }


    /**
     * Testa cadastro de nova rede
     */
    public function testAddRede() {
        // Conecta ao formulário
        $crawler = $this->client->request(
            'GET',
            '/admin/subrede/cadastrar',
            array(),
            array(),
            array(),
            array()
        );

        // Testa autenticação
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $this->logger->debug("Response status: $status");
        //$this->logger->debug("Resposta:\n".$response->getContent());
        $this->assertEquals(200, $status);

        // Pega formulario
        $buttonCrawlerNode = $crawler->selectButton('Salvar Dados');
        $form = $buttonCrawlerNode->form();

        // Cadastra a rede no local cadastrado
        $local = $this->fixtures->getReference('local');
        $this->assertNotEmpty($local, "Local padrão não encontrado");

        // Envia formulário de cadastro somente com campos obrigatorios
        $this->client->submit($form, array(
            'rede[idLocal]' => $local->getIdLocal(),
            'rede[teIpRede]' => '192.168.0.0',
            'rede[teServCacic]' => '127.0.0.1',
            'rede[teServUpdates]' => '127.0.0.1',
            'rede[downloadMethod]' => 'http',
            'rede[habilitar]' => 1
        ));

        // Testa resposta do formulário
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $this->logger->debug("Response status: $status");
        $this->assertNotEquals(
            500,
            $response->getStatusCode(),
            "Houve uma falha ao submeter o forumlario de cadastro de subredes"
        );

        // Agora testa pra ver se o local foi inserido com sucesso
        $rede = $this->em->getRepository("CacicCommonBundle:Rede")->findOneBy(array(
            'teIpRede' => '192.168.0.0'
        ));

        $this->assertNotEmpty($rede, "A inserção da subrede falhou");

    }


    public function tearDown() {

        // Delete base data
        parent::tearDown();
    }

}