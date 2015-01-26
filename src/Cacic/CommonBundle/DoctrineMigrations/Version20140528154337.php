<?php

namespace Cacic\CommonBundle\Migrations;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140528154337 extends AbstractMigration implements ContainerAwareInterface
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
        $modulos = $em->getRepository('CacicCommonBundle:Acao')->findBy( array('csOpcional' => 'N') );

        foreach ($modulos as $elm) {
            // PEga todos os módulos que são opcionais e remove da tabela acao_rede
            $acoes = $em->getRepository('CacicCommonBundle:AcaoRede')->findBy( array('acao' => $elm->getIdAcao() ) );

            foreach ($acoes as $acao) {
                $em->remove($acao);
            }
        }

        $em->flush();

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}