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

function psqlversion() {
    $dbconn = pg_connect("user=postgres host=localhost dbname=postgres port=5432")
        or die("Erro: Não foi possivel conectar-se com o banco de dados<br><br><a href=''>Voltar</a>");
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

if (version_compare(psqlversion(), '9.1', '<')) {
    echo "A versão minima do <b>Postgresql</b> é a <b>9.1</b>, atualmente você está com a versão <b>".psqlversion()."</b>. Atualize e tente novamente.<br>";
    $check = "bad";
}

if (version_compare(phpversion(), '5.3.17', '<')) {
    echo "A versão minima do <b>PHP</b> é a <b>5.3.17</b>, atualmente você está com a versão <b>".phpversion()."</b>. Atualize e tente novamente.<br>";
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
    system("php composer.phar install > web/instalador/log_instalacao.txt &&
            php app/console assets:install --symlink >> web/instalador/log_instalacao.txt &&
            php app/console assetic:dump --force >> web/instalador/log_instalacao.txt &&
            php app/console doctrine:schema:update --force >> web/instalador/log_instalacao.txt &&
            php app/console doctrine:fixtures:load >> web/instalador/log_instalacao.txt");
    echo file_get_contents("web/instalador/log_instalacao.txt");

    if ($importar == "s") {
        echo "<a href='http://teste.cacic.cc/'>Continuar</a>";
    } else {
        echo "<a href='http://teste.cacic.cc/migracao/cacic26'>Continuar</a>";
    }
}
?>

    </div>
</body>
</html>