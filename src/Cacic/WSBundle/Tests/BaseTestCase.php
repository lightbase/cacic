<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 20/04/15
 * Time: 12:51
 */

namespace Cacic\WSBundle\Tests;
use Cacic\CommonBundle\Tests\BaseTestCase as DefaultTestCase;


class BaseTestCase extends DefaultTestCase {

    public function setUp() {
        // Load setup from BaseTestCase method
        parent::setUp();

        // Load specific fixtures
        $fixtures = array_merge(
            $this->classes,
            array(
                'Cacic\WSBundle\DataFixtures\ORM\LoadRedeVersaoModuloData',
                'Cacic\WSBundle\DataFixtures\ORM\LoadTipoSo'
            )
        );
        $this->loadFixtures($fixtures);

        // Basic data
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->apiKey = $this->container->getParameter('test_api_key');
        $this->computador = '{
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
                }
            }';
        $this->sem_mac = '{
                "computador": {
                    "networkDevices": [
                        {
                            "ipv4": "10.1.0.56",
                            "ipv6": "fe80::295b:a8db:d433:ebe%4",
                            "netmask_ipv4": "255.255.255.0",
                            "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                            "nome": "Wi-Fi"
                        },
                        {
                            "ipv4": "192.168.56.1",
                            "ipv6": "fe80::19f2:4739:8a9e:45e4%16",
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
                }
            }';

        $this->computador_http = '{
                "computador": {
                    "networkDevices": [
                        {
                            "ipv4": "0.0.0.1",
                            "ipv6": "fe80::295b:a8db:d433:ebe%4",
                            "mac": "9C:D2:1E:EA:E0:89",
                            "netmask_ipv4": "255.255.255.255",
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
                }
            }';

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
                    "Win32_BIOS": {
                        "releaseDate": "11/12/2013",
                        "romSize": "4096 kB",
                        "runtimeSize": "128 kB",
                        "vendor": "Dell Inc.",
                        "version": "A07"
                    },
                    "Win32_Processor": {
                        "clock": "768000000 Hz",
                        "name": "Intel(R) Core(TM) i7-4500U CPU @ 1.80GHz",
                        "vendor": "Intel Corp."
                    },
                    "NetworkAdapterConfiguration": {
                        "capacity": "100000000 bits/s",
                        "description": "Ethernet interface",
                        "logicalname": "eth0",
                        "product": "RTL8101E/RTL8102E PCI Express Fast Ethernet controller",
                        "serial": "78:2b:cb:eb:36:24",
                        "vendor": "Realtek Semiconductor Co., Ltd."
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
                    "Win32_BIOS": {
                        "releaseDate": "11/12/2013",
                        "romSize": "4096 kB",
                        "runtimeSize": "128 kB",
                        "vendor": "Dell Inc.",
                        "version": "A07"
                    },
                    "Win32_Processor": {
                        "clock": "768000000 Hz",
                        "name": "Intel(R) Core(TM) i7-4500U CPU @ 1.80GHz",
                        "vendor": "Intel Corp."
                    },
                    "NetworkAdapterConfiguration": {
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

        $this->computador_erro = '{
                    "computador": {
                    "networkDevices": [
                    {
                        "ipv4": "10.0.2.15",
                        "ipv6": "fe80::a00:27ff:fe11:caec%eth0",
                        "mac": "08:00:27:11:CA:EC",
                        "netmask_ipv4": "255.255.255.0",
                        "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                        "nome": "eth0"
                    },
                    {
                        "ipv4": "192.168.56.102",
                        "ipv6": "fe80::a00:27ff:fe7d:b57e%eth1",
                        "mac": "08:00:27:7D:B5:7E",
                        "netmask_ipv4": "255.255.255.0",
                        "netmask_ipv6": "ffff:ffff:ffff:ffff::",
                        "nome": "eth1"
                    }
                    ],
                        "nmComputador": "virtualbox-ubuntu",
                        "operatingSystem": {
                        "idOs": 2,
                        "nomeOs": "Ubuntu 14.04.1 LTS-x86_64",
                        "tipo": "linux-x86_64",
                        "upTime": 2125
                    },
                        "usuario": "virtualbox",
                        "versaoAgente": "3.1.9",
                        "versaoGercols": "3.1.9"
                    },
                        "logInfo": [],
                        "logError": [
                        {
                            "timestamp": "25-03-2015 12:20:54.094",
                            "message": "[Error] {Cacic Daemon (Timer)} Erro no login: Host  not found"
                        },
                        {
                            "timestamp": "25-03-2015 12:20:54.095",
                            "message": "[Error] {Cacic Daemon (Timer)} Problemas ao comunicar com gerente."
                        }
                    ]
                }';
    }

    /**
     * Método que apaga todos os dados criados no teste
     */
    public function tearDown() {
        // Executa método de limpeza de todos os casos de teste
        parent::tearDown();

    }

}