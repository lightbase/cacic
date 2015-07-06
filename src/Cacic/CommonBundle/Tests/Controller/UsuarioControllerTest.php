<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 06/07/15
 * Time: 10:33
 */

namespace Cacic\CommonBundle\Tests\Controller;

class UsuarioControllerTest extends DefaultControllerTest {

    public function setUp() {
        // Load base data
        parent::setUp();
    }


    /**
     * Testa cadastro de novo usuário
     */
    public function testAddUsuario() {

        // Conecta ao formulário
        $crawler = $this->client->request(
            'GET',
            '/admin/usuario/cadastrar',
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

        // Cadastra o usuário no local cadastrado
        $local = $this->fixtures->getReference('local');
        $this->assertNotEmpty($local, "Local padrão não encontrado");

        // Grupo de administradores
        $grupo_admin = $this->fixtures->getReference('grupo-admin');
        $this->assertNotEmpty($grupo_admin, "Grupo de administradores não encontrado!");

        // Envia formulário de cadastro com valores de teste
        $this->client->submit($form, array(
            'usuario[idLocal]' => $local->getIdLocal(),
            'usuario[nmUsuarioAcesso]' => 'test-user',
            'usuario[nmUsuarioCompleto]' => 'Usuário de teste',
            'usuario[idGrupoUsuario]' => $grupo_admin->getIdGrupoUsuario()
        ));

        // Testa resposta do formulário
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $this->logger->debug("Response status: $status");
        $this->assertNotEquals(500, $response->getStatusCode(), "Falha no envio do formulario de cadastro de usuarios.");

        // Agora testa pra ver se o local foi inserido com sucesso
        $local = $this->em->getRepository("CacicCommonBundle:Usuario")->findOneBy(array(
            'nmUsuarioAcesso' => 'test-user'
        ));

        $this->assertNotEmpty($local, "A inserção do usuario falhu falhou");

    }


    public function tearDown() {

        // Delete base data
        parent::tearDown();
    }

}