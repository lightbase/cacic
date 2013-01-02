<?
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../include/library.php');
conecta_bd_cacic();

if ($_POST['consultar']) {
					
	$_SESSION['str_historico_estacao'] = $_POST['string_historico_estacao'];	
	$_SESSION['ftr_historico_estacao'] = $_POST['filtro_historico_estacao'];		
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="../../imgs/linha_v.gif" onLoad="SetaCampo('string_historico_estacao')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>

<?

if (($_SESSION['ftr_historico_estacao'] == 'nome') or ($_SESSION['ftr_historico_estacao'] == '')) {
	$valor_padrao_historico_estacao = '<option value="nome">Consulta por Nome</option>
			<option value="mac">Consulta por MAC address</option>';
}

if (($_SESSION['ftr_historico_estacao'] == 'mac')) {
	$valor_padrao_historico_estacao = '<option value="mac">Consulta por MAC address</option>
			<option value="nome">Consulta por Nome</option>';
}

?>  

<form action="<? echo $PHP_SELF; ?>" method="post" name="form1">
<table width="90%" border="0" align="center">
<tr> 
<td class="cabecalho">Consulta hist&oacute;rico de software por m&aacute;quina</td>
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
	<select name="filtro_historico_estacao" id="select" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
	<? echo $valor_padrao_historico_estacao;?>
	</select>
</td> 
            <td> 
              <input name="string_historico_estacao" type="text" id="string_historico_estacao2" value="<? echo $_REQUEST['string_historico_estacao'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
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

	if ($_SESSION['ftr_historico_estacao'] == 'nome') {
		$query = "SELECT s.nm_software_inventariado, h.dt_hr_inclusao, h.dt_hr_ult_coleta 
		FROM softwares_inventariados s, historicos_software h, computadores c   
		WHERE (h.te_node_address = c.te_node_address) AND 
		(c.te_nome_computador = '". $_SESSION['str_historico_estacao'] ."') 
		AND (s.id_software_inventariado = h.id_software_inventariado) 
		ORDER BY s.nm_software_inventariado"; 

		$queryAntigo = "SELECT s.nm_software_inventariado, hc.dt_hr_inclusao, hc.dt_hr_ult_coleta 
		FROM softwares_inventariados s, historicos_software_completo hc, computadores c   
		WHERE (hc.te_node_address = c.te_node_address) AND 
		(c.te_nome_computador = '". $_SESSION['str_historico_estacao'] ."') 
		AND (s.id_software_inventariado = hc.id_software_inventariado) 
		ORDER BY s.nm_software_inventariado"; 
	}

	if ($_SESSION['ftr_historico_estacao'] == 'mac') {
		$query = "SELECT s.nm_software_inventariado, h.dt_hr_inclusao, h.dt_hr_ult_coleta 
		FROM softwares_inventariados s, historicos_software h 
		WHERE (h.te_node_address = '" . $_SESSION['str_historico_estacao'] . "') AND
		(s.id_software_inventariado = h.id_software_inventariado)
		ORDER BY s.nm_software_inventariado";

		$queryAntigo = "SELECT s.nm_software_inventariado, hc.dt_hr_inclusao, hc.dt_hr_ult_coleta 
		FROM softwares_inventariados s, historicos_software hc 
		WHERE (hc.te_node_address = '" . $_SESSION['str_historico_estacao'] . "') AND
		(s.id_software_inventariado = hc.id_software_inventariado)
		ORDER BY s.nm_software_inventariado";
	}

	$result = mysql_query($query) or die('Erro no select ou sua sessão expirou!');
	$resultAntigo = mysql_query($queryAntigo) or die('Erro no select ou sua sessão expirou!');
	
	if (strlen($_SESSION['str_historico_estacao']) < 3) {
		echo $mensagem = mensagem('Digite pelo menos 03 caracteres...');
		}
		else
		{
			if(($nu_reg= mysql_num_rows($result))==0){
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
	  <td align="left" nowrap bgcolor="#E1E1E1"><div align="left"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Software Inventariado</font></strong></div></td>
	  <td nowrap >&nbsp; </td> 
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Data Inclus&atilde;o</font></strong></div></td>
	  <td nowrap >&nbsp; </td> 
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Data &Uacute;ltima Coleta</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><? echo $row['nm_software_inventariado']; ?></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo date("d/m/Y H:i", strtotime($row['dt_hr_inclusao'])); ?></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo date("d/m/Y H:i", strtotime($row['dt_hr_ult_coleta'])); ?></div></td>
          <td nowrap>&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
}
?>		
  <tr> 
    <td colspan=9 height="3" bgcolor="#333333"></td>
  </tr>
<?	while($row = mysql_fetch_array($resultAntigo)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><? echo $row['nm_software_inventariado']; ?></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo date("d/m/Y H:i", strtotime($row['dt_hr_inclusao'])); ?></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo date("d/m/Y H:i", strtotime($row['dt_hr_ult_coleta'])); ?></div></td>
          <td nowrap>&nbsp;</td>
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
