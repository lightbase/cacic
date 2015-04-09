<?php

namespace Cacic\CommonBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Entity\TeSo;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150317110806 extends AbstractMigration implements ContainerAwareInterface
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
        $em = $this->container->get('doctrine.orm.entity_manager');

        $so_list = $em->getRepository('CacicCommonBundle:So')->findAll();

        foreach ($so_list as $so) {

            $te_so = $so->getTeSo();
            $id_so = $so->getIdSo();

            if ($te_so == '2.6.0.1.256') {
                // Primeiro pega o ID do 3.1 já cadastrado
                    $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                        'teSo' => 'Microsoft Windows Vista Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows Vista Professional');
                    $so_new->setSgSo('Win7');
                    $so_new->setTeSo('Microsoft Windows Vista Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.6.0.1.256');
                $so->setTeSo31('Microsoft Windows Vista Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.0.1.0') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 2000 Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 2000 Professional');
                    $so_new->setSgSo('W2K_PRO');
                    $so_new->setTeSo('Microsoft Windows 2000 Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.0.1.0');
                $so->setTeSo31('Microsoft Windows 2000 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.0.Service Pack 4') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 2000 Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 2000 Professional (SP4)');
                    $so_new->setSgSo('W2K-SP4');
                    $so_new->setTeSo('Microsoft Windows 2000 Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.0.Service Pack 4');
                $so->setTeSo31('Microsoft Windows 2000 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.2.2.274') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 2003 Server'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 2003 Server');
                    $so_new->setSgSo('W2K-SP4');
                    $so_new->setTeSo('Microsoft Windows 2003 Server');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.2.2.274');
                $so->setTeSo31('Microsoft Windows 2003 Server');

                $em->persist($so);
            }

            if ($te_so == '2.6.1.1.256.32') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 7 Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 7 Professional');
                    $so_new->setSgSo('Win7');
                    $so_new->setTeSo('Microsoft Windows 7 Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.6.1.1.256.32');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.6.1.1.256.64') {
                // Primeiro pega o ID do 3.1 já cadastrado
            $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                'teSo' => 'Microsoft Windows 7 Professional'
            ));

            if (empty($so_new)) {
                $so_new = new So();
                $so_new->setTeDescSo('Microsoft Windows 7 Professional');
                $so_new->setSgSo('Win7');
                $so_new->setTeSo('Microsoft Windows 7 Professional');
                $so_new->setInMswindows('S');

                $em->persist($so_new);
                $em->flush();
            }

            // Busca todos os computadores que têm o mesmo MAC Address e esse SO
            $this->atualizaSo($em, $id_so);

            $so = new TeSo();
            $so->setIdSo($id_so);
            $so->setTeSo28('2.6.1.1.256.64');
            $so->setTeSo31('Microsoft Windows 7 Professional');

            $em->persist($so);
            }

            if ($te_so == '2.6.1.1.256') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 7 Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 7 Professional');
                    $so_new->setSgSo('Win7');
                    $so_new->setTeSo('Microsoft Windows 7 Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.6.1.1.256');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.6.1.1.768.64') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 7 Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 7 Professional');
                    $so_new->setSgSo('Win7');
                    $so_new->setTeSo('Microsoft Windows 7 Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.6.1.1.768.64');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.6.1.1.768') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 7 Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 7 Professional');
                    $so_new->setSgSo('Win7');
                    $so_new->setTeSo('Microsoft Windows 7 Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.6.1.1.768');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.1.1.256.64') {
                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.1.1.256.64');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.1.1.256.64') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 7 Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 7 Professional');
                    $so_new->setSgSo('Win7');
                    $so_new->setTeSo('Microsoft Windows 7 Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.1.1.256.64');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.6.1') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 7 Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 7 Professional');
                    $so_new->setSgSo('Win7');
                    $so_new->setTeSo('Microsoft Windows 7 Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.6.1');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == 'Microsoft Windows 7 Professional') {
                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.1.1.256.64');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.6.1') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows 7 Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows 7 Professional');
                    $so_new->setSgSo('Win7');
                    $so_new->setTeSo('Microsoft Windows 7 Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.6.1');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.1.1.256.32') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows XP Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows XP Professional');
                    $so_new->setSgSo('Windows XP');
                    $so_new->setTeSo('Microsoft Windows XP Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.1.1.256.32');
                $so->setTeSo31('Microsoft Windows XP Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.1.1.256') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows XP Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows XP Professional');
                    $so_new->setSgSo('Windows XP');
                    $so_new->setTeSo('Microsoft Windows XP Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.1.1.256');
                $so->setTeSo31('Microsoft Windows XP Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.1.1.0') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows XP Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows XP Professional');
                    $so_new->setSgSo('Windows XP');
                    $so_new->setTeSo('Microsoft Windows XP Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.1.1.256');
                $so->setTeSo31('Microsoft Windows XP Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.1.Service Pack 3') {
                // Primeiro pega o ID do 3.1 já cadastrado
                $so_new = $em->getRepository('CacicCommonBundle:SO')->findOneBy(array(
                    'teSo' => 'Microsoft Windows XP Professional'
                ));

                if (empty($so_new)) {
                    $so_new = new So();
                    $so_new->setTeDescSo('Microsoft Windows XP Professional');
                    $so_new->setSgSo('Windows XP');
                    $so_new->setTeSo('Microsoft Windows XP Professional');
                    $so_new->setInMswindows('S');

                    $em->persist($so_new);
                    $em->flush();
                }

                // Busca todos os computadores que têm o mesmo MAC Address e esse SO
                $this->atualizaSo($em, $id_so);

                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.1.Service Pack 3');
                $so->setTeSo31('Microsoft Windows XP Professional');

                $em->persist($so);
            }

            if ($te_so == 'Microsoft Windows XP Professional') {
                $so = new TeSo();
                $so->setIdSo($id_so);
                $so->setTeSo28('2.5.1.Service Pack 3');
                $so->setTeSo31('Microsoft Windows XP Professional');

                $em->persist($so);
            }

            $em->flush($so);

        }

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

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
                $ArrCompAntigo = implode(', ', array_column($computadorAntigo, 'idComputador'));

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
}
