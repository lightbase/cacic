<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/04/15
 * Time: 12:01
 */

namespace Cacic\WSBundle\Tests\Controller;


class NeoColetaControllerTest extends NeoControllerTest {

    public function setUp() {
        // Carrega dados da classe Pai
        parent::setUp();

        $this->coleta = '{
                "computador": {
                    "networkDevices": [
                        {
                            "ipv4": "10.1.0.56",
                            "ipv6": "fe80::295b:a8db:d433:ebe%4",
                            "mac": "9C:D2:1E:EA:E0:89",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "Wi-Fi"
                        },
                        {
                            "ipv4": "192.168.56.1",
                            "ipv6": "fe80::19f2:4739:8a9e:45e4%16",
                            "mac": "08:00:27:00:14:2B",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "VirtualBox Host-Only Network"
                        }
                    ],
                    "operatingSystem": {
                        "idOs": 176,
                        "nomeOs": "Windows_NT",
                        "tipo": "windows"
                    },
                    "usuario": "Eric Menezes",
                    "nmComputador": "Notebook-XPTO",
                    "versaoAgente": "2.8.0",
                    "versaoGercols": "2.8.0"
                },
                "hardware": {
                    "bios": {
                        "releaseDate": "11/12/2013",
                        "romSize": "4096 kB",
                        "runtimeSize": "128 kB",
                        "vendor": "Dell Inc.",
                        "version": "A07"
                    },
                    "cpu": {
                        "clock": "768000000 Hz",
                        "name": "Intel(R) Core(TM) i7-4500U CPU @ 1.80GHz",
                        "vendor": "Intel Corp."
                    },
                    "ethernet_card": {
                        "capacity": "100000000 bits/s",
                        "description": "Ethernet interface",
                        "logicalname": "eth0",
                        "product": "RTL8101E/RTL8102E PCI Express Fast Ethernet controller",
                        "serial": "78:2b:cb:eb:36:24",
                        "vendor": "Realtek Semiconductor Co., Ltd."
                    },
                    "isNotebook": {
                        "value": "true"
                    },
                    "memory": {
                        "size": "8589934592 bytes"
                    },
                    "motherboard": {
                        "assetTag": "Not Specified",
                        "manufacturer": "Dell Inc.",
                        "onboardCapabilities": [
                            "Video"
                        ],
                        "productName": "0YK7DY",
                        "serialNumber": ".CSQKLZ1.BR1081943R0013.",
                        "version": "A00"
                    },
                    "wireless_card": {
                        "description": "Wireless interface",
                        "firmware": "N/A",
                        "logicalname": "wlan0",
                        "product": "QCA9565 / AR9565 Wireless Network Adapter",
                        "serial": "9c:d2:1e:ea:e0:89",
                        "vendor": "Qualcomm Atheros"
                    }
                },
                "software": {
                    "": {
                        "name": ""
                    },
                    "account-plugin-aim": {
                        "description": "Messaging account plugin for AIM",
                        "installed_size": "941",
                        "name": "account-plugin-aim",
                        "url": "http://wiki.gnome.org/Empathy",
                        "version": "3.8.6-0ubuntu9"
                    },
                    "account-plugin-facebook": {
                        "description": "GNOME Control Center account plugin for single signon - facebook",
                        "installed_size": "65",
                        "name": "account-plugin-facebook",
                        "url": "https://launchpad.net/account-plugins",
                        "version": "0.11+14.04.20140409.1-0ubuntu1"
                    },
                    "account-plugin-flickr": {
                        "description": "GNOME Control Center account plugin for single signon - flickr",
                        "installed_size": "64",
                        "name": "account-plugin-flickr",
                        "url": "https://launchpad.net/account-plugins",
                        "version": "0.11+14.04.20140409.1-0ubuntu1"
                    },
                    "account-plugin-google": {
                        "description": "GNOME Control Center account plugin for single signon",
                        "installed_size": "66",
                        "name": "account-plugin-google",
                        "url": "https://launchpad.net/account-plugins",
                        "version": "0.11+14.04.20140409.1-0ubuntu1"
                    },
                    "account-plugin-jabber": {
                        "description": "Messaging account plugin for Jabber/XMPP",
                        "installed_size": "941",
                        "name": "account-plugin-jabber",
                        "url": "http://wiki.gnome.org/Empathy",
                        "version": "3.8.6-0ubuntu9"
                    },
                    "account-plugin-salut": {
                        "description": "Messaging account plugin for Local XMPP (Salut)",
                        "installed_size": "941",
                        "name": "account-plugin-salut",
                        "url": "http://wiki.gnome.org/Empathy",
                        "version": "3.8.6-0ubuntu9"
                    },
                    "account-plugin-twitter": {
                        "description": "GNOME Control Center account plugin for single signon - twitter",
                        "installed_size": "63",
                        "name": "account-plugin-twitter",
                        "url": "https://launchpad.net/account-plugins",
                        "version": "0.11+14.04.20140409.1-0ubuntu1"
                    }
                }
            }';


        $this->coleta_notebook = '{
                "computador": {
                    "networkDevices": [
                        {
                            "ipv4": "10.1.0.56",
                            "ipv6": "fe80::295b:a8db:d433:ebe%4",
                            "mac": "9C:D2:1E:EA:E0:88",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "Wi-Fi"
                        },
                        {
                            "ipv4": "192.168.56.1",
                            "ipv6": "fe80::19f2:4739:8a9e:45e4%16",
                            "mac": "08:00:27:00:14:2B",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "VirtualBox Host-Only Network"
                        }
                    ],
                    "operatingSystem": {
                        "idOs": 176,
                        "nomeOs": "Windows_NT",
                        "tipo": "windows"
                    },
                    "usuario": "Eric Menezes",
                    "nmComputador": "Notebook-XPTO",
                    "versaoAgente": "2.8.0",
                    "versaoGercols": "2.8.0"
                },
                "hardware": {
                    "bios": {
                        "releaseDate": "11/12/2013",
                        "romSize": "4096 kB",
                        "runtimeSize": "128 kB",
                        "vendor": "Dell Inc.",
                        "version": "A07"
                    },
                    "cpu": {
                        "clock": "768000000 Hz",
                        "name": "Intel(R) Core(TM) i7-4500U CPU @ 1.80GHz",
                        "vendor": "Intel Corp."
                    },
                    "ethernet_card": {
                        "capacity": "100000000 bits/s",
                        "description": "Ethernet interface",
                        "logicalname": "eth0",
                        "product": "RTL8101E/RTL8102E PCI Express Fast Ethernet controller",
                        "serial": "78:2b:cb:eb:36:24",
                        "vendor": "Realtek Semiconductor Co., Ltd."
                    },
                    "isNotebook": {
                        "value": "true"
                    },
                    "memory": {
                        "size": "8589934592 bytes"
                    },
                    "motherboard": {
                        "assetTag": "Not Specified",
                        "manufacturer": "Dell Inc.",
                        "onboardCapabilities": [
                            "Video"
                        ],
                        "productName": "0YK7DY",
                        "serialNumber": ".CSQKLZ1.BR1081943R0013.",
                        "version": "A00"
                    },
                    "wireless_card": {
                        "description": "Wireless interface",
                        "firmware": "N/A",
                        "logicalname": "wlan0",
                        "product": "QCA9565 / AR9565 Wireless Network Adapter",
                        "serial": "9c:d2:1e:ea:e0:89",
                        "vendor": "Qualcomm Atheros"
                    },
                    "IsNotebook": {
                        "Value": true
                    }
                },
                "software": {
                    "": {
                        "name": ""
                    },
                    "account-plugin-aim": {
                        "description": "Messaging account plugin for AIM",
                        "installed_size": "941",
                        "name": "account-plugin-aim",
                        "url": "http://wiki.gnome.org/Empathy",
                        "version": "3.8.6-0ubuntu9"
                    },
                    "account-plugin-facebook": {
                        "description": "GNOME Control Center account plugin for single signon - facebook",
                        "installed_size": "65",
                        "name": "account-plugin-facebook",
                        "url": "https://launchpad.net/account-plugins",
                        "version": "0.11+14.04.20140409.1-0ubuntu1"
                    },
                    "account-plugin-flickr": {
                        "description": "GNOME Control Center account plugin for single signon - flickr",
                        "installed_size": "64",
                        "name": "account-plugin-flickr",
                        "url": "https://launchpad.net/account-plugins",
                        "version": "0.11+14.04.20140409.1-0ubuntu1"
                    },
                    "account-plugin-google": {
                        "description": "GNOME Control Center account plugin for single signon",
                        "installed_size": "66",
                        "name": "account-plugin-google",
                        "url": "https://launchpad.net/account-plugins",
                        "version": "0.11+14.04.20140409.1-0ubuntu1"
                    },
                    "account-plugin-jabber": {
                        "description": "Messaging account plugin for Jabber/XMPP",
                        "installed_size": "941",
                        "name": "account-plugin-jabber",
                        "url": "http://wiki.gnome.org/Empathy",
                        "version": "3.8.6-0ubuntu9"
                    },
                    "account-plugin-salut": {
                        "description": "Messaging account plugin for Local XMPP (Salut)",
                        "installed_size": "941",
                        "name": "account-plugin-salut",
                        "url": "http://wiki.gnome.org/Empathy",
                        "version": "3.8.6-0ubuntu9"
                    },
                    "account-plugin-twitter": {
                        "description": "GNOME Control Center account plugin for single signon - twitter",
                        "installed_size": "63",
                        "name": "account-plugin-twitter",
                        "url": "https://launchpad.net/account-plugins",
                        "version": "0.11+14.04.20140409.1-0ubuntu1"
                    }
                }
            }';
    }

    /**
     * Teste de inserção das coletas
     */
    public function testColeta() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Messaging account plugin for Jabber/XMPP");
        $this->assertNotEmpty($software);

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Software que não existe");
        $this->assertEmpty($software);

        // Testa se identificou que não é notebook
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Windows_NT'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:89',
            'idSo' => $so->getIdSo()
        ));
        $this->assertNotEmpty($computador);

        $notebook = $computador->getIsNotebook();
        $this->assertEmpty($notebook);

    }

    /**
     * Teste de coletas e identificação de notebook
     */

    public function testColetaNotebook() {
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_notebook
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Messaging account plugin for Jabber/XMPP");
        $this->assertNotEmpty($software);

        $software = $em->getRepository("CacicCommonBundle:Software")->getByName("Software que não existe");
        $this->assertEmpty($software);

        // Testa se identificou que não é notebook
        $so = $em->getRepository("CacicCommonBundle:So")->findOneBy(array(
            'teSo' => 'Windows_NT'
        ));
        $this->assertNotEmpty($so);

        $computador = $em->getRepository("CacicCommonBundle:Computador")->findOneBy(array(
            'teNodeAddress' => '9C:D2:1E:EA:E0:88',
            'idSo' => $so->getIdSo()
        ));
        $this->assertNotEmpty($computador);

        $notebook = $computador->getIsNotebook();
        $this->assertEquals(true, $notebook);

    }

    /**
     * Testa inserção de nova coleta com os mesmos softwares
     */
    public function testSoftwareDuplicado() {
        // 1 - Primeiro computador
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // 2 - Segundo Computador
        $logger = $this->container->get('logger');
        $this->client->request(
            'POST',
            '/ws/neo/coleta',
            array(),
            array(),
            array(
                'CONTENT_TYPE'  => 'application/json',
                //'HTTPS'         => true
            ),
            $this->coleta_notebook
        );
        $logger->debug("Dados JSON do computador enviados para a coleta: \n".$this->client->getRequest()->getcontent());

        $response = $this->client->getResponse();
        $status = $response->getStatusCode();
        $logger->debug("Response status: $status");
        //$logger->debug("JSON da coleta: \n".$response->getContent());

        $this->assertEquals($status, 200);

        // Verifica se o Software Coleta foi inserido
        $em =$this->container->get('doctrine')->getManager();

        // Verifica que dois computadores foram coletas
        $computadores = $em->getRepository("CacicCommonBundle:Computador")->findAll();
        $this->assertEquals(2, sizeof($computadores));

        // Checa se o notebook foi identificado
        $result = false;
        foreach ($computadores as $elm) {
            if ($elm->getIsNotebook() == true) {
                $result = true;
            }
        }
        $this->assertEquals(true, $result);

        // Busca software pelo nome
        $software = $em->getRepository("CacicCommonBundle:Software")->findBy(array(
            'nmSoftware' => 'Messaging account plugin for AIM'
        ));

        $this->assertEquals(1, sizeof($software));

    }

    public function tearDown() {

        // Remove dados da classe pai
        parent::tearDown();
    }
}