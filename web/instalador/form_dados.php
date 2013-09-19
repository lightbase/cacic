<?php
$formulario =
"    <div class='row'>
        <div class='col-md-6'>
            <form name='parameters' action='recebe_dados.php' method='POST' role='form' class='form-horizontal'>
                <div class='form-group'>
                    <label for='select1' class='control-label'>Banco de dados usado</label>
                    <div>
                        <select name='banco_tipo' class='form-control'>
                            <option value='pdo_pgsql'>PostgreSQL</option>
                        </select>
                    </div>
                    <label for='servidorBanco' class='control-label'>IP ou nome do servidor do banco</label>
                    <div>
                        <input class='form-control' type='text' name='banco_host' placeholder='127.0.0.1'>
                    </div>
                    <label for='portaBanco' class='control-label'>Porta de conexão ao banco</label>
                    <div>
                        <input class='form-control' type='text' name='banco_porta' placeholder='5432'>
                    </div>
                    <label for='nomeBanco' class='control-label'>Nome do banco de dados</label>
                    <div>
                        <input class='form-control' type='text' name='banco_nome' placeholder='cacic'>
                    </div>
                    <label for='userBanco' class='control-label'>Usuário do banco</label>
                    <div>
                        <input class='form-control' type='text' name='banco_usuario' placeholder='cacic'>
                    </div>
                    <label for='passwdBanco' class='control-label'>Senha para o usuário do banco</label>
                    <div>
                        <input class='form-control' type='password' name='banco_senha' placeholder='null'>
                    </div>
                    <label for='protocolEmail' class='control-label'>Protocolo de transmissão dos e-mails</label>
                    <div>
                        <input class='form-control' type='text' name='email_protocolo' placeholder='smtp'>
                    </div>
                    <label for='servidorEmail' class='control-label'>IP ou nome do servidor do serviço de e-mail</label>
                    <div>
                        <input class='form-control' type='text' name='email_host' placeholder='127.0.0.1'>
                    </div>
                    <label for='usrEmail' class='control-label'>Usuário do e-mail</label>
                    <div>
                        <input class='form-control' type='text' name='email_usuario' placeholder='null'>
                    </div>
                    <label for='passwdUsrEmail' class='control-label'>Senha para o usuário do e-mail</label>
                    <div>
                        <input class='form-control' type='password' name='email_senha' placeholder='null'>
                    </div>
                    <label for='idioma' class='control-label'>Idioma usado dentro da aplicação</label>
                    <div>
                        <select name='idioma' class='form-control'>
                            <option value='pt_BR'>Português brasileiro</option>
                            <option value='en_US'>English</option>
                        </select>
                    </div>
                    <label for='caminhoBanco' class='control-label'>Caminho do banco de dados</label>
                    <div>
                        <input class='form-control' type='text' name='banco_path' placeholder='null'>
                    </div><br> 
                    <label>Importar dados do cacic 2.6?</label><br>
                    <label><input type='radio' name='importar' value='s'>Sim</label>
                    <label><input type='radio' name='importar' value='n' checked='true'>Não</label><br>
                    <input type='submit' class='btn btn-default' value='Continuar'><br><br>
                    <span class='label label-warning'><u>Aviso</u>: Esse Procedimento pode demorar alguns minutos!</span>
                </div>
            </form>
        </div>
        <div class='col-md-6 col-md-offset-3 pull-left tamanho'>
            <div class='infoInstall '>
                <div class='well well-lg tamanho'>
                    <div>
                        <p><b>Obs</b>: Campos deixados em branco irão utilizar o valor definido como padrão para o CACIC</p>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>";
$erro = "<span class='label label-danger'>Erro: Ocorreu um erro inesperado!</span>";
?>

<!DOCTYPE html>
<html lang='pt'>
<head>
    <meta charset='UTF-8'>
    <title>Instalação do Cacic 3.0</title>
    <link rel='stylesheet' href='css/style.css' type='text/css'>
    <link href="dist/css/bootstrap.css" rel="stylesheet">
    <link href="dist/css/bootstrap-theme.css" rel="stylesheet">
</head>
<body>
    <div id='tudo'>
        "<div class='well well-lg'>
        <?php
        if (!isset($_REQUEST["lido"])) {
            header("Location: index.php");  
        } elseif (isset($_REQUEST["erro"])) {
            echo $erro;
            echo $formulario; 
        } else {
            echo $formulario;
        }
        ?>
    </div>
</body>
</html>
