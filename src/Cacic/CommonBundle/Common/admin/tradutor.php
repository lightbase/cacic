<?php
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario']))
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
   define('CACIC',1);
}

require_once('../include/library.php');
?>
<html>
 <body>
   <h2><?php echo $oTranslator->_('kciq_mnt_tradutor');?><h2>
<?php
   $oTranslator->translatorGUI();
?>