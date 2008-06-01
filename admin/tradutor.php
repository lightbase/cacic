<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario']))
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
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