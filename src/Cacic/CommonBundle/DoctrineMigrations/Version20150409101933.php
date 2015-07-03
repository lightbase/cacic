<?php

namespace Cacic\CommonBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Cacic\CommonBundle\Entity\TeSo;
use Cacic\CommonBundle\Entity\So;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150409101933 extends AbstractMigration implements ContainerAwareInterface
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

        $so_list = $em->getRepository('CacicCommonBundle:So')->findAll();

        foreach ($so_list as $so) {
            $te_so = $so->getTeSo();
            $id_so = $so->getIdSo();

            $logger->debug("Removendo computadores repetidos para o SO $te_so");

            $this->atualizaSo($em, $id_so);
        }

        // Finalmente adiciona índice único fundamental nas coletas
        //$this->addSql("CREATE UNIQUE INDEX mac_os_unique_idx ON computador (te_node_address, id_so);");

    }

    public function atualizaSo( $em, $id_so ) {
        // 1 - Acha lista de MAC address repetidos para esse SO
        $computadores = $em->getRepository('CacicCommonBundle:Computador')->filtroMac($id_so);

        // 2 - Busca o computador mais recente e os mais antigos dos MAC Address repetido caso não esteja vazio
        if (!empty($computadores)){

            foreach ($computadores as $computador ){

                $teNodeAddress = $computador['teNodeAddress'];

                $computadorRecente = $em->getRepository('CacicCommonBundle:Computador')->computadorRecente( $teNodeAddress, $id_so );
                $ArrCompRecente = print_r($computadorRecente,true);

                $computadorAntigo = $em->getRepository('CacicCommonBundle:Computador')->computadorAntigo( $teNodeAddress, $id_so, $ArrCompRecente );
                $ArrCompAntigo = array();
                foreach ($computadorAntigo as $comp) {
                    array_push($ArrCompAntigo, $comp['idComputador']);
                }
                $ArrCompAntigo = implode(', ', $ArrCompAntigo);

                // 2.1 - Para cada um dos resultados, atualiza o computador mais antigo para o mais novo
                #$this->addSql("UPDATE computador_coleta_historico SET id_computador = $ArrCompRecente WHERE id_computador IN ($ArrCompAntigo)");
                $this->addSql("UPDATE log_acesso SET id_computador = $ArrCompRecente WHERE id_computador IN ($ArrCompAntigo)");
                $this->addSql("UPDATE log_user_logado SET id_computador = $ArrCompRecente WHERE id_computador IN ($ArrCompAntigo)");

                // 2.3 - Apaga os registros dos computadores antigos em proriedade_software e relatorio_coleta
                $this->addSql("DELETE FROM proriedade_software WHERE id_computador IN ($ArrCompAntigo)");
                $this->addSql("DELETE FROM relatorio_coleta WHERE id_computador IN ($ArrCompAntigo)");
                $this->addSql("DELETE FROM computador_coleta_historico WHERE id_computador IN ($ArrCompAntigo)");
                $this->addSql("DELETE FROM computador_coleta WHERE id_computador IN ($ArrCompAntigo)");
                $this->addSql("DELETE FROM rede_grupo_ftp WHERE id_computador IN ($ArrCompAntigo)");
                $this->addSql("DELETE FROM computador WHERE id_computador IN($ArrCompAntigo)");

            }
        }
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
