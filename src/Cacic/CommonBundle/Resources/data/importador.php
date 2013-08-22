<?php
// Conexão com o banco
$server = "127.0.0.1";
$db = "cacic";
$user = "postgres";
$pass = "w1f1t1d1";
$dbcon = new PDO("pgsql:host={$server};dbname={$db}", $user, $pass);


function importar($dbcon, $tmpdir) {
    // Cria a query padrão de inclusão de dados
    $lista_tabelas = array(
        "acao",
        "acao_excecao",
        "acao_rede",
        "acao_so",
        "aplicativo",
        "servidor_autenticacao",
        "local",
        "rede",
        "aplicativo_rede",
        "aquisicao",
        "tipo_licenca",
        "software",
        "aquisicao_item",
        "so",
        "computador",
        "descricao_coluna_computador",
        "grupo_usuario",
        "insucesso_instalacao",
        "usuario",
        "local_secundario",
        "log",
        "patrimonio",
        "patrimonio_config_interface",
        "rede_grupo_ftp",
        "rede_versao_modulo",
        "software_estacao",
        "srcacic_chat",
        "srcacic_conexao",
        "srcacic_sessao",
        "srcacic_transf",
        "teste",
        "tipo_software",
        "tipo_uorg",
        "unid_organizacional_nivel1",
        "unid_organizacional_nivel1a",
        "unid_organizacional_nivel2",
        "uorg",
        "usb_device",
        "usb_vendor",
        "usb_log",
    );

    foreach ($lista_tabelas as $tabela) {
        // Limpa as tabelas antes
        $dbcon->exec("truncate {$tabela} cascade");
    }

    foreach ($lista_tabelas as $tabela) {
        echo "Importando ".$tabela."...";
        $dbcon->exec("COPY {$tabela} FROM '{$tmpdir}/bases_cacic2/{$tabela}.csv' WITH DELIMITER AS ';' NULL AS '\N' ESCAPE '\"' ENCODING 'ISO-8859-1' CSV");
        // SELECT setval('{$tabela}', 42);
        echo " feito.\n";
    }
}


$targzfile = "/tmp/bases_cacic2_teste.tar.gz"; // Será especificado pelo usuário no futuro
// $targzfile = $_POST['fname'];
$tmpdir = sys_get_temp_dir();

echo "Iniciando importação\n";
sleep(2);
system("rm -rf ".escapeshellarg("{$tmpdir}/bases_cacic2")); // Deleta diretorio se já existir
system("tar -xzf ".escapeshellarg($targzfile)." -C ".$tmpdir);
importar($dbcon, $tmpdir);
echo "Os dados foram importados com sucesso!\n";

$dbcon = null;
?>