<?php

namespace Cacic\CommonBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Cacic\CommonBundle\Entity\So;
use Cacic\CommonBundle\Entity\TeSo;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150413102515 extends AbstractMigration implements ContainerAwareInterface
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
        $logger = $this->container->get('logger');

        $rootDir = $this->container->get('kernel')->getRootDir();
        $upgrade1 = $rootDir."/../src/Cacic/CommonBundle/Resources/data/upgrade-3.1.12-3.sql";
        $upgradeSQL1 = file_get_contents($upgrade1);

        $logger->debug("Arquivo de atualização: $upgrade1");

        // Chama o container para executar o arquivo de atualização
        // FIXME: Só funciona no PostgreSQL
        $this->addSql($upgradeSQL1);

        // Primeiro remove tudo o que está na tabela atual
        $te_so_list = $em->getRepository('CacicCommonBundle:TeSo')->findAll();
        foreach ($te_so_list as $elm) {
            $em->remove($elm);
        }
        $em->flush();

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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
                $so->setTeSo28('2.6.1.1.768');
                $so->setTeSo31('Microsoft Windows 7 Professional');

                $em->persist($so);
            }

            if ($te_so == '2.5.1.1.256.64') {

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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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
                $so_novo = $so_new->getIdSo();
                $this->atualizaSo($em, $id_so, $so_novo);

                $so = new TeSo();
                $so->setIdSo($so_new->getIdSo());
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

            $em->flush();

        }

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }

    public function atualizaSo( $em, $id_so, $so_novo ) {
        $this->addSql("SELECT remove_repetidos($id_so, $so_novo)");
    }
}

