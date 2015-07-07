<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/04/15
 * Time: 12:51
 */

namespace Cacic\WSBundle\Tests;
use Cacic\CommonBundle\Tests\BaseTestCase as DefaultTestCase;


class BaseTestCase extends DefaultTestCase {

    public function setUp() {
        // Load setup from BaseTestCase method
        parent::setUp();

        // Load specific fixtures
        $fixtures = array_merge(
            $this->classes,
            array(
                'Cacic\WSBundle\DataFixtures\ORM\LoadRedeVersaoModuloData',
                'Cacic\WSBundle\DataFixtures\ORM\LoadTipoSo'
            )
        );
        $this->loadFixtures($fixtures);

        // Basic data
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        //$this->apiKey = $this->container->getParameter('test_api_key');

        // Load static data fixtures
        $kernel = $this->container->get('kernel');
        $this->data_dir = $kernel->locateResource("@CacicWSBundle/Resources/data/fixtures/");

        $this->computador = file_get_contents($this->data_dir."computador.json");
        $this->sem_mac = file_get_contents($this->data_dir."computador-sem-mac.json");
        $this->computador_http = file_get_contents($this->data_dir."computador-http.json");
        $this->coleta = file_get_contents($this->data_dir."coleta.json");
        $this->coleta_notebook = file_get_contents($this->data_dir."coleta-notebook.json");
        $this->computador_erro = file_get_contents($this->data_dir."computador-erro.json");
        $this->coleta_erro_orm = file_get_contents($this->data_dir."coleta-erro-orm.json");
        $this->computador_semso1 = file_get_contents($this->data_dir."computador-semso1.json");
        $this->computador_semso2 = file_get_contents($this->data_dir."computador-semso2.json");
        $this->computador_sem_tipo = file_get_contents($this->data_dir."computador-sem-tipo.json");
    }

    /**
     * Método que apaga todos os dados criados no teste
     */
    public function tearDown() {
        // Executa método de limpeza de todos os casos de teste
        parent::tearDown();

    }

}