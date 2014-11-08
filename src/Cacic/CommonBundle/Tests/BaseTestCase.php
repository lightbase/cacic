<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 07/10/14
 * Time: 12:47
 */

namespace Cacic\CommonBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;


class BaseTestCase extends WebTestCase {

    /**
     * Método construtor
     */
    public function __construct() {
        // Fixtures to be loaded on tests
        $this->classes = array(
            'Cacic\CommonBundle\DataFixtures\ORM\LoadLocalData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadRedeData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadClasseData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadClassPropertyData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadAcaoData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadCollectDefClassData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadConfiguracaoPadraoData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadConfiguracaoLocalData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadGrupoUsuarioData',
            'Cacic\CommonBundle\DataFixtures\ORM\LoadUsuarioData',
        );
    }

    /**
     * Setup test data for all cases
     */

    public function setUp() {
        //$this->loadFixtures($this->classes);
    }

    /**
     * Clear test data for all cases
     */
    public function tearDown() {

    }
} 