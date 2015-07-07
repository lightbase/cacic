<?php

namespace Cacic\CommonBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Cacic\CommonBundle\Entity\GrupoUsuario;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150703105634 extends AbstractMigration implements ContainerAwareInterface
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

        // Pega grupos gestão e supervisão
        $grupo_user = $em->getRepository("CacicCommonBundle:GrupoUsuario")->findOneBy(array(
            'role' => 'ROLE_USER'
        ));

        if (empty($grupo_user)) {
            $logger->debug("Criando grupo ROLE_USER");

            $grupo_user = new GrupoUsuario();
            $grupo_user->setNmGrupoUsuarios('comum');
            $grupo_user->setTeGrupoUsuarios('Comum');
            $grupo_user->setTeMenuGrupo('menu_adm.txt');
            $grupo_user->setTeDescricaoGrupo('Acesso de leitura em todas as opções.');
            $grupo_user->setCsNivelAdministracao(2);
            $grupo_user->setRole('ROLE_USER');

            $em->persist($grupo_user);
        }

        $grupo_gestao = $em->getRepository("CacicCommonBundle:GrupoUsuario")->findOneBy(array(
            'role' => 'ROLE_GESTAO'
        ));

        if (empty($grupo_gestao)) {
            $logger->debug("Criando grupo ROLE_GESTAO");

            $grupo_gestao = new GrupoUsuario();
            $grupo_gestao->setNmGrupoUsuarios('gestao');
            $grupo_gestao->setTeGrupoUsuarios('Gestão Central');
            $grupo_gestao->setTeMenuGrupo('menu_adm.txt');
            $grupo_gestao->setTeDescricaoGrupo('Acesso ao menu de Manutenção.');
            $grupo_gestao->setCsNivelAdministracao(3);
            $grupo_gestao->setRole('ROLE_GESTAO');

            $em->persist($grupo_gestao);
        }

        // 1 - Todos os técnicos vão virar usuários comuns
        $grupo = $em->getRepository("CacicCommonBundle:GrupoUsuario")->findOneBy(array(
            'role' => 'ROLE_TECNICO'
        ));

        if (!empty($grupo)) {

            $user_list = $em->getRepository("CacicCommonBundle:Usuario")->findBy(array(
                'idGrupoUsuario' => $grupo->getIdGrupoUsuario()
            ));

            foreach ($user_list as $user) {
                $logger->debug("Atualizando papel de ROLE_TECNICO para ROLE_USER para o usuário: ".$user->getnmUsuarioAcesso());

                $user->setIdGrupoUsuario($grupo_user);
                $em->persist($user);
            }

            $logger->debug("Removendo grupo ROLE_TECNICO");

            $em->remove($grupo);
        }

        // 2 - Todos os usuários supervisão vão virar gestão
        $grupo = $em->getRepository("CacicCommonBundle:GrupoUsuario")->findOneBy(array(
            'role' => 'ROLE_SUPERVISAO'
        ));

        if (!empty($grupo)) {

            $user_list = $em->getRepository("CacicCommonBundle:Usuario")->findBy(array(
                'idGrupoUsuario' => $grupo->getIdGrupoUsuario()
            ));

            foreach ($user_list as $user) {
                $logger->debug("Atualizando papel de ROLE_SUPERVISAO para ROLE_GESTAO para o usuário: ".$user->getnmUsuarioAcesso());

                $user->setIdGrupoUsuario($grupo_gestao);
                $em->persist($user);
            }

            $logger->debug("Removendo grupo ROLE_SUPERVISAO");

            $em->remove($grupo);
        }

        // 3 - Criar novo grupo para desenvolvedores
        $grupoDev = $em->getRepository('CacicCommonBundle:GrupoUsuario')->findOneBy(array(
            'role' => 'ROLE_DEVEL'
        ));

        if (empty($grupoDev)) {
            // Grupo para desenvolvedores
            $grupoDev = new GrupoUsuario();
            $grupoDev->setNmGrupoUsuarios('devel');
            $grupoDev->setTeGrupoUsuarios('Desenvolvedores');
            $grupoDev->setTeMenuGrupo('menu_adm.txt');
            $grupoDev->setTeDescricaoGrupo('Acesso às telas de log do sistema.');
            $grupoDev->setCsNivelAdministracao(true);
            $grupoDev->setRole('ROLE_DEVEL');

            $logger->debug("Criando grupo para desenvolvedores com ROLE_DEVEL");

            $em->persist($grupoDev);
        }

        $em->flush();

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
