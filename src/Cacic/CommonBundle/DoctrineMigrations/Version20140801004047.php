<?php

namespace Cacic\CommonBundle\Migrations;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140801004047 extends AbstractMigration implements ContainerAwareInterface
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

        // Função de atualização da tabela de impressoras
        $this->addSql("
        CREATE OR REPLACE FUNCTION conserta_impressoras() RETURNS VOID AS $$
DECLARE
	impr record;
	v_id integer;
BEGIN
	FOR impr IN select count(id) as impr,
			date,
			printer_id
		from tb_printer_counter
		group by date,
			printer_id
		having count(id) > 1 LOOP

		SELECT max(id) INTO v_id
		FROM tb_printer_counter
		WHERE date = impr.date
		AND printer_id = impr.printer_id;

		RAISE NOTICE 'Removing log id = % printer_id = % date = %',v_id,impr.printer_id,impr.date;

		DELETE FROM tb_printer_counter
		WHERE id <> v_id
		AND date = impr.date
		AND printer_id = impr.printer_id;

	END LOOP;

	RETURN;
END;
$$ LANGUAGE 'plpgsql';
        ");

        $logger->info("Função de atualização das impressoras criada");

        $this->addSql("SELECT conserta_impressoras();");

        $logger->info("Impressoras arrumadas");

        $this->addSql("create unique index tb_printer_counter_date_uq on tb_printer_counter (date, printer_id);");

        $logger->info("Índice único criado");

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
