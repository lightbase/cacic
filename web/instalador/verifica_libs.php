<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Verificação de bibliotecas</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link href="dist/css/bootstrap.css" rel="stylesheet">
    <link href="dist/css/bootstrap-theme.css" rel="stylesheet">
</head>
<body>
    <div id="tudo">
        <div class="well well-lg">
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
        or die("<span class='label label-danger'>Erro: Não foi possivel conectar-se com o banco de dados</span><br><br><a href='form_dados.php?lido=y'>Voltar</a>");
    $version = pg_version($dbconn);
    return $version['client'];
}


if (is_file(@checkgit())) {
    echo "<span class='label label-danger'>O programa de versionamento <u>git</u> não foi encontrado. Instale-o e tente novamente.</span><br>";
    $check = "bad";
}

if (!version_compare(psqlversion($config), '9.1', '<')) {
    echo "<span class='label label-danger'>A versão minima do <u>Postgresql</u> é a <u>9.1</u>, atualmente você está com a versão <u>".psqlversion($config)."</u>. Atualize-o e tente novamente.</span><br>";
    $check = "bad";
}

if (!version_compare(phpversion(), '5.3.10', '<')) {
    echo "<span class='label label-danger'>A versão minima do <u>PHP</u> é a <u>5.3.10</u>, atualmente você está com a versão <u>".phpversion()."</u>. Atualize-o e tente novamente.</span><br>";
    $check = "bad";
}

if (class_exists('System')) {
    echo "<span class='label label-danger'>A biblioteca <u>pear</u> não foi encontrada. instale-a e tente novamente.</span><br>";
    $check = "bad";
}

foreach ($dependencias as $biblioteca) {
    if (extension_loaded($biblioteca)) {
        echo "<span class='label label-danger'>A biblioteca <u>{$biblioteca}</u> não foi encontrada. instale-a e tente novamente.</span><br>";
        $check = "bad";
    }
}

if ($check == "ok") {
    $importar = file_get_contents("instalacao.log");
    $composerhome = getcwd()."/../";
    copy("default_htaccess", "../.htaccess");
    chdir("../..");
    exec("echo 'n' > web/instalador/finalizado.txt
          COMPOSER_HOME='$composerhome' php composer.phar install > web/instalador/instalacao.log 2>&1 &&
          php app/console assets:install --symlink >> web/instalador/instalacao.log 2>&1 &&
          php app/console assetic:dump --force >> web/instalador/instalacao.log 2>&1 &&
          php app/console doctrine:schema:update --force >> web/instalador/instalacao.log 2>&1 &&
          php app/console doctrine:fixtures:load >> web/instalador/instalacao.log 2>&1 &&
          echo 's' > web/instalador/finalizado.txt");
    $finish = file_get_contents("web/instalador/finalizado.txt");
    if ($finish == "s") {
        if ($importar == "s") {
            header("Location: http://$config[2]/cacic/migracao/cacic26");
        } else {
            header("Location: http://$config[2]/cacic");
        }
    } else {
        exec("echo '\nErro: Ocorreu um erro inesperado!\n' >> web/instalador/instalacao.log");
        echo "<span class='label label-danger'><u>Erro</u>: Ocorreu um erro inesperado!</span>";
    }
}
?>
        </div>
    </div>
</body>
</html>
