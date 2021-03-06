<?php

namespace Cacic\CommonBundle\Tests\Controller;

use Cacic\CommonBundle\Tests\BaseTestCase;

class DefaultControllerTest extends BaseTestCase
{
    public function setUp() {
        // Load base data
        parent::setUp();

        $this->fixtures = $this->loadFixtures($this->classes)->getReferenceRepository();

        $this->client = static::makeClient(true);
        $this->container = $this->client->getContainer();
        $this->logger = $this->container->get('logger');

        $this->em = $this->container->get('doctrine')->getManager();

    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $response = $client->getResponse();

        $this->assertNotEquals(500, $response->getStatusCode());
    }

    /**
     * Testa autenticação utilizando HTTP BASIC
     */
    public function testLogin() {

        $this->client->request('GET', '/');

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $this->logger->debug("Response status: $status");
        $this->assertEquals(200, $status);
    }

    public function tearDown() {
        // Remove default data
        parent::tearDown();
    }
}
