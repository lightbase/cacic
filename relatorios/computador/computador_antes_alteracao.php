<?
session_start();
/*
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php');
conecta_bd_cacic();
$query = "SELECT * FROM computadores, so
		  WHERE te_node_address = '". $_GET['te_node_address'] ."' AND
		  		computadores.id_so = ". $_GET['id_so'] ." AND 
		  		computadores.id_so = so.id_so";

$result = mysql_query($query) or die('Erro na consulta à tabela "computadores"');
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
			  <td height="1"></td>
			  <td height="1"></td>
			  <td height="1"></td>
          </tr>';	  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Detalhes do Computador</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script LANGUAGE="JavaScript">
<!-- Begin
function open_window(theURL,winName,features) { 
    window.open(theURL,winName,features);
}
//-->
</script>
<style type="text/css">
<!--
a {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: normal;
	color: #333333;
	text-decoration: none;
}
a:link {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: normal;
	color: #333333;
	text-decoration: none;
}
a:hover {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: normal;
	color: #FF9900;
	text-decoration: none;
}
-->
</style>
</head>
<body bgcolor="#FFFFFF" leftmargin="2" topmargin="10" marginwidth="0" marginheight="0">
<?
/*
<table border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"> <div align="center"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"> 
        <b><br>
        &nbsp; Detalhes do computador <? echo mysql_result($result, 0, "te_nome_computador");?></b></font></div></td>
  </tr>
</table>
<br>
<table width="102%" border="0" cellspacing="2" cellpadding="0">
  <tr> 
    <td><table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
        <tr> 
          <td colspan="5"> </td>
        </tr>
        <tr> 
          <td colspan="5" height="1" bgcolor="#333333"></td>
        </tr>
        <tr> 
          <td colspan="5" bgcolor="#E1E1E1"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <strong>&nbsp; Informa&ccedil;&otilde;es B&aacute;sicas</strong></font> 
          </td>
        </tr>
        <tr> 
          <td colspan="5" height="1" bgcolor="#333333"></td>
        </tr>
        <?
    /* Obtenho os limites para os alertas vermelho e amarelo */     								
    	$query_config = "SELECT nu_horas_vermelho,	nu_horas_amarelo FROM configuracoes limit 1";
    	$resul_config 	= mysql_query($query_config) or die ('Erro na consulta à tabela "configuracoes".');   
    	$rowconfig 			= mysql_fetch_array($resul_config);
								
								
			$date_time=strtotime(date("Y/m/d H:i", strtotime(mysql_result($result, 0, "dt_hr_ult_acesso"))));
			$date = getdate(); 
			$date_time_now = $date['year']."-".$date['mon']."-".$date['mday']." ".$date['hours'];
			$date_time_now = strtotime($date_time_now);
			$dif_hour=(($date_time_now - $date_time)/3600);
			if ($dif_hour > $rowconfig['nu_horas_vermelho'])
				$img_date = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/cacic2/imgs/alerta_vermelho.gif" title="Último acesso realizado há mais de 48 horas" width="8" height="8">';
			else if($dif_hour > $rowconfig['nu_horas_amarelo'])
				$img_date = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/cacic2/imgs/alerta_amarelo.gif" title="Último acesso realizado há mais de 24 horas" width="8" height="8">';
			else
				$img_date = '';
		?>
        <tr> 
          <td width="1%">&nbsp;</td>
          <td width="21%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Nome 
            do Computador:</font></td>
          <td width="28%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo mysql_result($result, 0, "te_nome_computador");?> 
            </font></td>
          <td width="25%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Vers&atilde;o 
            do CACIC:</font></td>
          <td width="25%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo mysql_result($result, 0, "te_versao_cacic"); ?> 
            </font></td>
        </tr>
        <? echo $linha?> 
        <tr> 
          <td><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></p></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Endere&ccedil;o 
            TCP/IP:</font></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo mysql_result($result, 0, "te_ip"); ?> 
            </font></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Data/Hora 
            Inclus&atilde;o:</font></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <?	echo date("d/m/Y H:i", strtotime(mysql_result($result, 0, "dt_hr_inclusao"))); ?>
            </font></td>
        </tr>
        <? echo $linha?> 
        <tr> 
          <td><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></p></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Sistema 
            Operacional:</font></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo mysql_result($result, 0, "te_desc_so"); ?> 
            </font></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Data/Hora 
            &Uacute;ltimo Acesso:</font></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo date("d/m/Y H:i", strtotime(mysql_result($result, 0, "dt_hr_ult_acesso"))). ' ' .$img_date; ?> 
            </font></td>
        </tr>
        <? echo $linha?> 
      </table></td>
  </tr>
  <tr> 
    <td>
      <? require_once('inc_tcp_ip.php'); ?>
    </td>
  </tr>
  <tr> 
    <td>
      <? require_once('inc_hardware.php'); ?>
    </td>
  </tr>
  <tr> 
    <td>
      <? require_once('inc_software.php'); ?>
    </td>
  </tr>
  <tr>
    <td><? require_once('inc_patrimonio.php'); ?></td>
  </tr>
  <tr> 
    <td>
      <? require_once('inc_officescan.php'); ?>
    </td>
  </tr>
  <tr> 
    <td>
      <? require_once('inc_compartilhamento.php'); ?>
    </td>
  </tr>
  <tr> 
    <td>
      <? require_once('inc_unidades_disco.php'); ?>
    </td>
  </tr>
  <tr> 
    <td>
      <? require_once('inc_ferramentas.php'); ?>
    </td>
  </tr>
  <tr> 
    <td>
      <? require_once('inc_opcoes_administrativas.php');?>
    </td>
  </tr>
  
  <tr> 
    <td>&nbsp; </td>
  </tr>
</table>
</body>
</html>
