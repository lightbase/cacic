<?php
$formulario =
"<form name='parameters' action='recebe_dados.php' method='POST'>
<p title='Qual o tipo de banco que será usado (Somente PostgreSQL por enquanto).'>
    <select name='banco_tipo'>
        <option value='pdo_pgsql'>PostgreSQL</option>
    </select> Tipo</p>
<p title='IP ou nome do servidor do banco de dados.'>
    <input type='text' name='banco_host'> Servidor</p>
<p title='Porta de conexão ao banco de dados.'>
    <input type='text' name='banco_porta'> Porta</p>
<p title='Nome do banco de dados pré-existente.'>
    <input type='text' name='banco_nome'> Nome do banco de dados</p>
<p title='Nome do usuário para ser usado pelo CACIC para conectar ao banco de dados'>
    <input type='text' name='banco_usuario'> Usuário do banco</p>
<p title='Senha para o usuário do banco de dados'>
    <input type='password' name='banco_senha'> Senha do banco</p>
<p title='Protocolo de transmissão usado no correio eletronico'>
    <input type='text' name='email_protocolo'> Protocolo de e-mail</p>
<p title='IP ou nome do servidor do serviço de e-mail.'>
    <input type='text' name='email_host'> Servidor de e-mail</p>
<p title='Nome de usuário do e-mail'>
    <input type='text' name='email_usuario'> Usuário do e-mail</p>
<p title='Senha para o usuário do e-mail'>
    <input type='password' name='email_senha'> Senha de usuário do e-mail</p>
<p title='Idioma usado dentro da aplicação'>
    <select name='idioma'>
        <option value='pt_BR'>Português brasileiro</option>
        <option value='en_US'>English</option>
    </select> Idioma</p>
<p title='Caminho do banco de dados'>
    <input type='text' name='banco_path'> Caminho do banco de dados</p>
<p title='Senha do usuario postgres(super usuário do banco de dados)'>
    <input type='password' name='banco_senha_root'> Senha do usuário postgres</p>
<p title='Se marcado, redireciona o usuário para a tela de importação de dados da versão anterior do gerente'>
    <label><input type='radio' name='importar' value='s'>Sim</label>
    <label><input type='radio' name='importar' value='n' checked='true'>Não</label> |  Importar dados do cacic 2.6?</p>
<input type='submit' value='Continuar'>
</form>"
?>

<!DOCTYPE html>
<html lang='pt'>
<head>
    <meta charset='UTF-8'>
    <title>Instalação do Cacic 3.0</title>
    <link rel='stylesheet' href='firsttime' type='text/css'>
</head>
<body>
    <div id='tudo'>
        <?php
        if (!isset($_POST["lido"])) {
            header("Location: index.php");  
        } else {
            echo $formulario;
        }
        ?>
    </div>
</body>
</html>
