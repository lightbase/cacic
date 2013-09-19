<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Verificação de bibliotecas</title>
    <link rel="stylesheet" href="firsttime" type="text/css">
</head>
<body>
    <div id="tudo">
<?php

require_once 'System.php';
$check = "ok";

function getconfig() {
    $file = "../../app/config/parameters.yml";
    $parameters = file_get_contents($file);
    $linhas = explode("\n", $parameters);
    foreach ($linhas as $linha) {
        $dado = explode(": ", $linha);
        $config[] = $dado[1];
    }
    return $config;
}

$config = @getconfig();

$dependencias = array(
    'pgsql',
    'PDO',
    'pdo_pgsql',
    'mcrypt',
    'zip'
);


function checkgit() {
    exec("which git", $git);
    return $git[0];
}

function psqlversion($config) {
    $dbconn = pg_connect("user={$config[5]} host={$config[2]} dbname={$config[4]} port={$config[3]} password={$config[6]}")
        or die("Erro: Não foi possivel conectar-se com o banco de dados<br><br><a href='form_dados.php?lido=y'>Voltar</a>");
    $version = pg_version($dbconn);
    return $version['client'];
}


if (!is_file(@checkgit())) {
    echo "O programa de versionamento <b>git</b> não foi encontrado. Instale e tente novamente.<br>";
    $check = "bad";
}

if (!class_exists('System')) {
    echo "A biblioteca <b>pear</b> não foi encontrada. Instale e tente novamente.<br>";
    $check = "bad";
}

if (version_compare(psqlversion($config), '9.1', '<')) {
    echo "A versão minima do <b>Postgresql</b> é a <b>9.1</b>, atualmente você está com a versão <b>".psqlversion($config)."</b>. Atualize e tente novamente.<br>";
    $check = "bad";
}

if (version_compare(phpversion(), '5.3.10', '<')) {
    echo "A versão minima do <b>PHP</b> é a <b>5.3.10</b>, atualmente você está com a versão <b>".phpversion()."</b>. Atualize e tente novamente.<br>";
    $check = "bad";
}

foreach ($dependencias as $biblioteca) {
    if (!extension_loaded($biblioteca)) {
        echo "A biblioteca <b>{$biblioteca}</b> não foi encontrada. Instale e tente novamente.<br>";
        $check = "bad";
    }
}

if ($check == "ok") {
    $importar = file_get_contents("log_instalacao.txt");
    copy("default_htaccess", "../.htaccess");
    chdir("../..");
    exec("echo \"<?php \$finish='n' ?>\" > web/instalador/finalizado.php
          COMPOSER_HOME='/path/you/want/to/be/home' php composer.phar install > web/instalador/log_instalacao.txt 2>&1 &&
          php app/console assets:install --symlink >> web/instalador/log_instalacao.txt 2>&1 &&
          php app/console assetic:dump --force >> web/instalador/log_instalacao.txt 2>&1 &&
          php app/console doctrine:schema:update --force >> web/instalador/log_instalacao.txt 2>&1 &&
          php app/console doctrine:fixtures:load >> web/instalador/log_instalacao.txt 2>&1 &&
          echo \"<?php \$finish='s' ?>\" > web/instalador/finalizado.php");
    include_once "web/instalador/finalizado.php";
    if ($finish == "s") {
        if ($importar == "s") {
            header("Location: http://$config[2]/cacic/migracao/cacic26");
        } else {
            header("Location: http://$config[2]/cacic");
        }
    }
}
?>
    </div>
</body>
</html>