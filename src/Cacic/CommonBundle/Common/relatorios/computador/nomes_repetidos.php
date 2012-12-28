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

$titulo = $oTranslator->_('Relatorio de Maquinas com Nome Repetido');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$titulo;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<link href="<?=CACIC_URL?>/include/cacic.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>
       <?=$titulo;?></strong></font>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
        <?php echo $oTranslator->_('Gerado em') . ' ' . date("d/m/Y �\s H:i"); ?></font></p>
    </td>
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
	 $query = "SELECT a.te_nome_computador as nm_maquina, 
                          count(*) as qtde
		FROM computadores a 
		GROUP BY a.te_nome_computador HAVING count(*) > 1 
		ORDER BY qtde desc,a.te_nome_computador"; 
	$result = mysql_query($query) or die($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('computadores')));
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td align="center" nowrap></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="6" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap class="cabecalho_tabela">&nbsp;&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela"></td>
          <td align="center"  nowrap class="cabecalho_tabela">&nbsp;&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela" bgcolor="#E1E1E1"><div align="center"><?=$oTranslator->_('Nome da Maquina');?></div></td>
          <td nowrap class="cabecalho_tabela" >&nbsp;&nbsp;</td>
	  <td nowrap class="cabecalho_tabela" ><div align="center"><?=$oTranslator->_('Registros');?></div></td>
	  <td nowrap class="cabecalho_tabela" >&nbsp;&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap class="dado_med_sem_fundo">&nbsp;&nbsp;</td>
          <td nowrap class="dado_med_sem_fundo"><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap class="dado_med_sem_fundo">&nbsp;&nbsp;</td>
          <td nowrap class="dado_med_sem_fundo"><div align="left"><a href="rel_nomes_repetidos.php?te_nome_computador=<? echo $row['nm_maquina'];?>" target="_blank"><? echo $row['nm_maquina']; ?></div></td>
          <td nowrap class="dado_med_sem_fundo">&nbsp;&nbsp;</td>
	  <td align="center" nowrap class="dado_med_sem_fundo"><? echo $row['qtde']; ?></td>
	  <td nowrap class="dado_med_sem_fundo">&nbsp;&nbsp;</td>
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
    <td height="10">
       <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
         <?=$oTranslator->_('Clique sobre o nome da maquina para ver os detalhes');?>
       </font>
    </td>
  </tr>
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?=$oTranslator->_('Gerado por');?> - <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
