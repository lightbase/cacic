-- phpMyAdmin SQL Dump
-- version 3.4.4-rc1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 26/10/2012 às 00h59min
-- Versão do Servidor: 5.0.51
-- Versão do PHP: 5.2.6-1+lenny16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "-03:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: 'cacic3'
--

-- --------------------------------------------------------

--
-- Estrutura da tabela 'acoes'
--

CREATE TABLE IF NOT EXISTS acoes (
  id_acao char(20) NOT NULL default '',
  te_descricao_breve char(100) default NULL,
  te_descricao text,
  te_nome_curto_modulo char(20) default NULL,
  dt_hr_alteracao datetime default '0000-00-00 00:00:00',
  cs_situacao char(1) default NULL,
  PRIMARY KEY  (id_acao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'acoes_excecoes'
--

CREATE TABLE IF NOT EXISTS acoes_excecoes (
  id_local int(11) NOT NULL,
  te_node_address char(17) NOT NULL default '',
  id_acao char(20) NOT NULL default '',
  id_so int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'acoes_redes'
--

CREATE TABLE IF NOT EXISTS acoes_redes (
  id_acao char(20) NOT NULL default '',
  id_local int(11) NOT NULL default '0',
  id_ip_rede char(15) NOT NULL default '',
  dt_hr_coleta_forcada datetime default NULL,
  cs_situacao char(1) NOT NULL default 'T',
  dt_hr_alteracao datetime default NULL,
  PRIMARY KEY  (id_local,id_ip_rede,id_acao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'acoes_so'
--

CREATE TABLE IF NOT EXISTS acoes_so (
  id_local int(11) NOT NULL default '0',
  id_acao char(20) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  PRIMARY KEY  (id_acao,id_so,id_local)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'aplicativos_monitorados'
--

CREATE TABLE IF NOT EXISTS aplicativos_monitorados (
  te_node_address char(17) NOT NULL default '',
  id_so int(11) unsigned NOT NULL default '0',
  id_aplicativo int(11) unsigned NOT NULL default '0',
  te_versao char(50) default NULL,
  te_licenca char(50) default NULL,
  te_ver_engine char(50) default NULL,
  te_ver_pattern char(50) default NULL,
  cs_instalado char(1) default NULL,
  PRIMARY KEY  (te_node_address,id_so,id_aplicativo)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'aplicativos_redes'
--

CREATE TABLE IF NOT EXISTS aplicativos_redes (
  id_local int(11) NOT NULL default '0',
  id_ip_rede char(15) NOT NULL default '',
  id_aplicativo int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (id_local,id_ip_rede,id_aplicativo)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relacionamento redes e perfis_aplicativos_monitorados';

-- --------------------------------------------------------

--
-- Estrutura da tabela 'aquisicoes'
--

CREATE TABLE IF NOT EXISTS aquisicoes (
  id_aquisicao int(10) unsigned NOT NULL auto_increment,
  dt_aquisicao date default NULL,
  nr_processo char(11) default NULL,
  nm_empresa char(45) default NULL,
  nm_proprietario char(45) default NULL,
  nr_notafiscal char(20) default NULL,
  PRIMARY KEY  (id_aquisicao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'aquisicoes_item'
--

CREATE TABLE IF NOT EXISTS aquisicoes_item (
  id_aquisicao int(10) unsigned NOT NULL default '0',
  id_software int(10) unsigned NOT NULL default '0',
  id_tipo_licenca int(10) unsigned NOT NULL default '0',
  qt_licenca int(11) default NULL,
  dt_vencimento_licenca date default NULL,
  te_obs char(50) default NULL,
  PRIMARY KEY  (id_aquisicao,id_software,id_tipo_licenca)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'compartilhamentos'
--

CREATE TABLE IF NOT EXISTS compartilhamentos (
  nm_compartilhamento char(30) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  te_node_address char(17) NOT NULL default '',
  nm_dir_compart char(100) default NULL,
  in_senha_escrita char(1) default NULL,
  in_senha_leitura char(1) default NULL,
  cs_tipo_permissao char(1) default NULL,
  cs_tipo_compart char(1) default NULL,
  te_comentario char(50) default NULL,
  PRIMARY KEY  (nm_compartilhamento,id_so,te_node_address),
  KEY node_so_tipocompart (te_node_address,id_so,cs_tipo_compart)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'componentes_estacoes'
--

CREATE TABLE IF NOT EXISTS componentes_estacoes (
  te_node_address char(17) NOT NULL default '',
  id_so int(11) NOT NULL,
  cs_tipo_componente char(100) NOT NULL default '',
  te_valor text NOT NULL,
  KEY te_node_address (te_node_address,id_so,cs_tipo_componente),
  KEY idxComponentesEstacoes_IdSO_TeNodeAddress (id_so,te_node_address)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Componentes de hardware instalados nas estaÃƒÂ§ÃƒÂµes';

-- --------------------------------------------------------

--
-- Estrutura da tabela 'componentes_estacoes_historico'
--

CREATE TABLE IF NOT EXISTS componentes_estacoes_historico (
  te_node_address char(17) character set latin1 NOT NULL default '',
  id_so int(11) NOT NULL,
  cs_tipo_componente char(100) character set latin1 NOT NULL default '',
  te_valor char(200) character set latin1 NOT NULL default '',
  dt_alteracao datetime NOT NULL,
  cs_tipo_alteracao char(3) character set latin1 NOT NULL default '',
  KEY te_node_address (te_node_address,id_so,cs_tipo_componente),
  KEY idxComponentesEstacoesHistorico_IdSO_TeNodeAddress (id_so,te_node_address)
) ENGINE=InnoDB DEFAULT CHARSET=ascii COMMENT='Componentes de hardware instalados nas estaÃƒÂ§ÃƒÂµes';

-- --------------------------------------------------------

--
-- Estrutura da tabela 'computadores'
--

CREATE TABLE IF NOT EXISTS computadores (
  te_node_address char(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  te_so char(50) default NULL,
  te_nome_computador char(50) default NULL,
  id_ip_rede char(15) NOT NULL default '',
  te_dominio_windows char(50) default NULL,
  te_dominio_dns char(50) default NULL,
  te_placa_video_desc char(100) default NULL,
  te_ip char(15) default NULL,
  te_mascara char(15) default NULL,
  te_nome_host char(50) default NULL,
  te_placa_rede_desc char(100) default NULL,
  dt_hr_inclusao datetime default NULL,
  te_gateway char(15) default NULL,
  te_wins_primario char(15) default NULL,
  te_cpu_desc char(100) default NULL,
  te_wins_secundario char(15) default NULL,
  te_dns_primario char(15) default NULL,
  qt_placa_video_mem int(11) default NULL,
  te_dns_secundario char(15) default NULL,
  te_placa_mae_desc char(100) default NULL,
  te_serv_dhcp char(15) default NULL,
  qt_mem_ram int(11) default NULL,
  te_cpu_serial char(50) default NULL,
  te_cpu_fabricante char(100) default NULL,
  te_cpu_freq char(6) default NULL,
  te_mem_ram_desc varchar(512) default NULL,
  te_bios_desc char(100) default NULL,
  te_bios_data char(10) default NULL,
  dt_hr_ult_acesso datetime default NULL,
  te_versao_cacic char(15) default NULL,
  te_versao_gercols char(15) default NULL,
  te_bios_fabricante char(100) default NULL,
  te_palavra_chave char(30) NOT NULL default 'abcdefghij',
  te_placa_mae_fabricante char(100) default NULL,
  qt_placa_video_cores int(11) default NULL,
  te_placa_video_resolucao char(50) default NULL,
  te_placa_som_desc char(100) default NULL,
  te_cdrom_desc char(100) default NULL,
  te_teclado_desc char(100) NOT NULL default '',
  te_mouse_desc char(100) default NULL,
  te_modem_desc char(100) default NULL,
  te_workgroup char(50) default NULL,
  dt_hr_coleta_forcada_estacao datetime default NULL,
  te_nomes_curtos_modulos char(255) default NULL,
  te_origem_mac text,
  id_conta int(10) unsigned default NULL,
  PRIMARY KEY  (te_node_address,id_so),
  KEY computadores_versao_cacic (te_versao_cacic),
  KEY te_ip (te_ip),
  KEY te_node_address (te_node_address),
  KEY te_nome_computador (te_nome_computador)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'configuracoes_locais'
--

CREATE TABLE IF NOT EXISTS configuracoes_locais (
  id_local int(11) unsigned NOT NULL default '0',
  te_notificar_mudanca_hardware text,
  te_notificar_utilizacao_usb text,
  in_exibe_erros_criticos char(1) default 'N',
  in_exibe_bandeja char(1) default 'S',
  nu_exec_apos int(11) default '10',
  dt_hr_alteracao_patrim_interface datetime default NULL,
  dt_hr_alteracao_patrim_uon1 datetime default '0000-00-00 00:00:00',
  dt_hr_alteracao_patrim_uon1a datetime default '0000-00-00 00:00:00',
  dt_hr_alteracao_patrim_uon2 datetime default '0000-00-00 00:00:00',
  dt_hr_coleta_forcada datetime default NULL,
  te_notificar_mudanca_patrim text,
  nm_organizacao char(150) default NULL,
  nu_timeout_srcacic tinyint(3) NOT NULL default '30' COMMENT 'Valor para timeout do servidor srCACIC',
  nu_intervalo_exec int(11) default '4',
  nu_intervalo_renovacao_patrim int(11) default '0',
  te_senha_adm_agente char(30) default 'ADMINCACIC',
  te_serv_updates_padrao char(60) character set utf8 collate utf8_unicode_ci default NULL,
  te_serv_cacic_padrao char(60) character set utf8 collate utf8_unicode_ci default NULL,
  te_enderecos_mac_invalidos text,
  te_janelas_excecao text,
  te_nota_email_gerentes text,
  cs_abre_janela_patr char(1) NOT NULL default 'N',
  id_default_body_bgcolor char(10) NOT NULL default '#EBEBEB',
  te_exibe_graficos char(100) NOT NULL default '[acessos_locais][so][acessos][locais]',
  nu_porta_srcacic char(5) character set utf8 collate utf8_unicode_ci NOT NULL default '5900',
  te_usb_filter text NOT NULL,
  PRIMARY KEY  (id_local)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'configuracoes_padrao'
--

CREATE TABLE IF NOT EXISTS configuracoes_padrao (
  in_exibe_erros_criticos char(1) default NULL,
  in_exibe_bandeja char(1) default NULL,
  nu_exec_apos int(11) default NULL,
  nu_rel_maxlinhas smallint(5) unsigned default '50',
  nm_organizacao char(150) default NULL,
  nu_timeout_srcacic tinyint(3) NOT NULL default '30' COMMENT 'Valor padrao para timeout do servidor srCACIC',
  nu_intervalo_exec int(11) default NULL,
  nu_intervalo_renovacao_patrim int(11) default NULL,
  te_senha_adm_agente char(30) default NULL,
  te_serv_updates_padrao char(60) character set utf8 collate utf8_unicode_ci default NULL,
  te_serv_cacic_padrao char(60) character set utf8 collate utf8_unicode_ci default NULL,
  te_enderecos_mac_invalidos text,
  te_janelas_excecao text,
  cs_abre_janela_patr char(1) NOT NULL default 'S',
  id_default_body_bgcolor char(10) NOT NULL default '#EBEBEB',
  te_exibe_graficos char(100) NOT NULL default '[acessos_locais][so][acessos][locais]',
  nu_porta_srcacic char(5) character set utf8 collate utf8_unicode_ci NOT NULL default '5900',
  nu_resolucao_grafico_h smallint(5) NOT NULL default '0',
  nu_resolucao_grafico_w smallint(5) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'contas'
--

CREATE TABLE IF NOT EXISTS contas (
  id_conta int(10) unsigned NOT NULL auto_increment,
  nm_responsavel char(30) NOT NULL default '',
  PRIMARY KEY  (id_conta)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'descricao_hardware'
--

CREATE TABLE IF NOT EXISTS descricao_hardware (
  nm_campo_tab_hardware char(45) NOT NULL default '',
  te_desc_hardware char(45) NOT NULL default '',
  te_locais_notificacao_ativada text COMMENT 'Locais onde a notificaÃ§Ã£o de alteraÃ§Ã£o de hardware encontra-se ativa.',
  PRIMARY KEY  (nm_campo_tab_hardware)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'descricoes_colunas_computadores'
--

CREATE TABLE IF NOT EXISTS descricoes_colunas_computadores (
  nm_campo char(100) character set latin1 NOT NULL default '',
  te_descricao_campo char(100) character set latin1 NOT NULL default '',
  cs_condicao_pesquisa char(1) character set latin1 NOT NULL default 'S',
  UNIQUE KEY nm_campo (nm_campo)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin COMMENT='Tabela para auxÃƒÂ­lio na opÃƒÂ§ÃƒÂ£o ExclusÃƒÂ£o de Informa';

-- --------------------------------------------------------

--
-- Estrutura da tabela 'grupo_usuarios'
--

CREATE TABLE IF NOT EXISTS grupo_usuarios (
  id_grupo_usuarios int(2) NOT NULL auto_increment,
  te_grupo_usuarios char(20) default NULL,
  te_menu_grupo char(20) default NULL,
  te_descricao_grupo text NOT NULL,
  cs_nivel_administracao tinyint(2) NOT NULL default '0',
  nm_grupo_usuarios char(20) NOT NULL default '',
  PRIMARY KEY  (id_grupo_usuarios)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'historicos_hardware'
--

CREATE TABLE IF NOT EXISTS historicos_hardware (
  te_node_address char(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  campo_alterado char(45) default '',
  valor_antigo char(45) default '',
  data_anterior datetime default '0000-00-00 00:00:00',
  novo_valor char(45) default '',
  nova_data datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (te_node_address,id_so)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'historicos_outros_softwares'
--

CREATE TABLE IF NOT EXISTS historicos_outros_softwares (
  te_node_address char(17) NOT NULL default '',
  id_so int(10) unsigned NOT NULL default '0',
  id_software_inventariado int(10) unsigned NOT NULL default '0',
  dt_hr_inclusao datetime NOT NULL default '0000-00-00 00:00:00',
  dt_hr_ult_coleta datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (te_node_address,id_so,id_software_inventariado)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'historicos_software'
--

CREATE TABLE IF NOT EXISTS historicos_software (
  te_node_address char(17) NOT NULL default '',
  id_so int(11) unsigned NOT NULL default '0',
  id_software_inventariado int(11) unsigned NOT NULL default '0',
  dt_hr_inclusao datetime NOT NULL default '0000-00-00 00:00:00',
  dt_hr_ult_coleta datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (te_node_address,id_so,id_software_inventariado),
  KEY id_software (id_software_inventariado)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'historicos_software_completo'
--

CREATE TABLE IF NOT EXISTS historicos_software_completo (
  te_node_address char(17) NOT NULL default '',
  id_so int(10) unsigned NOT NULL default '0',
  id_software_inventariado int(10) unsigned NOT NULL default '0',
  dt_hr_inclusao datetime NOT NULL default '0000-00-00 00:00:00',
  dt_hr_ult_coleta datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (te_node_address,id_so,id_software_inventariado,dt_hr_inclusao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'historico_hardware'
--

CREATE TABLE IF NOT EXISTS historico_hardware (
  te_node_address char(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  dt_hr_alteracao datetime NOT NULL default '0000-00-00 00:00:00',
  te_placa_video_desc char(100) default NULL,
  te_placa_rede_desc char(100) default NULL,
  te_cpu_desc char(100) default NULL,
  qt_placa_video_mem int(11) default NULL,
  te_placa_mae_desc char(100) default NULL,
  qt_mem_ram int(11) default NULL,
  te_cpu_serial char(50) default NULL,
  te_cpu_fabricante char(100) default NULL,
  te_cpu_freq char(6) default NULL,
  te_mem_ram_desc char(100) default NULL,
  te_bios_desc char(100) default NULL,
  te_bios_data char(10) default NULL,
  te_bios_fabricante char(100) default NULL,
  te_placa_mae_fabricante char(100) default NULL,
  qt_placa_video_cores int(11) default NULL,
  te_placa_video_resolucao char(10) default NULL,
  te_placa_som_desc char(100) default NULL,
  te_cdrom_desc char(100) default NULL,
  te_teclado_desc char(100) default NULL,
  te_mouse_desc char(100) default NULL,
  te_modem_desc char(100) default NULL,
  te_lic_win char(50) default NULL,
  te_key_win char(50) default NULL,
  PRIMARY KEY  (te_node_address,id_so,dt_hr_alteracao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'historico_tcp_ip'
--

CREATE TABLE IF NOT EXISTS historico_tcp_ip (
  te_node_address char(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  dt_hr_alteracao datetime NOT NULL default '0000-00-00 00:00:00',
  te_nome_computador char(25) default NULL,
  te_dominio_windows char(30) default NULL,
  te_dominio_dns char(30) default NULL,
  te_ip char(15) default NULL,
  te_mascara char(15) default NULL,
  id_ip_rede char(15) default NULL,
  te_nome_host char(15) default NULL,
  te_gateway char(15) default NULL,
  te_wins_primario char(15) default NULL,
  te_wins_secundario char(15) default NULL,
  te_dns_primario char(15) default NULL,
  te_dns_secundario char(15) default NULL,
  te_serv_dhcp char(15) default NULL,
  te_workgroup char(20) default NULL,
  PRIMARY KEY  (te_node_address,id_so,dt_hr_alteracao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'insucessos_instalacao'
--

CREATE TABLE IF NOT EXISTS insucessos_instalacao (
  te_ip char(15) NOT NULL default '',
  te_so char(60) NOT NULL default '',
  id_usuario char(60) NOT NULL default '',
  dt_datahora datetime NOT NULL,
  cs_indicador char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'locais'
--

CREATE TABLE IF NOT EXISTS locais (
  id_local int(11) unsigned NOT NULL auto_increment,
  nm_local char(100) NOT NULL default '',
  sg_local char(20) NOT NULL default '',
  te_observacao char(255) default NULL,
  PRIMARY KEY  (id_local),
  KEY sg_localizacao (sg_local)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='LocalizaÃƒÂ§ÃƒÂµes para regionalizaÃƒÂ§ÃƒÂ£o de acesso a dad' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'log'
--

CREATE TABLE IF NOT EXISTS log (
  dt_acao datetime NOT NULL default '0000-00-00 00:00:00',
  cs_acao char(20) NOT NULL default '',
  nm_script char(255) NOT NULL default '',
  nm_tabela char(255) NOT NULL default '',
  id_usuario int(11) NOT NULL default '0',
  te_ip_origem char(15) NOT NULL default '',
  KEY idxLog_DtAcao (dt_acao),
  KEY idxLog_CsAcao (cs_acao),
  KEY idxLog_IdUsuario (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log de Atividades no Sistema CACIC';

-- --------------------------------------------------------

--
-- Estrutura da tabela 'officescan'
--

CREATE TABLE IF NOT EXISTS officescan (
  nu_versao_engine char(10) default NULL,
  nu_versao_pattern char(10) default NULL,
  dt_hr_instalacao datetime default NULL,
  dt_hr_coleta datetime default NULL,
  te_servidor char(30) default NULL,
  in_ativo char(1) default NULL,
  te_node_address char(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  PRIMARY KEY  (te_node_address,id_so)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'patrimonio'
--

CREATE TABLE IF NOT EXISTS patrimonio (
  id_unid_organizacional_nivel1a int(11) NOT NULL,
  id_so int(11) NOT NULL default '0',
  dt_hr_alteracao datetime NOT NULL default '0000-00-00 00:00:00',
  te_node_address char(17) NOT NULL default '',
  id_unid_organizacional_nivel2 int(11) default NULL,
  te_localizacao_complementar char(100) default NULL,
  te_info_patrimonio1 char(20) default NULL,
  te_info_patrimonio2 char(20) default NULL,
  te_info_patrimonio3 char(20) default NULL,
  te_info_patrimonio4 char(20) default NULL,
  te_info_patrimonio5 char(20) default NULL,
  te_info_patrimonio6 char(20) default NULL,
  id_unid_organizacional_nivel1 int(11) NOT NULL default '0',
  KEY te_node_address (te_node_address,id_so),
  KEY idxPatrimonio_IdUnidOrganizacionalNivel1a (id_unid_organizacional_nivel1a),
  KEY idxPatrimonio_DtHrAlteracao (dt_hr_alteracao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'patrimonio_config_interface'
--

CREATE TABLE IF NOT EXISTS patrimonio_config_interface (
  id_local int(11) unsigned NOT NULL default '0',
  id_etiqueta char(30) NOT NULL default '',
  nm_etiqueta char(15) default NULL,
  te_etiqueta char(50) NOT NULL default '',
  in_exibir_etiqueta char(1) default NULL,
  te_help_etiqueta char(100) default NULL,
  te_plural_etiqueta char(50) default NULL,
  nm_campo_tab_patrimonio char(50) default NULL,
  in_destacar_duplicidade char(1) default 'N',
  in_obrigatorio char(1) NOT NULL default 'N',
  PRIMARY KEY  (id_etiqueta,id_local),
  KEY id_localizacao (id_local)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'perfis_aplicativos_monitorados'
--

CREATE TABLE IF NOT EXISTS perfis_aplicativos_monitorados (
  id_aplicativo int(11) NOT NULL auto_increment,
  nm_aplicativo char(100) NOT NULL default '',
  cs_car_inst_w9x char(2) default NULL,
  te_car_inst_w9x char(255) default NULL,
  cs_car_ver_w9x char(2) default NULL,
  te_car_ver_w9x char(255) default NULL,
  cs_car_inst_wnt char(2) default NULL,
  te_car_inst_wnt char(255) default NULL,
  cs_car_ver_wnt char(2) default NULL,
  te_car_ver_wnt char(255) default NULL,
  cs_ide_licenca char(2) default NULL,
  te_ide_licenca char(255) default NULL,
  dt_atualizacao datetime NOT NULL default '0000-00-00 00:00:00',
  te_arq_ver_eng_w9x char(100) default NULL,
  te_arq_ver_pat_w9x char(100) default NULL,
  te_arq_ver_eng_wnt char(100) default NULL,
  te_arq_ver_pat_wnt char(100) default NULL,
  te_dir_padrao_w9x char(100) default NULL,
  te_dir_padrao_wnt char(100) default NULL,
  id_so int(11) default '0',
  te_descritivo text,
  in_disponibiliza_info char(1) default 'N',
  in_disponibiliza_info_usuario_comum char(1) NOT NULL default 'N',
  dt_registro datetime default NULL,
  PRIMARY KEY  (id_aplicativo)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'redes'
--

CREATE TABLE IF NOT EXISTS redes (
  id_local int(11) unsigned NOT NULL default '0',
  id_servidor_autenticacao int(11) default '0',
  id_ip_rede char(15) NOT NULL default '',
  nm_rede char(100) default NULL,
  te_observacao char(100) default NULL,
  nm_pessoa_contato1 char(50) default NULL,
  nm_pessoa_contato2 char(50) default NULL,
  nu_telefone1 char(11) default NULL,
  te_email_contato2 char(50) default NULL,
  nu_telefone2 char(11) default NULL,
  te_email_contato1 char(50) default NULL,
  te_serv_cacic char(60) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  te_serv_updates char(60) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  te_path_serv_updates char(255) default NULL,
  nm_usuario_login_serv_updates char(20) default NULL,
  te_senha_login_serv_updates char(20) default NULL,
  nu_porta_serv_updates char(4) default NULL,
  te_mascara_rede char(15) default NULL,
  dt_verifica_updates date default NULL,
  nm_usuario_login_serv_updates_gerente char(20) default NULL,
  te_senha_login_serv_updates_gerente char(20) default NULL,
  nu_limite_ftp int(5) unsigned NOT NULL default '5',
  cs_permitir_desativar_srcacic char(1) character set utf8 collate utf8_unicode_ci NOT NULL default 'S',
  PRIMARY KEY  (id_ip_rede,id_local),
  KEY id_ip_rede (id_ip_rede)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'redes_grupos_ftp'
--

CREATE TABLE IF NOT EXISTS redes_grupos_ftp (
  id_local int(11) NOT NULL default '0',
  id_ip_rede char(15) NOT NULL default '0',
  id_ip_estacao char(15) NOT NULL default '0',
  nu_hora_inicio int(12) NOT NULL default '0',
  nu_hora_fim char(12) NOT NULL default '0',
  id_ftp int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id_ftp),
  KEY idxRedesGruposFtp_IdLocal_IdIpRede_IdIpEstacao (id_local,id_ip_rede,id_ip_estacao),
  KEY idxRedesGruposFtp_IdIpRede_IdLocal (id_ip_rede,id_local)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'redes_versoes_modulos'
--

CREATE TABLE IF NOT EXISTS redes_versoes_modulos (
  id_local int(11) unsigned NOT NULL default '0',
  id_ip_rede char(15) NOT NULL default '',
  nm_modulo char(100) NOT NULL default '',
  te_versao_modulo char(20) default NULL,
  dt_atualizacao datetime NOT NULL,
  cs_tipo_so char(20) NOT NULL default 'MS-Windows',
  te_hash char(40) default 'a',
  PRIMARY KEY  (id_ip_rede,nm_modulo,id_local)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'servidores_autenticacao'
--

CREATE TABLE IF NOT EXISTS servidores_autenticacao (
  id_servidor_autenticacao int(11) NOT NULL auto_increment,
  nm_servidor_autenticacao char(60) NOT NULL default '',
  nm_servidor_autenticacao_dns char(60) NOT NULL,
  te_ip_servidor_autenticacao char(15) NOT NULL default '',
  nu_porta_servidor_autenticacao char(5) NOT NULL default '',
  id_tipo_protocolo char(20) NOT NULL default '',
  nu_versao_protocolo char(10) NOT NULL default '',
  te_atributo_identificador char(100) NOT NULL default '',
  te_atributo_retorna_nome char(100) NOT NULL default '',
  te_atributo_retorna_email char(100) NOT NULL default '',
  te_atributo_retorna_telefone char(100) default NULL,
  te_atributo_status_conta char(100) default NULL,
  te_atributo_valor_status_conta_valida char(100) NOT NULL,
  te_observacao text NOT NULL,
  in_ativo char(1) NOT NULL default 'S',
  PRIMARY KEY  (id_servidor_autenticacao),
  KEY nm_servidor_autenticacao_dns (nm_servidor_autenticacao_dns)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Servidores para Autenticacao do srCACIC' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'so'
--

CREATE TABLE IF NOT EXISTS so (
  id_so int(11) NOT NULL default '0',
  te_desc_so char(50) default NULL,
  sg_so char(20) default NULL,
  te_so char(50) NOT NULL default '',
  in_mswindows char(1) NOT NULL default 'S',
  PRIMARY KEY  (id_so,te_so),
  KEY idxSo_TeSo (te_so)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'softwares'
--

CREATE TABLE IF NOT EXISTS softwares (
  id_software int(10) unsigned NOT NULL auto_increment,
  nm_software char(150) default NULL,
  te_descricao_software char(255) default NULL,
  qt_licenca int(11) default '0',
  nr_midia char(10) default NULL,
  te_local_midia char(30) default NULL,
  te_obs char(200) default NULL,
  PRIMARY KEY  (id_software)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'softwares_estacao'
--

CREATE TABLE IF NOT EXISTS softwares_estacao (
  nr_patrimonio char(20) NOT NULL default '',
  id_software int(10) unsigned NOT NULL default '0',
  nm_computador char(50) default NULL,
  dt_autorizacao date default NULL,
  nr_processo char(11) default NULL,
  dt_expiracao_instalacao date default NULL,
  id_aquisicao_particular int(10) unsigned default NULL,
  dt_desinstalacao date default NULL,
  te_observacao char(90) default NULL,
  nr_patr_destino char(20) default NULL,
  PRIMARY KEY  (nr_patrimonio,id_software)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'softwares_inventariados'
--

CREATE TABLE IF NOT EXISTS softwares_inventariados (
  id_software_inventariado int(10) unsigned NOT NULL auto_increment,
  nm_software_inventariado char(100) NOT NULL default '',
  id_tipo_software int(11) default '0',
  id_software int(10) unsigned default NULL,
  te_hash char(40) NOT NULL default '',
  PRIMARY KEY  (id_software_inventariado),
  KEY nm_software_inventariado (nm_software_inventariado),
  KEY id_software (id_software_inventariado),
  KEY idx_nm_software_inventariado (nm_software_inventariado),
  KEY idxSoftwaresInventariados_TeHash (te_hash)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'softwares_inventariados_estacoes'
--

CREATE TABLE IF NOT EXISTS softwares_inventariados_estacoes (
  te_node_address char(17) NOT NULL default '',
  id_so int(11) unsigned NOT NULL default '0',
  id_software_inventariado int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (te_node_address,id_so,id_software_inventariado),
  KEY id_software (id_software_inventariado)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'srcacic_chats'
--

CREATE TABLE IF NOT EXISTS srcacic_chats (
  id_conexao int(11) NOT NULL,
  dt_hr_mensagem datetime NOT NULL,
  te_mensagem text character set utf8 NOT NULL,
  cs_origem char(3) character set utf8 collate utf8_unicode_ci NOT NULL default 'cli',
  KEY id_conexao (id_conexao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log de Atividades no Sistema CACIC';

-- --------------------------------------------------------

--
-- Estrutura da tabela 'srcacic_conexoes'
--

CREATE TABLE IF NOT EXISTS srcacic_conexoes (
  id_conexao int(11) NOT NULL auto_increment COMMENT 'Identificador da conexÃ£o',
  id_sessao int(11) NOT NULL,
  id_usuario_cli int(11) NOT NULL default '0',
  te_node_address_cli char(17) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  te_documento_referencial char(60) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  id_so_cli int(11) NOT NULL,
  te_motivo_conexao text character set utf8 collate utf8_unicode_ci NOT NULL COMMENT 'Descritivo breve sobre o motivo da conexÃ£o',
  dt_hr_inicio_conexao datetime NOT NULL,
  dt_hr_ultimo_contato datetime NOT NULL,
  PRIMARY KEY  (id_conexao)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Registros de ConexÃƒÂµes efetuadas ÃƒÂ s sessÃƒÂµes abertas' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'srcacic_sessoes'
--

CREATE TABLE IF NOT EXISTS srcacic_sessoes (
  id_sessao int(11) NOT NULL auto_increment,
  dt_hr_inicio_sessao datetime NOT NULL,
  nm_acesso_usuario_srv char(30) character set utf8 NOT NULL default '',
  nm_completo_usuario_srv char(100) NOT NULL default 'NoNoNo',
  te_email_usuario_srv char(60) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  te_node_address_srv char(17) character set utf8 NOT NULL default '',
  id_so_srv int(11) NOT NULL,
  dt_hr_ultimo_contato datetime default NULL,
  PRIMARY KEY  (id_sessao),
  KEY idx_dtHrInicioSessao (dt_hr_inicio_sessao)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Log de Sessoes de Suporte Remoto do CACIC/srCACIC' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'srcacic_transfs'
--

CREATE TABLE IF NOT EXISTS srcacic_transfs (
  id_conexao int(11) NOT NULL,
  dt_systemtime datetime NOT NULL,
  nu_duracao double NOT NULL,
  te_path_origem char(255) character set utf8 collate utf8_unicode_ci NOT NULL default 'NoNoNo',
  te_path_destino char(255) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  nm_arquivo char(127) character set utf8 collate utf8_unicode_ci NOT NULL default '',
  nu_tamanho_arquivo int(11) NOT NULL,
  cs_status char(1) character set utf8 collate utf8_unicode_ci NOT NULL default '1',
  cs_operacao char(1) character set utf8 collate utf8_unicode_ci NOT NULL default 'D',
  KEY id_conexao (id_conexao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log de Transferencias de Arquivos em Conexoes de Suporte Remoto do CACIC/srCACIC';

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Estrutura da tabela 'tipos_licenca'
--

CREATE TABLE IF NOT EXISTS tipos_licenca (
  id_tipo_licenca int(10) unsigned NOT NULL auto_increment,
  te_tipo_licenca char(20) default NULL,
  PRIMARY KEY  (id_tipo_licenca)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'tipos_software'
--

CREATE TABLE IF NOT EXISTS tipos_software (
  id_tipo_software int(10) unsigned NOT NULL auto_increment,
  te_descricao_tipo_software char(30) NOT NULL default '',
  PRIMARY KEY  (id_tipo_software)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'tipos_unidades_disco'
--

CREATE TABLE IF NOT EXISTS tipos_unidades_disco (
  id_tipo_unid_disco char(1) NOT NULL default '',
  te_tipo_unid_disco char(25) default NULL,
  PRIMARY KEY  (id_tipo_unid_disco)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'unidades_disco'
--

CREATE TABLE IF NOT EXISTS unidades_disco (
  te_letra char(20) character set utf8 collate utf8_unicode_ci NOT NULL,
  id_so int(11) NOT NULL default '0',
  te_node_address char(17) NOT NULL default '',
  id_tipo_unid_disco char(1) default NULL,
  nu_serial char(12) default NULL,
  nu_capacidade int(10) unsigned default NULL,
  nu_espaco_livre int(10) unsigned default NULL,
  te_unc char(60) default NULL,
  cs_sist_arq char(10) default NULL,
  PRIMARY KEY  (te_letra,id_so,te_node_address)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'unid_organizacional_nivel1'
--

CREATE TABLE IF NOT EXISTS unid_organizacional_nivel1 (
  id_unid_organizacional_nivel1 int(11) NOT NULL auto_increment,
  nm_unid_organizacional_nivel1 char(50) default NULL,
  te_endereco_uon1 char(80) default NULL,
  te_bairro_uon1 char(30) default NULL,
  te_cidade_uon1 char(50) default NULL,
  te_uf_uon1 char(2) default NULL,
  nm_responsavel_uon1 char(80) default NULL,
  te_email_responsavel_uon1 char(50) default NULL,
  nu_tel1_responsavel_uon1 char(10) default NULL,
  nu_tel2_responsavel_uon1 char(10) default NULL,
  PRIMARY KEY  (id_unid_organizacional_nivel1)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'unid_organizacional_nivel1a'
--

CREATE TABLE IF NOT EXISTS unid_organizacional_nivel1a (
  id_unid_organizacional_nivel1 int(11) NOT NULL,
  id_unid_organizacional_nivel1a int(11) NOT NULL auto_increment,
  nm_unid_organizacional_nivel1a char(50) default NULL,
  PRIMARY KEY  (id_unid_organizacional_nivel1a)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'unid_organizacional_nivel2'
--

CREATE TABLE IF NOT EXISTS unid_organizacional_nivel2 (
  id_local int(11) unsigned NOT NULL default '0',
  id_unid_organizacional_nivel2 int(11) NOT NULL auto_increment,
  id_unid_organizacional_nivel1a int(11) NOT NULL default '0',
  nm_unid_organizacional_nivel2 char(50) NOT NULL default '',
  te_endereco_uon2 char(80) default NULL,
  te_bairro_uon2 char(30) default NULL,
  te_cidade_uon2 char(50) default NULL,
  te_uf_uon2 char(2) default NULL,
  nm_responsavel_uon2 char(80) default NULL,
  te_email_responsavel_uon2 char(50) default NULL,
  nu_tel1_responsavel_uon2 char(10) default NULL,
  nu_tel2_responsavel_uon2 char(10) default NULL,
  dt_registro datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (id_unid_organizacional_nivel2,id_unid_organizacional_nivel1a,id_local),
  KEY id_localizacao (id_local)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'usb_devices'
--

CREATE TABLE IF NOT EXISTS usb_devices (
  id_vendor char(5) character set latin1 collate latin1_general_ci NOT NULL,
  id_device char(5) character set latin1 collate latin1_general_ci NOT NULL,
  nm_device char(127) collate utf8_unicode_ci NOT NULL,
  te_observacao text collate utf8_unicode_ci NOT NULL,
  dt_registro char(12) collate utf8_unicode_ci default NULL,
  KEY idxUSBDevices_idVendor (id_vendor),
  KEY idxUSBDevices_idDevice (id_device)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'usb_logs'
--

CREATE TABLE IF NOT EXISTS usb_logs (
  id_vendor char(5) collate utf8_unicode_ci NOT NULL,
  id_device char(5) character set latin1 collate latin1_general_ci NOT NULL,
  dt_event char(14) collate utf8_unicode_ci NOT NULL,
  te_node_address char(17) collate utf8_unicode_ci NOT NULL,
  id_so int(11) NOT NULL,
  cs_event char(1) collate utf8_unicode_ci NOT NULL default 'I',
  KEY idxUSBLogs_dtEvent (dt_event),
  KEY idxUSBLogs_idVendor_idDevice (id_vendor,id_device)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'usb_vendors'
--

CREATE TABLE IF NOT EXISTS usb_vendors (
  id_vendor char(5) collate latin1_general_ci NOT NULL,
  nm_vendor char(127) collate latin1_general_ci NOT NULL,
  te_observacao text character set utf8 collate utf8_unicode_ci NOT NULL,
  dt_registro char(12) collate latin1_general_ci default NULL,
  UNIQUE KEY idxUSBVendors_idVendor (id_vendor)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'usuarios'
--

CREATE TABLE IF NOT EXISTS usuarios (
  id_local int(11) unsigned NOT NULL default '0',
  id_servidor_autenticacao int(11) NOT NULL default '0',
  id_usuario int(10) unsigned NOT NULL auto_increment,
  id_usuario_ldap char(100) default NULL,
  nm_usuario_acesso char(20) NOT NULL default '',
  nm_usuario_completo char(60) NOT NULL default '',
  nm_usuario_completo_ldap char(100) default NULL,
  te_senha char(60) NOT NULL default '',
  dt_log_in datetime NOT NULL default '0000-00-00 00:00:00',
  id_grupo_usuarios int(1) NOT NULL default '1',
  te_emails_contato char(100) default NULL,
  te_telefones_contato char(100) default NULL,
  te_locais_secundarios text,
  PRIMARY KEY  (id_usuario),
  UNIQUE KEY nm_usuario_acesso (nm_usuario_acesso),
  UNIQUE KEY id_usuario_ldap (id_usuario_ldap),
  KEY id_localizacao (id_local)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'variaveis_ambiente'
--

CREATE TABLE IF NOT EXISTS variaveis_ambiente (
  id_variavel_ambiente int(10) unsigned NOT NULL auto_increment,
  nm_variavel_ambiente char(100) NOT NULL default '',
  te_hash char(40) NOT NULL default '',
  PRIMARY KEY  (id_variavel_ambiente),
  KEY idxVariaveisAmbiente_TeHash (te_hash)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'variaveis_ambiente_estacoes'
--

CREATE TABLE IF NOT EXISTS variaveis_ambiente_estacoes (
  te_node_address char(17) NOT NULL default '',
  id_so int(10) unsigned NOT NULL default '0',
  id_variavel_ambiente int(10) unsigned NOT NULL default '0',
  vl_variavel_ambiente char(100) NOT NULL default '',
  PRIMARY KEY  (te_node_address,id_so,id_variavel_ambiente)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela 'versoes_softwares'
--

CREATE TABLE IF NOT EXISTS versoes_softwares (
  id_so int(11) NOT NULL default '0',
  te_node_address char(17) NOT NULL default '',
  te_versao_bde char(10) default NULL,
  te_versao_dao char(5) default NULL,
  te_versao_ado char(5) default NULL,
  te_versao_odbc char(15) default NULL,
  te_versao_directx int(10) unsigned default NULL,
  te_versao_acrobat_reader char(10) default NULL,
  te_versao_ie char(18) default NULL,
  te_versao_mozilla char(12) default NULL,
  te_versao_jre char(6) default NULL,
  PRIMARY KEY  (te_node_address,id_so)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
