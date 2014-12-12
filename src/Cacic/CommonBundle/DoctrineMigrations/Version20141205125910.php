<?php

namespace Cacic\CommonBundle\Migrations;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141205125910 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $sm = $this->connection->getSchemaManager();
        $logger = $this->container->get('logger');
        $em = $this->container->get('doctrine.orm.entity_manager');

        $this->addSql("ALTER TABLE software_estacao DROP CONSTRAINT software_estacao_pkey");
        $this->addSql(" ALTER TABLE aquisicao_item DROP CONSTRAINT aquisicao_item_pkey");
        $this->addSql("CREATE TABLE IF NOT EXISTS aquisicoes_software (id_software INT NOT NULL, id_aquisicao INT NOT NULL, id_tipo_licenca INT NOT NULL)");
        #$this->addSql("CREATE INDEX IDX_6BCDE8B1270B845A ON aquisicoes_software (id_software);");
        #$this->addSql("CREATE INDEX IDX_6BCDE8B1CF537CBE2AFF7683 ON aquisicoes_software (id_aquisicao, id_tipo_licenca);");
        #$this->addSql("ALTER TABLE aquisicoes_software ADD CONSTRAINT FK_6BCDE8B1270B845A FOREIGN KEY (id_software) REFERENCES software (id_software);");
        $this->addSql("ALTER TABLE aquisicao_item DROP id_software;");
        $this->addSql("ALTER TABLE aquisicao_item ADD PRIMARY KEY (id_tipo_licenca, id_aquisicao);");
        #$this->addSql("ALTER TABLE aquisicoes_software ADD PRIMARY KEY (id_software, id_aquisicao, id_tipo_licenca);");

        $logger->debug("Migração de software finalizada");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
