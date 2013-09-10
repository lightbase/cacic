<?php

namespace Cacic\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Cacic\CommonBundle\Entity\ClassProperty;
use Cacic\CommonBundle\Entity\ClassPropertyType;

class LoadClassPropertyData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /*
     * Carrega os atributos permitidos às classes WMI que serão utilizadas na coleta
     */
    public function load(ObjectManager $manager)
    {
        /*************************
         * Classe ComputerSystem
         *************************/

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('Caption');
        $property->setTePropertyDescription('Short description of the object—a one-line string. This property is inherited from CIM_ManagedSystemElement.');

        // Referência à classe
        $property->setIdClass($this->getReference('ComputerSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('Domain');
        $property->setTePropertyDescription('Name of the domain to which a computer belongs.');

        // Referência à classe
        $property->setIdClass($this->getReference('ComputerSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('TotalPhysicalMemory');
        $property->setTePropertyDescription('Total size of physical memory. Be aware that, under some circumstances, this property may not return an accurate value for the physical memory. For example, it is not accurate if the BIOS is using some of the physical memory. For an accurate value, use the Capacity property in Win32_PhysicalMemory instead.');

        // Referência à classe
        $property->setIdClass($this->getReference('ComputerSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('UserName');
        $property->setTePropertyDescription('Name of a user that is logged on currently. This property must have a value. In a terminal services session, UserName returns the name of the user that is logged on to the console—not the user logged on during the terminal service session.');

        // Referência à classe
        $property->setIdClass($this->getReference('ComputerSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Grava todos os dados da classe
        $manager->flush();

        /*************************
         * Classe Win32_NetworkAdapterConfiguration
         *************************/

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('DefaultIPGateway');
        $property->setTePropertyDescription('Array of IP addresses of default gateways that the computer system uses.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('Description');
        $property->setTePropertyDescription('Description of the CIM_Setting object. This property is inherited from CIM_Setting.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('DHCPServer');
        $property->setTePropertyDescription('IP address of the dynamic host configuration protocol (DHCP) server.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('DNSDomain');
        $property->setTePropertyDescription('Organization name followed by a period and an extension that indicates the type of organization, such as microsoft.com. The name can be any combination of the letters A through Z, the numerals 0 through 9, and the hyphen (-), plus the period (.) character used as a separator.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('DNSHostName');
        $property->setTePropertyDescription('Host name used to identify the local computer for authentication by some utilities. Other TCP/IP-based utilities can use this value to acquire the name of the local computer. Host names are stored on DNS servers in a table that maps names to IP addresses for use by DNS. The name can be any combination of the letters A through Z, the numerals 0 through 9, and the hyphen (-), plus the period (.) character used as a separator. By default, this value is the Microsoft networking computer name, but the network administrator can assign another host name without affecting the computer name.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('DNSServerSearchOrder');
        $property->setTePropertyDescription('Array of server IP addresses to be used in querying for DNS servers.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('IPAddress');
        $property->setTePropertyDescription('Array of all of the IP addresses associated with the current network adapter. Starting with Windows Vista, this property can contain either IPv6 addresses or IPv4 addresses. For more information, see IPv6 and IPv4 Support in WMI.
Example IPv6 address: "2010:836B:4179::836B:4179"');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('IPSubnet');
        $property->setTePropertyDescription('Array of all of the subnet masks associated with the current network adapter.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('MACAddress');
        $property->setTePropertyDescription('Media Access Control (MAC) address of the network adapter. A MAC address is assigned by the manufacturer to uniquely identify the network adapter.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('WINSPrimaryServer');
        $property->setTePropertyDescription('IP address for the primary WINS server.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('WINSSecondaryServer');
        $property->setTePropertyDescription('IP address for the secondary WINS server.');

        // Referência à classe
        $property->setIdClass($this->getReference('Network'));

        // Grava o objeto
        $manager->persist($property);

        // Grava todos os dados da classe
        $manager->flush();


        /*************************
         * Classe Win32_OperatingSystem
         *************************/

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('Caption');
        $property->setTePropertyDescription('Short description of the object—a one-line string. The string includes the operating system version. For example, "Microsoft Windows XP Professional Version = 5.1.2500". This property can be localized.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('CSDVersion');
        $property->setTePropertyDescription('NULL-terminated string that indicates the latest service pack installed on a computer. If no service pack is installed, the string is NULL.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('InstallDate');
        $property->setTePropertyDescription('Date object was installed. This property does not require a value to indicate that the object is installed.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('LastBootUpTime');
        $property->setTePropertyDescription('Date and time the operating system was last restarted.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('NumberOfLicensedUsers');
        $property->setTePropertyDescription('Number of user licenses for the operating system. If unlimited, enter 0 (zero). If unknown, enter -1.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('OSArchitecture');
        $property->setTePropertyDescription('Architecture of the operating system, as opposed to the processor. This property can be localized.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('OSLanguage');
        $property->setTePropertyDescription('Language version of the operating system installed.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('ProductType');
        $property->setTePropertyDescription('Additional system information.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('SerialNumber');
        $property->setTePropertyDescription('Operating system product serial identification number.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('Version');
        $property->setTePropertyDescription('Version number of the operating system.');

        // Referência à classe
        $property->setIdClass($this->getReference('OperatingSystem'));

        // Grava o objeto
        $manager->persist($property);

        // Commit
        $manager->flush();

        /*************************
         * Software List
         *************************/

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('IDSoftware');
        $property->setTePropertyDescription('Identificador do software no registro.');

        // Referência à classe
        $property->setIdClass($this->getReference('Software'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('DisplayName');
        $property->setTePropertyDescription('Nome do software.');

        // Referência à classe
        $property->setIdClass($this->getReference('Software'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('DisplayVersion');
        $property->setTePropertyDescription('Versão identificada.');

        // Referência à classe
        $property->setIdClass($this->getReference('Software'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('URLInfoAbout');
        $property->setTePropertyDescription('URL do software.');

        // Referência à classe
        $property->setIdClass($this->getReference('Software'));

        // Grava o objeto
        $manager->persist($property);

        // Atributo
        $property = new ClassProperty();
        $property->setNmPropertyName('Publisher');
        $property->setTePropertyDescription('Nome do fabricante.');

        // Referência à classe
        $property->setIdClass($this->getReference('Software'));

        // Grava o objeto
        $manager->persist($property);



        // Commit
        $manager->flush();

        
    }

    public function getOrder()
    {
        return 2;
    }
}   
