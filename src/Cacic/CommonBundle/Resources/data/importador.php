<?php
// Conexão com o banco
$server = "127.0.0.1";
$db = "cacic";
$user = "postgres";
$pass = "w1f1t1d1";
$dbcon = new PDO("pgsql:host={$server};dbname={$db}", $user, $pass);


function importar($dbcon, $tmpdir){
    // Cria a query padrão de inclusão de dados
    $lista_tabelas = array(
        "acao",
        "acao_excecao",
        "acao_rede",
        "acao_so",
        "aplicativo",
        "aplicativo_rede",
        "aquisicao",
        "aquisicao_item",
        "computador",
        "descricao_coluna_computador",
        "grupo_usuario",
        "insucesso_instalacao",
        "local",
        "local_secundario",
        "log",
        "patrimonio",
        "patrimonio_config_interface",
        "rede",
        "rede_grupo_ftp",
        "rede_versao_modulo",
        "servidor_autenticacao",
        "so",
        "software",
        "software_estacao",
        "srcacic_chat",
        "srcacic_conexao",
        "srcacic_sessao",
        "srcacic_transf",
        "teste",
        "tipo_licenca",
        "tipo_software",
        "tipo_uorg",
        "unid_organizacional_nivel1",
        "unid_organizacional_nivel1a",
        "unid_organizacional_nivel2",
        "uorg",
        "usb_device",
        "usb_log",
        "usb_vendor",
        "usuario"
    );

    foreach ($lista_tabelas as $tabela){
        echo "Importando ".$tabela."...";
        $dbcon->exec("\COPY {$tabela} FROM '{$tmpdir}/bases_cacic2/{$tabela}.csv' WITH DELIMITER AS ';' NULL AS '\N' ESCAPE '\"' CSV");
        echo " feito.\n";
    }
}


$tmpdir = sys_get_temp_dir();
$targzfile = $tmpdir."/bases_cacic2_19-08-2013_17:01:26.tar.gz"; // Será especificado pelo usuário no futuro
// $targzfile = $_POST['fname'];

echo "Iniciando importação\n";
system("tar - xmzf ".escapeshellarg($targzfile)." -C ".$tmpdir);
importar($dbcon, $tmpdir);
// system("rm -r ".escapeshellarg("{$tmpdir}/bases_cacic2"));
echo "Os dados foram importados com sucesso!\n";

$dbcon = null;
?>