-- --------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-fev2006
-- SGBD: MySQL-4.1.20
-- --------------------------------------------------------
#
# criado com o auxilio de: MySQL Diff 1.5.0
# http://www.mysqldiff.com

CREATE TABLE aplicativos_redes (
    id_local int(11) NOT NULL DEFAULT '0' COMMENT '',
    id_ip_rede varchar(15) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_aplicativo int(11) unsigned NOT NULL DEFAULT '0' COMMENT '',
    PRIMARY KEY (id_local, id_ip_rede, id_aplicativo)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Relacionamento entre redes e perfis de aplicativos monitorad; InnoDB free: 15360';

CREATE TABLE aquisicoes (
    id_aquisicao int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    dt_aquisicao date NULL DEFAULT NULL COMMENT '',
    nr_processo varchar(11) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    nm_empresa varchar(45) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    nm_proprietario varchar(45) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    nr_notafiscal int(10) unsigned NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (id_aquisicao)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE aquisicoes_item (
    id_aquisicao int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    id_software int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    id_tipo_licenca int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    qt_licenca int(11) NULL DEFAULT NULL COMMENT '',
    dt_vencimento_licenca date NULL DEFAULT NULL COMMENT '',
    te_obs varchar(50) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_aquisicao, id_software, id_tipo_licenca)
) row_format=DYNAMIC DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

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

CREATE TABLE configuracoes_locais (
    id_local int(11) unsigned NOT NULL DEFAULT '0' COMMENT '',
    te_notificar_mudanca_hardware text NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    in_exibe_erros_criticos char(1) NULL DEFAULT 'N' COMMENT '' COLLATE latin1_swedish_ci,
    in_exibe_bandeja char(1) NULL DEFAULT 'S' COMMENT '' COLLATE latin1_swedish_ci,
    nu_exec_apos int(11) NULL DEFAULT '10' COMMENT '',
    dt_hr_alteracao_patrim_interface datetime NULL DEFAULT NULL COMMENT '',
    dt_hr_alteracao_patrim_uon1 datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_alteracao_patrim_uon1a datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_alteracao_patrim_uon2 datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_coleta_forcada datetime NULL DEFAULT NULL COMMENT '',
    te_notificar_mudanca_patrim text NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    nm_organizacao varchar(150) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    nu_intervalo_exec int(11) NULL DEFAULT '4' COMMENT '',
    nu_intervalo_renovacao_patrim int(11) NULL DEFAULT '0' COMMENT '',
    te_senha_adm_agente varchar(30) NULL DEFAULT 'ADMINCACIC' COMMENT '' COLLATE latin1_swedish_ci,
    te_serv_updates_padrao varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_serv_cacic_padrao varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_enderecos_mac_invalidos text NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_janelas_excecao text NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_nota_email_gerentes text NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    cs_abre_janela_patr char(1) NOT NULL DEFAULT 'N' COMMENT '' COLLATE latin1_swedish_ci,
    id_default_body_bgcolor varchar(10) NOT NULL DEFAULT '#EBEBEB' COMMENT '' COLLATE latin1_swedish_ci,
    te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_local)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE configuracoes_padrao (
    in_exibe_erros_criticos char(1) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    in_exibe_bandeja char(1) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    nu_exec_apos int(11) NULL DEFAULT NULL COMMENT '',
    nm_organizacao varchar(150) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    nu_intervalo_exec int(11) NULL DEFAULT NULL COMMENT '',
    nu_intervalo_renovacao_patrim int(11) NULL DEFAULT NULL COMMENT '',
    te_senha_adm_agente varchar(30) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_serv_updates_padrao varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_serv_cacic_padrao varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_enderecos_mac_invalidos text NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_janelas_excecao text NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    cs_abre_janela_patr char(1) NOT NULL DEFAULT 'S' COMMENT '' COLLATE latin1_swedish_ci,
    id_default_body_bgcolor varchar(10) NOT NULL DEFAULT '#EBEBEB' COMMENT '' COLLATE latin1_swedish_ci,
    te_exibe_graficos varchar(100) NOT NULL DEFAULT '[acessos_locais][so][acessos][locais]' COMMENT '' COLLATE latin1_swedish_ci
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE contas (
    id_conta int(10) unsigned NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    nm_responsavel varchar(30) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_conta)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE historicos_hardware (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_so int(11) NOT NULL DEFAULT '0' COMMENT '',
    campo_alterado varchar(45) NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    valor_antigo varchar(45) NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    data_anterior datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    novo_valor varchar(45) NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    nova_data datetime NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    PRIMARY KEY (te_node_address, id_so)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE historicos_outros_softwares (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_so int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    id_software_inventariado int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    dt_hr_inclusao datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_ult_coleta datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    PRIMARY KEY (te_node_address, id_so, id_software_inventariado)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE historicos_software (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_so int(11) unsigned NOT NULL DEFAULT '0' COMMENT '',
    id_software_inventariado int(11) unsigned NOT NULL DEFAULT '0' COMMENT '',
    dt_hr_inclusao datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_ult_coleta datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    PRIMARY KEY (te_node_address, id_so, id_software_inventariado),
    INDEX id_software (id_software_inventariado)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE historicos_software_completo (
    te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_so int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    id_software_inventariado int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    dt_hr_inclusao datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    dt_hr_ult_coleta datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    PRIMARY KEY (te_node_address, id_so, id_software_inventariado, dt_hr_inclusao)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE insucessos_instalacao (
    te_ip varchar(15) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_so varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_usuario varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    dt_datahora datetime NOT NULL DEFAULT '' COMMENT '',
    cs_indicador char(1) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE locais (
    id_local int(11) unsigned NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    nm_local varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    sg_local varchar(20) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_observacao varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_local),
    INDEX sg_localizacao (sg_local)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Localizações para regionalização de acesso a dados; InnoDB free: 15360 kB';

CREATE TABLE log (
    dt_acao datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
    cs_acao varchar(20) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    nm_script varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    nm_tabela varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_usuario int(11) NOT NULL DEFAULT '0' COMMENT '',
    te_ip_origem varchar(15) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Log de Atividades no Sistema CACIC; InnoDB free: 15360 kB';

CREATE TABLE softwares (
    id_software int(10) unsigned NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    nm_software varchar(150) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_descricao_software varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    qt_licenca int(11) NULL DEFAULT '0' COMMENT '',
    nr_midia varchar(10) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_local_midia varchar(30) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    te_obs varchar(200) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_software)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE softwares_estacao (
    nr_patrimonio varchar(20) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_software int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    nm_computador varchar(50) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    dt_autorizacao date NULL DEFAULT NULL COMMENT '',
    nr_processo varchar(11) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    dt_expiracao_instalacao date NULL DEFAULT NULL COMMENT '',
    id_aquisicao_particular int(10) unsigned NULL DEFAULT NULL COMMENT '',
    dt_desinstalacao date NULL DEFAULT NULL COMMENT '',
    te_observacao varchar(90) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    nr_patr_destino varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (nr_patrimonio, id_software)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE tipos_licenca (
    id_tipo_licenca int(10) unsigned NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    te_tipo_licenca varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_tipo_licenca)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE tipos_software (
    id_tipo_software int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    te_descricao_tipo_software varchar(30) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_tipo_software)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE unid_organizacional_nivel1a (
    id_unid_organizacional_nivel1 int(11) NOT NULL DEFAULT '' COMMENT '',
    id_unid_organizacional_nivel1a int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    nm_unid_organizacional_nivel1a varchar(50) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_unid_organizacional_nivel1a)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

ALTER TABLE acoes_excecoes
    ADD id_local int(11) NOT NULL DEFAULT '' COMMENT '' FIRST;


ALTER TABLE acoes_redes
    ADD id_local int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER id_acao,
    ADD cs_situacao char(1) NOT NULL DEFAULT 'T' COMMENT '' COLLATE latin1_swedish_ci AFTER dt_hr_coleta_forcada,
    ADD dt_hr_alteracao datetime NULL DEFAULT NULL COMMENT '' AFTER cs_situacao,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_local, id_ip_rede, id_acao);


ALTER TABLE acoes_so
    ADD id_local int(11) NOT NULL DEFAULT '0' COMMENT '' FIRST,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_acao, id_so, id_local);


ALTER TABLE aplicativos_monitorados
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE compartilhamentos
    ADD INDEX node_so_tipocompart (te_node_address, id_so, cs_tipo_compart);


ALTER TABLE computadores
    ADD te_so varchar(50) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci AFTER id_so,
    ADD id_conta int(10) unsigned NULL DEFAULT NULL COMMENT '' AFTER te_origem_mac,
    MODIFY te_mem_ram_desc varchar(200) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    ADD INDEX te_ip (te_ip),
    ADD INDEX te_node_address (te_node_address),
    ADD INDEX te_nome_computador (te_nome_computador);

DROP TABLE configuracoes;

ALTER TABLE descricao_hardware
    ADD te_locais_notificacao_ativada text NULL DEFAULT NULL COMMENT 'Locais onde a notificação de alteração de hardware encontra-se ativa.' COLLATE latin1_swedish_ci AFTER te_desc_hardware,
    DROP cs_notificacao_ativada;


ALTER TABLE descricoes_colunas_computadores
    COMMENT='Tabela para auxílio na opção Exclusão de Informações; InnoDB free: 15360 kB';


ALTER TABLE grupo_usuarios
    ADD cs_nivel_administracao tinyint(2) NOT NULL DEFAULT '0' COMMENT '' AFTER te_descricao_grupo,
    MODIFY id_grupo_usuarios int(2) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    COMMENT='InnoDB free: 15360 kB';

ALTER TABLE officescan
    ADD PRIMARY KEY (te_node_address, id_so);


ALTER TABLE patrimonio
    ADD id_unid_organizacional_nivel1a int(11) NOT NULL DEFAULT '' COMMENT '' FIRST,
    DROP id_unid_organizacional_nivel1,
    MODIFY te_node_address varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    ADD INDEX te_node_address (te_node_address, id_so);

ALTER TABLE patrimonio_config_interface
    ADD id_local int(11) unsigned NOT NULL DEFAULT '0' COMMENT '' FIRST,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_etiqueta, id_local),
    ADD INDEX id_localizacao (id_local);


ALTER TABLE perfis_aplicativos_monitorados
    ADD in_disponibiliza_info_usuario_comum char(1) NOT NULL DEFAULT 'N' COMMENT '' COLLATE latin1_swedish_ci AFTER in_disponibiliza_info,
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE redes
    ADD id_local int(11) unsigned NOT NULL DEFAULT '0' COMMENT '' FIRST,
    ADD nu_limite_ftp int(5) unsigned NOT NULL DEFAULT '5' COMMENT '' AFTER te_senha_login_serv_updates_gerente,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_ip_rede, id_local),
    ADD INDEX id_ip_rede (id_ip_rede);


ALTER TABLE redes_grupos_ftp
    ADD id_local int(11) NOT NULL DEFAULT '0' COMMENT '' FIRST,
    ADD id_ftp int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment AFTER nu_hora_fim,
    ADD PRIMARY KEY (id_ftp);


ALTER TABLE redes_versoes_modulos
    ADD id_local int(11) unsigned NOT NULL DEFAULT '0' COMMENT '' FIRST,
    ADD dt_atualizacao datetime NOT NULL DEFAULT '' COMMENT '' AFTER te_versao_modulo,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_ip_rede, nm_modulo, id_local);


ALTER TABLE so
    ADD te_so varchar(50) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci AFTER sg_so,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_so, te_so);


ALTER TABLE softwares_inventariados
    ADD id_tipo_software int(11) NULL DEFAULT '0' COMMENT '' AFTER nm_software_inventariado,
    ADD id_software int(10) unsigned NULL DEFAULT NULL COMMENT '' AFTER id_tipo_software,
    ADD te_hash varchar(40) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci AFTER id_software,
    ADD INDEX id_software (id_software_inventariado),
    ADD INDEX idx_nm_software_inventariado (nm_software_inventariado),
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE softwares_inventariados_estacoes
    ADD INDEX id_software (id_software_inventariado),
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE unid_organizacional_nivel2
    ADD id_local int(11) unsigned NOT NULL DEFAULT '0' COMMENT '' FIRST,
    ADD id_unid_organizacional_nivel1a int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER id_unid_organizacional_nivel2,
    DROP id_unid_organizacional_nivel1,
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (id_unid_organizacional_nivel2, id_unid_organizacional_nivel1a, id_local),
    ADD INDEX id_localizacao (id_local);


ALTER TABLE usuarios
    ADD id_local int(11) unsigned NOT NULL DEFAULT '0' COMMENT '' FIRST,
    ADD te_emails_contato varchar(100) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci AFTER id_grupo_usuarios,
    ADD te_telefones_contato varchar(100) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci AFTER te_emails_contato,
    ADD te_locais_secundarios varchar(200) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci AFTER te_telefones_contato,
    MODIFY nm_usuario_acesso varchar(20) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    MODIFY te_senha varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    MODIFY id_grupo_usuarios int(1) NOT NULL DEFAULT '1' COMMENT '',
    ADD INDEX id_localizacao (id_local);

ALTER TABLE variaveis_ambiente
    ADD te_hash varchar(40) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci AFTER nm_variavel_ambiente;


ALTER TABLE variaveis_ambiente_estacoes
    COMMENT='InnoDB free: 15360 kB';


ALTER TABLE versoes_softwares
    ADD PRIMARY KEY (te_node_address, id_so);


