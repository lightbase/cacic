-- --------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-jun2005
-- SGBD: MySQL-4.1.20
-- --------------------------------------------------------
#
# criado com o auxilio de: MySQL Diff 1.5.0
# http://www.mysqldiff.com

SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE aplicativos_redes (
    id_local int(11) NOT NULL COMMENT '',
    id_ip_rede varchar(15) NOT NULL DEFAULT '' COMMENT '',
    id_aplicativo int(11) unsigned NOT NULL DEFAULT '0' COMMENT '',
    PRIMARY KEY (id_local, id_ip_rede, id_aplicativo)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE aquisicoes (
    id_aquisicao int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    dt_aquisicao date NULL DEFAULT NULL COMMENT '',
    nr_processo varchar(11) NULL DEFAULT NULL COMMENT '',
    nm_empresa varchar(45) NULL DEFAULT NULL COMMENT '',
    nm_proprietario varchar(45) NULL DEFAULT NULL COMMENT '',
    nr_notafiscal int(10) unsigned NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (id_aquisicao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE aquisicoes_item (
    id_aquisicao int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    id_software int(10) unsigned NOT NULL COMMENT '',
    id_tipo_licenca int(10) unsigned NOT NULL COMMENT '',
    qt_licenca int(11) NULL DEFAULT NULL COMMENT '',
    dt_vencimento_licenca date NULL DEFAULT NULL COMMENT '',
    te_obs varchar(50) NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (id_aquisicao, id_software, id_tipo_licenca)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
    te_valor varchar(200) NOT NULL DEFAULT '' COMMENT '',
    dt_alteracao datetime NOT NULL COMMENT '',
    cs_tipo_alteracao varchar(3) NOT NULL DEFAULT '' COMMENT '',
    INDEX te_node_address (te_node_address, id_so, cs_tipo_componente)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE configuracoes_locais (
    id_local int(11) unsigned NOT NULL COMMENT '',
    te_notificar_mudanca_hardware text NULL DEFAULT NULL COMMENT '',
    in_exibe_erros_criticos char(1) NULL DEFAULT 'N' COMMENT '',
    in_exibe_bandeja char(1) NULL DEFAULT 'S' COMMENT '',
    nu_exec_apos int(11) NULL DEFAULT '10' COMMENT '',
    dt_hr_alteracao_patrim_interface datetime NULL DEFAULT NULL COMMENT '',
    dt_hr_alteracao_patrim_uon1 datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_alteracao_patrim_uon1a datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_alteracao_patrim_uon2 datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_coleta_forcada datetime NULL DEFAULT NULL COMMENT '',
    te_notificar_mudanca_patrim text NULL DEFAULT NULL COMMENT '',
    nm_organizacao varchar(150) NULL DEFAULT NULL COMMENT '',
    nu_intervalo_exec int(11) NULL DEFAULT '4' COMMENT '',
    nu_intervalo_renovacao_patrim int(11) NULL DEFAULT '0' COMMENT '',
    te_senha_adm_agente varchar(30) NULL DEFAULT 'ADMINCACIC' COMMENT '',
    te_serv_updates_padrao varchar(20) NULL DEFAULT NULL COMMENT '',
    te_serv_cacic_padrao varchar(20) NULL DEFAULT NULL COMMENT '',
    te_enderecos_mac_invalidos text NULL DEFAULT NULL COMMENT '',
    te_janelas_excecao text NULL DEFAULT NULL COMMENT '',
    te_nota_email_gerentes text NULL DEFAULT NULL COMMENT '',
    cs_abre_janela_patr char(1) NOT NULL DEFAULT 'N' COMMENT '',
    id_default_body_bgcolor varchar(10) NOT NULL DEFAULT '#EBEBEB' COMMENT '',
    te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT '',
    PRIMARY KEY (id_local)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE configuracoes_padrao (
    in_exibe_erros_criticos char(1) NULL DEFAULT NULL COMMENT '',
    in_exibe_bandeja char(1) NULL DEFAULT NULL COMMENT '',
    nu_exec_apos int(11) NULL DEFAULT NULL COMMENT '',
    rel_maxlinhas int(1) default 50,
    nm_organizacao varchar(150) NULL DEFAULT NULL COMMENT '',
    nu_intervalo_exec int(11) NULL DEFAULT NULL COMMENT '',
    nu_intervalo_renovacao_patrim int(11) NULL DEFAULT NULL COMMENT '',
    te_senha_adm_agente varchar(30) NULL DEFAULT NULL COMMENT '',
    te_serv_updates_padrao varchar(20) NULL DEFAULT NULL COMMENT '',
    te_serv_cacic_padrao varchar(20) NULL DEFAULT NULL COMMENT '',
    te_enderecos_mac_invalidos text NULL DEFAULT NULL COMMENT '',
    te_janelas_excecao text NULL DEFAULT NULL COMMENT '',
    cs_abre_janela_patr char(1) NOT NULL DEFAULT 'S' COMMENT '',
    id_default_body_bgcolor varchar(10) NOT NULL DEFAULT '#EBEBEB' COMMENT '',
    te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE contas (
    id_conta int(10) unsigned NOT NULL COMMENT '' auto_increment,
    nm_responsavel varchar(30) NOT NULL DEFAULT '' COMMENT '',
    PRIMARY KEY (id_conta)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE descricoes_colunas_computadores (
    nm_campo varchar(100) NOT NULL DEFAULT '' COMMENT '',
    te_descricao_campo varchar(100) NOT NULL DEFAULT '' COMMENT '',
    cs_condicao_pesquisa char(1) NOT NULL DEFAULT 'S' COMMENT '',
    UNIQUE nm_campo (nm_campo)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE historicos_hardware (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '',
    id_so int(11) NOT NULL COMMENT '',
    campo_alterado varchar(45) NULL DEFAULT '' COMMENT '',
    valor_antigo varchar(45) NULL DEFAULT '' COMMENT '',
    data_anterior datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    novo_valor varchar(45) NULL DEFAULT '' COMMENT '',
    nova_data datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    PRIMARY KEY (te_node_address, id_so)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE historicos_outros_softwares (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '',
    id_so int(10) unsigned NOT NULL COMMENT '',
    id_software_inventariado int(10) unsigned NOT NULL COMMENT '',
    dt_hr_inclusao datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_ult_coleta datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    PRIMARY KEY (te_node_address, id_so, id_software_inventariado)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE historicos_software (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '',
    id_so int(11) unsigned NOT NULL COMMENT '',
    id_software_inventariado int(11) unsigned NOT NULL COMMENT '',
    dt_hr_inclusao datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_ult_coleta datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    PRIMARY KEY (te_node_address, id_so, id_software_inventariado),
    INDEX id_software (id_software_inventariado)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE historicos_software_completo (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '',
    id_so int(10) unsigned NOT NULL COMMENT '',
    id_software_inventariado int(10) unsigned NOT NULL COMMENT '',
    dt_hr_inclusao datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_ult_coleta datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    PRIMARY KEY (te_node_address, id_so, id_software_inventariado, dt_hr_inclusao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE insucessos_instalacao (
    te_ip varchar(15) NOT NULL DEFAULT '' COMMENT '',
    te_so varchar(60) NOT NULL DEFAULT '' COMMENT '',
    id_usuario varchar(60) NOT NULL DEFAULT '' COMMENT '',
    dt_datahora datetime NOT NULL COMMENT '',
    cs_indicador char(1) NOT NULL DEFAULT '' COMMENT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE locais (
    id_local int(11) unsigned NOT NULL COMMENT '' auto_increment,
    nm_local varchar(100) NOT NULL DEFAULT '' COMMENT '',
    sg_local varchar(20) NOT NULL DEFAULT '' COMMENT '',
    te_observacao varchar(255) NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (id_local),
    INDEX sg_localizacao (sg_local)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE log (
    dt_acao datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    cs_acao varchar(20) NOT NULL DEFAULT '' COMMENT '',
    nm_script varchar(255) NOT NULL DEFAULT '' COMMENT '',
    nm_tabela varchar(255) NOT NULL DEFAULT '' COMMENT '',
    id_usuario int(11) NOT NULL DEFAULT '0' COMMENT '',
    te_ip_origem varchar(15) NOT NULL DEFAULT '' COMMENT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE softwares (
    id_software int(10) unsigned NOT NULL COMMENT '' auto_increment,
    nm_software varchar(150) NULL DEFAULT NULL COMMENT '',
    te_descricao_software varchar(255) NULL DEFAULT NULL COMMENT '',
    qt_licenca int(11) NULL DEFAULT '0' COMMENT '',
    nr_midia varchar(10) NULL DEFAULT NULL COMMENT '',
    te_local_midia varchar(30) NULL DEFAULT NULL COMMENT '',
    te_obs varchar(200) NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (id_software)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE softwares_estacao (
    nr_patrimonio varchar(20) NOT NULL DEFAULT '' COMMENT '',
    id_software int(10) unsigned NOT NULL COMMENT '',
    nm_computador varchar(50) NULL DEFAULT NULL COMMENT '',
    dt_autorizacao date NULL DEFAULT NULL COMMENT '',
    nr_processo varchar(11) NULL DEFAULT NULL COMMENT '',
    dt_expiracao_instalacao date NULL DEFAULT NULL COMMENT '',
    id_aquisicao_particular int(10) unsigned NULL DEFAULT NULL COMMENT '',
    dt_desinstalacao date NULL DEFAULT NULL COMMENT '',
    te_observacao varchar(90) NULL DEFAULT NULL COMMENT '',
    nr_patr_destino varchar(20) NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (nr_patrimonio, id_software)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE tipos_licenca (
    id_tipo_licenca int(10) unsigned NOT NULL COMMENT '' auto_increment,
    te_tipo_licenca varchar(20) NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (id_tipo_licenca)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE tipos_software (
    id_tipo_software int(10) unsigned NOT NULL auto_increment,
    te_descricao_tipo_software varchar(30) NOT NULL DEFAULT '' COMMENT '',
    PRIMARY KEY (id_tipo_software)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE unid_organizacional_nivel1a (
    id_unid_organizacional_nivel1 int(11) NOT NULL COMMENT '',
    id_unid_organizacional_nivel1a int(11) NOT NULL COMMENT '' auto_increment,
    nm_unid_organizacional_nivel1a varchar(50) NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (id_unid_organizacional_nivel1a)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE acoes_excecoes
    ADD id_local int(11) NOT NULL COMMENT '' FIRST,
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE acoes_redes
    ADD id_local int(11) NOT NULL COMMENT '' AFTER id_acao,
    ADD cs_situacao char(1) NOT NULL DEFAULT 'T' COMMENT '' AFTER dt_hr_coleta_forcada,
    ADD dt_hr_alteracao datetime NULL DEFAULT NULL COMMENT '' AFTER cs_situacao,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_local, id_ip_rede, id_acao),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE acoes_so
    ADD id_local int(11) NOT NULL COMMENT '' FIRST,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_acao, id_so, id_local),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE compartilhamentos
    ADD INDEX node_so_tipocompart (te_node_address, id_so, cs_tipo_compart),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE computadores
    ADD te_so varchar(50) NULL DEFAULT NULL COMMENT '' AFTER id_so,
    ADD te_versao_gercols varchar(10) NULL DEFAULT NULL COMMENT '' AFTER te_versao_cacic,
    ADD id_conta int(10) unsigned NULL COMMENT '' AFTER te_origem_mac,
    MODIFY te_mem_ram_desc varchar(200) NULL DEFAULT NULL COMMENT '',
    MODIFY te_palavra_chave char(30) NOT NULL DEFAULT 'abcdefghij',
    ADD INDEX te_ip (te_ip),
    ADD INDEX te_node_address (te_node_address),
    ADD INDEX te_nome_computador (te_nome_computador),
    ENGINE=InnoDB CHARACTER SET=latin1;


DROP TABLE configuracoes;

ALTER TABLE descricao_hardware
    ADD te_locais_notificacao_ativada text NULL DEFAULT NULL COMMENT 'Locais onde a notificação de alteração de hardware encontra-se ativa.' AFTER te_desc_hardware,
    DROP cs_notificacao_ativada,
    ENGINE=InnoDB CHARACTER SET=latin1;


DROP TABLE gerentes;

DROP TABLE gerentes_versoes_modulos;

ALTER TABLE grupo_usuarios
    ADD cs_nivel_administracao tinyint(2) NOT NULL DEFAULT '0' COMMENT '' AFTER te_descricao_grupo,
    MODIFY id_grupo_usuarios int(2) NOT NULL COMMENT '' auto_increment,
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE officescan
    ADD PRIMARY KEY (te_node_address, id_so),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE patrimonio
    ADD id_unid_organizacional_nivel1a int(11) NOT NULL COMMENT '' FIRST,
    DROP id_unid_organizacional_nivel1,
    MODIFY te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '',
    ADD INDEX te_node_address (te_node_address, id_so),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE patrimonio_config_interface
    ADD id_local int(11) unsigned NOT NULL COMMENT '' FIRST,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_etiqueta, id_local),
    ADD INDEX id_localizacao (id_local),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE perfis_aplicativos_monitorados
    ADD in_disponibiliza_info_usuario_comum char(1) NOT NULL DEFAULT 'N' COMMENT '' AFTER in_disponibiliza_info,
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE redes
    ADD id_local int(11) unsigned NOT NULL COMMENT '' FIRST,
    ADD nu_limite_ftp int(5) unsigned NOT NULL DEFAULT '5' COMMENT '' AFTER te_senha_login_serv_updates_gerente,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_ip_rede, id_local),
    ADD INDEX id_ip_rede (id_ip_rede),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE redes_grupos_ftp
    ADD id_local int(11) NOT NULL COMMENT '' FIRST,
    ADD id_ftp int(11) NOT NULL COMMENT '' auto_increment AFTER nu_hora_fim,
    ADD PRIMARY KEY (id_ftp),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE redes_versoes_modulos
    ADD id_local int(11) unsigned NOT NULL COMMENT '' FIRST,
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
    ADD id_tipo_software int(11) NULL DEFAULT '0' COMMENT '' AFTER nm_software_inventariado,
    ADD id_software int(10) unsigned NULL COMMENT '' AFTER id_tipo_software,
    ADD te_hash varchar(40) NOT NULL DEFAULT '' COMMENT '' AFTER id_software,
    ADD INDEX id_software (id_software_inventariado),
    ADD INDEX idx_nm_software_inventariado (nm_software_inventariado),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE softwares_inventariados_estacoes
    ADD INDEX id_software (id_software_inventariado),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE unid_organizacional_nivel2
    ADD id_local int(11) unsigned NOT NULL COMMENT '' FIRST,
    ADD id_unid_organizacional_nivel1a int(11) NOT NULL COMMENT '' AFTER id_unid_organizacional_nivel2,
    DROP id_unid_organizacional_nivel1,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_unid_organizacional_nivel2, id_unid_organizacional_nivel1a, id_local),
    ADD INDEX id_localizacao (id_local),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE usuarios
    ADD id_local int(11) unsigned NOT NULL COMMENT '' FIRST,
    ADD te_emails_contato varchar(100) NULL DEFAULT NULL COMMENT '' AFTER id_grupo_usuarios,
    ADD te_telefones_contato varchar(100) NULL DEFAULT NULL COMMENT '' AFTER te_emails_contato,
    ADD te_locais_secundarios varchar(200) NULL DEFAULT NULL COMMENT '' AFTER te_telefones_contato,
    MODIFY nm_usuario_acesso varchar(40) NOT NULL DEFAULT '' COMMENT '',
    MODIFY te_senha varchar(60) NOT NULL DEFAULT '' COMMENT '',
    MODIFY id_grupo_usuarios int(1) NOT NULL COMMENT '',
    ADD INDEX id_localizacao (id_local),
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE variaveis_ambiente
    ADD te_hash varchar(40) NOT NULL DEFAULT '' COMMENT '' AFTER nm_variavel_ambiente,
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE versoes_softwares
    ADD PRIMARY KEY (te_node_address, id_so),
    ENGINE=InnoDB CHARACTER SET=latin1;

--
-- Update ID_LOCAL on tables
--

UPDATE acoes_excecoes SET id_local=1;
UPDATE acoes_redes SET id_local=1;
UPDATE acoes_so SET id_local=1;
INSERT INTO `configuracoes_locais` (`id_local`) VALUES (1);
UPDATE patrimonio_config_interface SET id_local=1;
UPDATE redes SET id_local=1;
UPDATE redes_grupos_ftp SET id_local=1;
UPDATE redes_versoes_modulos SET id_local=1;
UPDATE unid_organizacional_nivel2 SET id_local=1;
UPDATE usuarios SET id_local=1;
UPDATE grupo_usuarios SET cs_nivel_administracao=0 WHERE id_grupo_usuarios=1;
UPDATE grupo_usuarios SET cs_nivel_administracao=1 WHERE id_grupo_usuarios=2;
UPDATE grupo_usuarios SET cs_nivel_administracao=2 WHERE id_grupo_usuarios=5;
UPDATE grupo_usuarios SET cs_nivel_administracao=3 WHERE id_grupo_usuarios=6;
UPDATE grupo_usuarios SET cs_nivel_administracao=0 WHERE id_grupo_usuarios=7;
INSERT INTO `locais` (`nm_local`,`sg_local`,`te_observacao`) VALUES ("Local Padrão","DFT","Colocar aqui informações sobre o local");
INSERT INTO `patrimonio_config_interface` 
        (`id_local`, `id_etiqueta`, `nm_etiqueta`, `te_etiqueta`, `in_exibir_etiqueta`, `te_help_etiqueta`,
         `te_plural_etiqueta`, `nm_campo_tab_patrimonio`, `in_destacar_duplicidade`)
     VALUES
        (1, 'etiqueta1a', 'Etiqueta 1a', 'Linha de Negócio', 'S', 'Selecione a Linha de Negócio', 'Linhas de Negócio', 'id_unid_organizacional_nivel1a', 'N');

SET FOREIGN_KEY_CHECKS = 1;
