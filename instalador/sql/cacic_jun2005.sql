-- script para converter o banco do cacic de jun2005 para um banco que poderá ser
-- utilizado pela versão 2.2 do cacic.

SET foreign_key_checks=0;


--
-- Table: `aplicativos_redes`
--
CREATE TABLE `aplicativos_redes` (
  `id_local` integer(11) NOT NULL DEFAULT '0',
  `id_ip_rede` varchar(15) NOT NULL DEFAULT '',
  `id_aplicativo` integer(11) unsigned NOT NULL DEFAULT '0',
  INDEX (`id_local`),
  PRIMARY KEY (`id_local`, `id_ip_rede`, `id_aplicativo`)
) ENGINE=InnoDB CHARACTER SET=latin1 comment='Relacionamento entre redes e perfis de aplicativos monitorad';


--
-- Table: `aquisicoes`
--
CREATE TABLE `aquisicoes` (
  `id_aquisicao` integer(10) unsigned NOT NULL DEFAULT '0',
  `dt_aquisicao` date DEFAULT NULL,
  `nr_processo` varchar(11) DEFAULT NULL,
  `nm_empresa` varchar(45) DEFAULT NULL,
  `nm_proprietario` varchar(45) DEFAULT NULL,
  `nr_notafiscal` integer(10) unsigned DEFAULT NULL,
  INDEX (`id_aquisicao`),
  PRIMARY KEY (`id_aquisicao`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `aquisicoes_item`
--
CREATE TABLE `aquisicoes_item` (
  `id_aquisicao` integer(10) unsigned NOT NULL DEFAULT '0',
  `id_software` integer(10) unsigned NOT NULL DEFAULT '0',
  `id_tipo_licenca` integer(10) unsigned NOT NULL DEFAULT '0',
  `qt_licenca` integer(11) DEFAULT NULL,
  `dt_vencimento_licenca` date DEFAULT NULL,
  `te_obs` varchar(50) DEFAULT NULL,
  INDEX (`id_aquisicao`),
  PRIMARY KEY (`id_aquisicao`, `id_software`, `id_tipo_licenca`)
) ENGINE=InnoDB CHARACTER SET=latin1 ROW_FORMAT=DYNAMIC;


--
-- Table: `configuracoes_locais`
--
CREATE TABLE `configuracoes_locais` (
  `id_local` integer(11) unsigned NOT NULL DEFAULT '0',
  `te_notificar_mudanca_hardware` text,
  `in_exibe_erros_criticos` char(1) DEFAULT 'N',
  `in_exibe_bandeja` char(1) DEFAULT 'S',
  `nu_exec_apos` integer(11) DEFAULT '10',
  `dt_hr_alteracao_patrim_interface` datetime DEFAULT NULL,
  `dt_hr_alteracao_patrim_uon1` datetime DEFAULT '0000-00-00 00:00:00',
  `dt_hr_alteracao_patrim_uon2` datetime DEFAULT NULL,
  `dt_hr_coleta_forcada` datetime DEFAULT NULL,
  `te_notificar_mudanca_patrim` text,
  `nm_organizacao` varchar(150) DEFAULT NULL,
  `nu_intervalo_exec` integer(11) DEFAULT '4',
  `nu_intervalo_renovacao_patrim` integer(11) DEFAULT '0',
  `te_senha_adm_agente` varchar(30) DEFAULT 'ADMINCACIC',
  `te_serv_updates_padrao` varchar(20) DEFAULT NULL,
  `te_serv_cacic_padrao` varchar(20) DEFAULT NULL,
  `te_enderecos_mac_invalidos` text,
  `te_janelas_excecao` text,
  `te_nota_email_gerentes` text,
  `cs_abre_janela_patr` char(1) NOT NULL DEFAULT 'N',
  `id_default_body_bgcolor` varchar(10) NOT NULL DEFAULT '#EBEBEB',
  INDEX (`id_local`),
  PRIMARY KEY (`id_local`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `configuracoes_padrao`
--
CREATE TABLE `configuracoes_padrao` (
  `in_exibe_erros_criticos` char(1) DEFAULT NULL,
  `in_exibe_bandeja` char(1) DEFAULT NULL,
  `nu_exec_apos` integer(11) DEFAULT NULL,
  `nm_organizacao` varchar(150) DEFAULT NULL,
  `nu_intervalo_exec` integer(11) DEFAULT NULL,
  `nu_intervalo_renovacao_patrim` integer(11) DEFAULT NULL,
  `te_senha_adm_agente` varchar(30) DEFAULT NULL,
  `te_serv_updates_padrao` varchar(20) DEFAULT NULL,
  `te_serv_cacic_padrao` varchar(20) DEFAULT NULL,
  `te_enderecos_mac_invalidos` text,
  `te_janelas_excecao` text,
  `cs_abre_janela_patr` char(1) NOT NULL DEFAULT 'S',
  `id_default_body_bgcolor` varchar(10) NOT NULL DEFAULT '#EBEBEB'
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `contas`
--
CREATE TABLE `contas` (
  `id_conta` integer(10) unsigned NOT NULL auto_increment,
  `nm_responsavel` varchar(30) NOT NULL DEFAULT '',
  INDEX (`id_conta`),
  PRIMARY KEY (`id_conta`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `descricoes_colunas_computadores`
--
CREATE TABLE `descricoes_colunas_computadores` (
  `nm_campo` varchar(100) NOT NULL DEFAULT '',
  `te_descricao_campo` varchar(100) NOT NULL DEFAULT '',
  `cs_condicao_pesquisa` char(1) NOT NULL DEFAULT 'S',
  INDEX (`nm_campo`),
  UNIQUE (`nm_campo`)
) ENGINE=InnoDB CHARACTER SET=latin1 comment='Tabela para auxílio na opção Exclusão de Informações de Comp';


--
-- Table: `historicos_hardware`
--
CREATE TABLE `historicos_hardware` (
  `te_node_address` varchar(17) NOT NULL DEFAULT '',
  `id_so` integer(11) NOT NULL DEFAULT '0',
  `campo_alterado` varchar(45) DEFAULT '',
  `valor_antigo` varchar(45) DEFAULT '',
  `data_anterior` datetime DEFAULT '0000-00-00 00:00:00',
  `novo_valor` varchar(45) DEFAULT '',
  `nova_data` datetime DEFAULT '0000-00-00 00:00:00',
  INDEX (`te_node_address`),
  PRIMARY KEY (`te_node_address`, `id_so`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `historicos_outros_softwares`
--
CREATE TABLE `historicos_outros_softwares` (
  `te_node_address` varchar(17) NOT NULL DEFAULT '',
  `id_so` integer(10) unsigned NOT NULL DEFAULT '0',
  `id_software_inventariado` integer(10) unsigned NOT NULL DEFAULT '0',
  `dt_hr_inclusao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  INDEX (`te_node_address`),
  PRIMARY KEY (`te_node_address`, `id_so`, `id_software_inventariado`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `historicos_software`
--
CREATE TABLE `historicos_software` (
  `te_node_address` varchar(17) NOT NULL DEFAULT '',
  `id_so` integer(11) unsigned NOT NULL DEFAULT '0',
  `id_software_inventariado` integer(11) unsigned NOT NULL DEFAULT '0',
  `dt_hr_inclusao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  INDEX id_software (`id_software_inventariado`),
  INDEX (`te_node_address`),
  PRIMARY KEY (`te_node_address`, `id_so`, `id_software_inventariado`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `historicos_software_completo`
--
CREATE TABLE `historicos_software_completo` (
  `te_node_address` varchar(17) NOT NULL DEFAULT '',
  `id_so` integer(10) unsigned NOT NULL DEFAULT '0',
  `id_software_inventariado` integer(10) unsigned NOT NULL DEFAULT '0',
  `dt_hr_inclusao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  INDEX (`te_node_address`),
  PRIMARY KEY (`te_node_address`, `id_so`, `id_software_inventariado`, `dt_hr_inclusao`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `locais`
--
CREATE TABLE `locais` (
  `id_local` integer(11) unsigned NOT NULL auto_increment,
  `nm_local` varchar(100) NOT NULL DEFAULT '',
  `sg_local` varchar(20) NOT NULL DEFAULT '',
  `te_observacao` varchar(255) DEFAULT NULL,
  INDEX sg_localizacao (`sg_local`),
  INDEX (`id_local`),
  PRIMARY KEY (`id_local`)
) ENGINE=InnoDB CHARACTER SET=latin1 comment='Localizações para regionalização de acesso a dados';


--
-- Table: `log`
--
CREATE TABLE `log` (
  `dt_acao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cs_acao` varchar(20) NOT NULL DEFAULT '',
  `nm_script` varchar(255) NOT NULL DEFAULT '',
  `nm_tabela` varchar(255) NOT NULL DEFAULT '',
  `id_usuario` integer(11) NOT NULL DEFAULT '0',
  `te_ip_origem` varchar(15) NOT NULL DEFAULT ''
) ENGINE=InnoDB CHARACTER SET=latin1 comment='Log de Atividades no Sistema CACIC';


--
-- Table: `softwares`
--
CREATE TABLE `softwares` (
  `id_software` integer(10) unsigned NOT NULL auto_increment,
  `nm_software` varchar(150) DEFAULT NULL,
  `te_descricao_software` varchar(255) DEFAULT NULL,
  `qt_licenca` integer(11) DEFAULT '0',
  `nr_midia` varchar(10) DEFAULT NULL,
  `te_local_midia` varchar(30) DEFAULT NULL,
  `te_obs` varchar(200) DEFAULT NULL,
  INDEX (`id_software`),
  PRIMARY KEY (`id_software`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `softwares_estacao`
--
CREATE TABLE `softwares_estacao` (
  `nr_patrimonio` varchar(20) NOT NULL DEFAULT '',
  `id_software` integer(10) unsigned NOT NULL DEFAULT '0',
  `nm_computador` varchar(50) DEFAULT NULL,
  `dt_autorizacao` date DEFAULT NULL,
  `nr_processo` varchar(11) DEFAULT NULL,
  `dt_expiracao_instalacao` date DEFAULT NULL,
  `id_aquisicao_particular` integer(10) unsigned DEFAULT NULL,
  `dt_desinstalacao` date DEFAULT NULL,
  `te_observacao` varchar(90) DEFAULT NULL,
  `nr_patr_destino` varchar(20) DEFAULT NULL,
  INDEX (`nr_patrimonio`),
  PRIMARY KEY (`nr_patrimonio`, `id_software`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `tipos_licenca`
--
CREATE TABLE `tipos_licenca` (
  `id_tipo_licenca` integer(10) unsigned NOT NULL auto_increment,
  `te_tipo_licenca` varchar(20) DEFAULT NULL,
  INDEX (`id_tipo_licenca`),
  PRIMARY KEY (`id_tipo_licenca`)
) ENGINE=InnoDB CHARACTER SET=latin1;


--
-- Table: `tipos_software`
--
CREATE TABLE `tipos_software` (
  `id_tipo_software` integer(10) unsigned NOT NULL,
  `te_descricao_tipo_software` varchar(30) NOT NULL DEFAULT '',
  INDEX (`id_tipo_software`),
  PRIMARY KEY (`id_tipo_software`)
) ENGINE=InnoDB CHARACTER SET=latin1;


SET foreign_key_checks=1;


ALTER TABLE acoes ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE acoes_excecoes ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE acoes_redes DROP PRIMARY KEY;
ALTER TABLE acoes_redes ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE acoes_redes ADD id_local int(11) DEFAULT '0' NOT NULL;
ALTER TABLE acoes_redes ADD cs_situacao char(1) DEFAULT 'T' NOT NULL;
ALTER TABLE acoes_redes ADD dt_hr_alteracao datetime DEFAULT NULL;
ALTER TABLE acoes_so DROP PRIMARY KEY;
ALTER TABLE acoes_so ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE acoes_so ADD id_local int(11) DEFAULT '0' NOT NULL;
ALTER TABLE aplicativos_monitorados ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE compartilhamentos ENGINE=InnoDB CHARACTER SET=latin1;
CREATE INDEX node_so_tipocompart ON compartilhamentos (te_node_address,id_so,cs_tipo_compart);
ALTER TABLE computadores ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE computadores ADD te_so varchar(10) DEFAULT NULL;
ALTER TABLE computadores ADD te_versao_gercols varchar(10) DEFAULT NULL;
ALTER TABLE computadores ADD id_conta int(10) DEFAULT NULL;
ALTER TABLE computadores CHANGE te_mem_ram_desc te_mem_ram_desc varchar(200) DEFAULT NULL;
CREATE INDEX te_ip ON computadores (te_ip);
CREATE INDEX te_node_address ON computadores (te_node_address);
CREATE INDEX te_nome_computador ON computadores (te_nome_computador);
ALTER TABLE descricao_hardware ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE grupo_usuarios ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE grupo_usuarios ADD cs_nivel_administracao tinyint(2) DEFAULT '0' NOT NULL;
ALTER TABLE grupo_usuarios CHANGE id_grupo_usuarios id_grupo_usuarios int(2) NOT NULL AUTO_INCREMENT;
ALTER TABLE historico_hardware ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE historico_tcp_ip ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE officescan ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE officescan CHANGE te_node_address te_node_address varchar(17) NOT NULL DEFAULT '';
ALTER TABLE officescan CHANGE id_so id_so int(11) NOT NULL DEFAULT '0';
ALTER TABLE patrimonio ENGINE=InnoDB CHARACTER SET=latin1;
CREATE INDEX te_node_address ON patrimonio (te_node_address,id_so);
ALTER TABLE patrimonio_config_interface DROP PRIMARY KEY;
ALTER TABLE patrimonio_config_interface ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE patrimonio_config_interface ADD id_local int(11) DEFAULT '0' NOT NULL;
CREATE INDEX id_localizacao ON patrimonio_config_interface (id_local);
ALTER TABLE perfis_aplicativos_monitorados ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE perfis_aplicativos_monitorados ADD in_disponibiliza_info_usuario_comum char(1) DEFAULT 'N' NOT NULL;
ALTER TABLE redes DROP PRIMARY KEY;
ALTER TABLE redes ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE redes ADD id_local int(11) DEFAULT '0' NOT NULL;
ALTER TABLE redes ADD nu_limite_ftp int(5) DEFAULT '5' NOT NULL;
CREATE INDEX id_ip_rede ON redes (id_ip_rede);
ALTER TABLE redes_grupos_ftp ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE redes_grupos_ftp ADD id_local int(11) DEFAULT '0' NOT NULL;
ALTER TABLE redes_grupos_ftp ADD id_ftp int(11) NOT NULL auto_increment;
ALTER TABLE redes_grupos_ftp ADD PRIMARY KEY ( `id_ftp` ); 
ALTER TABLE redes_versoes_modulos ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE redes_versoes_modulos ADD id_local int(11) DEFAULT '0' NOT NULL;
ALTER TABLE so ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE so ADD te_so varchar(50) DEFAULT NULL;
ALTER TABLE softwares_inventariados ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE softwares_inventariados ADD id_tipo_software int(11) DEFAULT '0';
ALTER TABLE softwares_inventariados ADD id_software int(10) DEFAULT NULL;
CREATE INDEX id_software ON softwares_inventariados (id_software_inventariado);
ALTER TABLE softwares_inventariados_estacoes ENGINE=InnoDB CHARACTER SET=latin1;
CREATE INDEX id_software ON softwares_inventariados_estacoes (id_software_inventariado);
ALTER TABLE tipos_unidades_disco ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE unid_organizacional_nivel1 ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE unid_organizacional_nivel2 ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE unid_organizacional_nivel2 ADD id_local int(11) DEFAULT '0' NOT NULL;
CREATE INDEX id_localizacao ON unid_organizacional_nivel2 (id_local);
ALTER TABLE unidades_disco ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE usuarios ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE usuarios ADD id_local int(11) DEFAULT '0' NOT NULL;
ALTER TABLE usuarios ADD te_emails_contato varchar(100) DEFAULT NULL;
ALTER TABLE usuarios ADD te_telefones_contato varchar(100) DEFAULT NULL;
ALTER TABLE usuarios ADD te_locais_secundarios varchar(200) DEFAULT NULL;
ALTER TABLE usuarios CHANGE te_senha te_senha varchar(50) NOT NULL DEFAULT '';
CREATE INDEX id_localizacao ON usuarios (id_local);
ALTER TABLE variaveis_ambiente ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE variaveis_ambiente_estacoes ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE versoes_softwares ENGINE=InnoDB CHARACTER SET=latin1;
ALTER TABLE versoes_softwares CHANGE id_so id_so int(11) NOT NULL DEFAULT '0';
ALTER TABLE versoes_softwares CHANGE te_node_address te_node_address varchar(17) NOT NULL DEFAULT '';
ALTER TABLE acoes_redes ADD PRIMARY KEY (id_local, id_ip_rede, id_acao);
ALTER TABLE acoes_so ADD PRIMARY KEY (id_acao, id_so, id_local);
ALTER TABLE officescan ADD PRIMARY KEY (te_node_address, id_so);
ALTER TABLE patrimonio_config_interface ADD PRIMARY KEY (id_etiqueta, id_local);
ALTER TABLE redes ADD PRIMARY KEY (id_ip_rede, id_local);
ALTER TABLE versoes_softwares ADD PRIMARY KEY (te_node_address, id_so);
DROP TABLE configuracoes;
DROP TABLE gerentes;
DROP TABLE gerentes_versoes_modulos;
