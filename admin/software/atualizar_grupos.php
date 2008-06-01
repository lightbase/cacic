<?
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if ($_POST['submit']) {
  header ("Location: incluir_grupos.php");
}

include_once "../../include/library.php";
Conecta_bd_cacic();

if (isset($_POST["Submit"])) 
	{
	$id_si_grupo = $_POST['id_si_grupo']; 
	$id_software_inventariado = $_POST['id_software_inventariado'];  
	
	$query = "UPDATE softwares_inventariados 
			  SET 			
			  id_si_grupo = '".$_POST['id_si_grupo']."'
			  WHERE trim(id_software_inventariado) = '".trim($_POST['id_software_inventariado'])."'";
	mysql_query($query) or die('Update falhou ou sua sessão expirou!');		
			 					
}

$queryG = 'SELECT * FROM softwares_inventariados_grupos ORDER BY id_si_grupo';
$resultG = mysql_query($queryG);

$query = "SELECT * FROM softwares_inventariados where id_software_inventariado=".$_REQUEST["id_software_inventariado"];
$result = mysql_query($query);
$row = @mysql_fetch_array($result)
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Atualizar Grupos</title>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">

<style type="text/css">
<!--
.style2 {font-size: 12px}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
</head>
<body scroll="no" bgcolor="#FFFFFF" background="../../../imgs/linha_v.gif">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0"> 
  <tr> 
    <td class="cabecalho">Atualizar grupos</td>
  </tr>
  <tr>
    <td height="30"></td>
  </tr>
  <tr>
    <td colspan="2" class="label">Selecione:</td>
  </tr>
  <tr>
    <td class="descricao">
      <form name="form1" method="post" action="">
        <select name="id_si_grupo" id="id_si_grupo" class="normal" onChange="document.form1.submit()">
          <? while($rowG = mysql_fetch_array($resultG)) {?>
          <option value="<? echo $rowG['id_si_grupo']; ?>" <? if ($row['id_si_grupo']==$rowG['id_si_grupo']){?>selected<? }?>><? echo $rowG['nm_si_grupo']; ?></option>
          <? }?>
        </select>
        <input name="id_software_inventariado" type="hidden" id="id_software_inventariado" value="<? echo $_REQUEST["id_software_inventariado"]?>">
        <input name="numero" type="hidden" id="numero" value="<? echo $_REQUEST["numero"]?>">
        <input name="Submit" type="hidden" id="Submit">
      </form></td>
  </tr>
</table>
<p class="dado">&nbsp;</p>
<p class="dado">&nbsp;</p>
</body>
</html>
