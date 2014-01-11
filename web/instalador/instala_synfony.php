<?php

$importar = file_get_contents("importar.tmp");
unlink("importar.tmp");
$composerhome = getcwd()."/../";
chdir("../..");
exec("echo '=== Iniciando Instalação do Cacic 3.0 ===' >> web/instalador/instalacao.log &&
      COMPOSER_HOME='$composerhome' php composer.phar install >> web/instalador/instalacao.log 2>&1 &&
      php app/console assets:install --symlink >> web/instalador/instalacao.log 2>&1 &&
      php app/console assetic:dump --force >> web/instalador/instalacao.log 2>&1 &&
      php app/console doctrine:schema:update --force >> web/instalador/instalacao.log 2>&1 &&
      php app/console doctrine:fixtures:load >> web/instalador/instalacao.log 2>&1 &&
      php app/console cache:clear --env=prod --no-debug >> web/instalador/instalacao.log 3>&1 &&
      php app/console assetic:dump --env=prod --no-debug >> web/instalador/instalacao.log 3>&1
    ");

copy("web/instalador/default_htaccess", "web/.htaccess");
$index = file_get_contents("http://localhost/cacic/");
$htmllinha = explode("\n", $index);
$checkoverride = "  <title>Index of /cacic</title>";
if (!extension_loaded("mod_rewrite")||$htmllinha[3] == $checkoverride) {
    header("Location: ../app.php");
} else {
    if ($importar == "s") {
        header("Location: ../migracao/cacic26");
    } else {
        header("Location: ..");
    }
}
?>
