-- ----------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-v2.4.0-rc1
-- SGBD: MySQL-4.1.20
-- MySQL Workbench 5.2.8 Beta
-- ----------------------------------------------------------

ALTER TABLE aquisicoes
    DROP PRIMARY KEY,
    MODIFY id_aquisicao int(10) auto_increment,
    MODIFY nr_notafiscal varchar(20),
    ADD PRIMARY KEY (id_aquisicao),
    ENGINE=InnoDB CHARACTER SET=latin1;

UPDATE descricoes_colunas_computadores 
    SET nm_campo='te_cpu_frequencia' 
    WHERE nm_campo='te_cpu_freq';

ALTER TABLE componentes_estacoes_historico
    MODIFY te_valor text;

--
-- Update 2.5 Dataprev
-- 
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
COLLATE = latin1_swedish_ci
COMMENT = 'Servidores para Autenticacao do srCACIC';

CREATE  TABLE IF NOT EXISTS `srcacic_chats` (
  `id_conexao` INT(11) NOT NULL ,
  `dt_hr_mensagem` DATETIME NOT NULL ,
  `te_mensagem` TEXT CHARACTER SET 'utf8' NOT NULL ,
  `cs_origem` CHAR(3) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'cli' ,
  INDEX `id_conexao` (`id_conexao` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
COMMENT = 'Log de Atividades no Sistema CACIC';

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
COLLATE = latin1_swedish_ci
COMMENT = 'Registros de Conexões efetuadas às sessões abertas';

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
COLLATE = latin1_swedish_ci
COMMENT = 'Log de Atividades no Sistema CACIC';

ALTER TABLE `unidades_disco` CHANGE COLUMN `te_letra` `te_letra` CHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL  ;

ALTER TABLE `componentes_estacoes_historico` CHARACTER SET = latin1 , CHANGE COLUMN `te_valor` `te_valor` VARCHAR(200) NOT NULL  ;

ALTER TABLE `configuracoes_locais` ADD COLUMN `nu_porta_srcacic` VARCHAR(5) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '5900'  AFTER `te_exibe_graficos` , ADD COLUMN `nu_timeout_srcacic` TINYINT(3) NOT NULL DEFAULT '30' COMMENT 'Valor para timeout do servidor srCACIC'  AFTER `nm_organizacao` , CHANGE COLUMN `te_serv_updates_padrao` `te_serv_updates_padrao` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL  , CHANGE COLUMN `te_serv_cacic_padrao` `te_serv_cacic_padrao` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL  ;

ALTER TABLE `configuracoes_padrao` ADD COLUMN `nu_porta_srcacic` VARCHAR(5) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '5900'  AFTER `te_exibe_graficos` , ADD COLUMN `nu_resolucao_grafico_h` SMALLINT(5) NOT NULL DEFAULT '0'  AFTER `nu_porta_srcacic` , ADD COLUMN `nu_resolucao_grafico_w` SMALLINT(5) NOT NULL DEFAULT '0'  AFTER `nu_resolucao_grafico_h` , ADD COLUMN `nu_timeout_srcacic` TINYINT(3) NOT NULL DEFAULT '30' COMMENT 'Valor padrao para timeout do servidor srCACIC'  AFTER `nm_organizacao` , CHANGE COLUMN `te_serv_updates_padrao` `te_serv_updates_padrao` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL  , CHANGE COLUMN `te_serv_cacic_padrao` `te_serv_cacic_padrao` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL  ;

ALTER TABLE `perfis_aplicativos_monitorados` CHANGE COLUMN `te_car_inst_w9x` `te_car_inst_w9x` VARCHAR(255) NULL DEFAULT NULL  , CHANGE COLUMN `te_car_ver_w9x` `te_car_ver_w9x` VARCHAR(255) NULL DEFAULT NULL  , CHANGE COLUMN `te_car_inst_wnt` `te_car_inst_wnt` VARCHAR(255) NULL DEFAULT NULL  , CHANGE COLUMN `te_car_ver_wnt` `te_car_ver_wnt` VARCHAR(255) NULL DEFAULT NULL  , CHANGE COLUMN `te_ide_licenca` `te_ide_licenca` VARCHAR(255) NULL DEFAULT NULL  ;

ALTER TABLE `redes` ADD COLUMN `cs_permitir_desativar_srcacic` CHAR(1) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'S'  AFTER `nu_limite_ftp` , ADD COLUMN `id_servidor_autenticacao` INT(11) NULL DEFAULT NULL  AFTER `id_local` , CHANGE COLUMN `te_serv_cacic` `te_serv_cacic` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL  , CHANGE COLUMN `te_serv_updates` `te_serv_updates` VARCHAR(60) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL  ;

ALTER TABLE `redes_versoes_modulos` CHANGE COLUMN `nm_modulo` `nm_modulo` VARCHAR(100) NOT NULL  ;

ALTER TABLE `so` ADD COLUMN `in_mswindows` CHAR(1) NOT NULL DEFAULT 'S'  AFTER `te_so` ;

ALTER TABLE `unid_organizacional_nivel1a` CHANGE COLUMN `nm_unid_organizacional_nivel1a` `nm_unid_organizacional_nivel1a` VARCHAR(70) NULL DEFAULT NULL  ;

ALTER TABLE `unid_organizacional_nivel2` ADD COLUMN `cs_atualizado` CHAR(1) NOT NULL DEFAULT 'N'  AFTER `dt_registro` , CHANGE COLUMN `nm_unid_organizacional_nivel2` `nm_unid_organizacional_nivel2` VARCHAR(70) NOT NULL  ;

ALTER TABLE `usuarios` ADD COLUMN `id_servidor_autenticacao` INT(11) NULL DEFAULT NULL  AFTER `id_local` ;

ALTER TABLE tipos_licenca
  MODIFY te_tipo_licenca VARCHAR(50) NULL DEFAULT NULL;
ALTER TABLE configuracoes_padrao
  ADD  `te_standard_language` VARCHAR(5) NOT NULL DEFAULT 'pt_BR';

-- -----------------------------------------------------
-- Table `preferencia_usuarios`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `preferencia_usuarios` (
  `id_usuario` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `te_std_language` VARCHAR(05) ,
  PRIMARY KEY (`id_usuario`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE perfis_aplicativos_monitorados
    MODIFY te_car_inst_w9x varchar(255),
    MODIFY te_car_ver_w9x varchar(255),
    MODIFY te_car_inst_wnt varchar(255),
    MODIFY te_ide_licenca varchar(255),
    MODIFY te_arq_ver_eng_w9x varchar(255),
    MODIFY te_arq_ver_pat_w9x varchar(255),
    MODIFY te_arq_ver_eng_wnt varchar(255),
    MODIFY te_arq_ver_pat_wnt varchar(255),
    MODIFY te_dir_padrao_w9x varchar(255),
    MODIFY te_dir_padrao_wnt varchar(255),
    MODIFY te_car_ver_wnt varchar(255);
  