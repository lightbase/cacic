<?php

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Cacic\CommonBundle\Entity\Usuario;

class LoadUsuarioData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userAdmin = new Usuario();
        $userAdmin->setNmUsuarioAcesso('admin');
        $userAdmin->setNmUsuarioCompleto('Administrador do Sistema');

        // Adiciona referência ao local
        $userAdmin->setIdLocal($this->getReference('local'));

        // Adiciona referência ao grupo
        $userAdmin->setIdGrupoUsuario($this->getReference('grupo-admin'));

        // Criptografa a senha
        //$userAdmin->setSalt(md5(uniqid()));

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($userAdmin)
        ;
        $userAdmin->setTeSenha($encoder->encodePassword('123456', $userAdmin->getSalt()));
        $userAdmin->setApiKey('cacic123');

        //$userAdmin->setTeSenha('7c4a8d09ca3762af61e59520943dc26494f8941b');

        $manager->persist($userAdmin);

        // Cria usuário Devel
        $userDevel = new Usuario();
        $userDevel->setNmUsuarioAcesso('devel');
        $userDevel->setNmUsuarioCompleto('Desenvolvedor do Sistema');

        // Adiciona referência ao local
        $userDevel->setIdLocal($this->getReference('local'));

        // Adiciona referência ao grupo
        $userDevel->setIdGrupoUsuario($this->getReference('grupo-devel'));

        // Criptografa a senha
        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($userDevel)
        ;
        $userDevel->setTeSenha($encoder->encodePassword('123456', $userDevel->getSalt()));
        $userDevel->setApiKey('cacictecnico');

        $manager->persist($userDevel);

        $manager->flush();
        
    }

    public function getOrder()
    {
        return 3;
    }
}   
