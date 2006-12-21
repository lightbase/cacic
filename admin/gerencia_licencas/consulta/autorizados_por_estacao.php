<?
session_start();
require_once('../../../include/library.php');
conecta_bd_cacic();

if ($_POST['consultar']) {
					
	$_SESSION['str_autorizado_estacao'] = $_POST['string_autorizado_estacao'];	
	$_SESSION['ftr_autorizado_estacao'] = $_POST['filtro_autorizado_estacao'];		
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../include/cacic.css">

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="../../../imgs/linha_v.gif" onLoad="SetaCampo('string_autorizado_estacao')">
<script language="JavaScript" type="text/javascript" src="../../../include/cacic.js"></script>

<?

if (($_SESSION['ftr_autorizado_estacao'] == 'nome') or ($_SESSION['ftr_autorizado_estacao'] == '')) {
	$valor_padrao_autorizado_estacao = '<option value="nome">Nome do Computador</option>
			<option value="patrimonio">Número do Patrim&ocirc;nio</option>';
}

if ($_SESSION['ftr_autorizado_estacao'] == 'patrimonio') {
	$valor_padrao_autorizado_estacao = '<option value="patrimonio">Número do Patrim&ocirc;nio</option>
			<option value="nome">Nome do Computador</option>';
}

?>  

<form action="<? echo $PHP_SELF; ?>" method="post" name="form1">
<table width="90%" border="0" align="center">
<tr> 
<td class="cabecalho">Licenças autorizadas por esta&ccedil;&atilde;o</td>
</tr>
<tr> 
<td>&nbsp;</td>
</tr>
</table>
<tr><td height="1" colspan="2" bgcolor="#333333"></td></tr>
<tr><td height="30" colspan="2"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr><td colspan="2" class="label">Selecione os filtros da consulta:</td></tr>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
<td height="1" bgcolor="#333333"></td>
</tr>
<tr> 
<td height="28"><table width="96%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td>
	<select name="filtro_autorizado_estacao" id="select" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
	<? echo $valor_padrao_autorizado_estacao;?>
	</select>
</td> 
            <td> 
              <input name="string_autorizado_estacao" type="text" id="string_autorizado_estacao2" value="<? echo $_REQUEST['string_autorizado_estacao'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
              </td>
            <td><input name="consultar" type="submit" id="consultar2" value="Consultar"></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
  </table>
  </form>
<?

if ($_POST['consultar']) {

	if ($_SESSION['ftr_autorizado_estacao'] == 'nome') {
		$query = "SELECT s.nm_software, se.dt_autorizacao, se.nr_processo, se.id_aquisicao_particular, se.nm_computador, se.nr_patrimonio, se.id_aquisicao_particular, se.te_observacao  
			  FROM softwares_estacao se, softwares s  
			  WHERE (nm_computador LIKE  '%" . $_SESSION['str_autorizado_estacao'] . "%') AND 
				(s.id_software = se.id_software) AND 
				(se.dt_desinstalacao IS NULL)  
			  ORDER BY nm_software";

		$queryDesinstaladoTransferido = "SELECT s.nm_software, se.dt_autorizacao, se.nr_processo, se.id_aquisicao_particular, se.nm_computador, se.nr_patrimonio, se.te_observacao, se.nr_patr_destino, se.dt_desinstalacao 
						FROM softwares_estacao se, softwares s 
						WHERE (nm_computador = '" . $_SESSION['str_autorizado_estacao'] . "') AND 
						      (s.id_software = se.id_software) AND 
						      (se.dt_desinstalacao IS NOT NULL) 
						ORDER BY nm_software"; 

	}

	if ($_SESSION['ftr_autorizado_estacao'] == 'patrimonio') {
		$query = "SELECT s.nm_software, se.dt_autorizacao, se.nr_processo, se.id_aquisicao_particular, se.nm_computador, se.nr_patrimonio, se.id_aquisicao_particular, se.te_observacao    
			  FROM softwares_estacao se, softwares s  
			  WHERE (nr_patrimonio = '" . $_SESSION['str_autorizado_estacao'] . "') AND 
				(s.id_software = se.id_software) AND 
				(se.dt_desinstalacao IS NULL)  
			  ORDER BY nm_software";

		$queryDesinstaladoTransferido = "SELECT s.nm_software, se.dt_autorizacao, se.nr_processo, se.id_aquisicao_particular, se.nm_computador, se.nr_patrimonio, se.te_observacao, se.nr_patr_destino, se.dt_desinstalacao     
			  FROM softwares_estacao se, softwares s  
			  WHERE (nr_patrimonio = '" . $_SESSION['str_autorizado_estacao'] . "') AND 
				(s.id_software = se.id_software) AND 
				(se.dt_desinstalacao IS NOT NULL)  
			  ORDER BY nm_software";
	}

	$result = mysql_query($query) or die('Erro no select');
	$resultDesinstaladoTransferido = mysql_query($queryDesinstaladoTransferido) or die('Erro no select');
	
	if (strlen($_SESSION['str_autorizado_estacao']) < 3) {
		echo $mensagem = mensagem('Digite pelo menos 03 caracteres...');
		}
		else
		{
			if(($nu_reg= mysql_num_rows($result))<0){
			echo $mensagem = mensagem('Nenhum registro encontrado!');
				}
				else
				{

?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>

  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1">
	  <td align="center" nowrap>&nbsp;</td>
	  <td align="center" nowrap><div align="left"><strong></strong></div></td>
	  <td align="center" nowrap >&nbsp; </td>
	  <td align="left" nowrap bgcolor="#E1E1E1"><div align="left"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Software</font></strong></div></td>
	  <td nowrap >&nbsp; </td> 
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Autoriza&ccedil;&atilde;o</font></strong></div></td>
	  <td nowrap >&nbsp; </td> 
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Processo</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Computador</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Patrim&ocirc;nio</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Particular</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Observa&ccedil;&atilde;o</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		if ($NumRegistro == 1) { echo '<tr><td colspan=16 align=center>Autorizados</td></tr>'; }		  
		/*
	 ?>
        <tr <? if (!$Cor) { echo 'bgcolor="#00CC00"'; } else echo 'bgcolor="#99FF33"' ?>> 
		*/
		?>
        <tr <? if (!$Cor) { echo 'bgcolor="#c0CoC0"'; } else echo 'bgcolor="#000O00"' ?>> 		
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><? echo $row['nm_software']; ?></td>
          <td nowrap>&nbsp;</td>
          <? if ($row['dt_autorizacao']) { ?>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo date("d/m/Y", strtotime($row['dt_autorizacao'])); ?></a></div></td>
          <? } else { ?>
	  <td nowrap class="opcao_tabela"><div align="center">&nbsp;</a></div></td>
          <? } ?>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo $row['nr_processo']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo $row['nm_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo $row['nr_patrimonio']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center">
		<? if ($row['id_aquisicao_particular'])  
			echo "<a href='softwares_aquisicao.php?id_aquisicao=" . $row['id_aquisicao_particular'] . "' target='_blank'>SIM</a>"; 
                   else  echo "N&Atilde;O"; 
		?> 
	  </div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo $row['te_observacao']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
}
//	if ($NumRegistro == 1) { echo '<tr bgcolor="#00CC00"><td colspan=17 align=center>N&atilde;o h&aacute; autoriza&ccedil;&atilde;o para esta m&aacute;quina</td><tr>'; }
	if ($NumRegistro == 1) { echo '<tr bgcolor="#C0C0C0"><td colspan=17 align=center><b>N&atilde;o h&aacute; autoriza&ccedil;&atilde;o para esta m&aacute;quina</b></td><tr>'; }	
?>
	<tr>
	 <td colspan=17 height="3" bgcolor="#333333"></td>
	</tr>
	<? $NumRegistroAutorizados = $NumRegistro; ?>
<?	while ($row = mysql_fetch_array($resultDesinstaladoTransferido)) { 
	if ($NumRegistroAutorizados == $NumRegistro) {
	  echo '<tr><td colspan=16 align=center>Hist&oacute;rico</td></tr>';
        } ?>
        <tr <? 
		
//		if ($Cor) { echo 'bgcolor="#FF9900"'; } else echo 'bgcolor="#FFCC00"'; 
		if ($Cor) { echo 'bgcolor="#C0C0C0"'; } else echo 'bgcolor="#000000"'; 		
		?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><? echo $row['nm_software']; ?></td>
          <td nowrap>&nbsp;</td>
          <? if ($row['dt_autorizacao']) { ?>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo date("d/m/Y", strtotime($row['dt_autorizacao'])); ?></a></div></td>
          <? } else { ?>
	  <td nowrap class="opcao_tabela"><div align="center">&nbsp;</a></div></td>
          <? } ?>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo $row['nr_processo']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo $row['nm_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo $row['nr_patrimonio']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center">
		<? if ($row['id_aquisicao_particular'])  
			echo "<a href='softwares_aquisicao.php?id_aquisicao=" . $row['id_aquisicao_particular'] . "' target='_blank'>SIM</a>"; 
                   else  echo "N&Atilde;O"; 
		?> 
	  </div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo date("d/m/Y", strtotime($row['dt_desinstalacao'])) . ' -> ' . $row['nr_patr_destino'] . ' -> ' . $row['te_observacao']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
}	
	if ($NumRegistroAutorizados == $NumRegistro) {
//	  echo '<tr bgcolor="#FF9900"><td colspan=17 align=center>N&atilde;o h&aacute; hist&oacute;rico desta m&aacute;quina</td><tr>';
	  echo '<tr bgcolor="#C0C0C0"><td colspan=17 align=center><b>N&atilde;o h&aacute; hist&oacute;rico desta m&aacute;quina</b></td><tr>';	  
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
</table>
<?
				}
		}
}
?>
</body>
</html>
