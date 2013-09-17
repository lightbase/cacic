<?php
// Conexão com o banco
$server = "localhost";
$db = "cacic";
$user = "root";
$pass = "";
$dbcon = new PDO("mysql:host={$server};dbname={$db}", $user, $pass);

// Nome do diretório temporário
$tmproot = sys_get_temp_dir();
$tmpdir = $tmproot.'/bases_cacic2';


function extrair($dbcon) {
    global $tmpdir;

    $lista_tabelas = array(
        "acao" => "SELECT id_acao, te_descricao_breve, te_descricao, te_nome_curto_modulo, dt_hr_alteracao, cs_situacao AS cs_opcional INTO OUTFILE '$tmpdir/acao.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM acoes",
        "acao_excecao" => "SELECT acao_excecao.te_node_address, acao_excecao.id_acao, tmp_redes.id_rede INTO OUTFILE '$tmpdir/acao_excecao.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM acoes_excecoes AS acao_excecao INNER JOIN tmp_redes ON (acao_excecao.id_local=tmp_redes.id_local)",
        "acao_rede" => "SELECT acao_rede.id_acao, tmp_redes.id_rede, acao_rede.dt_hr_coleta_forcada INTO OUTFILE '$tmpdir/acao_rede.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM acoes_redes AS acao_rede INNER JOIN tmp_redes ON (acao_rede.id_local=tmp_redes.id_local AND acao_rede.id_ip_rede=tmp_redes.id_ip_rede)",
        "acao_so" => "SELECT acao_so.id_acao, tmp_redes.id_rede, acao_so.id_so INTO OUTFILE '$tmpdir/acao_so.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM acoes_so AS acao_so INNER JOIN tmp_redes ON (acao_so.id_local=tmp_redes.id_local)",
        "aplicativo" => "SELECT id_aplicativo, id_so, nm_aplicativo, cs_car_inst_w9x, te_car_inst_w9x, cs_car_ver_w9x, te_car_ver_w9x, cs_car_inst_wnt, te_car_inst_wnt, cs_car_ver_wnt, te_car_ver_wnt, cs_ide_licenca, te_ide_licenca, dt_atualizacao, te_arq_ver_eng_w9x, te_arq_ver_pat_w9x, te_arq_ver_eng_wnt, te_arq_ver_pat_wnt, te_dir_padrao_w9x, te_dir_padrao_wnt, te_descritivo, in_disponibiliza_info, in_disponibiliza_info_usuario_comum, dt_registro INTO OUTFILE '$tmpdir/aplicativo.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM perfis_aplicativos_monitorados",
        "aplicativo_rede" => "SELECT tmp_redes.id_rede, aplicativo_rede.id_aplicativo INTO OUTFILE '$tmpdir/aplicativo_rede.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM aplicativos_redes AS aplicativo_rede INNER JOIN tmp_redes ON (aplicativo_rede.id_ip_rede=tmp_redes.id_ip_rede)",
        "aquisicao" => "SELECT id_aquisicao, dt_aquisicao, nr_processo, nm_empresa, nm_proprietario, nr_notafiscal INTO OUTFILE '$tmpdir/aquisicao.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM aquisicoes",
        "aquisicao_item" => "SELECT aquisicoes_item.id_tipo_licenca, aquisicoes_item.id_aquisicao, softwares.id_software, aquisicoes_item.qt_licenca, aquisicoes_item.dt_vencimento_licenca, aquisicoes_item.te_obs INTO OUTFILE '$tmpdir/aquisicao_item.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM aquisicoes_item INNER JOIN softwares ON (aquisicoes_item.id_software=softwares.id_software)",
        "computador" => "SELECT tmp_computador.id_computador, @s1:=NULL id_usuario_exclusao, computador.id_so, tmp_redes.id_rede, computador.te_nome_computador AS nm_computador, computador.te_node_address, computador.te_ip AS te_ip_computador, computador.dt_hr_inclusao, @s2:=NULL dt_hr_exclusao, computador.dt_hr_ult_acesso, computador.te_versao_cacic, computador.te_versao_gercols, computador.te_palavra_chave, computador.dt_hr_coleta_forcada_estacao, computador.te_nomes_curtos_modulos, computador.id_conta, @s3:=NULL te_debugging, @s4:=NULL te_ultimo_login, @s5:=NULL dt_debug INTO OUTFILE '$tmpdir/computador.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM computadores AS computador INNER JOIN tmp_redes ON (computador.id_ip_rede=tmp_redes.id_ip_rede) INNER JOIN tmp_computador ON (computador.id_so=tmp_computador.id_so AND computador.te_node_address=tmp_computador.te_node_address), (SELECT @s:=0) AS s",
        "descricao_coluna_computador" => "SELECT @s1:='DB' te_source, @s2:=CONCAT('Cacic2','_',nm_campo) te_target, te_descricao_campo AS te_description, cs_condicao_pesquisa INTO OUTFILE '$tmpdir/descricao_coluna_computador.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM descricoes_colunas_computadores",
        "grupo_usuario" => "SELECT id_grupo_usuarios AS id_grupo_usuario, nm_grupo_usuarios, te_grupo_usuarios, te_menu_grupo, te_descricao_grupo, cs_nivel_administracao INTO OUTFILE '$tmpdir/grupo_usuario.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM grupo_usuarios",
        "insucesso_instalacao" => "SELECT @s:=@s+1 id_insucesso_instalacao, te_ip AS te_ip_computador, te_so, id_usuario, dt_datahora, cs_indicador INTO OUTFILE '$tmpdir/insucesso_instalacao.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM insucessos_instalacao, (SELECT @s:=0) AS s",
        "local" => "SELECT id_local, nm_local, sg_local, te_observacao, @s1:=NULL te_debugging, @s2:=NULL dt_debug INTO OUTFILE '$tmpdir/local.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM locais",
        "local_secundario" => "SELECT id_usuario, te_locais_secundarios AS id_local INTO OUTFILE '$tmpdir/local_secundario.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM usuarios",
        "log" => "SELECT @s:=@s+1 id_log, usuarios.id_usuario, log.dt_acao, log.cs_acao, log.nm_script, log.nm_tabela, log.te_ip_origem INTO OUTFILE '$tmpdir/log.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM log INNER JOIN usuarios ON (usuarios.id_usuario = log.id_usuario), (SELECT @s:=0) AS s",
        "patrimonio" => "SELECT @s:=@s+1 id_patrimonio, @s1:=NULL id_usuario, patrimonio.id_unid_organizacional_nivel1a, @s2:=NULL id_computador, patrimonio.id_unid_organizacional_nivel2, patrimonio.dt_hr_alteracao, patrimonio.te_localizacao_complementar, patrimonio.te_info_patrimonio1, patrimonio.te_info_patrimonio2, patrimonio.te_info_patrimonio3, patrimonio.te_info_patrimonio4, patrimonio.te_info_patrimonio5, patrimonio.te_info_patrimonio6, unid_organizacional_nivel1a.id_unid_organizacional_nivel1 INTO OUTFILE '$tmpdir/patrimonio.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM patrimonio INNER JOIN unid_organizacional_nivel1a ON (patrimonio.id_unid_organizacional_nivel1a=unid_organizacional_nivel1a.id_unid_organizacional_nivel1), (SELECT @s:=0) AS s ",
        "patrimonio_config_interface" => "SELECT id_etiqueta, id_local, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade, @s1:='' in_obrigatorio INTO OUTFILE '$tmpdir/patrimonio_config_interface.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM patrimonio_config_interface",
        "rede" => "SELECT tmp_redes.id_rede, rede.id_local, rede.id_servidor_autenticacao, rede.id_ip_rede AS te_ip_rede, rede.nm_rede, rede.te_observacao, rede.nm_pessoa_contato1, rede.nm_pessoa_contato2, rede.nu_telefone1, rede.te_email_contato2, rede.nu_telefone2, rede.te_email_contato1, rede.te_serv_cacic, rede.te_serv_updates, rede.te_path_serv_updates, rede.nm_usuario_login_serv_updates, rede.te_senha_login_serv_updates, rede.nu_porta_serv_updates, rede.te_mascara_rede, rede.dt_verifica_updates, rede.nm_usuario_login_serv_updates_gerente, rede.te_senha_login_serv_updates_gerente, rede.nu_limite_ftp, rede.cs_permitir_desativar_srcacic, @s1:=NULL te_debugging, @s2:=NULL dt_debug INTO OUTFILE '$tmpdir/rede.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM redes AS rede INNER JOIN tmp_redes ON (rede.id_local=tmp_redes.id_local AND rede.id_ip_rede=tmp_redes.id_ip_rede)",
        "rede_grupo_ftp" => "SELECT t1.id_ftp, t1.id_rede,  t2.id_computador, t1.nu_hora_inicio, t1.nu_hora_fim INTO OUTFILE '$tmpdir/rede_grupo_ftp.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM ( SELECT rede_grupo_ftp.id_ftp, tmp_redes.id_rede,  tmp_computador.id_computador, rede_grupo_ftp.nu_hora_inicio, rede_grupo_ftp.nu_hora_fim FROM redes_grupos_ftp AS rede_grupo_ftp INNER JOIN tmp_redes ON (rede_grupo_ftp.id_local=tmp_redes.id_local) AND (rede_grupo_ftp.id_ip_rede=tmp_redes.id_ip_rede) INNER JOIN tmp_computador ON (rede_grupo_ftp.id_ip_estacao=tmp_computador.te_ip) GROUP BY id_ftp ) t1 INNER JOIN ( SELECT tmp_computador.id_computador, @s1:=NULL id_usuario_exclusao, computador.id_so, tmp_redes.id_rede, computador.te_nome_computador AS nm_computador, computador.te_node_address, computador.te_ip AS te_ip_computador, computador.dt_hr_inclusao, @s2:=NULL dt_hr_exclusao, computador.dt_hr_ult_acesso, computador.te_versao_cacic, computador.te_versao_gercols, computador.te_palavra_chave, computador.dt_hr_coleta_forcada_estacao, computador.te_nomes_curtos_modulos, computador.id_conta, @s3:=NULL te_debugging, @s4:=NULL te_ultimo_login, @s5:=NULL dt_debug FROM computadores AS computador INNER JOIN tmp_redes ON (computador.id_ip_rede=tmp_redes.id_ip_rede) INNER JOIN tmp_computador ON (computador.id_so=tmp_computador.id_so) AND (computador.te_node_address=tmp_computador.te_node_address), (SELECT @s:=0) AS s ) t2 ON (t1.id_computador=t2.id_computador)",
        "rede_versao_modulo" => "SELECT @s:=@s+1 id_rede_versao_modulo, tmp_redes.id_rede, rede_versao_modulo.nm_modulo, rede_versao_modulo.te_versao_modulo, rede_versao_modulo.dt_atualizacao, rede_versao_modulo.cs_tipo_so, rede_versao_modulo.te_hash INTO OUTFILE '$tmpdir/rede_versao_modulo.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM redes_versoes_modulos AS rede_versao_modulo INNER JOIN tmp_redes ON (rede_versao_modulo.id_local=tmp_redes.id_local) AND rede_versao_modulo.id_ip_rede=tmp_redes.id_ip_rede, (SELECT @s:=0) AS s",
        "servidor_autenticacao" => "SELECT id_servidor_autenticacao, nm_servidor_autenticacao, @s1:='' nm_servidor_autenticacao_dns, te_ip_servidor_autenticacao, nu_porta_servidor_autenticacao, id_tipo_protocolo, nu_versao_protocolo, te_atributo_identificador, @s2:=NULL te_atributo_identificador_alternativo, te_atributo_retorna_nome, te_atributo_retorna_email, @s3:=NULL te_atributo_retorna_telefone, @s4:=NULL te_atributo_status_conta, @s5:='' te_atributo_valor_status_conta_valida, te_observacao, in_ativo INTO OUTFILE '$tmpdir/servidor_autenticacao.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM servidores_autenticacao",
        "so" => "SELECT id_so, te_desc_so, sg_so, te_so, in_mswindows INTO OUTFILE '$tmpdir/so.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM so",
        "software" => "SELECT id_software, @s1:=NULL id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs INTO OUTFILE '$tmpdir/software.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM softwares AS software",
        "software_estacao" => "SELECT tmp_computador.id_computador, softwares_estacao.id_software, softwares_estacao.id_aquisicao_particular AS id_aquisicao, softwares_estacao.nr_patrimonio, softwares_estacao.dt_autorizacao, softwares_estacao.dt_expiracao_instalacao, softwares_estacao.id_aquisicao_particular, softwares_estacao.dt_desinstalacao, softwares_estacao.te_observacao, softwares_estacao.nr_patr_destino INTO OUTFILE '$tmpdir/software_estacao.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM tmp_computador INNER JOIN softwares_inventariados_estacoes ON (tmp_computador.te_node_address=softwares_inventariados_estacoes.te_node_address AND tmp_computador.id_so = softwares_inventariados_estacoes.id_so) INNER JOIN softwares_inventariados ON (softwares_inventariados_estacoes.id_software_inventariado=softwares_inventariados.id_software_inventariado) INNER JOIN softwares_estacao ON (softwares_estacao.id_software=softwares_inventariados.id_software)",
        "srcacic_chat" => "SELECT @s:=@s+1 id_srcacic_chat, id_conexao AS id_srcacic_conexao, dt_hr_mensagem, te_mensagem, cs_origem INTO OUTFILE '$tmpdir/srcacic_chat.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM srcacic_chats, (SELECT @s:=0) AS s",
        "srcacic_conexao" => "SELECT id_conexao AS id_srcacic_conexao, id_sessao AS id_srcacic_sessao, id_usuario_cli, id_so_cli, te_node_address_cli, id_so_cli, te_documento_referencial, te_motivo_conexao, dt_hr_inicio_conexao, dt_hr_ultimo_contato INTO OUTFILE '$tmpdir/srcacic_conexao.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM srcacic_conexoes",
        "srcacic_sessao" => "SELECT id_sessao AS id_srcacic_sessao, tmp_computador.id_computador, dt_hr_inicio_sessao, nm_acesso_usuario_srv, nm_completo_usuario_srv, te_email_usuario_srv, dt_hr_ultimo_contato INTO OUTFILE '$tmpdir/srcacic_sessao.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM srcacic_sessoes INNER JOIN tmp_computador ON (tmp_computador.id_so=srcacic_sessoes.id_so_srv AND tmp_computador.te_node_address=srcacic_sessoes.te_node_address_srv)",
        "srcacic_transf" => "SELECT @s:=@s+1 id_srcacic_transf, id_conexao AS id_srcacic_conexao, dt_systemtime, nu_duracao, te_path_origem, te_path_destino, nm_arquivo, nu_tamanho_arquivo, cs_status, cs_operacao INTO OUTFILE '$tmpdir/srcacic_transf.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM srcacic_transfs, (SELECT @s:=0) AS s",
        "teste" => "SELECT id_transacao, te_linha INTO OUTFILE '$tmpdir/teste.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM testes",
        "tipo_licenca" => "SELECT id_tipo_licenca, te_tipo_licenca INTO OUTFILE '$tmpdir/tipo_licenca.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM tipos_licenca",
        "tipo_software" => "SELECT id_tipo_software, te_descricao_tipo_software INTO OUTFILE '$tmpdir/tipo_software.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM tipos_software",
        "tipo_uorg" => "SELECT @s1:=0 id_tipo_uorg, @s2:='Cacic2' nm_tipo_uorg, @s3:=NULL tedescricao INTO OUTFILE '$tmpdir/tipo_uorg.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n'",
        "uorg" => "SELECT * INTO OUTFILE '$tmpdir/uorg.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM (SELECT tmp_uorg.id_uorg, @s1:=NULL id_uorg_pai, @s2:='0' id_tipo_uorg, @s3:=NULL id_local, uorg1.nm_unid_organizacional_nivel1 AS nm_uorg, uorg1.te_endereco_uon1 AS te_endereco, uorg1.te_bairro_uon1 AS te_bairro, uorg1.te_cidade_uon1 AS te_cidade, uorg1.te_uf_uon1 AS te_uf, uorg1.nm_responsavel_uon1 AS nm_responsavel, uorg1.te_email_responsavel_uon1 AS te_responsavel_email, uorg1.nu_tel1_responsavel_uon1 AS nu_responsavel_tel1, uorg1.nu_tel2_responsavel_uon1 AS nu_responsavel_tel2 FROM unid_organizacional_nivel1 uorg1 INNER JOIN tmp_uorg ON (uorg1.id_unid_organizacional_nivel1=tmp_uorg.id_uorg1) WHERE tmp_uorg.id_uorg1a IS NULL UNION ALL SELECT tmp_uorg.id_uorg, t.id_uorg_pai AS id_uorg_pai, @s1:='0' id_tipo_uorg, @s2:=NULL id_local, uorg1a.nm_unid_organizacional_nivel1a AS nm_uorg, @s3:=NULL te_endereco, @s4:=NULL te_bairro, @s5:=NULL te_cidade, @s6:=NULL te_uf, @s7:=NULL nm_responsavel, @s8:=NULL te_responsavel_email, @s9:=NULL nu_responsavel_tel1, @s10:=NULL nu_responsavel_tel2 FROM unid_organizacional_nivel1a uorg1a INNER JOIN tmp_uorg ON (uorg1a.id_unid_organizacional_nivel1a=tmp_uorg.id_uorg1a) INNER JOIN (SELECT tmp_uorg.id_uorg AS id_uorg_pai, u1.id_unid_organizacional_nivel1 FROM unid_organizacional_nivel1 u1 INNER JOIN tmp_uorg ON (tmp_uorg.id_uorg1=u1.id_unid_organizacional_nivel1) WHERE tmp_uorg.id_uorg1a IS NULL) t ON (uorg1a.id_unid_organizacional_nivel1=t.id_unid_organizacional_nivel1) WHERE tmp_uorg.id_uorg2 IS NULL UNION ALL SELECT tmp_uorg.id_uorg, t.id_uorg_pai AS id_uorg_pai, @s1:='0' id_tipo_uorg, uorg2.id_local AS id_local, uorg2.nm_unid_organizacional_nivel2 AS nm_uorg, uorg2.te_endereco_uon2 AS te_endereco, uorg2.te_bairro_uon2 AS te_bairro, uorg2.te_cidade_uon2 AS te_cidade, uorg2.te_uf_uon2 AS te_uf, uorg2.nm_responsavel_uon2 AS nm_responsavel, uorg2.te_email_responsavel_uon2 AS te_responsavel_email, uorg2.nu_tel1_responsavel_uon2 AS nu_responsavel_tel1, uorg2.nu_tel2_responsavel_uon2 AS nu_responsavel_tel2 FROM unid_organizacional_nivel2 uorg2 INNER JOIN tmp_uorg ON (uorg2.id_unid_organizacional_nivel2=tmp_uorg.id_uorg2) INNER JOIN (SELECT tmp_uorg.id_uorg AS id_uorg_pai, u1a.id_unid_organizacional_nivel1a FROM unid_organizacional_nivel1a u1a INNER JOIN tmp_uorg ON (tmp_uorg.id_uorg1a=u1a.id_unid_organizacional_nivel1a) WHERE tmp_uorg.id_uorg2 IS NULL) t ON (uorg2.id_unid_organizacional_nivel1a=t.id_unid_organizacional_nivel1a)) t",
        "unid_organizacional_nivel1" => "SELECT id_unid_organizacional_nivel1, nm_unid_organizacional_nivel1, te_endereco_uon1, te_bairro_uon1, te_cidade_uon1, te_uf_uon1, nm_responsavel_uon1, te_email_responsavel_uon1, nu_tel1_responsavel_uon1, nu_tel2_responsavel_uon1 INTO OUTFILE '$tmpdir/unid_organizacional_nivel1.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM unid_organizacional_nivel1",
        "unid_organizacional_nivel1a" => "SELECT id_unid_organizacional_nivel1a, id_unid_organizacional_nivel1, nm_unid_organizacional_nivel1a INTO OUTFILE '$tmpdir/unid_organizacional_nivel1a.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM unid_organizacional_nivel1a WHERE id_unid_organizacional_nivel1 != 0",
        "unid_organizacional_nivel2" => "SELECT id_unid_organizacional_nivel2, id_local, id_unid_organizacional_nivel1a, nm_unid_organizacional_nivel2, te_endereco_uon2, te_bairro_uon2, te_cidade_uon2, te_uf_uon2, nm_responsavel_uon2, te_email_responsavel_uon2, nu_tel1_responsavel_uon2, nu_tel2_responsavel_uon2, dt_registro INTO OUTFILE '$tmpdir/unid_organizacional_nivel2.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM unid_organizacional_nivel2 WHERE id_local != 0",
        "usb_device" => "SELECT tmp_usb.id_usb_device, usb_vendors.id_vendor AS id_usb_vendor, usb_devices.nm_device AS nm_usb_device, usb_devices.te_observacao, @s1:=NULL dt_registro, usb_devices.id_device INTO OUTFILE '$tmpdir/usb_device.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM usb_devices INNER JOIN tmp_usb ON (tmp_usb.id_device=usb_devices.id_device AND tmp_usb.id_vendor=usb_devices.id_vendor AND tmp_usb.nm_device=usb_devices.nm_device) INNER JOIN usb_vendors ON (usb_devices.id_vendor=usb_vendors.id_vendor) WHERE tmp_usb.repeticao = 1",
        "usb_log" => "SELECT @s:=@s+1 id_usb_log, tmp_usb.id_usb_device, tmp_computador.id_computador, dt_event, cs_event, usb_logs.id_device INTO OUTFILE '$tmpdir/usb_log.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM usb_logs INNER JOIN tmp_computador ON (tmp_computador.id_so=usb_logs.id_so AND tmp_computador.te_node_address=usb_logs.te_node_address) INNER JOIN tmp_usb ON (tmp_usb.id_device=usb_logs.id_device AND tmp_usb.id_vendor=usb_logs.id_vendor), (SELECT @s:=0) AS s",
        "usb_vendor" => "SELECT id_vendor AS id_usb_vendor, nm_vendor AS nm_usb_vendor, te_observacao, @s1:=NULL dt_registro INTO OUTFILE '$tmpdir/usb_vendor.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM usb_vendors",
        "usuario" => "SELECT id_usuario, id_local, id_servidor_autenticacao, id_grupo_usuarios AS id_grupo_usuario, @s1:=NULL id_usuario_ldap, nm_usuario_acesso, nm_usuario_completo, @s2:=NULL nm_usuario_completo_ldap, @s3:=CONCAT('Cacic2','_',nm_usuario_acesso) te_senha, dt_log_in, te_emails_contato, te_telefones_contato INTO OUTFILE '$tmpdir/usuario.csv' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM usuarios"
    );

    while($tabela = current($lista_tabelas)) {

        $filename = $tmpdir."/".key($lista_tabelas).".csv";
        echo "Carregando dados de ".key($lista_tabelas)."... ";
        $pesquisa = $dbcon->prepare($tabela);
        $pesquisa->execute();
        echo "feito.<br>\n";
        next($lista_tabelas);
    }
}


function Zip($source, $destination){
    // Função que cira o arquivo zip a partir do diretorio informado
    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)){
        return false;
    }
    $source = str_replace('\\', '/', realpath($source));
    if (is_dir($source) === true){
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file){
            $file = str_replace('\\', '/', $file);

            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
               continue;

            $file = realpath($file);

            if (is_dir($file) === true){
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true){
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true){
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
}


function fix_local_secundario($filename) {
    // Altera modelo dos locais secundarios de usuário de acordo com o novo padrão
    $local_sec = file_get_contents($filename);
    $locais = explode("\n", $local_sec); // Separa as linhas em arrays
    $local_secundario = "";

    while ($line = current($locais)) {
        $linearray = explode(";", $line); // Separa id_usuario e id_local nas linhas
        if ($linearray[1]!='""') {
            $id_local = explode(",", $linearray[1]); // Separa id_local diferentes

            foreach ($id_local as $fix_id_local) {
                // Coloca os registros de id_local entre "" se não estiver
                if($fix_id_local[0] != '"') {
                    $fix_id_local = '"'.$fix_id_local;
                }
                if($fix_id_local[strlen($fix_id_local)-1] != '"') {
                    $fix_id_local = $fix_id_local . '"';
                }

                $local_secundario .= $linearray[0] . ";" . $fix_id_local . "\n";
            }
        }
        next($locais);
    }
    // Sobrepoe o arquivo de locais_secundarios com a versão corrigida
    file_put_contents($filename, $local_secundario);
}


function fix_null_time($filename) {
    // Altera valor de datas com "0000-00-00 00:00:00" para \N para funcionar no postgres
    $dados = file_get_contents($filename);
    $dados_corrigidos = str_replace('"0000-00-00 00:00:00"', '\N', $dados);
    file_put_contents($filename, $dados_corrigidos);
}


function fix_rede_grupo_ftp($filename) {
    //Corrige a coluna nu_hora_inicio para o padrao correto de tempo
    $grupos = file_get_contents($filename);
    $dados_corrigidos = "";

    $array_linhas = explode("\n", $grupos);
    foreach ($array_linhas as $linha) {
        $coluna = explode(";", $linha);
        if(count($coluna) == 1) {
            continue;
        }
        // Converte o formato de horario da coluna nu_hora_inicio
        $nu_hora_inicio = substr($coluna[3], 1, -1);
        $nu_hora_inicio = '"'.date("Y-m-d H:i:s",$nu_hora_inicio).'"';

        $dados_corrigidos .= $coluna[0].";".$coluna[1].";".$coluna[2].";".$nu_hora_inicio.";\N\n";
    }
     // Sobrepoe o arquivo de rede_grupo_ftp com a versão corrigida
    file_put_contents($filename, $dados_corrigidos);
}


function fix_quoted_data($filename) {
    // Remove aspas duplas de registros
    $dados = file_get_contents($filename);
    $dados_corrigidos = str_replace('\"', '', $dados);
    file_put_contents($filename, $dados_corrigidos);
}


function create_temptables($dbcon) {
    // Cria e popula as tabelas temporarias "tmp_redes" , "tmp_computador" , "tmp_uorg" e "tmp_usb"
    global $tmpdir;

    $tmp_redes_file = $tmpdir."/tmp_redes.csv";
    $tmp_computador_file = $tmpdir."/tmp_computador.csv";
    $tmp_uorg_file = $tmpdir."/tmp_uorg.csv";
    $tmp_usb_file = $tmpdir."/tmp_usb.csv";

    $dbcon->exec("DROP TABLE IF EXISTS tmp_redes");
    $dbcon->exec("SELECT @s:=@s+1 id_rede, id_ip_rede, id_local INTO OUTFILE '$tmp_redes_file' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM redes, (SELECT @s:=0) AS s");
    $dbcon->exec("CREATE TABLE tmp_redes (id_rede int(11) NOT NULL, id_ip_rede char(15) NOT NULL, id_local int(11), PRIMARY KEY (id_rede))");
    $dbcon->exec("LOAD DATA INFILE '$tmp_redes_file' INTO TABLE tmp_redes FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n'");
    unlink($tmp_redes_file);

    $dbcon->exec("DROP TABLE IF EXISTS tmp_computador");
    $dbcon->exec("SELECT @s:=@s+1 id_computador, id_so, te_node_address, te_ip INTO OUTFILE '$tmp_computador_file' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM computadores, (SELECT @s:=0) AS s");
    $dbcon->exec("CREATE TABLE tmp_computador (id_computador int(11) NOT NULL, id_so int(11) NOT NULL, te_node_address char(17) NOT NULL, te_ip char(15) DEFAULT NULL, PRIMARY KEY (id_computador))");
    $dbcon->exec("LOAD DATA INFILE '$tmp_computador_file' INTO TABLE tmp_computador FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n'");
    unlink($tmp_computador_file);

    $dbcon->exec("DROP TABLE IF EXISTS tmp_uorg");
    $dbcon->exec("SELECT @s:=@s+1 id_uorg, t.id_uorg1, t.id_uorg1a, t.id_uorg2 INTO OUTFILE '$tmp_uorg_file' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM (SELECT u1.id_unid_organizacional_nivel1 AS id_uorg1, @s1:=NULL id_uorg1a, @s2:=NULL id_uorg2  FROM unid_organizacional_nivel1 u1 UNION ALL SELECT u1.id_unid_organizacional_nivel1 AS id_uorg1, u1a.id_unid_organizacional_nivel1a AS id_uorg1a, @s1:=NULL id_uorg2 FROM unid_organizacional_nivel1a u1a INNER JOIN unid_organizacional_nivel1 u1 ON (u1.id_unid_organizacional_nivel1=u1a.id_unid_organizacional_nivel1) UNION ALL SELECT u1.id_unid_organizacional_nivel1 AS id_uorg1, u1a.id_unid_organizacional_nivel1a AS id_uorg1a, u2.id_unid_organizacional_nivel2 AS id_uorg2 FROM unid_organizacional_nivel2 u2 INNER JOIN unid_organizacional_nivel1a u1a ON (u2.id_unid_organizacional_nivel1a=u1a.id_unid_organizacional_nivel1a) INNER JOIN unid_organizacional_nivel1 u1 ON (u1.id_unid_organizacional_nivel1=u1a.id_unid_organizacional_nivel1)) t, (SELECT @s:=0) AS s");
    $dbcon->exec("CREATE TABLE tmp_uorg (id_uorg int(11) NOT NULL, id_uorg1 int(11) DEFAULT NULL, id_uorg1a int(11) DEFAULT NULL, id_uorg2 int(11) DEFAULT NULL, PRIMARY KEY (id_uorg))");
    $dbcon->exec("LOAD DATA INFILE '$tmp_uorg_file' INTO TABLE tmp_uorg FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n'");
    unlink($tmp_uorg_file);

    $dbcon->exec("DROP TABLE IF EXISTS tmp_usb");
    $dbcon->exec("SELECT COUNT(id_device) AS repeticao, @s:=@s+1 id_usb_device, id_device, id_vendor, nm_device INTO OUTFILE '$tmp_usb_file' FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM usb_devices, (SELECT @s:=0) AS s GROUP BY id_device, id_vendor ORDER BY repeticao DESC, id_vendor, id_usb_device");
    $dbcon->exec("CREATE TABLE tmp_usb (repeticao int(2) NOT NULL, id_usb_device int(11) NOT NULL, id_device char(5) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL, id_vendor char(5) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL, nm_device char(127) NOT NULL, PRIMARY KEY (id_usb_device))");
    $dbcon->exec("LOAD DATA INFILE '$tmp_usb_file' INTO TABLE tmp_usb FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n'");
    unlink($tmp_usb_file);
}


// Execuções
echo "Iniciando criação do arquivo de exportação<br>\n";

// Remove diretório se ele já existir
if (file_exists($tmpdir)){
    foreach (glob($tmpdir.'/*') as $filename) {
        unlink($filename);
    }
    rmdir($tmpdir);
}

// Cria diretório temporário para o import
mkdir($tmpdir);
chmod($tmpdir, 0777);

// Cria tabelas temporárias
create_temptables($dbcon);

// Realiza a extração
extrair($dbcon);

// Correções de compatibilidade
fix_local_secundario($tmpdir."/local_secundario.csv");
fix_null_time($tmpdir."/acao.csv");
fix_null_time($tmpdir."/aplicativo.csv");
fix_null_time($tmpdir."/unid_organizacional_nivel2.csv");
fix_rede_grupo_ftp($tmpdir."/rede_grupo_ftp.csv");
fix_quoted_data($tmpdir."/usb_device.csv");

// Deleta tabelas temporárias
/*$dbcon->exec("DROP TABLE tmp_redes");
$dbcon->exec("DROP TABLE tmp_computador");
$dbcon->exec("DROP TABLE tmp_uorg");
$dbcon->exec("DROP TABLE tmp_usb");*/

// Gera um arquivo .zip com os dados
$zipfile = $tmproot."/bases_cacic2_".date("Y-m-d_H:i:s").".zip";
Zip($tmpdir, $zipfile);
chmod($zipfile, 0777);

// Deleta o diretorio temporario
foreach (glob($tmpdir.'/*') as $filename) {
    unlink($filename);
}
rmdir($tmpdir);

echo "O arquivo {$zipfile} foi criado.<br>\n";

// Fecha conexão com o banco
$dbcon = null;
/*

// Permite o download do arquivo
if (file_exists($zipfile)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename='.basename($zipfile));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($zipfile));
    ob_clean();
    flush();
    readfile($zipfile);
    exit;
}


// Redireciona para a pagina anterior
header("Location: extrator.php");*/
?>