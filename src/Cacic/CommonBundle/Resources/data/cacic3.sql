CREATE TABLE acao (
    id_acao character varying(30) NOT NULL,
    te_descricao_breve character varying(100) DEFAULT NULL::character varying,
    te_descricao text,
    te_nome_curto_modulo character varying(20) DEFAULT NULL::character varying,
    dt_hr_alteracao timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    cs_opcional character varying(1) NOT NULL
);


CREATE TABLE acao_excecao (
    te_node_address character varying(17) NOT NULL,
    id_acao character varying(30) NOT NULL,
    id_rede integer NOT NULL
);


CREATE TABLE acao_rede (
    id_acao character varying(30) NOT NULL,
    id_rede integer NOT NULL,
    dt_hr_coleta_forcada timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


CREATE TABLE acao_so (
    id_acao character varying(30) NOT NULL,
    id_rede integer NOT NULL,
    id_so integer NOT NULL
);


CREATE TABLE aplicativo (
    id_aplicativo integer NOT NULL,
    id_so integer,
    nm_aplicativo character varying(100) NOT NULL,
    cs_car_inst_w9x character varying(2) DEFAULT NULL::character varying,
    te_car_inst_w9x character varying(255) DEFAULT NULL::character varying,
    cs_car_ver_w9x character varying(2) DEFAULT NULL::character varying,
    te_car_ver_w9x character varying(255) DEFAULT NULL::character varying,
    cs_car_inst_wnt character varying(2) DEFAULT NULL::character varying,
    te_car_inst_wnt character varying(255) DEFAULT NULL::character varying,
    cs_car_ver_wnt character varying(2) DEFAULT NULL::character varying,
    te_car_ver_wnt character varying(255) DEFAULT NULL::character varying,
    cs_ide_licenca character varying(2) DEFAULT NULL::character varying,
    te_ide_licenca character varying(255) DEFAULT NULL::character varying,
    dt_atualizacao timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    te_arq_ver_eng_w9x character varying(100) DEFAULT NULL::character varying,
    te_arq_ver_pat_w9x character varying(100) DEFAULT NULL::character varying,
    te_arq_ver_eng_wnt character varying(100) DEFAULT NULL::character varying,
    te_arq_ver_pat_wnt character varying(100) DEFAULT NULL::character varying,
    te_dir_padrao_w9x character varying(100) DEFAULT NULL::character varying,
    te_dir_padrao_wnt character varying(100) DEFAULT NULL::character varying,
    te_descritivo text,
    in_disponibiliza_info character varying(1) NOT NULL,
    in_disponibiliza_info_usuario_comum character varying(1) NOT NULL,
    dt_registro timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


CREATE TABLE aplicativo_rede (
    id_rede integer NOT NULL,
    id_aplicativo integer NOT NULL
);


CREATE TABLE aquisicao (
    id_aquisicao integer NOT NULL,
    dt_aquisicao date,
    nr_processo character varying(11) DEFAULT NULL::character varying,
    nm_empresa character varying(45) DEFAULT NULL::character varying,
    nm_proprietario character varying(45) DEFAULT NULL::character varying,
    nr_notafiscal character varying(20) DEFAULT NULL::character varying
);


CREATE TABLE aquisicao_item (
    id_tipo_licenca integer NOT NULL,
    id_aquisicao integer NOT NULL,
    id_software integer NOT NULL,
    qt_licenca integer,
    dt_vencimento_licenca date,
    te_obs character varying(50) DEFAULT NULL::character varying
);


CREATE TABLE class_property (
    id_class_property integer NOT NULL,
    id_class integer,
    nm_property_name character varying(100) NOT NULL,
    te_property_description character varying(100) NOT NULL,
    nm_function_pre_db character varying(30) DEFAULT NULL::character varying,
    nm_function_pos_db character varying(30) DEFAULT NULL::character varying
);


CREATE TABLE class_property_type (
    id_class_property_type integer NOT NULL,
    id_class_property integer,
    cs_type character varying(20) NOT NULL,
    te_type_description character varying(100) NOT NULL
);


CREATE TABLE classe (
    id_class integer NOT NULL,
    nm_class_name character varying(50) NOT NULL,
    te_class_description character varying(255) NOT NULL
);


CREATE TABLE collect_def_class (
    id_collect_def_class integer NOT NULL,
    id_acao character varying(30) DEFAULT NULL::character varying,
    id_class integer,
    te_where_clause text
);


CREATE TABLE computador (
    id_computador integer NOT NULL,
    id_usuario_exclusao integer,
    id_so integer,
    id_rede integer,
    nm_computador character varying(255) DEFAULT NULL::character varying,
    te_node_address character varying(255) NOT NULL,
    te_ip_computador character varying(255) DEFAULT NULL::character varying,
    dt_hr_inclusao timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    dt_hr_exclusao timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    dt_hr_ult_acesso timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    te_versao_cacic character varying(255) DEFAULT NULL::character varying,
    te_versao_gercols character varying(255) DEFAULT NULL::character varying,
    te_palavra_chave character varying(255) NOT NULL,
    dt_hr_coleta_forcada_estacao timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    te_nomes_curtos_modulos character varying(255) DEFAULT NULL::character varying,
    id_conta integer,
    te_debugging text,
    te_ultimo_login character varying(255) DEFAULT NULL::character varying,
    dt_debug character varying(255) DEFAULT NULL::character varying
);


CREATE TABLE computador_coleta (
    id_computador_coleta integer NOT NULL,
    id_computador integer,
    id_class_property integer,
    te_class_property_value text NOT NULL
);


CREATE TABLE computador_coleta_historico (
    id_computador_coleta_historico integer NOT NULL,
    id_computador_coleta integer,
    id_computador integer,
    id_class_property integer,
    te_class_property_value text NOT NULL,
    dt_hr_inclusao timestamp(0) without time zone NOT NULL
);


CREATE TABLE configuracao_local (
    id_local integer NOT NULL,
    id_configuracao character varying(50) NOT NULL,
    vl_configuracao text
);


CREATE TABLE configuracao_padrao (
    id_configuracao character varying(50) NOT NULL,
    nm_configuracao character varying(100) NOT NULL,
    vl_configuracao text
);


CREATE TABLE descricao_coluna_computador (
    te_source character varying(100) NOT NULL,
    te_target character varying(100) NOT NULL,
    te_description character varying(100),
    cs_condicao_pesquisa character varying(1) NOT NULL
);


CREATE TABLE grupo_usuario (
    id_grupo_usuario integer NOT NULL,
    nm_grupo_usuarios character varying(20) NOT NULL,
    te_grupo_usuarios character varying(20) DEFAULT NULL::character varying,
    te_menu_grupo character varying(20) DEFAULT NULL::character varying,
    te_descricao_grupo text,
    cs_nivel_administracao boolean
);


CREATE TABLE insucesso_instalacao (
    id_insucesso_instalacao integer NOT NULL,
    te_ip_computador character varying(15) NOT NULL,
    te_so character varying(60) NOT NULL,
    id_usuario character varying(60) NOT NULL,
    dt_datahora timestamp(0) without time zone NOT NULL,
    cs_indicador character varying(1) NOT NULL
);


CREATE TABLE local (
    id_local integer NOT NULL,
    nm_local character varying(100) NOT NULL,
    sg_local character varying(20) NOT NULL,
    te_observacao character varying(255) DEFAULT NULL::character varying,
    te_debugging text,
    dt_debug character varying(8) DEFAULT NULL::character varying
);


CREATE TABLE local_secundario (
    id_usuario integer NOT NULL,
    id_local integer NOT NULL
);


CREATE TABLE log (
    id_log integer NOT NULL,
    id_usuario integer,
    dt_acao timestamp(0) without time zone NOT NULL,
    cs_acao character varying(20) NOT NULL,
    nm_script character varying(255) NOT NULL,
    nm_tabela character varying(255) NOT NULL,
    te_ip_origem character varying(15) NOT NULL
);


CREATE TABLE patrimonio (
    id_patrimonio integer NOT NULL,
    id_usuario integer,
    id_unid_organizacional_nivel1a integer,
    id_computador integer,
    id_unid_organizacional_nivel2 integer,
    dt_hr_alteracao timestamp(0) without time zone NOT NULL,
    te_localizacao_complementar character varying(100) DEFAULT NULL::character varying,
    te_info_patrimonio1 character varying(20) DEFAULT NULL::character varying,
    te_info_patrimonio2 character varying(20) DEFAULT NULL::character varying,
    te_info_patrimonio3 character varying(20) DEFAULT NULL::character varying,
    te_info_patrimonio4 character varying(20) DEFAULT NULL::character varying,
    te_info_patrimonio5 character varying(20) DEFAULT NULL::character varying,
    te_info_patrimonio6 character varying(20) DEFAULT NULL::character varying,
    id_unid_organizacional_nivel1 integer NOT NULL
);


CREATE TABLE patrimonio_config_interface (
    id_etiqueta character varying(30) NOT NULL,
    id_local integer NOT NULL,
    nm_etiqueta character varying(15) DEFAULT NULL::character varying,
    te_etiqueta character varying(50) NOT NULL,
    in_exibir_etiqueta character varying(1) DEFAULT NULL::character varying,
    te_help_etiqueta character varying(100) DEFAULT NULL::character varying,
    te_plural_etiqueta character varying(50) DEFAULT NULL::character varying,
    nm_campo_tab_patrimonio character varying(50) DEFAULT NULL::character varying,
    in_destacar_duplicidade character varying(1) DEFAULT NULL::character varying,
    in_obrigatorio character varying(1) NOT NULL
);


CREATE TABLE rede (
    id_rede integer NOT NULL,
    id_local integer,
    id_servidor_autenticacao integer,
    te_ip_rede character varying(15) NOT NULL,
    nm_rede character varying(100) DEFAULT NULL::character varying,
    te_observacao character varying(100) DEFAULT NULL::character varying,
    nm_pessoa_contato1 character varying(50) DEFAULT NULL::character varying,
    nm_pessoa_contato2 character varying(50) DEFAULT NULL::character varying,
    nu_telefone1 character varying(11) DEFAULT NULL::character varying,
    te_email_contato2 character varying(50) DEFAULT NULL::character varying,
    nu_telefone2 character varying(11) DEFAULT NULL::character varying,
    te_email_contato1 character varying(50) DEFAULT NULL::character varying,
    te_serv_cacic character varying(60) NOT NULL,
    te_serv_updates character varying(60) NOT NULL,
    te_path_serv_updates character varying(255) DEFAULT NULL::character varying,
    nm_usuario_login_serv_updates character varying(20) DEFAULT NULL::character varying,
    te_senha_login_serv_updates character varying(20) DEFAULT NULL::character varying,
    nu_porta_serv_updates character varying(4) DEFAULT NULL::character varying,
    te_mascara_rede character varying(15) DEFAULT NULL::character varying,
    dt_verifica_updates date,
    nm_usuario_login_serv_updates_gerente character varying(20) DEFAULT NULL::character varying,
    te_senha_login_serv_updates_gerente character varying(20) DEFAULT NULL::character varying,
    nu_limite_ftp integer NOT NULL,
    cs_permitir_desativar_srcacic character varying(1) NOT NULL,
    te_debugging text,
    dt_debug character varying(8) DEFAULT NULL::character varying
);


CREATE TABLE rede_grupo_ftp (
    id_ftp integer NOT NULL,
    id_rede integer,
    id_computador integer,
    nu_hora_inicio timestamp(0) without time zone NOT NULL, (muda pra timestamp)
    nu_hora_fim timestamp(0) without time zone   (muda pra timestamp e tira o not null)
);


CREATE TABLE rede_versao_modulo (
    id_rede_versao_modulo integer NOT NULL,
    id_rede integer,
    nm_modulo character varying(100) NOT NULL,
    te_versao_modulo character varying(20) DEFAULT NULL::character varying,
    dt_atualizacao timestamp(0) without time zone NOT NULL,
    cs_tipo_so character varying(20) NOT NULL,
    te_hash character varying(40) DEFAULT NULL::character varying
);


CREATE TABLE servidor_autenticacao (
    id_servidor_autenticacao integer NOT NULL,
    nm_servidor_autenticacao character varying(60) NOT NULL,
    nm_servidor_autenticacao_dns character varying(60) NOT NULL,
    te_ip_servidor_autenticacao character varying(15) NOT NULL,
    nu_porta_servidor_autenticacao character varying(5) NOT NULL,
    id_tipo_protocolo character varying(20) NOT NULL,
    nu_versao_protocolo character varying(10) NOT NULL,
    te_atributo_identificador character varying(100) NOT NULL,
    te_atributo_identificador_alternativo character varying(100) DEFAULT NULL::character varying,
    te_atributo_retorna_nome character varying(100) NOT NULL,
    te_atributo_retorna_email character varying(100) NOT NULL,
    te_atributo_retorna_telefone character varying(100) DEFAULT NULL::character varying,
    te_atributo_status_conta character varying(100) DEFAULT NULL::character varying,
    te_atributo_valor_status_conta_valida character varying(100) NOT NULL,
    te_observacao text,
    in_ativo character varying(1) NOT NULL
);


CREATE TABLE so (
    id_so integer NOT NULL,
    te_desc_so character varying(255) DEFAULT NULL::character varying,
    sg_so character varying(20) DEFAULT NULL::character varying,
    te_so character varying(50) NOT NULL,
    in_mswindows character varying(1) NOT NULL
);


CREATE TABLE software (
    id_software integer NOT NULL,
    id_tipo_software integer,
    nm_software character varying(150) NOT NULL,
    te_descricao_software character varying(255) DEFAULT NULL::character varying,
    qt_licenca integer,
    nr_midia character varying(10) DEFAULT NULL::character varying,
    te_local_midia character varying(30) DEFAULT NULL::character varying,
    te_obs character varying(200) DEFAULT NULL::character varying
);


CREATE TABLE software_estacao (
    id_computador integer NOT NULL,
    id_software integer NOT NULL,
    id_aquisicao integer,
    nr_patrimonio character varying(20) DEFAULT NULL::character varying,
    dt_autorizacao date,
    dt_expiracao_instalacao date,
    id_aquisicao_particular integer,
    dt_desinstalacao date,
    te_observacao character varying(90) DEFAULT NULL::character varying,
    nr_patr_destino character varying(20) DEFAULT NULL::character varying
);


CREATE TABLE srcacic_action (
    id_srcacic_action integer NOT NULL,
    id_srcacic_conexao integer,
    dt_hr_action timestamp(0) without time zone NOT NULL,
    te_action character varying(50) NOT NULL,
    te_param1 text NOT NULL,
    te_param2 text,
    te_flag integer NOT NULL
);


CREATE TABLE srcacic_chat (
    id_srcacic_chat integer NOT NULL,
    id_srcacic_conexao integer,
    dt_hr_mensagem timestamp(0) without time zone NOT NULL,
    te_mensagem text NOT NULL,
    cs_origem character varying(3) NOT NULL
);


CREATE TABLE srcacic_conexao (
    id_srcacic_conexao integer NOT NULL,
    id_srcacic_sessao integer,
    id_usuario_cli integer,
    id_so_cli integer,
    te_node_address_cli character varying(17) NOT NULL,
    te_documento_referencial character varying(60) NOT NULL,
    te_motivo_conexao text NOT NULL,
    dt_hr_inicio_conexao timestamp(0) without time zone NOT NULL,
    dt_hr_ultimo_contato timestamp(0) without time zone NOT NULL
);


CREATE TABLE srcacic_sessao (
    id_srcacic_sessao integer NOT NULL,
    id_computador integer,
    dt_hr_inicio_sessao timestamp(0) without time zone NOT NULL,
    nm_acesso_usuario_srv character varying(30) NOT NULL,
    nm_completo_usuario_srv character varying(100) NOT NULL,
    te_email_usuario_srv character varying(60) NOT NULL,
    dt_hr_ultimo_contato timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


CREATE TABLE srcacic_transf (
    id_srcacic_transf integer NOT NULL,
    id_srcacic_conexao integer,
    dt_systemtime timestamp(0) without time zone NOT NULL,
    nu_duracao double precision NOT NULL,
    te_path_origem character varying(255) NOT NULL,
    te_path_destino character varying(255) NOT NULL,
    nm_arquivo character varying(127) NOT NULL,
    nu_tamanho_arquivo integer NOT NULL,
    cs_status character varying(1) NOT NULL,
    cs_operacao character varying(1) NOT NULL
);


CREATE TABLE teste (
    id_transacao integer NOT NULL,
    te_linha text
);


CREATE TABLE tipo_licenca (
    id_tipo_licenca integer NOT NULL,
    te_tipo_licenca character varying(20) NOT NULL
);


CREATE TABLE tipo_software (
    id_tipo_software integer NOT NULL,
    te_descricao_tipo_software character varying(30) NOT NULL
);


CREATE TABLE tipo_uorg (
    id_tipo_uorg integer NOT NULL,
    nm_tipo_uorg character varying(50) NOT NULL,
    tedescricao text
);


CREATE TABLE unid_organizacional_nivel1 (
    id_unid_organizacional_nivel1 integer NOT NULL,
    nm_unid_organizacional_nivel1 character varying(50) DEFAULT NULL::character varying,
    te_endereco_uon1 character varying(80) DEFAULT NULL::character varying,
    te_bairro_uon1 character varying(30) DEFAULT NULL::character varying,
    te_cidade_uon1 character varying(50) DEFAULT NULL::character varying,
    te_uf_uon1 character varying(2) DEFAULT NULL::character varying,
    nm_responsavel_uon1 character varying(80) DEFAULT NULL::character varying,
    te_email_responsavel_uon1 character varying(50) DEFAULT NULL::character varying,
    nu_tel1_responsavel_uon1 character varying(10) DEFAULT NULL::character varying,
    nu_tel2_responsavel_uon1 character varying(10) DEFAULT NULL::character varying
);


CREATE TABLE unid_organizacional_nivel1a (
    id_unid_organizacional_nivel1a integer NOT NULL,
    id_unid_organizacional_nivel1 integer,
    nm_unid_organizacional_nivel1a character varying(50) DEFAULT NULL::character varying
);


CREATE TABLE unid_organizacional_nivel2 (
    id_unid_organizacional_nivel2 integer NOT NULL,
    id_local integer,
    id_unid_organizacional_nivel1a integer,
    nm_unid_organizacional_nivel2 character varying(50) NOT NULL,
    te_endereco_uon2 character varying(80) DEFAULT NULL::character varying,
    te_bairro_uon2 character varying(30) DEFAULT NULL::character varying,
    te_cidade_uon2 character varying(50) DEFAULT NULL::character varying,
    te_uf_uon2 character varying(2) DEFAULT NULL::character varying,
    nm_responsavel_uon2 character varying(80) DEFAULT NULL::character varying,
    te_email_responsavel_uon2 character varying(50) DEFAULT NULL::character varying,
    nu_tel1_responsavel_uon2 character varying(10) DEFAULT NULL::character varying,
    nu_tel2_responsavel_uon2 character varying(10) DEFAULT NULL::character varying,
    dt_registro timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


CREATE TABLE uorg (
    id_uorg integer NOT NULL,
    id_uorg_pai integer,
    id_tipo_uorg integer NOT NULL,
    id_local integer,
    nm_uorg character varying(50) NOT NULL,
    te_endereco character varying(80) DEFAULT NULL::character varying,
    te_bairro character varying(30) DEFAULT NULL::character varying,
    te_cidade character varying(50) DEFAULT NULL::character varying,
    te_uf character varying(2) DEFAULT NULL::character varying,
    nm_responsavel character varying(80) DEFAULT NULL::character varying,
    te_responsavel_email character varying(50) DEFAULT NULL::character varying,
    nu_responsavel_tel1 character varying(10) DEFAULT NULL::character varying,
    nu_responsavel_tel2 character varying(10) DEFAULT NULL::character varying
);


CREATE TABLE usb_device (
    id_usb_device character varying(5) NOT NULL, (auto-increment)
(+) id_device (puxando id_device anterior)
    id_usb_vendor character varying(5) DEFAULT NULL::character varying,
    nm_usb_device character varying(127) NOT NULL,
    te_observacao text NOT NULL,
    dt_registro character varying(12) DEFAULT NULL::character varying
);


CREATE TABLE usb_log (
    id_usb_log integer NOT NULL,
(-) id_usb_vendor character varying(5) DEFAULT NULL::character varying,
    id_usb_device character varying(5) DEFAULT NULL::character varying,
    id_computador integer,
    dt_event character varying(14) NOT NULL,
    cs_event character varying(1) NOT NULL
);


CREATE TABLE usb_vendor (
    id_usb_vendor character varying(5) NOT NULL,
    nm_usb_vendor character varying(100) NOT NULL,
    te_observacao text,
    dt_registro character varying(12) DEFAULT NULL::character varying
);


CREATE TABLE usuario (
    id_usuario integer NOT NULL,
    id_local integer NOT NULL,
    id_servidor_autenticacao integer,
    id_grupo_usuario integer NOT NULL,
    id_usuario_ldap character varying(100) DEFAULT NULL::character varying,
    nm_usuario_acesso character varying(20) NOT NULL,
    nm_usuario_completo character varying(60) NOT NULL,
    nm_usuario_completo_ldap character varying(100) DEFAULT NULL::character varying,
    te_senha character varying(60) NOT NULL,
    dt_log_in timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    te_emails_contato character varying(100) DEFAULT NULL::character varying,
    te_telefones_contato character varying(100) DEFAULT NULL::character varying
);