<?php
//conexão com o banco
$server = "localhost";
$username = "root";
$pass = "w1f1t1d1";
$db = "cacic";
mysql_connect($server, $username, $pass);
mysql_select_db($db);
//criar função que cria a tabela import automaticamente

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

$tabelasx = array(
    "import" => "SELECT DISTINCT @s:=@s+1 id_rede, id_ip_rede, id_local FROM redes, (SELECT @s:= 0) AS s"
);

$tabelas = array(
    // "nome_da_tabela_no_cacic3" => "SELECT colunas_velhas as colunas_novas from nome_da_tabela_no_cacic2"
    "acao" => "SELECT id_acao, te_descricao_breve, te_descricao, te_nome_curto_modulo, dt_hr_alteracao, cs_situacao AS cs_opcional FROM acoes",
    "acao_excecao" => "SELECT acao_excecao.te_node_address, acao_excecao.id_acao, import.id_rede FROM acoes_excecoes AS acao_excecao INNER JOIN import ON acao_excecao.id_local=import.id_local",
    "acao_rede" => "SELECT acao_rede.id_acao, import.id_rede, acao_rede.dt_hr_coleta_forcada FROM acoes_redes AS acao_rede INNER JOIN import ON acao_rede.id_local=import.id_local AND acao_rede.id_ip_rede=import.id_ip_rede",
    "acao_so" => "SELECT acao_so.id_acao, import.id_rede, acao_so.id_so FROM acoes_so AS acao_so INNER JOIN import ON acao_so.id_local=import.id_local",
    "aplicativo" => "SELECT id_aplicativo, id_so, nm_aplicativo, cs_car_inst_w9x, te_car_inst_w9x, cs_car_ver_w9x, te_car_ver_w9x, cs_car_inst_wnt, te_car_inst_wnt, cs_car_ver_wnt, te_car_ver_wnt, cs_ide_licenca, te_ide_licenca, dt_atualizacao, te_arq_ver_eng_w9x, te_arq_ver_pat_w9x, te_arq_ver_eng_wnt, te_arq_ver_pat_wnt, te_dir_padrao_w9x, te_dir_padrao_wnt, te_descritivo, in_disponibiliza_info, in_disponibiliza_info_usuario_comum, dt_registro FROM perfis_aplicativos_monitorados",
    "aplicativo_rede" => "SELECT import.id_rede, aplicativo_rede.id_aplicativo FROM aplicativos_redes AS aplicativo_rede INNER JOIN import ON aplicativo_rede.id_ip_rede=import.id_ip_rede",
    "aquisicao" => "SELECT id_aquisicao, dt_aquisicao, nr_processo, nm_empresa, nm_proprietario, nr_notafiscal FROM aquisicoes",
    "aquisicao_item" => "SELECT id_tipo_licenca, id_aquisicao, id_software, qt_licenca, dt_vencimento_licenca, te_obs FROM aquisicoes_item",
    // "class_property",
    // "class_property_type",
    // "classe",
    // "collect_def_class",
    "computador" => "SELECT @s:=@s+1 id_computador, @s1:=NULL id_usuario_exclusao, computador.id_so, import.id_rede, computador.te_nome_computador as nm_computador, computador.te_node_address, computador.te_ip as te_ip_computador, computador.dt_hr_inclusao, @s2:=NULL dt_hr_exclusao, computador.dt_hr_ult_acesso, computador.te_versao_cacic, computador.te_versao_gercols, computador.te_palavra_chave, computador.dt_hr_coleta_forcada_estacao, computador.te_nomes_curtos_modulos, computador.id_conta, @s3:=NULL te_debugging, @s4:=NULL te_ultimo_login, @s5:=NULL dt_debug FROM computadores AS computador INNER JOIN import ON computador.id_ip_rede=import.id_ip_rede, (SELECT @s:= 0) AS s",
    // "computador_coleta",
    // "computador_coleta_historico",
    // "configuracao_local",
    // "configuracao_padrao",
    "descricao_coluna_computador" => "SELECT @s1:='DB' te_source, @s2:=CONCAT('NomeDaTabela','_',nm_campo) te_target, te_descricao_campo AS te_description, cs_condicao_pesquisa FROM descricoes_colunas_computadores",
    "grupo_usuario" => "SELECT id_grupo_usuarios AS id_grupo_usuario, nm_grupo_usuarios, te_grupo_usuarios, te_menu_grupo, te_descricao_grupo, cs_nivel_administracao FROM grupo_usuarios;",
    // "insucesso_instalacao" => "",
    // "local" => "",
    // "local_secundario" => "",
    "log" => "SELECT @s:=@s+1 id_log, id_usuario, dt_acao, cs_acao, nm_script, nm_tabela, te_ip_origem FROM log, (SELECT @s:= 0) AS s",
    // "patrimonio" => "",
    // "patrimonio_config_interface" => "",
    "rede" => "SELECT import.id_rede, rede.id_local, rede.id_servidor_autenticacao, rede.id_ip_rede AS te_ip_rede, rede.nm_rede, rede.te_observacao, rede.nm_pessoa_contato1, rede.nm_pessoa_contato2, rede.nu_telefone1, rede.te_email_contato2, rede.nu_telefone2, rede.te_email_contato1, rede.te_serv_cacic, rede.te_serv_updates, rede.te_path_serv_updates, rede.nm_usuario_login_serv_updates, rede.te_senha_login_serv_updates, rede.nu_porta_serv_updates, rede.te_mascara_rede, rede.dt_verifica_updates, rede.nm_usuario_login_serv_updates_gerente, rede.te_senha_login_serv_updates_gerente, rede.nu_limite_ftp, rede.cs_permitir_desativar_srcacic, @s1:=NULL te_debugging, @s2:=NULL dt_debug FROM redes as rede INNER JOIN import ON rede.id_local=import.id_local AND rede.id_ip_rede=import.id_ip_rede",
    // "rede_grupo_ftp" => "",
    // _____________________________ //
    // "rede_versao_modulo" => "",
    // "servidor_autenticacao" => "",
    "so" => "SELECT id_so, te_desc_so, sg_so, te_so, in_mswindows FROM so",
    // "software" => "",
    // "software_estacao" => "",
    // "srcacic_action" => "",
    // "srcacic_chat" => "",
    // "srcacic_conexao" => "",
    // "srcacic_sessao" => "",
    // "srcacic_transf" => "",
    // "teste" => "",
    // "tipo_licenca" => "",
    // "tipo_software" => "",
    "tipo_uorg" => "SELECT @s1:=0 id_tipo_uorg, @s2:='Cacic2' nm_tipo_uorg, @s3:=NULL tedescricao",
    "unid_organizacional_nivel1" => "SELECT id_unid_organizacional_nivel1, nm_unid_organizacional_nivel1, te_endereco_uon1, te_bairro_uon1, te_cidade_uon1, te_uf_uon1, nm_responsavel_uon1, te_email_responsavel_uon1, nu_tel1_responsavel_uon1, nu_tel2_responsavel_uon1 FROM unid_organizacional_nivel1",
    "unid_organizacional_nivel1a" => "SELECT id_unid_organizacional_nivel1a, id_unid_organizacional_nivel1, nm_unid_organizacional_nivel1a FROM unid_organizacional_nivel1a",
    "unid_organizacional_nivel2" => "SELECT id_local, id_unid_organizacional_nivel2, id_unid_organizacional_nivel1a, nm_unid_organizacional_nivel2, te_endereco_uon2, te_bairro_uon2, te_cidade_uon2, te_uf_uon2, nm_responsavel_uon2, te_email_responsavel_uon2, nu_tel1_responsavel_uon2, nu_tel2_responsavel_uon2, dt_registro FROM unid_organizacional_nivel2",
    // "uorg" => "",
    // "usb_device" => "",
    // "usb_log" => "",
    // "usb_vendor" => "",
    // "usuario" => "",
    /*Joemerson
    "rede_versao_modulo" => "SELECT id_rede, nm_modulo, te_versao_modulo, dt_atualizacao, cs_tipo_so, te_hash FROM rede_versao_modulo INNER JOIN rede ON rede.id_rede=rede_versao_modulo.id_rede",
    "servidor_autenticacao" => "SELECT id_servidor_autenticacao, nm_servidor_autenticacao, te_ip_servidor_autenticacao, nu_porta_servidor_autenticacao, id_tipo_protocolo, nu_versao_protocolo, te_atributo_identificador, te_atributo_retorna_nome, te_atributo_retorna_email, te_observacao, in_ativo",
    "so" => "SELECT id_so, te_desc_so, sg_so, te_so, in_mswindows",
    "software" => "SELECT id_software, id_tipo_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs FROM software INNER JOIN tipo_software ON software.id_tipo_software=tipo_software.id_tipo_software",
    "software_estacao" => "SELECT id_software, id_aquisicao, nr_patrimonio, dt_autorizacao, dt_expiracao_instalacao, id_aquisicao_particular, dt_desinstalacao, te_observacao, nr_part_destino",join
    "srcacic_action" => "SELECT",
    "srcacic_chat" => "SELECT id_srcacic_action, id_srcacic_conexao, dt_hr_mensagem, te_mensagem, cs_origem",
    "srcacic_conexao" => "SELECT id_srcacic_conexao, id_srcacic_sessao, id_usuario_cli, id_so_cli, te_node_address_cli, id_so_cli, te_documento_referencial, te_motivo_conexao, dt_hr_inicio_conexao, dt_hr_ultimo_contato",
    "srcacic_sessao" => "SELECT id_srcacic_sessao, dt_hr_inicio_sessao, nm_acesso_usuario_srv, nm_completo_usuario_srv, te_email_usuario_srv, dt_hr_ultimo_acesso",
    "srcacic_transf" => "SELECT id_srcacic_conexao, dt_systemtime, nu_duracao, te_path_origem, te_path_destino, nm_arquivo, nu_tamanho_arquivo, cs_status, cs_operacao",
    "teste" => "SELECT id_teste, id_linha",
    "tipo_licenca" => "SELECT id_tipo_licenca, te_tipo_licenca",
    "tipo_software" => "SELECT id_tipo_software, te_descricao_tipo_software",
    "usb_device" => "SELECT id_usb_device, id_usb_vendor, nm_usb_device, te_observacao FROM usb_device INNER JOIN usb_vendor ON usb_device.id_usb_vendor=usb_vendor.id_usb_vendor",
    "usb_log" => "SELECT id_usb_vendor, id_usb_device, te_observacao FROM usb_log INNER JOIN usb_vendor ON usb_log.id_usb_vendor=usb_vendor.id_usb_vendor INNER JOIN usb_device ON usb_device.id_usb_device=usb_log.id_usb_device INNER JOIN computador ON computador.id_computador=usb_log.id_computador",
    "usb_vendor" => "SELECT id_usb_vendor, nm_usb_vendor, te_observacao",
    "usuario" => "SELECT id_usuario, id_local, servidor_autenticacao, id_grupo_usuario, nm_usuario_acesso, nm_usuario_completo, te_senha, dt_login_in, te_emails_contato, te_telefones_contato"
    */
);


while ($tabela = current($tabelas)) {
    extrair(key($tabelas),$tabela);
    next($tabelas);
}
?>