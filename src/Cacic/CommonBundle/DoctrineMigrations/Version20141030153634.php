<?php

namespace Cacic\CommonBundle\Migrations;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141030153634 extends AbstractMigration implements ContainerAwareInterface
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

        $this->addSql("
        CREATE OR REPLACE FUNCTION conserta_propriedade() RETURNS VOID AS $$
        DECLARE
            impr record;
            v_id integer;
        BEGIN
            FOR impr IN select id_class_property
                from class_property
                where id_class IS NULL LOOP

                RAISE NOTICE 'Removing property_id = %',impr.id_class_property;

                DELETE FROM computador_coleta_historico
                WHERE id_class_property = impr.id_class_property;

                DELETE FROM computador_coleta
                WHERE id_class_property = impr.id_class_property;

                DELETE FROM class_property
                WHERE id_class_property = impr.id_class_property;

                END LOOP;
            RETURN;
        END;
        $$ LANGUAGE 'plpgsql';
        ");
        $logger->info("Função de atualização das impressoras criada");
        $this->addSql("SELECT conserta_propriedade();");
        $logger->info("Propriedades nulas removaidas");
        $this->addSql("ALTER TABLE class_property ALTER id_class SET NOT NULL;");
        $logger->info("Índice único criado");


    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
