<?php
//conexão com o banco
$server = "localhost";
$username = "root";
$pass = "w1f1t1d1";
$db = "cacic";
mysql_connect($server, $username, $pass);
mysql_select_db($db);


function extrair($table, $query){
    // $table = "nome_da_tabela_no_cacic3"
    // $query = "SELECT colunas_velhas as colunas_novas from nome_da_tabela_no_cacic2"

    $res = mysql_query($query);

    $filename = $table . ".csv";
    echo "Iniciando extração em " . $table . "... ";
    $fp = fopen($filename, "w");

    // Recebe os nomes das colunas
    $row = mysql_fetch_assoc($res);
    $line = "";
    $separador = "";
    foreach($row as $name => $value) {
        $line .= $separador . '"' . str_replace('"', '""', $name) . '"';
        $separador = ";";
    }
    $line .= "\n";
    fputs($fp, $line);
    mysql_data_seek($res, 0);

    // Recebe as linhas da tabela
    while($row = mysql_fetch_assoc($res)) {
        $line = "";
        $separador = "";
        foreach($row as $value) {
            $line .= $separador . '"' . str_replace('"', '""', $value) . '"';
            $separador = ";";
        }
        $line .= "\n";
        fputs($fp, $line);
    }

    fclose($fp);
    echo "feito.\n";
}


function createtemptables(){
    //Cria e popula as tabelas temporarias "newredes" e "newcomputador"


    mysql_query("DROP TABLE IF EXISTS newredes");
    mysql_query("DROP TABLE IF EXISTS newcomputador");

    mysql_query("CREATE TABLE newredes (id_rede int(11) NOT NULL, id_ip_rede char(15) NOT NULL, id_local int(11), PRIMARY KEY (id_rede))");
    mysql_query("CREATE TABLE newcomputador (id_computador int(11) NOT NULL, id_so int(11) NOT NULL, te_node_address char(17) NOT NULL, te_ip char(15) DEFAULT NULL, PRIMARY KEY (id_computador))");

    extrair("newredes", "SELECT DISTINCT @s:=@s+1 id_rede, id_ip_rede, id_local FROM redes, (SELECT @s:=0) AS s");
    extrair("newcomputador", "SELECT DISTINCT @s:=@s+1 id_computador, id_so, te_node_address, te_ip FROM computadores, (SELECT @s:=0) AS s");

    mysql_query("LOAD DATA LOCAL INFILE 'newredes.csv' INTO TABLE newredes FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES");
    mysql_query("LOAD DATA LOCAL INFILE 'newcomputador.csv' INTO TABLE newcomputador FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES");

}

$tabelas = array(
    // "nome_da_tabela_no_cacic3" => "SELECT colunas_velhas as colunas_novas from nome_da_tabela_no_cacic2"
    "acao" => "SELECT id_acao, te_descricao_breve, te_descricao, te_nome_curto_modulo, dt_hr_alteracao, cs_situacao AS cs_opcional FROM acoes",
    "acao_excecao" => "SELECT acao_excecao.te_node_address, acao_excecao.id_acao, newredes.id_rede FROM acoes_excecoes AS acao_excecao INNER JOIN newredes ON (acao_excecao.id_local=newredes.id_local)",
    "acao_rede" => "SELECT acao_rede.id_acao, newredes.id_rede, acao_rede.dt_hr_coleta_forcada FROM acoes_redes AS acao_rede INNER JOIN newredes ON (acao_rede.id_local=newredes.id_local AND acao_rede.id_ip_rede=newredes.id_ip_rede)",
    "acao_so" => "SELECT acao_so.id_acao, newredes.id_rede, acao_so.id_so FROM acoes_so AS acao_so INNER JOIN newredes ON (acao_so.id_local=newredes.id_local)",
    "aplicativo" => "SELECT id_aplicativo, id_so, nm_aplicativo, cs_car_inst_w9x, te_car_inst_w9x, cs_car_ver_w9x, te_car_ver_w9x, cs_car_inst_wnt, te_car_inst_wnt, cs_car_ver_wnt, te_car_ver_wnt, cs_ide_licenca, te_ide_licenca, dt_atualizacao, te_arq_ver_eng_w9x, te_arq_ver_pat_w9x, te_arq_ver_eng_wnt, te_arq_ver_pat_wnt, te_dir_padrao_w9x, te_dir_padrao_wnt, te_descritivo, in_disponibiliza_info, in_disponibiliza_info_usuario_comum, dt_registro FROM perfis_aplicativos_monitorados",
    "aplicativo_rede" => "SELECT newredes.id_rede, aplicativo_rede.id_aplicativo FROM aplicativos_redes AS aplicativo_rede INNER JOIN newredes ON (aplicativo_rede.id_ip_rede=newredes.id_ip_rede)",
    "aquisicao" => "SELECT id_aquisicao, dt_aquisicao, nr_processo, nm_empresa, nm_proprietario, nr_notafiscal FROM aquisicoes",
    "aquisicao_item" => "SELECT id_tipo_licenca, id_aquisicao, id_software, qt_licenca, dt_vencimento_licenca, te_obs FROM aquisicoes_item",
    "computador" => "SELECT newcomputador.id_computador, @s1:=NULL id_usuario_exclusao, computador.id_so, newredes.id_rede, computador.te_nome_computador AS nm_computador, computador.te_node_address, computador.te_ip AS te_ip_computador, computador.dt_hr_inclusao, @s2:=NULL dt_hr_exclusao, computador.dt_hr_ult_acesso, computador.te_versao_cacic, computador.te_versao_gercols, computador.te_palavra_chave, computador.dt_hr_coleta_forcada_estacao, computador.te_nomes_curtos_modulos, computador.id_conta, @s3:=NULL te_debugging, @s4:=NULL te_ultimo_login, @s5:=NULL dt_debug FROM computadores AS computador INNER JOIN newredes ON (computador.id_ip_rede=newredes.id_ip_rede) INNER JOIN newcomputador ON (computador.id_so=newcomputador.id_so AND computador.te_node_address=newcomputador.te_node_address), (SELECT @s:=0) AS s",
    "descricao_coluna_computador" => "SELECT @s1:='DB' te_source, @s2:=CONCAT('Cacic2','_',nm_campo) te_target, te_descricao_campo AS te_description, cs_condicao_pesquisa FROM descricoes_colunas_computadores",
    "grupo_usuario" => "SELECT id_grupo_usuarios AS id_grupo_usuario, nm_grupo_usuarios, te_grupo_usuarios, te_menu_grupo, te_descricao_grupo, cs_nivel_administracao FROM grupo_usuarios;",
    "insucesso_instalacao" => "SELECT @s:=@s+1 id_insucesso_instalacao, te_ip AS te_ip_computador, te_so, id_usuario, dt_datahora, cs_indicador FROM insucessos_instalacao, (SELECT @s:=0) AS s",
    "local" => "SELECT id_local, nm_local, sg_local, te_observacao, @s1:=NULL te_debugging, @s2:=NULL dt_debug FROM locais",
    // "local_secundario" => "",
    "log" => "SELECT @s:=@s+1 id_log, id_usuario, dt_acao, cs_acao, nm_script, nm_tabela, te_ip_origem FROM log, (SELECT @s:=0) AS s",
    "patrimonio" => "SELECT @s:=@s+1 id_patrimonio, @s1:=NULL id_usuario, patrimonio.id_unid_organizacional_nivel1a, @s2:=NULL id_computador, patrimonio.id_unid_organizacional_nivel2, patrimonio.dt_hr_alteracao, patrimonio.te_localizacao_complementar, patrimonio.te_info_patrimonio1, patrimonio.te_info_patrimonio2, patrimonio.te_info_patrimonio3, patrimonio.te_info_patrimonio4, patrimonio.te_info_patrimonio5, patrimonio.te_info_patrimonio6, @s3:=NULL id_unid_organizacional_nivel1 FROM patrimonio, (SELECT @s:=0) AS s",
    "patrimonio_config_interface" => "SELECT id_etiqueta, id_local, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade, @s1:='' in_obrigatorio FROM patrimonio_config_interface",
    "rede" => "SELECT newredes.id_rede, rede.id_local, rede.id_servidor_autenticacao, rede.id_ip_rede AS te_ip_rede, rede.nm_rede, rede.te_observacao, rede.nm_pessoa_contato1, rede.nm_pessoa_contato2, rede.nu_telefone1, rede.te_email_contato2, rede.nu_telefone2, rede.te_email_contato1, rede.te_serv_cacic, rede.te_serv_updates, rede.te_path_serv_updates, rede.nm_usuario_login_serv_updates, rede.te_senha_login_serv_updates, rede.nu_porta_serv_updates, rede.te_mascara_rede, rede.dt_verifica_updates, rede.nm_usuario_login_serv_updates_gerente, rede.te_senha_login_serv_updates_gerente, rede.nu_limite_ftp, rede.cs_permitir_desativar_srcacic, @s1:=NULL te_debugging, @s2:=NULL dt_debug FROM redes as rede INNER JOIN newredes ON (rede.id_local=newredes.id_local AND rede.id_ip_rede=newredes.id_ip_rede)",
    "rede_grupo_ftp" => "SELECT rede_grupo_ftp.id_ftp, newredes.id_rede, newcomputador.id_computador, rede_grupo_ftp.nu_hora_inicio, rede_grupo_ftp.nu_hora_fim FROM redes_grupos_ftp AS rede_grupo_ftp INNER JOIN newredes ON (rede_grupo_ftp.id_local=newredes.id_local) AND rede_grupo_ftp.id_ip_rede=newredes.id_ip_rede INNER JOIN newcomputador ON (rede_grupo_ftp.id_ip_estacao=newcomputador.te_ip)",
    "rede_versao_modulo" => "SELECT @s:=@s+1 id_rede_versao_modulo, newredes.id_rede, rede_versao_modulo.nm_modulo, rede_versao_modulo.te_versao_modulo, rede_versao_modulo.dt_atualizacao, rede_versao_modulo.cs_tipo_so, rede_versao_modulo.te_hash FROM redes_versoes_modulos AS rede_versao_modulo INNER JOIN newredes ON (rede_versao_modulo.id_local=newredes.id_local) AND rede_versao_modulo.id_ip_rede=newredes.id_ip_rede, (SELECT @s:=0) AS s",
    "servidor_autenticacao" => "SELECT id_servidor_autenticacao, nm_servidor_autenticacao, @s1:='' nm_servidor_autenticacao_dns, te_ip_servidor_autenticacao, nu_porta_servidor_autenticacao, id_tipo_protocolo, nu_versao_protocolo, te_atributo_identificador, @s2:=NULL te_atributo_identificador_alternativo, te_atributo_retorna_nome, te_atributo_retorna_email, @s3:=NULL te_atributo_retorna_telefone, @s4:=NULL te_atributo_status_conta, @s5:='' te_atributo_valor_status_conta_valida, te_observacao, in_ativo FROM servidores_autenticacao",
    "so" => "SELECT id_so, te_desc_so, sg_so, te_so, in_mswindows FROM so",
    "software" => "SELECT id_software, @s1:=NULL id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs FROM softwares AS software",
    "software_estacao" => "SELECT newcomputador.id_computador, softwares_estacao.id_software, softwares_estacao.id_aquisicao_particular as id_aquisicao, softwares_estacao.nr_patrimonio, softwares_estacao.dt_autorizacao, softwares_estacao.dt_expiracao_instalacao, softwares_estacao.id_aquisicao_particular, softwares_estacao.dt_desinstalacao, softwares_estacao.te_observacao, softwares_estacao.nr_patr_destino FROM newcomputador INNER JOIN softwares_inventariados_estacoes ON (newcomputador.te_node_address=softwares_inventariados_estacoes.te_node_address AND newcomputador.id_so = softwares_inventariados_estacoes.id_so) INNER JOIN softwares_inventariados ON (softwares_inventariados_estacoes.id_software_inventariado=softwares_inventariados.id_software_inventariado) INNER JOIN softwares_estacao ON (softwares_estacao.id_software=softwares_inventariados.id_software)",
    // Tabela srcacic_action Não existia anteriormente
    "srcacic_chat" => "SELECT @s:=@s+1 id_srcacic_chat, id_conexao AS id_srcacic_conexao, dt_hr_mensagem, te_mensagem, cs_origem FROM srcacic_chats, (SELECT @s:=0) AS s",
    "srcacic_conexao" => "SELECT id_conexao AS id_srcacic_conexao, id_sessao AS id_srcacic_sessao, id_usuario_cli, id_so_cli, te_node_address_cli, id_so_cli, te_documento_referencial, te_motivo_conexao, dt_hr_inicio_conexao, dt_hr_ultimo_contato FROM srcacic_conexoes",
    "srcacic_sessao" => "SELECT id_sessao as id_srcacic_sessao, newcomputador.id_computador, dt_hr_inicio_sessao, nm_acesso_usuario_srv, nm_completo_usuario_srv, te_email_usuario_srv, dt_hr_ultimo_contato FROM srcacic_sessoes INNER JOIN newcomputador ON (newcomputador.id_so=srcacic_sessoes.id_so_srv AND newcomputador.te_node_address=srcacic_sessoes.te_node_address_srv)",
    "srcacic_transf" => "SELECT @s:=@s+1 id_srcacic_transf, id_conexao AS id_srcacic_conexao, dt_systemtime, nu_duracao, te_path_origem, te_path_destino, nm_arquivo, nu_tamanho_arquivo, cs_status, cs_operacao FROM srcacic_transfs, (SELECT @s:=0) AS s",
    "teste" => "SELECT id_transacao, te_linha FROM testes",
    "tipo_licenca" => "SELECT id_tipo_licenca, te_tipo_licenca FROM tipos_licenca",
    "tipo_software" => "SELECT id_tipo_software, te_descricao_tipo_software FROM tipos_software",
    "tipo_uorg" => "SELECT @s1:=0 id_tipo_uorg, @s2:='Cacic2' nm_tipo_uorg, @s3:=NULL tedescricao",
    "unid_organizacional_nivel1" => "SELECT id_unid_organizacional_nivel1, nm_unid_organizacional_nivel1, te_endereco_uon1, te_bairro_uon1, te_cidade_uon1, te_uf_uon1, nm_responsavel_uon1, te_email_responsavel_uon1, nu_tel1_responsavel_uon1, nu_tel2_responsavel_uon1 FROM unid_organizacional_nivel1",
    "unid_organizacional_nivel1a" => "SELECT id_unid_organizacional_nivel1a, id_unid_organizacional_nivel1, nm_unid_organizacional_nivel1a FROM unid_organizacional_nivel1a",
    "unid_organizacional_nivel2" => "SELECT id_local, id_unid_organizacional_nivel2, id_unid_organizacional_nivel1a, nm_unid_organizacional_nivel2, te_endereco_uon2, te_bairro_uon2, te_cidade_uon2, te_uf_uon2, nm_responsavel_uon2, te_email_responsavel_uon2, nu_tel1_responsavel_uon2, nu_tel2_responsavel_uon2, dt_registro FROM unid_organizacional_nivel2",
    // "uorg" => "",
    "usb_device" => "SELECT id_device AS id_usb_device, id_vendor AS id_usb_vendor, nm_device AS nm_usb_device, te_observacao, @s1:=0 dt_registro FROM usb_devices",
    "usb_log" => "SELECT @s:=@s+1 id_usb_log, id_vendor AS id_usb_vendor, id_device AS id_usb_device, newcomputador.id_computador, dt_event, cs_event FROM usb_logs INNER JOIN newcomputador ON (newcomputador.id_so=usb_logs.id_so AND newcomputador.te_node_address=usb_logs.te_node_address), (SELECT @s:=0) AS s",
    "usb_vendor" => "SELECT id_vendor AS id_usb_vendor, nm_vendor AS nm_usb_vendor, te_observacao, @s1:=NULL dt_registro FROM usb_vendors",
    "usuario" => "SELECT id_usuario, id_local, id_servidor_autenticacao, id_grupo_usuarios AS id_grupo_usuario, @s1:=NULL id_usuario_ldap, nm_usuario_acesso, nm_usuario_completo, @s2:=NULL nm_usuario_completo_ldap, te_senha, dt_log_in, te_emails_contato, te_telefones_contato FROM usuarios",
);

createtemptables();
while ($tabela = current($tabelas)) {
    extrair(key($tabelas),$tabela);
    next($tabelas);
}
// mysql_query("DROP TABLE newredes");
// mysql_query("DROP TABLE newcomputador");

?>