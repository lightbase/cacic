-- phpMyAdmin SQL Dump
-- version 3.4.4-rc1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 26/10/2012 às 01h37min
-- Versão do Servidor: 5.0.51
-- Versão do PHP: 5.2.6-1+lenny16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "-03:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `cacic3`
--

--
-- Extraindo dados da tabela `acoes`
--

INSERT INTO `acoes` (`id_acao`, `te_descricao_breve`, `te_descricao`, `te_nome_curto_modulo`, `dt_hr_alteracao`, `cs_situacao`) VALUES
('cs_auto_update', 'Auto Atualização dos Agentes', 'Essa ação permite que seja realizada a auto atualização dos agentes do CACIC nos computadores onde os agentes são executados. \r\n\r\n', NULL, '0000-00-00 00:00:00', NULL),
('cs_coleta_compart', 'Coleta Informações de Compartilhamentos de Diretórios e Impressoras', 'Essa ação permite que sejam coletadas informações sobre compartilhamentos de diretórios e impressoras dos computadores onde os agentes estão instalados.', 'COMP', '0000-00-00 00:00:00', NULL),
('cs_coleta_hardware', 'Coleta Informações de Hardware', 'Essa ação permite que sejam coletadas diversas informações sobre o hardware dos computadores onde os agentes estão instalados, tais como Memória, Placa de Vídeo, CPU, Discos Rígidos, BIOS, Placa de Rede, Placa Mãe, etc.', 'HARD', '0000-00-00 00:00:00', NULL),
('cs_coleta_monitorado', 'Coleta Informações sobre os Sistemas Monitorados', 'Essa ação permite que sejam coletadas, nas estações onde os agentes Cacic estão instalados, as informações acerca dos perfís de sistemas, previamente cadastrados pela Administração Central.', 'MONI', '0000-00-00 00:00:00', NULL),
('cs_coleta_officescan', 'Coleta Informações do Antivírus OfficeScan', 'Essa ação permite que sejam coletadas informações sobre o antivírus OfficeScan nos computadores onde os agentes estão instalados. São coletadas informações como a versão do engine, versão do pattern, endereço do servidor, data da instalação, etc.', 'ANVI', '0000-00-00 00:00:00', NULL),
('cs_coleta_software', 'Coleta Informações de Software', 'Essa ação permite que sejam coletadas informações sobre as versões de diversos softwares instalados nos computadores onde os agentes são executados. São coletadas, por exemplo, informações sobre as versões do Internet Explorer, Mozilla, DirectX, ADO, BDE, DAO, Java Runtime Environment, etc.', 'SOFT', '0000-00-00 00:00:00', NULL),
('cs_coleta_unid_disc', 'Coleta Informações sobre Unidades de Disco', 'Essa ação permite que sejam coletadas informações sobre as unidades de disco disponíveis nos computadores onde os agentes são executados. São coletadas, por exemplo, informações sobre o sistema de arquivos das unidades, suas capacidades de armazenamento, ocupação, espaço livre, etc.', 'UNDI', '0000-00-00 00:00:00', NULL),
('cs_suporte_remoto', 'Suporte Remoto Seguro', 'Esta ação permite a realização de suporte remoto na estação de trabalho, com registro de logs de sessão efetuado pelo Gerente WEB.', 'SR_CACIC', '0000-00-00 00:00:00', NULL);



--
-- Extraindo dados da tabela `configuracoes_padrao`
--

INSERT INTO `configuracoes_padrao` (`in_exibe_erros_criticos`, `in_exibe_bandeja`, `nu_exec_apos`, `nu_rel_maxlinhas`, `nm_organizacao`, `nu_timeout_srcacic`, `nu_intervalo_exec`, `nu_intervalo_renovacao_patrim`, `te_senha_adm_agente`, `te_serv_updates_padrao`, `te_serv_cacic_padrao`, `te_enderecos_mac_invalidos`, `te_janelas_excecao`, `cs_abre_janela_patr`, `id_default_body_bgcolor`, `te_exibe_graficos`, `nu_porta_srcacic`, `nu_resolucao_grafico_h`, `nu_resolucao_grafico_w`) VALUES
('N', 'S', 10, 50, 'Nome Extenso da Organização', 30, 4, 0, '5a584f8a61b65baf', 'UXRJO117', 'www-cacic3', '00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,\r\n00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08', 'openoffice.org, microsoft word, photoshop, hod, aor.exe, pc2003.exe, cpp.exe, sal.exe, sal.bat, girafa4.exe, dro.exe, plenus', 'N', '#EBEBEB', '[so][acessos][acessos_locais][locais]', '5900', 0, 0);

--
-- Extraindo dados da tabela `descricao_hardware`
--

INSERT INTO `descricao_hardware` (`nm_campo_tab_hardware`, `te_desc_hardware`, `te_locais_notificacao_ativada`) VALUES
('te_cdrom_desc', 'CD-ROM', ''),
('qt_mem_ram', 'Memória RAM', ''),
('qt_placa_video_cores', 'Qtd. Cores Placa VÃ­deo', ''),
('qt_placa_video_mem', 'Memória Placa Vídeo', ''),
('te_bios_desc', 'Descrição da BIOS', ''),
('te_bios_fabricante', 'Fabricante da BIOS', ''),
('te_cpu_desc', 'CPU', ''),
('te_cpu_fabricante', 'Fabricante da CPU', ''),
('te_cpu_serial', 'Serial da CPU', ''),
('te_mem_ram_desc', 'Descrição da RAM', ''),
('te_modem_desc', 'Modem', ''),
('te_mouse_desc', 'Mouse', ''),
('te_placa_mae_desc', 'Placa Mãe', ''),
('te_placa_mae_fabricante', 'Fabricante Placa Mãe', ''),
('te_placa_rede_desc', 'Placa de Rede', ''),
('te_placa_som_desc', 'Placa de Som', ''),
('te_placa_video_desc', 'Placa de Vídeo', ''),
('te_placa_video_resolucao', 'Resolução Placa de Vídeo', ''),
('te_teclado_desc', 'Teclado', '');

--
-- Extraindo dados da tabela `descricoes_colunas_computadores`
--

INSERT INTO `descricoes_colunas_computadores` (`nm_campo`, `te_descricao_campo`, `cs_condicao_pesquisa`) VALUES
('dt_hr_coleta_forcada_estacao', 'Quant. dias de última coleta forçada na estação', 'S'),
('dt_hr_inclusao', 'Quant. dias de inclusão do computador na base', 'S'),
('dt_hr_ult_acesso', 'Quant. dias do último acesso da estação ao gerente WEB', 'S'),
('id_ip_rede', 'Endereço IP da Subrede', 'S'),
('id_so', 'Código do sistema operacional da estação', 'S'),
('qt_mem_ram', 'Quant. memória RAM', 'S'),
('qt_placa_video_cores', 'Quant. cores da placa de vídeo', 'S'),
('qt_placa_video_mem', 'Quant. memória da placa de vídeo', 'S'),
('te_bios_data', 'Identificação da BIOS', 'S'),
('te_bios_desc', 'Descrição da BIOS', 'S'),
('te_bios_fabricante', 'Nome do fabricante da BIOS', 'S'),
('te_cdrom_desc', 'Disco Óptico', 'S'),
('te_cpu_desc', 'CPU', 'S'),
('te_cpu_fabricante', 'Fabricante da CPU', 'S'),
('te_cpu_frequencia', 'Frequência da CPU', 'S'),
('te_cpu_serial', 'Número de série da CPU', 'S'),
('te_dns_primario', 'IP do DNS primário', 'S'),
('te_dns_secundario', 'IP do DNS secundário', 'S'),
('te_dominio_dns', 'Nome/IP do domínio DNS', 'S'),
('te_dominio_windows', 'Nome/IP do domínio Windows', 'S'),
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
('te_placa_mae_fabricante', 'Fabricante da placa-mãe', 'S'),
('te_placa_rede_desc', 'Placa de Rede', 'S'),
('te_placa_som_desc', 'Placa de Som', 'S'),
('te_placa_video_desc', 'Placa de Vídeo', 'S'),
('te_placa_video_resolucao', 'Resolução da placa de vídeo', 'S'),
('te_serv_dhcp', 'IP do servidor DHCP', 'S'),
('te_so', 'Identificador Interno do S.O.', 'S'),
('te_teclado_desc', 'Descrição do teclado', 'S'),
('te_versao_cacic', 'Versão do Agente Principal do CACIC', 'S'),
('te_versao_gercols', 'Versão do Gerente de Coletas do CACIC', 'S'),
('te_wins_primario', 'IP do servidor WINS primário', 'S'),
('te_wins_secundario', 'IP do servidor WINS secundário', 'S'),
('te_workgroup', 'Nome do grupo de trabalho', 'S');

--
-- Extraindo dados da tabela `grupo_usuarios`
--

INSERT INTO `grupo_usuarios` (`id_grupo_usuarios`, `te_grupo_usuarios`, `te_menu_grupo`, `te_descricao_grupo`, `cs_nivel_administracao`, `nm_grupo_usuarios`) VALUES
(1, 'Comum', 'menu_com.txt', 'Usuário limitado, sem acesso a informações confidenciais como Softwares Inventariados e Opções Administrativas como Forçar Coletas e Excluir Computador. Poderá alterar sua própria senha.', 0, ''),
(2, 'Administração', 'menu_adm.txt', 'Acesso irrestrito.', 1, ''),
(5, 'Gestão Central', 'menu_adm.txt', 'Acesso de leitura em todas as opções.', 2, ''),
(6, 'Supervisão', 'menu_sup.txt', 'Manutenção de tabelas e acesso a todas as informações referentes à Localização.', 3, ''),
(7, 'Técnico', 'menu_tec.txt', 'Acesso técnico. Será permitido acessar configuracoes de rede e relatórios de Patrimônio e Hardware.', 4, '');

--
-- Extraindo dados da tabela `patrimonio_config_interface`
--

INSERT INTO `patrimonio_config_interface` (`id_local`, `id_etiqueta`, `nm_etiqueta`, `te_etiqueta`, `in_exibir_etiqueta`, `te_help_etiqueta`, `te_plural_etiqueta`, `nm_campo_tab_patrimonio`, `in_destacar_duplicidade`, `in_obrigatorio`) VALUES
(1, 'etiqueta1', 'Etiqueta 1', 'Entidade', '', 'Selecione a Entidade', 'Entidades', 'id_unid_organizacional_nivel1', 'N', 'N'),
(1, 'etiqueta1a', 'Etiqueta 1a', 'Linha de Negócio', '', 'Selecione a Linha de Negócio', 'Linhas de Negócio', 'id_unid_organizacional_nivel1a', 'N', 'N'),
(1, 'etiqueta2', 'Etiqueta 2', 'Órgão', '', 'Selecione o órgão', 'Órgãos', 'id_unid_organizacional_nivel2', 'N', 'N'),
(1, 'etiqueta3', 'Etiqueta 3', 'Seção / Sala / Ramal', '', 'Informe a seção onde está instalado o equipamento.', '', 'te_localizacao_complementar', 'N', 'N'),
(1, 'etiqueta4', 'Etiqueta 4', 'PIB da CPU', 'S', 'Informe o número de PIB(tombamento) da CPU', '', 'te_info_patrimonio1', 'S', 'N'),
(1, 'etiqueta5', 'Etiqueta 5', 'PIB do Monitor', 'S', 'Informe o número de PIB (tombamento) do Monitor', '', 'te_info_patrimonio2', 'S', 'N'),
(1, 'etiqueta6', 'Etiqueta 6', 'PIB da Impressora', 'S', 'Caso haja uma Impressora conectada informe n?mero de PIB (tombamento)', '', 'te_info_patrimonio3', 'S', 'N'),
(1, 'etiqueta7', 'Etiqueta 7', 'Nº Série CPU (Opcional)', 'S', 'Caso não disponha do nº de PIB, informe o nº de série da CPU', '', 'te_info_patrimonio4', 'S', 'N'),
(1, 'etiqueta8', 'Etiqueta 8', 'Nº Série Monitor (Opcional)', 'S', 'Caso não disponha do nº de PIB, informe o nº de série do Monitor', '', 'te_info_patrimonio5', 'S', 'N'),
(1, 'etiqueta9', 'Etiqueta 9', 'Nº Série Impres. (Opcional)', 'S', 'Caso haja uma impressora conectada ao micro e n?o disponha do n? de PIB, informe seu nº de série', '', 'te_info_patrimonio6', 'S', 'N');

--
-- Extraindo dados da tabela `so`
--

INSERT INTO `so` (`id_so`, `te_desc_so`, `sg_so`, `te_so`, `in_mswindows`) VALUES
(1, 'Windows 95', 'W95', '1.4.0.B', 'S'),
(3, 'Windows 98', 'W98', '1.4.10', 'S'),
(4, 'Windows 98 SE', 'W98SE', '1.4.10.A', 'S'),
(6, 'Windows NT (SP5)', 'WNT-SP5', '2.4.0.Service Pack 5', 'S'),
(7, 'Windows 2000 (Genérico)', 'W2K-SP2-Gen', '5.0', 'S'),
(8, 'Windows XP (Genérico)', 'WXP-SP2-Gen', '5.1', 'S'),
(13, 'Windows 2003 (SP2)', 'W2003-SP2', '2.5.2.Service Pack 2', 'S'),
(15, 'Ubuntu 7.10 Gutsy Gibbon', 'Ubuntu-7.10', 'Ubuntu - 7.10', 'N'),
(17, 'Windows 2000 (SP3)', 'W2K-SP3', '2.5.0.Service Pack 3', 'S'),
(18, 'Windows VISTA', 'VISTA', '2.6.0', 'S'),
(19, 'Windows VISTA (SP1)', 'VISTA-SP1', '2.6.0.Service Pack 1', 'S'),
(20, 'Ubuntu 8.04 Hardy Heron', 'Ubuntu-8.04', 'Ubuntu - 8.04', 'N'),
(21, 'CentOS 3.9', 'CENTOS-3.9', 'CentOS release - 3.9', 'N'),
(22, 'Debian 4.0 Etch', 'Debian-4', 'Debian - 4.0', 'N'),
(23, 'Ubuntu 8.04.1 Hardy Heron', 'Ubuntu-8.041', 'Ubuntu - 8.04.1', 'N'),
(24, 'Red Hat EL 4', 'RHEL-4', 'Red Hat Enterprise Linux ES release - 4', 'N'),
(25, 'Red Hat ELS 5.1', 'RHELS-5.1', 'Red Hat Enterprise Linux Server release - 5.1', 'N'),
(26, 'Fedora 8', 'Fedora-8', 'Fedora release - 8', 'N'),
(27, 'Fedora Core R4', 'Fedora-4', 'Fedora Core release - 4', 'N'),
(28, 'CentOS 4.3', 'CentOS-4.3', 'CentOS release - 4.3', 'N'),
(29, 'Red Hat AS 4', 'RHAS-4', 'Red Hat Enterprise Linux AS release - 4', 'N'),
(30, 'Windows XP (SP3)', 'WXP-SP3', '2.5.1.Service Pack 3', 'S'),
(31, 'Windows 2000 (SP4)', 'W2K-SP4', '2.5.0.Service Pack 4', 'S'),
(32, 'Windows XP (SP2)', 'WXP-SP2', '2.5.1.Service Pack 2', 'S'),
(33, 'Windows 2000 (SP2)', 'W2K-SP2', '2.5.0.Service Pack 2', 'S'),
(34, 'Windows 2000', 'W2K', '2.5.0', 'S'),
(35, 'Windows XP (SP1)', 'WXP-SP1', '2.5.1.Service Pack 1', 'S'),
(36, 'Windows XP', 'WXP', '2.5.1', 'S'),
(37, 'Windows NT (SP6)', 'WNT-SP6', '2.4.0.Service Pack 6', 'S'),
(38, 'Kurumin 7.0 b6', 'Kurumin-7.0b6', 'Kurumin - 7.0b6', 'N'),
(39, 'Kurumin 7.0', 'Kurumin-7.0', 'Kurumin - 7.0', 'N'),
(40, 'Arch Linux', 'Arch-Linux', 'Arch Linux', 'N'),
(42, 'Windows 95A', 'W95A', '1.4.0', 'S'),
(43, 'Ubuntu 8.10 Intrepid Ibex', 'Ubuntu-8.10', 'Ubuntu - 8.10', 'N'),
(46, 'Windows 2000 (SP1)', 'W2K-SP1', '2.5.0.Service Pack 1', 'S'),
(47, 'Windows 2000 (SP4) - RC3.154', 'W2K-SP4-RC3', '2.5.0.Service Pack 4, RC 3.154', 'S'),
(50, 'Ubuntu 9.04 Jaunty Jackalope', 'Ubuntu-9.04', 'Ubuntu - 9.04', 'N'),
(52, 'Debian 5.0 Lenny', 'Debian_5', 'Debian - 5.0', 'N'),
(53, 'Debian 5.0.1 Lenny', 'Debian_501', 'Debian - 5.0.1', 'N'),
(54, 'Windows 2003', 'W2K3', '2.5.2', 'S'),
(56, 'Windows VISTA (SP2)', 'VISTA-SP2', '2.6.0.Service Pack 2', 'S'),
(57, 'Windows 7', 'W7', '2.6.1', 'S'),
(59, 'Ubuntu 9.10 Karmic Koala', 'Ubuntu_910', 'Ubuntu - 9.10', 'N'),
(61, 'Debian 5.0.3 Lenny', 'Debian_503', 'Debian - 5.0.3', 'N'),
(63, 'Debian 5.0.4 Lenny', 'Debian_504', 'Debian - 5.0.4', 'N'),
(65, 'Ubuntu 10.04 Lucid Lynx', 'Ubuntu-10.04', 'Ubuntu - 10.04', 'N'),
(67, 'Debian 5.0.5 Lenny', 'Debian_505', 'Debian - 5.0.5', 'N'),
(69, 'Ubuntu 10.10 Maverick Meerkat', 'Ubuntu-10.10', 'Ubuntu - 10.10', 'N'),
(71, 'Debian 5.0.2 Lenny', 'Debian_502', 'Debian - 5.0.2', 'N'),
(72, 'Windows 7 (SP1)', 'W7-SP1', '2.6.1.Service Pack 1', 'S'),
(74, 'Ubuntu 11.04 Natty Narwhal', 'Ubuntu-11.04', 'Ubuntu - 11.04', 'N'),
(75, 'Debian 5.0.8 Lenny', 'Debian_508', 'Debian - 5.0.8', 'N'),
(76, 'Windows 7 - Professional - 64Bits', 'W7_PRO_64', '2.6.1.1.256.64', 'S'),
(77, 'Windows 7 - Professional - 32Bits', 'W7_PRO_32', '2.6.1.1.256.32', 'S'),
(80, 'Windows XP - SP3', 'WXP_SP3', '2.5.1.1.256.32', 'S'),
(81, 'Windows 7 - Enterprise - 32Bits', 'W7_EN_32_2', '2.6.1.1.256', 'S'),
(83, 'Ubuntu 11.10 Oneiric Ocelot', 'Ubuntu-11.10', 'Ubuntu - 11.10', 'N'),
(84, 'Windows XP - 64bits', 'WXP_64Bits', '2.5.1.1.256.64', 'S'),
(85, 'Windows 7 - Home Edition - 64Bits', 'W7_HE_64', '2.6.1.1.768.64', 'S'),
(86, 'Debian 5.0.10 Lenny', 'Debian_5010', 'Debian - 5.0.10', 'N'),
(88, 'Windows VISTA - 32Bits', 'VISTA_32', '2.6.0.1.256.32', 'S'),
(89, 'Ubuntu 12.04 Precise Pangolin', 'Ubuntu-12.04', 'Ubuntu - 12.04', 'N'),
(90, 'Windows 7 - Home Premium - 64bits', 'W7_HP_64', '2.6.1.1.768', 'S'),
(91, 'Windows XP', 'WXP', '2.5.1.1.256', 'S'),
(92, 'Debian - Wheezy (sid)', 'Debian_Wheezy', 'Debian - wheezy/sid', 'N');

--
-- Extraindo dados da tabela `tipos_software`
--

INSERT INTO `tipos_software` (`id_tipo_software`, `te_descricao_tipo_software`) VALUES
(0, 'Versão Trial'),
(1, 'Correção/Atualização'),
(2, 'Sistema Interno'),
(3, 'Software Livre'),
(4, 'Software Licenciado'),
(5, 'Software Suspeito'),
(6, 'Software Descontinuado'),
(7, 'Jogos e Similares');

--
-- Extraindo dados da tabela `tipos_unidades_disco`
--

INSERT INTO `tipos_unidades_disco` (`id_tipo_unid_disco`, `te_tipo_unid_disco`) VALUES
('1', 'Removível'),
('2', 'Disco Rígido'),
('3', 'CD-ROM'),
('4', 'Unid.Remota');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
