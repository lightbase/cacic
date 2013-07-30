insert into local(nm_local, sg_local, te_observacao, te_debugging, dt_debug) values ('Ministério do Planejamento', 'MPOG', NULL, NULL, NULL);

insert into grupo_usuario(id_grupo_usuario, nm_grupo_usuarios, te_grupo_usuarios, te_menu_grupo, te_descricao_grupo, cs_nivel_administracao) values (1, 'Admin', 'Administradores', 'menu_adm.txt', 'Acesso irrestrito', TRUE);

insert into usuario(id_local, id_servidor_autenticacao, id_grupo_usuario, nm_usuario_acesso, nm_usuario_completo, te_senha) values (1, NULL, 1, 'admin', 'Administrador do Sistema', '7c4a8d09ca3762af61e59520943dc26494f8941b');

INSERT INTO `configuracao_padrao` (`id_configuracao`, `nm_configuracao`, `vl_configuracao`) VALUES
('cs_abre_janela_patr', 'Exibe janela de patrimônio', 'S'),
('id_default_body_bgcolor', 'id_default_body_bgcolor', '#1820d9'),
('in_exibe_bandeja', 'Exibir o icone do CACIC na bandeja (systray)', 'N'),
('in_exibe_erros_criticos', 'Exibir erros criticos aos usuarios', 'N'),
('nm_organizacao', 'Nome da organização', 'Org. CACIC v3'),
('nu_exec_apos', 'Inicio de execucao das ações (em minutos)', '1111'),
('nu_intervalo_exec', 'Intervalo de execução das ações (em horas)', '4'),
('nu_intervalo_renovacao_patrim', 'nu_intervalo_renovacao_patrim', '0'),
('nu_porta_srcacic', 'nu_porta_srcacic', '5900'),
('nu_rel_maxlinhas', 'Quantidade máxima de linhas em relatorios', '1000'),
('nu_resolucao_grafico_h', 'Resolucao dos graficos a serem exibidos (Altura)', '0'),
('nu_resolucao_grafico_w', 'Resolucao dos graficos a serem exibidos (Largura)', '0'),
('nu_timeout_srcacic', 'nu_timeout_srcacic', '30'),
('te_enderecos_mac_invalidos', 'Endereços MAC a desconsiderar', '00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08'),
('te_exibe_graficos', 'Gráficos a serem exibidos', 'so,acessos_locais,locais'),
('te_janelas_excecao', 'Aplicativos (janelas) a evitar', 'openoffice.org, microsoft word, photoshop, hod, aor.exe, pc2003.exe, cpp.exe, sal.exe, sal.bat, girafa4.exe, dro.exe, plenus'),
('te_notificar_mudanca_hardware', 'E-mails para notificar alteracoes de hardware', NULL),
('te_notificar_utilizacao_usb', 'E-mails para notificar utilizacao de dispositivos USB', NULL),
('te_senha_adm_agente', 'Senha padrão para administrar o agente', '5a584f8a61b65baf'),
('te_serv_cacic_padrao', 'Nome ou IP do servidor de aplicação (gerente)', 'www-cacic'),
('te_serv_updates_padrao', 'Nome ou IP do servidor de atualização (FTP)', '');