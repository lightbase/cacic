-- ----------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-v2.4.0-rc1
-- SGBD: MySQL-4.1.20
-- ----------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

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

-- ---------------------------------------------------------------------------------------------
-- Estrutura da tabela `srcacic_sessoes`
-- Armazenamento de sessoes de Suporte Remoto Seguro
-- Contera os dados dos usuarios visitante e visitado e suas sessoes para fins de suporte remoto
-- ---------------------------------------------------------------------------------------------
CREATE TABLE `srcacic_sessoes` 
	(
	`id_sessao` int(11) NOT NULL auto_increment,
	`dt_hr_inicio_sessao` datetime NOT NULL,
	`dt_hr_fim_sessao` datetime default NULL,
	`id_usuario_visitante` int(11) NOT NULL default '0',
	`nm_nome_acesso_visitado` varchar(30) character set utf8 NOT NULL,
	`nm_nome_completo_visitado` varchar(100) NOT NULL default 'NoNoNo',
	`te_node_address_visitado` varchar(17) character set utf8 NOT NULL,
	`id_so_visitado` int(11) NOT NULL,
	`te_node_address_visitante` varchar(17) character set utf8 NOT NULL default 'NoNoNo',
	`dt_hr_ult_contato` datetime default NULL,
	PRIMARY KEY  (`id_sessao`),
		KEY `idx_idUsuario` (`id_usuario_visitante`),
		KEY `idx_dtHrInicioSessao` (`dt_hr_inicio_sessao`)
	) 
ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Log de Atividades no Sistema CACIC';

-- -----------------------------------------------------------------------------------------------------------
-- Estrutura da tabela `dominios`
-- Armazenamento de dados de servidores de dominios
-- Esses dados serao utilizados nas autenticacoes de usuarios e criacao de sessoes para fins de suporte remoto
-- -----------------------------------------------------------------------------------------------------------
CREATE TABLE `dominios` 
	(
	`id_dominio` int(11) NOT NULL auto_increment,
      `nm_dominio` varchar(60) NOT NULL,
	`te_ip_dominio` varchar(15) NOT NULL,
	`id_tipo_protocolo` varchar(20) NOT NULL,
	`nu_versao_protocolo` varchar(10) NOT NULL,
	`te_string_DN` varchar(100) NOT NULL,
	`te_observacao` text NOT NULL,
	`in_ativo` char(1) NOT NULL default 'S',
	PRIMARY KEY  (`id_dominio`)
	) 
ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Servidores de Dominio para Autenticacao do srCACIC';

-- -----------------------------------------------------------------------------------------------------------
-- Insercao da coluna `id_dominio`
-- Relacionamento de redes com servidores de dominios
-- -----------------------------------------------------------------------------------------------------------
ALTER TABLE `redes` ADD `id_dominio` INT( 11 ) NULL AFTER `id_local` ;

-- -----------------------------------------------------------------------------------------------------------
-- Insercao da coluna `id_dominio`
-- Relacionamento de usuarios com servidores de dominios
-- -----------------------------------------------------------------------------------------------------------
ALTER TABLE `usuarios` ADD `id_dominio` INT( 11 ) NULL AFTER `id_local` 

-- -----------------------------------------------------------------------------------------------------------
-- Redimensionamento de coluna `nm_modulo` para armazenamento de nomes maiores
-- -----------------------------------------------------------------------------------------------------------
ALTER TABLE `redes_versoes_modulos` CHANGE `nm_modulo` `nm_modulo` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL  

-- -------------------------------------------------------------------------------------------------------------
-- Insercao de coluna `in_mswindows` para classificacao do Sistema Operacional pelo tipo
-- Essa informacao sera usada principalmente na resposta do Gerente WEB aos Agentes quando estes fizerem contato
-- -------------------------------------------------------------------------------------------------------------
ALTER TABLE `so` ADD `in_mswindows` CHAR( 1 ) NOT NULL DEFAULT 'S';

-- ----------------------------------------------------------------------------------------------------------------------------------
-- Redimensionamento de coluna `nm_unid_organizacional_nivel1a` para armazenamento de nomes maiores de linhas de negocio ou similares
-- ----------------------------------------------------------------------------------------------------------------------------------
ALTER TABLE `unid_organizacional_nivel1a` CHANGE `nm_unid_organizacional_nivel1a` `nm_unid_organizacional_nivel1a` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL

-- ----------------------------------------------------------------------------------------------------------------------
-- Redimensionamento de coluna `nm_unid_organizacional_nivel2` para armazenamento de nomes maiores de orgaos ou similares
-- ----------------------------------------------------------------------------------------------------------------------
ALTER TABLE `unid_organizacional_nivel2`  CHANGE `nm_unid_organizacional_nivel2`  `nm_unid_organizacional_nivel2`  VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL  

-- ----------------------------------------------------------------------------------------------------------------------
-- Coluna para padronizar dimensões de gráficos
-- ----------------------------------------------------------------------------------------------------------------------
ALTER TABLE configuracoes_padrao
    ADD nu_resolucao_grafico_h smallint unsigned default 320,
    ADD nu_resolucao_grafico_w smallint unsigned default 240;

SET FOREIGN_KEY_CHECKS = 1;
