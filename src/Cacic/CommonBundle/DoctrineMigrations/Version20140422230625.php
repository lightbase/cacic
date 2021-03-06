<?php

namespace Cacic\CommonBundle\Migrations;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140422230625 extends AbstractMigration implements ContainerAwareInterface
{

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $logger = $this->container->get('logger');
        $rootDir = $this->container->get('kernel')->getRootDir();
        $upgrade = $rootDir."/../src/Cacic/CommonBundle/Resources/data/upgrade-3.0b3.sql";
        $upgradeSQL = file_get_contents($upgrade);

        $logger->debug("Arquivo de atualização: $upgrade");

        // Chama o container para executar o arquivo de atualização
        // FIXME: Só funciona no PostgreSQL
        $this->addSql($upgradeSQL);
        $this->addSql("SELECT upgrade()");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }

    public function postUp(Schema $schema)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        // ... update the entities
    }

    public function postDown(Schema $schema)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        // ... update the entities
    }
}
