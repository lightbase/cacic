-- --------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-jun2005
-- SGBD: MySQL-4.1.20
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
    te_valor varchar(200) NOT NULL DEFAULT '' COMMENT '',
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
    MODIFY te_palavra_chave char(30) NOT NULL DEFAULT 'abcdefghij',
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE configuracoes_locais
    ADD dt_hr_alteracao_patrim_uon1a datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '' AFTER dt_hr_alteracao_patrim_uon1,
    ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT '' AFTER id_default_body_bgcolor,
    ALTER dt_hr_alteracao_patrim_uon2 SET DEFAULT '0000-00-00 00:00:00',
    ENGINE=InnoDB CHARACTER SET=latin1;


ALTER TABLE configuracoes_padrao
    ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT '' AFTER id_default_body_bgcolor,
    ADD nu_rel_maxlinhas int(3) default 50 AFTER nu_exec_apos,
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
        
--
-- Update ID_LOCAL on tables
--

UPDATE acoes_excecoes SET id_local=1;

SET FOREIGN_KEY_CHECKS = 1;
