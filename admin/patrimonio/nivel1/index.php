<? 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
session_start();

include_once "../../../include/library.php";

anti_spy();

if ($_POST['incluirUON1']) 
	{
  	header ("Location: incluir_nivel1.php");
	break;
	}

Conecta_bd_cacic();

$queryCONFIG = "SELECT 	id_etiqueta,
						te_etiqueta,
						te_plural_etiqueta
		  		FROM 	patrimonio_config_interface patcon
				WHERE	patcon.id_etiqueta in ('".'etiqueta1'."','".'etiqueta2'."')
		  		ORDER BY 	id_etiqueta";

$resultCONFIG 		= mysql_query($queryCONFIG);
$row 				= mysql_fetch_array($resultCONFIG);
session_register('etiqueta1');
session_register('plural_etiqueta1');
$_SESSION['etiqueta1']			= $row['te_etiqueta'];
$_SESSION['plural_etiqueta1']	= $row['te_plural_etiqueta'];

$query = 'SELECT 	id_unid_organizacional_nivel1,nm_unid_organizacional_nivel1 
		  FROM 		unid_organizacional_nivel1
		  ORDER BY 	nm_unid_organizacional_nivel1';
$result = mysql_query($query);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../include/cacic.css">
<body background="../../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<title>Cadastro de U. O. N&iacute;vel 1</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Cadastro de <? echo $_SESSION['plural_etiqueta1'];?> (U. 
      O. N&iacute;vel 1)</td>
  </tr>
  <tr> 
    <td class="descricao">M&oacute;dulo para cadastramento de Unidades Organizacionais de N&iacute;vel 1.</td>
  </tr>
</table>
<br><table width="292" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td><div align="center">

          <input name="incluirUON1" type="submit" id="incluirUON1" value="Incluir <? echo $_SESSION['etiqueta1'];?>" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>

        
      </div></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10" class="ajuda"><? echo $msg;?></td>
  </tr>

<tr>
    <td height="1" bgcolor="#333333"></td>
</tr>	
  <tr>   
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><? echo $_SESSION['plural_etiqueta1'];?></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap ><div align="left"></div></td>
          <td nowrap >&nbsp;</td>
        </tr>
<?  
if(mysql_num_rows($result)==0) {
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Nenhuma Unidade Organizacional de Nível 1 cadastrada
			</font><br><br></div>';
			
}
else {
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_nivel1.php?id_unid_organizacional_nivel1=<? echo $row['id_unid_organizacional_nivel1'];?>"><? echo $row['nm_unid_organizacional_nivel1']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap>&nbsp;</td>			  
			  <td nowrap>&nbsp;</td>			  			  
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

          <input name="incluirUON1" type="submit" id="incluirUON1" value="Incluir <? echo $_SESSION['etiqueta1'];?>" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>

        
      </div></td>
  </tr>
</table>
        </form>
<p>&nbsp;</p>
</body>
</html>