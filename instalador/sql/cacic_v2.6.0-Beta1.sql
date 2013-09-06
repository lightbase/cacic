-- Atualização de Tabelas do banco de dados CACIC-v2.6.0-Beta1

SET FOREIGN_KEY_CHECKS = 0;

#
# DDL START
#
CREATE TABLE servidores_autenticacao (
    id_servidor_autenticacao int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    nm_servidor_autenticacao varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_ip_servidor_autenticacao varchar(15) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    id_tipo_protocolo varchar(20) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    nu_versao_protocolo varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_base_consulta_raiz varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_base_consulta_folha varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_atributo_identificador varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_atributo_retorna_nome varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_atributo_retorna_email varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    te_observacao text NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    in_ativo char(1) NOT NULL DEFAULT 'S' COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_servidor_autenticacao)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Servidores para Autenticacao do srCACIC; InnoDB free: 15360 kB';

CREATE TABLE srcacic_chats (
    id_conexao int(11) NOT NULL DEFAULT 0 COMMENT '',
    dt_hr_mensagem datetime NOT NULL DEFAULT 0000-00-00 00:00:00 COMMENT '',
    te_mensagem text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci,
    cs_origem char(3) NOT NULL DEFAULT 'cli' COMMENT '' COLLATE utf8_unicode_ci,
    INDEX id_conexao (id_conexao)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Log de Atividades no Sistema CACIC; InnoDB free: 15360 kB';

CREATE TABLE srcacic_conexoes (
    id_conexao int(11) NOT NULL DEFAULT 0 COMMENT 'Identificador da conexÃ£o' auto_increment,
    id_sessao int(11) NOT NULL DEFAULT 0 COMMENT '',
    id_usuario_cli int(11) NOT NULL DEFAULT '0' COMMENT '',
    te_node_address_cli varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    te_documento_referencial varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    id_so_cli int(11) NOT NULL DEFAULT 0 COMMENT '',
    te_motivo_conexao text NOT NULL DEFAULT '' COMMENT 'Descritivo breve sobre o motivo da conexÃ£o' COLLATE utf8_unicode_ci,
    dt_hr_inicio_conexao datetime NOT NULL DEFAULT 0000-00-00 00:00:00 COMMENT '',
    dt_hr_ultimo_contato datetime NOT NULL DEFAULT 0000-00-00 00:00:00 COMMENT '',
    PRIMARY KEY (id_conexao)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Registros de ConexÃµes efetuadas Ã s sessÃµes abertas; InnoDB free: 15360 kB';

CREATE TABLE srcacic_sessoes (
    id_sessao int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    dt_hr_inicio_sessao datetime NOT NULL DEFAULT 0000-00-00 00:00:00 COMMENT '',
    nm_acesso_usuario_srv varchar(30) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci,
    nm_completo_usuario_srv varchar(100) NOT NULL DEFAULT 'NoNoNo' COMMENT '' COLLATE latin1_swedish_ci,
    te_email_usuario_srv varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    te_node_address_srv varchar(17) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci,
    id_so_srv int(11) NOT NULL DEFAULT 0 COMMENT '',
    dt_hr_ultimo_contato datetime NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (id_sessao),
    INDEX idx_dtHrInicioSessao (dt_hr_inicio_sessao)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Log de Atividades no Sistema CACIC; InnoDB free: 15360 kB';

CREATE TABLE srcacic_transfs (
    id_conexao int(11) NOT NULL DEFAULT 0 COMMENT '',
    dt_systemtime datetime NOT NULL DEFAULT 0000-00-00 00:00:00 COMMENT '',
    nu_duracao double NOT NULL DEFAULT '' COMMENT '',
    te_path_origem varchar(512) NOT NULL DEFAULT 'NoNoNo' COMMENT '' COLLATE utf8_unicode_ci,
    te_path_destino varchar(512) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    nm_arquivo char(127) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    nu_tamanho_arquivo int(11) NOT NULL DEFAULT 0 COMMENT '',
    cs_status char(1) NOT NULL DEFAULT '1' COMMENT '' COLLATE utf8_unicode_ci,
    cs_operacao char(1) NOT NULL DEFAULT 'D' COMMENT '' COLLATE utf8_unicode_ci,
    INDEX id_conexao (id_conexao)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Log de Atividades no Sistema CACIC';

CREATE TABLE testes (
    id_transacao int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
    te_linha text NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    PRIMARY KEY (id_transacao)
) DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE usb_devices (
    id_vendor char(5) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_general_ci,
    id_device char(5) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_general_ci,
    nm_device char(127) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    te_observacao text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    INDEX idxUSBDevices_idVendor (id_vendor),
    INDEX idxUSBDevices_idDevice (id_device)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE usb_logs (
    id_vendor char(5) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    id_device char(5) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_general_ci,
    dt_event char(14) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    te_node_address char(17) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    id_so int(11) NOT NULL DEFAULT 0 COMMENT '',
    cs_event char(1) NOT NULL DEFAULT 'I' COMMENT '' COLLATE utf8_unicode_ci,
    INDEX idxUSBLogs_idDevice (id_device),
    INDEX idxUSBLogs_dtEvent (dt_event),
    INDEX idxUSBLogs_idVendor (id_vendor)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='InnoDB free: 15360 kB';

CREATE TABLE usb_vendors (
    id_vendor char(5) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_general_ci,
    nm_vendor char(127) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_general_ci,
    te_observacao text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    UNIQUE idxUSBVendors_idVendor (id_vendor)
) DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='InnoDB free: 15360 kB';

ALTER TABLE componentes_estacoes
    ADD INDEX idxComponentesEstacoes_IdSO_TeNodeAddress (id_so, te_node_address),
    COMMENT='Componentes de hardware instalados nas estaÃ§Ãµes; InnoDB free: 15360 kB';


ALTER TABLE componentes_estacoes_historico
    MODIFY te_valor varchar(200) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci,
    ADD INDEX idxComponentesEstacoesHistorico_IdSO_TeNodeAddress (id_so, te_node_address),
    COMMENT='Componentes de hardware instalados nas estaÃ§Ãµes; InnoDB free: 15360 kB';
#
#  Fieldformat of
#    componentes_estacoes_historico.te_valor changed from varchar(3072) NOT NULL DEFAULT '' COMMENT '' COLLATE ascii_general_ci to varchar(200) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci.
#  Possibly data modifications needed!
#

ALTER TABLE configuracoes_locais
    ADD nu_timeout_srcacic tinyint(3) NOT NULL DEFAULT '30' COMMENT 'Valor para timeout do servidor srCACIC' AFTER nm_organizacao,
    ADD nu_porta_srcacic varchar(5) NOT NULL DEFAULT '5900' COMMENT '' COLLATE utf8_unicode_ci AFTER te_exibe_graficos,
    ADD te_usb_filter text NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci AFTER nu_porta_srcacic,
    MODIFY te_serv_updates_padrao varchar(60) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci,
    MODIFY te_serv_cacic_padrao varchar(60) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci;
#
#  Fieldformats of
#    configuracoes_locais.te_serv_updates_padrao changed from varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(60) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci.
#    configuracoes_locais.te_serv_cacic_padrao changed from varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(60) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE configuracoes_padrao
    ADD nu_timeout_srcacic tinyint(3) NOT NULL DEFAULT '30' COMMENT 'Valor padrao para timeout do servidor srCACIC' AFTER nm_organizacao,
    ADD nu_porta_srcacic varchar(5) NOT NULL DEFAULT '5900' COMMENT '' COLLATE utf8_unicode_ci AFTER te_exibe_graficos,
    ADD nu_resolucao_grafico_h smallint(5) NOT NULL DEFAULT '0' COMMENT '' AFTER nu_porta_srcacic,
    ADD nu_resolucao_grafico_w smallint(5) NOT NULL DEFAULT '0' COMMENT '' AFTER nu_resolucao_grafico_h,
    MODIFY te_serv_updates_padrao varchar(60) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci,
    MODIFY te_serv_cacic_padrao varchar(60) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci;
#
#  Fieldformats of
#    configuracoes_padrao.te_serv_updates_padrao changed from varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(60) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci.
#    configuracoes_padrao.te_serv_cacic_padrao changed from varchar(20) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(60) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE descricao_hardware
    MODIFY te_locais_notificacao_ativada text NULL DEFAULT NULL COMMENT 'Locais onde a notificaÃ§Ã£o de alteraÃ§Ã£o de hardware encontra-se ativa.' COLLATE latin1_swedish_ci;
#
#  Fieldformat of
#    descricao_hardware.te_locais_notificacao_ativada changed from text NULL DEFAULT NULL COMMENT 'Locais onde a notificação de alteração de hardware encontra-se ativa.' COLLATE latin1_swedish_ci to text NULL DEFAULT NULL COMMENT 'Locais onde a notificaÃ§Ã£o de alteraÃ§Ã£o de hardware encontra-se ativa.' COLLATE latin1_swedish_ci.
#  Possibly data modifications needed!
#

ALTER TABLE descricoes_colunas_computadores
DEFAULT CHARSET=latin1 COLLATE=latin1_bin,
    COMMENT='Tabela para auxÃ­lio na opÃ§Ã£o ExclusÃ£o de InformaÃ§Ãµes; InnoDB free: 15360 k';


ALTER TABLE locais
    COMMENT='LocalizaÃ§Ãµes para regionalizaÃ§Ã£o de acesso a dados; InnoDB free: 15360 kB';


ALTER TABLE log
    ADD INDEX idxLog_DtAcao (dt_acao),
    ADD INDEX idxLog_CsAcao (cs_acao),
    ADD INDEX idxLog_IdUsuario (id_usuario);


ALTER TABLE patrimonio
    ADD INDEX idxPatrimonio_IdUnidOrganizacionalNivel1a (id_unid_organizacional_nivel1a);


ALTER TABLE perfis_aplicativos_monitorados
    MODIFY te_car_inst_w9x varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    MODIFY te_car_ver_w9x varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    MODIFY te_car_inst_wnt varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    MODIFY te_car_ver_wnt varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci,
    MODIFY te_ide_licenca varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci;
#
#  Fieldformats of
#    perfis_aplicativos_monitorados.te_car_inst_w9x changed from varchar(100) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci.
#    perfis_aplicativos_monitorados.te_car_ver_w9x changed from varchar(100) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci.
#    perfis_aplicativos_monitorados.te_car_inst_wnt changed from varchar(100) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci.
#    perfis_aplicativos_monitorados.te_car_ver_wnt changed from varchar(100) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci.
#    perfis_aplicativos_monitorados.te_ide_licenca changed from varchar(200) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci.
#  Possibly data modifications needed!
#

ALTER TABLE redes
    ADD id_servidor_autenticacao int(11) NULL DEFAULT '0' COMMENT '' AFTER id_local,
    ADD cs_permitir_desativar_srcacic char(1) NOT NULL DEFAULT 'S' COMMENT '' COLLATE utf8_unicode_ci AFTER nu_limite_ftp,
    MODIFY te_serv_cacic varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci,
    MODIFY te_serv_updates varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci;
#
#  Fieldformats of
#    redes.te_serv_cacic changed from varchar(45) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci.
#    redes.te_serv_updates changed from varchar(45) NULL DEFAULT NULL COMMENT '' COLLATE latin1_swedish_ci to varchar(60) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE redes_grupos_ftp
    ADD INDEX idxRedesGruposFtp_IdLocal_IdIpRede_IdIpEstacao (id_local, id_ip_rede, id_ip_estacao),
    ADD INDEX idxRedesGruposFtp_IdIpRede_IdLocal (id_ip_rede, id_local);


ALTER TABLE redes_versoes_modulos
    MODIFY nm_modulo varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci;
#
#  Fieldformat of
#    redes_versoes_modulos.nm_modulo changed from varchar(20) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci to varchar(100) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci.
#  Possibly data modifications needed!
#

ALTER TABLE so
    ADD in_mswindows char(1) NOT NULL DEFAULT 'S' COMMENT '' COLLATE latin1_swedish_ci AFTER te_so,
    ADD INDEX idxSo_TeSo (te_so);


ALTER TABLE softwares_inventariados
    ADD INDEX idxSoftwaresInventariados_TeHash (te_hash);


ALTER TABLE unidades_disco
    MODIFY te_letra char(20) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci;
#
#  Fieldformat of
#    unidades_disco.te_letra changed from char(1) NOT NULL DEFAULT '' COMMENT '' COLLATE latin1_swedish_ci to char(20) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE usuarios
    ADD id_servidor_autenticacao int(11) NULL DEFAULT NULL COMMENT '' AFTER id_local;


ALTER TABLE variaveis_ambiente
    ADD INDEX idxVariaveisAmbiente_TeHash (te_hash);


#
# DDL END
#

SET FOREIGN_KEY_CHECKS = 1;

