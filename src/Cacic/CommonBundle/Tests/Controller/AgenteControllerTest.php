<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 18/10/14
 * Time: 21:14
 */

namespace Cacic\CommonBundle\Tests\Controller;

use Cacic\CommonBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Session;


class AgenteControllerTest extends BaseTestCase {

    public function setUp() {
        // Load setup from BaseTestCase method
        parent::setUp();

        // Cria o cliente utilizando autenticação HTTP para o usuário admin padrão
        $this->client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => '123456',
            )
        );
        $this->container = $this->client->getContainer();
    }

    public function tearDown() {
        // Executa método de limpeza de todos os casos de teste
        parent::tearDown();
    }

} 