<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../include/library.php');

conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';

?>

<?php if ($_SESSION["nm_grupo_usuarios"] <> "adm1")
	die("<h1><font color='red'>".$oTranslator->_('Acesso nao autorizado')."</font></h1>
	     <h3>".$oTranslator->_('Sua tentativa foi registrada no log')."</h3>
	     <b>".$oTranslator->_('Nome').":</b> " . $_SESSION["nm_usuario"] );
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $oTranslator->_('ADMIN - Excluir historico de software');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF">
    	<font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
    		<strong>
    		<?php echo $oTranslator->_('ADMIN - Excluir historico de software');?>
    		</strong>
    	</font>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    <?php echo $oTranslator->_('Gerado em');?> <?php echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br><?php

$mensagemErro = '';
$v_apaguei = 'sim';
	
$query_Insert = 'INSERT INTO historicos_software_completo(id_computador,   id_software_inventariado,  dt_hr_inclusao,   dt_hr_ult_coleta) 
					 SELECT 								  h.id_computador, h.id_software_inventariado,h.dt_hr_inclusao, h.dt_hr_ult_coleta 
			 		 FROM 		historicos_software h,(SELECT id_computador, MAX(dt_hr_ult_coleta) AS ULT_COLETA 
				      									FROM historicos_software
														GROUP BY id_computador) AS tableUltColeta 
					 WHERE (tableUltColeta.id_computador = h.id_computador) AND 
							(h.dt_hr_ult_coleta < DATE_SUB(tableUltColeta.ULT_COLETA, 
						INTERVAL 1 day))';

$result_query_Insert = mysql_query($query_Insert);

$query_Delete = 'DELETE historicos_software 
		 FROM historicos_software, 
		      (SELECT id_computador, MAX(dt_hr_ult_coleta) AS ULT_COLETA 
		       FROM historicos_software 
		       GROUP BY id_computador) AS tableUltColeta  
		 WHERE (tableUltColeta.id_computador = historicos_software.id_computador) AND
			(historicos_software.dt_hr_ult_coleta < DATE_SUB(tableUltColeta.ULT_COLETA, INTERVAL 1 day))';

$result_query_Delete = mysql_query($query_Delete);

?>
<table width="62%" border="0" align="center">
  <tr> 
    <td><div align="center"></div>
      <table width="98%" border="0" align="center">
        <tr valign="top"> 
          <td nowrap > <table width="100%" border="0" align="center">
              <?php if ($v_apaguei=='')
 		{
		echo '<tr><td colspan="2" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><B>' . $mensagemErro . '</B></td></tr>';
		} else {
		echo '<tr><td colspan="2" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><B>'.$oTranslator->_('Historio de softwares excluidos com sucesso da base de dados').'</B></td></tr>';
		}

?>
        </table></td>
        </tr>
      </table>
 
 </tr>  
</table>
<p align="center">
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?php echo $oTranslator->_('Gerado por');?> 
	<strong>CACIC</strong>
	 - Configurador Autom&aacute;tico e Coletor de Informa&ccedil;&otilde;es Computacionais
	</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    Software desenvolvido pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo
  </font></p>
</body>
</html>
