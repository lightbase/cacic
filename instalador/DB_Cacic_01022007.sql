-- MySQL dump 10.9
--
-- Host: localhost    Database: cacic
-- ------------------------------------------------------
-- Server version	4.1.15-Debian_1ubuntu5-log


/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acoes`
--

DROP TABLE IF EXISTS `acoes`;
CREATE TABLE `acoes` (
  `id_acao` varchar(20) NOT NULL default '',
  `te_descricao_breve` varchar(100) default NULL,
  `te_descricao` text,
  `te_nome_curto_modulo` varchar(20) default NULL,
  `dt_hr_alteracao` datetime default '0000-00-00 00:00:00',
  `cs_situacao` char(1) default NULL,
  PRIMARY KEY  (`id_acao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acoes`
--


/*!40000 ALTER TABLE `acoes` DISABLE KEYS */;
LOCK TABLES `acoes` WRITE;
INSERT INTO `acoes` VALUES ('cs_auto_update','Auto Atualização dos Agentes','Essa ação permite que seja realizada a auto atualização dos agentes do CACIC nos computadores onde os agentes são executados. \r\n\r\n',NULL,'0000-00-00 00:00:00',NULL),('cs_coleta_compart','Coleta Informações de Compartilhamentos de Diretórios e Impressoras','Essa ação permite que sejam coletadas informações sobre compartilhamentos de diretórios e impressoras dos computadores onde os agentes estão instalado.','COMP','0000-00-00 00:00:00',NULL),('cs_coleta_hardware','Coleta Informações de Hardware','Essa ação permite que sejam coletadas diversas informações sobre o hardware dos computadores onde os agentes estão instalados, tais como Memória, Placa de Vídeo, CPU, Discos Rígidos, BIOS, Placa de Rede, Placa Mãe, etc.','HARD','0000-00-00 00:00:00',NULL),('cs_coleta_monitorado','Coleta Informações sobre os Sistemas Monitorados','Essa ação permite que sejam coletadas, nas estações onde os agentes Cacic estão instalados, as informações acerca dos perfís de sistemas, previamente cadastrados pela Administração Central.','MONI','0000-00-00 00:00:00',NULL),('cs_coleta_officescan','Coleta Informações do Antivírus OfficeScan','Essa ação permite que sejam coletadas informações sobre o anti-vírus OfficeScan nos computadores onde os agentes estão instalado. São coletadas informações como a versão do engine, versão do pattern, endereão do servidor, data da instalação, etc.','ANVI','0000-00-00 00:00:00',NULL),('cs_coleta_patrimonio','Coleta Informações de Patrimônio','Essa ação permite que sejam coletadas diversas informações sobre Patrimônio e Localização Física dos computadores onde os agentes estão instalados.','PATR','0000-00-00 00:00:00',NULL),('cs_coleta_software','Coleta Informações de Software','Essa ação permite que sejam coletadas informações sobre a versão de diversos softwares instalados nos computadores onde os agentes são executados. São coletadas, por exemplo, informações sobre as versões do Internet Explorer, Mozilla, DirectX, ADO, BDE, DAO, Java Runtime Environment, etc.','SOFT','0000-00-00 00:00:00',NULL),('cs_coleta_unid_disc','Coleta Informações sobre Unidades de Disco','Essa ação permite que sejam coletadas informações sobre as unidades de disco disponíveis nos computadores onde os agentes são executados. São coletadas, por exemplo, informações sobre o sistema de arquivos das unidades, suas capacidades de armazenamento, ocupação, espaço livre, etc.','UNDI','0000-00-00 00:00:00',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `acoes` ENABLE KEYS */;

--
-- Table structure for table `acoes_excecoes`
--

DROP TABLE IF EXISTS `acoes_excecoes`;
CREATE TABLE `acoes_excecoes` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_acao` varchar(20) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acoes_excecoes`
--


/*!40000 ALTER TABLE `acoes_excecoes` DISABLE KEYS */;
LOCK TABLES `acoes_excecoes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `acoes_excecoes` ENABLE KEYS */;

--
-- Table structure for table `acoes_redes`
--

DROP TABLE IF EXISTS `acoes_redes`;
CREATE TABLE `acoes_redes` (
  `id_acao` varchar(20) NOT NULL default '',
  `id_local` int(11) NOT NULL default '0',
  `id_ip_rede` varchar(15) NOT NULL default '',
  `dt_hr_coleta_forcada` datetime default NULL,
  `cs_situacao` char(1) NOT NULL default 'T',
  `dt_hr_alteracao` datetime default NULL,
  PRIMARY KEY  (`id_local`,`id_ip_rede`,`id_acao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acoes_redes`
--


/*!40000 ALTER TABLE `acoes_redes` DISABLE KEYS */;
LOCK TABLES `acoes_redes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `acoes_redes` ENABLE KEYS */;

--
-- Table structure for table `acoes_so`
--

DROP TABLE IF EXISTS `acoes_so`;
CREATE TABLE `acoes_so` (
  `id_local` int(11) NOT NULL default '0',
  `id_acao` varchar(20) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_acao`,`id_so`,`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `acoes_so`
--


/*!40000 ALTER TABLE `acoes_so` DISABLE KEYS */;
LOCK TABLES `acoes_so` WRITE;
INSERT INTO `acoes_so` VALUES (2,'cs_auto_update',0),(1,'cs_auto_update',1),(2,'cs_auto_update',1),(1,'cs_auto_update',2),(2,'cs_auto_update',2),(1,'cs_auto_update',3),(2,'cs_auto_update',3),(1,'cs_auto_update',4),(2,'cs_auto_update',4),(1,'cs_auto_update',5),(2,'cs_auto_update',5),(1,'cs_auto_update',6),(2,'cs_auto_update',6),(1,'cs_auto_update',7),(2,'cs_auto_update',7),(1,'cs_auto_update',8),(2,'cs_auto_update',8),(1,'cs_auto_update',9),(2,'cs_auto_update',9),(1,'cs_auto_update',10),(2,'cs_auto_update',10),(1,'cs_auto_update',11),(2,'cs_auto_update',11),(1,'cs_auto_update',12),(2,'cs_auto_update',12),(2,'cs_auto_update',13),(2,'cs_coleta_compart',0),(1,'cs_coleta_compart',1),(2,'cs_coleta_compart',1),(1,'cs_coleta_compart',2),(2,'cs_coleta_compart',2),(1,'cs_coleta_compart',3),(2,'cs_coleta_compart',3),(1,'cs_coleta_compart',4),(2,'cs_coleta_compart',4),(1,'cs_coleta_compart',5),(2,'cs_coleta_compart',5),(1,'cs_coleta_compart',6),(2,'cs_coleta_compart',6),(1,'cs_coleta_compart',7),(2,'cs_coleta_compart',7),(1,'cs_coleta_compart',8),(2,'cs_coleta_compart',8),(1,'cs_coleta_compart',9),(2,'cs_coleta_compart',9),(1,'cs_coleta_compart',10),(2,'cs_coleta_compart',10),(1,'cs_coleta_compart',11),(2,'cs_coleta_compart',11),(1,'cs_coleta_compart',12),(2,'cs_coleta_compart',12),(1,'cs_coleta_compart',13),(2,'cs_coleta_compart',13),(2,'cs_coleta_hardware',0),(1,'cs_coleta_hardware',1),(2,'cs_coleta_hardware',1),(1,'cs_coleta_hardware',2),(2,'cs_coleta_hardware',2),(1,'cs_coleta_hardware',3),(2,'cs_coleta_hardware',3),(1,'cs_coleta_hardware',4),(2,'cs_coleta_hardware',4),(1,'cs_coleta_hardware',5),(2,'cs_coleta_hardware',5),(1,'cs_coleta_hardware',6),(2,'cs_coleta_hardware',6),(1,'cs_coleta_hardware',7),(2,'cs_coleta_hardware',7),(1,'cs_coleta_hardware',8),(2,'cs_coleta_hardware',8),(1,'cs_coleta_hardware',9),(2,'cs_coleta_hardware',9),(1,'cs_coleta_hardware',10),(2,'cs_coleta_hardware',10),(1,'cs_coleta_hardware',11),(2,'cs_coleta_hardware',11),(1,'cs_coleta_hardware',12),(2,'cs_coleta_hardware',12),(1,'cs_coleta_hardware',13),(2,'cs_coleta_hardware',13),(2,'cs_coleta_monitorado',0),(1,'cs_coleta_monitorado',1),(2,'cs_coleta_monitorado',1),(1,'cs_coleta_monitorado',2),(2,'cs_coleta_monitorado',2),(1,'cs_coleta_monitorado',3),(2,'cs_coleta_monitorado',3),(1,'cs_coleta_monitorado',4),(2,'cs_coleta_monitorado',4),(1,'cs_coleta_monitorado',5),(2,'cs_coleta_monitorado',5),(1,'cs_coleta_monitorado',6),(2,'cs_coleta_monitorado',6),(1,'cs_coleta_monitorado',7),(2,'cs_coleta_monitorado',7),(1,'cs_coleta_monitorado',8),(2,'cs_coleta_monitorado',8),(1,'cs_coleta_monitorado',9),(2,'cs_coleta_monitorado',9),(1,'cs_coleta_monitorado',10),(2,'cs_coleta_monitorado',10),(1,'cs_coleta_monitorado',11),(2,'cs_coleta_monitorado',11),(1,'cs_coleta_monitorado',12),(2,'cs_coleta_monitorado',12),(1,'cs_coleta_monitorado',13),(2,'cs_coleta_monitorado',13),(2,'cs_coleta_officescan',0),(1,'cs_coleta_officescan',1),(2,'cs_coleta_officescan',1),(1,'cs_coleta_officescan',2),(2,'cs_coleta_officescan',2),(1,'cs_coleta_officescan',3),(2,'cs_coleta_officescan',3),(1,'cs_coleta_officescan',4),(2,'cs_coleta_officescan',4),(1,'cs_coleta_officescan',5),(2,'cs_coleta_officescan',5),(1,'cs_coleta_officescan',6),(2,'cs_coleta_officescan',6),(1,'cs_coleta_officescan',7),(2,'cs_coleta_officescan',7),(1,'cs_coleta_officescan',8),(2,'cs_coleta_officescan',8),(1,'cs_coleta_officescan',9),(2,'cs_coleta_officescan',9),(1,'cs_coleta_officescan',10),(2,'cs_coleta_officescan',10),(1,'cs_coleta_officescan',11),(2,'cs_coleta_officescan',11),(1,'cs_coleta_officescan',12),(2,'cs_coleta_officescan',12),(1,'cs_coleta_officescan',13),(2,'cs_coleta_officescan',13),(2,'cs_coleta_patrimonio',0),(1,'cs_coleta_patrimonio',1),(2,'cs_coleta_patrimonio',1),(1,'cs_coleta_patrimonio',2),(2,'cs_coleta_patrimonio',2),(1,'cs_coleta_patrimonio',3),(2,'cs_coleta_patrimonio',3),(1,'cs_coleta_patrimonio',4),(2,'cs_coleta_patrimonio',4),(1,'cs_coleta_patrimonio',5),(2,'cs_coleta_patrimonio',5),(1,'cs_coleta_patrimonio',6),(2,'cs_coleta_patrimonio',6),(1,'cs_coleta_patrimonio',7),(2,'cs_coleta_patrimonio',7),(1,'cs_coleta_patrimonio',8),(2,'cs_coleta_patrimonio',8),(1,'cs_coleta_patrimonio',9),(2,'cs_coleta_patrimonio',9),(1,'cs_coleta_patrimonio',10),(2,'cs_coleta_patrimonio',10),(1,'cs_coleta_patrimonio',11),(2,'cs_coleta_patrimonio',11),(1,'cs_coleta_patrimonio',12),(2,'cs_coleta_patrimonio',12),(1,'cs_coleta_patrimonio',13),(2,'cs_coleta_patrimonio',13),(2,'cs_coleta_software',0),(1,'cs_coleta_software',1),(2,'cs_coleta_software',1),(1,'cs_coleta_software',2),(2,'cs_coleta_software',2),(1,'cs_coleta_software',3),(2,'cs_coleta_software',3),(1,'cs_coleta_software',4),(2,'cs_coleta_software',4),(1,'cs_coleta_software',5),(2,'cs_coleta_software',5),(1,'cs_coleta_software',6),(2,'cs_coleta_software',6),(1,'cs_coleta_software',7),(2,'cs_coleta_software',7),(1,'cs_coleta_software',8),(2,'cs_coleta_software',8),(1,'cs_coleta_software',9),(2,'cs_coleta_software',9),(1,'cs_coleta_software',10),(2,'cs_coleta_software',10),(1,'cs_coleta_software',11),(2,'cs_coleta_software',11),(1,'cs_coleta_software',12),(2,'cs_coleta_software',12),(1,'cs_coleta_software',13),(2,'cs_coleta_software',13),(2,'cs_coleta_unid_disc',0),(1,'cs_coleta_unid_disc',1),(2,'cs_coleta_unid_disc',1),(1,'cs_coleta_unid_disc',2),(2,'cs_coleta_unid_disc',2),(1,'cs_coleta_unid_disc',3),(2,'cs_coleta_unid_disc',3),(1,'cs_coleta_unid_disc',4),(2,'cs_coleta_unid_disc',4),(1,'cs_coleta_unid_disc',5),(2,'cs_coleta_unid_disc',5),(1,'cs_coleta_unid_disc',6),(2,'cs_coleta_unid_disc',6),(1,'cs_coleta_unid_disc',7),(2,'cs_coleta_unid_disc',7),(1,'cs_coleta_unid_disc',8),(2,'cs_coleta_unid_disc',8),(1,'cs_coleta_unid_disc',9),(2,'cs_coleta_unid_disc',9),(1,'cs_coleta_unid_disc',10),(2,'cs_coleta_unid_disc',10),(1,'cs_coleta_unid_disc',11),(2,'cs_coleta_unid_disc',11),(1,'cs_coleta_unid_disc',12),(2,'cs_coleta_unid_disc',12),(1,'cs_coleta_unid_disc',13),(2,'cs_coleta_unid_disc',13);
UNLOCK TABLES;
/*!40000 ALTER TABLE `acoes_so` ENABLE KEYS */;

--
-- Table structure for table `aplicativos_monitorados`
--

DROP TABLE IF EXISTS `aplicativos_monitorados`;
CREATE TABLE `aplicativos_monitorados` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) unsigned NOT NULL default '0',
  `id_aplicativo` int(11) unsigned NOT NULL default '0',
  `te_versao` varchar(50) default NULL,
  `te_licenca` varchar(50) default NULL,
  `te_ver_engine` varchar(50) default NULL,
  `te_ver_pattern` varchar(50) default NULL,
  `cs_instalado` char(1) default NULL,
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_aplicativo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aplicativos_monitorados`
--


/*!40000 ALTER TABLE `aplicativos_monitorados` DISABLE KEYS */;
LOCK TABLES `aplicativos_monitorados` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `aplicativos_monitorados` ENABLE KEYS */;

--
-- Table structure for table `aplicativos_redes`
--

DROP TABLE IF EXISTS `aplicativos_redes`;
CREATE TABLE `aplicativos_redes` (
  `id_local` int(11) NOT NULL default '0',
  `id_ip_rede` varchar(15) NOT NULL default '',
  `id_aplicativo` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_local`,`id_ip_rede`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relacionamento entre redes e perfis de aplicativos monitorados';

--
-- Dumping data for table `aplicativos_redes`
--


/*!40000 ALTER TABLE `aplicativos_redes` DISABLE KEYS */;
LOCK TABLES `aplicativos_redes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `aplicativos_redes` ENABLE KEYS */;

--
-- Table structure for table `aquisicoes`
--

DROP TABLE IF EXISTS `aquisicoes`;
CREATE TABLE `aquisicoes` (
  `id_aquisicao` int(10) unsigned NOT NULL default '0',
  `dt_aquisicao` date default NULL,
  `nr_processo` varchar(11) default NULL,
  `nm_empresa` varchar(45) default NULL,
  `nm_proprietario` varchar(45) default NULL,
  `nr_notafiscal` int(10) unsigned default NULL,
  PRIMARY KEY  (`id_aquisicao`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aquisicoes`
--


/*!40000 ALTER TABLE `aquisicoes` DISABLE KEYS */;
LOCK TABLES `aquisicoes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `aquisicoes` ENABLE KEYS */;

--
-- Table structure for table `aquisicoes_item`
--

DROP TABLE IF EXISTS `aquisicoes_item`;
CREATE TABLE `aquisicoes_item` (
  `id_aquisicao` int(10) unsigned NOT NULL default '0',
  `id_software` int(10) unsigned NOT NULL default '0',
  `id_tipo_licenca` int(10) unsigned NOT NULL default '0',
  `qt_licenca` int(11) default NULL,
  `dt_vencimento_licenca` date default NULL,
  `te_obs` varchar(50) default NULL,
  PRIMARY KEY  (`id_aquisicao`,`id_software`,`id_tipo_licenca`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `aquisicoes_item`
--


/*!40000 ALTER TABLE `aquisicoes_item` DISABLE KEYS */;
LOCK TABLES `aquisicoes_item` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `aquisicoes_item` ENABLE KEYS */;

--
-- Table structure for table `compartilhamentos`
--

DROP TABLE IF EXISTS `compartilhamentos`;
CREATE TABLE `compartilhamentos` (
  `nm_compartilhamento` varchar(30) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  `te_node_address` varchar(17) NOT NULL default '',
  `nm_dir_compart` varchar(100) default NULL,
  `in_senha_escrita` char(1) default NULL,
  `in_senha_leitura` char(1) default NULL,
  `cs_tipo_permissao` char(1) default NULL,
  `cs_tipo_compart` char(1) default NULL,
  `te_comentario` varchar(50) default NULL,
  PRIMARY KEY  (`nm_compartilhamento`,`id_so`,`te_node_address`),
  KEY `node_so_tipocompart` (`te_node_address`,`id_so`,`cs_tipo_compart`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `compartilhamentos`
--


/*!40000 ALTER TABLE `compartilhamentos` DISABLE KEYS */;
LOCK TABLES `compartilhamentos` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `compartilhamentos` ENABLE KEYS */;

--
-- Table structure for table `computadores`
--

DROP TABLE IF EXISTS `computadores`;
CREATE TABLE `computadores` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  `te_so` varchar(10) default NULL,
  `te_nome_computador` varchar(50) default NULL,
  `id_ip_rede` varchar(15) NOT NULL default '',
  `te_dominio_windows` varchar(50) default NULL,
  `te_dominio_dns` varchar(50) default NULL,
  `te_placa_video_desc` varchar(100) default NULL,
  `te_ip` varchar(15) default NULL,
  `te_mascara` varchar(15) default NULL,
  `te_nome_host` varchar(50) default NULL,
  `te_placa_rede_desc` varchar(100) default NULL,
  `dt_hr_inclusao` datetime default NULL,
  `te_gateway` varchar(15) default NULL,
  `te_wins_primario` varchar(15) default NULL,
  `te_cpu_desc` varchar(100) default NULL,
  `te_wins_secundario` varchar(15) default NULL,
  `te_dns_primario` varchar(15) default NULL,
  `qt_placa_video_mem` int(11) default NULL,
  `te_dns_secundario` varchar(15) default NULL,
  `te_placa_mae_desc` varchar(100) default NULL,
  `te_serv_dhcp` varchar(15) default NULL,
  `qt_mem_ram` int(11) default NULL,
  `te_cpu_serial` varchar(50) default NULL,
  `te_cpu_fabricante` varchar(100) default NULL,
  `te_cpu_freq` varchar(6) default NULL,
  `te_mem_ram_desc` varchar(100) default NULL,
  `te_bios_desc` varchar(100) default NULL,
  `te_bios_data` varchar(10) default NULL,
  `dt_hr_ult_acesso` datetime default NULL,
  `te_versao_cacic` varchar(10) default NULL,
  `te_versao_gercols` varchar(10) default NULL,
  `te_bios_fabricante` varchar(100) default NULL,
  `te_placa_mae_fabricante` varchar(100) default NULL,
  `qt_placa_video_cores` int(11) default NULL,
  `te_placa_video_resolucao` varchar(10) default NULL,
  `te_placa_som_desc` varchar(100) default NULL,
  `te_cdrom_desc` varchar(100) default NULL,
  `te_teclado_desc` varchar(100) NOT NULL default '',
  `te_mouse_desc` varchar(100) default NULL,
  `te_modem_desc` varchar(100) default NULL,
  `te_workgroup` varchar(50) default NULL,
  `dt_hr_coleta_forcada_estacao` datetime default NULL,
  `te_nomes_curtos_modulos` varchar(255) default NULL,
  `te_origem_mac` text,
  `id_conta` int(10) unsigned default NULL,
  PRIMARY KEY  (`te_node_address`,`id_so`),
  KEY `computadores_versao_cacic` (`te_versao_cacic`),
  KEY `te_ip` (`te_ip`),
  KEY `te_node_address` (`te_node_address`),
  KEY `te_nome_computador` (`te_nome_computador`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `computadores`
--


/*!40000 ALTER TABLE `computadores` DISABLE KEYS */;
LOCK TABLES `computadores` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `computadores` ENABLE KEYS */;

--
-- Table structure for table `configuracoes_locais`
--

DROP TABLE IF EXISTS `configuracoes_locais`;
CREATE TABLE `configuracoes_locais` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `te_notificar_mudanca_hardware` text,
  `in_exibe_erros_criticos` char(1) default 'N',
  `in_exibe_bandeja` char(1) default 'S',
  `nu_exec_apos` int(11) default '10',
  `dt_hr_alteracao_patrim_interface` datetime default NULL,
  `dt_hr_alteracao_patrim_uon1` datetime default '0000-00-00 00:00:00',
  `dt_hr_alteracao_patrim_uon2` datetime default NULL,
  `dt_hr_coleta_forcada` datetime default NULL,
  `te_notificar_mudanca_patrim` text,
  `nm_organizacao` varchar(150) default NULL,
  `nu_intervalo_exec` int(11) default '4',
  `nu_intervalo_renovacao_patrim` int(11) default '0',
  `te_senha_adm_agente` varchar(30) default 'ADMINCACIC',
  `te_serv_updates_padrao` varchar(20) default NULL,
  `te_serv_cacic_padrao` varchar(20) default NULL,
  `te_enderecos_mac_invalidos` text,
  `te_janelas_excecao` text,
  `te_nota_email_gerentes` text,
  `cs_abre_janela_patr` char(1) NOT NULL default 'N',
  `id_default_body_bgcolor` varchar(10) NOT NULL default '#EBEBEB',
  PRIMARY KEY  (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configuracoes_locais`
--


/*!40000 ALTER TABLE `configuracoes_locais` DISABLE KEYS */;
LOCK TABLES `configuracoes_locais` WRITE;
INSERT INTO `configuracoes_locais` VALUES (1,NULL,'N','S',10,NULL,'0000-00-00 00:00:00',NULL,NULL,NULL,NULL,4,0,'','','','00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,\r\n00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08',NULL,NULL,'N','#EBEBEB');
UNLOCK TABLES;
/*!40000 ALTER TABLE `configuracoes_locais` ENABLE KEYS */;

--
-- Table structure for table `configuracoes_padrao`
--

DROP TABLE IF EXISTS `configuracoes_padrao`;
CREATE TABLE `configuracoes_padrao` (
  `in_exibe_erros_criticos` char(1) default NULL,
  `in_exibe_bandeja` char(1) default NULL,
  `nu_exec_apos` int(11) default NULL,
  `nm_organizacao` varchar(150) default NULL,
  `nu_intervalo_exec` int(11) default NULL,
  `nu_intervalo_renovacao_patrim` int(11) default NULL,
  `te_senha_adm_agente` varchar(30) default NULL,
  `te_serv_updates_padrao` varchar(20) default NULL,
  `te_serv_cacic_padrao` varchar(20) default NULL,
  `te_enderecos_mac_invalidos` text,
  `te_janelas_excecao` text,
  `cs_abre_janela_patr` char(1) NOT NULL default 'S',
  `id_default_body_bgcolor` varchar(10) NOT NULL default '#EBEBEB'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configuracoes_padrao`
--


/*!40000 ALTER TABLE `configuracoes_padrao` DISABLE KEYS */;
LOCK TABLES `configuracoes_padrao` WRITE;
INSERT INTO `configuracoes_padrao` VALUES ('N','S',0,'',0,0,'','','','00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,\r\n00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08','','N','#EBEBEB');
UNLOCK TABLES;
/*!40000 ALTER TABLE `configuracoes_padrao` ENABLE KEYS */;

--
-- Table structure for table `contas`
--

DROP TABLE IF EXISTS `contas`;
CREATE TABLE `contas` (
  `id_conta` int(10) unsigned NOT NULL auto_increment,
  `nm_responsavel` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id_conta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contas`
--


/*!40000 ALTER TABLE `contas` DISABLE KEYS */;
LOCK TABLES `contas` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `contas` ENABLE KEYS */;

--
-- Table structure for table `descricao_hardware`
--

DROP TABLE IF EXISTS `descricao_hardware`;
CREATE TABLE `descricao_hardware` (
  `nm_campo_tab_hardware` varchar(45) NOT NULL default '',
  `te_desc_hardware` varchar(45) NOT NULL default '',
  `cs_notificacao_ativada` char(1) default NULL,
  PRIMARY KEY  (`nm_campo_tab_hardware`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `descricao_hardware`
--


/*!40000 ALTER TABLE `descricao_hardware` DISABLE KEYS */;
LOCK TABLES `descricao_hardware` WRITE;
INSERT INTO `descricao_hardware` VALUES (' te_cdrom_desc','CD-ROM','1'),('qt_mem_ram','Memória RAM','1'),('qt_placa_video_cores','Qtd. Cores Placa Vídeo','0'),('qt_placa_video_mem','Memória Placa Vídeo','0'),('te_bios_desc','Descrição da BIOS','1'),('te_bios_fabricante','Fabricante da BIOS','0'),('te_cpu_desc','CPU','0'),('te_cpu_fabricante','Fabricante da CPU','0'),('te_cpu_serial','Serial da CPU','1'),('te_mem_ram_desc','Descrição da RAM','0'),('te_modem_desc','Modem','0'),('te_mouse_desc','Mouse','1'),('te_placa_mae_desc','Placa Mãe','1'),('te_placa_mae_fabricante','Fabricante Placa Mãe','0'),('te_placa_rede_desc','Placa de Rede','1'),('te_placa_som_desc','Placa de Som','1'),('te_placa_video_desc','Placa de Vídeo','1'),('te_placa_video_resolucao','Resolução Placa de Vídeo','0'),('te_teclado_desc','Teclado','1');
UNLOCK TABLES;
/*!40000 ALTER TABLE `descricao_hardware` ENABLE KEYS */;

--
-- Table structure for table `descricoes_colunas_computadores`
--

DROP TABLE IF EXISTS `descricoes_colunas_computadores`;
CREATE TABLE `descricoes_colunas_computadores` (
  `nm_campo` varchar(100) NOT NULL default '',
  `te_descricao_campo` varchar(100) NOT NULL default '',
  `cs_condicao_pesquisa` char(1) NOT NULL default 'S',
  UNIQUE KEY `nm_campo` (`nm_campo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela para auxílio na opção Exclusão de Informações';

--
-- Dumping data for table `descricoes_colunas_computadores`
--


/*!40000 ALTER TABLE `descricoes_colunas_computadores` DISABLE KEYS */;
LOCK TABLES `descricoes_colunas_computadores` WRITE;
INSERT INTO descricoes_colunas_computadores VALUES ('dt_hr_coleta_forcada_estacao', 'Quant. dias da última coleta forçada na estação', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('dt_hr_inclusao', 'Quant. dias de inclusão do computador na base', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('dt_hr_ult_acesso', 'Quant. dias do último acesso da estação ao gerente WEB', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('id_ip_rede', 'Endereço IP da Subrede', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('id_so', 'Código do sistema operacional da estação', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('qt_mem_ram', 'Quant. memória RAM', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('qt_placa_video_cores', 'Quant. cores da placa de vídeo', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('qt_placa_video_mem', 'Quant. memória da placa de vídeo', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_bios_data', 'Identificação da BIOS', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_bios_desc', 'Descrição da BIOS', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_bios_fabricante', 'Nome do fabricante da BIOS', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cdrom_desc', 'Descrição da unidade de CD-ROM', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cpu_desc', 'Descrição da CPU', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cpu_fabricante', 'Fabricante da CPU', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cpu_freq', 'Frequência da CPU', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cpu_serial', 'Número de série da CPU', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_dns_primario', 'IP do DNS primário', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_dns_secundario', 'IP do DNS secundário', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_dominio_dns', 'Nome/IP do domínio DNS', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_dominio_windows', 'Nome/IP do domínio Windows', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_gateway', 'IP do gateway', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_ip', 'IP da estação', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_mascara', 'Máscara de Subrede', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_mem_ram_desc', 'Descrição da memória RAM', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_modem_desc', 'Descrição do modem', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_mouse_desc', 'Descrição do mouse', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_node_address', 'Endereço MAC da estação', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_nomes_curtos_modulos', 'te_nomes_curtos_modulos', 'N');
INSERT INTO descricoes_colunas_computadores VALUES ('te_nome_computador', 'Nome do computador', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_nome_host', 'Nome do Host', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_origem_mac', 'te_origem_mac', 'N');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_mae_desc', 'Descrição da placa-m', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_mae_fabricante', 'Fabricante da placa-m', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_rede_desc', 'Descrição da placa de rede', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_som_desc', 'Descrição da placa de som', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_video_desc', 'Descrição da placa de vídeo', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_video_resolucao', 'Resolução da placa de vídeo', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_serv_dhcp', 'IP do servidor DHCP', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_teclado_desc', 'Descrição do teclado', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_versao_cacic', 'Versão do Agente Principal do CACIC', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_versao_gercols', 'Versão do Gerente de Coletas do CACIC', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_wins_primario', 'IP do servidor WINS primário', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_wins_secundario', 'IP do servidor WINS secundário', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_workgroup', 'Nome do grupo de trabalho', 'S');
UNLOCK TABLES;
/*!40000 ALTER TABLE `descricoes_colunas_computadores` ENABLE KEYS */;

--
-- Table structure for table `grupo_usuarios`
--

DROP TABLE IF EXISTS `grupo_usuarios`;
CREATE TABLE `grupo_usuarios` (
  `id_grupo_usuarios` int(2) NOT NULL auto_increment,
  `te_grupo_usuarios` varchar(20) default NULL,
  `te_menu_grupo` varchar(20) default NULL,
  `te_descricao_grupo` text NOT NULL,
  `cs_nivel_administracao` tinyint(2) NOT NULL default '0',
  `nm_grupo_usuarios` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id_grupo_usuarios`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grupo_usuarios`
--


/*!40000 ALTER TABLE `grupo_usuarios` DISABLE KEYS */;
LOCK TABLES `grupo_usuarios` WRITE;
INSERT INTO `grupo_usuarios` VALUES (1,'Comum','menu_com.txt','Usuário limitado, sem acesso a informações confidenciais como Softwares Inventariados e Opções Administrativas como Forçar Coletas e Excluir Computador. Poderá alterar sua própria senha.',0,''),(2,'Administração','menu_adm.txt','Acesso irrestrito.',1,''),(5,'Gestão Central','menu_adm.txt','Acesso de leitura em todas as opções.',2,''),(6,'Supervisão','menu_sup.txt','Manutenção de tabelas e acesso a todas as informações referentes à  Localização.',3,''),(7,'Técnico','menu_tec.txt','Acesso técnico. Será permitido acessar configuracoes de rede e relatórios de Patrimônio e Hardware.',0,'');
UNLOCK TABLES;
/*!40000 ALTER TABLE `grupo_usuarios` ENABLE KEYS */;

--
-- Table structure for table `historico_hardware`
--

DROP TABLE IF EXISTS `historico_hardware`;
CREATE TABLE `historico_hardware` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  `dt_hr_alteracao` datetime NOT NULL default '0000-00-00 00:00:00',
  `te_placa_video_desc` varchar(100) default NULL,
  `te_placa_rede_desc` varchar(100) default NULL,
  `te_cpu_desc` varchar(100) default NULL,
  `qt_placa_video_mem` int(11) default NULL,
  `te_placa_mae_desc` varchar(100) default NULL,
  `qt_mem_ram` int(11) default NULL,
  `te_cpu_serial` varchar(50) default NULL,
  `te_cpu_fabricante` varchar(100) default NULL,
  `te_cpu_freq` varchar(6) default NULL,
  `te_mem_ram_desc` varchar(100) default NULL,
  `te_bios_desc` varchar(100) default NULL,
  `te_bios_data` varchar(10) default NULL,
  `te_bios_fabricante` varchar(100) default NULL,
  `te_placa_mae_fabricante` varchar(100) default NULL,
  `qt_placa_video_cores` int(11) default NULL,
  `te_placa_video_resolucao` varchar(10) default NULL,
  `te_placa_som_desc` varchar(100) default NULL,
  `te_cdrom_desc` varchar(100) default NULL,
  `te_teclado_desc` varchar(100) default NULL,
  `te_mouse_desc` varchar(100) default NULL,
  `te_modem_desc` varchar(100) default NULL,
  `te_lic_win` varchar(50) default NULL,
  `te_key_win` varchar(50) default NULL,
  PRIMARY KEY  (`te_node_address`,`id_so`,`dt_hr_alteracao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `historico_hardware`
--


/*!40000 ALTER TABLE `historico_hardware` DISABLE KEYS */;
LOCK TABLES `historico_hardware` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `historico_hardware` ENABLE KEYS */;

--
-- Table structure for table `historico_tcp_ip`
--

DROP TABLE IF EXISTS `historico_tcp_ip`;
CREATE TABLE `historico_tcp_ip` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  `dt_hr_alteracao` datetime NOT NULL default '0000-00-00 00:00:00',
  `te_nome_computador` varchar(25) default NULL,
  `te_dominio_windows` varchar(30) default NULL,
  `te_dominio_dns` varchar(30) default NULL,
  `te_ip` varchar(15) default NULL,
  `te_mascara` varchar(15) default NULL,
  `id_ip_rede` varchar(15) default NULL,
  `te_nome_host` varchar(15) default NULL,
  `te_gateway` varchar(15) default NULL,
  `te_wins_primario` varchar(15) default NULL,
  `te_wins_secundario` varchar(15) default NULL,
  `te_dns_primario` varchar(15) default NULL,
  `te_dns_secundario` varchar(15) default NULL,
  `te_serv_dhcp` varchar(15) default NULL,
  `te_workgroup` varchar(20) default NULL,
  PRIMARY KEY  (`te_node_address`,`id_so`,`dt_hr_alteracao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `historico_tcp_ip`
--


/*!40000 ALTER TABLE `historico_tcp_ip` DISABLE KEYS */;
LOCK TABLES `historico_tcp_ip` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `historico_tcp_ip` ENABLE KEYS */;

--
-- Table structure for table `historicos_hardware`
--

DROP TABLE IF EXISTS `historicos_hardware`;
CREATE TABLE `historicos_hardware` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  `campo_alterado` varchar(45) default '',
  `valor_antigo` varchar(45) default '',
  `data_anterior` datetime default '0000-00-00 00:00:00',
  `novo_valor` varchar(45) default '',
  `nova_data` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`te_node_address`,`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `historicos_hardware`
--


/*!40000 ALTER TABLE `historicos_hardware` DISABLE KEYS */;
LOCK TABLES `historicos_hardware` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `historicos_hardware` ENABLE KEYS */;

--
-- Table structure for table `historicos_outros_softwares`
--

DROP TABLE IF EXISTS `historicos_outros_softwares`;
CREATE TABLE `historicos_outros_softwares` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(10) unsigned NOT NULL default '0',
  `id_software_inventariado` int(10) unsigned NOT NULL default '0',
  `dt_hr_inclusao` datetime NOT NULL default '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `historicos_outros_softwares`
--


/*!40000 ALTER TABLE `historicos_outros_softwares` DISABLE KEYS */;
LOCK TABLES `historicos_outros_softwares` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `historicos_outros_softwares` ENABLE KEYS */;

--
-- Table structure for table `historicos_software`
--

DROP TABLE IF EXISTS `historicos_software`;
CREATE TABLE `historicos_software` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) unsigned NOT NULL default '0',
  `id_software_inventariado` int(11) unsigned NOT NULL default '0',
  `dt_hr_inclusao` datetime NOT NULL default '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_software_inventariado`),
  KEY `id_software` (`id_software_inventariado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `historicos_software`
--


/*!40000 ALTER TABLE `historicos_software` DISABLE KEYS */;
LOCK TABLES `historicos_software` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `historicos_software` ENABLE KEYS */;

--
-- Table structure for table `historicos_software_completo`
--

DROP TABLE IF EXISTS `historicos_software_completo`;
CREATE TABLE `historicos_software_completo` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(10) unsigned NOT NULL default '0',
  `id_software_inventariado` int(10) unsigned NOT NULL default '0',
  `dt_hr_inclusao` datetime NOT NULL default '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_software_inventariado`,`dt_hr_inclusao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `historicos_software_completo`
--


/*!40000 ALTER TABLE `historicos_software_completo` DISABLE KEYS */;
LOCK TABLES `historicos_software_completo` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `historicos_software_completo` ENABLE KEYS */;

--
-- Table structure for table `locais`
--

DROP TABLE IF EXISTS `locais`;
CREATE TABLE `locais` (
  `id_local` int(11) unsigned NOT NULL auto_increment,
  `nm_local` varchar(100) NOT NULL default '',
  `sg_local` varchar(20) NOT NULL default '',
  `te_observacao` varchar(255) default NULL,
  PRIMARY KEY  (`id_local`),
  KEY `sg_localizacao` (`sg_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Localizações para regionalização de acesso a dados';

--
-- Dumping data for table `locais`
--


/*!40000 ALTER TABLE `locais` DISABLE KEYS */;
LOCK TABLES `locais` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `locais` ENABLE KEYS */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `dt_acao` datetime NOT NULL default '0000-00-00 00:00:00',
  `cs_acao` varchar(20) NOT NULL default '',
  `nm_script` varchar(255) NOT NULL default '',
  `nm_tabela` varchar(255) NOT NULL default '',
  `id_usuario` int(11) NOT NULL default '0',
  `te_ip_origem` varchar(15) NOT NULL default ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log de Atividades no Sistema CACIC';

--
-- Dumping data for table `log`
--


/*!40000 ALTER TABLE `log` DISABLE KEYS */;
LOCK TABLES `log` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

--
-- Table structure for table `officescan`
--

DROP TABLE IF EXISTS `officescan`;
CREATE TABLE `officescan` (
  `nu_versao_engine` varchar(10) default NULL,
  `nu_versao_pattern` varchar(10) default NULL,
  `dt_hr_instalacao` datetime default NULL,
  `dt_hr_coleta` datetime default NULL,
  `te_servidor` varchar(30) default NULL,
  `in_ativo` char(1) default NULL,
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  PRIMARY KEY  (`te_node_address`,`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `officescan`
--


/*!40000 ALTER TABLE `officescan` DISABLE KEYS */;
LOCK TABLES `officescan` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `officescan` ENABLE KEYS */;

--
-- Table structure for table `patrimonio`
--

DROP TABLE IF EXISTS `patrimonio`;
CREATE TABLE `patrimonio` (
  `id_unid_organizacional_nivel1` int(11) default NULL,
  `id_so` int(11) NOT NULL default '0',
  `dt_hr_alteracao` datetime NOT NULL default '0000-00-00 00:00:00',
  `te_node_address` varchar(17) default NULL,
  `id_unid_organizacional_nivel2` int(11) default NULL,
  `te_localizacao_complementar` varchar(100) default NULL,
  `te_info_patrimonio1` varchar(20) default NULL,
  `te_info_patrimonio2` varchar(20) default NULL,
  `te_info_patrimonio3` varchar(20) default NULL,
  `te_info_patrimonio4` varchar(20) default NULL,
  `te_info_patrimonio5` varchar(20) default NULL,
  `te_info_patrimonio6` varchar(20) default NULL,
  PRIMARY KEY  (`dt_hr_alteracao`),
  KEY `te_node_address` (`te_node_address`,`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patrimonio`
--


/*!40000 ALTER TABLE `patrimonio` DISABLE KEYS */;
LOCK TABLES `patrimonio` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `patrimonio` ENABLE KEYS */;

--
-- Table structure for table `patrimonio_config_interface`
--

DROP TABLE IF EXISTS `patrimonio_config_interface`;
CREATE TABLE `patrimonio_config_interface` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `id_etiqueta` varchar(30) NOT NULL default '',
  `nm_etiqueta` varchar(15) default NULL,
  `te_etiqueta` varchar(50) NOT NULL default '',
  `in_exibir_etiqueta` char(1) default NULL,
  `te_help_etiqueta` varchar(100) default NULL,
  `te_plural_etiqueta` varchar(50) default NULL,
  `nm_campo_tab_patrimonio` varchar(50) default NULL,
  `in_destacar_duplicidade` char(1) default 'N',
  PRIMARY KEY  (`id_etiqueta`,`id_local`),
  KEY `id_localizacao` (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patrimonio_config_interface`
--


/*!40000 ALTER TABLE `patrimonio_config_interface` DISABLE KEYS */;
	LOCK TABLES `patrimonio_config_interface` WRITE;
INSERT INTO `patrimonio_config_interface` VALUES (1,'etiqueta1','Etiqueta 1','Entidade','','Selecione a Entidade','Entidades','id_unid_organizacional_nivel1','N'),(2,'etiqueta1','Etiqueta 1','Entidade','','Selecione a Entidade','Entidades','id_unid_organizacional_nivel1','N'),(1,'etiqueta2','Etiqueta 2','orgão','','Selecione o orgão','orgãos','id_unid_organizacional_nivel2','N'),(2,'etiqueta2','Etiqueta 2','orgão','','Selecione o orgão','orgãos','id_unid_organizacional_nivel2','N'),(1,'etiqueta3','Etiqueta 3','Seção','','Informe a Seção onde está instalado o equipamento.','Sessões','te_localizacao_complementar','N'),(2,'etiqueta3','Etiqueta 3','Seção','','Informe a Seção onde está instalado o equipamento','Sessões','te_localizacao_complementar','N'),(1,'etiqueta4','Etiqueta 4','PIB da CPU','S','Informe o número de PIB(tombamento) da CPU','','te_info_patrimonio1','S'),(2,'etiqueta4','Etiqueta 4','PIB da CPU','S','Informe o número de PIB(tombamento) da CPU','','te_info_patrimonio1','S'),(1,'etiqueta5','Etiqueta 5','PIB do Monitor','S','Informe o número de PIB(tombamento) do Monitor','','te_info_patrimonio2','S'),(2,'etiqueta5','Etiqueta 5','PIB do monitor','S','Informe o número de PIB(tombamento) do monitor','','te_info_patrimonio2','S'),(1,'etiqueta6','Etiqueta 6','PIB da Impressora','S','Caso haja uma Impressora conectada informe número de PIB(tombamento)','','te_info_patrimonio3','S'),(2,'etiqueta6','Etiqueta 6','PIB da impressora','S','Caso haja uma impressora conectada, informe o nr. de PIB(tombamento)','','te_info_patrimonio3','S'),(1,'etiqueta7','Etiqueta 7','Nr. Série CPU','S','Caso não disponha do nr. de PIB, informe o Nr. de Série da CPU','','te_info_patrimonio4','S'),(2,'etiqueta7','Etiqueta 7','Nr. série CPU','S','Caso não disponha do nr. de PIB, informe o nr. de série da CPU','','te_info_patrimonio4','S'),(1,'etiqueta8','Etiqueta 8','Nr. série Monitor','S','Caso não disponha do nr. de PIB, informe o Nr. de Série do Monitor','','te_info_patrimonio5','S'),(2,'etiqueta8','Etiqueta 8','Nr. série Monitor','S','Caso não disponha do nr. de PIB, informe o nr. de série do Monitor','','te_info_patrimonio5','S'),(1,'etiqueta9','Etiqueta 9','Nr. Série Impres. (Opcional)','S','Caso haja uma impressora conectada ao micro e não disponha do nr. de PIB, informe seu nr. de série','','te_info_patrimonio6','S'),(2,'etiqueta9','Etiqueta 9','Nr. série Impres. (opcional)','S','Caso haja uma impressora conectada ao micro e não disponha do nr. de PIB, informe o nr. de série','','te_info_patrimonio6','S');
UNLOCK TABLES;
/*!40000 ALTER TABLE `patrimonio_config_interface` ENABLE KEYS */;

--
-- Table structure for table `perfis_aplicativos_monitorados`
--

DROP TABLE IF EXISTS `perfis_aplicativos_monitorados`;
CREATE TABLE `perfis_aplicativos_monitorados` (
  `id_aplicativo` int(11) NOT NULL auto_increment,
  `nm_aplicativo` varchar(100) NOT NULL default '',
  `cs_car_inst_w9x` char(2) default NULL,
  `te_car_inst_w9x` varchar(100) default NULL,
  `cs_car_ver_w9x` char(2) default NULL,
  `te_car_ver_w9x` varchar(100) default NULL,
  `cs_car_inst_wnt` char(2) default NULL,
  `te_car_inst_wnt` varchar(100) default NULL,
  `cs_car_ver_wnt` char(2) default NULL,
  `te_car_ver_wnt` varchar(100) default NULL,
  `cs_ide_licenca` char(2) default NULL,
  `te_ide_licenca` varchar(100) default NULL,
  `dt_atualizacao` datetime NOT NULL default '0000-00-00 00:00:00',
  `te_arq_ver_eng_w9x` varchar(100) default NULL,
  `te_arq_ver_pat_w9x` varchar(100) default NULL,
  `te_arq_ver_eng_wnt` varchar(100) default NULL,
  `te_arq_ver_pat_wnt` varchar(100) default NULL,
  `te_dir_padrao_w9x` varchar(100) default NULL,
  `te_dir_padrao_wnt` varchar(100) default NULL,
  `id_so` int(11) default '0',
  `te_descritivo` text,
  `in_disponibiliza_info` char(1) default 'N',
  `in_disponibiliza_info_usuario_comum` char(1) NOT NULL default 'N',
  `dt_registro` datetime default NULL,
  PRIMARY KEY  (`id_aplicativo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `perfis_aplicativos_monitorados`
--


/*!40000 ALTER TABLE `perfis_aplicativos_monitorados` DISABLE KEYS */;
LOCK TABLES `perfis_aplicativos_monitorados` WRITE;
INSERT INTO `perfis_aplicativos_monitorados` VALUES (8,'Windows 2000','0','','0','','0','','0','','1','HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\ProductId','2006-04-11 10:28:29','','','','','','',7,'','S','N',NULL),(16,'Windows 98 SE','0','','0','','0','','0','','1','HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId','2006-04-11 14:39:25','','','','','','',4,'','S','N',NULL),(20,'Windows XP','0','','0','','0','','0','','1','HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId','2006-04-11 10:29:29','','','','','','',8,'','S','N',NULL),(21,'Windows 95','0','','0','','0','','0','','1','HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId','2006-04-11 10:28:38','','','','','','',1,'','S','N',NULL),(22,'Windows 95 OSR2','0','','0','','0','','0','','1','HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId','2006-04-11 10:28:49','','','','','','',2,'','S','N',NULL),(23,'Windows NT','0','','0','','0','','0','','1','HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId','2006-04-11 10:29:18','','','','','','',6,'','S','N',NULL),(24,'Microsoft Office 2000','0','','0','','0','','0','','1','HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\Office\\9.0\\Registration\\ProductID\\(Padrão)','2006-04-11 10:28:15','','','','','','',0,'SuÃ­te para escritório com Editor de Textos, Planilha Eletrônica, Banco de Dados, etc.','S','N',NULL),(50,'OpenOffice.org 1.1.3','0','soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.3\\FriendlyAppName','0','OpenOffice.org1.1.3\\program\\soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.3\\FriendlyAppName','0','','2006-03-03 11:37:56','','','','','','',0,'','S','S',NULL),(51,'OpenOffice.org.br 1.1.3','0','soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 1.1.3\\FriendlyAppName','0','OpenOffice.org.br1.1.3\\program\\soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 1.1.3\\FriendlyAppName','0','','2006-03-03 11:38:21','','','','','','',0,'','S','S',NULL),(52,'OpenOffice.org 1.1.0','0','soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.0\\FriendlyAppName','0','soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.0\\FriendlyAppName','0','','2006-03-03 11:37:25','','','','','','',0,'','S','S',NULL),(53,'OpenOffice.org 1.0.3','0','soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.0.3\\FriendlyAppName','0','soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.0.3\\FriendlyAppName','0','','2006-03-03 11:37:12','','','','','','',0,'','S','S',NULL),(54,'OpenOffice.org 1.1.1a','0','soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.1a\\FriendlyAppName','0','soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.1a\\FriendlyAppName','0','','2006-03-03 11:37:37','','','','','','',0,'','S','S',NULL),(67,'CACIC - Col_Anvi - Col. de Inf. de Anti-Vírus','0','','4','Cacic\\modulos\\col_anvi.exe','0','','4','Cacic\\modulos\\col_anvi.exe','0','','2006-01-19 19:34:20','','','','','','',0,'','N','S',NULL),(68,'CACIC - Col_Moni - Col. de Inf. de Sistemas Monitorados','0','','4','cacic\\modulos\\col_moni.exe','0','','4','cacic\\modulos\\col_moni.exe','0','','2006-01-19 19:34:12','','','','','','',0,'','N','S',NULL),(69,'CACIC - Col_Patr - Col. de Inf. de Patrimônio e Loc. Física','0','','4','cacic\\modulos\\col_patr.exe','0','','4','cacic\\modulos\\col_patr.exe','0','','2006-01-19 19:35:03','','','','','','',0,'','N','S',NULL),(70,'CACIC - Col_Hard - Col. de Inf. de Hardware','0','','4','cacic\\modulos\\col_hard.exe','0','','4','cacic\\modulos\\col_hard.exe','0','','2006-01-19 19:34:38','','','','','','',0,'','N','S',NULL),(71,'CACIC - Col_Soft - Col. de Inf. de Softwares Básicos','0','','4','cacic\\modulos\\col_soft.exe','0','','4','cacic\\modulos\\col_soft.exe','0','','2006-01-19 19:35:19','','','','','','',0,'','N','S',NULL),(72,'CACIC - Col_Undi - Col. de Inf. de Unidades de Disco','0','','4','cacic\\modulos\\col_undi.exe','0','','4','cacic\\modulos\\col_undi.exe','0','','2006-01-19 19:35:35','','','','','','',0,'','N','S',NULL),(73,'CACIC - Col_Comp - Col. de Inf. de Compartilhamentos','0','','4','cacic\\modulos\\col_comp.exe','0','','4','cacic\\modulos\\col_comp.exe','0','','2006-01-19 19:34:29','','','','','','',0,'','N','S',NULL),(74,'CACIC - Ini_Cols - Inicializador de Coletas','0','','4','cacic\\modulos\\ini_cols.exe','0','','4','cacic\\modulos\\ini_cols.exe','0','','2006-01-10 16:33:12','','','','','','',0,'','N','S',NULL),(75,'CACIC - Agente Principal','0','','4','Cacic\\cacic2.exe','0','','4','Cacic\\cacic2.exe','0','','2006-01-19 19:37:07','','','','','','',0,'','S','S',NULL),(76,'CACIC - Gerente de Coletas','0','','4','Cacic\\modulos\\ger_cols.exe','0','','4','Cacic\\modulos\\ger_cols.exe','0','','2006-01-19 19:37:46','','','','','','',0,'','S','S',NULL),(77,'OpenOffice.org 2.0','0','Arquivos de programas\\OpenOffice.org 2.0\\program\\soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 2.0\\FriendlyAppName','0','Arquivos de programas\\OpenOffice.org 2.0\\program\\soffice.exe','2','HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 2.0\\FriendlyAppName','0','','2006-03-03 11:38:11','','','','','','',0,'','S','S',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `perfis_aplicativos_monitorados` ENABLE KEYS */;

--
-- Table structure for table `redes`
--

DROP TABLE IF EXISTS `redes`;
CREATE TABLE `redes` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `id_ip_rede` varchar(15) NOT NULL default '',
  `nm_rede` varchar(100) default NULL,
  `te_observacao` varchar(100) default NULL,
  `nm_pessoa_contato1` varchar(50) default NULL,
  `nm_pessoa_contato2` varchar(50) default NULL,
  `nu_telefone1` varchar(11) default NULL,
  `te_email_contato2` varchar(50) default NULL,
  `nu_telefone2` varchar(11) default NULL,
  `te_email_contato1` varchar(50) default NULL,
  `te_serv_cacic` varchar(45) default NULL,
  `te_serv_updates` varchar(45) default NULL,
  `te_path_serv_updates` varchar(255) default NULL,
  `nm_usuario_login_serv_updates` varchar(20) default NULL,
  `te_senha_login_serv_updates` varchar(20) default NULL,
  `nu_porta_serv_updates` varchar(4) default NULL,
  `te_mascara_rede` varchar(15) default NULL,
  `dt_verifica_updates` date default NULL,
  `nm_usuario_login_serv_updates_gerente` varchar(20) default NULL,
  `te_senha_login_serv_updates_gerente` varchar(20) default NULL,
  `nu_limite_ftp` int(5) unsigned NOT NULL default '5',
  PRIMARY KEY  (`id_ip_rede`,`id_local`),
  KEY `id_ip_rede` (`id_ip_rede`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `redes`
--


/*!40000 ALTER TABLE `redes` DISABLE KEYS */;
LOCK TABLES `redes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `redes` ENABLE KEYS */;

--
-- Table structure for table `redes_grupos_ftp`
--

DROP TABLE IF EXISTS `redes_grupos_ftp`;
CREATE TABLE `redes_grupos_ftp` (
  `id_local` int(11) NOT NULL default '0',
  `id_ip_rede` varchar(15) NOT NULL default '0',
  `id_ip_estacao` varchar(15) NOT NULL default '0',
  `nu_hora_inicio` int(12) NOT NULL default '0',
  `nu_hora_fim` varchar(12) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `redes_grupos_ftp`
--


/*!40000 ALTER TABLE `redes_grupos_ftp` DISABLE KEYS */;
LOCK TABLES `redes_grupos_ftp` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `redes_grupos_ftp` ENABLE KEYS */;

--
-- Table structure for table `redes_versoes_modulos`
--

DROP TABLE IF EXISTS `redes_versoes_modulos`;
CREATE TABLE `redes_versoes_modulos` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `id_ip_rede` varchar(15) NOT NULL default '',
  `nm_modulo` varchar(20) NOT NULL default '',
  `te_versao_modulo` varchar(20) default NULL,
  PRIMARY KEY  (`id_ip_rede`,`nm_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `redes_versoes_modulos`
--


/*!40000 ALTER TABLE `redes_versoes_modulos` DISABLE KEYS */;
LOCK TABLES `redes_versoes_modulos` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `redes_versoes_modulos` ENABLE KEYS */;

--
-- Table structure for table `so`
--

DROP TABLE IF EXISTS `so`;
CREATE TABLE `so` (
  `id_so` int(11) NOT NULL default '0',
  `te_desc_so` varchar(50) default NULL,
  `sg_so` varchar(10) default NULL,
  PRIMARY KEY  (`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `so`
--


/*!40000 ALTER TABLE `so` DISABLE KEYS */;
LOCK TABLES `so` WRITE;
INSERT INTO `so` VALUES (0,'S.O. Desconhecido','Desc.'),(1,'Windows 95','W95'),(2,'Windows 95 OSR2','W95OSR2'),(3,'Windows 98','W98'),(4,'Windows 98 SE','W98SE'),(5,'Windows ME','WME'),(6,'Windows NT','WNT'),(7,'Windows 2000','W2K'),(8,'Windows XP','WXP'),(9,'GNU/Linux','LNX'),(10,'FreeBSD','FBSD'),(11,'NetBSD','NBSD'),(12,'OpenBSD','OBSD'),(13,'Windows 2003','W2003');
UNLOCK TABLES;
/*!40000 ALTER TABLE `so` ENABLE KEYS */;

--
-- Table structure for table `softwares`
--

DROP TABLE IF EXISTS `softwares`;
CREATE TABLE `softwares` (
  `id_software` int(10) unsigned NOT NULL auto_increment,
  `nm_software` varchar(150) default NULL,
  `te_descricao_software` varchar(255) default NULL,
  `qt_licenca` int(11) default '0',
  `nr_midia` varchar(10) default NULL,
  `te_local_midia` varchar(30) default NULL,
  `te_obs` varchar(200) default NULL,
  PRIMARY KEY  (`id_software`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `softwares`
--


/*!40000 ALTER TABLE `softwares` DISABLE KEYS */;
LOCK TABLES `softwares` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `softwares` ENABLE KEYS */;

--
-- Table structure for table `softwares_estacao`
--

DROP TABLE IF EXISTS `softwares_estacao`;
CREATE TABLE `softwares_estacao` (
  `nr_patrimonio` varchar(20) NOT NULL default '',
  `id_software` int(10) unsigned NOT NULL default '0',
  `nm_computador` varchar(50) default NULL,
  `dt_autorizacao` date default NULL,
  `nr_processo` varchar(11) default NULL,
  `dt_expiracao_instalacao` date default NULL,
  `id_aquisicao_particular` int(10) unsigned default NULL,
  `dt_desinstalacao` date default NULL,
  `te_observacao` varchar(90) default NULL,
  `nr_patr_destino` varchar(20) default NULL,
  PRIMARY KEY  (`nr_patrimonio`,`id_software`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `softwares_estacao`
--


/*!40000 ALTER TABLE `softwares_estacao` DISABLE KEYS */;
LOCK TABLES `softwares_estacao` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `softwares_estacao` ENABLE KEYS */;

--
-- Table structure for table `softwares_inventariados`
--

DROP TABLE IF EXISTS `softwares_inventariados`;
CREATE TABLE `softwares_inventariados` (
  `id_software_inventariado` int(10) unsigned NOT NULL auto_increment,
  `nm_software_inventariado` varchar(100) NOT NULL default '',
  `id_tipo_software` int(11) default '0',
  `id_software` int(10) unsigned default NULL,
  PRIMARY KEY  (`id_software_inventariado`),
  KEY `nm_software_inventariado` (`nm_software_inventariado`),
  KEY `id_software` (`id_software_inventariado`),
  KEY `idx_nm_software_inventariado` (`nm_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `softwares_inventariados`
--


/*!40000 ALTER TABLE `softwares_inventariados` DISABLE KEYS */;
LOCK TABLES `softwares_inventariados` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `softwares_inventariados` ENABLE KEYS */;

--
-- Table structure for table `softwares_inventariados_estacoes`
--

DROP TABLE IF EXISTS `softwares_inventariados_estacoes`;
CREATE TABLE `softwares_inventariados_estacoes` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) unsigned NOT NULL default '0',
  `id_software_inventariado` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_software_inventariado`),
  KEY `id_software` (`id_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `softwares_inventariados_estacoes`
--


/*!40000 ALTER TABLE `softwares_inventariados_estacoes` DISABLE KEYS */;
LOCK TABLES `softwares_inventariados_estacoes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `softwares_inventariados_estacoes` ENABLE KEYS */;

--
-- Table structure for table `tipos_licenca`
--

DROP TABLE IF EXISTS `tipos_licenca`;
CREATE TABLE `tipos_licenca` (
  `id_tipo_licenca` int(10) unsigned NOT NULL auto_increment,
  `te_tipo_licenca` varchar(20) default NULL,
  PRIMARY KEY  (`id_tipo_licenca`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipos_licenca`
--


/*!40000 ALTER TABLE `tipos_licenca` DISABLE KEYS */;
LOCK TABLES `tipos_licenca` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `tipos_licenca` ENABLE KEYS */;

--
-- Table structure for table `tipos_software`
--

DROP TABLE IF EXISTS `tipos_software`;
CREATE TABLE `tipos_software` (
  `id_tipo_software` int(10) unsigned NOT NULL default '0',
  `te_descricao_tipo_software` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id_tipo_software`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipos_software`
--


/*!40000 ALTER TABLE `tipos_software` DISABLE KEYS */;
LOCK TABLES `tipos_software` WRITE;
INSERT INTO `tipos_software` VALUES (0,'Versão Trial'),(1,'Correção/Atualização'),(2,'Sistema Interno'),(3,'Software Livre'),(4,'Software Licenciado'),(5,'Software Suspeito'),(6,'Software Descontinuado'),(7,'Jogos e Similares');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tipos_software` ENABLE KEYS */;

--
-- Table structure for table `tipos_unidades_disco`
--

DROP TABLE IF EXISTS `tipos_unidades_disco`;
CREATE TABLE `tipos_unidades_disco` (
  `id_tipo_unid_disco` char(1) NOT NULL default '',
  `te_tipo_unid_disco` varchar(25) default NULL,
  PRIMARY KEY  (`id_tipo_unid_disco`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipos_unidades_disco`
--


/*!40000 ALTER TABLE `tipos_unidades_disco` DISABLE KEYS */;
LOCK TABLES `tipos_unidades_disco` WRITE;
INSERT INTO `tipos_unidades_disco` VALUES ('1','Removível'),('2','Disco Rígido'),('3','CD-ROM'),('4','Unid.Remota');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tipos_unidades_disco` ENABLE KEYS */;

--
-- Table structure for table `unid_organizacional_nivel1`
--

DROP TABLE IF EXISTS `unid_organizacional_nivel1`;
CREATE TABLE `unid_organizacional_nivel1` (
  `id_unid_organizacional_nivel1` int(11) NOT NULL auto_increment,
  `nm_unid_organizacional_nivel1` varchar(50) default NULL,
  `te_endereco_uon1` varchar(80) default NULL,
  `te_bairro_uon1` varchar(30) default NULL,
  `te_cidade_uon1` varchar(50) default NULL,
  `te_uf_uon1` char(2) default NULL,
  `nm_responsavel_uon1` varchar(80) default NULL,
  `te_email_responsavel_uon1` varchar(50) default NULL,
  `nu_tel1_responsavel_uon1` varchar(10) default NULL,
  `nu_tel2_responsavel_uon1` varchar(10) default NULL,
  PRIMARY KEY  (`id_unid_organizacional_nivel1`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unid_organizacional_nivel1`
--


/*!40000 ALTER TABLE `unid_organizacional_nivel1` DISABLE KEYS */;
LOCK TABLES `unid_organizacional_nivel1` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `unid_organizacional_nivel1` ENABLE KEYS */;

--
-- Table structure for table `unid_organizacional_nivel2`
--

DROP TABLE IF EXISTS `unid_organizacional_nivel2`;
CREATE TABLE `unid_organizacional_nivel2` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `id_unid_organizacional_nivel2` int(11) NOT NULL auto_increment,
  `id_unid_organizacional_nivel1` int(11) NOT NULL default '0',
  `nm_unid_organizacional_nivel2` varchar(50) NOT NULL default '',
  `te_endereco_uon2` varchar(80) default NULL,
  `te_bairro_uon2` varchar(30) default NULL,
  `te_cidade_uon2` varchar(50) default NULL,
  `te_uf_uon2` char(2) default NULL,
  `nm_responsavel_uon2` varchar(80) default NULL,
  `te_email_responsavel_uon2` varchar(50) default NULL,
  `nu_tel1_responsavel_uon2` varchar(10) default NULL,
  `nu_tel2_responsavel_uon2` varchar(10) default NULL,
  `dt_registro` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id_unid_organizacional_nivel2`,`id_unid_organizacional_nivel1`),
  KEY `id_localizacao` (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unid_organizacional_nivel2`
--


/*!40000 ALTER TABLE `unid_organizacional_nivel2` DISABLE KEYS */;
LOCK TABLES `unid_organizacional_nivel2` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `unid_organizacional_nivel2` ENABLE KEYS */;

--
-- Table structure for table `unidades_disco`
--

DROP TABLE IF EXISTS `unidades_disco`;
CREATE TABLE `unidades_disco` (
  `te_letra` char(1) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  `te_node_address` varchar(17) NOT NULL default '',
  `id_tipo_unid_disco` char(1) default NULL,
  `nu_serial` varchar(12) default NULL,
  `nu_capacidade` int(10) unsigned default NULL,
  `nu_espaco_livre` int(10) unsigned default NULL,
  `te_unc` varchar(60) default NULL,
  `cs_sist_arq` varchar(10) default NULL,
  PRIMARY KEY  (`te_letra`,`id_so`,`te_node_address`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unidades_disco`
--


/*!40000 ALTER TABLE `unidades_disco` DISABLE KEYS */;
LOCK TABLES `unidades_disco` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `unidades_disco` ENABLE KEYS */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `id_usuario` int(10) unsigned NOT NULL auto_increment,
  `nm_usuario_acesso` varchar(10) NOT NULL default '',
  `nm_usuario_completo` varchar(60) NOT NULL default '',
  `te_senha` varchar(50) NOT NULL default '',
  `dt_log_in` datetime NOT NULL default '0000-00-00 00:00:00',
  `id_grupo_usuarios` int(1) default NULL,
  `te_emails_contato` varchar(100) default NULL,
  `te_telefones_contato` varchar(100) default NULL,
  PRIMARY KEY  (`id_usuario`),
  KEY `id_localizacao` (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usuarios`
--


/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
LOCK TABLES `usuarios` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

--
-- Table structure for table `variaveis_ambiente`
--

DROP TABLE IF EXISTS `variaveis_ambiente`;
CREATE TABLE `variaveis_ambiente` (
  `id_variavel_ambiente` int(10) unsigned NOT NULL auto_increment,
  `nm_variavel_ambiente` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id_variavel_ambiente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `variaveis_ambiente`
--


/*!40000 ALTER TABLE `variaveis_ambiente` DISABLE KEYS */;
LOCK TABLES `variaveis_ambiente` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `variaveis_ambiente` ENABLE KEYS */;

--
-- Table structure for table `variaveis_ambiente_estacoes`
--

DROP TABLE IF EXISTS `variaveis_ambiente_estacoes`;
CREATE TABLE `variaveis_ambiente_estacoes` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(10) unsigned NOT NULL default '0',
  `id_variavel_ambiente` int(10) unsigned NOT NULL default '0',
  `vl_variavel_ambiente` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_variavel_ambiente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `variaveis_ambiente_estacoes`
--


/*!40000 ALTER TABLE `variaveis_ambiente_estacoes` DISABLE KEYS */;
LOCK TABLES `variaveis_ambiente_estacoes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `variaveis_ambiente_estacoes` ENABLE KEYS */;

--
-- Table structure for table `versoes_softwares`
--

DROP TABLE IF EXISTS `versoes_softwares`;
CREATE TABLE `versoes_softwares` (
  `id_so` int(11) NOT NULL default '0',
  `te_node_address` varchar(17) NOT NULL default '',
  `te_versao_bde` varchar(10) default NULL,
  `te_versao_dao` varchar(5) default NULL,
  `te_versao_ado` varchar(5) default NULL,
  `te_versao_odbc` varchar(15) default NULL,
  `te_versao_directx` int(10) unsigned default NULL,
  `te_versao_acrobat_reader` varchar(10) default NULL,
  `te_versao_ie` varchar(18) default NULL,
  `te_versao_mozilla` varchar(12) default NULL,
  `te_versao_jre` varchar(6) default NULL,
  PRIMARY KEY  (`te_node_address`,`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `versoes_softwares`
--


/*!40000 ALTER TABLE `versoes_softwares` DISABLE KEYS */;
LOCK TABLES `versoes_softwares` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `versoes_softwares` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

