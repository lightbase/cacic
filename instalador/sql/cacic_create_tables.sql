-- --------------------------------------------------------
-- Tabelas para o banco de dados CACIC
-- SGBD: MySQL-5.0.51
-- MySQL Workbench 5.2.8 Beta
-- --------------------------------------------------------

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- -----------------------------------------------------
-- Table `acoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `acoes` (
  `id_acao` VARCHAR(20) NOT NULL DEFAULT '' ,
  `te_descricao_breve` VARCHAR(100) NULL DEFAULT NULL ,
  `te_descricao` TEXT NULL DEFAULT NULL ,
  `te_nome_curto_modulo` VARCHAR(20) NULL DEFAULT NULL ,
  `dt_hr_alteracao` DATETIME NULL DEFAULT '0000-00-00 00:00:00' ,
  `cs_situacao` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_acao`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `acoes_excecoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `acoes_excecoes` (
  `id_local` INT(11) NOT NULL ,
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_acao` VARCHAR(20) NOT NULL DEFAULT '' ,
  `id_so` INT(11) NOT NULL DEFAULT '0' )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `acoes_redes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `acoes_redes` (
  `id_acao` VARCHAR(20) NOT NULL DEFAULT '' ,
  `id_local` INT(11) NOT NULL DEFAULT '0' ,
  `id_ip_rede` VARCHAR(15) NOT NULL DEFAULT '' ,
  `dt_hr_coleta_forcada` DATETIME NULL DEFAULT NULL ,
  `cs_situacao` CHAR(1) NOT NULL DEFAULT 'T' ,
  `dt_hr_alteracao` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id_local`, `id_ip_rede`, `id_acao`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `acoes_so`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `acoes_so` (
  `id_local` INT(11) NOT NULL DEFAULT '0' ,
  `id_acao` VARCHAR(20) NOT NULL DEFAULT '' ,
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id_acao`, `id_so`, `id_local`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `aplicativos_monitorados`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `aplicativos_monitorados` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_aplicativo` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `te_versao` VARCHAR(50) NULL DEFAULT NULL ,
  `te_licenca` VARCHAR(50) NULL DEFAULT NULL ,
  `te_ver_engine` VARCHAR(50) NULL DEFAULT NULL ,
  `te_ver_pattern` VARCHAR(50) NULL DEFAULT NULL ,
  `cs_instalado` CHAR(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`te_node_address`, `id_so`, `id_aplicativo`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `aplicativos_redes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `aplicativos_redes` (
  `id_local` INT(11) NOT NULL DEFAULT '0' ,
  `id_ip_rede` VARCHAR(15) NOT NULL DEFAULT '' ,
  `id_aplicativo` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id_local`, `id_ip_rede`, `id_aplicativo`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Relacionamento redes e perfis_aplicativos_monitorados';


-- -----------------------------------------------------
-- Table `aquisicoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `aquisicoes` (
  `id_aquisicao` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `dt_aquisicao` DATE NULL DEFAULT NULL ,
  `nr_processo` VARCHAR(11) NULL DEFAULT NULL ,
  `nm_empresa` VARCHAR(45) NULL DEFAULT NULL ,
  `nm_proprietario` VARCHAR(45) NULL DEFAULT NULL ,
  `nr_notafiscal` VARCHAR(20) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_aquisicao`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `aquisicoes_item`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `aquisicoes_item` (
  `id_aquisicao` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_software` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_tipo_licenca` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `qt_licenca` INT(11) NULL DEFAULT NULL ,
  `dt_vencimento_licenca` DATE NULL DEFAULT NULL ,
  `te_obs` VARCHAR(50) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_aquisicao`, `id_software`, `id_tipo_licenca`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
ROW_FORMAT = DYNAMIC;


-- -----------------------------------------------------
-- Table `compartilhamentos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `compartilhamentos` (
  `nm_compartilhamento` VARCHAR(30) NOT NULL DEFAULT '' ,
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `nm_dir_compart` VARCHAR(100) NULL DEFAULT NULL ,
  `in_senha_escrita` CHAR(1) NULL DEFAULT NULL ,
  `in_senha_leitura` CHAR(1) NULL DEFAULT NULL ,
  `cs_tipo_permissao` CHAR(1) NULL DEFAULT NULL ,
  `cs_tipo_compart` CHAR(1) NULL DEFAULT NULL ,
  `te_comentario` VARCHAR(50) NULL DEFAULT NULL ,
  PRIMARY KEY (`nm_compartilhamento`, `id_so`, `te_node_address`) ,
  INDEX `node_so_tipocompart` (`te_node_address` ASC, `id_so` ASC, `cs_tipo_compart` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `componentes_estacoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `componentes_estacoes` (
  `te_node_address` VARCHAR(17) NOT NULL ,
  `id_so` INT(11) NOT NULL ,
  `cs_tipo_componente` VARCHAR(100) NOT NULL ,
  `te_valor` TEXT NOT NULL ,
  INDEX `te_node_address` (`te_node_address` ASC, `id_so` ASC, `cs_tipo_componente` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Componentes de hardware instalados nas estações';


-- -----------------------------------------------------
-- Table `componentes_estacoes_historico`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `componentes_estacoes_historico` (
  `te_node_address` VARCHAR(17) NOT NULL ,
  `id_so` INT(11) NOT NULL ,
  `cs_tipo_componente` VARCHAR(100) NOT NULL ,
  `te_valor` VARCHAR(200) NOT NULL ,
  `dt_alteracao` DATETIME NOT NULL ,
  `cs_tipo_alteracao` VARCHAR(3) NOT NULL ,
  INDEX `te_node_address` (`te_node_address` ASC, `id_so` ASC, `cs_tipo_componente` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Componentes de hardware instalados nas estações';


-- -----------------------------------------------------
-- Table `computadores`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `computadores` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  `te_so` VARCHAR(50) NULL DEFAULT NULL ,
  `te_nome_computador` VARCHAR(50) NULL DEFAULT NULL ,
  `id_ip_rede` VARCHAR(15) NOT NULL DEFAULT '' ,
  `te_dominio_windows` VARCHAR(50) NULL DEFAULT NULL ,
  `te_dominio_dns` VARCHAR(50) NULL DEFAULT NULL ,
  `te_placa_video_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_ip` VARCHAR(15) NULL DEFAULT NULL ,
  `te_mascara` VARCHAR(15) NULL DEFAULT NULL ,
  `te_nome_host` VARCHAR(50) NULL DEFAULT NULL ,
  `te_placa_rede_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `dt_hr_inclusao` DATETIME NULL DEFAULT NULL ,
  `te_gateway` VARCHAR(15) NULL DEFAULT NULL ,
  `te_wins_primario` VARCHAR(15) NULL DEFAULT NULL ,
  `te_cpu_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_wins_secundario` VARCHAR(15) NULL DEFAULT NULL ,
  `te_dns_primario` VARCHAR(15) NULL DEFAULT NULL ,
  `qt_placa_video_mem` INT(11) NULL DEFAULT NULL ,
  `te_dns_secundario` VARCHAR(15) NULL DEFAULT NULL ,
  `te_placa_mae_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_serv_dhcp` VARCHAR(15) NULL DEFAULT NULL ,
  `qt_mem_ram` INT(11) NULL DEFAULT NULL ,
  `te_cpu_serial` VARCHAR(50) NULL DEFAULT NULL ,
  `te_cpu_fabricante` VARCHAR(100) NULL DEFAULT NULL ,
  `te_cpu_freq` VARCHAR(6) NULL DEFAULT NULL ,
  `te_mem_ram_desc` VARCHAR(200) NULL DEFAULT NULL ,
  `te_bios_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_bios_data` VARCHAR(10) NULL DEFAULT NULL ,
  `dt_hr_ult_acesso` DATETIME NULL DEFAULT NULL ,
  `te_versao_cacic` VARCHAR(10) NULL DEFAULT NULL ,
  `te_versao_gercols` VARCHAR(10) NULL DEFAULT NULL ,
  `te_bios_fabricante` VARCHAR(100) NULL DEFAULT NULL ,
  `te_palavra_chave` CHAR(30) NOT NULL DEFAULT 'abcdefghij' ,
  `te_placa_mae_fabricante` VARCHAR(100) NULL DEFAULT NULL ,
  `qt_placa_video_cores` INT(11) NULL DEFAULT NULL ,
  `te_placa_video_resolucao` VARCHAR(10) NULL DEFAULT NULL ,
  `te_placa_som_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_cdrom_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_teclado_desc` VARCHAR(100) NOT NULL DEFAULT '' ,
  `te_mouse_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_modem_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_workgroup` VARCHAR(50) NULL DEFAULT NULL ,
  `dt_hr_coleta_forcada_estacao` DATETIME NULL DEFAULT NULL ,
  `te_nomes_curtos_modulos` VARCHAR(255) NULL DEFAULT NULL ,
  `te_origem_mac` TEXT NULL DEFAULT NULL ,
  `id_conta` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`te_node_address`, `id_so`) ,
  INDEX `computadores_versao_cacic` (`te_versao_cacic` ASC) ,
  INDEX `te_ip` (`te_ip` ASC) ,
  INDEX `te_node_address` (`te_node_address` ASC) ,
  INDEX `te_nome_computador` (`te_nome_computador` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `configuracoes_locais`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `configuracoes_locais` (
  `id_local` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `te_notificar_mudanca_hardware` TEXT NULL DEFAULT NULL ,
  `in_exibe_erros_criticos` CHAR(1) NULL DEFAULT 'N' ,
  `in_exibe_bandeja` CHAR(1) NULL DEFAULT 'S' ,
  `nu_exec_apos` INT(11) NULL DEFAULT '10' ,
  `dt_hr_alteracao_patrim_interface` DATETIME NULL DEFAULT NULL ,
  `dt_hr_alteracao_patrim_uon1` DATETIME NULL DEFAULT '0000-00-00 00:00:00' ,
  `dt_hr_alteracao_patrim_uon1a` DATETIME NULL DEFAULT '0000-00-00 00:00:00' ,
  `dt_hr_alteracao_patrim_uon2` DATETIME NULL DEFAULT '0000-00-00 00:00:00' ,
  `dt_hr_coleta_forcada` DATETIME NULL DEFAULT NULL ,
  `te_notificar_mudanca_patrim` TEXT NULL DEFAULT NULL ,
  `nm_organizacao` VARCHAR(150) NULL DEFAULT NULL ,
  `nu_timeout_srcacic` TINYINT(3) NOT NULL DEFAULT '30' COMMENT 'Valor para timeout do servidor srCACIC' ,
  `nu_intervalo_exec` INT(11) NULL DEFAULT '4' ,
  `nu_intervalo_renovacao_patrim` INT(11) NULL DEFAULT '0' ,
  `te_senha_adm_agente` VARCHAR(30) NULL DEFAULT 'ADMINCACIC' ,
  `te_serv_updates_padrao` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `te_serv_cacic_padrao` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `te_enderecos_mac_invalidos` TEXT NULL DEFAULT NULL ,
  `te_janelas_excecao` TEXT NULL DEFAULT NULL ,
  `te_nota_email_gerentes` TEXT NULL DEFAULT NULL ,
  `cs_abre_janela_patr` CHAR(1) NOT NULL DEFAULT 'N' ,
  `id_default_body_bgcolor` VARCHAR(10) NOT NULL DEFAULT '#EBEBEB' ,
  `te_exibe_graficos` VARCHAR(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' ,
  `nu_porta_srcacic` VARCHAR(5) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '5900' ,
  PRIMARY KEY (`id_local`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `configuracoes_padrao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `configuracoes_padrao` (
  `in_exibe_erros_criticos` CHAR(1) NULL DEFAULT NULL ,
  `in_exibe_bandeja` CHAR(1) NULL DEFAULT NULL ,
  `nu_exec_apos` INT(11) NULL DEFAULT NULL ,
  `nu_rel_maxlinhas` SMALLINT(5) UNSIGNED NULL DEFAULT '50' ,
  `nm_organizacao` VARCHAR(150) NULL DEFAULT NULL ,
  `nu_timeout_srcacic` TINYINT(3) NOT NULL DEFAULT '30' COMMENT 'Valor padrao para timeout do servidor srCACIC' ,
  `nu_intervalo_exec` INT(11) NULL DEFAULT NULL ,
  `nu_intervalo_renovacao_patrim` INT(11) NULL DEFAULT NULL ,
  `te_senha_adm_agente` VARCHAR(30) NULL DEFAULT NULL ,
  `te_serv_updates_padrao` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `te_serv_cacic_padrao` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
  `te_enderecos_mac_invalidos` TEXT NULL DEFAULT NULL ,
  `te_janelas_excecao` TEXT NULL DEFAULT NULL ,
  `cs_abre_janela_patr` CHAR(1) NOT NULL DEFAULT 'S' ,
  `id_default_body_bgcolor` VARCHAR(10) NOT NULL DEFAULT '#EBEBEB' ,
  `te_exibe_graficos` VARCHAR(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' ,
  `nu_porta_srcacic` VARCHAR(5) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '5900' ,
  `nu_resolucao_grafico_h` SMALLINT(5) NOT NULL DEFAULT '0' ,
  `nu_resolucao_grafico_w` SMALLINT(5) NOT NULL DEFAULT '0' )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `contas`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `contas` (
  `id_conta` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nm_responsavel` VARCHAR(30) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`id_conta`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `descricao_hardware`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `descricao_hardware` (
  `nm_campo_tab_hardware` VARCHAR(45) NOT NULL DEFAULT '' ,
  `te_desc_hardware` VARCHAR(45) NOT NULL DEFAULT '' ,
  `te_locais_notificacao_ativada` TEXT NULL DEFAULT NULL COMMENT 'Locais onde a notificação de alteração de hardware encontra-se ativa.' ,
  PRIMARY KEY (`nm_campo_tab_hardware`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `descricoes_colunas_computadores`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `descricoes_colunas_computadores` (
  `nm_campo` VARCHAR(100) NOT NULL DEFAULT '' ,
  `te_descricao_campo` VARCHAR(100) NOT NULL DEFAULT '' ,
  `cs_condicao_pesquisa` CHAR(1) NOT NULL DEFAULT 'S' ,
  UNIQUE INDEX `nm_campo` (`nm_campo` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Tabela para auxílio na opção Exclusão de Informações';


-- -----------------------------------------------------
-- Table `grupo_usuarios`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `grupo_usuarios` (
  `id_grupo_usuarios` INT(2) NOT NULL AUTO_INCREMENT ,
  `te_grupo_usuarios` VARCHAR(20) NULL DEFAULT NULL ,
  `te_menu_grupo` VARCHAR(20) NULL DEFAULT NULL ,
  `te_descricao_grupo` TEXT NOT NULL ,
  `cs_nivel_administracao` TINYINT(2) NOT NULL DEFAULT '0' ,
  `nm_grupo_usuarios` VARCHAR(20) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`id_grupo_usuarios`) )
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `historicos_hardware`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `historicos_hardware` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  `campo_alterado` VARCHAR(45) NULL DEFAULT '' ,
  `valor_antigo` VARCHAR(45) NULL DEFAULT '' ,
  `data_anterior` DATETIME NULL DEFAULT '0000-00-00 00:00:00' ,
  `novo_valor` VARCHAR(45) NULL DEFAULT '' ,
  `nova_data` DATETIME NULL DEFAULT '0000-00-00 00:00:00' ,
  PRIMARY KEY (`te_node_address`, `id_so`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `historicos_outros_softwares`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `historicos_outros_softwares` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_software_inventariado` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `dt_hr_inclusao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `dt_hr_ult_coleta` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  PRIMARY KEY (`te_node_address`, `id_so`, `id_software_inventariado`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `historicos_software`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `historicos_software` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_software_inventariado` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `dt_hr_inclusao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `dt_hr_ult_coleta` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  PRIMARY KEY (`te_node_address`, `id_so`, `id_software_inventariado`) ,
  INDEX `id_software` (`id_software_inventariado` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `historicos_software_completo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `historicos_software_completo` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_software_inventariado` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `dt_hr_inclusao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `dt_hr_ult_coleta` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  PRIMARY KEY (`te_node_address`, `id_so`, `id_software_inventariado`, `dt_hr_inclusao`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `historico_hardware`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `historico_hardware` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  `dt_hr_alteracao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `te_placa_video_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_placa_rede_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_cpu_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `qt_placa_video_mem` INT(11) NULL DEFAULT NULL ,
  `te_placa_mae_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `qt_mem_ram` INT(11) NULL DEFAULT NULL ,
  `te_cpu_serial` VARCHAR(50) NULL DEFAULT NULL ,
  `te_cpu_fabricante` VARCHAR(100) NULL DEFAULT NULL ,
  `te_cpu_freq` VARCHAR(6) NULL DEFAULT NULL ,
  `te_mem_ram_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_bios_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_bios_data` VARCHAR(10) NULL DEFAULT NULL ,
  `te_bios_fabricante` VARCHAR(100) NULL DEFAULT NULL ,
  `te_placa_mae_fabricante` VARCHAR(100) NULL DEFAULT NULL ,
  `qt_placa_video_cores` INT(11) NULL DEFAULT NULL ,
  `te_placa_video_resolucao` VARCHAR(10) NULL DEFAULT NULL ,
  `te_placa_som_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_cdrom_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_teclado_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_mouse_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_modem_desc` VARCHAR(100) NULL DEFAULT NULL ,
  `te_lic_win` VARCHAR(50) NULL DEFAULT NULL ,
  `te_key_win` VARCHAR(50) NULL DEFAULT NULL ,
  PRIMARY KEY (`te_node_address`, `id_so`, `dt_hr_alteracao`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `historico_tcp_ip`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `historico_tcp_ip` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  `dt_hr_alteracao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `te_nome_computador` VARCHAR(25) NULL DEFAULT NULL ,
  `te_dominio_windows` VARCHAR(30) NULL DEFAULT NULL ,
  `te_dominio_dns` VARCHAR(30) NULL DEFAULT NULL ,
  `te_ip` VARCHAR(15) NULL DEFAULT NULL ,
  `te_mascara` VARCHAR(15) NULL DEFAULT NULL ,
  `id_ip_rede` VARCHAR(15) NULL DEFAULT NULL ,
  `te_nome_host` VARCHAR(15) NULL DEFAULT NULL ,
  `te_gateway` VARCHAR(15) NULL DEFAULT NULL ,
  `te_wins_primario` VARCHAR(15) NULL DEFAULT NULL ,
  `te_wins_secundario` VARCHAR(15) NULL DEFAULT NULL ,
  `te_dns_primario` VARCHAR(15) NULL DEFAULT NULL ,
  `te_dns_secundario` VARCHAR(15) NULL DEFAULT NULL ,
  `te_serv_dhcp` VARCHAR(15) NULL DEFAULT NULL ,
  `te_workgroup` VARCHAR(20) NULL DEFAULT NULL ,
  PRIMARY KEY (`te_node_address`, `id_so`, `dt_hr_alteracao`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `insucessos_instalacao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `insucessos_instalacao` (
  `te_ip` VARCHAR(15) NOT NULL ,
  `te_so` VARCHAR(60) NOT NULL ,
  `id_usuario` VARCHAR(60) NOT NULL ,
  `dt_datahora` DATETIME NOT NULL ,
  `cs_indicador` CHAR(1) NOT NULL )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `locais`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `locais` (
  `id_local` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nm_local` VARCHAR(100) NOT NULL DEFAULT '' ,
  `sg_local` VARCHAR(20) NOT NULL DEFAULT '' ,
  `te_observacao` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_local`) ,
  INDEX `sg_localizacao` (`sg_local` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 70
DEFAULT CHARACTER SET = latin1
COMMENT = 'Localizações para regionalização de acesso a dados';


-- -----------------------------------------------------
-- Table `log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `log` (
  `dt_acao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `cs_acao` VARCHAR(20) NOT NULL DEFAULT '' ,
  `nm_script` VARCHAR(255) NOT NULL DEFAULT '' ,
  `nm_tabela` VARCHAR(255) NOT NULL DEFAULT '' ,
  `id_usuario` INT(11) NOT NULL DEFAULT '0' ,
  `te_ip_origem` VARCHAR(15) NOT NULL DEFAULT '' )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Log de Atividades no Sistema CACIC';


-- -----------------------------------------------------
-- Table `officescan`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `officescan` (
  `nu_versao_engine` VARCHAR(10) NULL DEFAULT NULL ,
  `nu_versao_pattern` VARCHAR(10) NULL DEFAULT NULL ,
  `dt_hr_instalacao` DATETIME NULL DEFAULT NULL ,
  `dt_hr_coleta` DATETIME NULL DEFAULT NULL ,
  `te_servidor` VARCHAR(30) NULL DEFAULT NULL ,
  `in_ativo` CHAR(1) NULL DEFAULT NULL ,
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`te_node_address`, `id_so`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `patrimonio`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `patrimonio` (
  `id_unid_organizacional_nivel1a` INT(11) NOT NULL ,
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  `dt_hr_alteracao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `te_node_address` VARCHAR(17) NOT NULL ,
  `id_unid_organizacional_nivel2` INT(11) NULL DEFAULT NULL ,
  `te_localizacao_complementar` VARCHAR(100) NULL DEFAULT NULL ,
  `te_info_patrimonio1` VARCHAR(20) NULL DEFAULT NULL ,
  `te_info_patrimonio2` VARCHAR(20) NULL DEFAULT NULL ,
  `te_info_patrimonio3` VARCHAR(20) NULL DEFAULT NULL ,
  `te_info_patrimonio4` VARCHAR(20) NULL DEFAULT NULL ,
  `te_info_patrimonio5` VARCHAR(20) NULL DEFAULT NULL ,
  `te_info_patrimonio6` VARCHAR(20) NULL DEFAULT NULL ,
  PRIMARY KEY (`dt_hr_alteracao`) ,
  INDEX `te_node_address` (`te_node_address` ASC, `id_so` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `patrimonio_config_interface`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `patrimonio_config_interface` (
  `id_local` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_etiqueta` VARCHAR(30) NOT NULL DEFAULT '' ,
  `nm_etiqueta` VARCHAR(15) NULL DEFAULT NULL ,
  `te_etiqueta` VARCHAR(50) NOT NULL DEFAULT '' ,
  `in_exibir_etiqueta` CHAR(1) NULL DEFAULT NULL ,
  `te_help_etiqueta` VARCHAR(100) NULL DEFAULT NULL ,
  `te_plural_etiqueta` VARCHAR(50) NULL DEFAULT NULL ,
  `nm_campo_tab_patrimonio` VARCHAR(50) NULL DEFAULT NULL ,
  `in_destacar_duplicidade` CHAR(1) NULL DEFAULT 'N' ,
  PRIMARY KEY (`id_etiqueta`, `id_local`) ,
  INDEX `id_localizacao` (`id_local` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `perfis_aplicativos_monitorados`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `perfis_aplicativos_monitorados` (
  `id_aplicativo` INT(11) NOT NULL AUTO_INCREMENT ,
  `nm_aplicativo` VARCHAR(100) NOT NULL DEFAULT '' ,
  `cs_car_inst_w9x` CHAR(2) NULL DEFAULT NULL ,
  `te_car_inst_w9x` VARCHAR(255) NULL DEFAULT NULL ,
  `cs_car_ver_w9x` CHAR(2) NULL DEFAULT NULL ,
  `te_car_ver_w9x` VARCHAR(255) NULL DEFAULT NULL ,
  `cs_car_inst_wnt` CHAR(2) NULL DEFAULT NULL ,
  `te_car_inst_wnt` VARCHAR(255) NULL DEFAULT NULL ,
  `cs_car_ver_wnt` CHAR(2) NULL DEFAULT NULL ,
  `te_car_ver_wnt` VARCHAR(255) NULL DEFAULT NULL ,
  `cs_ide_licenca` CHAR(2) NULL DEFAULT NULL ,
  `te_ide_licenca` VARCHAR(255) NULL DEFAULT NULL ,
  `dt_atualizacao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `te_arq_ver_eng_w9x` VARCHAR(100) NULL DEFAULT NULL ,
  `te_arq_ver_pat_w9x` VARCHAR(100) NULL DEFAULT NULL ,
  `te_arq_ver_eng_wnt` VARCHAR(100) NULL DEFAULT NULL ,
  `te_arq_ver_pat_wnt` VARCHAR(100) NULL DEFAULT NULL ,
  `te_dir_padrao_w9x` VARCHAR(100) NULL DEFAULT NULL ,
  `te_dir_padrao_wnt` VARCHAR(100) NULL DEFAULT NULL ,
  `id_so` INT(11) NULL DEFAULT '0' ,
  `te_descritivo` TEXT NULL DEFAULT NULL ,
  `in_disponibiliza_info` CHAR(1) NULL DEFAULT 'N' ,
  `in_disponibiliza_info_usuario_comum` CHAR(1) NOT NULL DEFAULT 'N' ,
  `dt_registro` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id_aplicativo`) )
ENGINE = InnoDB
AUTO_INCREMENT = 85
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `redes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `redes` (
  `id_local` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_servidor_autenticacao` INT(11) NULL DEFAULT NULL ,
  `id_ip_rede` VARCHAR(15) NOT NULL DEFAULT '' ,
  `nm_rede` VARCHAR(100) NULL DEFAULT NULL ,
  `te_observacao` VARCHAR(100) NULL DEFAULT NULL ,
  `nm_pessoa_contato1` VARCHAR(50) NULL DEFAULT NULL ,
  `nm_pessoa_contato2` VARCHAR(50) NULL DEFAULT NULL ,
  `nu_telefone1` VARCHAR(11) NULL DEFAULT NULL ,
  `te_email_contato2` VARCHAR(50) NULL DEFAULT NULL ,
  `nu_telefone2` VARCHAR(11) NULL DEFAULT NULL ,
  `te_email_contato1` VARCHAR(50) NULL DEFAULT NULL ,
  `te_serv_cacic` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `te_serv_updates` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `te_path_serv_updates` VARCHAR(255) NULL DEFAULT NULL ,
  `nm_usuario_login_serv_updates` VARCHAR(20) NULL DEFAULT NULL ,
  `te_senha_login_serv_updates` VARCHAR(20) NULL DEFAULT NULL ,
  `nu_porta_serv_updates` VARCHAR(4) NULL DEFAULT NULL ,
  `te_mascara_rede` VARCHAR(15) NULL DEFAULT NULL ,
  `dt_verifica_updates` DATE NULL DEFAULT NULL ,
  `nm_usuario_login_serv_updates_gerente` VARCHAR(20) NULL DEFAULT NULL ,
  `te_senha_login_serv_updates_gerente` VARCHAR(20) NULL DEFAULT NULL ,
  `nu_limite_ftp` INT(5) UNSIGNED NOT NULL DEFAULT '5' ,
  `cs_permitir_desativar_srcacic` CHAR(1) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'S' ,
  PRIMARY KEY (`id_ip_rede`, `id_local`) ,
  INDEX `id_ip_rede` (`id_ip_rede` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `redes_grupos_ftp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `redes_grupos_ftp` (
  `id_local` INT(11) NOT NULL DEFAULT '0' ,
  `id_ip_rede` VARCHAR(15) NOT NULL DEFAULT '0' ,
  `id_ip_estacao` VARCHAR(15) NOT NULL DEFAULT '0' ,
  `nu_hora_inicio` INT(12) NOT NULL DEFAULT '0' ,
  `nu_hora_fim` VARCHAR(12) NOT NULL DEFAULT '0' ,
  `id_ftp` INT(11) NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id_ftp`) )
ENGINE = InnoDB
AUTO_INCREMENT = 23595
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `redes_versoes_modulos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `redes_versoes_modulos` (
  `id_local` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_ip_rede` VARCHAR(15) NOT NULL DEFAULT '' ,
  `nm_modulo` VARCHAR(100) NOT NULL ,
  `te_versao_modulo` VARCHAR(20) NULL DEFAULT NULL ,
  `dt_atualizacao` DATETIME NOT NULL ,
  `cs_tipo_so` CHAR(20) NOT NULL DEFAULT 'MS-Windows' ,
  `te_hash` VARCHAR(40) NULL DEFAULT 'a' ,
  PRIMARY KEY (`id_ip_rede`, `nm_modulo`, `id_local`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `servidores_autenticacao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `servidores_autenticacao` (
  `id_servidor_autenticacao` INT(11) NOT NULL AUTO_INCREMENT ,
  `nm_servidor_autenticacao` VARCHAR(60) NOT NULL ,
  `te_ip_servidor_autenticacao` VARCHAR(15) NOT NULL ,
  `id_tipo_protocolo` VARCHAR(20) NOT NULL ,
  `nu_versao_protocolo` VARCHAR(10) NOT NULL ,
  `te_base_consulta_raiz` VARCHAR(100) NOT NULL ,
  `te_base_consulta_folha` VARCHAR(100) NOT NULL ,
  `te_atributo_identificador` VARCHAR(100) NOT NULL ,
  `te_atributo_retorna_nome` VARCHAR(100) NOT NULL ,
  `te_atributo_retorna_email` VARCHAR(100) NOT NULL ,
  `te_observacao` TEXT NOT NULL ,
  `in_ativo` CHAR(1) NOT NULL DEFAULT 'S' ,
  PRIMARY KEY (`id_servidor_autenticacao`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1
COMMENT = 'Servidores para Autenticacao do srCACIC';


-- -----------------------------------------------------
-- Table `so`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `so` (
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  `te_desc_so` VARCHAR(50) NULL DEFAULT NULL ,
  `sg_so` VARCHAR(20) NULL DEFAULT NULL ,
  `te_so` VARCHAR(50) NOT NULL DEFAULT '' ,
  `in_mswindows` CHAR(1) NOT NULL DEFAULT 'S' ,
  PRIMARY KEY (`id_so`, `te_so`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `softwares`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `softwares` (
  `id_software` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nm_software` VARCHAR(150) NULL DEFAULT NULL ,
  `te_descricao_software` VARCHAR(255) NULL DEFAULT NULL ,
  `qt_licenca` INT(11) NULL DEFAULT '0' ,
  `nr_midia` VARCHAR(10) NULL DEFAULT NULL ,
  `te_local_midia` VARCHAR(30) NULL DEFAULT NULL ,
  `te_obs` VARCHAR(200) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_software`) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `softwares_estacao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `softwares_estacao` (
  `nr_patrimonio` VARCHAR(20) NOT NULL DEFAULT '' ,
  `id_software` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `nm_computador` VARCHAR(50) NULL DEFAULT NULL ,
  `dt_autorizacao` DATE NULL DEFAULT NULL ,
  `nr_processo` VARCHAR(11) NULL DEFAULT NULL ,
  `dt_expiracao_instalacao` DATE NULL DEFAULT NULL ,
  `id_aquisicao_particular` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `dt_desinstalacao` DATE NULL DEFAULT NULL ,
  `te_observacao` VARCHAR(90) NULL DEFAULT NULL ,
  `nr_patr_destino` VARCHAR(20) NULL DEFAULT NULL ,
  PRIMARY KEY (`nr_patrimonio`, `id_software`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `softwares_inventariados`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `softwares_inventariados` (
  `id_software_inventariado` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nm_software_inventariado` VARCHAR(100) NOT NULL DEFAULT '' ,
  `id_tipo_software` INT(11) NULL DEFAULT '0' ,
  `id_software` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `te_hash` VARCHAR(40) NOT NULL ,
  PRIMARY KEY (`id_software_inventariado`) ,
  INDEX `nm_software_inventariado` (`nm_software_inventariado` ASC) ,
  INDEX `id_software` (`id_software_inventariado` ASC) ,
  INDEX `idx_nm_software_inventariado` (`nm_software_inventariado` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 3399
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `softwares_inventariados_estacoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `softwares_inventariados_estacoes` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_software_inventariado` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`te_node_address`, `id_so`, `id_software_inventariado`) ,
  INDEX `id_software` (`id_software_inventariado` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `srcacic_chats`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `srcacic_chats` (
  `id_conexao` INT(11) NOT NULL ,
  `dt_hr_mensagem` DATETIME NOT NULL ,
  `te_mensagem` TEXT CHARACTER SET 'utf8' NOT NULL ,
  `cs_origem` CHAR(3) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'cli' ,
  INDEX `id_conexao` (`id_conexao` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COMMENT = 'Log de Atividades no Sistema CACIC';


-- -----------------------------------------------------
-- Table `srcacic_conexoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `srcacic_conexoes` (
  `id_conexao` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador da conexão' ,
  `id_sessao` INT(11) NOT NULL ,
  `id_usuario_cli` INT(11) NOT NULL DEFAULT '0' ,
  `te_node_address_cli` VARCHAR(17) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `te_documento_referencial` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `id_so_cli` INT(11) NOT NULL ,
  `te_motivo_conexao` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Descritivo breve sobre o motivo da conexão' ,
  `dt_hr_inicio_conexao` DATETIME NOT NULL ,
  `dt_hr_ultimo_contato` DATETIME NOT NULL ,
  PRIMARY KEY (`id_conexao`) )
ENGINE = InnoDB
AUTO_INCREMENT = 306
DEFAULT CHARACTER SET = latin1
COMMENT = 'Registros de Conexões efetuadas às sessões abertas';


-- -----------------------------------------------------
-- Table `srcacic_sessoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `srcacic_sessoes` (
  `id_sessao` INT(11) NOT NULL AUTO_INCREMENT ,
  `dt_hr_inicio_sessao` DATETIME NOT NULL ,
  `nm_acesso_usuario_srv` VARCHAR(30) CHARACTER SET 'utf8' NOT NULL ,
  `nm_completo_usuario_srv` VARCHAR(100) NOT NULL DEFAULT 'NoNoNo' ,
  `te_email_usuario_srv` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `te_node_address_srv` VARCHAR(17) CHARACTER SET 'utf8' NOT NULL ,
  `id_so_srv` INT(11) NOT NULL ,
  `dt_hr_ultimo_contato` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id_sessao`) ,
  INDEX `idx_dtHrInicioSessao` (`dt_hr_inicio_sessao` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 569
DEFAULT CHARACTER SET = latin1
COMMENT = 'Log de Atividades no Sistema CACIC';


-- -----------------------------------------------------
-- Table `testes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `testes` (
  `id_transacao` INT(11) NOT NULL AUTO_INCREMENT ,
  `te_linha` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id_transacao`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5481
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `tipos_licenca`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tipos_licenca` (
  `id_tipo_licenca` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `te_tipo_licenca` VARCHAR(20) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_tipo_licenca`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `tipos_software`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tipos_software` (
  `id_tipo_software` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `te_descricao_tipo_software` VARCHAR(30) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`id_tipo_software`) )
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `tipos_unidades_disco`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tipos_unidades_disco` (
  `id_tipo_unid_disco` CHAR(1) NOT NULL DEFAULT '' ,
  `te_tipo_unid_disco` VARCHAR(25) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_tipo_unid_disco`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `unidades_disco`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `unidades_disco` (
  `te_letra` CHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL ,
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_tipo_unid_disco` CHAR(1) NULL DEFAULT NULL ,
  `nu_serial` VARCHAR(12) NULL DEFAULT NULL ,
  `nu_capacidade` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `nu_espaco_livre` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `te_unc` VARCHAR(60) NULL DEFAULT NULL ,
  `cs_sist_arq` VARCHAR(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`te_letra`, `id_so`, `te_node_address`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `unid_organizacional_nivel1`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `unid_organizacional_nivel1` (
  `id_unid_organizacional_nivel1` INT(11) NOT NULL AUTO_INCREMENT ,
  `nm_unid_organizacional_nivel1` VARCHAR(50) NULL DEFAULT NULL ,
  `te_endereco_uon1` VARCHAR(80) NULL DEFAULT NULL ,
  `te_bairro_uon1` VARCHAR(30) NULL DEFAULT NULL ,
  `te_cidade_uon1` VARCHAR(50) NULL DEFAULT NULL ,
  `te_uf_uon1` CHAR(2) NULL DEFAULT NULL ,
  `nm_responsavel_uon1` VARCHAR(80) NULL DEFAULT NULL ,
  `te_email_responsavel_uon1` VARCHAR(50) NULL DEFAULT NULL ,
  `nu_tel1_responsavel_uon1` VARCHAR(10) NULL DEFAULT NULL ,
  `nu_tel2_responsavel_uon1` VARCHAR(10) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_unid_organizacional_nivel1`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `unid_organizacional_nivel1a`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `unid_organizacional_nivel1a` (
  `id_unid_organizacional_nivel1` INT(11) NOT NULL ,
  `id_unid_organizacional_nivel1a` INT(11) NOT NULL AUTO_INCREMENT ,
  `nm_unid_organizacional_nivel1a` VARCHAR(70) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_unid_organizacional_nivel1a`) )
ENGINE = InnoDB
AUTO_INCREMENT = 260
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `unid_organizacional_nivel2`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `unid_organizacional_nivel2` (
  `id_local` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_unid_organizacional_nivel2` INT(11) NOT NULL AUTO_INCREMENT ,
  `id_unid_organizacional_nivel1a` INT(11) NOT NULL DEFAULT '0' ,
  `nm_unid_organizacional_nivel2` VARCHAR(70) NOT NULL ,
  `te_endereco_uon2` VARCHAR(80) NULL DEFAULT NULL ,
  `te_bairro_uon2` VARCHAR(30) NULL DEFAULT NULL ,
  `te_cidade_uon2` VARCHAR(50) NULL DEFAULT NULL ,
  `te_uf_uon2` CHAR(2) NULL DEFAULT NULL ,
  `nm_responsavel_uon2` VARCHAR(80) NULL DEFAULT NULL ,
  `te_email_responsavel_uon2` VARCHAR(50) NULL DEFAULT NULL ,
  `nu_tel1_responsavel_uon2` VARCHAR(10) NULL DEFAULT NULL ,
  `nu_tel2_responsavel_uon2` VARCHAR(10) NULL DEFAULT NULL ,
  `dt_registro` DATETIME NULL DEFAULT '0000-00-00 00:00:00' ,
  `cs_atualizado` CHAR(1) NOT NULL DEFAULT 'N' ,
  PRIMARY KEY (`id_unid_organizacional_nivel2`, `id_unid_organizacional_nivel1a`, `id_local`) ,
  INDEX `id_localizacao` (`id_local` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 4718
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `usuarios`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `usuarios` (
  `id_local` INT(11) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_servidor_autenticacao` INT(11) NULL DEFAULT NULL ,
  `id_usuario` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nm_usuario_acesso` VARCHAR(20) NOT NULL DEFAULT '' ,
  `nm_usuario_completo` VARCHAR(60) NOT NULL DEFAULT '' ,
  `te_senha` VARCHAR(60) NOT NULL DEFAULT '' ,
  `dt_log_in` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `id_grupo_usuarios` INT(1) NOT NULL DEFAULT '1' ,
  `te_emails_contato` VARCHAR(100) NULL DEFAULT NULL ,
  `te_telefones_contato` VARCHAR(100) NULL DEFAULT NULL ,
  `te_locais_secundarios` VARCHAR(200) NULL DEFAULT NULL ,
  PRIMARY KEY (`id_usuario`) ,
  INDEX `id_localizacao` (`id_local` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 256
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `variaveis_ambiente`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `variaveis_ambiente` (
  `id_variavel_ambiente` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nm_variavel_ambiente` VARCHAR(100) NOT NULL DEFAULT '' ,
  `te_hash` VARCHAR(40) NOT NULL ,
  PRIMARY KEY (`id_variavel_ambiente`) )
ENGINE = InnoDB
AUTO_INCREMENT = 142
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `variaveis_ambiente_estacoes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `variaveis_ambiente_estacoes` (
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `id_so` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `id_variavel_ambiente` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `vl_variavel_ambiente` VARCHAR(100) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`te_node_address`, `id_so`, `id_variavel_ambiente`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `versoes_softwares`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `versoes_softwares` (
  `id_so` INT(11) NOT NULL DEFAULT '0' ,
  `te_node_address` VARCHAR(17) NOT NULL DEFAULT '' ,
  `te_versao_bde` VARCHAR(10) NULL DEFAULT NULL ,
  `te_versao_dao` VARCHAR(5) NULL DEFAULT NULL ,
  `te_versao_ado` VARCHAR(5) NULL DEFAULT NULL ,
  `te_versao_odbc` VARCHAR(15) NULL DEFAULT NULL ,
  `te_versao_directx` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `te_versao_acrobat_reader` VARCHAR(10) NULL DEFAULT NULL ,
  `te_versao_ie` VARCHAR(18) NULL DEFAULT NULL ,
  `te_versao_mozilla` VARCHAR(12) NULL DEFAULT NULL ,
  `te_versao_jre` VARCHAR(6) NULL DEFAULT NULL ,
  PRIMARY KEY (`te_node_address`, `id_so`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
