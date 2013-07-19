--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: acao; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE acao (
    id_acao character varying(30) NOT NULL,
    te_descricao_breve character varying(100) DEFAULT NULL::character varying,
    te_descricao text,
    te_nome_curto_modulo character varying(20) DEFAULT NULL::character varying,
    dt_hr_alteracao timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    cs_opcional character varying(1) NOT NULL
);


ALTER TABLE public.acao OWNER TO "www-data";

--
-- Name: acao_excecao; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE acao_excecao (
    te_node_address character varying(17) NOT NULL,
    id_acao character varying(30) NOT NULL,
    id_rede integer NOT NULL
);


ALTER TABLE public.acao_excecao OWNER TO "www-data";

--
-- Name: acao_rede; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE acao_rede (
    id_acao character varying(30) NOT NULL,
    id_rede integer NOT NULL,
    dt_hr_coleta_forcada timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.acao_rede OWNER TO "www-data";

--
-- Name: acao_so; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE acao_so (
    id_acao character varying(30) NOT NULL,
    id_rede integer NOT NULL,
    id_so integer NOT NULL
);


ALTER TABLE public.acao_so OWNER TO "www-data";

--
-- Name: aplicativo; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.aplicativo OWNER TO "www-data";

--
-- Name: aplicativo_id_aplicativo_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE aplicativo_id_aplicativo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aplicativo_id_aplicativo_seq OWNER TO "www-data";

--
-- Name: aplicativo_id_aplicativo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE aplicativo_id_aplicativo_seq OWNED BY aplicativo.id_aplicativo;


--
-- Name: aplicativo_rede; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE aplicativo_rede (
    id_rede integer NOT NULL,
    id_aplicativo integer NOT NULL
);


ALTER TABLE public.aplicativo_rede OWNER TO "www-data";

--
-- Name: aquisicao; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE aquisicao (
    id_aquisicao integer NOT NULL,
    dt_aquisicao date,
    nr_processo character varying(11) DEFAULT NULL::character varying,
    nm_empresa character varying(45) DEFAULT NULL::character varying,
    nm_proprietario character varying(45) DEFAULT NULL::character varying,
    nr_notafiscal character varying(20) DEFAULT NULL::character varying
);


ALTER TABLE public.aquisicao OWNER TO "www-data";

--
-- Name: aquisicao_id_aquisicao_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE aquisicao_id_aquisicao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.aquisicao_id_aquisicao_seq OWNER TO "www-data";

--
-- Name: aquisicao_id_aquisicao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE aquisicao_id_aquisicao_seq OWNED BY aquisicao.id_aquisicao;


--
-- Name: aquisicao_item; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE aquisicao_item (
    id_tipo_licenca integer NOT NULL,
    id_aquisicao integer NOT NULL,
    id_software integer NOT NULL,
    qt_licenca integer,
    dt_vencimento_licenca date,
    te_obs character varying(50) DEFAULT NULL::character varying
);


ALTER TABLE public.aquisicao_item OWNER TO "www-data";

--
-- Name: class_property; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE class_property (
    id_class_property integer NOT NULL,
    id_class integer,
    nm_property_name character varying(100) NOT NULL,
    te_property_description character varying(100) NOT NULL,
    nm_function_pre_db character varying(30) DEFAULT NULL::character varying,
    nm_function_pos_db character varying(30) DEFAULT NULL::character varying
);


ALTER TABLE public.class_property OWNER TO "www-data";

--
-- Name: class_property_id_class_property_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE class_property_id_class_property_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.class_property_id_class_property_seq OWNER TO "www-data";

--
-- Name: class_property_id_class_property_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE class_property_id_class_property_seq OWNED BY class_property.id_class_property;


--
-- Name: class_property_type; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE class_property_type (
    id_class_property_type integer NOT NULL,
    id_class_property integer,
    cs_type character varying(20) NOT NULL,
    te_type_description character varying(100) NOT NULL
);


ALTER TABLE public.class_property_type OWNER TO "www-data";

--
-- Name: class_property_type_id_class_property_type_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE class_property_type_id_class_property_type_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.class_property_type_id_class_property_type_seq OWNER TO "www-data";

--
-- Name: class_property_type_id_class_property_type_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE class_property_type_id_class_property_type_seq OWNED BY class_property_type.id_class_property_type;


--
-- Name: classe; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE classe (
    id_class integer NOT NULL,
    nm_class_name character varying(50) NOT NULL,
    te_class_description character varying(255) NOT NULL
);


ALTER TABLE public.classe OWNER TO "www-data";

--
-- Name: classe_id_class_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE classe_id_class_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.classe_id_class_seq OWNER TO "www-data";

--
-- Name: classe_id_class_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE classe_id_class_seq OWNED BY classe.id_class;


--
-- Name: collect_def_class; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE collect_def_class (
    id_collect_def_class integer NOT NULL,
    id_acao character varying(30) DEFAULT NULL::character varying,
    id_class integer,
    te_where_clause text
);


ALTER TABLE public.collect_def_class OWNER TO "www-data";

--
-- Name: collect_def_class_id_collect_def_class_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE collect_def_class_id_collect_def_class_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.collect_def_class_id_collect_def_class_seq OWNER TO "www-data";

--
-- Name: collect_def_class_id_collect_def_class_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE collect_def_class_id_collect_def_class_seq OWNED BY collect_def_class.id_collect_def_class;


--
-- Name: computador; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE computador (
    id_computador integer NOT NULL,
    id_usuario_exclusao integer,
    id_so integer,
    id_rede integer,
    nm_computador character varying(50) DEFAULT NULL::character varying,
    te_node_address character varying(17) NOT NULL,
    te_ip_computador character varying(15) DEFAULT NULL::character varying,
    dt_hr_inclusao timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    dt_hr_exclusao timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    dt_hr_ult_acesso timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    te_versao_cacic character varying(15) DEFAULT NULL::character varying,
    te_versao_gercols character varying(15) DEFAULT NULL::character varying,
    te_palavra_chave character varying(30) NOT NULL,
    dt_hr_coleta_forcada_estacao timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    te_nomes_curtos_modulos character varying(255) DEFAULT NULL::character varying,
    id_conta integer,
    te_debugging text,
    te_ultimo_login character varying(100) DEFAULT NULL::character varying,
    dt_debug character varying(8) DEFAULT NULL::character varying
);


ALTER TABLE public.computador OWNER TO "www-data";

--
-- Name: computador_coleta; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE computador_coleta (
    id_computador_coleta integer NOT NULL,
    id_computador integer,
    id_class integer,
    te_class_values text NOT NULL
);


ALTER TABLE public.computador_coleta OWNER TO "www-data";

--
-- Name: computador_coleta_historico; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE computador_coleta_historico (
    id_computador_coleta_historico integer NOT NULL,
    id_computador_coleta integer,
    id_computador integer,
    id_class integer,
    te_class_values text NOT NULL,
    dt_hr_inclusao timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.computador_coleta_historico OWNER TO "www-data";

--
-- Name: computador_coleta_historico_id_computador_coleta_historico_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE computador_coleta_historico_id_computador_coleta_historico_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.computador_coleta_historico_id_computador_coleta_historico_seq OWNER TO "www-data";

--
-- Name: computador_coleta_historico_id_computador_coleta_historico_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE computador_coleta_historico_id_computador_coleta_historico_seq OWNED BY computador_coleta_historico.id_computador_coleta_historico;


--
-- Name: computador_coleta_id_computador_coleta_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE computador_coleta_id_computador_coleta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.computador_coleta_id_computador_coleta_seq OWNER TO "www-data";

--
-- Name: computador_coleta_id_computador_coleta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE computador_coleta_id_computador_coleta_seq OWNED BY computador_coleta.id_computador_coleta;


--
-- Name: computador_id_computador_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE computador_id_computador_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.computador_id_computador_seq OWNER TO "www-data";

--
-- Name: computador_id_computador_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE computador_id_computador_seq OWNED BY computador.id_computador;


--
-- Name: configuracao_local; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE configuracao_local (
    id_local integer NOT NULL,
    id_configuracao character varying(50) NOT NULL,
    vl_configuracao text
);


ALTER TABLE public.configuracao_local OWNER TO "www-data";

--
-- Name: configuracao_padrao; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE configuracao_padrao (
    id_configuracao character varying(50) NOT NULL,
    nm_configuracao character varying(100) NOT NULL,
    vl_configuracao text
);


ALTER TABLE public.configuracao_padrao OWNER TO "www-data";

--
-- Name: descricao_coluna_computador; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE descricao_coluna_computador (
    te_source character varying(100) NOT NULL,
    te_target character varying(100) NOT NULL,
    te_description character varying(100) NOT NULL,
    cs_condicao_pesquisa character varying(1) NOT NULL
);


ALTER TABLE public.descricao_coluna_computador OWNER TO "www-data";

--
-- Name: grupo_usuario; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE grupo_usuario (
    id_grupo_usuario integer NOT NULL,
    nm_grupo_usuarios character varying(20) NOT NULL,
    te_grupo_usuarios character varying(20) DEFAULT NULL::character varying,
    te_menu_grupo character varying(20) DEFAULT NULL::character varying,
    te_descricao_grupo text,
    cs_nivel_administracao boolean
);


ALTER TABLE public.grupo_usuario OWNER TO "www-data";

--
-- Name: grupo_usuario_id_grupo_usuario_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE grupo_usuario_id_grupo_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.grupo_usuario_id_grupo_usuario_seq OWNER TO "www-data";

--
-- Name: grupo_usuario_id_grupo_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE grupo_usuario_id_grupo_usuario_seq OWNED BY grupo_usuario.id_grupo_usuario;


--
-- Name: insucesso_instalacao; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE insucesso_instalacao (
    id_insucesso_instalacao integer NOT NULL,
    te_ip_computador character varying(15) NOT NULL,
    te_so character varying(60) NOT NULL,
    id_usuario character varying(60) NOT NULL,
    dt_datahora timestamp(0) without time zone NOT NULL,
    cs_indicador character varying(1) NOT NULL
);


ALTER TABLE public.insucesso_instalacao OWNER TO "www-data";

--
-- Name: insucesso_instalacao_id_insucesso_instalacao_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE insucesso_instalacao_id_insucesso_instalacao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.insucesso_instalacao_id_insucesso_instalacao_seq OWNER TO "www-data";

--
-- Name: insucesso_instalacao_id_insucesso_instalacao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE insucesso_instalacao_id_insucesso_instalacao_seq OWNED BY insucesso_instalacao.id_insucesso_instalacao;


--
-- Name: local; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE local (
    id_local integer NOT NULL,
    nm_local character varying(100) NOT NULL,
    sg_local character varying(20) NOT NULL,
    te_observacao character varying(255) DEFAULT NULL::character varying,
    te_debugging text,
    dt_debug character varying(8) DEFAULT NULL::character varying
);


ALTER TABLE public.local OWNER TO "www-data";

--
-- Name: local_id_local_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE local_id_local_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.local_id_local_seq OWNER TO "www-data";

--
-- Name: local_id_local_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE local_id_local_seq OWNED BY local.id_local;


--
-- Name: local_secundario; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE local_secundario (
    id_usuario integer NOT NULL,
    id_local integer NOT NULL
);


ALTER TABLE public.local_secundario OWNER TO "www-data";

--
-- Name: log; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE log (
    id_log integer NOT NULL,
    id_usuario integer,
    dt_acao timestamp(0) without time zone NOT NULL,
    cs_acao character varying(20) NOT NULL,
    nm_script character varying(255) NOT NULL,
    nm_tabela character varying(255) NOT NULL,
    te_ip_origem character varying(15) NOT NULL
);


ALTER TABLE public.log OWNER TO "www-data";

--
-- Name: log_id_log_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE log_id_log_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.log_id_log_seq OWNER TO "www-data";

--
-- Name: log_id_log_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE log_id_log_seq OWNED BY log.id_log;


--
-- Name: patrimonio; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.patrimonio OWNER TO "www-data";

--
-- Name: patrimonio_config_interface; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.patrimonio_config_interface OWNER TO "www-data";

--
-- Name: patrimonio_id_patrimonio_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE patrimonio_id_patrimonio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.patrimonio_id_patrimonio_seq OWNER TO "www-data";

--
-- Name: patrimonio_id_patrimonio_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE patrimonio_id_patrimonio_seq OWNED BY patrimonio.id_patrimonio;


--
-- Name: rede; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.rede OWNER TO "www-data";

--
-- Name: rede_grupo_ftp; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE rede_grupo_ftp (
    id_ftp integer NOT NULL,
    id_rede integer,
    id_computador integer,
    nu_hora_inicio time(0) without time zone NOT NULL,
    nu_hora_fim time(0) without time zone NOT NULL
);


ALTER TABLE public.rede_grupo_ftp OWNER TO "www-data";

--
-- Name: rede_grupo_ftp_id_ftp_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE rede_grupo_ftp_id_ftp_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rede_grupo_ftp_id_ftp_seq OWNER TO "www-data";

--
-- Name: rede_grupo_ftp_id_ftp_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE rede_grupo_ftp_id_ftp_seq OWNED BY rede_grupo_ftp.id_ftp;


--
-- Name: rede_id_rede_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE rede_id_rede_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rede_id_rede_seq OWNER TO "www-data";

--
-- Name: rede_id_rede_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE rede_id_rede_seq OWNED BY rede.id_rede;


--
-- Name: rede_versao_modulo; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE rede_versao_modulo (
    id_rede_versao_modulo integer NOT NULL,
    id_rede integer,
    nm_modulo character varying(100) NOT NULL,
    te_versao_modulo character varying(20) DEFAULT NULL::character varying,
    dt_atualizacao timestamp(0) without time zone NOT NULL,
    cs_tipo_so character varying(20) NOT NULL,
    te_hash character varying(40) DEFAULT NULL::character varying
);


ALTER TABLE public.rede_versao_modulo OWNER TO "www-data";

--
-- Name: rede_versao_modulo_id_rede_versao_modulo_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE rede_versao_modulo_id_rede_versao_modulo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rede_versao_modulo_id_rede_versao_modulo_seq OWNER TO "www-data";

--
-- Name: rede_versao_modulo_id_rede_versao_modulo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE rede_versao_modulo_id_rede_versao_modulo_seq OWNED BY rede_versao_modulo.id_rede_versao_modulo;


--
-- Name: servidor_autenticacao; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.servidor_autenticacao OWNER TO "www-data";

--
-- Name: servidor_autenticacao_id_servidor_autenticacao_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE servidor_autenticacao_id_servidor_autenticacao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.servidor_autenticacao_id_servidor_autenticacao_seq OWNER TO "www-data";

--
-- Name: servidor_autenticacao_id_servidor_autenticacao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE servidor_autenticacao_id_servidor_autenticacao_seq OWNED BY servidor_autenticacao.id_servidor_autenticacao;


--
-- Name: so; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE so (
    id_so integer NOT NULL,
    te_desc_so character varying(255) DEFAULT NULL::character varying,
    sg_so character varying(20) DEFAULT NULL::character varying,
    te_so character varying(50) NOT NULL,
    in_mswindows character varying(1) NOT NULL
);


ALTER TABLE public.so OWNER TO "www-data";

--
-- Name: so_id_so_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE so_id_so_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.so_id_so_seq OWNER TO "www-data";

--
-- Name: so_id_so_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE so_id_so_seq OWNED BY so.id_so;


--
-- Name: software; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.software OWNER TO "www-data";

--
-- Name: software_estacao; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.software_estacao OWNER TO "www-data";

--
-- Name: software_id_software_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE software_id_software_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.software_id_software_seq OWNER TO "www-data";

--
-- Name: software_id_software_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE software_id_software_seq OWNED BY software.id_software;


--
-- Name: srcacic_action; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE srcacic_action (
    id_srcacic_action integer NOT NULL,
    id_srcacic_conexao integer,
    dt_hr_action timestamp(0) without time zone NOT NULL,
    te_action character varying(50) NOT NULL,
    te_param1 text NOT NULL,
    te_param2 text,
    te_flag integer NOT NULL
);


ALTER TABLE public.srcacic_action OWNER TO "www-data";

--
-- Name: srcacic_action_id_srcacic_action_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE srcacic_action_id_srcacic_action_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.srcacic_action_id_srcacic_action_seq OWNER TO "www-data";

--
-- Name: srcacic_action_id_srcacic_action_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE srcacic_action_id_srcacic_action_seq OWNED BY srcacic_action.id_srcacic_action;


--
-- Name: srcacic_chat; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE srcacic_chat (
    id_srcacic_chat integer NOT NULL,
    id_srcacic_conexao integer,
    dt_hr_mensagem timestamp(0) without time zone NOT NULL,
    te_mensagem text NOT NULL,
    cs_origem character varying(3) NOT NULL
);


ALTER TABLE public.srcacic_chat OWNER TO "www-data";

--
-- Name: srcacic_chat_id_srcacic_chat_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE srcacic_chat_id_srcacic_chat_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.srcacic_chat_id_srcacic_chat_seq OWNER TO "www-data";

--
-- Name: srcacic_chat_id_srcacic_chat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE srcacic_chat_id_srcacic_chat_seq OWNED BY srcacic_chat.id_srcacic_chat;


--
-- Name: srcacic_conexao; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.srcacic_conexao OWNER TO "www-data";

--
-- Name: srcacic_conexao_id_srcacic_conexao_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE srcacic_conexao_id_srcacic_conexao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.srcacic_conexao_id_srcacic_conexao_seq OWNER TO "www-data";

--
-- Name: srcacic_conexao_id_srcacic_conexao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE srcacic_conexao_id_srcacic_conexao_seq OWNED BY srcacic_conexao.id_srcacic_conexao;


--
-- Name: srcacic_sessao; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE srcacic_sessao (
    id_srcacic_sessao integer NOT NULL,
    id_computador integer,
    dt_hr_inicio_sessao timestamp(0) without time zone NOT NULL,
    nm_acesso_usuario_srv character varying(30) NOT NULL,
    nm_completo_usuario_srv character varying(100) NOT NULL,
    te_email_usuario_srv character varying(60) NOT NULL,
    dt_hr_ultimo_contato timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.srcacic_sessao OWNER TO "www-data";

--
-- Name: srcacic_sessao_id_srcacic_sessao_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE srcacic_sessao_id_srcacic_sessao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.srcacic_sessao_id_srcacic_sessao_seq OWNER TO "www-data";

--
-- Name: srcacic_sessao_id_srcacic_sessao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE srcacic_sessao_id_srcacic_sessao_seq OWNED BY srcacic_sessao.id_srcacic_sessao;


--
-- Name: srcacic_transf; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.srcacic_transf OWNER TO "www-data";

--
-- Name: srcacic_transf_id_srcacic_transf_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE srcacic_transf_id_srcacic_transf_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.srcacic_transf_id_srcacic_transf_seq OWNER TO "www-data";

--
-- Name: srcacic_transf_id_srcacic_transf_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE srcacic_transf_id_srcacic_transf_seq OWNED BY srcacic_transf.id_srcacic_transf;


--
-- Name: teste; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE teste (
    id_transacao integer NOT NULL,
    te_linha text
);


ALTER TABLE public.teste OWNER TO "www-data";

--
-- Name: teste_id_transacao_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE teste_id_transacao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.teste_id_transacao_seq OWNER TO "www-data";

--
-- Name: teste_id_transacao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE teste_id_transacao_seq OWNED BY teste.id_transacao;


--
-- Name: tipo_licenca; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE tipo_licenca (
    id_tipo_licenca integer NOT NULL,
    te_tipo_licenca character varying(20) NOT NULL
);


ALTER TABLE public.tipo_licenca OWNER TO "www-data";

--
-- Name: tipo_licenca_id_tipo_licenca_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE tipo_licenca_id_tipo_licenca_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipo_licenca_id_tipo_licenca_seq OWNER TO "www-data";

--
-- Name: tipo_licenca_id_tipo_licenca_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE tipo_licenca_id_tipo_licenca_seq OWNED BY tipo_licenca.id_tipo_licenca;


--
-- Name: tipo_software; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE tipo_software (
    id_tipo_software integer NOT NULL,
    te_descricao_tipo_software character varying(30) NOT NULL
);


ALTER TABLE public.tipo_software OWNER TO "www-data";

--
-- Name: tipo_software_id_tipo_software_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE tipo_software_id_tipo_software_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipo_software_id_tipo_software_seq OWNER TO "www-data";

--
-- Name: tipo_software_id_tipo_software_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE tipo_software_id_tipo_software_seq OWNED BY tipo_software.id_tipo_software;


--
-- Name: tipo_uorg; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE tipo_uorg (
    id_tipo_uorg integer NOT NULL,
    nm_tipo_uorg character varying(50) NOT NULL,
    tedescricao text
);


ALTER TABLE public.tipo_uorg OWNER TO "www-data";

--
-- Name: tipo_uorg_id_tipo_uorg_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE tipo_uorg_id_tipo_uorg_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipo_uorg_id_tipo_uorg_seq OWNER TO "www-data";

--
-- Name: tipo_uorg_id_tipo_uorg_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE tipo_uorg_id_tipo_uorg_seq OWNED BY tipo_uorg.id_tipo_uorg;


--
-- Name: unid_organizacional_nivel1; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.unid_organizacional_nivel1 OWNER TO "www-data";

--
-- Name: unid_organizacional_nivel1_id_unid_organizacional_nivel1_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE unid_organizacional_nivel1_id_unid_organizacional_nivel1_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.unid_organizacional_nivel1_id_unid_organizacional_nivel1_seq OWNER TO "www-data";

--
-- Name: unid_organizacional_nivel1_id_unid_organizacional_nivel1_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE unid_organizacional_nivel1_id_unid_organizacional_nivel1_seq OWNED BY unid_organizacional_nivel1.id_unid_organizacional_nivel1;


--
-- Name: unid_organizacional_nivel1a; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE unid_organizacional_nivel1a (
    id_unid_organizacional_nivel1a integer NOT NULL,
    id_unid_organizacional_nivel1 integer,
    nm_unid_organizacional_nivel1a character varying(50) DEFAULT NULL::character varying
);


ALTER TABLE public.unid_organizacional_nivel1a OWNER TO "www-data";

--
-- Name: unid_organizacional_nivel1a_id_unid_organizacional_nivel1a_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE unid_organizacional_nivel1a_id_unid_organizacional_nivel1a_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.unid_organizacional_nivel1a_id_unid_organizacional_nivel1a_seq OWNER TO "www-data";

--
-- Name: unid_organizacional_nivel1a_id_unid_organizacional_nivel1a_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE unid_organizacional_nivel1a_id_unid_organizacional_nivel1a_seq OWNED BY unid_organizacional_nivel1a.id_unid_organizacional_nivel1a;


--
-- Name: unid_organizacional_nivel2; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.unid_organizacional_nivel2 OWNER TO "www-data";

--
-- Name: unid_organizacional_nivel2_id_unid_organizacional_nivel2_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE unid_organizacional_nivel2_id_unid_organizacional_nivel2_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.unid_organizacional_nivel2_id_unid_organizacional_nivel2_seq OWNER TO "www-data";

--
-- Name: unid_organizacional_nivel2_id_unid_organizacional_nivel2_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE unid_organizacional_nivel2_id_unid_organizacional_nivel2_seq OWNED BY unid_organizacional_nivel2.id_unid_organizacional_nivel2;


--
-- Name: uorg; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

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


ALTER TABLE public.uorg OWNER TO "www-data";

--
-- Name: uorg_id_uorg_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE uorg_id_uorg_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.uorg_id_uorg_seq OWNER TO "www-data";

--
-- Name: uorg_id_uorg_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE uorg_id_uorg_seq OWNED BY uorg.id_uorg;


--
-- Name: usb_device; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE usb_device (
    id_usb_device character varying(5) NOT NULL,
    id_usb_vendor character varying(5) DEFAULT NULL::character varying,
    nm_usb_device character varying(127) NOT NULL,
    te_observacao text NOT NULL,
    dt_registro character varying(12) DEFAULT NULL::character varying
);


ALTER TABLE public.usb_device OWNER TO "www-data";

--
-- Name: usb_log; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE usb_log (
    id_usb_log integer NOT NULL,
    id_usb_vendor character varying(5) DEFAULT NULL::character varying,
    id_usb_device character varying(5) DEFAULT NULL::character varying,
    id_computador integer,
    dt_event character varying(14) NOT NULL,
    cs_event character varying(1) NOT NULL
);


ALTER TABLE public.usb_log OWNER TO "www-data";

--
-- Name: usb_log_id_usb_log_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE usb_log_id_usb_log_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usb_log_id_usb_log_seq OWNER TO "www-data";

--
-- Name: usb_log_id_usb_log_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www-data
--

ALTER SEQUENCE usb_log_id_usb_log_seq OWNED BY usb_log.id_usb_log;


--
-- Name: usb_vendor; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE usb_vendor (
    id_usb_vendor character varying(5) NOT NULL,
    nm_usb_vendor character varying(100) NOT NULL,
    te_observacao text,
    dt_registro character varying(12) DEFAULT NULL::character varying
);


ALTER TABLE public.usb_vendor OWNER TO "www-data";

--
-- Name: usuario; Type: TABLE; Schema: public; Owner: www-data; Tablespace: 
--

CREATE TABLE usuario (
    id_usuario integer NOT NULL,
    id_local integer NOT NULL,
    id_servidor_autenticacao integer,
    id_grupo_usuario integer,
    id_usuario_ldap character varying(100) DEFAULT NULL::character varying,
    nm_usuario_acesso character varying(20) NOT NULL,
    nm_usuario_completo character varying(60) NOT NULL,
    nm_usuario_completo_ldap character varying(100) DEFAULT NULL::character varying,
    te_senha character varying(60) NOT NULL,
    dt_log_in timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    te_emails_contato character varying(100) DEFAULT NULL::character varying,
    te_telefones_contato character varying(100) DEFAULT NULL::character varying
);


ALTER TABLE public.usuario OWNER TO "www-data";

--
-- Name: usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: www-data
--

CREATE SEQUENCE usuario_id_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;