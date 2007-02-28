# MUSSI: Mantive o usuário "d306851" senha "d306851" para acesso...
# Você deve cadastrar subrede(s) e fazer o(s) update(s)...
# 


# phpMyAdmin MySQL-Dump
# version 2.5.0-rc2
# http://www.phpmyadmin.net/ (download page)
#
# Servidor: localhost
# Tempo de Generação: Jan 26, 2007 at 12:52 PM
# Versão do Servidor: 4.0.12
# Versão do PHP: 4.1.1
# Banco de Dados : `cacic`
# --------------------------------------------------------

#
# Estrutura da tabela `acoes`
#

CREATE TABLE acoes (
  id_acao varchar(20) NOT NULL default '',
  te_descricao_breve varchar(100) default NULL,
  te_descricao text,
  te_nome_curto_modulo varchar(20) default NULL,
  dt_hr_alteracao datetime default '0000-00-00 00:00:00',
  cs_situacao char(1) default NULL,
  PRIMARY KEY  (id_acao)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `acoes`
#

INSERT INTO acoes VALUES ('cs_auto_update', 'Auto Atualização dos Agentes', 0x457373612061e7e36f207065726d697465207175652073656a61207265616c697a6164612061206175746f20617475616c697a61e7e36f20646f73206167656e74657320646f204341434943206e6f7320636f6d70757461646f726573206f6e6465206f73206167656e7465732073e36f2065786563757461646f732e200d0a0d0a, NULL, '0000-00-00 00:00:00', NULL);
INSERT INTO acoes VALUES ('cs_coleta_compart', 'Coleta Informações de Compartilhamentos de Diretórios e Impressoras', 0x457373612061e7e36f207065726d697465207175652073656a616d20636f6c65746164617320696e666f726d61e7f5657320736f62726520636f6d70617274696c68616d656e746f73206465206469726574f372696f73206520696d70726573736f72617320646f7320636f6d70757461646f726573206f6e6465206f73206167656e74657320657374e36f20696e7374616c61646f2e, 'COMP', '0000-00-00 00:00:00', NULL);
INSERT INTO acoes VALUES ('cs_coleta_hardware', 'Coleta Informações de Hardware', 0x457373612061e7e36f207065726d697465207175652073656a616d20636f6c65746164617320646976657273617320696e666f726d61e7f5657320736f627265206f20686172647761726520646f7320636f6d70757461646f726573206f6e6465206f73206167656e74657320657374e36f20696e7374616c61646f732c207461697320636f6d6f204d656df37269612c20506c6163612064652056edad64656f2c204350552c20446973636f732052edad6769646f732c2042494f532c20506c61636120646520526564652c20506c616361204de3652c206574632e, 'HARD', '0000-00-00 00:00:00', NULL);
INSERT INTO acoes VALUES ('cs_coleta_monitorado', 'Coleta Informações sobre os Sistemas Monitorados', 0x457373612061e7e36f207065726d697465207175652073656a616d20636f6c6574616461732c206e61732065737461e7f56573206f6e6465206f73206167656e74657320436163696320657374e36f20696e7374616c61646f732c20617320696e666f726d61e7f565732061636572636120646f732070657266edad732064652073697374656d61732c207072657669616d656e7465206361646173747261646f732070656c612041646d696e6973747261e7e36f2043656e7472616c2e, 'MONI', '0000-00-00 00:00:00', NULL);
INSERT INTO acoes VALUES ('cs_coleta_officescan', 'Coleta Informações do Antivírus OfficeScan', 0x457373612061e7e36f207065726d697465207175652073656a616d20636f6c65746164617320696e666f726d61e7f5657320736f627265206f20616e746976edad727573204f66666963655363616e206e6f7320636f6d70757461646f726573206f6e6465206f73206167656e74657320657374e36f20696e7374616c61646f2e2053e36f20636f6c65746164617320696e666f726d61e7f5657320636f6d6f20612076657273e36f20646f20656e67696e652c2076657273e36f20646f207061747465726e2c20656e64657265e76f20646f207365727669646f722c206461746120646120696e7374616c61e7e36f2c206574632e, 'ANVI', '0000-00-00 00:00:00', NULL);
INSERT INTO acoes VALUES ('cs_coleta_patrimonio', 'Coleta Informações de Patrimônio', 0x457373612061e7e36f207065726d697465207175652073656a616d20636f6c65746164617320646976657273617320696e666f726d61e7f5657320736f6272652050617472696df46e696f2065204c6f63616c697a61e7e36f2046edad7369636120646f7320636f6d70757461646f726573206f6e6465206f73206167656e74657320657374e36f20696e7374616c61646f732e, 'PATR', '0000-00-00 00:00:00', NULL);
INSERT INTO acoes VALUES ('cs_coleta_software', 'Coleta Informações de Software', 0x457373612061e7e36f207065726d697465207175652073656a616d20636f6c65746164617320696e666f726d61e7f5657320736f62726520612076657273e36f206465206469766572736f7320736f6674776172657320696e7374616c61646f73206e6f7320636f6d70757461646f726573206f6e6465206f73206167656e7465732073e36f2065786563757461646f732e2053e36f20636f6c6574616461732c20706f72206578656d706c6f2c20696e666f726d61e7f5657320736f6272652061732076657273f5657320646f20496e7465726e6574204578706c6f7265722c204d6f7a696c6c612c20446972656374582c2041444f2c204244452c2044414f2c204a6176612052756e74696d6520456e7669726f6e6d656e742c206574632e, 'SOFT', '0000-00-00 00:00:00', NULL);
INSERT INTO acoes VALUES ('cs_coleta_unid_disc', 'Coleta Informações sobre Unidades de Disco', 0x457373612061e7e36f207065726d697465207175652073656a616d20636f6c65746164617320696e666f726d61e7f5657320736f62726520617320756e69646164657320646520646973636f20646973706f6eedad76656973206e6f7320636f6d70757461646f726573206f6e6465206f73206167656e7465732073e36f2065786563757461646f732e2053e36f20636f6c6574616461732c20706f72206578656d706c6f2c20696e666f726d61e7f5657320736f627265206f2073697374656d61206465206172717569766f732064617320756e6964616465732c20737561732063617061636964616465732064652061726d617a656e616d656e746f2c206f63757061e7e36f2c2065737061e76f206c697672652c206574632e, 'UNDI', '0000-00-00 00:00:00', NULL);
# --------------------------------------------------------

#
# Estrutura da tabela `acoes_excecoes`
#

CREATE TABLE acoes_excecoes (
  te_node_address varchar(17) NOT NULL default '',
  id_acao varchar(20) NOT NULL default '',
  id_so int(11) NOT NULL default '0'
) TYPE=InnoDB;

#
# Extraindo dados da tabela `acoes_excecoes`
#

# --------------------------------------------------------

#
# Estrutura da tabela `acoes_redes`
#

CREATE TABLE acoes_redes (
  id_acao varchar(20) NOT NULL default '',
  id_local int(11) NOT NULL default '0',
  id_ip_rede varchar(15) NOT NULL default '',
  dt_hr_coleta_forcada datetime default NULL,
  cs_situacao char(1) NOT NULL default 'T',
  dt_hr_alteracao datetime default NULL,
  PRIMARY KEY  (id_local,id_ip_rede,id_acao)
) TYPE=InnoDB;


#
# Estrutura da tabela `acoes_so`
#

CREATE TABLE acoes_so (
  id_local int(11) NOT NULL default '0',
  id_acao varchar(20) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  PRIMARY KEY  (id_acao,id_so,id_local)
) TYPE=InnoDB;


#
# Estrutura da tabela `aplicativos_monitorados`
#

CREATE TABLE aplicativos_monitorados (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(11) unsigned NOT NULL default '0',
  id_aplicativo int(11) unsigned NOT NULL default '0',
  te_versao varchar(50) default NULL,
  te_licenca varchar(50) default NULL,
  te_ver_engine varchar(50) default NULL,
  te_ver_pattern varchar(50) default NULL,
  cs_instalado char(1) default NULL,
  PRIMARY KEY  (te_node_address,id_so,id_aplicativo)
) TYPE=InnoDB;


#
# Estrutura da tabela `aplicativos_redes`
#

CREATE TABLE aplicativos_redes (
  id_local int(11) NOT NULL default '0',
  id_ip_rede varchar(15) NOT NULL default '',
  id_aplicativo int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (id_local,id_ip_rede,id_aplicativo)
) TYPE=InnoDB COMMENT='Relacionamento entre redes e perfis de aplicativos monitorad';


#
# Estrutura da tabela `aquisicoes`
#

CREATE TABLE aquisicoes (
  id_aquisicao int(10) unsigned NOT NULL default '0',
  dt_aquisicao date default NULL,
  nr_processo varchar(11) default NULL,
  nm_empresa varchar(45) default NULL,
  nm_proprietario varchar(45) default NULL,
  nr_notafiscal int(10) unsigned default NULL,
  PRIMARY KEY  (id_aquisicao)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `aquisicoes`
#

INSERT INTO aquisicoes VALUES (1, NULL, '12345', 'Empresa Teste', 'Proprietário Teste', 1234567);
# --------------------------------------------------------

#
# Estrutura da tabela `aquisicoes_item`
#

CREATE TABLE aquisicoes_item (
  id_aquisicao int(10) unsigned NOT NULL default '0',
  id_software int(10) unsigned NOT NULL default '0',
  id_tipo_licenca int(10) unsigned NOT NULL default '0',
  qt_licenca int(11) default NULL,
  dt_vencimento_licenca date default NULL,
  te_obs varchar(50) default NULL,
  PRIMARY KEY  (id_aquisicao,id_software,id_tipo_licenca)
) TYPE=InnoDB ROW_FORMAT=DYNAMIC;

#
# Extraindo dados da tabela `aquisicoes_item`
#

INSERT INTO aquisicoes_item VALUES (1, 1, 1, 5, NULL, NULL);
# --------------------------------------------------------

#
# Estrutura da tabela `compartilhamentos`
#

CREATE TABLE compartilhamentos (
  nm_compartilhamento varchar(30) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  te_node_address varchar(17) NOT NULL default '',
  nm_dir_compart varchar(100) default NULL,
  in_senha_escrita char(1) default NULL,
  in_senha_leitura char(1) default NULL,
  cs_tipo_permissao char(1) default NULL,
  cs_tipo_compart char(1) default NULL,
  te_comentario varchar(50) default NULL,
  PRIMARY KEY  (nm_compartilhamento,id_so,te_node_address),
  KEY node_so_tipocompart (te_node_address,id_so,cs_tipo_compart)
) TYPE=InnoDB;


#
# Estrutura da tabela `computadores`
#

CREATE TABLE computadores (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  te_so varchar(10) default NULL,
  te_nome_computador varchar(50) default NULL,
  id_ip_rede varchar(15) NOT NULL default '',
  te_dominio_windows varchar(50) default NULL,
  te_dominio_dns varchar(50) default NULL,
  te_placa_video_desc varchar(100) default NULL,
  te_ip varchar(15) default NULL,
  te_mascara varchar(15) default NULL,
  te_nome_host varchar(50) default NULL,
  te_placa_rede_desc varchar(100) default NULL,
  dt_hr_inclusao datetime default NULL,
  te_gateway varchar(15) default NULL,
  te_wins_primario varchar(15) default NULL,
  te_cpu_desc varchar(100) default NULL,
  te_wins_secundario varchar(15) default NULL,
  te_dns_primario varchar(15) default NULL,
  qt_placa_video_mem int(11) default NULL,
  te_dns_secundario varchar(15) default NULL,
  te_placa_mae_desc varchar(100) default NULL,
  te_serv_dhcp varchar(15) default NULL,
  qt_mem_ram int(11) default NULL,
  te_cpu_serial varchar(50) default NULL,
  te_cpu_fabricante varchar(100) default NULL,
  te_cpu_freq varchar(6) default NULL,
  te_mem_ram_desc varchar(200) default NULL,
  te_bios_desc varchar(100) default NULL,
  te_bios_data varchar(10) default NULL,
  dt_hr_ult_acesso datetime default NULL,
  te_versao_cacic varchar(10) default NULL,
  te_versao_gercols varchar(10) default NULL,
  te_bios_fabricante varchar(100) default NULL,
  te_placa_mae_fabricante varchar(100) default NULL,
  qt_placa_video_cores int(11) default NULL,
  te_placa_video_resolucao varchar(10) default NULL,
  te_placa_som_desc varchar(100) default NULL,
  te_cdrom_desc varchar(100) default NULL,
  te_teclado_desc varchar(100) NOT NULL default '',
  te_mouse_desc varchar(100) default NULL,
  te_modem_desc varchar(100) default NULL,
  te_workgroup varchar(50) default NULL,
  dt_hr_coleta_forcada_estacao datetime default NULL,
  te_nomes_curtos_modulos varchar(255) default NULL,
  te_origem_mac text,
  id_conta int(10) unsigned default NULL,
  PRIMARY KEY  (te_node_address,id_so),
  KEY computadores_versao_cacic (te_versao_cacic),
  KEY te_ip (te_ip),
  KEY te_node_address (te_node_address),
  KEY te_nome_computador (te_nome_computador)
) TYPE=InnoDB;

#
# Estrutura da tabela `configuracoes_locais`
#

CREATE TABLE configuracoes_locais (
  id_local int(11) unsigned NOT NULL default '0',
  te_notificar_mudanca_hardware text,
  in_exibe_erros_criticos char(1) default 'N',
  in_exibe_bandeja char(1) default 'S',
  nu_exec_apos int(11) default '10',
  dt_hr_alteracao_patrim_interface datetime default NULL,
  dt_hr_alteracao_patrim_uon1 datetime default '0000-00-00 00:00:00',
  dt_hr_alteracao_patrim_uon2 datetime default NULL,
  dt_hr_coleta_forcada datetime default NULL,
  te_notificar_mudanca_patrim text,
  nm_organizacao varchar(150) default NULL,
  nu_intervalo_exec int(11) default '4',
  nu_intervalo_renovacao_patrim int(11) default '0',
  te_senha_adm_agente varchar(30) default 'ADMINCACIC',
  te_serv_updates_padrao varchar(20) default NULL,
  te_serv_cacic_padrao varchar(20) default NULL,
  te_enderecos_mac_invalidos text,
  te_janelas_excecao text,
  te_nota_email_gerentes text,
  cs_abre_janela_patr char(1) NOT NULL default 'N',
  id_default_body_bgcolor varchar(10) NOT NULL default '#EBEBEB',
  PRIMARY KEY  (id_local)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `configuracoes_locais`
#

INSERT INTO configuracoes_locais VALUES (1, 0x616e646572736f6e2e70657465726c6540707265766964656e6369612e676f762e62722c616e646572736f6e4070657465726c65732e6f7267, 'N', 'S', 10, '2006-01-25 15:59:25', '2006-03-29 11:31:00', '2006-11-30 11:51:46', '2004-07-25 14:19:39', 0x6a6f73654074657374652e636f6d2e6272, 'Dataprev', 4, 0, 'ADMINCACIC', '10.71.0.212', '10.71.0.212', 0x30302d30302d30302d30302d30302d30302c34342d34352d35332d35342d30302d30302c34342d34352d35332d35342d30302d30312c0d0a30302d35332d34352d30302d30302d30302c30302d35302d35362d43302d30302d30312c30302d35302d35362d43302d30302d3038, 0x616f72, 0x434f4c5f4d4f4e49202d2076657273c3a36f20322e302e322e365f313331302a20466f6920616372657363656e746164612061206f70c3a7c3a36f206465206578747261c3a7c3a36f2064652076657273c3a36f206465206172717569766f20657865637574c3a176656c2c2063756a6f20706172c3a26d6574726f20646576652073657220696e666f726d61646f206e6f206dc3b364756c6f20476572656e7465205745422c206f70c3a7c3a36f20436164617374726f2064652050657266c3ad732064652053697374656d6173204d6f6e69746f7261646f732f4964656e746966696361646f722064652056657273c3a36f2f436f6e666967757261c3a7c3a36f2f56657273c3a36f20646520457865637574c3a176656c2e, 'N', '#EBEBEB');
# --------------------------------------------------------

#
# Estrutura da tabela `configuracoes_padrao`
#

CREATE TABLE configuracoes_padrao (
  in_exibe_erros_criticos char(1) default NULL,
  in_exibe_bandeja char(1) default NULL,
  nu_exec_apos int(11) default NULL,
  nm_organizacao varchar(150) default NULL,
  nu_intervalo_exec int(11) default NULL,
  nu_intervalo_renovacao_patrim int(11) default NULL,
  te_senha_adm_agente varchar(30) default NULL,
  te_serv_updates_padrao varchar(20) default NULL,
  te_serv_cacic_padrao varchar(20) default NULL,
  te_enderecos_mac_invalidos text,
  te_janelas_excecao text,
  cs_abre_janela_patr char(1) NOT NULL default 'S',
  id_default_body_bgcolor varchar(10) NOT NULL default '#EBEBEB'
) TYPE=InnoDB;


#
# Estrutura da tabela `contas`
#

CREATE TABLE contas (
  id_conta int(10) unsigned NOT NULL auto_increment,
  nm_responsavel varchar(30) NOT NULL default '',
  PRIMARY KEY  (id_conta)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

#
# Extraindo dados da tabela `contas`
#

# --------------------------------------------------------

#
# Estrutura da tabela `descricao_hardware`
#

CREATE TABLE descricao_hardware (
  nm_campo_tab_hardware varchar(45) NOT NULL default '',
  te_desc_hardware varchar(45) NOT NULL default '',
  cs_notificacao_ativada char(1) default NULL,
  PRIMARY KEY  (nm_campo_tab_hardware)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `descricao_hardware`
#

INSERT INTO descricao_hardware VALUES (' te_cdrom_desc', 'CD-ROM', '1');
INSERT INTO descricao_hardware VALUES ('qt_mem_ram', 'Memória RAM', '1');
INSERT INTO descricao_hardware VALUES ('qt_placa_video_cores', 'Qtd. Cores Placa Vídeo', '0');
INSERT INTO descricao_hardware VALUES ('qt_placa_video_mem', 'Memória Placa Vídeo', '0');
INSERT INTO descricao_hardware VALUES ('te_bios_desc', 'Descrição da BIOS', '1');
INSERT INTO descricao_hardware VALUES ('te_bios_fabricante', 'Fabricante da BIOS', '0');
INSERT INTO descricao_hardware VALUES ('te_cpu_desc', 'CPU', '0');
INSERT INTO descricao_hardware VALUES ('te_cpu_fabricante', 'Fabricante da CPU', '0');
INSERT INTO descricao_hardware VALUES ('te_cpu_serial', 'Serial da CPU', '1');
INSERT INTO descricao_hardware VALUES ('te_mem_ram_desc', 'Descrição da RAM', '0');
INSERT INTO descricao_hardware VALUES ('te_modem_desc', 'Modem', '0');
INSERT INTO descricao_hardware VALUES ('te_mouse_desc', 'Mouse', '1');
INSERT INTO descricao_hardware VALUES ('te_placa_mae_desc', 'Placa Mãe', '1');
INSERT INTO descricao_hardware VALUES ('te_placa_mae_fabricante', 'Fabricante Placa Mãe', '0');
INSERT INTO descricao_hardware VALUES ('te_placa_rede_desc', 'Placa de Rede', '1');
INSERT INTO descricao_hardware VALUES ('te_placa_som_desc', 'Placa de Som', '1');
INSERT INTO descricao_hardware VALUES ('te_placa_video_desc', 'Placa de Vídeo', '1');
INSERT INTO descricao_hardware VALUES ('te_placa_video_resolucao', 'Resolução Placa de Vídeo', '0');
INSERT INTO descricao_hardware VALUES ('te_teclado_desc', 'Teclado', '1');
# --------------------------------------------------------

#
# Estrutura da tabela `descricoes_colunas_computadores`
#

CREATE TABLE descricoes_colunas_computadores (
  nm_campo varchar(100) NOT NULL default '',
  te_descricao_campo varchar(100) NOT NULL default '',
  cs_condicao_pesquisa char(1) NOT NULL default 'S',
  UNIQUE KEY nm_campo (nm_campo)
) TYPE=InnoDB COMMENT='Tabela para auxílio na opção Exclusão de Informações de Comp.';

#
# Extraindo dados da tabela `descricoes_colunas_computadores`
#

INSERT INTO descricoes_colunas_computadores VALUES ('dt_hr_coleta_forcada_estacao', 'Quant. dias da última coleta forçada na estação', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('dt_hr_inclusao', 'Quant. dias de inclusão do computador na base', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('dt_hr_ult_acesso', 'Quant. dias do último acesso da estação ao gerente WEB', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('id_ip_rede', 'Endereço IP da Subrede', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('id_so', 'Código do sistema operacional da estação', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('qt_mem_ram', 'Quant. memória RAM', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('qt_placa_video_cores', 'Quant. cores da placa de vídeo', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('qt_placa_video_mem', 'Quant. memória da placa de vídeo', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_bios_data', 'Identificação da BIOS', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_bios_desc', 'Descrição da BIOS', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_bios_fabricante', 'Nome do fabricante da BIOS', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cdrom_desc', 'Descrição da unidade de CD-ROM', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cpu_desc', 'Descrição da CPU', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cpu_fabricante', 'Fabricante da CPU', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cpu_freq', 'Frequência da CPU', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_cpu_serial', 'Número de série da CPU', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_dns_primario', 'IP do DNS primário', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_dns_secundario', 'IP do DNS secundário', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_dominio_dns', 'Nome/IP do domínio DNS', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_dominio_windows', 'Nome/IP do domínio Windows', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_gateway', 'IP do gateway', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_ip', 'IP da estação', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_mascara', 'Máscara de Subrede', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_mem_ram_desc', 'Descrição da memória RAM', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_modem_desc', 'Descrição do modem', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_mouse_desc', 'Descrição do mouse', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_node_address', 'Endereço MAC da estação', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_nomes_curtos_modulos', 'te_nomes_curtos_modulos', 'N');
INSERT INTO descricoes_colunas_computadores VALUES ('te_nome_computador', 'Nome do computador', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_nome_host', 'Nome do Host', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_origem_mac', 'te_origem_mac', 'N');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_mae_desc', 'Descrição da placa-m', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_mae_fabricante', 'Fabricante da placa-m', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_rede_desc', 'Descrição da placa de rede', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_som_desc', 'Descrição da placa de som', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_video_desc', 'Descrição da placa de vídeo', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_placa_video_resolucao', 'Resolução da placa de vídeo', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_serv_dhcp', 'IP do servidor DHCP', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_teclado_desc', 'Descrição do teclado', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_versao_cacic', 'Versão do Agente Principal do CACIC', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_versao_gercols', 'Versão do Gerente de Coletas do CACIC', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_wins_primario', 'IP do servidor WINS primário', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_wins_secundario', 'IP do servidor WINS secundário', 'S');
INSERT INTO descricoes_colunas_computadores VALUES ('te_workgroup', 'Nome do grupo de trabalho', 'S');
# --------------------------------------------------------

#
# Estrutura da tabela `grupo_usuarios`
#

CREATE TABLE grupo_usuarios (
  id_grupo_usuarios int(2) NOT NULL auto_increment,
  te_grupo_usuarios varchar(20) default NULL,
  te_menu_grupo varchar(20) default NULL,
  te_descricao_grupo text NOT NULL,
  cs_nivel_administracao tinyint(2) NOT NULL default '0',
  nm_grupo_usuarios varchar(20) NOT NULL default '',
  PRIMARY KEY  (id_grupo_usuarios)
) TYPE=InnoDB AUTO_INCREMENT=8 ;

#
# Extraindo dados da tabela `grupo_usuarios`
#

INSERT INTO grupo_usuarios VALUES (1, 'Comum', 'menu_com.txt', 0x557375e172696f206c696d697461646f2c2073656d2061636573736f206120696e666f726d61e7f5657320636f6e666964656e636961697320636f6d6f20536f6674776172657320496e76656e7461726961646f732065204f70e7f565732041646d696e6973747261746976617320636f6d6f20466f72e7617220436f6c657461732065204578636c75697220436f6d70757461646f722e20506f646572e120616c746572617220737561207072f3707269612073656e68612e, 0, '');
INSERT INTO grupo_usuarios VALUES (2, 'Administração', 'menu_adm.txt', 0x41636573736f206972726573747269746f2e, 1, '');
INSERT INTO grupo_usuarios VALUES (5, 'Gestão Central', 'menu_adm.txt', 0x41636573736f206465206c65697475726120656d20746f646173206173206f70e7f565732e, 2, '');
INSERT INTO grupo_usuarios VALUES (6, 'Supervisão', 'menu_sup.txt', 0x4d616e7574656ee7e36f20646520746162656c617320652061636573736f206120746f64617320617320696e666f726d61e7f56573207265666572656e74657320e0204c6f63616c697a61e7e36f2e, 3, '');
INSERT INTO grupo_usuarios VALUES (7, 'Técnico', 'menu_tec.txt', 0x41636573736f2074e9636e69636f2e20536572e1207065726d697469646f206163657373617220636f6e666967757261636f6573206465207265646520652072656c6174f372696f732064652050617472696df46e696f20652048617264776172652e, 0, '');
# --------------------------------------------------------

#
# Estrutura da tabela `historico_hardware`
#

CREATE TABLE historico_hardware (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  dt_hr_alteracao datetime NOT NULL default '0000-00-00 00:00:00',
  te_placa_video_desc varchar(100) default NULL,
  te_placa_rede_desc varchar(100) default NULL,
  te_cpu_desc varchar(100) default NULL,
  qt_placa_video_mem int(11) default NULL,
  te_placa_mae_desc varchar(100) default NULL,
  qt_mem_ram int(11) default NULL,
  te_cpu_serial varchar(50) default NULL,
  te_cpu_fabricante varchar(100) default NULL,
  te_cpu_freq varchar(6) default NULL,
  te_mem_ram_desc varchar(100) default NULL,
  te_bios_desc varchar(100) default NULL,
  te_bios_data varchar(10) default NULL,
  te_bios_fabricante varchar(100) default NULL,
  te_placa_mae_fabricante varchar(100) default NULL,
  qt_placa_video_cores int(11) default NULL,
  te_placa_video_resolucao varchar(10) default NULL,
  te_placa_som_desc varchar(100) default NULL,
  te_cdrom_desc varchar(100) default NULL,
  te_teclado_desc varchar(100) default NULL,
  te_mouse_desc varchar(100) default NULL,
  te_modem_desc varchar(100) default NULL,
  te_lic_win varchar(50) default NULL,
  te_key_win varchar(50) default NULL,
  PRIMARY KEY  (te_node_address,id_so,dt_hr_alteracao)
) TYPE=InnoDB;


#
# Estrutura da tabela `historico_tcp_ip`
#

CREATE TABLE historico_tcp_ip (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  dt_hr_alteracao datetime NOT NULL default '0000-00-00 00:00:00',
  te_nome_computador varchar(25) default NULL,
  te_dominio_windows varchar(30) default NULL,
  te_dominio_dns varchar(30) default NULL,
  te_ip varchar(15) default NULL,
  te_mascara varchar(15) default NULL,
  id_ip_rede varchar(15) default NULL,
  te_nome_host varchar(15) default NULL,
  te_gateway varchar(15) default NULL,
  te_wins_primario varchar(15) default NULL,
  te_wins_secundario varchar(15) default NULL,
  te_dns_primario varchar(15) default NULL,
  te_dns_secundario varchar(15) default NULL,
  te_serv_dhcp varchar(15) default NULL,
  te_workgroup varchar(20) default NULL,
  PRIMARY KEY  (te_node_address,id_so,dt_hr_alteracao)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `historico_tcp_ip`
#

# --------------------------------------------------------

#
# Estrutura da tabela `historicos_hardware`
#

CREATE TABLE historicos_hardware (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  campo_alterado varchar(45) default '',
  valor_antigo varchar(45) default '',
  data_anterior datetime default '0000-00-00 00:00:00',
  novo_valor varchar(45) default '',
  nova_data datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (te_node_address,id_so)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `historicos_hardware`
#

# --------------------------------------------------------

#
# Estrutura da tabela `historicos_outros_softwares`
#

CREATE TABLE historicos_outros_softwares (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(10) unsigned NOT NULL default '0',
  id_software_inventariado int(10) unsigned NOT NULL default '0',
  dt_hr_inclusao datetime NOT NULL default '0000-00-00 00:00:00',
  dt_hr_ult_coleta datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (te_node_address,id_so,id_software_inventariado)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `historicos_outros_softwares`
#

# --------------------------------------------------------

#
# Estrutura da tabela `historicos_software`
#

CREATE TABLE historicos_software (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(11) unsigned NOT NULL default '0',
  id_software_inventariado int(11) unsigned NOT NULL default '0',
  dt_hr_inclusao datetime NOT NULL default '0000-00-00 00:00:00',
  dt_hr_ult_coleta datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (te_node_address,id_so,id_software_inventariado),
  KEY id_software (id_software_inventariado)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `historicos_software`
#

# --------------------------------------------------------

#
# Estrutura da tabela `historicos_software_completo`
#

CREATE TABLE historicos_software_completo (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(10) unsigned NOT NULL default '0',
  id_software_inventariado int(10) unsigned NOT NULL default '0',
  dt_hr_inclusao datetime NOT NULL default '0000-00-00 00:00:00',
  dt_hr_ult_coleta datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (te_node_address,id_so,id_software_inventariado,dt_hr_inclusao)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `historicos_software_completo`
#

# --------------------------------------------------------

#
# Estrutura da tabela `locais`
#

CREATE TABLE locais (
  id_local int(11) unsigned NOT NULL auto_increment,
  nm_local varchar(100) NOT NULL default '',
  sg_local varchar(20) NOT NULL default '',
  te_observacao varchar(255) default NULL,
  PRIMARY KEY  (id_local),
  KEY sg_localizacao (sg_local)
) TYPE=InnoDB COMMENT='Localizações para regionalização de acesso a dados' AUTO_INCREMENT=6 ;

#
# Estrutura da tabela `log`
#

CREATE TABLE log (
  dt_acao datetime NOT NULL default '0000-00-00 00:00:00',
  cs_acao varchar(20) NOT NULL default '',
  nm_script varchar(255) NOT NULL default '',
  nm_tabela varchar(255) NOT NULL default '',
  id_usuario int(11) NOT NULL default '0',
  te_ip_origem varchar(15) NOT NULL default ''
) TYPE=InnoDB COMMENT='Log de Atividades no Sistema CACIC';


#
# Estrutura da tabela `officescan`
#

CREATE TABLE officescan (
  nu_versao_engine varchar(10) default NULL,
  nu_versao_pattern varchar(10) default NULL,
  dt_hr_instalacao datetime default NULL,
  dt_hr_coleta datetime default NULL,
  te_servidor varchar(30) default NULL,
  in_ativo char(1) default NULL,
  te_node_address varchar(17) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  PRIMARY KEY  (te_node_address,id_so)
) TYPE=InnoDB;


#
# Estrutura da tabela `patrimonio`
#

CREATE TABLE patrimonio (
  id_unid_organizacional_nivel1 int(11) default NULL,
  id_so int(11) NOT NULL default '0',
  dt_hr_alteracao datetime NOT NULL default '0000-00-00 00:00:00',
  te_node_address varchar(17) default NULL,
  id_unid_organizacional_nivel2 int(11) default NULL,
  te_localizacao_complementar varchar(100) default NULL,
  te_info_patrimonio1 varchar(20) default NULL,
  te_info_patrimonio2 varchar(20) default NULL,
  te_info_patrimonio3 varchar(20) default NULL,
  te_info_patrimonio4 varchar(20) default NULL,
  te_info_patrimonio5 varchar(20) default NULL,
  te_info_patrimonio6 varchar(20) default NULL,
  PRIMARY KEY  (dt_hr_alteracao),
  KEY te_node_address (te_node_address,id_so)
) TYPE=InnoDB;


#
# Estrutura da tabela `patrimonio_config_interface`
#

CREATE TABLE patrimonio_config_interface (
  id_local int(11) unsigned NOT NULL default '0',
  id_etiqueta varchar(30) NOT NULL default '',
  nm_etiqueta varchar(15) default NULL,
  te_etiqueta varchar(50) NOT NULL default '',
  in_exibir_etiqueta char(1) default NULL,
  te_help_etiqueta varchar(100) default NULL,
  te_plural_etiqueta varchar(50) default NULL,
  nm_campo_tab_patrimonio varchar(50) default NULL,
  in_destacar_duplicidade char(1) default 'N',
  PRIMARY KEY  (id_etiqueta,id_local),
  KEY id_localizacao (id_local)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `patrimonio_config_interface`
#

INSERT INTO patrimonio_config_interface VALUES (1, 'etiqueta1', 'Etiqueta 1', 'Entidade', '', 'Selecione a Entidade', 'Entidades', 'id_unid_organizacional_nivel1', 'N');

#
# Estrutura da tabela `perfis_aplicativos_monitorados`
#

CREATE TABLE perfis_aplicativos_monitorados (
  id_aplicativo int(11) NOT NULL auto_increment,
  nm_aplicativo varchar(100) NOT NULL default '',
  cs_car_inst_w9x char(2) default NULL,
  te_car_inst_w9x varchar(100) default NULL,
  cs_car_ver_w9x char(2) default NULL,
  te_car_ver_w9x varchar(100) default NULL,
  cs_car_inst_wnt char(2) default NULL,
  te_car_inst_wnt varchar(100) default NULL,
  cs_car_ver_wnt char(2) default NULL,
  te_car_ver_wnt varchar(100) default NULL,
  cs_ide_licenca char(2) default NULL,
  te_ide_licenca varchar(100) default NULL,
  dt_atualizacao datetime NOT NULL default '0000-00-00 00:00:00',
  te_arq_ver_eng_w9x varchar(100) default NULL,
  te_arq_ver_pat_w9x varchar(100) default NULL,
  te_arq_ver_eng_wnt varchar(100) default NULL,
  te_arq_ver_pat_wnt varchar(100) default NULL,
  te_dir_padrao_w9x varchar(100) default NULL,
  te_dir_padrao_wnt varchar(100) default NULL,
  id_so int(11) default '0',
  te_descritivo text,
  in_disponibiliza_info char(1) default 'N',
  in_disponibiliza_info_usuario_comum char(1) NOT NULL default 'N',
  dt_registro datetime default NULL,
  PRIMARY KEY  (id_aplicativo)
) TYPE=InnoDB AUTO_INCREMENT=78 ;

#
# Extraindo dados da tabela `perfis_aplicativos_monitorados`
#

INSERT INTO perfis_aplicativos_monitorados VALUES (8, 'Windows 2000', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:28:29', '', '', '', '', '', '', 7, '', 'S', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (16, 'Windows 98 SE', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 14:39:25', '', '', '', '', '', '', 4, '', 'S', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (20, 'Windows XP', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:29:29', '', '', '', '', '', '', 8, '', 'S', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (21, 'Windows 95', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:28:38', '', '', '', '', '', '', 1, '', 'S', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (22, 'Windows 95 OSR2', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:28:49', '', '', '', '', '', '', 2, '', 'S', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (23, 'Windows NT', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:29:18', '', '', '', '', '', '', 6, '', 'S', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (24, 'Microsoft Office 2000', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\Office\\9.0\\Registration\\ProductID\\(Padr?o)', '2006-04-11 10:28:15', '', '', '', '', '', '', 0, 0x5375c3ad7465207061726120657363726974c3b372696f20636f6d20456469746f7220646520546578746f732c20506c616e696c686120456c657472c3b46e6963612c2042616e636f206465204461646f732c206574632e, 'S', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (34, 'Plenus GateWay', '0', '', '3', 'c:\\gplenus\\tcp2lcw.ini/sock1/nome', '0', '', '3', 'c:\\gplenus\\tcp2lcw.ini/sock1/nome', '0', '', '2006-01-05 10:27:50', '', '', '', '', '', '', 0, '', 'S', 'S', '0000-00-00 00:00:00');
INSERT INTO perfis_aplicativos_monitorados VALUES (35, 'Plenus for Windows', '0', '', '3', 'c:\\wplenus\\plenus.trp/CV3/Nome', '0', '', '3', 'c:\\wplenus\\plenus.trp/CV3/Nome', '0', '', '2006-01-05 10:28:06', '', '', '', '', '', '', 0, '', 'S', 'S', '0000-00-00 00:00:00');
INSERT INTO perfis_aplicativos_monitorados VALUES (50, 'OpenOffice.org 1.1.3', '0', 'soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.3\\FriendlyAppName', '0', 'OpenOffice.org1.1.3\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.3\\FriendlyAppName', '0', '', '2006-03-03 11:37:56', '', '', '', '', '', '', 0, '', 'S', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (51, 'OpenOffice.org.br 1.1.3', '0', 'soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 1.1.3\\FriendlyAppName', '0', 'OpenOffice.org.br1.1.3\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 1.1.3\\FriendlyAppName', '0', '', '2006-03-03 11:38:21', '', '', '', '', '', '', 0, '', 'S', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (52, 'OpenOffice.org 1.1.0', '0', 'soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.0\\FriendlyAppName', '0', 'soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.0\\FriendlyAppName', '0', '', '2006-03-03 11:37:25', '', '', '', '', '', '', 0, '', 'S', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (53, 'OpenOffice.org 1.0.3', '0', 'soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.0.3\\FriendlyAppName', '0', 'soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.0.3\\FriendlyAppName', '0', '', '2006-03-03 11:37:12', '', '', '', '', '', '', 0, '', 'S', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (54, 'OpenOffice.org 1.1.1a', '0', 'soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.1a\\FriendlyAppName', '0', 'soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.1a\\FriendlyAppName', '0', '', '2006-03-03 11:37:37', '', '', '', '', '', '', 0, '', 'S', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (65, 'Plenus Estaï¿½o', '0', '', '3', 'c:\\wplenus\\plenus.trp/RPRINT/Nome', '0', '', '3', 'c:\\wplenus\\plenus.trp/RPRINT/Nome', '0', '', '2006-12-04 17:27:15', '', '', '', '', '', '', 0, 0x477261766120612063686176652064612045737461e7e36f20506c656e7573, 'S', 'N', '0000-00-00 00:00:00');
INSERT INTO perfis_aplicativos_monitorados VALUES (66, 'SART', '1', 'sart.exe', '1', 'sart.exe', '1', 'sart.exe', '1', 'sart.exe', '0', '', '2005-04-29 10:58:07', '', '', '', '', '', '', 0, '', 'N', 'S', '0000-00-00 00:00:00');
INSERT INTO perfis_aplicativos_monitorados VALUES (67, 'CACIC - Col_Anvi - Col. de Inf. de Anti-Vï¿½us', '0', '', '4', 'Cacic\\modulos\\col_anvi.exe', '0', '', '4', 'Cacic\\modulos\\col_anvi.exe', '0', '', '2006-12-04 17:27:56', '', '', '', '', '', '', 0, '', 'N', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (68, 'CACIC - Col_Moni - Col. de Inf. de Sistemas Monitorados', '0', '', '4', 'cacic\\modulos\\col_moni.exe', '0', '', '4', 'cacic\\modulos\\col_moni.exe', '0', '', '2006-01-19 19:34:12', '', '', '', '', '', '', 0, '', 'N', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (69, 'CACIC - Col_Patr - Col. de Inf. de Patrimônio e Loc. Fï¿½ica', '0', '', '4', 'cacic\\modulos\\col_patr.exe', '0', '', '4', 'cacic\\modulos\\col_patr.exe', '0', '', '2006-12-04 17:27:34', '', '', '', '', '', '', 0, '', 'N', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (70, 'CACIC - Col_Hard - Col. de Inf. de Hardware', '0', '', '4', 'cacic\\modulos\\col_hard.exe', '0', '', '4', 'cacic\\modulos\\col_hard.exe', '0', '', '2006-01-19 19:34:38', '', '', '', '', '', '', 0, '', 'N', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (71, 'CACIC - Col_Soft - Col. de Inf. de Softwares Bï¿½icos', '0', '', '4', 'cacic\\modulos\\col_soft.exe', '0', '', '4', 'cacic\\modulos\\col_soft.exe', '0', '', '2006-12-04 17:27:45', '', '', '', '', '', '', 0, '', 'N', 'N', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (72, 'CACIC - Col_Undi - Col. de Inf. de Unidades de Disco', '0', '', '4', 'cacic\\modulos\\col_undi.exe', '0', '', '4', 'cacic\\modulos\\col_undi.exe', '0', '', '2006-01-19 19:35:35', '', '', '', '', '', '', 0, '', 'N', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (73, 'CACIC - Col_Comp - Col. de Inf. de Compartilhamentos', '0', '', '4', 'cacic\\modulos\\col_comp.exe', '0', '', '4', 'cacic\\modulos\\col_comp.exe', '0', '', '2006-01-19 19:34:29', '', '', '', '', '', '', 0, '', 'N', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (74, 'CACIC - Ini_Cols - Inicializador de Coletas', '0', '', '4', 'cacic\\modulos\\ini_cols.exe', '0', '', '4', 'cacic\\modulos\\ini_cols.exe', '0', '', '2006-01-10 16:33:12', '', '', '', '', '', '', 0, '', 'N', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (75, 'CACIC - Agente Principal', '0', '', '4', 'Cacic\\cacic2.exe', '0', '', '4', 'Cacic\\cacic2.exe', '0', '', '2006-01-19 19:37:07', '', '', '', '', '', '', 0, '', 'S', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (76, 'CACIC - Gerente de Coletas', '0', '', '4', 'Cacic\\modulos\\ger_cols.exe', '0', '', '4', 'Cacic\\modulos\\ger_cols.exe', '0', '', '2006-01-19 19:37:46', '', '', '', '', '', '', 0, '', 'S', 'S', NULL);
INSERT INTO perfis_aplicativos_monitorados VALUES (77, 'OpenOffice.org 2.0', '0', 'Arquivos de programas\\OpenOffice.org 2.0\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 2.0\\FriendlyAppName', '0', 'Arquivos de programas\\OpenOffice.org 2.0\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 2.0\\FriendlyAppName', '0', '', '2006-03-03 11:38:11', '', '', '', '', '', '', 0, '', 'S', 'S', NULL);
# --------------------------------------------------------

#
# Estrutura da tabela `redes`
#

CREATE TABLE redes (
  id_local int(11) unsigned NOT NULL default '0',
  id_ip_rede varchar(15) NOT NULL default '',
  nm_rede varchar(100) default NULL,
  te_observacao varchar(100) default NULL,
  nm_pessoa_contato1 varchar(50) default NULL,
  nm_pessoa_contato2 varchar(50) default NULL,
  nu_telefone1 varchar(11) default NULL,
  te_email_contato2 varchar(50) default NULL,
  nu_telefone2 varchar(11) default NULL,
  te_email_contato1 varchar(50) default NULL,
  te_serv_cacic varchar(45) default NULL,
  te_serv_updates varchar(45) default NULL,
  te_path_serv_updates varchar(255) default NULL,
  nm_usuario_login_serv_updates varchar(20) default NULL,
  te_senha_login_serv_updates varchar(20) default NULL,
  nu_porta_serv_updates varchar(4) default NULL,
  te_mascara_rede varchar(15) default NULL,
  dt_verifica_updates date default NULL,
  nm_usuario_login_serv_updates_gerente varchar(20) default NULL,
  te_senha_login_serv_updates_gerente varchar(20) default NULL,
  nu_limite_ftp int(5) unsigned NOT NULL default '5',
  PRIMARY KEY  (id_ip_rede,id_local),
  KEY id_ip_rede (id_ip_rede)
) TYPE=InnoDB;


#
# Estrutura da tabela `redes_grupos_ftp`
#

CREATE TABLE redes_grupos_ftp (
  id_local int(11) NOT NULL default '0',
  id_ip_rede varchar(15) NOT NULL default '0',
  id_ip_estacao varchar(15) NOT NULL default '0',
  nu_hora_inicio int(12) NOT NULL default '0',
  nu_hora_fim varchar(12) NOT NULL default '0'
) TYPE=InnoDB;


#
# Estrutura da tabela `redes_versoes_modulos`
#

CREATE TABLE redes_versoes_modulos (
  id_local int(11) unsigned NOT NULL default '0',
  id_ip_rede varchar(15) NOT NULL default '',
  nm_modulo varchar(20) NOT NULL default '',
  te_versao_modulo varchar(20) default NULL,
  PRIMARY KEY  (id_ip_rede,nm_modulo)
) TYPE=InnoDB;


#
# Estrutura da tabela `so`
#

CREATE TABLE so (
  id_so int(11) NOT NULL default '0',
  te_desc_so varchar(50) default NULL,
  sg_so varchar(10) default NULL,
  PRIMARY KEY  (id_so)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `so`
#

INSERT INTO so VALUES (0, 'S.O. Desconhecido', 'Desc.');
INSERT INTO so VALUES (1, 'Windows 95', 'W95');
INSERT INTO so VALUES (2, 'Windows 95 OSR2', 'W95OSR2');
INSERT INTO so VALUES (3, 'Windows 98', 'W98');
INSERT INTO so VALUES (4, 'Windows 98 SE', 'W98SE');
INSERT INTO so VALUES (5, 'Windows ME', 'WME');
INSERT INTO so VALUES (6, 'Windows NT', 'WNT');
INSERT INTO so VALUES (7, 'Windows 2000', 'W2K');
INSERT INTO so VALUES (8, 'Windows XP', 'WXP');
INSERT INTO so VALUES (9, 'GNU/Linux', 'LNX');
INSERT INTO so VALUES (10, 'FreeBSD', 'FBSD');
INSERT INTO so VALUES (11, 'NetBSD', 'NBSD');
INSERT INTO so VALUES (12, 'OpenBSD', 'OBSD');
INSERT INTO so VALUES (13, 'Windows 2003', 'W2003');
# --------------------------------------------------------

#
# Estrutura da tabela `softwares`
#

CREATE TABLE softwares (
  id_software int(10) unsigned NOT NULL auto_increment,
  nm_software varchar(150) default NULL,
  te_descricao_software varchar(255) default NULL,
  qt_licenca int(11) default '0',
  nr_midia varchar(10) default NULL,
  te_local_midia varchar(30) default NULL,
  te_obs varchar(200) default NULL,
  PRIMARY KEY  (id_software)
) TYPE=InnoDB AUTO_INCREMENT=8 ;

#
# Extraindo dados da tabela `softwares`
#

INSERT INTO softwares VALUES (1, 'Teste', 'Software para testes', 20, '12', 'Armário 1', NULL);
INSERT INTO softwares VALUES (2, 'Teste1', 'Teste de cadastro de software', 2, '3', 'armário 1', '');
INSERT INTO softwares VALUES (5, 'teste2', 't2', 0, '', '', '');
INSERT INTO softwares VALUES (6, 'teste3', 't3', 0, '', '', '');
INSERT INTO softwares VALUES (7, 'Teste4', 't4', 0, '', '', '');
# --------------------------------------------------------

#
# Estrutura da tabela `softwares_estacao`
#

CREATE TABLE softwares_estacao (
  nr_patrimonio varchar(20) NOT NULL default '',
  id_software int(10) unsigned NOT NULL default '0',
  nm_computador varchar(50) default NULL,
  dt_autorizacao date default NULL,
  nr_processo varchar(11) default NULL,
  dt_expiracao_instalacao date default NULL,
  id_aquisicao_particular int(10) unsigned default NULL,
  dt_desinstalacao date default NULL,
  te_observacao varchar(90) default NULL,
  nr_patr_destino varchar(20) default NULL,
  PRIMARY KEY  (nr_patrimonio,id_software)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `softwares_estacao`
#

INSERT INTO softwares_estacao VALUES ('069525', 1, 'Computador de TESTE', NULL, '12345', NULL, NULL, NULL, NULL, NULL);
# --------------------------------------------------------

#
# Estrutura da tabela `softwares_inventariados`
#

CREATE TABLE softwares_inventariados (
  id_software_inventariado int(10) unsigned NOT NULL auto_increment,
  nm_software_inventariado varchar(100) NOT NULL default '',
  id_tipo_software int(11) default '0',
  id_software int(10) unsigned default NULL,
  PRIMARY KEY  (id_software_inventariado),
  KEY nm_software_inventariado (nm_software_inventariado),
  KEY id_software (id_software_inventariado),
  KEY idx_nm_software_inventariado (nm_software_inventariado)
) TYPE=InnoDB AUTO_INCREMENT=3795 ;


#
# Estrutura da tabela `softwares_inventariados_estacoes`
#

CREATE TABLE softwares_inventariados_estacoes (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(11) unsigned NOT NULL default '0',
  id_software_inventariado int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (te_node_address,id_so,id_software_inventariado),
  KEY id_software (id_software_inventariado)
) TYPE=InnoDB;


#
# Estrutura da tabela `testes`
#

CREATE TABLE testes (
  id_transacao int(10) unsigned NOT NULL auto_increment,
  te_linha text,
  id_ip_rede text,
  PRIMARY KEY  (id_transacao)
) TYPE=InnoDB PACK_KEYS=0 AUTO_INCREMENT=35080 ;


#
# Estrutura da tabela `tipos_licenca`
#

CREATE TABLE tipos_licenca (
  id_tipo_licenca int(10) unsigned NOT NULL auto_increment,
  te_tipo_licenca varchar(20) default NULL,
  PRIMARY KEY  (id_tipo_licenca)
) TYPE=InnoDB AUTO_INCREMENT=2 ;

#
# Extraindo dados da tabela `tipos_licenca`
#

INSERT INTO tipos_licenca VALUES (1, 'Tipo Licença Teste');
# --------------------------------------------------------

#
# Estrutura da tabela `tipos_software`
#

CREATE TABLE tipos_software (
  id_tipo_software int(10) unsigned NOT NULL default '0',
  te_descricao_tipo_software varchar(30) NOT NULL default '',
  PRIMARY KEY  (id_tipo_software)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `tipos_software`
#

INSERT INTO tipos_software VALUES (0, 'Versão Trial');
INSERT INTO tipos_software VALUES (1, 'Correção/Atualização');
INSERT INTO tipos_software VALUES (2, 'Sistema Interno');
INSERT INTO tipos_software VALUES (3, 'Software Livre');
INSERT INTO tipos_software VALUES (4, 'Software Licenciado');
INSERT INTO tipos_software VALUES (5, 'Software Suspeito');
INSERT INTO tipos_software VALUES (6, 'Software Descontinuado');
INSERT INTO tipos_software VALUES (7, 'Jogos e Similares');
# --------------------------------------------------------

#
# Estrutura da tabela `tipos_unidades_disco`
#

CREATE TABLE tipos_unidades_disco (
  id_tipo_unid_disco char(1) NOT NULL default '',
  te_tipo_unid_disco varchar(25) default NULL,
  PRIMARY KEY  (id_tipo_unid_disco)
) TYPE=InnoDB;

#
# Extraindo dados da tabela `tipos_unidades_disco`
#

INSERT INTO tipos_unidades_disco VALUES ('1', 'Removível');
INSERT INTO tipos_unidades_disco VALUES ('2', 'Disco Rígido');
INSERT INTO tipos_unidades_disco VALUES ('3', 'CD-ROM');
INSERT INTO tipos_unidades_disco VALUES ('4', 'Unid.Remota');
# --------------------------------------------------------

#
# Estrutura da tabela `unid_organizacional_nivel1`
#

CREATE TABLE unid_organizacional_nivel1 (
  id_unid_organizacional_nivel1 int(11) NOT NULL auto_increment,
  nm_unid_organizacional_nivel1 varchar(50) default NULL,
  te_endereco_uon1 varchar(80) default NULL,
  te_bairro_uon1 varchar(30) default NULL,
  te_cidade_uon1 varchar(50) default NULL,
  te_uf_uon1 char(2) default NULL,
  nm_responsavel_uon1 varchar(80) default NULL,
  te_email_responsavel_uon1 varchar(50) default NULL,
  nu_tel1_responsavel_uon1 varchar(10) default NULL,
  nu_tel2_responsavel_uon1 varchar(10) default NULL,
  PRIMARY KEY  (id_unid_organizacional_nivel1)
) TYPE=InnoDB AUTO_INCREMENT=31 ;

#
# Estrutura da tabela `unidades_disco`
#

CREATE TABLE unidades_disco (
  te_letra char(1) NOT NULL default '',
  id_so int(11) NOT NULL default '0',
  te_node_address varchar(17) NOT NULL default '',
  id_tipo_unid_disco char(1) default NULL,
  nu_serial varchar(12) default NULL,
  nu_capacidade int(10) unsigned default NULL,
  nu_espaco_livre int(10) unsigned default NULL,
  te_unc varchar(60) default NULL,
  cs_sist_arq varchar(10) default NULL,
  PRIMARY KEY  (te_letra,id_so,te_node_address)
) TYPE=InnoDB;


#
# Estrutura da tabela `usuarios`
#

CREATE TABLE usuarios (
  id_local int(11) unsigned NOT NULL default '0',
  id_usuario int(10) unsigned NOT NULL auto_increment,
  nm_usuario_acesso varchar(10) NOT NULL default '',
  nm_usuario_completo varchar(60) NOT NULL default '',
  te_senha varchar(60) NOT NULL default '',
  dt_log_in datetime NOT NULL default '0000-00-00 00:00:00',
  id_grupo_usuarios int(1) default NULL,
  te_emails_contato varchar(100) default NULL,
  te_telefones_contato varchar(100) default NULL,
  PRIMARY KEY  (id_usuario),
  KEY id_localizacao (id_local)
) TYPE=InnoDB AUTO_INCREMENT=30 ;

#
# Estrutura da tabela `variaveis_ambiente`
#

CREATE TABLE variaveis_ambiente (
  id_variavel_ambiente int(10) unsigned NOT NULL auto_increment,
  nm_variavel_ambiente varchar(100) NOT NULL default '',
  PRIMARY KEY  (id_variavel_ambiente)
) TYPE=InnoDB AUTO_INCREMENT=421 ;


# Estrutura da tabela `variaveis_ambiente_estacoes`
#

CREATE TABLE variaveis_ambiente_estacoes (
  te_node_address varchar(17) NOT NULL default '',
  id_so int(10) unsigned NOT NULL default '0',
  id_variavel_ambiente int(10) unsigned NOT NULL default '0',
  vl_variavel_ambiente varchar(100) NOT NULL default '',
  PRIMARY KEY  (te_node_address,id_so,id_variavel_ambiente)
) TYPE=InnoDB;


#
# Estrutura da tabela `versoes_softwares`
#

CREATE TABLE versoes_softwares (
  id_so int(11) NOT NULL default '0',
  te_node_address varchar(17) NOT NULL default '',
  te_versao_bde varchar(10) default NULL,
  te_versao_dao varchar(5) default NULL,
  te_versao_ado varchar(5) default NULL,
  te_versao_odbc varchar(15) default NULL,
  te_versao_directx int(10) unsigned default NULL,
  te_versao_acrobat_reader varchar(10) default NULL,
  te_versao_ie varchar(18) default NULL,
  te_versao_mozilla varchar(12) default NULL,
  te_versao_jre varchar(6) default NULL,
  PRIMARY KEY  (te_node_address,id_so)
) TYPE=InnoDB;


#
# Estrutura da tabela `unid_organizacional_nivel2`
#

CREATE TABLE unid_organizacional_nivel2 (
  id_local int(11) unsigned NOT NULL default '0',
  id_unid_organizacional_nivel2 int(11) NOT NULL auto_increment,
  id_unid_organizacional_nivel1 int(11) NOT NULL default '0',
  nm_unid_organizacional_nivel2 varchar(50) NOT NULL default '',
  te_endereco_uon2 varchar(80) default NULL,
  te_bairro_uon2 varchar(30) default NULL,
  te_cidade_uon2 varchar(50) default NULL,
  te_uf_uon2 char(2) default NULL,
  nm_responsavel_uon2 varchar(80) default NULL,
  te_email_responsavel_uon2 varchar(50) default NULL,
  nu_tel1_responsavel_uon2 varchar(10) default NULL,
  nu_tel2_responsavel_uon2 varchar(10) default NULL,
  dt_registro datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (id_unid_organizacional_nivel2,id_unid_organizacional_nivel1),
  KEY id_localizacao (id_local)
) TYPE=InnoDB AUTO_INCREMENT=77 ;
# --------------------------------------------------------

