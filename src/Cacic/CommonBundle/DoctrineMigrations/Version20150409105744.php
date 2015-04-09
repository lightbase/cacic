<?php

namespace Cacic\CommonBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Cacic\CommonBundle\Entity\PropriedadeSoftware;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150409105744 extends AbstractMigration implements ContainerAwareInterface
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

        $qb = $em->createQueryBuilder('cl')
            ->select('prop.idClassProperty', 'comp.idComputador', 'sw.idSoftware', 'cl.teClassPropertyValue')
            ->from('CacicCommonBundle:ComputadorColeta', 'cl')
            ->innerJoin('CacicCommonBundle:Computador', 'comp', 'WITH', 'cl.computador = comp.idComputador')
            ->innerJoin('CacicCommonBundle:ClassProperty', 'prop', 'WITH', 'cl.classProperty = prop.idClassProperty')
            ->innerJoin('CacicCommonBundle:Classe', 'classe', 'WITH', 'prop.idClass = classe.idClass')
            ->innerJoin('CacicCommonBundle:Software', 'sw', 'WITH', 'sw.nmSoftware = prop.nmPropertyName')
            ->andWhere("classe.nmClassName = 'SoftwareList'");

        $coletas = $qb->getQuery()->getResult();

        foreach ($coletas as $computador) {
            $this->atualizaComputador($em, $computador);
        }

        $em->flush();
    }

    public function atualizaComputador($em, $computador) {

        $logger = $this->container->get('logger');

        $computadorObj = $em->getRepository('CacicCommonBundle:Computador')->find($computador['idComputador']);
        $softwareObject = $em->getRepository('CacicCommonBundle:Software')->find($computador['idSoftware']);
        $classProperty = $em->getRepository('CacicCommonBundle:ClassProperty')->find($computador['idClassProperty']);

        $propSoftware = $em->getRepository('CacicCommonBundle:PropriedadeSoftware')->findOneBy(array(
            'classProperty' => $classProperty->getIdClassProperty(),
            'computador' => $computadorObj->getIdComputador(),
            'software' => $softwareObject->getIdSoftware()
        ));

        if (empty($propSoftware)) {
            // Insere entrada na tabela
            $propSoftware = new PropriedadeSoftware();
            $propSoftware->setComputador($computadorObj);
            $propSoftware->setSoftware($softwareObject);
            $propSoftware->setClassProperty($classProperty);

            // Adiciona software na coleta
            $softwareObject->addColetado($propSoftware);

            $em->persist($propSoftware);
            $em->persist($softwareObject);
            $em->flush();
        }

        return;
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
