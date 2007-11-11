<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

$password = md5('123456');
$challenge = $_SESSION['challenge'];
if(md5($password.$challenge)==$_POST['challenge'])
	{
  	echo 'Senha Correta';
	}
else
	{
  	echo 'Acesso negado!';
	}
?>
