<?php
session_start();
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
