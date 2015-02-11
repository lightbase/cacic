<?php
/**
 * Created by JetBrains PhpStorm.
 * User: eduardo
 * Date: 13/07/13
 * Time: 21:12
 * To change this template use File | Settings | File Templates.
 */

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Cacic\CommonBundle\Entity\GrupoUsuario;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * Carrega grupos de usuário
 */

class LoadGrupoUsuarioData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{

    /*
     * Carrega grupo admin
     */

    public function load(ObjectManager $manager)
    {
        $grupoAdmin = new GrupoUsuario();
        $grupoAdmin->setNmGrupoUsuarios('Admin');
        $grupoAdmin->setTeGrupoUsuarios('Administradores');
        $grupoAdmin->setTeMenuGrupo('menu_adm.txt');
        $grupoAdmin->setTeDescricaoGrupo('Acesso irrestrito');
        $grupoAdmin->setCsNivelAdministracao(true);
        $grupoAdmin->setRole('ROLE_ADMIN');

        // Adiciona referência ao Grupo que será usada depois
        $this->addReference('grupo-admin', $grupoAdmin);

        $manager->persist($grupoAdmin);

        // Cria os outros grupos padrão do Cacic
        $grupo = new GrupoUsuario();
        $grupo->setNmGrupoUsuarios('comum');
        $grupo->setTeGrupoUsuarios('Comum');
        $grupo->setTeMenuGrupo('menu_adm.txt');
        $grupo->setTeDescricaoGrupo('Usuário limitado, sem acesso a informações confidenciais como Softwares Inventariados e Opções Administrativas como Forçar Coletas e Excluir Computadores. Poderá alterar sua própria senha.');
        $grupo->setCsNivelAdministracao(2);
        $grupo->setRole('ROLE_USER');

        $manager->persist($grupo);

        // Cria os outros grupos padrão do Cacic
        $grupo = new GrupoUsuario();
        $grupo->setNmGrupoUsuarios('gestao');
        $grupo->setTeGrupoUsuarios('Gestão Central');
        $grupo->setTeMenuGrupo('menu_adm.txt');
        $grupo->setTeDescricaoGrupo('Acesso de leitura em todas as opções.');
        $grupo->setCsNivelAdministracao(3);
        $grupo->setRole('ROLE_GESTAO');

        $manager->persist($grupo);

        // Cria os outros grupos padrão do Cacic
        $grupo = new GrupoUsuario();
        $grupo->setNmGrupoUsuarios('supervisao');
        $grupo->setTeGrupoUsuarios('Supervisão');
        $grupo->setTeMenuGrupo('menu_adm.txt');
        $grupo->setTeDescricaoGrupo('Manutenção de tabelas e acesso a todas as informações referentes à Localização.');
        $grupo->setCsNivelAdministracao(4);
        $grupo->setRole('ROLE_SUPERVISAO');

        $manager->persist($grupo);


        // Cria os outros grupos padrão do Cacic
        $grupo = new GrupoUsuario();
        $grupo->setNmGrupoUsuarios('tecnico');
        $grupo->setTeGrupoUsuarios('Técnico');
        $grupo->setTeMenuGrupo('menu_adm.txt');
        $grupo->setTeDescricaoGrupo('Acesso técnico. Será permitido acessar configurações de rede e relatórios de Patrimônio e Hardware.');
        $grupo->setCsNivelAdministracao(5);
        $grupo->setRole('ROLE_TECNICO');

        $manager->flush();

    }

    public function getOrder()
    {
        return 2;
    }
}