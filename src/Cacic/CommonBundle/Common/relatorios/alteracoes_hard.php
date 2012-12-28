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
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

require_once('../include/library.php');
AntiSpy();
conecta_bd_cacic();

if ($periodo) 
	{
	$historico_hardware = true;

	if ($historico_hardware) 
		{
		$query = "SELECT 	dt_hr_alteracao as 'Data da Altera��o', 
							te_placa_rede_desc as 'Placa de Rede', 
				  			te_cpu_desc as 'CPU', 
							te_cpu_freq as 'Frequ�ncia CPU', 
							te_cpu_fabricante as 'Fab. CPU', 
				  			te_cpu_serial as 'CPU serial', 
							te_placa_mae_desc as 'Placa M�e', 
				  			te_placa_mae_fabricante as 'Fab. Placa M�e', 
							te_placa_video_desc as 'Placa de V�deo', 
				  			qt_placa_video_cores as 'Quant. cores Placa Video', 
							te_placa_video_resolucao as 'Resol. Placa V�deo', 
				  			qt_placa_video_mem as 'Mem. V�deo', 
							qt_mem_ram as 'RAM', 
							te_mem_ram_desc as 'Desc. RAM', 
				  			te_bios_desc as 'Desc. da BIOS', 
							te_bios_fabricante as 'Fab. BIOS', 
							te_placa_som_desc as 'Desc. Placa Som', 
				  			te_modem_desc as 'Desc. Modem', 
							te_cdrom_desc as 'Desc. CDROM', 
							te_teclado_desc as 'Desc. Teclado', 
				  			te_mouse_desc as 'Desc. Mouse' 
				  FROM 		historico_hardware
				  WHERE 	dt_hr_alteracao between '" . $_POST['per_inicial'] ."' AND 
				  			'" . $_POST['per_final'] . "'
				  ORDER BY 	dt_hr_alteracao";

		$result = mysql_query($query) or die ('Erro no select ou sua sess�o expirou!');
		$tipo_historico = 'de Hardware';
		}

	if ($historico_rede) 
		{
		$query = "SELECT 	dt_hr_alteracao as 'Data da Altera��o', 
							te_nome_computador as 'Nome da M�quina', 
							te_ip as 'IP', 
			  				te_mascara as 'Mascara de Rede', 
							te_gateway as 'Gateway', 
							te_wins_primario as 'Wins Prim�rio', 
							te_wins_secundario as 'Wins Secundario', 
							te_dns_primario as 'DNS Prim�rio', 
							te_dns_secundario as 'DNS Secundario', 
							te_dominio_dns as 'Dom�nio DNS', 
							te_serv_dhcp as 'Servidor DHCP' 
				  FROM 		historico_tcp_ip";
		$result = mysql_query($query) or die ('Erro no select ou sua sess�o expirou!');
		$tipo_historico = 'TCP/IP';
		}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Hist&oacute;rico <? echo $tipo_historico?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<table border="0" align="default" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Hist&oacute;rico <? echo $tipo_historico?>
      </strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p>&nbsp;</p></td>
  </tr>
</table>
<p><br>
  <? 
$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
if (mysql_num_rows($result) > 0) {
    
	echo '<table cellpadding="1" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
		 <tr bgcolor="#E1E1E1" >
		  <td nowrap align="left"><font size="2" face="Verdana, Arial">&nbsp;</font></td>';
	
	for ($i=0; $i < mysql_num_fields($result); $i++) { //Table Header
	   print '<td nowrap align="center"><b><font size="2" face="Verdana, Arial">'. mysql_field_name($result, $i) .'</font><b></td>';
	}
	echo '</tr>';

	while ($row = mysql_fetch_row($result)) { //Table body
		echo '<tr ';
		if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
		echo '>';
		echo '<td nowrap align="left"><font size="2" face="Verdana, Arial">' . $num_registro . '</font></td>';
	
		for ($i=0; $i < $fields; $i++) {
			echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 
		}
		$cor=!$cor;
		$num_registro++;
		echo '</tr>';
	}
	echo '</table>';
}
else {
	echo '</table>';
	echo mensagem('N�o foi encontrado nenhum registro');
}

}
else {
?>
</p>
<form name="form1" method="post" action="">
  <table width="16%" border="0" align="center">
    <tr> 
      <td><div align="center"></div></td>
      <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Per&iacute;odo</font></div></td>
    </tr>
    <tr> 
      <td width="26%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Inicial</font></td>
      <td width="74%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <input name="per_inicial" type="text" id="per_inicial" size="12">
        </font></td>
    </tr>
    <tr> 
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Final</font></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <input name="per_final" type="text" id="per_final" size="12">
        </font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td align="center"><input name="periodo" type="submit" id="periodo" value="   OK   "></td>
    </tr>
  </table>
  </form>
<p>&nbsp;</p>
<?
}
?>
<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> Hit&oacute;rico 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>
