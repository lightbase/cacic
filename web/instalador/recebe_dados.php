<?php
//Recebe os dados do formulario ou usa o padrão em caso de formulario em branco

$parameters["database_driver"] = $_POST["banco_tipo"];

if ($_POST["banco_host"] == "") {
    $parameters["database_host"] = "127.0.0.1";
} else {
    $parameters["database_host"] = $_POST["banco_host"];
}
if ($_POST["banco_porta"] == "") {
    $parameters["database_port"] = "5432";
} else {
    $parameters["database_port"] = $_POST["banco_porta"];
}
if ($_POST["banco_nome"] == "") {
    $parameters["database_name"] = "cacic";
} else {
    $parameters["database_name"] = $_POST["banco_nome"];
}
if ($_POST["banco_usuario"] == "") {
    $parameters["database_user"] = "cacic";
} else {
    $parameters["database_user"] = $_POST["banco_usuario"];
}
if ($_POST["banco_senha"] == "") {
    $parameters["database_password"] = "null";
} else {
    $parameters["database_password"] = $_POST["banco_senha"];
}
if ($_POST["email_protocolo"] == "") {
    $parameters["mailer_transport"] = "smtp";
} else {
    $parameters["mailer_transport"] = $_POST["email_protocolo"];
}
if ($_POST["email_host"] == "") {
    $parameters["mailer_host"] = "127.0.0.1";
} else {
    $parameters["mailer_host"] = $_POST["email_host"];
}
if ($_POST["email_usuario"] == "") {
    $parameters["mailer_user"] = "null";
} else {
    $parameters["mailer_user"] = $_POST["email_usuario"];
}
if ($_POST["email_senha"] == "") {
    $parameters["mailer_password"] = "null";
} else {
    $parameters["mailer_password"] = $_POST["email_senha"];
}
if ($_POST["idioma"] == "") {
    $parameters["locale"] = "pt_BR";
} else {
    $parameters["locale"] = $_POST["idioma"];
}

$parameters["secret"] = "d7c123f25645010985ca27c1015bc76797";

if ($_POST["banco_path"] == "") {
    $parameters["database_path"] = "null";
} else {
    $parameters["database_path"] = $_POST["banco_path"];
}

$importar = $_POST["importar"];

file_put_contents("instalacao.log", $importar);
$file = "../../app/config/parameters.yml";
file_put_contents($file, "parameters:\n");
foreach ($parameters as $key => $dado) {
    $linha = "    ".$key.": ".$dado."\n";
    file_put_contents($file, $linha, FILE_APPEND);
}

if (file_exists($file)) {
    header("Location: verifica_libs.php");
} else {
    header("Location: form_dados.php?lido=s&erro=s");
}
?>
