<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 06/07/15
 * Time: 10:32
 */

namespace Cacic\CommonBundle\Tests\Controller;

class GrupoUsuarioControllerTest extends DefaultControllerTest {

    public function setUp() {
        // Load base data
        parent::setUp();
    }


    /**
     * Testa cadastro de novo grupo
     */
    public function testAddGrupo() {

        // Conecta ao formulário
        $crawler = $this->client->request(
            'GET',
            '/admin/grupousuario/cadastrar',
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

        // Envia formulário de cadastro com valores de teste
        $this->client->submit($form, array(
            'grupoUsuario[teGrupoUsuarios]' => "Grupo de Testes",
            'grupoUsuario[nmGrupoUsuarios]' => "Admin"
        ));

        // Testa resposta do formulário
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $this->logger->debug("Response status: $status");
        $this->assertNotEquals(500, $response->getStatusCode(), "Falha no envio do formulario de cadastro de usuarios.");

        // Agora testa pra ver se o local foi inserido com sucesso
        $local = $this->em->getRepository("CacicCommonBundle:GrupoUsuario")->findOneBy(array(
            'teGrupoUsuarios' => 'Grupo de Testes'
        ));

        $this->assertNotEmpty($local, "A inserção do grupo de usuarios falhou");

    }


    public function tearDown() {

        // Delete base data
        parent::tearDown();
    }

}