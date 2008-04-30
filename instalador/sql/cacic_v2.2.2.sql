-- --------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-jun2005
-- SGBD: MySQL-4.1.20
-- --------------------------------------------------------
#
# criado com o auxilio de: MySQL Diff 1.5.0
# http://www.mysqldiff.com


SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE componentes_estacoes (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_so int(11) NOT NULL DEFAULT '' COMMENT '',
    cs_tipo_componente varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_valor text NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    INDEX te_node_address (te_node_address, id_so, cs_tipo_componente)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Componentes de hardware instalados nas estações; InnoDB free: 15360 kB';

CREATE TABLE componentes_estacoes_historico (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE ascii_general_ci,
    id_so int(11) NOT NULL DEFAULT '' COMMENT '',
    cs_tipo_componente varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE ascii_general_ci,
    te_valor varchar(200) NOT NULL DEFAULT '' COMMENT '' COLLATE ascii_general_ci,
    dt_alteracao datetime NOT NULL DEFAULT '' COMMENT '',
    cs_tipo_alteracao varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE ascii_general_ci,
    INDEX te_node_address (te_node_address, id_so, cs_tipo_componente)
) DEFAULT CHARSET=ascii COLLATE=ascii_general_ci COMMENT='Componentes de hardware instalados nas estaÃ§Ãµes; InnoDB free: 15360 kB';

CREATE TABLE insucessos_instalacao (
    te_ip varchar(15) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_so varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_usuario varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    dt_datahora datetime NOT NULL DEFAULT '' COMMENT '',
    cs_indicador char(1) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE unid_organizacional_nivel1a (
    id_unid_organizacional_nivel1 int(11) NOT NULL DEFAULT '' COMMENT '',
    id_unid_organizacional_nivel1a int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    nm_unid_organizacional_nivel1a varchar(50) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_unid_organizacional_nivel1a)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

ALTER TABLE acoes_excecoes
    ADD id_local int(11) NOT NULL DEFAULT '' COMMENT '' FIRST;


ALTER TABLE aquisicoes
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE aquisicoes_item
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE computadores
    MODIFY te_so varchar(50) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    MODIFY te_mem_ram_desc varchar(200) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci;
ALTER TABLE configuracoes_locais
    ADD dt_hr_alteracao_patrim_uon1a datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '' AFTER dt_hr_alteracao_patrim_uon1,
    ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT '' COLLATE latin1_swedish_ci AFTER id_default_body_bgcolor,
    ALTER dt_hr_alteracao_patrim_uon2 SET DEFAULT '0000-00-00 00:00:00';


ALTER TABLE configuracoes_padrao
    ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT '' COLLATE latin1_swedish_ci AFTER id_default_body_bgcolor;


ALTER TABLE descricao_hardware
    ADD te_locais_notificacao_ativada text NULL DEFAULT NULL COMMENT 'Locais onde a notificação de alteração de hardware encontra-se ativa.' COLLATE latin1_swedish_ci AFTER te_desc_hardware,
    DROP cs_notificacao_ativada;


ALTER TABLE historicos_software
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE patrimonio
    ADD id_unid_organizacional_nivel1a int(11) NOT NULL DEFAULT '' COMMENT '' FIRST,
    DROP id_unid_organizacional_nivel1,
    MODIFY te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci;

ALTER TABLE redes_grupos_ftp
    ADD id_ftp int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment AFTER nu_hora_fim,
    ADD PRIMARY KEY (id_ftp);


ALTER TABLE redes_versoes_modulos
    ADD dt_atualizacao datetime NOT NULL DEFAULT '' COMMENT '' AFTER te_versao_modulo,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_ip_rede, nm_modulo, id_local);


ALTER TABLE so
    ADD te_so varchar(50) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci AFTER sg_so,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_so, te_so);


ALTER TABLE softwares
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE softwares_estacao
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE softwares_inventariados
    ADD te_hash varchar(40) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci AFTER id_software;


ALTER TABLE tipos_licenca
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE unid_organizacional_nivel2
    ADD id_unid_organizacional_nivel1a int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER id_unid_organizacional_nivel2,
    DROP id_unid_organizacional_nivel1,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_unid_organizacional_nivel2, id_unid_organizacional_nivel1a, id_local);


ALTER TABLE usuarios
    ADD te_locais_secundarios varchar(200) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci AFTER te_telefones_contato,
    MODIFY nm_usuario_acesso varchar(20) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    MODIFY te_senha varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    MODIFY id_grupo_usuarios int(1) NOT NULL DEFAULT '1' COMMENT '';

ALTER TABLE variaveis_ambiente
    ADD te_hash varchar(40) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci AFTER nm_variavel_ambiente;


SET FOREIGN_KEY_CHECKS = 1;
