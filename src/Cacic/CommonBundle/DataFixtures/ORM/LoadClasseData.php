<?php

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Cacic\CommonBundle\Entity\Classe;

class LoadClasseData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /*
     * Carrega dados das classes WMI que serão utilizadas nas coletas do Gerente.
     */

    public function load(ObjectManager $manager)
    {
        // Classe Computer System
        $classe = new Classe();
        $classe->setNmClassName('Win32_ComputerSystem');
        $classe->setTeClassDescription('The Win32_ComputerSystem WMI class represents a computer system running Windows.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties.');

        // Adiciona referência à classe OperatingSystem que será usada depois
        $this->addReference('ComputerSystem', $classe);

        $manager->persist($classe);
        $manager->flush();

        // Classe operating System
        $classe = new Classe();
        $classe->setNmClassName('Win32_OperatingSystem');
        $classe->setTeClassDescription('The Win32_OperatingSystem WMI class represents a Windows-based operating system installed on a computer. Any operating system that can be installed on a computer that can run a Windows-based operating system is a descendent or member of this class. Win32_OperatingSystem is a singleton class. To get the single instance, use "@" for the key.
Windows Server 2003 and Windows XP:  If a computer has multiple operating systems installed, this class only returns an instance for the currently active operating system.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties. Properties and methods are in alphabetic order, not MOF order.');

        // Adiciona referência à classe OperatingSystem que será usada depois
        $this->addReference('OperatingSystem', $classe);

        $manager->persist($classe);
        $manager->flush();

        // Classe de rede
        $classe = new Classe();
        $classe->setNmClassName('Win32_NetworkAdapterConfiguration');
        $classe->setTeClassDescription('The Win32_NetworkAdapterConfiguration WMI class represents the attributes and behaviors of a network adapter. This class includes extra properties and methods that support the management of the TCP/IP and Internetwork Packet Exchange (IPX) protocols that are independent from the network adapter.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties. Properties are listed in alphabetic order, not MOF order.');

        // Adiciona referência à classe OperatingSystem que será usada depois
        $this->addReference('Network', $classe);

        $manager->persist($classe);
        $manager->flush();

        // Classe de rede
        $classe = new Classe();
        $classe->setNmClassName('Win32_NetworkAdapterConfiguration');
        $classe->setTeClassDescription('The Win32_NetworkAdapterConfiguration WMI class represents the attributes and behaviors of a network adapter. This class includes extra properties and methods that support the management of the TCP/IP and Internetwork Packet Exchange (IPX) protocols that are independent from the network adapter.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties. Properties are listed in alphabetic order, not MOF order.');

        // Adiciona referência à classe OperatingSystem que será usada depois
        $this->addReference('Network', $classe);

        $manager->persist($classe);
        $manager->flush();
        
    }

    public function getOrder()
    {
        return 1;
    }
}   
