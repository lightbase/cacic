<?php

namespace Cacic\CommonBundle\Migrations;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Cacic\CommonBundle\Entity\GrupoUsuario;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141018220727 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $logger = $this->container->get('logger');
        $em = $this->container->get('doctrine.orm.entity_manager');

        $grupoAdmin = $em->getRepository('CacicCommonBundle:GrupoUsuario')->findOneBy(array(
            'nmGrupoUsuarios' => 'Admin'
        ));

        if (empty($grupoAdmin)) {
            // Cria pelo menos um grupo de administradores
            $grupoAdmin = new GrupoUsuario();
            $grupoAdmin->setNmGrupoUsuarios('Admin');
            $grupoAdmin->setTeGrupoUsuarios('Administradores');
            $grupoAdmin->setTeMenuGrupo('menu_adm.txt');
            $grupoAdmin->setTeDescricaoGrupo('Acesso ao menu de administração');
            $grupoAdmin->setCsNivelAdministracao(true);
            $grupoAdmin->setRole('ROLE_ADMIN');

            $em->persist($grupoAdmin);
        }

        // A melhor solução é adicionar todos os usuários no Grupo de Administradores
        $usuario_list = $em->getRepository('CacicCommonBundle:Usuario')->findAll();
        foreach ($usuario_list as $usuario) {
            $usuario->setIdGrupoUsuario($grupoAdmin);

            $em->persist($usuario);
        }

        $grupo = $em->getRepository('CacicCommonBundle:GrupoUsuario')->findOneBy(array(
            'role' => 'ROLE_USER'
        ));

        if (empty($grupo)) {
            // Cria os outros grupos padrão do Cacic
            $grupo = new GrupoUsuario();
            $grupo->setNmGrupoUsuarios('comum');
            $grupo->setTeGrupoUsuarios('Comum');
            $grupo->setTeMenuGrupo('menu_adm.txt');
            $grupo->setTeDescricaoGrupo('Acesso de leitura em todas as opções.');
            $grupo->setCsNivelAdministracao(2);
            $grupo->setRole('ROLE_USER');

            $em->persist($grupo);
        }

        $grupo = $em->getRepository('CacicCommonBundle:GrupoUsuario')->findOneBy(array(
            'role' => 'ROLE_GESTAO'
        ));

        if (empty($grupo)) {
            // Cria os outros grupos padrão do Cacic
            $grupo = new GrupoUsuario();
            $grupo->setNmGrupoUsuarios('gestao');
            $grupo->setTeGrupoUsuarios('Gestão Central');
            $grupo->setTeMenuGrupo('menu_adm.txt');
            $grupo->setTeDescricaoGrupo('Acesso ao menu de Manutenção.');
            $grupo->setCsNivelAdministracao(3);
            $grupo->setRole('ROLE_GESTAO');

            $em->persist($grupo);
        }

        $grupo = $em->getRepository('CacicCommonBundle:GrupoUsuario')->findOneBy(array(
            'role' => 'ROLE_DEVEL'
        ));

        if (empty($grupo)) {
            // Grupo para desenvolvedores
            $grupoDev = new GrupoUsuario();
            $grupoDev->setNmGrupoUsuarios('devel');
            $grupoDev->setTeGrupoUsuarios('Desenvolvedores');
            $grupoDev->setTeMenuGrupo('menu_adm.txt');
            $grupoDev->setTeDescricaoGrupo('Acesso às telas de log do sistema.');
            $grupoDev->setCsNivelAdministracao(true);
            $grupoDev->setRole('ROLE_DEVEL');

            $em->persist($grupoDev);
        }

        // Grava tudo
        $em->flush();

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
