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
            $grupoAdmin->setTeDescricaoGrupo('Acesso irrestrito');
            $grupoAdmin->setRole('ROLE_ADMIN');
            $grupoAdmin->setCsNivelAdministracao(true);

            $em->persist($grupoAdmin);
        }

        // A melhor solução é adicionar todos os usuários no Grupo de Administradores
        $usuario_list = $em->getRepository('CacicCommonBundle:Usuario')->findAll();
        foreach ($usuario_list as $usuario) {
            $usuario->setIdGrupoUsuario($grupoAdmin);

            $em->persist($usuario);
        }

        // Cria os outros grupos padrão do Cacic
        $grupo = new GrupoUsuario();
        $grupo->setNmGrupoUsuarios('comum');
        $grupo->setTeGrupoUsuarios('Comum');
        $grupo->setTeMenuGrupo('menu_adm.txt');
        $grupo->setTeDescricaoGrupo('Usuário limitado, sem acesso a informações confidenciais como Softwares Inventariados e Opções Administrativas como Forçar Coletas e Excluir Computadores. Poderá alterar sua própria senha.');
        $grupo->setCsNivelAdministracao(2);
        $grupo->setRole('ROLE_USER');

        $em->persist($grupo);

        // Cria os outros grupos padrão do Cacic
        $grupo = new GrupoUsuario();
        $grupo->setNmGrupoUsuarios('gestao');
        $grupo->setTeGrupoUsuarios('Gestão Central');
        $grupo->setTeMenuGrupo('menu_adm.txt');
        $grupo->setTeDescricaoGrupo('Acesso de leitura em todas as opções.');
        $grupo->setCsNivelAdministracao(3);
        $grupo->setRole('ROLE_GESTAO');

        $em->persist($grupo);

        // Cria os outros grupos padrão do Cacic
        $grupo = new GrupoUsuario();
        $grupo->setNmGrupoUsuarios('supervisao');
        $grupo->setTeGrupoUsuarios('Supervisão');
        $grupo->setTeMenuGrupo('menu_adm.txt');
        $grupo->setTeDescricaoGrupo('Manutenção de tabelas e acesso a todas as informações referentes à Localização.');
        $grupo->setCsNivelAdministracao(4);
        $grupo->setRole('ROLE_SUPERVISAO');

        $em->persist($grupo);


        // Cria os outros grupos padrão do Cacic
        $grupo = new GrupoUsuario();
        $grupo->setNmGrupoUsuarios('tecnico');
        $grupo->setTeGrupoUsuarios('Técnico');
        $grupo->setTeMenuGrupo('menu_adm.txt');
        $grupo->setTeDescricaoGrupo('Acesso técnico. Será permitido acessar configurações de rede e relatórios de Patrimônio e Hardware.');
        $grupo->setCsNivelAdministracao(5);
        $grupo->setRole('ROLE_TECNICO');

        $em->persist($grupo);

        // Grava tudo
        $em->flush();

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
