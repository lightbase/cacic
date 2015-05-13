<?php

namespace Cacic\CommonBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150513145701 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $em = $this->container->get('doctrine.orm.entity_manager');
        $logger = $this->container->get('logger');

        $rootDir = $this->container->get('kernel')->getRootDir();
        $upgrade1 = $rootDir."/../src/Cacic/CommonBundle/Resources/data/upgrade-3.1.15.sql";
        $upgradeSQL1 = file_get_contents($upgrade1);

        $logger->debug("Arquivo de atualização: $upgrade1");

        // FIXME: Só funciona no PostgreSQL
        $this->addSql($upgradeSQL1);

        // Executa a atualização
        $this->addSql("SELECT upgrade_3115()");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
