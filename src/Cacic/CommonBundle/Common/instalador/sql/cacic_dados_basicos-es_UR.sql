INSERT INTO `acoes` (`id_acao`, `te_descricao_breve`, `te_descricao`, `te_nome_curto_modulo`, `dt_hr_alteracao`, `cs_situacao`) VALUES 
('cs_auto_update','Auto Actualizacion de los Agentes','Esta accion permite que sea realizada una auto actualizacion de los agentes de CACIC en las computadoras donde los agentes son ejecutados. \r\n\r\n',NULL,'0000-00-00 00:00:00',NULL),
('cs_coleta_compart','Recolecta Informaciones de Directorios e impresoras compartidas','Esta accion permite que sean recolectadas informaciones sobre directorios e impresoras compartidas de las computadoras donde los agentes estan instalados.','COMP','0000-00-00 00:00:00',NULL),
('cs_coleta_hardware','Recolecta Informaciones de Hardware','Esta accion permite que sean recolectadas diversas informaciones sobre el hardware de las computadoras donde los agentes estan instalados, tales como Memoria, Tarjeta de Vídeo, CPU, Discos Duros, BIOS, Tarjeta de Red, Placa Madre, etc.','HARD','0000-00-00 00:00:00',NULL),
('cs_coleta_monitorado','Recolecta Informaciones sobre los Sistemas Monitoreados','Esta accion permite que sean recolectadas, en las estaciones donde los agentes Cacic estan instalados, las informaciones acerca de los perfiles de los sistemas, previamente registrados por la Administracion Central.','MONI','0000-00-00 00:00:00',NULL),
('cs_coleta_officescan','Recolecta Informaciones del Antivírus OfficeScan','Esta accion permite que sean recolectadas informaciones sobre el anti-vírus OfficeScan en las computadoras donde los agentes estan instalados. Son recolectadas informaciones como la version del motor, version del pattern, version del servidor, fecha de instalacion, etc.','ANVI','0000-00-00 00:00:00',NULL),
('cs_coleta_software','Recoleta Informaciones de Software','Esta accion permite que sean recolectadas informaciones sobre la version de diversos softwares instalados en las computadoras donde los agentes son ejecutados. Son recolectadas, por ejemplo, informaciones sobre las versiones del Internet Explorer, Mozilla, DirectX, ADO, BDE, DAO, Java Runtime Environment, etc.','SOFT','0000-00-00 00:00:00',NULL),
('cs_coleta_unid_disc','Recolecta Informaciones sobre Unidades de Disco','Esta accion permite que sean recolectadas informaciones sobre las unidades de disco disponibles en las computadoras donde los agentes son ejecutados. Son recolectadas, por ejemplo, informaciones sobre el sistema de archivos das unidades, las capacidades de almacenamiento, ocupacion, espacio libre, etc.','UNDI','0000-00-00 00:00:00',NULL);


--
-- Extraindo dados da tabela `configuracoes_padrao`
--

INSERT INTO `configuracoes_padrao` (`in_exibe_erros_criticos`, `in_exibe_bandeja`, `nu_exec_apos`, `nu_rel_maxlinhas`, `nm_organizacao`, `nu_timeout_srcacic`, `nu_intervalo_exec`, `nu_intervalo_renovacao_patrim`, `te_senha_adm_agente`, `te_serv_updates_padrao`, `te_serv_cacic_padrao`, `te_enderecos_mac_invalidos`, `te_janelas_excecao`, `cs_abre_janela_patr`, `id_default_body_bgcolor`, `te_exibe_graficos`, `nu_porta_srcacic`, `nu_resolucao_grafico_h`, `nu_resolucao_grafico_w`) VALUES
('N', 'S', 10, 50, 'Nome Extenso da Organização', 30, 4, 0, '5a584f8a61b65baf', 'UXRJO117', 'www-cacic3', '00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,\r\n00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08', 'openoffice.org, microsoft word, photoshop, hod, aor.exe, pc2003.exe, cpp.exe, sal.exe, sal.bat, girafa4.exe, dro.exe, plenus', 'N', '#EBEBEB', '[so][acessos][acessos_locais][locais]', '5900', 0, 0);

INSERT INTO `descricao_hardware`
            (`nm_campo_tab_hardware`, `te_desc_hardware`, `te_locais_notificacao_ativada`)
     VALUES 
            ('te_cdrom_desc','CD-ROM','1'),
            ('qt_mem_ram','Memoria RAM','1'),
            ('qt_placa_video_cores','Qtd. Placa Video','0'),
            ('qt_placa_video_mem','Memoria Placa Video','0'),
            ('te_bios_desc','Descripcion de la BIOS','1'),
            ('te_bios_fabricante','Fabricante de la BIOS','0'),
            ('te_cpu_desc','CPU','0'),
            ('te_cpu_fabricante','Fabricante de la CPU','0'),
            ('te_cpu_serial','Serial de la CPU','1'),
            ('te_mem_ram_desc','Descripcion de la RAM','0'),
            ('te_modem_desc','Modem','0'),
            ('te_mouse_desc','Mouse','1'),
            ('te_placa_mae_desc','Placa Madre','1'),
            ('te_placa_mae_fabricante','Fabricante Placa Madre','0'),
            ('te_placa_rede_desc','Tarjeta de red','1'),
            ('te_placa_som_desc','Tarjeta de sonido','1'),
            ('te_placa_video_desc','Tarjeta de Video','1'),
            ('te_placa_video_resolucao','Resolucion Tarjeta de Video','0'),
            ('te_teclado_desc','Teclado','1');

INSERT INTO `descricoes_colunas_computadores`
            (`nm_campo`, `te_descricao_campo`, `cs_condicao_pesquisa`)
     VALUES 
            ('dt_hr_coleta_forcada_estacao', 'Cant. dias de la última recoleccion forzada en la estacion', 'S'),
            ('dt_hr_inclusao', 'Cant. dias de inclusion del computador en la base', 'S'),
            ('dt_hr_ult_acesso', 'Cant. dias del último acceso de la estacion al gerente WEB', 'S'),
            ('id_ip_rede', 'Direccion IP de la Subred', 'S'),
            ('id_so', 'Código del sistema operacional de la estacion', 'S'),
            ('qt_mem_ram', 'Cant. memoria RAM', 'S'),
            ('qt_placa_video_cores', 'Cant. colores de la tarjeta de video', 'S'),
            ('qt_placa_video_mem', 'Cant. memoria de la tarjeta de video', 'S'),
            ('te_bios_data', 'Identificacion de la BIOS', 'S'),
            ('te_bios_desc', 'Descripcion de la BIOS', 'S'),
            ('te_bios_fabricante', 'Nombre del fabricante de la BIOS', 'S'),
            ('te_cdrom_desc', 'Descripcion de la unidad de CD-ROM', 'S'),
            ('te_cpu_desc', 'Descripcion de la CPU', 'S'),
            ('te_cpu_fabricante', 'Fabricante de la CPU', 'S'),
            ('te_cpu_freq', 'Frecuencia de la CPU', 'S'),
            ('te_cpu_serial', 'Número de série de la CPU', 'S'),
            ('te_dns_primario', 'IP del DNS primário', 'S'),
            ('te_dns_secundario', 'IP del DNS secundário', 'S'),
            ('te_dominio_dns', 'Nombre/IP del domínio DNS', 'S'),
            ('te_dominio_windows', 'Nombre/IP del domínio Windows', 'S'),
            ('te_gateway', 'IP del gateway', 'S'),
            ('te_ip', 'IP de la estacion', 'S'),
            ('te_mascara', 'Máscara de Subred', 'S'),
            ('te_mem_ram_desc', 'Descripcion de la memoria RAM', 'S'),
            ('te_modem_desc', 'Descripcion del modem', 'S'),
            ('te_mouse_desc', 'Descripcion del mouse', 'S'),
            ('te_node_address', 'Direccion MAC de la estacion', 'S'),
            ('te_nomes_curtos_modulos', 'te_nomes_curtos_modulos', 'N'),
            ('te_nome_computador', 'Nombre de la computadora', 'S'),
            ('te_nome_host', 'Nombre de Host', 'S'),
            ('te_origem_mac', 'te_origem_mac', 'N'),
            ('te_placa_mae_desc', 'Descripcion de la placa-m', 'S'),
            ('te_placa_mae_fabricante', 'Fabricante de la placa-m', 'S'),
            ('te_placa_rede_desc', 'Descripcion de la placa de red', 'S'),
            ('te_placa_som_desc', 'Descripcion de la tarjeta de sonido', 'S'),
            ('te_placa_video_desc', 'Descripcion de la tarjeta de video', 'S'),
            ('te_placa_video_resolucao', 'Resolucion de la tarjeta de video', 'S'),
            ('te_serv_dhcp', 'IP del servidor DHCP', 'S'),
            ('te_teclado_desc', 'Descripcion del teclado', 'S'),
            ('te_versao_cacic', 'Version del Agente Principal de CACIC', 'S'),
            ('te_versao_gercols', 'Version del Gerente de Recoleccion de CACIC', 'S'),
            ('te_wins_primario', 'IP del servidor WINS primario', 'S'),
            ('te_wins_secundario', 'IP del servidor WINS secundario', 'S'),
            ('te_workgroup', 'Nombre del grupo de trabajo', 'S');

INSERT INTO `grupo_usuarios`
            (`id_grupo_usuarios`, `te_grupo_usuarios`, `te_menu_grupo`, `te_descricao_grupo`, `cs_nivel_administracao`,
             `nm_grupo_usuarios`)
     VALUES
            (1,'Comun','menu_com.txt','Usuario limitado, sin acceso a informaciones confidenciales como Softwares Inventariados y Opciones Administrativas como Forzar Recoleccion y Excluir Computador. Podra alterar su propia clave.',0,''),
            (2,'Administracion','menu_adm.txt','Acceso irrestricto.',1,''),
            (5,'Gestion Central','menu_adm.txt','Acceso de lectura en todas las opciones.',2,''),
            (6,'Supervisor','menu_sup.txt','Mantenimiento de las tablas y acceso a todas las informaciones referentes a la Localizacion.',3,''),
            (7,'Técnico','menu_tec.txt','Acceso técnico. Será permitido acceder configuraciones de red y reportes de Patrimônio y Hardware.',0,'');

INSERT INTO `patrimonio_config_interface` 
            (`id_local`, `id_etiqueta`, `nm_etiqueta`, `te_etiqueta`, `in_exibir_etiqueta`, `te_help_etiqueta`,
             `te_plural_etiqueta`, `nm_campo_tab_patrimonio`, `in_destacar_duplicidade`,`in_obrigatorio`)
     VALUES
            (1,'etiqueta1','Etiqueta 1','Entidad','','Seleccione una Entidad','Entidades','id_unid_organizacional_nivel1','N', 'N'),
            (1, 'etiqueta1a', 'Etiqueta 1a', 'Linha de Negócio', 'S', 'Selecione a Linha de Negócio', 'Linhas de Negócio', 'id_unid_organizacional_nivel1a', 'N', 'N'),
            (1,'etiqueta2','Etiqueta 2','Organo','','Seleccione un organo','organos','id_unid_organizacional_nivel2','N', 'N'),
            (1,'etiqueta3','Etiqueta 3','Seccion','','Informe una seccion donde está instalado el equipamiento.','Secciones','te_localizacao_complementar','N', 'N'),
            (1,'etiqueta4','Etiqueta 4','PIB de la CPU','S','Informe el número de PIB de la CPU','','te_info_patrimonio1','S', 'N'),
            (1,'etiqueta5','Etiqueta 5','PIB del Monitor','S','Informe el número de PIB del Monitor','','te_info_patrimonio2','S', 'N'),
            (1,'etiqueta6','Etiqueta 6','PIB de la Impresora','S','En Caso si hay una Impresora conectada informe número de PIB','','te_info_patrimonio3','S', 'N'),
            (1,'etiqueta7','Etiqueta 7','Nro. Serie CPU','S','En Caso no disponga del nro. de PIB, informe el Nro. de Série de la CPU','','te_info_patrimonio4','S', 'N'),
            (1,'etiqueta8','Etiqueta 8','Nr. série Monitor','S','En Caso no disponga del nro. de PIB, informe el Nro. de Série del Monitor','','te_info_patrimonio5','S', 'N'),
            (1,'etiqueta9','Etiqueta 9','Nro. Série Impres. (Opcional)','S','En Caso haya una impresora conectada al micro y no disponga del nro. de PIB, informe su nro. de série','','te_info_patrimonio6','S', 'N');

INSERT INTO `so` (`id_so`, `te_desc_so`, `sg_so`, `te_so`, `in_mswindows`) VALUES
(20, 'Windows XP Professional', 'WXP_PRO', '2.5.1.1.256', 'S'),
(21, 'Windows 95 SP1', 'W95_SP1', '1.4.0.B', 'S'),
(22, 'Ubuntu 9.04 Jaunty Jackalope', 'Ubuntu-9.04', 'Ubuntu - 9.04', 'N'),
(27, 'Debian 5.0.1 Lenny', 'Debian_501', 'Debian - 5.0.1', 'N'),
(28, 'Windows 7 Professional', 'W7-PRO', '2.6.1.1.256', 'S'),
(29, 'Ubuntu 8.10 Intrepid Ibex', 'Ubuntu-8.10', 'Ubuntu - 8.10', 'N'),
(31, 'Windows Vista', 'WVista', '2.6.0.1.256', 'S'),
(32, 'Windows 2000 Professional', 'W2K_PRO', '2.5.0.1.0', 'S'),
(33, 'Debian 5.0.3 Lenny', 'Debian_503', 'Debian - 5.0.3', 'N'),
(34, 'Ubuntu 9.10 Karmic Koala', 'Ubuntu_910', 'Ubuntu - 9.10', 'N'),
(35, 'Windows 2003 Server', 'W2K3', '2.5.2.2.274', 'S'),
(36, 'Windows XP Home Edition', 'WXP_HE', '2.5.1.1.0', 'S'),
(37, 'Windows 2000 (SP4)', 'W2K-SP4', '2.5.0.Service Pack 4', 'S'),
(38, 'Ubuntu 10.04 Lucid', 'Ubuntu-10.04', 'Ubuntu - 10.04', 'N'),
(39, 'Windows 7 Home Basic', 'W7-HB', '2.6.1', 'S'),
(40, 'Debian 5.0.5 Lenny', 'Debian_505', 'Debian - 5.0.5', 'N'),
(41, 'Windows XP SP3', 'WXP_SP3', '2.5.1.Service Pack 3', 'S'),
(42, 'Windows 7 Enterprise', 'W7-ENT', '2.6.1.1.768', 'S'),
(43, 'Windows 7 (64Bits - Enterprise)', 'W7-EN64', '2.6.1.1.256.64', 'S'),
(44, 'Windows 7 (64Bits - Home Premium)', 'W7-HP64', '2.6.1.1.768.64', 'S'),
(45, 'Windows 7 (32Bits - Enterprise)', 'W7-EN32', '2.6.1.1.256.32', 'S'),
(46, 'Windows XP Professional SP3', 'WXP-PRO-SP3', '2.5.1.1.256.32', 'S');

INSERT INTO `tipos_software`
            (`te_descricao_tipo_software`)
     VALUES
            ('Version de Disponibilidad (Trial)'),
            ('Correccion/Actualizacion'),
            ('Sistema Interno'),
            ('Software Libre'),
            ('Software Licenciado'),
            ('Software Sospechoso'),
            ('Software Discontinuado'),
            ('Juegos y Similares');

INSERT INTO `tipos_unidades_disco`
            (`id_tipo_unid_disco`, `te_tipo_unid_disco`)
     VALUES
            ('1', 'Removible'),
            ('2', 'Disco Rí­gido'),
            ('3', 'CD-ROM'),
            ('4', 'Unid.Remota');
