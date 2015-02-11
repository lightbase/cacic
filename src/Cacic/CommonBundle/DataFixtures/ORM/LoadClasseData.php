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
    /*
     * Array de dados das classes que serão carregadas
     */
    private $classes = array(
        array('className' => 'Win32_ComputerSystem',
            'description' => 'The Win32_ComputerSystem WMI class represents a computer system running Windows.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties.',
            'reference' => 'ComputerSystem'),
        array('className' => 'OperatingSystem',
            'description' => 'The Win32_OperatingSystem WMI class represents a Windows-based operating system installed on a computer. Any operating system that can be installed on a computer that can run a Windows-based operating system is a descendent or member of this class. Win32_OperatingSystem is a singleton class. To get the single instance, use "@" for the key.
Windows Server 2003 and Windows XP:  If a computer has multiple operating systems installed, this class only returns an instance for the currently active operating system.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties. Properties and methods are in alphabetic order, not MOF order.',
            'reference' => 'OperatingSystem'),
        array('className' => 'NetworkAdapterConfiguration',
            'description' => 'The Win32_NetworkAdapterConfiguration WMI class represents the attributes and behaviors of a network adapter. This class includes extra properties and methods that support the management of the TCP/IP and Internetwork Packet Exchange (IPX) protocols that are independent from the network adapter.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties. Properties are listed in alphabetic order, not MOF order.',
            'reference' => 'Network'),
        array('className' => 'SoftwareList',
            'description' => 'Computer softwares',
            'reference' => 'Software'
        ),
        array('className' => 'Win32_Keyboard',
            'description' => 'Represents a keyboard installed on a computer system running Windows.',
            'reference' => 'Keyboard'
        ),
        array('className' => 'Win32_PointingDevice',
            'description' => 'Represents an input device used to point to and select regions on the display of a computer system running Windows.',
            'reference' => 'PointingDevice'
        ),
        array('className' => 'Win32_PhysicalMedia',
            'description' => 'Represents any type of documentation or storage medium.',
            'reference' => 'PhysicalMedia'
        ),
        array('className' => 'Win32_BaseBoard',
            'description' => 'Represents a baseboard (also known as a motherboard or system board).',
            'reference' => 'BaseBoard'
        ),
        array('className' => 'Win32_BIOS',
            'description' => 'Represents the attributes of the computer system\'s basic input or output services (BIOS) that are installed on the computer.',
            'reference' => 'BIOS'
        ),
        array('className' => 'Win32_MemoryDevice',
            'description' => 'Represents the properties of a computer system\'s memory device along with its associated mapped addresses.',
            'reference' => 'MemoryDevice'
        ),
        array('className' => 'Win32_PhysicalMemory',
            'description' => 'Represents a physical memory device located on a computer as available to the operating system.',
            'reference' => 'PhysicalMemory'
        ),
        array('className' => 'Win32_Processor',
            'description' => 'Represents a device capable of interpreting a sequence of machine instructions on a computer system running Windows.',
            'reference' => 'Processor'
        ),
        array('className' => 'Win32_Printer',
            'description' => 'Represents a device connected to a computer system running Windows that is capable of reproducing a visual image on a medium.',
            'reference' => 'Printer'
        ),
        array('className' => 'Win32_DesktopMonitor',
            'description' => 'Represents the type of monitor or display device attached to the computer system.',
            'reference' => 'DesktopMonitor'
        ),
        array('className' => 'Patrimonio',
            'description' => 'Dados de patrimônio e localização física',
            'reference' => 'Patrimonio'
        ),
        array('className' => 'Win32_SystemEnclosure',
            'description' => 'The Win32_SystemEnclosure WMI class represents the properties that are associated with a physical system enclosure',
            'reference' => 'SystemEnclosure'
        ),
        array('className' => 'Win32_DiskDrive',
            'description' => 'Discos',
            'reference' => 'DiskDrive'
        )
    );

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
        foreach ($this->classes as $elemento){
            // Crio os objetos e atributos para a classe
            $classe = new Classe();
            $classe->setNmClassName($elemento['className']);
            $classe->setTeClassDescription($elemento['description']);

            // Adiciona referência
            $this->addReference($elemento['reference'], $classe);

            // Grava os dados
            $manager->persist($classe);
        }
        // Commit
        $manager->flush();
        
    }

    public function getOrder()
    {
        return 1;
    }
}   
