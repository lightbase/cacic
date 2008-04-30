-- --------------------------------------------------------
-- Tabelas para o banco de dados CACIC
-- SGBD: MySQL-4.1.20
-- --------------------------------------------------------
--
-- Table structure for table `acoes`
--

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
-- Table structure for table `acoes_excecoes`
--

CREATE TABLE `acoes_excecoes` (
  `id_local` int(11) NOT NULL,
  `te_node_address` varchar(17) NOT NULL default '',
  `id_acao` varchar(20) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `acoes_redes`
--

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
-- Table structure for table `acoes_so`
--

CREATE TABLE `acoes_so` (
  `id_local` int(11) NOT NULL default '0',
  `id_acao` varchar(20) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_acao`,`id_so`,`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `aplicativos_monitorados`
--

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
-- Table structure for table `aplicativos_redes`
--

CREATE TABLE `aplicativos_redes` (
  `id_local` int(11) NOT NULL default '0',
  `id_ip_rede` varchar(15) NOT NULL default '',
  `id_aplicativo` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_local`,`id_ip_rede`,`id_aplicativo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relacionamento entre redes e perfis de aplicativos monitorados';

--
-- Table structure for table `aquisicoes`
--

CREATE TABLE `aquisicoes` (
  `id_aquisicao` int(10) unsigned NOT NULL default '0',
  `dt_aquisicao` date default NULL,
  `nr_processo` varchar(11) default NULL,
  `nm_empresa` varchar(45) default NULL,
  `nm_proprietario` varchar(45) default NULL,
  `nr_notafiscal` int(10) unsigned default NULL,
  PRIMARY KEY  (`id_aquisicao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `aquisicoes_item`
--

CREATE TABLE `aquisicoes_item` (
  `id_aquisicao` int(10) unsigned NOT NULL default '0',
  `id_software` int(10) unsigned NOT NULL default '0',
  `id_tipo_licenca` int(10) unsigned NOT NULL default '0',
  `qt_licenca` int(11) default NULL,
  `dt_vencimento_licenca` date default NULL,
  `te_obs` varchar(50) default NULL,
  PRIMARY KEY  (`id_aquisicao`,`id_software`,`id_tipo_licenca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Table structure for table `compartilhamentos`
--

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
-- Table structure for table `componentes_estacoes`
--

CREATE TABLE `componentes_estacoes` (
  `te_node_address` varchar(17) NOT NULL,
  `id_so` int(11) NOT NULL,
  `cs_tipo_componente` varchar(100) NOT NULL,
  `te_valor` text NOT NULL,
  KEY `te_node_address` (`te_node_address`,`id_so`,`cs_tipo_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Componentes de hardware instalados nas estações';

--
-- Table structure for table `componentes_estacoes_historico`
--

CREATE TABLE `componentes_estacoes_historico` (
  `te_node_address` varchar(17) NOT NULL,
  `id_so` int(11) NOT NULL,
  `cs_tipo_componente` varchar(100) NOT NULL,
  `te_valor` varchar(200) NOT NULL,
  `dt_alteracao` datetime NOT NULL,
  `cs_tipo_alteracao` varchar(3) NOT NULL,
  KEY `te_node_address` (`te_node_address`,`id_so`,`cs_tipo_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii COMMENT='Componentes de hardware instalados nas estaÃ§Ãµes';

--
-- Table structure for table `computadores`
--

CREATE TABLE `computadores` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) NOT NULL default '0',
  `te_so` varchar(50) default NULL,
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
  `te_mem_ram_desc` varchar(200) default NULL,
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
-- Table structure for table `configuracoes_locais`
--

CREATE TABLE `configuracoes_locais` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `te_notificar_mudanca_hardware` text,
  `in_exibe_erros_criticos` char(1) default 'N',
  `in_exibe_bandeja` char(1) default 'S',
  `nu_exec_apos` int(11) default '10',
  `dt_hr_alteracao_patrim_interface` datetime default NULL,
  `dt_hr_alteracao_patrim_uon1` datetime default '0000-00-00 00:00:00',
  `dt_hr_alteracao_patrim_uon1a` datetime default '0000-00-00 00:00:00',
  `dt_hr_alteracao_patrim_uon2` datetime default '0000-00-00 00:00:00',
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
  `te_exibe_graficos` varchar(100) NOT NULL default '[acessos_locais][so][acessos][locais]',
  PRIMARY KEY  (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `configuracoes_padrao`
--

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
  `id_default_body_bgcolor` varchar(10) NOT NULL default '#EBEBEB',
  `te_exibe_graficos` varchar(100) NOT NULL default '[acessos_locais][so][acessos][locais]'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `contas`
--

CREATE TABLE `contas` (
  `id_conta` int(10) unsigned NOT NULL auto_increment,
  `nm_responsavel` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id_conta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `descricao_hardware`
--

CREATE TABLE `descricao_hardware` (
  `nm_campo_tab_hardware` varchar(45) NOT NULL default '',
  `te_desc_hardware` varchar(45) NOT NULL default '',
  `te_locais_notificacao_ativada` text COMMENT 'Locais onde a notificação de alteração de hardware encontra-se ativa.',
  PRIMARY KEY  (`nm_campo_tab_hardware`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `descricoes_colunas_computadores`
--

CREATE TABLE `descricoes_colunas_computadores` (
  `nm_campo` varchar(100) NOT NULL default '',
  `te_descricao_campo` varchar(100) NOT NULL default '',
  `cs_condicao_pesquisa` char(1) NOT NULL default 'S',
  UNIQUE KEY `nm_campo` (`nm_campo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela para auxílio na opção Exclusão de Informações';

--
-- Table structure for table `grupo_usuarios`
--

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
-- Table structure for table `historico_hardware`
--

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
-- Table structure for table `historico_tcp_ip`
--

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
-- Table structure for table `historicos_hardware`
--

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
-- Table structure for table `historicos_outros_softwares`
--

CREATE TABLE `historicos_outros_softwares` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(10) unsigned NOT NULL default '0',
  `id_software_inventariado` int(10) unsigned NOT NULL default '0',
  `dt_hr_inclusao` datetime NOT NULL default '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `historicos_software`
--

CREATE TABLE `historicos_software` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) unsigned NOT NULL default '0',
  `id_software_inventariado` int(11) unsigned NOT NULL default '0',
  `dt_hr_inclusao` datetime NOT NULL default '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_software_inventariado`),
  KEY `id_software` (`id_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `historicos_software_completo`
--

CREATE TABLE `historicos_software_completo` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(10) unsigned NOT NULL default '0',
  `id_software_inventariado` int(10) unsigned NOT NULL default '0',
  `dt_hr_inclusao` datetime NOT NULL default '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_software_inventariado`,`dt_hr_inclusao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `insucessos_instalacao`
--

CREATE TABLE `insucessos_instalacao` (
  `te_ip` varchar(15) NOT NULL,
  `te_so` varchar(60) NOT NULL,
  `id_usuario` varchar(60) NOT NULL,
  `dt_datahora` datetime NOT NULL,
  `cs_indicador` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `locais`
--

CREATE TABLE `locais` (
  `id_local` int(11) unsigned NOT NULL auto_increment,
  `nm_local` varchar(100) NOT NULL default '',
  `sg_local` varchar(20) NOT NULL default '',
  `te_observacao` varchar(255) default NULL,
  PRIMARY KEY  (`id_local`),
  KEY `sg_localizacao` (`sg_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Localizações para regionalização de acesso a dados';

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `dt_acao` datetime NOT NULL default '0000-00-00 00:00:00',
  `cs_acao` varchar(20) NOT NULL default '',
  `nm_script` varchar(255) NOT NULL default '',
  `nm_tabela` varchar(255) NOT NULL default '',
  `id_usuario` int(11) NOT NULL default '0',
  `te_ip_origem` varchar(15) NOT NULL default ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log de Atividades no Sistema CACIC';

--
-- Table structure for table `officescan`
--

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
-- Table structure for table `patrimonio`
--

CREATE TABLE `patrimonio` (
  `id_unid_organizacional_nivel1a` int(11) NOT NULL,
  `id_so` int(11) NOT NULL default '0',
  `dt_hr_alteracao` datetime NOT NULL default '0000-00-00 00:00:00',
  `te_node_address` varchar(17) NOT NULL,
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
-- Table structure for table `patrimonio_config_interface`
--

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
-- Table structure for table `perfis_aplicativos_monitorados`
--

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
-- Table structure for table `redes`
--

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
-- Table structure for table `redes_grupos_ftp`
--

CREATE TABLE `redes_grupos_ftp` (
  `id_local` int(11) NOT NULL default '0',
  `id_ip_rede` varchar(15) NOT NULL default '0',
  `id_ip_estacao` varchar(15) NOT NULL default '0',
  `nu_hora_inicio` int(12) NOT NULL default '0',
  `nu_hora_fim` varchar(12) NOT NULL default '0',
  `id_ftp` int(11) NOT NULL auto_increment,
  PRIMARY KEY ( `id_ftp` )
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `redes_versoes_modulos`
--

CREATE TABLE `redes_versoes_modulos` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `id_ip_rede` varchar(15) NOT NULL default '',
  `nm_modulo` varchar(20) NOT NULL default '',
  `te_versao_modulo` varchar(20) default NULL,
  `dt_atualizacao` datetime NOT NULL,
  PRIMARY KEY  (`id_ip_rede`,`nm_modulo`,`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `so`
--

CREATE TABLE `so` (
  `id_so` int(11) NOT NULL default '0',
  `te_desc_so` varchar(50) default NULL,
  `sg_so` varchar(10) default NULL,
  `te_so` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id_so`,`te_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `softwares`
--

CREATE TABLE `softwares` (
  `id_software` int(10) unsigned NOT NULL auto_increment,
  `nm_software` varchar(150) default NULL,
  `te_descricao_software` varchar(255) default NULL,
  `qt_licenca` int(11) default '0',
  `nr_midia` varchar(10) default NULL,
  `te_local_midia` varchar(30) default NULL,
  `te_obs` varchar(200) default NULL,
  PRIMARY KEY  (`id_software`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `softwares_estacao`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `softwares_inventariados`
--

CREATE TABLE `softwares_inventariados` (
  `id_software_inventariado` int(10) unsigned NOT NULL auto_increment,
  `nm_software_inventariado` varchar(100) NOT NULL default '',
  `id_tipo_software` int(11) default '0',
  `id_software` int(10) unsigned default NULL,
  `te_hash` varchar(40) NOT NULL,
  PRIMARY KEY  (`id_software_inventariado`),
  KEY `nm_software_inventariado` (`nm_software_inventariado`),
  KEY `id_software` (`id_software_inventariado`),
  KEY `idx_nm_software_inventariado` (`nm_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `softwares_inventariados_estacoes`
--

CREATE TABLE `softwares_inventariados_estacoes` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(11) unsigned NOT NULL default '0',
  `id_software_inventariado` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_software_inventariado`),
  KEY `id_software` (`id_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tipos_licenca`
--

CREATE TABLE `tipos_licenca` (
  `id_tipo_licenca` int(10) unsigned NOT NULL auto_increment,
  `te_tipo_licenca` varchar(20) default NULL,
  PRIMARY KEY  (`id_tipo_licenca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tipos_software`
--

CREATE TABLE `tipos_software` (
  `id_tipo_software` int(10) unsigned NOT NULL default '0',
  `te_descricao_tipo_software` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id_tipo_software`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `tipos_unidades_disco`
--

CREATE TABLE `tipos_unidades_disco` (
  `id_tipo_unid_disco` char(1) NOT NULL default '',
  `te_tipo_unid_disco` varchar(25) default NULL,
  PRIMARY KEY  (`id_tipo_unid_disco`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `unid_organizacional_nivel1`
--

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
-- Table structure for table `unid_organizacional_nivel1a`
--

CREATE TABLE `unid_organizacional_nivel1a` (
  `id_unid_organizacional_nivel1` int(11) NOT NULL,
  `id_unid_organizacional_nivel1a` int(11) NOT NULL auto_increment,
  `nm_unid_organizacional_nivel1a` varchar(50) default NULL,
  PRIMARY KEY  (`id_unid_organizacional_nivel1a`)
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=latin1;

--
-- Table structure for table `unid_organizacional_nivel2`
--

CREATE TABLE `unid_organizacional_nivel2` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `id_unid_organizacional_nivel2` int(11) NOT NULL auto_increment,
  `id_unid_organizacional_nivel1a` int(11) NOT NULL default '0',
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
  PRIMARY KEY  (`id_unid_organizacional_nivel2`,`id_unid_organizacional_nivel1a`,`id_local`),
  KEY `id_localizacao` (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `unidades_disco`
--

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
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_local` int(11) unsigned NOT NULL default '0',
  `id_usuario` int(10) unsigned NOT NULL auto_increment,
  `nm_usuario_acesso` varchar(20) NOT NULL default '',
  `nm_usuario_completo` varchar(60) NOT NULL default '',
  `te_senha` varchar(60) NOT NULL default '',
  `dt_log_in` datetime NOT NULL default '0000-00-00 00:00:00',
  `id_grupo_usuarios` int(1) NOT NULL default '1',
  `te_emails_contato` varchar(100) default NULL,
  `te_telefones_contato` varchar(100) default NULL,
  `te_locais_secundarios` varchar(200) default NULL,
  PRIMARY KEY  (`id_usuario`),
  KEY `id_localizacao` (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `variaveis_ambiente`
--

CREATE TABLE `variaveis_ambiente` (
  `id_variavel_ambiente` int(10) unsigned NOT NULL auto_increment,
  `nm_variavel_ambiente` varchar(100) NOT NULL default '',
  `te_hash` varchar(40) NOT NULL,
  PRIMARY KEY  (`id_variavel_ambiente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `variaveis_ambiente_estacoes`
--

CREATE TABLE `variaveis_ambiente_estacoes` (
  `te_node_address` varchar(17) NOT NULL default '',
  `id_so` int(10) unsigned NOT NULL default '0',
  `id_variavel_ambiente` int(10) unsigned NOT NULL default '0',
  `vl_variavel_ambiente` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`te_node_address`,`id_so`,`id_variavel_ambiente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `versoes_softwares`
--

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

