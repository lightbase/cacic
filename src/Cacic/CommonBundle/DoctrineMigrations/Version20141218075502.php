<?php

namespace Cacic\CommonBundle\Migrations;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141218075502 extends AbstractMigration implements ContainerAwareInterface
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

        $cocar = $this->container->get('kernel')->getBundle('CocarBundle');

        if (empty($cocar)) {
            $logger->info("O Cocar não está instalado. Não é necessário executar o migration");
            return;
        }

        # Remove todas as impressoras cujo serial for nulo
        $printer_list = $em->getRepository('CocarBundle:Printer')->findAll();

        foreach ($printer_list as $printer) {
            $logger->info("Removendo impressora ".$printer->getHost());

            $serie_list = $em->getRepository('CocarBundle:Printer')->findBy(array(
                'serie' => $printer->getSerie()
            ));

            foreach($serie_list as $serie) {
                if ($serie->getId() != $printer->getId()) {
                    $logger->debug("Impressora repetida ".$printer->getId() . " Série: ".$serie->getSerie());

                    $counter_list = $em->getRepository('CocarBundle:PrinterCounter')->findBy(array(
                        'printer' => $serie->getId()
                    ));

                    foreach($counter_list as $counter) {

                        $exists = $em->getRepository('CocarBundle:PrinterCounter')->findBy(array(
                            'date' => $counter->getDate(),
                            'prints' => $counter->getPrints()
                        ));

                        if (empty($exists)) {
                            $counter_obj = new \Swpb\Bundle\CocarBundle\Entity\PrinterCounter();
                            $counter_obj->setDate($counter->getDate());
                            $counter_obj->setPrinter($printer);
                            $counter_obj->setPrints($counter->getPrints());

                            $em->persist($counter_obj);
                        }

                        $em->remove($counter);
                    }

                    $em->remove($serie);
                }
            }

        }

        $em->flush();

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
