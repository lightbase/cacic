--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- Data for Name: rede; Type: TABLE DATA; Schema: public; Owner: eduardo
--

INSERT INTO rede (id_rede, id_local, id_servidor_autenticacao, te_ip_rede, nm_rede, te_observacao, nm_pessoa_contato1, nm_pessoa_contato2, nu_telefone1, te_email_contato2, nu_telefone2, te_email_contato1, te_serv_cacic, te_serv_updates, te_path_serv_updates, nm_usuario_login_serv_updates, te_senha_login_serv_updates, nu_porta_serv_updates, te_mascara_rede, dt_verifica_updates, nm_usuario_login_serv_updates_gerente, te_senha_login_serv_updates_gerente, nu_limite_ftp, cs_permitir_desativar_srcacic, te_debugging, dt_debug) VALUES (1, 1, NULL, '192.168.56.0', 'Rede Local', NULL, NULL, NULL, '', NULL, '', NULL, '192.168.56.1/cacic', '192.168.56.1', 'agentes', 'ftpcacic', 'cacicftp', '21', '255.255.255.0', NULL, 'ftpcacic', 'cacicftp', 100, 'N', NULL, NULL);


--
-- Data for Name: so; Type: TABLE DATA; Schema: public; Owner: eduardo
--

INSERT INTO so (id_so, te_desc_so, sg_so, te_so, in_mswindows) VALUES (1, 'Windows XP SP3', 'WinXP-SP3', '2.5.1.1.256.32', 'S');


--
-- Data for Name: computador; Type: TABLE DATA; Schema: public; Owner: eduardo
--

INSERT INTO computador (id_computador, id_usuario_exclusao, id_so, id_rede, nm_computador, te_node_address, te_ip_computador, dt_hr_inclusao, dt_hr_exclusao, dt_hr_ult_acesso, te_versao_cacic, te_versao_gercols, te_palavra_chave, dt_hr_coleta_forcada_estacao, te_nomes_curtos_modulos, id_conta, te_debugging, te_ultimo_login, dt_debug) VALUES (1, NULL, 1, 1, 'CACIC-2CEAC447', '08:00:27:1E:11:EE', '192.168.56.101', '2014-01-24 14:52:44', NULL, '2014-01-24 14:54:17', '2.8.1.', '2.8.1.', 'rBdxfI63M3zFF2+JYiWGew==__CRYPTED__', NULL, NULL, NULL, NULL, 'CACIC-2CEAC447\\cacic', NULL);
INSERT INTO computador (id_computador, id_usuario_exclusao, id_so, id_rede, nm_computador, te_node_address, te_ip_computador, dt_hr_inclusao, dt_hr_exclusao, dt_hr_ult_acesso, te_versao_cacic, te_versao_gercols, te_palavra_chave, dt_hr_coleta_forcada_estacao, te_nomes_curtos_modulos, id_conta, te_debugging, te_ultimo_login, dt_debug) VALUES (2, NULL, 1, 1, 'CAICIC-2CEAC447', '08:00:27:A1:4E:59', '192.168.56.102', '2014-01-24 14:52:54', NULL, '2014-01-24 14:54:37', '2.8.1.', '2.8.1.', 'irmEXAW3nKTVAZqaW1iVghbOEIGZNz8cn0ISfH+jiHw=__CRYPTED__', NULL, NULL, NULL, NULL, 'CAICIC-2CEAC447\\cacic', NULL);


--
-- Name: computador_id_computador_seq; Type: SEQUENCE SET; Schema: public; Owner: eduardo
--

SELECT pg_catalog.setval('computador_id_computador_seq', 2, true);


--
-- Name: rede_id_rede_seq; Type: SEQUENCE SET; Schema: public; Owner: eduardo
--

SELECT pg_catalog.setval('rede_id_rede_seq', 1, true);


--
-- Name: so_id_so_seq; Type: SEQUENCE SET; Schema: public; Owner: eduardo
--

SELECT pg_catalog.setval('so_id_so_seq', 1, true);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- Data for Name: classe; Type: TABLE DATA; Schema: public; Owner: eduardo
--

INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (1, 'Win32_ComputerSystem', 'The Win32_ComputerSystem WMI class represents a computer system running Windows.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (2, 'OperatingSystem', 'The Win32_OperatingSystem WMI class represents a Windows-based operating system installed on a computer. Any operating system that can be installed on a computer that can run a Windows-based operating system is a descendent or member of this class. Win32_OperatingSystem is a singleton class. To get the single instance, use "@" for the key.
Windows Server 2003 and Windows XP:  If a computer has multiple operating systems installed, this class only returns an instance for the currently active operating system.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties. Properties and methods are in alphabetic order, not MOF order.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (3, 'NetworkAdapterConfiguration', 'The Win32_NetworkAdapterConfiguration WMI class represents the attributes and behaviors of a network adapter. This class includes extra properties and methods that support the management of the TCP/IP and Internetwork Packet Exchange (IPX) protocols that are independent from the network adapter.
The following syntax is simplified from Managed Object Format (MOF) code and includes all of the inherited properties. Properties are listed in alphabetic order, not MOF order.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (4, 'SoftwareList', 'Computer softwares');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (5, 'Win32_Keyboard', 'Represents a keyboard installed on a computer system running Windows.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (6, 'Win32_PointingDevice', 'Represents an input device used to point to and select regions on the display of a computer system running Windows.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (7, 'Win32_PhysicalMedia', 'Represents any type of documentation or storage medium.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (8, 'Win32_BaseBoard', 'Represents a baseboard (also known as a motherboard or system board).');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (9, 'Win32_BIOS', 'Represents the attributes of the computer system''s basic input or output services (BIOS) that are installed on the computer.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (10, 'Win32_MemoryDevice', 'Represents the properties of a computer system''s memory device along with its associated mapped addresses.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (11, 'Win32_PhysicalMemory', 'Represents a physical memory device located on a computer as available to the operating system.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (12, 'Win32_Processor', 'Represents a device capable of interpreting a sequence of machine instructions on a computer system running Windows.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (13, 'Win32_Printer', 'Represents a device connected to a computer system running Windows that is capable of reproducing a visual image on a medium.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (14, 'Win32_DesktopMonitor', 'Represents the type of monitor or display device attached to the computer system.');
INSERT INTO classe (id_class, nm_class_name, te_class_description) VALUES (15, 'Patrimonio', 'Dados de patrimônio e localização física');


--
-- Data for Name: class_property; Type: TABLE DATA; Schema: public; Owner: eduardo
--

INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (1, 1, 'Caption', 'Short description of the object—a one-line string. This property is inherited from CIM_ManagedSystemElement.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (2, 1, 'Domain', 'Name of the domain to which a computer belongs.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (3, 1, 'TotalPhysicalMemory', 'Total size of physical memory. Be aware that, under some circumstances, this property may not return an accurate value for the physical memory. For example, it is not accurate if the BIOS is using some of the physical memory. For an accurate value, use the Capacity property in Win32_PhysicalMemory instead.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (4, 1, 'UserName', 'Name of a user that is logged on currently. This property must have a value. In a terminal services session, UserName returns the name of the user that is logged on to the console—not the user logged on during the terminal service session.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (5, 3, 'DefaultIPGateway', 'Array of IP addresses of default gateways that the computer system uses.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (6, 3, 'Description', 'Description of the CIM_Setting object. This property is inherited from CIM_Setting.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (7, 3, 'DHCPServer', 'IP address of the dynamic host configuration protocol (DHCP) server.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (8, 3, 'DNSDomain', 'Organization name followed by a period and an extension that indicates the type of organization, such as microsoft.com. The name can be any combination of the letters A through Z, the numerals 0 through 9, and the hyphen (-), plus the period (.) character used as a separator.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (9, 3, 'DNSHostName', 'Host name used to identify the local computer for authentication by some utilities. Other TCP/IP-based utilities can use this value to acquire the name of the local computer. Host names are stored on DNS servers in a table that maps names to IP addresses for use by DNS. The name can be any combination of the letters A through Z, the numerals 0 through 9, and the hyphen (-), plus the period (.) character used as a separator. By default, this value is the Microsoft networking computer name, but the network administrator can assign another host name without affecting the computer name.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (10, 3, 'DNSServerSearchOrder', 'Array of server IP addresses to be used in querying for DNS servers.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (11, 3, 'IPAddress', 'Array of all of the IP addresses associated with the current network adapter. Starting with Windows Vista, this property can contain either IPv6 addresses or IPv4 addresses. For more information, see IPv6 and IPv4 Support in WMI.
Example IPv6 address: "2010:836B:4179::836B:4179"', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (12, 3, 'IPSubnet', 'Array of all of the subnet masks associated with the current network adapter.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (13, 3, 'MACAddress', 'Media Access Control (MAC) address of the network adapter. A MAC address is assigned by the manufacturer to uniquely identify the network adapter.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (14, 3, 'WINSPrimaryServer', 'IP address for the primary WINS server.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (15, 3, 'WINSSecondaryServer', 'IP address for the secondary WINS server.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (16, 2, 'Caption', 'Short description of the object—a one-line string. The string includes the operating system version. For example, "Microsoft Windows XP Professional Version = 5.1.2500". This property can be localized.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (17, 2, 'CSDVersion', 'NULL-terminated string that indicates the latest service pack installed on a computer. If no service pack is installed, the string is NULL.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (18, 2, 'InstallDate', 'Date object was installed. This property does not require a value to indicate that the object is installed.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (19, 2, 'LastBootUpTime', 'Date and time the operating system was last restarted.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (20, 2, 'NumberOfLicensedUsers', 'Number of user licenses for the operating system. If unlimited, enter 0 (zero). If unknown, enter -1.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (21, 2, 'OSArchitecture', 'Architecture of the operating system, as opposed to the processor. This property can be localized.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (22, 2, 'OSLanguage', 'Language version of the operating system installed.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (23, 2, 'ProductType', 'Additional system information.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (24, 2, 'SerialNumber', 'Operating system product serial identification number.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (25, 2, 'Version', 'Version number of the operating system.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (26, 4, 'IDSoftware', 'Identificador do software no registro.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (27, 4, 'DisplayName', 'Nome do software.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (28, 4, 'DisplayVersion', 'Versão identificada.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (29, 4, 'URLInfoAbout', 'URL do software.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (30, 4, 'Publisher', 'Nome do fabricante.', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (31, 5, 'Caption', 'Nome do teclado', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (32, 6, 'Caption', 'Nome', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (33, 7, 'Caption', 'Nome', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (34, 8, 'Caption', 'Nome', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (35, 9, 'Caption', 'Nome', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (36, 10, 'Caption', 'Nome', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (37, 11, 'Caption', 'Nome', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (38, 12, 'Caption', 'Nome', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (39, 13, 'Caption', 'Nome', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (40, 14, 'Caption', 'Nome', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (41, 15, 'IDPatrimonio', 'Número do patrimônio', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (42, 4, 'AddressBook', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (43, 4, 'Branding', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (44, 4, 'Connection Manager', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (45, 4, 'DirectAnimation', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (46, 4, 'DirectDrawEx', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (47, 4, 'DXM_Runtime', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (48, 4, 'EMCO MSI Package Builder Enterprise_is1', 'EMCO MSI Package Builder Enterprise', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (49, 4, 'EMCO MSI Package Builder Starter_is1', 'EMCO MSI Package Builder Starter', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (50, 4, 'Fontcore', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (51, 4, 'Gtk+ Development Environment for Windows', 'Gtk+ Development Environment for Windows 2.12.9-2', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (52, 4, 'ICW', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (53, 4, 'IE40', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (54, 4, 'IE4Data', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (55, 4, 'IE5BAKEX', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (56, 4, 'IEData', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (57, 4, 'Microsoft .NET Framework 2.0', 'Microsoft .NET Framework 2.0', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (58, 4, 'Microsoft .NET Framework 2.0 SDK - ENU', 'Microsoft .NET Framework 2.0 SDK - ENU', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (59, 4, 'Microsoft Visual J# 2.0 Redistributable Package', 'Microsoft Visual J# 2.0 Redistributable Package', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (60, 4, 'MobileOptionPack', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (61, 4, 'MPlayer2', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (62, 4, 'NetMeeting', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (63, 4, 'Oracle VM VirtualBox Guest Additions', 'Oracle VM VirtualBox Guest Additions 4.2.6', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (64, 4, 'OutlookExpress', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (65, 4, 'PCHealth', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (66, 4, 'RAD Studio', 'RAD Studio', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (67, 4, 'SchedulingAgent', '', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (68, 4, 'TortoiseCVS_is1', 'TortoiseCVS 1.12.5', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (69, 4, '{1D44F148-5A2A-42CB-83AA-DB2B156F1ED7}', 'WixEdit', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (70, 4, '{350C9416-3D7C-4EE8-BAA9-00BCB3D54227}', 'WebFldrs XP', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (71, 4, '{5A37B181-B8D0-48C3-B4A4-5DC1ED104CED}', 'VC9RunTime', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (72, 4, '{639159C2-B27B-4208-8965-D8A0AEDBDED2}', 'Microsoft .NET Framework 2.0 SDK - ENU', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (73, 4, '{68A35043-C55A-4237-88C9-37EE1C63ED71}', 'Microsoft Visual J# 2.0 Redistributable Package', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (172, 9, 'Version', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (74, 4, '{710D9F65-B7F0-416C-9022-8C9098521270}', '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (75, 4, '{7131646D-CD3C-40F4-97B9-CD9E4E6262EF}', 'Microsoft .NET Framework 2.0', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (76, 4, '{72263053-50D1-4598-9502-51ED64E54C51}', 'Borland Delphi 7', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (77, 4, '{76F9F5C5-FF87-4ED8-B63C-2A25A299C4AA}', 'CVSNT 2.5.05.3744', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (78, 4, '{84ADC96C-B7E0-4938-9D6E-2B640D5DA224}', 'Python 2.7.4', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (79, 4, '{91227C10-CE03-4A31-BD0E-4986AB490716}', '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (80, 4, '{99758AC8-E271-4B1F-8092-865CD59A0D44}', '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (81, 4, '{9B09BEF2-10C4-4011-85F0-D9E5D18D641E}', 'InstalaÃ§Ã£o Cacic2.6Beta2 IPEA', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (82, 4, '{9B74BFA1-CA98-444D-853F-5C28593799CB}', '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (83, 4, '{B6CF2967-C81E-40C0-9815-C05774FEF120}', 'Skype Click to Call', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (84, 4, '{B7031148-C6E7-40F6-A978-EED2E77E7D1B}', 'RAD Studio', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (85, 4, '{C15A8A76-9077-4DD1-95E5-4807A5B7D2AC}', '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (86, 4, '{C9DCF4E9-A41B-40E7-B028-2255E36D2A1C}', 'TortoiseOverlays', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (87, 4, '{CF1215D3-8784-4908-9732-970A81248C7A}', '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (88, 4, '{EE7257A2-39A2-4D2F-9DAC-F9F25B8AE1D8}', 'Skypeâ„¢ 5.9', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (89, 5, 'Availability', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (90, 5, 'Description', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (91, 5, 'InstallDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (92, 5, 'Name', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (93, 6, 'Availability', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (94, 6, 'Description', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (95, 6, 'InstallDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (96, 6, 'Manufacturer', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (97, 6, 'Name', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (98, 7, 'Capacity', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (99, 7, 'Description', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (100, 7, 'InstallDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (101, 7, 'Manufacturer', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (102, 7, 'MediaDescription', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (103, 7, 'MediaType', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (104, 7, 'Model', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (105, 7, 'Name', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (106, 7, 'OtherIdentifyingInfo', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (107, 7, 'PartNumber', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (108, 7, 'SerialNumber', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (109, 7, 'SKU', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (110, 7, 'Tag', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (111, 7, 'Version', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (112, 9, 'BiosCharacteristics', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (113, 9, 'BIOSVersion', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (114, 9, 'BuildNumber', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (115, 9, 'CodeSet', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (116, 9, 'Description', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (117, 9, 'IdentificationCode', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (118, 9, 'InstallDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (119, 9, 'Manufacturer', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (120, 9, 'Name', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (121, 9, 'OtherTargetOS', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (122, 9, 'PrimaryBIOS', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (123, 9, 'ReleaseDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (124, 9, 'SerialNumber', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (125, 9, 'SMBIOSBIOSVersion', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (126, 9, 'SMBIOSMajorVersion', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (127, 9, 'SMBIOSMinorVersion', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (128, 9, 'SoftwareElementID', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (129, 9, 'TargetOperatingSystem', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (130, 9, 'Version', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (131, 5, 'Availability', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (132, 5, 'Description', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (133, 5, 'InstallDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (134, 5, 'Name', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (135, 6, 'Availability', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (136, 6, 'Description', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (137, 6, 'InstallDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (138, 6, 'Manufacturer', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (139, 6, 'Name', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (140, 7, 'Capacity', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (141, 7, 'Description', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (142, 7, 'InstallDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (143, 7, 'Manufacturer', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (144, 7, 'MediaDescription', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (145, 7, 'MediaType', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (146, 7, 'Model', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (147, 7, 'Name', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (148, 7, 'OtherIdentifyingInfo', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (149, 7, 'PartNumber', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (150, 7, 'SerialNumber', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (151, 7, 'SKU', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (152, 7, 'Tag', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (153, 7, 'Version', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (154, 9, 'BiosCharacteristics', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (155, 9, 'BIOSVersion', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (156, 9, 'BuildNumber', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (157, 9, 'CodeSet', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (158, 9, 'Description', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (159, 9, 'IdentificationCode', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (160, 9, 'InstallDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (161, 9, 'Manufacturer', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (162, 9, 'Name', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (163, 9, 'OtherTargetOS', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (164, 9, 'PrimaryBIOS', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (165, 9, 'ReleaseDate', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (166, 9, 'SerialNumber', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (167, 9, 'SMBIOSBIOSVersion', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (168, 9, 'SMBIOSMajorVersion', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (169, 9, 'SMBIOSMinorVersion', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (170, 9, 'SoftwareElementID', 'On the fly created Property', NULL, NULL);
INSERT INTO class_property (id_class_property, id_class, nm_property_name, te_property_description, nm_function_pre_db, nm_function_pos_db) VALUES (171, 9, 'TargetOperatingSystem', 'On the fly created Property', NULL, NULL);


--
-- Name: class_property_id_class_property_seq; Type: SEQUENCE SET; Schema: public; Owner: eduardo
--

SELECT pg_catalog.setval('class_property_id_class_property_seq', 172, true);


--
-- Name: classe_id_class_seq; Type: SEQUENCE SET; Schema: public; Owner: eduardo
--

SELECT pg_catalog.setval('classe_id_class_seq', 15, true);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- Data for Name: computador_coleta; Type: TABLE DATA; Schema: public; Owner: eduardo
--

INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (1, 1, 42, NULL, 'AddressBook');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (2, 1, 43, NULL, 'Branding');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (3, 1, 44, NULL, 'Connection Manager');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (4, 1, 45, NULL, 'DirectAnimation');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (5, 1, 46, NULL, 'DirectDrawEx');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (6, 1, 47, NULL, 'DXM_Runtime');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (7, 1, 48, NULL, 'EMCO MSI Package Builder Enterprise_is1');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (8, 1, 49, NULL, 'EMCO MSI Package Builder Starter_is1');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (9, 1, 50, NULL, 'Fontcore');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (10, 1, 51, NULL, 'Gtk+ Development Environment for Windows');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (11, 1, 52, NULL, 'ICW');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (12, 1, 53, NULL, 'IE40');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (13, 1, 54, NULL, 'IE4Data');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (14, 1, 55, NULL, 'IE5BAKEX');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (15, 1, 56, NULL, 'IEData');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (16, 1, 57, NULL, 'Microsoft .NET Framework 2.0');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (17, 1, 58, NULL, 'Microsoft .NET Framework 2.0 SDK - ENU');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (18, 1, 59, NULL, 'Microsoft Visual J# 2.0 Redistributable Package');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (19, 1, 60, NULL, 'MobileOptionPack');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (20, 1, 61, NULL, 'MPlayer2');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (21, 1, 62, NULL, 'NetMeeting');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (22, 1, 63, NULL, 'Oracle VM VirtualBox Guest Additions');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (23, 1, 64, NULL, 'OutlookExpress');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (24, 1, 65, NULL, 'PCHealth');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (25, 1, 66, NULL, 'RAD Studio');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (26, 1, 67, NULL, 'SchedulingAgent');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (27, 1, 68, NULL, 'TortoiseCVS_is1');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (28, 1, 69, NULL, '{1D44F148-5A2A-42CB-83AA-DB2B156F1ED7}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (29, 1, 70, NULL, '{350C9416-3D7C-4EE8-BAA9-00BCB3D54227}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (30, 1, 71, NULL, '{5A37B181-B8D0-48C3-B4A4-5DC1ED104CED}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (31, 1, 72, NULL, '{639159C2-B27B-4208-8965-D8A0AEDBDED2}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (32, 1, 73, NULL, '{68A35043-C55A-4237-88C9-37EE1C63ED71}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (33, 1, 74, NULL, '{710D9F65-B7F0-416C-9022-8C9098521270}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (34, 1, 75, NULL, '{7131646D-CD3C-40F4-97B9-CD9E4E6262EF}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (35, 1, 76, NULL, '{72263053-50D1-4598-9502-51ED64E54C51}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (36, 1, 77, NULL, '{76F9F5C5-FF87-4ED8-B63C-2A25A299C4AA}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (37, 1, 78, NULL, '{84ADC96C-B7E0-4938-9D6E-2B640D5DA224}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (38, 1, 79, NULL, '{91227C10-CE03-4A31-BD0E-4986AB490716}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (39, 1, 80, NULL, '{99758AC8-E271-4B1F-8092-865CD59A0D44}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (40, 1, 81, NULL, '{9B09BEF2-10C4-4011-85F0-D9E5D18D641E}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (41, 1, 82, NULL, '{9B74BFA1-CA98-444D-853F-5C28593799CB}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (42, 1, 83, NULL, '{B6CF2967-C81E-40C0-9815-C05774FEF120}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (43, 1, 84, NULL, '{B7031148-C6E7-40F6-A978-EED2E77E7D1B}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (44, 1, 85, NULL, '{C15A8A76-9077-4DD1-95E5-4807A5B7D2AC}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (45, 1, 86, NULL, '{C9DCF4E9-A41B-40E7-B028-2255E36D2A1C}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (46, 1, 87, NULL, '{CF1215D3-8784-4908-9732-970A81248C7A}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (47, 1, 88, NULL, '{EE7257A2-39A2-4D2F-9DAC-F9F25B8AE1D8}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (48, 1, 31, NULL, 'Aperfeiçoado (101 ou 102 teclas)');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (49, 1, 90, NULL, 'Teclado padrão com 101/102 teclas ou Microsoft Natural PS/2 Keyboard');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (50, 1, 92, NULL, 'Aperfeiçoado (101 ou 102 teclas)');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (51, 1, 93, NULL, '[[REG]]');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (52, 1, 32, NULL, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (53, 1, 94, NULL, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (54, 1, 95, NULL, '[[REG]]');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (55, 2, 42, NULL, 'AddressBook');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (56, 1, 96, NULL, 'Microsoft[[REG]](Dispositivos de sistema padrão)');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (57, 2, 43, NULL, 'Branding');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (58, 1, 97, NULL, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (59, 2, 44, NULL, 'Connection Manager');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (60, 2, 45, NULL, 'DirectAnimation');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (61, 2, 46, NULL, 'DirectDrawEx');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (62, 2, 47, NULL, 'DXM_Runtime');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (63, 2, 48, NULL, 'EMCO MSI Package Builder Enterprise_is1');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (64, 2, 49, NULL, 'EMCO MSI Package Builder Starter_is1');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (65, 1, 108, NULL, '42563862383439663738312d6335626462652062');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (66, 2, 50, NULL, 'Fontcore');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (67, 1, 110, NULL, '....PHYSICALDRIVE0');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (68, 2, 51, NULL, 'Gtk+ Development Environment for Windows');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (69, 1, 112, NULL, '4');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (70, 2, 52, NULL, 'ICW');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (71, 1, 113, NULL, 'VBOX   - 1');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (72, 2, 53, NULL, 'IE40');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (73, 1, 35, NULL, 'Default System BIOS');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (74, 2, 54, NULL, 'IE4Data');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (75, 1, 116, NULL, 'Default System BIOS');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (76, 2, 55, NULL, 'IE5BAKEX');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (77, 2, 56, NULL, 'IEData');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (78, 1, 119, NULL, 'innotek GmbH');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (79, 1, 120, NULL, 'Default System BIOS');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (80, 2, 57, NULL, 'Microsoft .NET Framework 2.0');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (81, 1, 122, NULL, 'True');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (82, 2, 58, NULL, 'Microsoft .NET Framework 2.0 SDK - ENU');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (83, 1, 123, NULL, '20061201000000.000000+000');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (84, 2, 59, NULL, 'Microsoft Visual J# 2.0 Redistributable Package');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (85, 1, 124, NULL, '0');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (86, 2, 60, NULL, 'MobileOptionPack');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (87, 1, 125, NULL, 'VirtualBox');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (88, 1, 126, NULL, '2');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (89, 2, 61, NULL, 'MPlayer2');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (90, 1, 127, NULL, '5');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (91, 2, 62, NULL, 'NetMeeting');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (92, 1, 128, NULL, 'Default System BIOS');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (93, 2, 63, NULL, 'Oracle VM VirtualBox Guest Additions');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (94, 1, 129, NULL, '0');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (95, 1, 130, NULL, 'VBOX   - 1');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (96, 2, 64, NULL, 'OutlookExpress');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (97, 2, 65, NULL, 'PCHealth');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (98, 2, 66, NULL, 'RAD Studio');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (99, 2, 67, NULL, 'SchedulingAgent');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (100, 2, 68, NULL, 'TortoiseCVS_is1');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (101, 2, 69, NULL, '{1D44F148-5A2A-42CB-83AA-DB2B156F1ED7}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (102, 2, 70, NULL, '{350C9416-3D7C-4EE8-BAA9-00BCB3D54227}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (103, 2, 71, NULL, '{5A37B181-B8D0-48C3-B4A4-5DC1ED104CED}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (104, 2, 72, NULL, '{639159C2-B27B-4208-8965-D8A0AEDBDED2}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (105, 2, 73, NULL, '{68A35043-C55A-4237-88C9-37EE1C63ED71}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (106, 2, 74, NULL, '{710D9F65-B7F0-416C-9022-8C9098521270}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (107, 2, 75, NULL, '{7131646D-CD3C-40F4-97B9-CD9E4E6262EF}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (108, 2, 76, NULL, '{72263053-50D1-4598-9502-51ED64E54C51}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (109, 2, 77, NULL, '{76F9F5C5-FF87-4ED8-B63C-2A25A299C4AA}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (110, 2, 78, NULL, '{84ADC96C-B7E0-4938-9D6E-2B640D5DA224}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (111, 2, 79, NULL, '{91227C10-CE03-4A31-BD0E-4986AB490716}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (112, 2, 80, NULL, '{99758AC8-E271-4B1F-8092-865CD59A0D44}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (113, 2, 81, NULL, '{9B09BEF2-10C4-4011-85F0-D9E5D18D641E}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (114, 2, 82, NULL, '{9B74BFA1-CA98-444D-853F-5C28593799CB}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (115, 2, 83, NULL, '{B6CF2967-C81E-40C0-9815-C05774FEF120}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (116, 2, 84, NULL, '{B7031148-C6E7-40F6-A978-EED2E77E7D1B}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (117, 2, 85, NULL, '{C15A8A76-9077-4DD1-95E5-4807A5B7D2AC}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (118, 2, 86, NULL, '{C9DCF4E9-A41B-40E7-B028-2255E36D2A1C}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (119, 2, 87, NULL, '{CF1215D3-8784-4908-9732-970A81248C7A}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (120, 2, 88, NULL, '{EE7257A2-39A2-4D2F-9DAC-F9F25B8AE1D8}');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (121, 2, 31, NULL, 'Aperfeiçoado (101 ou 102 teclas)');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (122, 2, 132, NULL, 'Teclado padrão com 101/102 teclas ou Microsoft Natural PS/2 Keyboard');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (123, 2, 134, NULL, 'Aperfeiçoado (101 ou 102 teclas)');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (124, 2, 135, NULL, '[[REG]]');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (125, 2, 32, NULL, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (126, 2, 136, NULL, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (127, 2, 137, NULL, '[[REG]]');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (128, 2, 138, NULL, 'Microsoft[[REG]](Dispositivos de sistema padrão)');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (129, 2, 139, NULL, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (130, 2, 150, NULL, '42566431346538656337382d6161626330632039');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (131, 2, 152, NULL, '....PHYSICALDRIVE0');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (132, 2, 154, NULL, '4');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (133, 2, 155, NULL, 'VBOX   - 1');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (134, 2, 35, NULL, 'Default System BIOS');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (135, 2, 158, NULL, 'Default System BIOS');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (136, 2, 161, NULL, 'innotek GmbH');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (137, 2, 162, NULL, 'Default System BIOS');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (138, 2, 164, NULL, 'True');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (139, 2, 165, NULL, '20061201000000.000000+000');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (140, 2, 166, NULL, '0');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (141, 2, 167, NULL, 'VirtualBox');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (142, 2, 168, NULL, '2');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (143, 2, 169, NULL, '5');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (144, 2, 170, NULL, 'Default System BIOS');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (145, 2, 171, NULL, '0');
INSERT INTO computador_coleta (id_computador_coleta, id_computador, id_class_property, id_class, te_class_property_value) VALUES (146, 2, 172, NULL, 'VBOX   - 1');


--
-- Data for Name: computador_coleta_historico; Type: TABLE DATA; Schema: public; Owner: eduardo
--

INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (1, 1, 1, 42, 'AddressBook', '2014-01-24 14:54:18');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (2, 2, 1, 43, 'Branding', '2014-01-24 14:54:18');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (3, 3, 1, 44, 'Connection Manager', '2014-01-24 14:54:18');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (4, 4, 1, 45, 'DirectAnimation', '2014-01-24 14:54:18');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (5, 5, 1, 46, 'DirectDrawEx', '2014-01-24 14:54:18');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (6, 6, 1, 47, 'DXM_Runtime', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (7, 7, 1, 48, 'EMCO MSI Package Builder Enterprise_is1', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (8, 8, 1, 49, 'EMCO MSI Package Builder Starter_is1', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (9, 9, 1, 50, 'Fontcore', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (10, 10, 1, 51, 'Gtk+ Development Environment for Windows', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (11, 11, 1, 52, 'ICW', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (12, 12, 1, 53, 'IE40', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (13, 13, 1, 54, 'IE4Data', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (14, 14, 1, 55, 'IE5BAKEX', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (15, 15, 1, 56, 'IEData', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (16, 16, 1, 57, 'Microsoft .NET Framework 2.0', '2014-01-24 14:54:19');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (17, 17, 1, 58, 'Microsoft .NET Framework 2.0 SDK - ENU', '2014-01-24 14:54:20');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (18, 18, 1, 59, 'Microsoft Visual J# 2.0 Redistributable Package', '2014-01-24 14:54:20');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (19, 19, 1, 60, 'MobileOptionPack', '2014-01-24 14:54:20');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (20, 20, 1, 61, 'MPlayer2', '2014-01-24 14:54:20');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (21, 21, 1, 62, 'NetMeeting', '2014-01-24 14:54:20');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (22, 22, 1, 63, 'Oracle VM VirtualBox Guest Additions', '2014-01-24 14:54:20');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (23, 23, 1, 64, 'OutlookExpress', '2014-01-24 14:54:20');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (24, 24, 1, 65, 'PCHealth', '2014-01-24 14:54:20');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (25, 25, 1, 66, 'RAD Studio', '2014-01-24 14:54:20');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (26, 26, 1, 67, 'SchedulingAgent', '2014-01-24 14:54:21');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (27, 27, 1, 68, 'TortoiseCVS_is1', '2014-01-24 14:54:21');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (28, 28, 1, 69, '{1D44F148-5A2A-42CB-83AA-DB2B156F1ED7}', '2014-01-24 14:54:21');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (29, 29, 1, 70, '{350C9416-3D7C-4EE8-BAA9-00BCB3D54227}', '2014-01-24 14:54:21');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (30, 30, 1, 71, '{5A37B181-B8D0-48C3-B4A4-5DC1ED104CED}', '2014-01-24 14:54:21');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (31, 31, 1, 72, '{639159C2-B27B-4208-8965-D8A0AEDBDED2}', '2014-01-24 14:54:21');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (32, 32, 1, 73, '{68A35043-C55A-4237-88C9-37EE1C63ED71}', '2014-01-24 14:54:21');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (33, 33, 1, 74, '{710D9F65-B7F0-416C-9022-8C9098521270}', '2014-01-24 14:54:21');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (34, 34, 1, 75, '{7131646D-CD3C-40F4-97B9-CD9E4E6262EF}', '2014-01-24 14:54:22');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (35, 35, 1, 76, '{72263053-50D1-4598-9502-51ED64E54C51}', '2014-01-24 14:54:22');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (36, 36, 1, 77, '{76F9F5C5-FF87-4ED8-B63C-2A25A299C4AA}', '2014-01-24 14:54:22');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (37, 37, 1, 78, '{84ADC96C-B7E0-4938-9D6E-2B640D5DA224}', '2014-01-24 14:54:22');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (38, 38, 1, 79, '{91227C10-CE03-4A31-BD0E-4986AB490716}', '2014-01-24 14:54:22');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (39, 39, 1, 80, '{99758AC8-E271-4B1F-8092-865CD59A0D44}', '2014-01-24 14:54:22');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (40, 40, 1, 81, '{9B09BEF2-10C4-4011-85F0-D9E5D18D641E}', '2014-01-24 14:54:22');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (41, 41, 1, 82, '{9B74BFA1-CA98-444D-853F-5C28593799CB}', '2014-01-24 14:54:23');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (42, 42, 1, 83, '{B6CF2967-C81E-40C0-9815-C05774FEF120}', '2014-01-24 14:54:23');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (43, 43, 1, 84, '{B7031148-C6E7-40F6-A978-EED2E77E7D1B}', '2014-01-24 14:54:23');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (44, 44, 1, 85, '{C15A8A76-9077-4DD1-95E5-4807A5B7D2AC}', '2014-01-24 14:54:23');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (45, 45, 1, 86, '{C9DCF4E9-A41B-40E7-B028-2255E36D2A1C}', '2014-01-24 14:54:23');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (46, 46, 1, 87, '{CF1215D3-8784-4908-9732-970A81248C7A}', '2014-01-24 14:54:23');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (47, 47, 1, 88, '{EE7257A2-39A2-4D2F-9DAC-F9F25B8AE1D8}', '2014-01-24 14:54:23');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (48, 48, 1, 31, 'Aperfeiçoado (101 ou 102 teclas)', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (49, 49, 1, 90, 'Teclado padrão com 101/102 teclas ou Microsoft Natural PS/2 Keyboard', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (50, 50, 1, 92, 'Aperfeiçoado (101 ou 102 teclas)', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (51, 51, 1, 93, '[[REG]]', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (52, 52, 1, 32, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (53, 53, 1, 94, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (54, 54, 1, 95, '[[REG]]', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (55, 55, 2, 42, 'AddressBook', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (56, 56, 1, 96, 'Microsoft[[REG]](Dispositivos de sistema padrão)', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (57, 57, 2, 43, 'Branding', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (58, 58, 1, 97, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (59, 59, 2, 44, 'Connection Manager', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (60, 60, 2, 45, 'DirectAnimation', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (61, 61, 2, 46, 'DirectDrawEx', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (62, 62, 2, 47, 'DXM_Runtime', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (63, 63, 2, 48, 'EMCO MSI Package Builder Enterprise_is1', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (64, 64, 2, 49, 'EMCO MSI Package Builder Starter_is1', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (65, 65, 1, 108, '42563862383439663738312d6335626462652062', '2014-01-24 14:54:25');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (66, 66, 2, 50, 'Fontcore', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (67, 67, 1, 110, '....PHYSICALDRIVE0', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (68, 68, 2, 51, 'Gtk+ Development Environment for Windows', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (69, 69, 1, 112, '4', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (70, 70, 2, 52, 'ICW', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (71, 71, 1, 113, 'VBOX   - 1', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (72, 72, 2, 53, 'IE40', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (73, 73, 1, 35, 'Default System BIOS', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (74, 74, 2, 54, 'IE4Data', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (75, 75, 1, 116, 'Default System BIOS', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (76, 76, 2, 55, 'IE5BAKEX', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (77, 77, 2, 56, 'IEData', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (78, 78, 1, 119, 'innotek GmbH', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (79, 79, 1, 120, 'Default System BIOS', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (80, 80, 2, 57, 'Microsoft .NET Framework 2.0', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (81, 81, 1, 122, 'True', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (82, 82, 2, 58, 'Microsoft .NET Framework 2.0 SDK - ENU', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (83, 83, 1, 123, '20061201000000.000000+000', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (84, 84, 2, 59, 'Microsoft Visual J# 2.0 Redistributable Package', '2014-01-24 14:54:26');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (85, 85, 1, 124, '0', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (86, 86, 2, 60, 'MobileOptionPack', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (87, 87, 1, 125, 'VirtualBox', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (88, 88, 1, 126, '2', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (89, 89, 2, 61, 'MPlayer2', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (90, 90, 1, 127, '5', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (91, 91, 2, 62, 'NetMeeting', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (92, 92, 1, 128, 'Default System BIOS', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (93, 93, 2, 63, 'Oracle VM VirtualBox Guest Additions', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (94, 94, 1, 129, '0', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (95, 95, 1, 130, 'VBOX   - 1', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (96, 96, 2, 64, 'OutlookExpress', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (97, 97, 2, 65, 'PCHealth', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (98, 98, 2, 66, 'RAD Studio', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (99, 99, 2, 67, 'SchedulingAgent', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (100, 100, 2, 68, 'TortoiseCVS_is1', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (101, 101, 2, 69, '{1D44F148-5A2A-42CB-83AA-DB2B156F1ED7}', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (102, 102, 2, 70, '{350C9416-3D7C-4EE8-BAA9-00BCB3D54227}', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (103, 103, 2, 71, '{5A37B181-B8D0-48C3-B4A4-5DC1ED104CED}', '2014-01-24 14:54:27');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (104, 104, 2, 72, '{639159C2-B27B-4208-8965-D8A0AEDBDED2}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (105, 105, 2, 73, '{68A35043-C55A-4237-88C9-37EE1C63ED71}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (106, 106, 2, 74, '{710D9F65-B7F0-416C-9022-8C9098521270}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (107, 107, 2, 75, '{7131646D-CD3C-40F4-97B9-CD9E4E6262EF}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (108, 108, 2, 76, '{72263053-50D1-4598-9502-51ED64E54C51}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (109, 109, 2, 77, '{76F9F5C5-FF87-4ED8-B63C-2A25A299C4AA}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (110, 110, 2, 78, '{84ADC96C-B7E0-4938-9D6E-2B640D5DA224}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (111, 111, 2, 79, '{91227C10-CE03-4A31-BD0E-4986AB490716}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (112, 112, 2, 80, '{99758AC8-E271-4B1F-8092-865CD59A0D44}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (113, 113, 2, 81, '{9B09BEF2-10C4-4011-85F0-D9E5D18D641E}', '2014-01-24 14:54:28');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (114, 114, 2, 82, '{9B74BFA1-CA98-444D-853F-5C28593799CB}', '2014-01-24 14:54:29');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (115, 115, 2, 83, '{B6CF2967-C81E-40C0-9815-C05774FEF120}', '2014-01-24 14:54:29');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (116, 116, 2, 84, '{B7031148-C6E7-40F6-A978-EED2E77E7D1B}', '2014-01-24 14:54:29');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (117, 117, 2, 85, '{C15A8A76-9077-4DD1-95E5-4807A5B7D2AC}', '2014-01-24 14:54:29');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (118, 118, 2, 86, '{C9DCF4E9-A41B-40E7-B028-2255E36D2A1C}', '2014-01-24 14:54:29');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (119, 119, 2, 87, '{CF1215D3-8784-4908-9732-970A81248C7A}', '2014-01-24 14:54:29');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (120, 120, 2, 88, '{EE7257A2-39A2-4D2F-9DAC-F9F25B8AE1D8}', '2014-01-24 14:54:29');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (121, 121, 2, 31, 'Aperfeiçoado (101 ou 102 teclas)', '2014-01-24 14:54:30');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (122, 122, 2, 132, 'Teclado padrão com 101/102 teclas ou Microsoft Natural PS/2 Keyboard', '2014-01-24 14:54:30');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (123, 123, 2, 134, 'Aperfeiçoado (101 ou 102 teclas)', '2014-01-24 14:54:30');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (124, 124, 2, 135, '[[REG]]', '2014-01-24 14:54:30');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (125, 125, 2, 32, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB', '2014-01-24 14:54:30');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (126, 126, 2, 136, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB', '2014-01-24 14:54:30');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (127, 127, 2, 137, '[[REG]]', '2014-01-24 14:54:30');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (128, 128, 2, 138, 'Microsoft[[REG]](Dispositivos de sistema padrão)', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (129, 129, 2, 139, 'Microsoft PS/2 Mouse[[REG]]Dispositivo de interface humana USB', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (130, 130, 2, 150, '42566431346538656337382d6161626330632039', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (131, 131, 2, 152, '....PHYSICALDRIVE0', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (132, 132, 2, 154, '4', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (133, 133, 2, 155, 'VBOX   - 1', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (134, 134, 2, 35, 'Default System BIOS', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (135, 135, 2, 158, 'Default System BIOS', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (136, 136, 2, 161, 'innotek GmbH', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (137, 137, 2, 162, 'Default System BIOS', '2014-01-24 14:54:31');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (138, 138, 2, 164, 'True', '2014-01-24 14:54:32');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (139, 139, 2, 165, '20061201000000.000000+000', '2014-01-24 14:54:32');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (140, 140, 2, 166, '0', '2014-01-24 14:54:32');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (141, 141, 2, 167, 'VirtualBox', '2014-01-24 14:54:32');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (142, 142, 2, 168, '2', '2014-01-24 14:54:32');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (143, 143, 2, 169, '5', '2014-01-24 14:54:32');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (144, 144, 2, 170, 'Default System BIOS', '2014-01-24 14:54:32');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (145, 145, 2, 171, '0', '2014-01-24 14:54:32');
INSERT INTO computador_coleta_historico (id_computador_coleta_historico, id_computador_coleta, id_computador, id_class_property, te_class_property_value, dt_hr_inclusao) VALUES (146, 146, 2, 172, 'VBOX   - 1', '2014-01-24 14:54:32');


--
-- Name: computador_coleta_historico_id_computador_coleta_historico_seq; Type: SEQUENCE SET; Schema: public; Owner: eduardo
--

SELECT pg_catalog.setval('computador_coleta_historico_id_computador_coleta_historico_seq', 146, true);


--
-- Name: computador_coleta_id_computador_coleta_seq; Type: SEQUENCE SET; Schema: public; Owner: eduardo
--

SELECT pg_catalog.setval('computador_coleta_id_computador_coleta_seq', 146, true);


--
-- Data for Name: software; Type: TABLE DATA; Schema: public; Owner: eduardo
--

INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (1, NULL, 'AddressBook', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (2, NULL, 'Branding', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (3, NULL, 'Connection Manager', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (4, NULL, 'DirectAnimation', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (5, NULL, 'DirectDrawEx', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (6, NULL, 'DXM_Runtime', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (7, NULL, 'EMCO MSI Package Builder Enterprise', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (8, NULL, 'EMCO MSI Package Builder Starter', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (9, NULL, 'Fontcore', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (10, NULL, 'Gtk+ Development Environment for Windows 2.12.9-2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (11, NULL, 'ICW', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (12, NULL, 'IE40', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (13, NULL, 'IE4Data', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (14, NULL, 'IE5BAKEX', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (15, NULL, 'IEData', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (16, NULL, 'Microsoft .NET Framework 2.0', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (17, NULL, 'Microsoft .NET Framework 2.0 SDK - ENU', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (18, NULL, 'Microsoft Visual J# 2.0 Redistributable Package', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (19, NULL, 'MobileOptionPack', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (20, NULL, 'MPlayer2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (21, NULL, 'NetMeeting', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (22, NULL, 'Oracle VM VirtualBox Guest Additions 4.2.6', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (23, NULL, 'OutlookExpress', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (24, NULL, 'PCHealth', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (25, NULL, 'RAD Studio', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (26, NULL, 'SchedulingAgent', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (27, NULL, 'TortoiseCVS 1.12.5', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (28, NULL, 'WixEdit', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (29, NULL, 'WebFldrs XP', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (30, NULL, 'VC9RunTime', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (31, NULL, 'Microsoft .NET Framework 2.0 SDK - ENU', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (32, NULL, 'Microsoft Visual J# 2.0 Redistributable Package', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (33, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (34, NULL, 'Microsoft .NET Framework 2.0', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (35, NULL, 'Borland Delphi 7', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (36, NULL, 'CVSNT 2.5.05.3744', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (37, NULL, 'Python 2.7.4', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (38, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (39, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (40, NULL, 'InstalaÃ§Ã£o Cacic2.6Beta2 IPEA', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (41, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (42, NULL, 'Skype Click to Call', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (43, NULL, 'RAD Studio', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (44, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (45, NULL, 'TortoiseOverlays', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (46, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (47, NULL, 'Skypeâ„¢ 5.9', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (48, NULL, 'EMCO MSI Package Builder Enterprise', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (49, NULL, 'EMCO MSI Package Builder Starter', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (50, NULL, 'Gtk+ Development Environment for Windows 2.12.9-2', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (51, NULL, 'Oracle VM VirtualBox Guest Additions 4.2.6', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (52, NULL, 'TortoiseCVS 1.12.5', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (53, NULL, 'WixEdit', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (54, NULL, 'WebFldrs XP', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (55, NULL, 'VC9RunTime', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (56, NULL, 'Microsoft .NET Framework 2.0 SDK - ENU', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (57, NULL, 'Microsoft Visual J# 2.0 Redistributable Package', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (58, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (59, NULL, 'Microsoft .NET Framework 2.0', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (60, NULL, 'Borland Delphi 7', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (61, NULL, 'CVSNT 2.5.05.3744', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (62, NULL, 'Python 2.7.4', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (63, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (64, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (65, NULL, 'InstalaÃ§Ã£o Cacic2.6Beta2 IPEA', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (66, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (67, NULL, 'Skype Click to Call', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (68, NULL, 'RAD Studio', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (69, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (70, NULL, 'TortoiseOverlays', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (71, NULL, '(EMCO EVALUATION PACKAGE) - Cacic260', NULL, NULL, NULL, NULL, NULL);
INSERT INTO software (id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) VALUES (72, NULL, 'Skypeâ„¢ 5.9', NULL, NULL, NULL, NULL, NULL);


--
-- Data for Name: proriedade_software; Type: TABLE DATA; Schema: public; Owner: eduardo
--

INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (1, 1, 42, 1, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (2, 1, 43, 2, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (3, 1, 44, 3, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (4, 1, 45, 4, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (5, 1, 46, 5, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (6, 1, 47, 6, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (7, 1, 48, 7, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (8, 1, 49, 8, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (9, 1, 50, 9, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (10, 1, 51, 10, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (11, 1, 52, 11, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (12, 1, 53, 12, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (13, 1, 54, 13, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (14, 1, 55, 14, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (15, 1, 56, 15, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (16, 1, 57, 16, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (17, 1, 58, 17, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (18, 1, 59, 18, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (19, 1, 60, 19, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (20, 1, 61, 20, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (21, 1, 62, 21, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (22, 1, 63, 22, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (23, 1, 64, 23, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (24, 1, 65, 24, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (25, 1, 66, 25, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (26, 1, 67, 26, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (27, 1, 68, 27, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (28, 1, 69, 28, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (29, 1, 70, 29, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (30, 1, 71, 30, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (31, 1, 72, 31, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (32, 1, 73, 32, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (33, 1, 74, 33, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (34, 1, 75, 34, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (35, 1, 76, 35, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (36, 1, 77, 36, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (37, 1, 78, 37, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (38, 1, 79, 38, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (39, 1, 80, 39, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (40, 1, 81, 40, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (41, 1, 82, 41, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (42, 1, 83, 42, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (43, 1, 84, 43, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (44, 1, 85, 44, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (45, 1, 86, 45, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (46, 1, 87, 46, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (47, 1, 88, 47, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (48, 2, 42, 1, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (49, 2, 43, 2, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (50, 2, 44, 3, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (51, 2, 45, 4, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (52, 2, 46, 5, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (53, 2, 47, 6, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (54, 2, 48, 48, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (55, 2, 49, 49, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (56, 2, 50, 9, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (57, 2, 51, 50, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (58, 2, 52, 11, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (59, 2, 53, 12, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (60, 2, 54, 13, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (61, 2, 55, 14, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (62, 2, 56, 15, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (63, 2, 57, 16, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (64, 2, 58, 17, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (65, 2, 59, 18, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (66, 2, 60, 19, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (67, 2, 61, 20, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (68, 2, 62, 21, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (69, 2, 63, 51, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (70, 2, 64, 23, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (71, 2, 65, 24, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (72, 2, 66, 25, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (73, 2, 67, 26, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (74, 2, 68, 52, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (75, 2, 69, 53, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (76, 2, 70, 54, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (77, 2, 71, 55, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (78, 2, 72, 56, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (79, 2, 73, 57, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (80, 2, 74, 58, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (81, 2, 75, 59, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (82, 2, 76, 60, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (83, 2, 77, 61, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (84, 2, 78, 62, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (85, 2, 79, 63, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (86, 2, 80, 64, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (87, 2, 81, 65, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (88, 2, 82, 66, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (89, 2, 83, 67, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (90, 2, 84, 68, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (91, 2, 85, 69, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (92, 2, 86, 70, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (93, 2, 87, 71, NULL, NULL, NULL, NULL);
INSERT INTO proriedade_software (id_propriedade_software, id_computador, id_class_property, id_software, display_name, display_version, url_info_about, publisher) VALUES (94, 2, 88, 72, NULL, NULL, NULL, NULL);


--
-- Name: proriedade_software_id_propriedade_software_seq; Type: SEQUENCE SET; Schema: public; Owner: eduardo
--

SELECT pg_catalog.setval('proriedade_software_id_propriedade_software_seq', 94, true);


--
-- Name: software_id_software_seq; Type: SEQUENCE SET; Schema: public; Owner: eduardo
--

SELECT pg_catalog.setval('software_id_software_seq', 72, true);


--
-- PostgreSQL database dump complete
--

