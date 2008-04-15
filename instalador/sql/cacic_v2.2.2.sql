-- script para converter o banco do cacic-2.2.2 para a nova versão.

-- script para acrescentar a coluna te_locais_secundarios ah tabela usuarios
-- em funcao de implementacao do conceito *locais secundarios* na versao 2.2.3-dev

ALTER TABLE usuarios
        ADD te_locais_secundarios varchar(200) DEFAULT NULL,
     CHANGE id_grupo_usuarios id_grupo_usuarios int(1) NOT NULL default '1';

-- Acrescentar a coluna te_so ah tabela so
-- para futura implementacao de classificacao dinamica de versoes do Sistema Operacional

ALTER TABLE so 
        ADD te_so varchar(50) NOT NULL DEFAULT '',
       DROP PRIMARY KEY,
        ADD PRIMARY KEY (`id_so`,`te_so`);

-- Acrescentar a coluna id_ftp ah tabela redes_grupos_ftp
-- para corrigir liberacao de sessao iniciada a partir do cliente (Gerente de Coletas) quando em operacao de  FTP.

ALTER TABLE redes_grupos_ftp ADD id_ftp int(11) NOT NULL auto_increment;
ALTER TABLE redes_grupos_ftp ADD PRIMARY KEY ( `id_ftp` ); 

-- Altera redes_versoes_modulos
ALTER TABLE redes_versoes_modulos
        ADD dt_atualizacao datetime NOT NULL,
       DROP PRIMARY KEY,
        ADD PRIMARY KEY (`id_ip_rede`,`nm_modulo`,`id_local`);

-- Acrescentar a coluna te_exibe_graficos as tabelas configuracoes_padrao e configuracoes_locais 
-- para indicativo de exibicao dos graficos pizza da pagina principal

ALTER TABLE configuracoes_padrao 
        ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT "[acessos_locais][so][acessos][locais]";

ALTER TABLE configuracoes_locais 
        ADD dt_hr_alteracao_patrim_uon1a datetime default '0000-00-00 00:00:00',
        ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT "[acessos_locais][so][acessos][locais]",
     CHANGE dt_hr_alteracao_patrim_uon2 dt_hr_alteracao_patrim_uon2 datetime default '0000-00-00 00:00:00';

-- Acrescentar a coluna id_local aa tabela acoes_excecoes, para aplicacao por local
ALTER TABLE acoes_excecoes ADD id_local int(11) NOT NULL DEFAULT 0;

-- Alterar a coluna cs_notificacao_ativada aa tabela descricao_hardware, para aplicacao por local
ALTER TABLE descricao_hardware CHANGE cs_notificacao_ativada te_locais_notificacao_ativada text;

--
-- Table structure for table `componentes_estacoes`
--

CREATE TABLE `componentes_estacoes` (
  `te_node_address` varchar(17) NOT NULL,
  `id_so` int(11) NOT NULL,
  `cs_tipo_componente` varchar(100) NOT NULL,
  `te_valor` text NOT NULL,
  KEY `te_node_address` (`te_node_address`,`id_so`,`cs_tipo_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Componentes de hardware instalados nas estações';

--
-- Table structure for table `insucessos_instalacao`
--

CREATE TABLE `insucessos_instalacao` (
  `te_ip` varchar(15) NOT NULL,
  `te_so` varchar(60) NOT NULL,
  `id_usuario` varchar(60) NOT NULL,
  `dt_datahora` datetime NOT NULL,
  `cs_indicador` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Altera patrimonio
ALTER TABLE patrimonio CHANGE id_unid_organizacional_nivel1a id_unid_organizacional_nivel1a int(11) NOT NULL;

-- Altera softwares_inventariados
ALTER TABLE softwares_inventariados
        ADD te_hash varchar(40) NOT NULL;

--
-- Table structure for table `unid_organizacional_nivel1a`
--

CREATE TABLE `unid_organizacional_nivel1a` (
  `id_unid_organizacional_nivel1` int(11) NOT NULL,
  `id_unid_organizacional_nivel1a` int(11) NOT NULL auto_increment,
  `nm_unid_organizacional_nivel1a` varchar(50) default NULL,
  PRIMARY KEY  (`id_unid_organizacional_nivel1a`)
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=latin1;

ALTER TABLE unid_organizacional_nivel2
     CHANGE id_unid_organizacional_nivel1 id_unid_organizacional_nivel1a int(11) NOT NULL default '0',
       DROP PRIMARY KEY,
        ADD PRIMARY KEY (`id_unid_organizacional_nivel2`,`id_unid_organizacional_nivel1a`,`id_local`);

ALTER TABLE variaveis_ambiente
        ADD te_hash varchar(40) NOT NULL;

