<? 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

if ($_POST['submit']) {
  header ("Location: incluir_grupos.php");
}

include_once "../../include/library.php";
Conecta_bd_cacic();


if ($_REQUEST['Excluir']) 
	{
	$query = "DELETE FROM softwares_inventariados_grupos WHERE id_si_grupo = '".$_POST['id_si_grupo']."'";
	mysql_query($query) or die('Delete falhou ou sua sess�o expirou!');
	$query = "DELETE FROM acoes_redes WHERE id_ip_rede = '".$_GET['id_ip_rede']."'";
	mysql_query($query) or die('Delete falhou ou sua sess�o expirou!');	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/software/index_grupos.php&tempo=1");									
	}

$query = 'SELECT * FROM softwares_inventariados_grupos ORDER BY id_si_grupo';
$result = mysql_query($query);



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Cadastro de Grupos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Cadastro de Grupos</td>
  </tr>
  <tr> 
    <td class="descricao">Neste m&oacute;dulo dever&atilde;o ser cadastradas todos os grupos de Softwares cadastrados no Cacic.</td>
  </tr>
</table>
<br><table width="261" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td><div align="center">

          <input name="submit" type="submit" id="submit" value="  Incluir Novo Grupo  ">

        
      </div></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10"><? echo $msg;?></td>
  </tr>

  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table width="454" border="0" align="center" cellpadding="2" cellspacing="0" bordercolor="#333333">
        <tr bgcolor="#E1E1E1"> 
          <td width="17" align="center"  nowrap>&nbsp;</td>
          <td width="18" align="center"  nowrap>&nbsp;</td>
          <td width="12" align="center"  nowrap>&nbsp;</td>
          <td width="100" align="center"  nowrap class="cabecalho_tabela"><div align="center">C&oacute;digo</div></td>
          <td width="9" nowrap >&nbsp;</td>
          <td width="159" nowrap  class="cabecalho_tabela"><div align="left">Grupo</div></td>
          <td width="11" nowrap >&nbsp;</td>
          <td width="96" nowrap >&nbsp;</td>
        </tr>
<?  
if(@mysql_num_rows($result)==0) {
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Nenhum grupo cadastrado
			</font><br><br></div>';
			
}
else {
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		$query_Cont = 'SELECT count(*) as numero FROM softwares_inventariados_grupos G,softwares_inventariados S where G.id_si_grupo=S.id_si_grupo and G.id_si_grupo='.$row['id_si_grupo'];
		$result_Cont = mysql_query($query_Cont);
		$linha = mysql_fetch_array($result_Cont);
		  
	 ?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="center"><a href="detalhes_grupos.php?id_si_grupo=<? echo $row['id_si_grupo'];?>"><? echo $row['id_si_grupo']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_grupos.php?id_si_grupo=<? echo $row['id_si_grupo'];?>"><? echo $row['nm_si_grupo']; ?></a> [<? echo $linha['numero']?>] </div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap><div align="center">
			  <? if ($linha['numero'] == 0){?>
			  	<a href="index_grupos.php?Excluir=1&id_si_grupo=<? echo $row['id_si_grupo']?>" onClick="return Confirma('Confirma Exclus�o do Grupo?');"><img src="excluir.jpg" width="16" height="16" border="0"></a>
			  <? }else{?>	
			  		<img src="excluir_cinza.jpg" width="16" height="16" border="0">
			  <? }?>
			  </div></td>
			  <? 
		$Cor=!$Cor;
		$NumRegistro++;
	}
}
?>
      </table></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10"><? echo $msg;?></td>
  </tr>
  <tr> 
    <td><div align="center">

          <input name="submit" type="submit" id="submit" value="  Incluir Novo Grupo  ">

        
      </div></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
</html>
