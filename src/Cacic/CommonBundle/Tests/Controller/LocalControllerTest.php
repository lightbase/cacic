<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 06/07/15
 * Time: 10:32
 */

namespace Cacic\CommonBundle\Tests\Controller;

use Cacic\CommonBundle\Entity\ConfiguracaoLocal;
use Cacic\CommonBundle\Entity\ConfiguracaoPadrao;

class LocalControllerTest extends DefaultControllerTest {

    public function setUp() {
        // Load base data
        parent::setUp();

    }


    /**
     * Testa cadastro de novos locais
     */
    public function testAddLocal() {

        // Conecta ao formulário
        $crawler = $this->client->request(
            'GET',
            '/admin/local/cadastrar',
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

        //$this->logger->debug("111111111111111111111111111111111\n".print_r($form, true));

        // Envia formulário de cadastro com valores de teste
        $this->client->submit($form, array(
            'local[nmLocal]' => 'test',
            'local[sgLocal]' => 'TT',

        ));

        // Testa resposta do formulário
        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $this->logger->debug("Response status: $status");
        $this->assertNotEquals(500, $response->getStatusCode());

        // Agora testa pra ver se o local foi inserido com sucesso
        $local = $this->em->getRepository("CacicCommonBundle:Local")->findOneBy(array(
            'nmLocal' => 'test'
        ));

        $this->assertNotEmpty($local, "A inserção do local falhou");

        // Testa se é possível encontrar uma configuração ajustada
        $found = $local->getConfiguracaoChave(
            'email_notifications'
        );
        $this->assertEquals("N", $found, "A configuração não foi encontrada com o valor esperado");

    }


    public function tearDown() {

        // Delete base data
        parent::tearDown();
    }

}