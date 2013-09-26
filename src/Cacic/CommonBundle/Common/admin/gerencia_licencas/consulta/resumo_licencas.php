<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../../include/library.php');
conecta_bd_cacic();

if ($_POST['consultar']) {
					
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../include/css/cacic.css">

<title><?php echo $oTranslator->_('Resumo quantitativo de licencas');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="../../../imgs/linha_v.gif" onLoad="SetaCampo('filtro_consulta')">
<script language="JavaScript" type="text/javascript" src="../../../include/js/cacic.js"></script>

<form action="<?php echo $PHP_SELF; ?>" method="post" name="form1">
<table width="85%" border="0" align="center">
<tr> 
      <td class="cabecalho"><?php echo $oTranslator->_('Resumo quantitativo de licencas');?></td>
</tr>
<tr>
<td><b><?php echo $oTranslator->_('Observacao');?></b> - 
   <i><u>
     <?php echo $oTranslator->_('Resumo quantitativo de licencas help');?>
   </u></i>
</td>
</tr>
<tr> 
<td>&nbsp;</td>
</tr>
</table>
<tr><td height="1" colspan="2" bgcolor="#333333"></td></tr>
<tr><td height="30" colspan="2"><table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr><td colspan="2" class="label"><?php echo $oTranslator->_('Selecione o software');?></td></tr>
<table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
<td height="1" bgcolor="#333333"></td>
</tr>
<tr> 
<td height="28"><table width="96%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td>
	<select name="filtro_consulta" id="select" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
	<?php	$query = "SELECT id_software, nm_software 
			  FROM softwares 
			  ORDER BY nm_software";
		$result = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('softwares')));
		echo '<option value=0>'.$oTranslator->_('Mostrar Todos').'</option>';
		while ($softwares=mysql_fetch_array($result)) {
			echo '<option value=' . $softwares['id_software'] . '>' . $softwares['nm_software'] . '</option>';
		}
	?>
	</select>
</td> 
            <td><input name="consultar" type="submit" id="consultar2" value="<?php echo $oTranslator->_('Consultar');?>"></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
  </table>
  </form>
<?php if ($_POST['consultar']) {

	$query = "SELECT softwares.id_software, softwares.nm_software, softwares.qt_licenca, 
		         tempTable.instalado, tempTable3.cacic   
	FROM softwares  
	LEFT JOIN (SELECT softwares_estacao.id_software AS id, count(*) as instalado 
                   FROM softwares_estacao 
	           WHERE (softwares_estacao.dt_desinstalacao IS NULL OR DATE(NOW())<dt_desinstalacao )  
		   GROUP BY softwares_estacao.id_software) AS tempTable 
	ON (softwares.id_software = tempTable.id) ";
	$query = $query . " LEFT JOIN (SELECT id_software AS id2, count(*) AS cacic 
				       FROM (SELECT softwares_inventariados.id_software 
		   			     FROM softwares_inventariados, softwares_inventariados_estacoes 
		                             WHERE (softwares_inventariados.id_software_inventariado = 
                                                    softwares_inventariados_estacoes.id_software_inventariado ) AND 
			                           (softwares_inventariados.id_software IS NOT NULL) 
		                             GROUP BY softwares_inventariados.id_software, 
                                                      softwares_inventariados_estacoes.id_computador) AS tempTable2
 				       GROUP BY id2) AS tempTable3 
        ON (softwares.id_software = tempTable3.id2)"; 
        if ($_POST['filtro_consulta'] <> 0) { 
		$query = $query . " WHERE (softwares.id_software = " . $_POST['filtro_consulta'] . ") ";
	}
	$query = $query . " ORDER BY nm_software";

	$result = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('softwares_inventariados')));
	
?> 
<p align="center" class="cabecalho"></p> 
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
	  <td align="left" nowrap bgcolor="#E1E1E1"><div align="left"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Software');?></font></strong></div></td>
	  <td nowrap >&nbsp; </td> 
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Licencas');?></font></strong></div></td>
	  <td nowrap >&nbsp; </td> 
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Autorizado');?></font></strong></div></td>
	  <td nowrap >&nbsp; </td> 
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">CACIC</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Saldo');?></font></strong></div></td>
	  <td nowrap >&nbsp; </td> 
        </tr>
        <?php  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><?php echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela">
		<?php if (($_SESSION['nm_grupo_usuarios'] == 'adm1') || ($_SESSION['nm_grupo_usuarios'] == 'saute_operador')) echo '<a href="detalhes_software.php?id_software=' . $row['id_software'] . '" target="_blank">'; ?>
		<?php echo $row['nm_software']; ?>
		<?php if ($_SESSION['nm_grupo_usuarios'] == 'adm1') echo '</a>'; ?>
	  </td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><a href="lista_aquisicoes_software.php?id_software=<?php echo $row['id_software']; ?> "target="_blank" ""><?php echo $row['qt_licenca']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><a href="lista_instalacoes_software.php?id_software=<?php echo $row['id_software']; ?> "target="_blank" ""><?php echo $row['instalado']+0; ?></a></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><a href="softwares_inventario_cacic.php?id_software=<?php echo $row['id_software']; ?> "target="_blank" ""><?php echo $row['cacic']+0; ?></a></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center">
	  <?php if ($row['qt_licenca'] < $row['cacic']) { 
		echo '<font color="red">'; }
	  ?> 
	  <?php echo $row['qt_licenca']-$row['cacic']; ?>
	  <?php if ($row['qt_licenca']-$row['cacic']) echo '</font>'; ?></a></div></td> 
          <td nowrap>&nbsp;</td>
          <?php 
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
<?php
}
?>
</body>
</html>
