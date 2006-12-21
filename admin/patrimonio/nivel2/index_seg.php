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

if ($_POST['incluirUON1']) 
	{
  	header ("Location: incluir_nivel1.php");
	}

include_once "../../../include/library.php";
Conecta_bd_cacic();

$queryCONFIG = "SELECT 	id_etiqueta,
						te_etiqueta
		  		FROM 	patrimonio_config_interface patcon
				WHERE	patcon.id_etiqueta in ('".'etiqueta1'."','".'etiqueta2'."')
		  		ORDER BY 	id_etiqueta";
//echo $queryCONFIG.'<BR>';				
$resultCONFIG = mysql_query($queryCONFIG);

$query = 'SELECT 	id_unid_organizacional_nivel1,nm_unid_organizacional_nivel1 
		  FROM 		unid_organizacional_nivel1
		  ORDER BY 	nm_unid_organizacional_nivel1';
$result = mysql_query($query);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<body background="../../../imgs/linha_v.gif">
<title>Cadastro de UO N&iacute;vel 2</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<table width="90%" border="0" align="center">
  <tr> 
    <td><font color="#FF0000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><b>Cadastro 
      de UO N&iacute;vel 2</b></font></td>
  </tr>
</table>
<br><table width="292" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="315" height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">UOs 
              N&iacute;vel 2</font></strong></div></td>
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
			  <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $NumRegistro; ?></font></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="../nivel1/detalhes_nivel1.php?id_unid_organizacional_nivel1=<? echo $row['id_unid_organizacional_nivel1'];?>"><? echo $row['nm_unid_organizacional_nivel1']; ?></a></font></div></td>
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
        <form name="form1" method="post" action="">
          <input name="incluirUON2" type="submit" id="incluirUON2" value="Incluir Nova U.O. N&iacute;vel 2">
        </form>
        
      </div></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
