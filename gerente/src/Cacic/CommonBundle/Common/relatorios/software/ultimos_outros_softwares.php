<?
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

//Mostrar computadores com nomes repetidos na base
require_once('../../include/library.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de &Uacute;ltimos Softwares Identificados</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" topmargin="5"  background="../../imgs/linha_v.gif">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de &Uacute;ltimos Softwares Suspeitos e Jogos Identificados</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y �\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?
conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';
?>
<?
	 $query = "SELECT a.te_nome_computador as nm_maquina, b.te_node_address, b.id_so, a.id_ip_rede, 
			b.dt_hr_inclusao, c.nm_software_inventariado, b.dt_hr_ult_coleta  
		FROM computadores a, historicos_outros_softwares b, softwares_inventariados c 
		WHERE (a.te_node_address = b.te_node_address) AND (a.id_so = b.id_so) AND 
			(b.id_software_inventariado = c.id_software_inventariado) AND 
			(b.dt_hr_inclusao > DATE_SUB(NOW(), INTERVAL 5 DAY))  
		ORDER BY dt_hr_inclusao DESC, c.nm_software_inventariado 
		LIMIT 300"; 
	$result = mysql_query($query) or die('Erro no select ou sua sess�o expirou!');
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td align="center" nowrap></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="5" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nome
              da M&aacute;quina</font></strong></div></td>
	  <td nowrap >&nbsp;&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Software</font></strong></div></td>
	  <td nowrap >&nbsp;&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Data Identifica&ccedil;&atilde;o</font></strong></div></td>
	  <td nowrap >&nbsp;&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&Uacute;ltima Coleta</font></strong></div></td>
	  <td nowrap >&nbsp;&nbsp;</td>

        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td wrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $NumRegistro; ?></font></div></td>
          <td wrap>&nbsp;&nbsp;</td>
          <td wrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="../../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['nm_maquina']; ?></div></td>
	  <td nowrap>&nbsp;&nbsp;</td>
	  <td align="left" wrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['nm_software_inventariado']; ?></font></td>
	  <td nowrap>&nbsp;&nbsp;</td>
	  <td align="center" wrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo date("d/m/Y H:i", strtotime($row['dt_hr_inclusao'])); ?></font></td>
	  <td nowrap>&nbsp;&nbsp;</td>
	  <td align="center" wrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo date("d/m/Y H:i", strtotime($row['dt_hr_ult_coleta'])); ?></font></td>
	  <td nowrap>&nbsp;&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
	}
?>
      </table></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="10"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Clique 
      sobre o nome da m&aacute;quina para ver os detalhes</font> </td>
  </tr>
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
