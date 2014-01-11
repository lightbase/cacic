<!DOCTYPE html>
<html lang='pt'>
<head>
    <meta charset='UTF-8'>
    <title>Instalação do Cacic 3.0</title>
    <link rel='shortcut icon' href='img/logo-bits.png'>
    <link rel='stylesheet' href='css/style.css'>
    <link rel='stylesheet' href='dist/css/bootstrap.css'>
    <link rel='stylesheet' href='dist/css/bootstrap-theme.css'>
</head>
<body>
    <div id='tudo'>
        <div class='well well-lg'>
<?php

require_once "System.php";
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


$dependencias = array(
    "pgsql",
    "PDO",
    "pdo_pgsql",
    "mcrypt",
    "zip"
);


function checkgit() {
    exec("which git", $git);
    return $git[0];
}


function psqlversion($config) {
    $dbconn = pg_connect("user={$config[5]} host={$config[2]} dbname={$config[4]} port={$config[3]} password={$config[6]}")
        or die("<span class='label label-danger'>Erro: Não foi possivel conectar-se com o banco de dados</span><br><br><a class='btn btn-default' href='form_dados.php?lido=y'>Voltar</a>");
    $version = pg_version($dbconn);
    return $version["client"];
}


$config = @getconfig();

if (!version_compare(phpversion(), "5.3", ">")) {
    echo "<span class='label label-danger'>A versão minima do <u>PHP</u> é a <u>5.3</u>, atualmente você está com a versão <u>".phpversion()."</u>. Atualize-o e tente novamente.</span><br>";
    $check = "bad";
}

if (!version_compare(phpversion(), "5.3.7", ">")) {
    echo "<span class='label label-warning'>A sua versão atual do <u>PHP</u> é a <u>".phpversion()."</u>. Recomendamos o uso da versão <u>5.3.7</u> ou superior.</span><br>";
    $check = "warn";
}

if (!version_compare(psqlversion($config), "9.1", ">")) {
    echo "<span class='label label-danger'>A versão minima do <u>Postgresql</u> é a <u>9.1</u>, atualmente você está com a versão <u>".psqlversion($config)."</u>. Atualize-o e tente novamente.</span><br>";
    $check = "bad";
}

if (!is_file(@checkgit())) {
    echo "<span class='label label-danger'>O programa de versionamento <u>git</u> não foi encontrado. Instale-o e tente novamente.</span><br>";
    $check = "bad";
}

if (!is_file("/usr/bin/java")) {
    echo "<span class='label label-danger'>Não foi encontrada nenhuma <u>JVM</u> (Java Virtual Machine) em seu computador. Instale-a e tente novamente.</span><br>";
    $check = "bad";
}

if (!is_file("/usr/sbin/proftpd")) {
    echo "<span class='label label-warning'>Apos a instalação é necessária a configuração do servidor ftp, recomenda-se a instalação do <u>PROFTPD</u></span><br>";
    $check = "warn";   
}

if (!class_exists("System")) {
    echo "<span class='label label-danger'>A biblioteca <u>pear</u> não foi encontrada. instale-a e tente novamente.</span><br>";
    $check = "bad";
}

foreach ($dependencias as $biblioteca) {
    if (!extension_loaded($biblioteca)) {
        echo "<span class='label label-danger'>A biblioteca <u>{$biblioteca}</u> não foi encontrada. instale-a e tente novamente.</span><br>";
        $check = "bad";
    }
}

if ($check == "ok") {
    header("Location: instala_synfony.php");
} elseif ($check == "warn") {
    echo "<br><a href='instala_synfony.php' class='btn btn-default' onclick='this.disabled=true;this.value=\"Instalando...\";this.form.submit();'>Ignorar aviso(s) e instalar</a>";
}
echo "<br><a href='form_dados.php?lido=y' class='btn btn-default'>Voltar</a>";
?>
        </div>
    </div>
</body>
</html>