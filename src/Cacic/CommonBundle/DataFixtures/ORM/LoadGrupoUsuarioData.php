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

        // Adiciona referência ao Grupo que será usada depois
        $this->addReference('grupo-admin', $grupoAdmin);

        $manager->persist($grupoAdmin);
        $manager->flush();

    }

    public function getOrder()
    {
        return 2;
    }
}