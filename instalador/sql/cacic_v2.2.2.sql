-- --------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-v2.2.2
-- SGBD: MySQL-4.1.20
-- MySQL Workbench 5.2.8 Beta
-- --------------------------------------------------------
#
# criado com o auxilio de: MySQL Diff 1.5.0
# http://www.mysqldiff.com


SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE componentes_estacoes (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '',
    id_so int(11) NOT NULL COMMENT '',
    cs_tipo_componente varchar(100) NOT NULL DEFAULT '' COMMENT '',
    te_valor text NOT NULL DEFAULT '' COMMENT '',
    INDEX te_node_address (te_node_address, id_so, cs_tipo_componente)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE componentes_estacoes_historico (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '',
    id_so int(11) NOT NULL COMMENT '',
    cs_tipo_componente varchar(100) NOT NULL DEFAULT '' COMMENT '',
    te_valor text NOT NULL DEFAULT '' COMMENT '',
    dt_alteracao datetime NOT NULL COMMENT '',
    cs_tipo_alteracao varchar(3) NOT NULL DEFAULT '' COMMENT '',
    INDEX te_node_address (te_node_address, id_so, cs_tipo_componente)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE insucessos_instalacao (
    te_ip varchar(15) NOT NULL DEFAULT '' COMMENT '',
    te_so varchar(60) NOT NULL DEFAULT '' COMMENT '',
    id_usuario varchar(60) NOT NULL DEFAULT '' COMMENT '',
    dt_datahora datetime NOT NULL COMMENT '',
    cs_indicador char(1) NOT NULL DEFAULT '' COMMENT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE unid_organizacional_nivel1a (
    id_unid_organizacional_nivel1 int(11) NOT NULL COMMENT '',
    id_unid_organizacional_nivel1a int(11) NOT NULL COMMENT '' auto_increment,
    nm_unid_organizacional_nivel1a varchar(50) NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (id_unid_organizacional_nivel1a)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE acoes_excecoes
    ADD id_local int(11) NOT NULL DEFAULT '0' COMMENT '' FIRST,
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE computadores
    MODIFY te_so varchar(50) NULL DEFAULT NULL COMMENT '',
    MODIFY te_mem_ram_desc varchar(200) NULL DEFAULT NULL COMMENT '',
    ADD te_palavra_chave char(30) NOT NULL DEFAULT 'abcdefghij',
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE configuracoes_locais
    ADD dt_hr_alteracao_patrim_uon1a datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '' AFTER dt_hr_alteracao_patrim_uon1,
    ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT '' AFTER id_default_body_bgcolor,
    ALTER dt_hr_alteracao_patrim_uon2 SET DEFAULT '0000-00-00 00:00:00',
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE configuracoes_padrao
    ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT '' AFTER id_default_body_bgcolor,
    ADD nu_rel_maxlinhas smallint unsigned default 50 AFTER nu_exec_apos,
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE descricao_hardware
    ADD te_locais_notificacao_ativada text NULL DEFAULT NULL COMMENT 'Locais onde a notificação de alteração de hardware encontra-se ativa.' AFTER te_desc_hardware,
    DROP cs_notificacao_ativada,
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE patrimonio
    ADD id_unid_organizacional_nivel1a int(11) NOT NULL COMMENT '' FIRST,
    DROP id_unid_organizacional_nivel1,
    MODIFY te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '',
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE redes_grupos_ftp
    ADD id_ftp int(11) NOT NULL COMMENT '' auto_increment AFTER nu_hora_fim,
    ADD PRIMARY KEY (id_ftp),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE redes_versoes_modulos
    ADD dt_atualizacao datetime NOT NULL COMMENT '' AFTER te_versao_modulo,
    ADD cs_tipo_so char(20) NOT NULL DEFAULT 'MS-Windows' AFTER dt_atualizacao,
    ADD te_hash varchar(40) NULL DEFAULT 'a' AFTER cs_tipo_so,    
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_ip_rede, nm_modulo, id_local),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE so
    ADD te_so varchar(50) NOT NULL DEFAULT '' COMMENT '' AFTER sg_so,
    MODIFY `sg_so` varchar(20) default NULL,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_so, te_so),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE softwares_inventariados
    ADD te_hash varchar(40) NOT NULL DEFAULT '' COMMENT '' AFTER id_software,
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE unid_organizacional_nivel2
    ADD id_unid_organizacional_nivel1a int(11) NOT NULL COMMENT '' AFTER id_unid_organizacional_nivel2,
    DROP id_unid_organizacional_nivel1,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_unid_organizacional_nivel2, id_unid_organizacional_nivel1a, id_local),
    ENGINE=InnoDB CHARACTER SET=latin1;

ALTER TABLE tipos_software
    MODIFY id_tipo_software int(10) unsigned NOT NULL auto_increment;

ALTER TABLE usuarios
    ADD te_locais_secundarios varchar(200) NULL DEFAULT NULL COMMENT '' AFTER te_telefones_contato,
    MODIFY nm_usuario_acesso varchar(40) NOT NULL DEFAULT '' COMMENT '',
    MODIFY te_senha varchar(60) NOT NULL DEFAULT '' COMMENT '',
    MODIFY id_grupo_usuarios int(1) NOT NULL DEFAULT '1' COMMENT '',
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE variaveis_ambiente
    ADD te_hash varchar(40) NOT NULL DEFAULT '' COMMENT '' AFTER nm_variavel_ambiente,
    ENGINE=InnoDB CHARACTER SET=latin1;

INSERT INTO `patrimonio_config_interface` 
        (`id_local`, `id_etiqueta`, `nm_etiqueta`, `te_etiqueta`, `in_exibir_etiqueta`, `te_help_etiqueta`,
         `te_plural_etiqueta`, `nm_campo_tab_patrimonio`, `in_destacar_duplicidade`)
     VALUES
        (1, 'etiqueta1a', 'Etiqueta 1a', 'Linha de Negócio', 'S', 'Selecione a Linha de Negócio', 'Linhas de Negócio', 'id_unid_organizacional_nivel1a', 'N');
        
ALTER TABLE perfis_aplicativos_monitorados
    MODIFY te_ide_licenca varchar(200) default NULL,
    ENGINE=InnoDB CHARACTER SET=latin1;
--
-- Update ID_LOCAL on tables
--

UPDATE acoes_excecoes SET id_local=1;

ALTER TABLE aquisicoes
    DROP PRIMARY KEY,
    MODIFY id_aquisicao int(10) auto_increment,
    MODIFY nr_notafiscal varchar(20),
    ADD PRIMARY KEY (id_aquisicao),
    ENGINE=InnoDB CHARACTER SET=latin1;

DELETE FROM `descricoes_colunas_computadores` WHERE nm_campo='te_cpu_freq';
INSERT INTO `descricoes_colunas_computadores` 
             (`nm_campo`,`te_descricao_campo`,`cs_condicao_pesquisa`)
            VALUES
             ('te_cpu_frequencia','Frequência da CPU','S');

--
-- Update SO identication
--
UPDATE so SET te_so='1.4.0' WHERE id_so=1;
UPDATE so SET te_so='1.4.0.B' WHERE id_so=2;
UPDATE so SET te_so='1.4.10' WHERE id_so=3;
UPDATE so SET te_so='1.4.10.A' WHERE id_so=4;
UPDATE so SET te_so='1.4.90' WHERE id_so=5;
UPDATE so SET te_so='2.5.0.3.272', te_desc_so='Windows 2000 Sever' WHERE id_so=7;
UPDATE so SET te_so='2.5.1.1.256', te_desc_so='Windows XP Professional' WHERE id_so=8;
UPDATE so SET te_so='2.5.2.3.274', te_desc_so='Windows Enterprise' WHERE id_so=13;

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

SET FOREIGN_KEY_CHECKS = 1;

