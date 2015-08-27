<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 27/08/15
 * Time: 10:54
 */

namespace Cacic\MultiBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Classe base com os dados do teste
 *
 * Class BaseTestCase
 * @package Cacic\MultiBundle\Tests
 */
class BaseTestCase extends WebTestCase
{
    public function __construct() {
        // Fixtures to be loaded on tests
        $this->classes = array(
            'Cacic\MultiBundle\DataFixtures\ORM\LoadSiteData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadLocalData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadGrupoUsuarioData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadUsuarioData',
        );
    }

    public function setup() {
        parent::setUp();
        // Load Fixtures
        $this->loadFixtures($this->classes);
    }

    public function tearDown() {
        parent::tearDown();
    }

}