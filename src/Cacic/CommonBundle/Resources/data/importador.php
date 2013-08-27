<?php
// Conexão com o banco
$server = "127.0.0.1";
$db = "cacictest";
$user = "postgres";
$pass = "w1f1t1d1";
$dbcon = new PDO("pgsql:host={$server};dbname={$db}", $user, $pass);

$targzfile = "/tmp/bases_cacic2_teste.tar.gz";
// $targzfile = $_POST['fname'];

$tmpdir = sys_get_temp_dir();

// Deleta diretorio temporario se já existir
system("rm -rf ".escapeshellarg("{$tmpdir}/bases_cacic2"));


function importar($dbcon, $tmpdir) {
    // Cria a query padrão de inclusão de dados
    $lista_tabelas = array(
        // "acao",
        // "acao_excecao",
        // "acao_rede",
        // "acao_so",
        "so",
        "aplicativo",
        "servidor_autenticacao",
        "local",
        "rede",
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
        // "usb_device",
        // "usb_log",
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


// SELECT setval('{$tabela}', {$id}); (alterar a sequencia)


// Execuções
echo "Iniciando importação\n";

// Extrai os arquivos necessarios para a importação
system("tar -xzf ".escapeshellarg($targzfile)." -C ".$tmpdir);

// Importa os dados para o postgres
$dbcon->exec("begin");
importar($dbcon, $tmpdir);
$dbcon->exec("end");

echo "Os dados foram importados com sucesso!\n";

system("rm -rf ".escapeshellarg("{$tmpdir}/bases_cacic2"));

// Fecha conexão com o banco
$dbcon = null;
?>
