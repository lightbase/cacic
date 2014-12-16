CREATE TABLE `acoes` (
  `id_acao` char(20) NOT NULL DEFAULT '',
  `te_descricao_breve` char(100) DEFAULT NULL,
  `te_descricao` text,
  `te_nome_curto_modulo` char(20) DEFAULT NULL,
  `dt_hr_alteracao` datetime DEFAULT '0000-00-00 00:00:00',
  `cs_situacao` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_acao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `acoes_excecoes` (
  `id_local` int(11) NOT NULL,
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_acao` char(20) NOT NULL DEFAULT '',
  `id_so` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `acoes_redes` (
  `id_acao` char(20) NOT NULL DEFAULT '',
  `id_local` int(11) NOT NULL DEFAULT '0',
  `id_ip_rede` char(15) NOT NULL DEFAULT '',
  `dt_hr_coleta_forcada` datetime DEFAULT NULL,
  `cs_situacao` char(1) NOT NULL DEFAULT 'T',
  `dt_hr_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`id_local`,`id_ip_rede`,`id_acao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `acoes_so` (
  `id_local` int(11) NOT NULL DEFAULT '0',
  `id_acao` char(20) NOT NULL DEFAULT '',
  `id_so` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_acao`,`id_so`,`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `aplicativos_monitorados` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(11) unsigned NOT NULL DEFAULT '0',
  `id_aplicativo` int(11) unsigned NOT NULL DEFAULT '0',
  `te_versao` char(50) DEFAULT NULL,
  `te_licenca` char(50) DEFAULT NULL,
  `te_ver_engine` char(50) DEFAULT NULL,
  `te_ver_pattern` char(50) DEFAULT NULL,
  `cs_instalado` char(1) DEFAULT NULL,
  PRIMARY KEY (`te_node_address`,`id_so`,`id_aplicativo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `aplicativos_redes` (
  `id_local` int(11) NOT NULL DEFAULT '0',
  `id_ip_rede` char(15) NOT NULL DEFAULT '',
  `id_aplicativo` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_local`,`id_ip_rede`,`id_aplicativo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relacionamento redes e perfis_aplicativos_monitorados';


CREATE TABLE `aquisicoes` (
  `id_aquisicao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dt_aquisicao` date DEFAULT NULL,
  `nr_processo` char(11) DEFAULT NULL,
  `nm_empresa` char(45) DEFAULT NULL,
  `nm_proprietario` char(45) DEFAULT NULL,
  `nr_notafiscal` char(20) DEFAULT NULL,
  PRIMARY KEY (`id_aquisicao`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


CREATE TABLE `aquisicoes_item` (
  `id_aquisicao` int(10) unsigned NOT NULL DEFAULT '0',
  `id_software` int(10) unsigned NOT NULL DEFAULT '0',
  `id_tipo_licenca` int(10) unsigned NOT NULL DEFAULT '0',
  `qt_licenca` int(11) DEFAULT NULL,
  `dt_vencimento_licenca` date DEFAULT NULL,
  `te_obs` char(50) DEFAULT NULL,
  PRIMARY KEY (`id_aquisicao`,`id_software`,`id_tipo_licenca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;


CREATE TABLE `compartilhamentos` (
  `nm_compartilhamento` char(30) NOT NULL DEFAULT '',
  `id_so` int(11) NOT NULL DEFAULT '0',
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `nm_dir_compart` char(100) DEFAULT NULL,
  `in_senha_escrita` char(1) DEFAULT NULL,
  `in_senha_leitura` char(1) DEFAULT NULL,
  `cs_tipo_permissao` char(1) DEFAULT NULL,
  `cs_tipo_compart` char(1) DEFAULT NULL,
  `te_comentario` char(50) DEFAULT NULL,
  PRIMARY KEY (`nm_compartilhamento`,`id_so`,`te_node_address`),
  KEY `node_so_tipocompart` (`te_node_address`,`id_so`,`cs_tipo_compart`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `componentes_estacoes` (
  `te_node_address` char(17) NOT NULL,
  `id_so` int(11) NOT NULL,
  `cs_tipo_componente` char(100) NOT NULL,
  `te_valor` text NOT NULL,
  KEY `te_node_address` (`te_node_address`,`id_so`,`cs_tipo_componente`),
  KEY `idxComponentesEstacoes_IdSO_TeNodeAddress` (`id_so`,`te_node_address`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Componentes de hardware instalados nas estações';


CREATE TABLE `componentes_estacoes_historico` (
  `te_node_address` char(17) CHARACTER SET latin1 NOT NULL,
  `id_so` int(11) NOT NULL,
  `cs_tipo_componente` char(100) CHARACTER SET latin1 NOT NULL,
  `te_valor` char(200) CHARACTER SET latin1 NOT NULL,
  `dt_alteracao` datetime NOT NULL,
  `cs_tipo_alteracao` char(3) CHARACTER SET latin1 NOT NULL,
  KEY `te_node_address` (`te_node_address`,`id_so`,`cs_tipo_componente`),
  KEY `idxComponentesEstacoesHistorico_IdSO_TeNodeAddress` (`id_so`,`te_node_address`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii COMMENT='Componentes de hardware instalados nas estações';


CREATE TABLE `computadores` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(11) NOT NULL DEFAULT '0',
  `te_so` char(50) DEFAULT NULL,
  `te_nome_computador` char(50) DEFAULT NULL,
  `id_ip_rede` char(15) NOT NULL DEFAULT '',
  `te_dominio_windows` char(50) DEFAULT NULL,
  `te_dominio_dns` char(50) DEFAULT NULL,
  `te_placa_video_desc` char(100) DEFAULT NULL,
  `te_ip` char(15) DEFAULT NULL,
  `te_mascara` char(15) DEFAULT NULL,
  `te_nome_host` char(50) DEFAULT NULL,
  `te_placa_rede_desc` char(100) DEFAULT NULL,
  `dt_hr_inclusao` datetime DEFAULT NULL,
  `te_gateway` char(15) DEFAULT NULL,
  `te_wins_primario` char(15) DEFAULT NULL,
  `te_cpu_desc` char(100) DEFAULT NULL,
  `te_wins_secundario` char(15) DEFAULT NULL,
  `te_dns_primario` char(15) DEFAULT NULL,
  `qt_placa_video_mem` int(11) DEFAULT NULL,
  `te_dns_secundario` char(15) DEFAULT NULL,
  `te_placa_mae_desc` char(100) DEFAULT NULL,
  `te_serv_dhcp` char(15) DEFAULT NULL,
  `qt_mem_ram` int(11) DEFAULT NULL,
  `te_cpu_serial` char(50) DEFAULT NULL,
  `te_cpu_fabricante` char(100) DEFAULT NULL,
  `te_cpu_freq` char(6) DEFAULT NULL,
  `te_mem_ram_desc` char(200) DEFAULT NULL,
  `te_bios_desc` char(100) DEFAULT NULL,
  `te_bios_data` char(10) DEFAULT NULL,
  `dt_hr_ult_acesso` datetime DEFAULT NULL,
  `te_versao_cacic` char(10) DEFAULT NULL,
  `te_versao_gercols` char(10) DEFAULT NULL,
  `te_bios_fabricante` char(100) DEFAULT NULL,
  `te_palavra_chave` char(30) NOT NULL DEFAULT 'abcdefghij',
  `te_placa_mae_fabricante` char(100) DEFAULT NULL,
  `qt_placa_video_cores` int(11) DEFAULT NULL,
  `te_placa_video_resolucao` char(10) DEFAULT NULL,
  `te_placa_som_desc` char(100) DEFAULT NULL,
  `te_cdrom_desc` char(100) DEFAULT NULL,
  `te_teclado_desc` char(100) NOT NULL DEFAULT '',
  `te_mouse_desc` char(100) DEFAULT NULL,
  `te_modem_desc` char(100) DEFAULT NULL,
  `te_workgroup` char(50) DEFAULT NULL,
  `dt_hr_coleta_forcada_estacao` datetime DEFAULT NULL,
  `te_nomes_curtos_modulos` char(255) DEFAULT NULL,
  `te_origem_mac` text,
  `id_conta` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`te_node_address`,`id_so`),
  KEY `computadores_versao_cacic` (`te_versao_cacic`),
  KEY `te_ip` (`te_ip`),
  KEY `te_node_address` (`te_node_address`),
  KEY `te_nome_computador` (`te_nome_computador`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `configuracoes_locais` (
  `id_local` int(11) unsigned NOT NULL DEFAULT '0',
  `te_notificar_mudanca_hardware` text,
  `te_notificar_utilizacao_usb` text,
  `in_exibe_erros_criticos` char(1) DEFAULT 'N',
  `in_exibe_bandeja` char(1) DEFAULT 'S',
  `nu_exec_apos` int(11) DEFAULT '10',
  `dt_hr_alteracao_patrim_interface` datetime DEFAULT NULL,
  `dt_hr_alteracao_patrim_uon1` datetime DEFAULT '0000-00-00 00:00:00',
  `dt_hr_alteracao_patrim_uon1a` datetime DEFAULT '0000-00-00 00:00:00',
  `dt_hr_alteracao_patrim_uon2` datetime DEFAULT '0000-00-00 00:00:00',
  `dt_hr_coleta_forcada` datetime DEFAULT NULL,
  `te_notificar_mudanca_patrim` text,
  `nm_organizacao` char(150) DEFAULT NULL,
  `nu_timeout_srcacic` tinyint(3) NOT NULL DEFAULT '30' COMMENT 'Valor para timeout do servidor srCACIC',
  `nu_intervalo_exec` int(11) DEFAULT '4',
  `nu_intervalo_renovacao_patrim` int(11) DEFAULT '0',
  `te_senha_adm_agente` char(30) DEFAULT 'ADMINCACIC',
  `te_serv_updates_padrao` char(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `te_serv_cacic_padrao` char(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `te_enderecos_mac_invalidos` text,
  `te_janelas_excecao` text,
  `te_nota_email_gerentes` text,
  `cs_abre_janela_patr` char(1) NOT NULL DEFAULT 'N',
  `id_default_body_bgcolor` char(10) NOT NULL DEFAULT '#EBEBEB',
  `te_exibe_graficos` char(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]',
  `nu_porta_srcacic` char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '5900',
  `te_usb_filter` text NOT NULL,
  PRIMARY KEY (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `configuracoes_padrao` (
  `in_exibe_erros_criticos` char(1) DEFAULT NULL,
  `in_exibe_bandeja` char(1) DEFAULT NULL,
  `nu_exec_apos` int(11) DEFAULT NULL,
  `nu_rel_maxlinhas` smallint(5) unsigned DEFAULT '50',
  `nm_organizacao` char(150) DEFAULT NULL,
  `nu_timeout_srcacic` tinyint(3) NOT NULL DEFAULT '30' COMMENT 'Valor padrao para timeout do servidor srCACIC',
  `nu_intervalo_exec` int(11) DEFAULT NULL,
  `nu_intervalo_renovacao_patrim` int(11) DEFAULT NULL,
  `te_senha_adm_agente` char(30) DEFAULT NULL,
  `te_serv_updates_padrao` char(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `te_serv_cacic_padrao` char(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `te_enderecos_mac_invalidos` text,
  `te_janelas_excecao` text,
  `cs_abre_janela_patr` char(1) NOT NULL DEFAULT 'S',
  `id_default_body_bgcolor` char(10) NOT NULL DEFAULT '#EBEBEB',
  `te_exibe_graficos` char(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]',
  `nu_porta_srcacic` char(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '5900',
  `nu_resolucao_grafico_h` smallint(5) NOT NULL DEFAULT '0',
  `nu_resolucao_grafico_w` smallint(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `contas` (
  `id_conta` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nm_responsavel` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_conta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `descricao_hardware` (
  `nm_campo_tab_hardware` char(45) NOT NULL DEFAULT '',
  `te_desc_hardware` char(45) NOT NULL DEFAULT '',
  `te_locais_notificacao_ativada` text COMMENT 'Locais onde a notificação de alteração de hardware encontra-se ativa.',
  PRIMARY KEY (`nm_campo_tab_hardware`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `descricoes_colunas_computadores` (
  `nm_campo` char(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `te_descricao_campo` char(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `cs_condicao_pesquisa` char(1) CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  UNIQUE KEY `nm_campo` (`nm_campo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin COMMENT='Tabela para auxílio na opção Exclusão de Informações de comp';


CREATE TABLE `grupo_usuarios` (
  `id_grupo_usuarios` int(2) NOT NULL AUTO_INCREMENT,
  `te_grupo_usuarios` char(20) DEFAULT NULL,
  `te_menu_grupo` char(20) DEFAULT NULL,
  `te_descricao_grupo` text NOT NULL,
  `cs_nivel_administracao` tinyint(2) NOT NULL DEFAULT '0',
  `nm_grupo_usuarios` char(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_grupo_usuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;


CREATE TABLE `historico_hardware` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(11) NOT NULL DEFAULT '0',
  `dt_hr_alteracao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `te_placa_video_desc` char(100) DEFAULT NULL,
  `te_placa_rede_desc` char(100) DEFAULT NULL,
  `te_cpu_desc` char(100) DEFAULT NULL,
  `qt_placa_video_mem` int(11) DEFAULT NULL,
  `te_placa_mae_desc` char(100) DEFAULT NULL,
  `qt_mem_ram` int(11) DEFAULT NULL,
  `te_cpu_serial` char(50) DEFAULT NULL,
  `te_cpu_fabricante` char(100) DEFAULT NULL,
  `te_cpu_freq` char(6) DEFAULT NULL,
  `te_mem_ram_desc` char(100) DEFAULT NULL,
  `te_bios_desc` char(100) DEFAULT NULL,
  `te_bios_data` char(10) DEFAULT NULL,
  `te_bios_fabricante` char(100) DEFAULT NULL,
  `te_placa_mae_fabricante` char(100) DEFAULT NULL,
  `qt_placa_video_cores` int(11) DEFAULT NULL,
  `te_placa_video_resolucao` char(10) DEFAULT NULL,
  `te_placa_som_desc` char(100) DEFAULT NULL,
  `te_cdrom_desc` char(100) DEFAULT NULL,
  `te_teclado_desc` char(100) DEFAULT NULL,
  `te_mouse_desc` char(100) DEFAULT NULL,
  `te_modem_desc` char(100) DEFAULT NULL,
  `te_lic_win` char(50) DEFAULT NULL,
  `te_key_win` char(50) DEFAULT NULL,
  PRIMARY KEY (`te_node_address`,`id_so`,`dt_hr_alteracao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `historico_tcp_ip` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(11) NOT NULL DEFAULT '0',
  `dt_hr_alteracao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `te_nome_computador` char(25) DEFAULT NULL,
  `te_dominio_windows` char(30) DEFAULT NULL,
  `te_dominio_dns` char(30) DEFAULT NULL,
  `te_ip` char(15) DEFAULT NULL,
  `te_mascara` char(15) DEFAULT NULL,
  `id_ip_rede` char(15) DEFAULT NULL,
  `te_nome_host` char(15) DEFAULT NULL,
  `te_gateway` char(15) DEFAULT NULL,
  `te_wins_primario` char(15) DEFAULT NULL,
  `te_wins_secundario` char(15) DEFAULT NULL,
  `te_dns_primario` char(15) DEFAULT NULL,
  `te_dns_secundario` char(15) DEFAULT NULL,
  `te_serv_dhcp` char(15) DEFAULT NULL,
  `te_workgroup` char(20) DEFAULT NULL,
  PRIMARY KEY (`te_node_address`,`id_so`,`dt_hr_alteracao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `historicos_hardware` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(11) NOT NULL DEFAULT '0',
  `campo_alterado` char(45) DEFAULT '',
  `valor_antigo` char(45) DEFAULT '',
  `data_anterior` datetime DEFAULT '0000-00-00 00:00:00',
  `novo_valor` char(45) DEFAULT '',
  `nova_data` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`te_node_address`,`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `historicos_outros_softwares` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(10) unsigned NOT NULL DEFAULT '0',
  `id_software_inventariado` int(10) unsigned NOT NULL DEFAULT '0',
  `dt_hr_inclusao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`te_node_address`,`id_so`,`id_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `historicos_software` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(11) unsigned NOT NULL DEFAULT '0',
  `id_software_inventariado` int(11) unsigned NOT NULL DEFAULT '0',
  `dt_hr_inclusao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`te_node_address`,`id_so`,`id_software_inventariado`),
  KEY `id_software` (`id_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `historicos_software_completo` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(10) unsigned NOT NULL DEFAULT '0',
  `id_software_inventariado` int(10) unsigned NOT NULL DEFAULT '0',
  `dt_hr_inclusao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dt_hr_ult_coleta` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`te_node_address`,`id_so`,`id_software_inventariado`,`dt_hr_inclusao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `insucessos_instalacao` (
  `te_ip` char(15) NOT NULL,
  `te_so` char(60) NOT NULL,
  `id_usuario` char(60) NOT NULL,
  `dt_datahora` datetime NOT NULL,
  `cs_indicador` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `locais` (
  `id_local` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nm_local` char(100) NOT NULL DEFAULT '',
  `sg_local` char(20) NOT NULL DEFAULT '',
  `te_observacao` char(255) DEFAULT NULL,
  PRIMARY KEY (`id_local`),
  KEY `sg_localizacao` (`sg_local`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Localizações para regionalização de acesso a dados';


CREATE TABLE `log` (
  `dt_acao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cs_acao` char(20) NOT NULL DEFAULT '',
  `nm_script` char(255) NOT NULL DEFAULT '',
  `nm_tabela` char(255) NOT NULL DEFAULT '',
  `id_usuario` int(11) NOT NULL DEFAULT '0',
  `te_ip_origem` char(15) NOT NULL DEFAULT '',
  KEY `idxLog_DtAcao` (`dt_acao`),
  KEY `idxLog_CsAcao` (`cs_acao`),
  KEY `idxLog_IdUsuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log de Atividades no Sistema CACIC';


CREATE TABLE `officescan` (
  `nu_versao_engine` char(10) DEFAULT NULL,
  `nu_versao_pattern` char(10) DEFAULT NULL,
  `dt_hr_instalacao` datetime DEFAULT NULL,
  `dt_hr_coleta` datetime DEFAULT NULL,
  `te_servidor` char(30) DEFAULT NULL,
  `in_ativo` char(1) DEFAULT NULL,
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`te_node_address`,`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `patrimonio` (
  `id_unid_organizacional_nivel1a` int(11) NOT NULL,
  `id_so` int(11) NOT NULL DEFAULT '0',
  `dt_hr_alteracao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `te_node_address` char(17) NOT NULL,
  `id_unid_organizacional_nivel2` int(11) DEFAULT NULL,
  `te_localizacao_complementar` char(100) DEFAULT NULL,
  `te_info_patrimonio1` char(20) DEFAULT NULL,
  `te_info_patrimonio2` char(20) DEFAULT NULL,
  `te_info_patrimonio3` char(20) DEFAULT NULL,
  `te_info_patrimonio4` char(20) DEFAULT NULL,
  `te_info_patrimonio5` char(20) DEFAULT NULL,
  `te_info_patrimonio6` char(20) DEFAULT NULL,
  KEY `idxPatrimonio_DtHrAlteracao` (`dt_hr_alteracao`),
  KEY `te_node_address` (`te_node_address`,`id_so`),
  KEY `idxPatrimonio_IdUnidOrganizacionalNivel1a` (`id_unid_organizacional_nivel1a`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `patrimonio_config_interface` (
  `id_local` int(11) unsigned NOT NULL DEFAULT '0',
  `id_etiqueta` char(30) NOT NULL DEFAULT '',
  `nm_etiqueta` char(15) DEFAULT NULL,
  `te_etiqueta` char(50) NOT NULL DEFAULT '',
  `in_exibir_etiqueta` char(1) DEFAULT NULL,
  `te_help_etiqueta` char(100) DEFAULT NULL,
  `te_plural_etiqueta` char(50) DEFAULT NULL,
  `nm_campo_tab_patrimonio` char(50) DEFAULT NULL,
  `in_destacar_duplicidade` char(1) DEFAULT 'N',
  PRIMARY KEY (`id_etiqueta`,`id_local`),
  KEY `id_localizacao` (`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `perfis_aplicativos_monitorados` (
  `id_aplicativo` int(11) NOT NULL AUTO_INCREMENT,
  `nm_aplicativo` char(100) NOT NULL DEFAULT '',
  `cs_car_inst_w9x` char(2) DEFAULT NULL,
  `te_car_inst_w9x` char(255) DEFAULT NULL,
  `cs_car_ver_w9x` char(2) DEFAULT NULL,
  `te_car_ver_w9x` char(255) DEFAULT NULL,
  `cs_car_inst_wnt` char(2) DEFAULT NULL,
  `te_car_inst_wnt` char(255) DEFAULT NULL,
  `cs_car_ver_wnt` char(2) DEFAULT NULL,
  `te_car_ver_wnt` char(255) DEFAULT NULL,
  `cs_ide_licenca` char(2) DEFAULT NULL,
  `te_ide_licenca` char(255) DEFAULT NULL,
  `dt_atualizacao` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `te_arq_ver_eng_w9x` char(100) DEFAULT NULL,
  `te_arq_ver_pat_w9x` char(100) DEFAULT NULL,
  `te_arq_ver_eng_wnt` char(100) DEFAULT NULL,
  `te_arq_ver_pat_wnt` char(100) DEFAULT NULL,
  `te_dir_padrao_w9x` char(100) DEFAULT NULL,
  `te_dir_padrao_wnt` char(100) DEFAULT NULL,
  `id_so` int(11) DEFAULT '0',
  `te_descritivo` text,
  `in_disponibiliza_info` char(1) DEFAULT 'N',
  `in_disponibiliza_info_usuario_comum` char(1) NOT NULL DEFAULT 'N',
  `dt_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id_aplicativo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


CREATE TABLE `redes` (
  `id_local` int(11) unsigned NOT NULL DEFAULT '0',
  `id_servidor_autenticacao` int(11) DEFAULT '0',
  `id_ip_rede` char(15) NOT NULL DEFAULT '',
  `nm_rede` char(100) DEFAULT NULL,
  `te_observacao` char(100) DEFAULT NULL,
  `nm_pessoa_contato1` char(50) DEFAULT NULL,
  `nm_pessoa_contato2` char(50) DEFAULT NULL,
  `nu_telefone1` char(11) DEFAULT NULL,
  `te_email_contato2` char(50) DEFAULT NULL,
  `nu_telefone2` char(11) DEFAULT NULL,
  `te_email_contato1` char(50) DEFAULT NULL,
  `te_serv_cacic` char(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `te_serv_updates` char(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `te_path_serv_updates` char(255) DEFAULT NULL,
  `nm_usuario_login_serv_updates` char(20) DEFAULT NULL,
  `te_senha_login_serv_updates` char(20) DEFAULT NULL,
  `nu_porta_serv_updates` char(4) DEFAULT NULL,
  `te_mascara_rede` char(15) DEFAULT NULL,
  `dt_verifica_updates` date DEFAULT NULL,
  `nm_usuario_login_serv_updates_gerente` char(20) DEFAULT NULL,
  `te_senha_login_serv_updates_gerente` char(20) DEFAULT NULL,
  `nu_limite_ftp` int(5) unsigned NOT NULL DEFAULT '5',
  `cs_permitir_desativar_srcacic` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_ip_rede`,`id_local`),
  KEY `id_ip_rede` (`id_ip_rede`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `redes_grupos_ftp` (
  `id_local` int(11) NOT NULL DEFAULT '0',
  `id_ip_rede` char(15) NOT NULL DEFAULT '0',
  `id_ip_estacao` char(15) NOT NULL DEFAULT '0',
  `nu_hora_inicio` int(12) NOT NULL DEFAULT '0',
  `nu_hora_fim` char(12) NOT NULL DEFAULT '0',
  `id_ftp` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_ftp`),
  KEY `idxRedesGruposFtp_IdLocal_IdIpRede_IdIpEstacao` (`id_local`,`id_ip_rede`,`id_ip_estacao`),
  KEY `idxRedesGruposFtp_IdIpRede_IdLocal` (`id_ip_rede`,`id_local`)
) ENGINE=InnoDB AUTO_INCREMENT=244178 DEFAULT CHARSET=latin1;


CREATE TABLE `redes_versoes_modulos` (
  `id_local` int(11) unsigned NOT NULL DEFAULT '0',
  `id_ip_rede` char(15) NOT NULL DEFAULT '',
  `nm_modulo` char(100) NOT NULL,
  `te_versao_modulo` char(20) DEFAULT NULL,
  `dt_atualizacao` datetime NOT NULL,
  `cs_tipo_so` char(20) NOT NULL DEFAULT 'MS-Windows',
  `te_hash` char(40) DEFAULT 'a',
  PRIMARY KEY (`id_ip_rede`,`nm_modulo`,`id_local`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `servidores_autenticacao` (
  `id_servidor_autenticacao` int(11) NOT NULL AUTO_INCREMENT,
  `nm_servidor_autenticacao` char(60) NOT NULL,
  `te_ip_servidor_autenticacao` char(15) NOT NULL,
  `nu_porta_servidor_autenticacao` char(5) NOT NULL,
  `id_tipo_protocolo` char(20) NOT NULL,
  `nu_versao_protocolo` char(10) NOT NULL,
  `te_atributo_identificador` char(100) NOT NULL,
  `te_atributo_retorna_nome` char(100) NOT NULL,
  `te_atributo_retorna_email` char(100) NOT NULL,
  `te_observacao` text NOT NULL,
  `in_ativo` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_servidor_autenticacao`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Servidores para Autenticação do srCACIC';


CREATE TABLE `so` (
  `id_so` int(11) NOT NULL DEFAULT '0',
  `te_desc_so` char(50) DEFAULT NULL,
  `sg_so` char(20) DEFAULT NULL,
  `te_so` char(50) NOT NULL DEFAULT '',
  `in_mswindows` char(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`id_so`,`te_so`),
  KEY `idxSo_TeSo` (`te_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `softwares` (
  `id_software` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nm_software` char(150) DEFAULT NULL,
  `te_descricao_software` char(255) DEFAULT NULL,
  `qt_licenca` int(11) DEFAULT '0',
  `nr_midia` char(10) DEFAULT NULL,
  `te_local_midia` char(30) DEFAULT NULL,
  `te_obs` char(200) DEFAULT NULL,
  PRIMARY KEY (`id_software`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;


CREATE TABLE `softwares_estacao` (
  `nr_patrimonio` char(20) NOT NULL DEFAULT '',
  `id_software` int(10) unsigned NOT NULL DEFAULT '0',
  `nm_computador` char(50) DEFAULT NULL,
  `dt_autorizacao` date DEFAULT NULL,
  `nr_processo` char(11) DEFAULT NULL,
  `dt_expiracao_instalacao` date DEFAULT NULL,
  `id_aquisicao_particular` int(10) unsigned DEFAULT NULL,
  `dt_desinstalacao` date DEFAULT NULL,
  `te_observacao` char(90) DEFAULT NULL,
  `nr_patr_destino` char(20) DEFAULT NULL,
  PRIMARY KEY (`nr_patrimonio`,`id_software`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `softwares_inventariados` (
  `id_software_inventariado` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nm_software_inventariado` char(100) NOT NULL DEFAULT '',
  `id_tipo_software` int(11) DEFAULT '0',
  `id_software` int(10) unsigned DEFAULT NULL,
  `te_hash` char(40) NOT NULL,
  PRIMARY KEY (`id_software_inventariado`),
  KEY `nm_software_inventariado` (`nm_software_inventariado`),
  KEY `id_software` (`id_software_inventariado`),
  KEY `idx_nm_software_inventariado` (`nm_software_inventariado`),
  KEY `idxSoftwaresInventariados_TeHash` (`te_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=13212 DEFAULT CHARSET=latin1;


CREATE TABLE `softwares_inventariados_estacoes` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(11) unsigned NOT NULL DEFAULT '0',
  `id_software_inventariado` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`te_node_address`,`id_so`,`id_software_inventariado`),
  KEY `id_software` (`id_software_inventariado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `srcacic_chats` (
  `id_conexao` int(11) NOT NULL,
  `dt_hr_mensagem` datetime NOT NULL,
  `te_mensagem` text CHARACTER SET utf8 NOT NULL,
  `cs_origem` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'cli',
  KEY `id_conexao` (`id_conexao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log de Atividades no Sistema CACIC';


CREATE TABLE `srcacic_conexoes` (
  `id_conexao` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador da conexão',
  `id_sessao` int(11) NOT NULL,
  `id_usuario_cli` int(11) NOT NULL DEFAULT '0',
  `te_node_address_cli` char(17) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `te_documento_referencial` char(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_so_cli` int(11) NOT NULL,
  `te_motivo_conexao` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descritivo breve sobre o motivo da conexão',
  `dt_hr_inicio_conexao` datetime NOT NULL,
  `dt_hr_ultimo_contato` datetime NOT NULL,
  PRIMARY KEY (`id_conexao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Registros de Conexões efetuadas em sessões abertas';


CREATE TABLE `srcacic_sessoes` (
  `id_sessao` int(11) NOT NULL AUTO_INCREMENT,
  `dt_hr_inicio_sessao` datetime NOT NULL,
  `nm_acesso_usuario_srv` char(30) CHARACTER SET utf8 NOT NULL,
  `nm_completo_usuario_srv` char(100) NOT NULL DEFAULT 'NoNoNo',
  `te_email_usuario_srv` char(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `te_node_address_srv` char(17) CHARACTER SET utf8 NOT NULL,
  `id_so_srv` int(11) NOT NULL,
  `dt_hr_ultimo_contato` datetime DEFAULT NULL,
  PRIMARY KEY (`id_sessao`),
  KEY `idx_dtHrInicioSessao` (`dt_hr_inicio_sessao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log de Atividades no Sistema CACIC';


CREATE TABLE `srcacic_transfs` (
  `id_conexao` int(11) NOT NULL,
  `dt_systemtime` datetime NOT NULL,
  `nu_duracao` double NOT NULL,
  `te_path_origem` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NoNoNo',
  `te_path_destino` char(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nm_arquivo` char(127) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nu_tamanho_arquivo` int(11) NOT NULL,
  `cs_status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `cs_operacao` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'D',
  KEY `id_conexao` (`id_conexao`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Log de Atividades no Sistema CACIC';


CREATE TABLE `testes` (
  `id_transacao` int(11) NOT NULL AUTO_INCREMENT,
  `te_linha` text,
  PRIMARY KEY (`id_transacao`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


CREATE TABLE `tipos_licenca` (
  `id_tipo_licenca` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `te_tipo_licenca` char(20) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_licenca`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


CREATE TABLE `tipos_software` (
  `id_tipo_software` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `te_descricao_tipo_software` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_tipo_software`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;


CREATE TABLE `tipos_unidades_disco` (
  `id_tipo_unid_disco` char(1) NOT NULL DEFAULT '',
  `te_tipo_unid_disco` char(25) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_unid_disco`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `unid_organizacional_nivel1` (
  `id_unid_organizacional_nivel1` int(11) NOT NULL AUTO_INCREMENT,
  `nm_unid_organizacional_nivel1` char(50) DEFAULT NULL,
  `te_endereco_uon1` char(80) DEFAULT NULL,
  `te_bairro_uon1` char(30) DEFAULT NULL,
  `te_cidade_uon1` char(50) DEFAULT NULL,
  `te_uf_uon1` char(2) DEFAULT NULL,
  `nm_responsavel_uon1` char(80) DEFAULT NULL,
  `te_email_responsavel_uon1` char(50) DEFAULT NULL,
  `nu_tel1_responsavel_uon1` char(10) DEFAULT NULL,
  `nu_tel2_responsavel_uon1` char(10) DEFAULT NULL,
  PRIMARY KEY (`id_unid_organizacional_nivel1`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


CREATE TABLE `unid_organizacional_nivel1a` (
  `id_unid_organizacional_nivel1` int(11) NOT NULL,
  `id_unid_organizacional_nivel1a` int(11) NOT NULL AUTO_INCREMENT,
  `nm_unid_organizacional_nivel1a` char(50) DEFAULT NULL,
  PRIMARY KEY (`id_unid_organizacional_nivel1a`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;


CREATE TABLE `unid_organizacional_nivel2` (
  `id_local` int(11) unsigned NOT NULL DEFAULT '0',
  `id_unid_organizacional_nivel2` int(11) NOT NULL AUTO_INCREMENT,
  `id_unid_organizacional_nivel1a` int(11) NOT NULL DEFAULT '0',
  `nm_unid_organizacional_nivel2` char(50) NOT NULL DEFAULT '',
  `te_endereco_uon2` char(80) DEFAULT NULL,
  `te_bairro_uon2` char(30) DEFAULT NULL,
  `te_cidade_uon2` char(50) DEFAULT NULL,
  `te_uf_uon2` char(2) DEFAULT NULL,
  `nm_responsavel_uon2` char(80) DEFAULT NULL,
  `te_email_responsavel_uon2` char(50) DEFAULT NULL,
  `nu_tel1_responsavel_uon2` char(10) DEFAULT NULL,
  `nu_tel2_responsavel_uon2` char(10) DEFAULT NULL,
  `dt_registro` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_unid_organizacional_nivel2`,`id_unid_organizacional_nivel1a`,`id_local`),
  KEY `id_localizacao` (`id_local`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;


CREATE TABLE `unidades_disco` (
  `te_letra` char(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_so` int(11) NOT NULL DEFAULT '0',
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_tipo_unid_disco` char(1) DEFAULT NULL,
  `nu_serial` char(12) DEFAULT NULL,
  `nu_capacidade` int(10) unsigned DEFAULT NULL,
  `nu_espaco_livre` int(10) unsigned DEFAULT NULL,
  `te_unc` char(60) DEFAULT NULL,
  `cs_sist_arq` char(10) DEFAULT NULL,
  PRIMARY KEY (`te_letra`,`id_so`,`te_node_address`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `usb_devices` (
  `id_vendor` char(5) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `id_device` char(5) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `nm_device` char(127) COLLATE utf8_unicode_ci NOT NULL,
  `te_observacao` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `idxUSBDevices_idVendor` (`id_vendor`),
  KEY `idxUSBDevices_idDevice` (`id_device`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `usb_logs` (
  `id_vendor` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `id_device` char(5) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `dt_event` char(14) COLLATE utf8_unicode_ci NOT NULL,
  `te_node_address` char(17) COLLATE utf8_unicode_ci NOT NULL,
  `id_so` int(11) NOT NULL,
  `cs_event` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'I',
  KEY `idxUSBLogs_dtEvent` (`dt_event`),
  KEY `idxUSBLogs_idVendor_idDevice` (`id_vendor`,`id_device`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `usb_vendors` (
  `id_vendor` char(5) COLLATE latin1_general_ci NOT NULL,
  `nm_vendor` char(127) COLLATE latin1_general_ci NOT NULL,
  `te_observacao` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `idxUSBVendors_idVendor` (`id_vendor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


CREATE TABLE `usuarios` (
  `id_local` int(11) unsigned NOT NULL DEFAULT '0',
  `id_servidor_autenticacao` int(11) DEFAULT NULL,
  `id_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nm_usuario_acesso` char(20) NOT NULL DEFAULT '',
  `nm_usuario_completo` char(60) NOT NULL DEFAULT '',
  `te_senha` char(60) NOT NULL DEFAULT '',
  `dt_log_in` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_grupo_usuarios` int(1) NOT NULL DEFAULT '1',
  `te_emails_contato` char(100) DEFAULT NULL,
  `te_telefones_contato` char(100) DEFAULT NULL,
  `te_locais_secundarios` text,
  PRIMARY KEY (`id_usuario`),
  KEY `id_localizacao` (`id_local`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;


CREATE TABLE `variaveis_ambiente` (
  `id_variavel_ambiente` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nm_variavel_ambiente` char(100) NOT NULL DEFAULT '',
  `te_hash` char(40) NOT NULL,
  PRIMARY KEY (`id_variavel_ambiente`),
  KEY `idxVariaveisAmbiente_TeHash` (`te_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=latin1;


CREATE TABLE `variaveis_ambiente_estacoes` (
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `id_so` int(10) unsigned NOT NULL DEFAULT '0',
  `id_variavel_ambiente` int(10) unsigned NOT NULL DEFAULT '0',
  `vl_variavel_ambiente` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`te_node_address`,`id_so`,`id_variavel_ambiente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `versoes_softwares` (
  `id_so` int(11) NOT NULL DEFAULT '0',
  `te_node_address` char(17) NOT NULL DEFAULT '',
  `te_versao_bde` char(10) DEFAULT NULL,
  `te_versao_dao` char(5) DEFAULT NULL,
  `te_versao_ado` char(5) DEFAULT NULL,
  `te_versao_odbc` char(15) DEFAULT NULL,
  `te_versao_directx` int(10) unsigned DEFAULT NULL,
  `te_versao_acrobat_reader` char(10) DEFAULT NULL,
  `te_versao_ie` char(18) DEFAULT NULL,
  `te_versao_mozilla` char(12) DEFAULT NULL,
  `te_versao_jre` char(6) DEFAULT NULL,
  PRIMARY KEY (`te_node_address`,`id_so`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;