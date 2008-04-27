-- --------------------------------------------------------
-- Dados basicos para o banco de dados CACIC 2.2.2
-- SGBD: MySQL-4.1.20
-- --------------------------------------------------------
--
-- Dumping data for table `acoes`
--

/*!40000 ALTER TABLE `acoes` DISABLE KEYS */;
INSERT INTO `acoes` (`id_acao`, `te_descricao_breve`, `te_descricao`, `te_nome_curto_modulo`, `dt_hr_alteracao`, `cs_situacao`) VALUES 
('cs_auto_update', 'Auto Atualização dos Agentes', 'Essa ação permite que seja realizada a auto atualização dos agentes do CACIC nos computadores onde os agentes são executados. \r\n\r\n', NULL, '0000-00-00 00:00:00', NULL),
('cs_coleta_compart', 'Coleta Informações de Compartilhamentos de Diretórios e Impressoras', 'Essa ação permite que sejam coletadas informações sobre compartilhamentos de diretórios e impressoras dos computadores onde os agentes estão instalados.', 'COMP', '0000-00-00 00:00:00', NULL),
('cs_coleta_hardware', 'Coleta Informações de Hardware', 'Essa ação permite que sejam coletadas diversas informações sobre o hardware dos computadores onde os agentes estão instalados, tais como Memória, Placa de Ví­deo, CPU, Discos Rí­gidos, BIOS, Placa de Rede, Placa Mãe, etc.', 'HARD', '0000-00-00 00:00:00', NULL),
('cs_coleta_monitorado', 'Coleta Informações sobre os Sistemas Monitorados', 'Essa ação permite que sejam coletadas, nas estações onde os agentes Cacic estão instalados, as informações acerca dos perfi­s de sistemas, previamente cadastrados pela Administração Central.', 'MONI', '0000-00-00 00:00:00', NULL),
('cs_coleta_officescan', 'Coleta Informações do Antiví­rus OfficeScan', 'Essa ação permite que sejam coletadas informações sobre o antiví­rus OfficeScan nos computadores onde os agentes estão instalados. São coletadas informações como a versão do engine, versão do pattern, endeço do servidor, data da instalação, etc.', 'ANVI', '0000-00-00 00:00:00', NULL),
('cs_coleta_patrimonio', 'Coleta Informações de Patrimônio', 'Essa ação permite que sejam coletadas diversas informações sobre Patrimônio e Localização Fí­sica dos computadores onde os agentes estão instalados.', 'PATR', '0000-00-00 00:00:00', NULL),
('cs_coleta_software', 'Coleta Informações de Software', 'Essa ação permite que sejam coletadas informações sobre as versões de diversos softwares instalados nos computadores onde os agentes são executados. São coletadas, por exemplo, informações sobre as versões do Internet Explorer, Mozilla, DirectX, ADO, BDE, DAO, Java Runtime Environment, etc.', 'SOFT', '0000-00-00 00:00:00', NULL),
('cs_coleta_unid_disc', 'Coleta Informações sobre Unidades de Disco', 'Essa ação permite que sejam coletadas informações sobre as unidades de disco disponí­veis nos computadores onde os agentes são executados. São coletadas, por exemplo, informações sobre o sistema de arquivos das unidades, suas capacidades de armazenamento, ocupação, espaço livre, etc.', 'UNDI', '0000-00-00 00:00:00', NULL);
/*!40000 ALTER TABLE `acoes` ENABLE KEYS */;

--
-- Dumping data for table `configuracoes_locais`
--

/*!40000 ALTER TABLE `configuracoes_locais` DISABLE KEYS */;
INSERT INTO `configuracoes_locais` 
      (`id_local`, `te_notificar_mudanca_hardware`, `in_exibe_erros_criticos`, `in_exibe_bandeja`, `nu_exec_apos`,
       `dt_hr_alteracao_patrim_interface`, `dt_hr_alteracao_patrim_uon1`, `dt_hr_alteracao_patrim_uon1a`,
       `dt_hr_alteracao_patrim_uon2`, `dt_hr_coleta_forcada`, `te_notificar_mudanca_patrim`, `nm_organizacao`,
       `nu_intervalo_exec`, `nu_intervalo_renovacao_patrim`, `te_senha_adm_agente`, `te_serv_updates_padrao`,
       `te_serv_cacic_padrao`, `te_enderecos_mac_invalidos`, `te_janelas_excecao`, `te_nota_email_gerentes`,
       `cs_abre_janela_patr`, `id_default_body_bgcolor`, `te_exibe_graficos`)
     VALUES 
      (1, '', 'N', 'S', 10, '2007-12-19 12:20:55', '2007-07-25 18:59:54', '2008-01-16 19:22:53', '2008-01-16 20:02:53',
       '2004-07-25 14:19:39', '', 'Nome de Sua Empresa', 4, 0, 'xyz', '10.71.0.121', '10.71.0.121',
       '00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,\r\n00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08',
       'openoffice.org, microsoft word, photoshop, hod', '', 'N', '#EBEBEB', '');
/*!40000 ALTER TABLE `configuracoes_locais` ENABLE KEYS */;

--
-- Dumping data for table `configuracoes_padrao`
--


/*!40000 ALTER TABLE `configuracoes_padrao` DISABLE KEYS */;
INSERT INTO `configuracoes_padrao` 
            (`in_exibe_erros_criticos`, `in_exibe_bandeja`, `nu_exec_apos`, `nm_organizacao`, `nu_intervalo_exec`,
             `nu_intervalo_renovacao_patrim`, `te_senha_adm_agente`, `te_serv_updates_padrao`, `te_serv_cacic_padrao`,
             `te_enderecos_mac_invalidos`, `te_janelas_excecao`, `cs_abre_janela_patr`, `id_default_body_bgcolor`,
             `te_exibe_graficos`)
     VALUES 
            ('N', 'S', 10, 'Nome da Organização - Tabela Configurações Padrão', 4, 0, '5a584f8a61b65baf', '10.71.0.121',
             '10.71.0.121', '00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,\r\n00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08', 
             'openoffice.org, microsoft word, photoshop, hod, aor.exe, pc2003.exe, cpp.exe, sal.exe, sal.bat, girafa4.exe, dro.exe, plenus', 
             'N', '#EBEBEB', '[so][acessos][locais][acessos_locais]');
/*!40000 ALTER TABLE `configuracoes_padrao` ENABLE KEYS */;


--
-- Dumping data for table `descricao_hardware`
--


/*!40000 ALTER TABLE `descricao_hardware` DISABLE KEYS */;
INSERT INTO `descricao_hardware`
            (`nm_campo_tab_hardware`, `te_desc_hardware`, `te_locais_notificacao_ativada`)
     VALUES 
            (' te_cdrom_desc', 'CD-ROM', ','),
            ('qt_mem_ram', 'Memória RAM', ','),
            ('qt_placa_video_cores', 'Qtd. Cores Placa Ví­deo', ','),
            ('qt_placa_video_mem', 'Memória Placa Ví­deo', ',,1,'),
            ('te_bios_desc', 'Descrição da BIOS', ',19,,1,'),
            ('te_bios_fabricante', 'Fabricante da BIOS', ',,1,'),
            ('te_cpu_desc', 'CPU', ',18,19,'),
            ('te_cpu_fabricante', 'Fabricante da CPU', ',,1,'),
            ('te_cpu_serial', 'Serial da CPU', ','),
            ('te_mem_ram_desc', 'Descrição da RAM', ',19,,1,'),
            ('te_modem_desc', 'Modem', ','),
            ('te_mouse_desc', 'Mouse', ''),
            ('te_placa_mae_desc', 'Placa Mãe', ','),
            ('te_placa_mae_fabricante', 'Fabricante Placa Mãe', ',,1,'),
            ('te_placa_rede_desc', 'Placa de Rede', ','),
            ('te_placa_som_desc', 'Placa de Som', ''),
            ('te_placa_video_desc', 'Placa de Ví­deo', ','),
            ('te_placa_video_resolucao', 'Resolução Placa de Ví­deo', ''),
            ('te_teclado_desc', 'Teclado', '');
/*!40000 ALTER TABLE `descricao_hardware` ENABLE KEYS */;

--
-- Dumping data for table `descricoes_colunas_computadores`
--


/*!40000 ALTER TABLE `descricoes_colunas_computadores` DISABLE KEYS */;
INSERT INTO `descricoes_colunas_computadores`
            (`nm_campo`, `te_descricao_campo`, `cs_condicao_pesquisa`)
     VALUES 
            ('dt_hr_coleta_forcada_estacao', 'Quant. dias de última coleta forçada na estação', 'S'),
            ('dt_hr_inclusao', 'Quant. dias de inclusão do computador na base', 'S'),
            ('dt_hr_ult_acesso', 'Quant. dias do último acesso da estação ao gerente WEB', 'S'),
            ('id_ip_rede', 'Endereço IP da Subrede', 'S'),
            ('id_so', 'Código do sistema operacional da estação', 'S'),
            ('qt_mem_ram', 'Quant. memória RAM', 'S'),
            ('qt_placa_video_cores', 'Quant. cores da placa de ví­deo', 'S'),
            ('qt_placa_video_mem', 'Quant. memória da placa de ví­deo', 'S'),
            ('te_bios_data', 'Identificação da BIOS', 'S'),
            ('te_bios_desc', 'Descrição da BIOS', 'S'),
            ('te_bios_fabricante', 'Nome do fabricante da BIOS', 'S'),
            ('te_cdrom_desc', 'Unidade de Disco Ótico', 'S'),
            ('te_cpu_desc', 'CPU', 'S'),
            ('te_cpu_fabricante', 'Fabricante da CPU', 'S'),
            ('te_cpu_frequencia', 'Frequência da CPU', 'S'),
            ('te_cpu_serial', 'Número de série da CPU', 'S'),
            ('te_dns_primario', 'IP do DNS primário', 'S'),
            ('te_dns_secundario', 'IP do DNS secundário', 'S'),
            ('te_dominio_dns', 'Nome/IP do domí­nio DNS', 'S'),
            ('te_dominio_windows', 'Nome/IP do domí­nio Windows', 'S'),
            ('te_gateway', 'IP do gateway', 'S'),
            ('te_ip', 'IP da estação', 'S'),
            ('te_mascara', 'Máscara de Subrede', 'S'),
            ('te_mem_ram_desc', 'Descrição da memória RAM', 'S'),
            ('te_modem_desc', 'Descrição do modem', 'S'),
            ('te_mouse_desc', 'Descrição do mouse', 'S'),
            ('te_node_address', 'Endereço MAC da estação', 'S'),
            ('te_nomes_curtos_modulos', 'te_nomes_curtos_modulos', 'N'),
            ('te_nome_computador', 'Nome do computador', 'S'),
            ('te_nome_host', 'Nome do Host', 'S'),
            ('te_origem_mac', 'te_origem_mac', 'N'),
            ('te_placa_mae_desc', 'Placa-Mãe', 'S'),
            ('te_placa_mae_fabricante', 'Fabricante da placa-mÃ£e', 'S'),
            ('te_placa_rede_desc', 'Placa de Rede', 'S'),
            ('te_placa_som_desc', 'Placa de Som', 'S'),
            ('te_placa_video_desc', 'Placa de Ví­deo', 'S'),
            ('te_placa_video_resolucao', 'Resolução da placa de ví­deo', 'S'),
            ('te_serv_dhcp', 'IP do servidor DHCP', 'S'),
            ('te_so', 'Identificador Interno do S.O.', 'S'),
            ('te_teclado_desc', 'Descrição do teclado', 'S'),
            ('te_versao_cacic', 'Versão do Agente Principal do CACIC', 'S'),
            ('te_versao_gercols', 'Versão do Gerente de Coletas do CACIC', 'S'),
            ('te_wins_primario', 'IP do servidor WINS primário', 'S'),
            ('te_wins_secundario', 'IP do servidor WINS secundário', 'S'),
            ('te_workgroup', 'Nome do grupo de trabalho', 'S');
/*!40000 ALTER TABLE `descricoes_colunas_computadores` ENABLE KEYS */;

--
-- Dumping data for table `grupo_usuarios`
--


/*!40000 ALTER TABLE `grupo_usuarios` DISABLE KEYS */;
INSERT INTO `grupo_usuarios`
            (`id_grupo_usuarios`, `te_grupo_usuarios`, `te_menu_grupo`, `te_descricao_grupo`, `cs_nivel_administracao`,
             `nm_grupo_usuarios`)
     VALUES
            (1, 'Comum', 'menu_com.txt', 'Usuário limitado, sem acesso a informações confidenciais como Softwares Inventariados e Opções Administrativas como Forçar Coletas e Excluir Computadores. Poderá alterar sua própria senha.', 0, ''),
            (2, 'Administração', 'menu_adm.txt', 'Acesso irrestrito.', 1, ''),
            (5, 'Gestão Central', 'menu_adm.txt', 'Acesso de leitura em todas as opções.', 2, ''),
            (6, 'Supervisão', 'menu_sup.txt', 'Manutenção de tabelas e acesso a todas as informações referentes à Localização.', 3, ''),
            (7, 'Técnico', 'menu_tec.txt', 'Acesso técnico. Será permitido acessar configurações de rede e relatórios de Patrimônio e Hardware.', 0, '');
/*!40000 ALTER TABLE `grupo_usuarios` ENABLE KEYS */;

--
-- Dumping data for table `patrimonio_config_interface`
--


/*!40000 ALTER TABLE `patrimonio_config_interface` DISABLE KEYS */;
INSERT INTO `patrimonio_config_interface` 
            (`id_local`, `id_etiqueta`, `nm_etiqueta`, `te_etiqueta`, `in_exibir_etiqueta`, `te_help_etiqueta`,
             `te_plural_etiqueta`, `nm_campo_tab_patrimonio`, `in_destacar_duplicidade`)
     VALUES
            (1, 'etiqueta1', 'Etiqueta 1', 'Entidade', '', 'Selecione a Entidade', 'Entidades', 'id_unid_organizacional_nivel1', 'N'),
            (1, 'etiqueta1a', 'Etiqueta 1a', 'Linha de Negócio', 'S', 'Selecione a Linha de Negócio', 'Linhas de Negócio', 'id_unid_organizacional_nivel1a', 'N'),
            (1, 'etiqueta2', 'Etiqueta 2', 'Órgão', '', 'Selecione o Órgão', 'Órgãos', 'id_unid_organizacional_nivel2', ''),
            (1, 'etiqueta3', 'Etiqueta 3', 'Seção / Sala / Ramal', '', 'Informe a Seção onde está instalado o equipamento.', '', 'te_localizacao_complementar', ''),
            (1, 'etiqueta4', 'Etiqueta 4', 'PIB da CPU', 'S', 'Informe o número de PIB(tombamento) da CPU', '', 'te_info_patrimonio1', 'S'),
            (1, 'etiqueta5', 'Etiqueta 5', 'PIB do Monitor', 'S', 'Informe o número de PIB(tombamento) do Monitor', '', 'te_info_patrimonio2', 'S'),
            (1, 'etiqueta6', 'Etiqueta 6', 'PIB da Impressora', 'S', 'Caso haja uma Impressora conectada informe n?mero de PIB(tombamento)', '', 'te_info_patrimonio3', 'S'),
            (1, 'etiqueta7', 'Etiqueta 7', 'Nº Série CPU (Opcional)', 'S', 'Caso não disponha do nº de PIB, informe o Nº de Série da CPU', '', 'te_info_patrimonio4', 'S'),
            (1, 'etiqueta8', 'Etiqueta 8', 'Nº Série Monitor (Opcional)', 'S', 'Caso não disponha do nº de PIB, informe o Nº de Série do Monitor', '', 'te_info_patrimonio5', 'S'),
            (1, 'etiqueta9', 'Etiqueta 9', 'Nº Série Impres. (Opcional)', 'S', 'Caso não disponha do nº de PIB, informe o Nº de Série da Impressora', '', 'te_info_patrimonio6', 'S');
/*!40000 ALTER TABLE `patrimonio_config_interface` ENABLE KEYS */;

--
-- Dumping data for table `perfis_aplicativos_monitorados`
--


/*!40000 ALTER TABLE `perfis_aplicativos_monitorados` DISABLE KEYS */;
INSERT INTO `perfis_aplicativos_monitorados`
            (`id_aplicativo`, `nm_aplicativo`, `cs_car_inst_w9x`, `te_car_inst_w9x`, `cs_car_ver_w9x`, `te_car_ver_w9x`,
             `cs_car_inst_wnt`, `te_car_inst_wnt`, `cs_car_ver_wnt`, `te_car_ver_wnt`, `cs_ide_licenca`, `te_ide_licenca`,
             `dt_atualizacao`, `te_arq_ver_eng_w9x`, `te_arq_ver_pat_w9x`, `te_arq_ver_eng_wnt`, `te_arq_ver_pat_wnt`,
             `te_dir_padrao_w9x`, `te_dir_padrao_wnt`, `id_so`, `te_descritivo`, `in_disponibiliza_info`,
             `in_disponibiliza_info_usuario_comum`, `dt_registro`)
     VALUES
            (8, 'Windows 2000', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:28:29', '', '', '', '', '', '', 7, '', 'S', 'N', NULL),
            (16, 'Windows 98 SE', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 14:39:25', '', '', '', '', '', '', 4, '', 'S', 'N', NULL),
            (20, 'Windows XP', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:29:29', '', '', '', '', '', '', 8, '', 'S', 'N', NULL),
            (21, 'Windows 95', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:28:38', '', '', '', '', '', '', 1, '', 'S', 'N', NULL),
            (22, 'Windows 95 OSR2', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:28:49', '', '', '', '', '', '', 2, '', 'S', 'N', NULL),
            (23, 'Windows NT', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\Software\\Microsoft\\Windows\\CurrentVersion\\ProductId', '2006-04-11 10:29:18', '', '', '', '', '', '', 6, '', 'S', 'N', NULL),
            (24, 'Microsoft Office 2000', '0', '', '0', '', '0', '', '0', '', '1', 'HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\Office\\9.0\\Registration\\ProductID\\(Padr?o)', '2006-04-11 10:28:15', '', '', '', '', '', '', 0, 'SuÃ­te para escritÃ³rio com Editor de Textos, Planilha EletrÃ´nica, Banco de Dados, etc.', 'S', 'N', NULL),
            (34, 'Plenus GateWay', '0', '', '3', 'c:\\gplenus\\tcp2lcw.ini/sock1/nome', '0', '', '3', 'c:\\gplenus\\tcp2lcw.ini/sock1/nome', '0', '', '2006-01-05 10:27:50', '', '', '', '', '', '', 0, '', 'S', 'S', '0000-00-00 00:00:00'),
            (35, 'Plenus for Windows', '0', '', '3', 'c:\\wplenus\\plenus.trp/CV3/Nome', '0', '', '3', 'c:\\wplenus\\plenus.trp/CV3/Nome', '0', '', '2006-01-05 10:28:06', '', '', '', '', '', '', 0, '', 'S', 'S', '0000-00-00 00:00:00'),
            (50, 'OpenOffice.org 1.1.3#DESATIVADO#', '1', 'OpenOffice.org1.1.3\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.3\\FriendlyAppName', '0', 'OpenOffice.org1.1.3\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.3\\FriendlyAppName', '0', '', '2007-10-30 17:30:27', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (51, 'OpenOffice.org.br 1.1.3#DESATIVADO#', '1', 'OpenOffice.org.br1.1.3\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 1.1.3\\FriendlyAppName', '0', 'OpenOffice.org.br1.1.3\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 1.1.3\\FriendlyAppName', '0', '', '2007-10-30 17:31:02', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (52, 'OpenOffice.org 1.1.0#DESATIVADO#', '1', 'OpenOffice.org1.1.0\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.0\\FriendlyAppName', '1', 'OpenOffice.org1.1.0\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.0\\FriendlyAppName', '0', '', '2007-10-30 17:30:00', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (53, 'OpenOffice.org 1.0.3#DESATIVADO#', '1', 'OpenOffice.org1.0.3\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.0.3\\FriendlyAppName', '1', 'OpenOffice.org1.0.3\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.0.3\\FriendlyAppName', '0', '', '2007-10-30 17:29:47', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (54, 'OpenOffice.org 1.1.1a#DESATIVADO#', '1', 'OpenOffice.org1.1.1a\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.1a\\FriendlyAppName', '1', 'OpenOffice.org1.1.1a\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org 1.1.1a\\FriendlyAppName', '0', '', '2007-10-30 17:30:17', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (65, 'Plenus Estação', '0', '', '3', 'c:\\wplenus\\plenus.trp/RPRINT/Nome', '0', '', '3', 'c:\\wplenus\\plenus.trp/RPRINT/Nome', '0', '', '2006-01-05 10:28:22', '', '', '', '', '', '', 0, 'Grava a chave da Estação Plenus', 'S', 'S', '0000-00-00 00:00:00'),
            (66, 'SART#DESATIVADO#', '1', 'sart.exe', '1', 'sart.exe', '1', 'sart.exe', '1', 'sart.exe', '0', '', '2007-05-11 12:25:18', '', '', '', '', '', '', 0, '', 'N', 'N', '0000-00-00 00:00:00'),
            (68, 'CACIC - Col_Moni - Col. de Inf. de Sistemas Monitorados', '0', '', '4', 'cacic\\modulos\\col_moni.exe', '0', '', '4', 'cacic\\modulos\\col_moni.exe', '0', '', '2007-07-27 17:59:27', '', '', '', '', '', '', 0, '', 'N', 'N', NULL),
            (69, 'CACIC - Col_Patr - Col. de Inf. de Patrimônio e Loc. Fí­sica', '0', '', '4', 'cacic\\modulos\\col_patr.exe', '0', '', '4', 'cacic\\modulos\\col_patr.exe', '0', '', '2007-07-27 17:59:39', '', '', '', '', '', '', 0, '', 'N', 'N', NULL),
            (70, 'CACIC - Col_Hard - Col. de Inf. de Hardware', '0', '', '4', 'cacic\\modulos\\col_hard.exe', '0', '', '4', 'cacic\\modulos\\col_hard.exe', '0', '', '2007-07-27 17:59:19', '', '', '', '', '', '', 0, '', 'N', 'N', NULL),
            (71, 'CACIC - Col_Soft - Col. de Inf. de Softwares Básicos', '0', '', '4', 'cacic\\modulos\\col_soft.exe', '0', '', '4', 'cacic\\modulos\\col_soft.exe', '0', '', '2007-07-27 17:59:48', '', '', '', '', '', '', 0, '', 'N', 'N', NULL),
            (72, 'CACIC - Col_Undi - Col. de Inf. de Unidades de Disco', '0', '', '4', 'cacic\\modulos\\col_undi.exe', '0', '', '4', 'cacic\\modulos\\col_undi.exe', '0', '', '2007-07-27 17:59:56', '', '', '', '', '', '', 0, '', 'N', 'N', NULL),
            (73, 'CACIC - Col_Comp - Col. de Inf. de Compartilhamentos', '0', '', '4', 'cacic\\modulos\\col_comp.exe', '0', '', '4', 'cacic\\modulos\\col_comp.exe', '0', '', '2007-07-27 19:06:39', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (74, 'CACIC - Ini_Cols - Inicializador de Coletas', '0', '', '4', 'cacic\\modulos\\ini_cols.exe', '0', '', '4', 'cacic\\modulos\\ini_cols.exe', '0', '', '2007-07-27 18:00:09', '', '', '', '', '', '', 0, '', 'N', 'N', NULL),
            (75, 'CACIC - Agente Principal', '0', '', '4', 'Cacic\\cacic2.exe', '0', '', '4', 'Cacic\\cacic2.exe', '0', '', '2007-07-27 18:52:32', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (76, 'CACIC - Gerente de Coletas', '0', '', '4', 'Cacic\\modulos\\ger_cols.exe', '0', '', '4', 'Cacic\\modulos\\ger_cols.exe', '0', '', '2007-08-13 15:44:14', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (77, 'OpenOffice.org 2.0#DESATIVADO#', '0', 'Arquivos de programas\\OpenOffice.org 2.0\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 2.0\\FriendlyAppName', '0', 'Arquivos de programas\\OpenOffice.org 2.0\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\OpenOffice.org.br 2.0\\FriendlyAppName', '0', '', '2007-10-30 17:30:37', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (78, 'Oracle Client 7', '3', 'HKEY_LOCAL_MACHINE\\SOFTWARE\\ORACLE\\OTRACE73', '0', '', '3', 'HKEY_LOCAL_MACHINE\\SOFTWARE\\ORACLE\\OTRACE73', '0', '', '0', '', '2007-09-20 19:48:09', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (79, 'BrOffice.org 2.0#DESATIVADO#', '1', 'BrOffice.org 2.0\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\BrOffice.org 2.0\\FriendlyAppName', '1', 'BrOffice.org 2.0\\program\\soffice.exe', '2', 'HKEY_CLASSES_ROOT\\applications\\BrOffice.org 2.0\\FriendlyAppName', '0', '', '2007-10-30 17:29:30', '', '', '', '', '', '', 0, '', 'S', 'N', NULL),
            (80, 'Microsoft Access 2000', '3', 'HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\Office\\9.0\\Access\\InstallRoot\\Path', '0', '', '3', 'HKEY_LOCAL_MACHINE\\SOFTWARE\\Microsoft\\Office\\9.0\\Access\\InstallRoot\\Path', '0', '', '0', '', '2008-01-22 11:51:17', '', '', '', '', '', '', 0, '', 'S', 'N', NULL);
/*!40000 ALTER TABLE `perfis_aplicativos_monitorados` ENABLE KEYS */;

--
-- Dumping data for table `so`
--


/*!40000 ALTER TABLE `so` DISABLE KEYS */;
INSERT INTO `so`
            (`id_so`, `te_desc_so`, `sg_so`, `te_so`)
     VALUES
            (0, 'S.O. Desconhecido', 'Desc.', ''),
            (1, 'Windows 95', 'W95', '1.4.0'),
            (2, 'Windows 95 OSR2', 'W95OSR2', ''),
            (3, 'Windows 98', 'W98', '1.4.10'),
            (4, 'Windows 98 SE', 'W98SE', '1.4.10.A'),
            (5, 'Windows ME', 'WME', ''),
            (6, 'Windows NT', 'WNT', ''),
            (7, 'Windows 2000', 'W2K', '2.5.0.Service Pack 4'),
            (8, 'Windows XP', 'WXP', '2.5.1.Service Pack 2'),
            (9, 'GNU/Linux', 'LNX', ''),
            (10, 'FreeBSD', 'FBSD', ''),
            (11, 'NetBSD', 'NBSD', ''),
            (12, 'OpenBSD', 'OBSD', ''),
            (13, 'Windows 2003', 'W2003', ''),
            (14, 'Windows VISTA', 'VISTA', '2.6.0');
/*!40000 ALTER TABLE `so` ENABLE KEYS */;

--
-- Dumping data for table `tipos_software`
--


/*!40000 ALTER TABLE `tipos_software` DISABLE KEYS */;
INSERT INTO `tipos_software`
            (`id_tipo_software`, `te_descricao_tipo_software`)
     VALUES
            (0, 'Versão Trial'),
            (1, 'Correção/Atualização'),
            (2, 'Sistema Interno'),
            (3, 'Software Livre'),
            (4, 'Software Licenciado'),
            (5, 'Software Suspeito'),
            (6, 'Software Descontinuado'),
            (7, 'Jogos e Similares');
/*!40000 ALTER TABLE `tipos_software` ENABLE KEYS */;

--
-- Dumping data for table `tipos_unidades_disco`
--


/*!40000 ALTER TABLE `tipos_unidades_disco` DISABLE KEYS */;
INSERT INTO `tipos_unidades_disco`
            (`id_tipo_unid_disco`, `te_tipo_unid_disco`)
     VALUES
            ('1', 'Removí­vel'),
            ('2', 'Disco Rí­gido'),
            ('3', 'CD-ROM'),
            ('4', 'Unid.Remota');
/*!40000 ALTER TABLE `tipos_unidades_disco` ENABLE KEYS */;
