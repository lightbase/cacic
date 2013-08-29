<?php
// Conexão com o banco
$server = "127.0.0.1";
$db = "cacic";
$user = "postgres";
// $pass = "";
$dbcon = new PDO("pgsql:host={$server};dbname={$db}", $user/*, $pass*/);

$targzfile = "/tmp/bases_cacic2_teste.tar.gz";
// $targzfile = $_POST['fname'];

$tmpdir = sys_get_temp_dir();

function importar($dbcon, $tmpdir) {
    // Cria a query padrão de inclusão de dados
    $lista_tabelas = array(
        "acao",
        "servidor_autenticacao",
        "local",
        "rede",
        "acao_excecao",
        "acao_rede",
        "so",
        "acao_so",
        "aplicativo",
        "aplicativo_rede",
        "aquisicao",
        "tipo_licenca",
        "software",
        "aquisicao_item",
        "computador",
        "descricao_coluna_computador",
        "grupo_usuario",
        "insucesso_instalacao",
        "usuario",
        "local_secundario",
        "log",
        "unid_organizacional_nivel1",
        "unid_organizacional_nivel1a",
        "unid_organizacional_nivel2",
        "patrimonio",
        "patrimonio_config_interface",
        // "rede_grupo_ftp",
        "rede_versao_modulo",
        "software_estacao",
        "srcacic_chat",
        "srcacic_conexao",
        "srcacic_sessao",
        "srcacic_transf",
        "teste",
        "tipo_software",
        "tipo_uorg",
        "uorg",
        "usb_vendor",
        "usb_device",
        // "usb_log"
    );

    foreach ($lista_tabelas as $tabela) {
        // Limpa as tabelas antes
        $dbcon->exec("truncate {$tabela} cascade");
    }

    foreach ($lista_tabelas as $tabela) {
        echo "Importando ".$tabela."...";
        $dbcon->exec("COPY {$tabela} FROM '{$tmpdir}/bases_cacic2/{$tabela}.csv' WITH DELIMITER AS ';' NULL AS '\N' ESCAPE '\"' ENCODING 'ISO-8859-1' CSV");
        echo " feito.\n";
    }
}

function atualizar_seq($dbcon){
    $lista_sequencias = array(
        "aplicativo_id_aplicativo_seq",
        "aquisicao_id_aquisicao_seq",
        "class_property_id_class_property_seq",
        "class_property_type_id_class_property_type_seq",
        "classe_id_class_seq",
        "collect_def_class_id_collect_def_class_seq",
        "computador_coleta_historico_id_computador_coleta_historico_seq",
        "computador_coleta_id_computador_coleta_seq",
        "computador_id_computador_seq",
        "grupo_usuario_id_grupo_usuario_seq",
        "insucesso_instalacao_id_insucesso_instalacao_seq",
        "local_id_local_seq",
        "log_id_log_seq",
        "patrimonio_id_patrimonio_seq",
        "rede_grupo_ftp_id_ftp_seq",
        "rede_id_rede_seq",
        "rede_versao_modulo_id_rede_versao_modulo_seq",
        "servidor_autenticacao_id_servidor_autenticacao_seq",
        "so_id_so_seq",
        "software_id_software_seq",
        "srcacic_action_id_srcacic_action_seq",
        "srcacic_chat_id_srcacic_chat_seq",
        "srcacic_conexao_id_srcacic_conexao_seq",
        "srcacic_sessao_id_srcacic_sessao_seq",
        "srcacic_transf_id_srcacic_transf_seq",
        "teste_id_transacao_seq",
        "tipo_licenca_id_tipo_licenca_seq",
        "tipo_software_id_tipo_software_seq",
        "tipo_uorg_id_tipo_uorg_seq",
        "unid_organizacional_nivel1_id_unid_organizacional_nivel1_seq",
        "unid_organizacional_nivel1a_id_unid_organizacional_nivel1a_seq",
        "unid_organizacional_nivel2_id_unid_organizacional_nivel2_seq",
        "uorg_id_uorg_seq",
        "usb_log_id_usb_log_seq",
        "usuario_id_usuario_seq"
    );

    foreach ($lista_sequencias as $tabela) {
        $dbcon->exec("SELECT nextval('{$tabela}')");
    }
}




// Execuções
echo "Iniciando importação\n";

// Extrai os arquivos necessarios para a importação
system("tar -xzf ".escapeshellarg($targzfile)." -C ".$tmpdir);

// Importa os dados para o postgres
$dbcon->exec("begin");
importar($dbcon, $tmpdir);
atualizar_seq($dbcon);
$dbcon->exec("end");

echo "Os dados foram importados com sucesso!\n";

system("rm -rf ".escapeshellarg("{$tmpdir}/bases_cacic2"));

// Fecha conexão com o banco
$dbcon = null;
?>