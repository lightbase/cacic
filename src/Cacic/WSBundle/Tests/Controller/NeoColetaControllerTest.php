<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/04/15
 * Time: 12:01
 */

namespace Cacic\WSBundle\Tests\Controller;
use Cacic\WSBundle\Tests\BaseTestCase;


class NeoColetaControllerTest extends BaseTestCase {

    public function setUp() {
        // Carrega dados da classe Pai
        parent::setUp();

    }

    /**
     * Teste de inserção das coletas
     */
    public function testColeta() {
        $logger = $this->container->get('logger');
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
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Messaging account plugin for Jabber/XMPP");
        $this->assertNotEmpty($software);

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Software que não existe");
        $this->assertEmpty($software);

        // Testa se identificou que não é notebook
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Windows_NT'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:89',
            'idSo' => $so->getIdSo()
        ));
        $this->assertNotEmpty($computador);

        $notebook = $computador->getIsNotebook();
        $this->assertEmpty($notebook);

    }

    /**
     * Teste de coletas e identificação de notebook
     */

    public function testColetaNotebook() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_notebook
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Messaging account plugin for Jabber/XMPP");
        $this->assertNotEmpty($software);

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Software que não existe");
        $this->assertEmpty($software);

        // Testa se identificou que não é notebook
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Windows_NT'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:88',
            'idSo' => $so->getIdSo()
        ));
        $this->assertNotEmpty($computador);

        $notebook = $computador->getIsNotebook();
        $this->assertEquals(true, $notebook);

    }

    /**
     * Testa inserção de nova coleta com os mesmos softwares
     */
    public function testSoftwareDuplicado() {
        // 1 - Primeiro computador
        $logger = $this->container->get('logger');
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
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // 2 - Segundo Computador
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_notebook
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        // Verifica que dois computadores foram coletas
        $computadores = $em->getRepository("CacicCommonBundle:Computador")->findAll();
        $this->assertEquals(2, sizeof($computadores));

        // Checa se o notebook foi identificado
        $result = false;
        foreach ($computadores as $elm) {
            if ($elm->getIsNotebook() == true) {
                $result = true;
            }
        }
        $this->assertEquals(true, $result);

        // Busca software pelo nome
        $software = $em->getRepository("CacicCommonBundle:Software")->findBy(array(
            'nmSoftware' => 'Messaging account plugin for AIM'
        ));

        $this->assertEquals(1, sizeof($software));

    }

    /**
     * Testa erro de Entity Manager closed na inserção de software
     */
    public function testErroEmClosed() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_erro_orm
        );
        //$logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se os Softwares coletados foram inseridos
        $em =$this->container->get('doctrine')->getManager();

        // Verifica que um computador foi inserido
        $computadores = $em->getRepository("CacicCommonBundle:Computador")->findAll();
        $this->assertEquals(1, sizeof($computadores));

        // VErifica que a coleta de software foi realizada
        $software = $em->getRepository("CacicCommonBundle:Software")->findAll();
        $this->assertGreaterThan(1, sizeof($software));
    }

    public function tearDown() {

        // Remove dados da classe pai
        parent::tearDown();
    }
}