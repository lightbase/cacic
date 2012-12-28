<?
// Este arquivo eh chamado pelo classificao_software.php 
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if($_POST['submit']) {
	$_SESSION["list6"] = $_POST['list6']; //Softwares selecionados
}

require_once('../include/library.php');

conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$oTranslator->_('Classificacao de software');?></title>
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
    			CACIC - <?=$oTranslator->_('Classificacao de software');?>
    		</strong>
    	</font>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    	<?=$oTranslator->_('Gerado em');?> <? echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br><?

$mensagemErro = '';
$v_atualizei = '';
if (count($_SESSION["list6"]) == 0) {
	$mensagemErro = $oTranslator->_('Selecione pelo menos 1 software.').'<BR>';
	if ($_POST['tiponovo'] == '') {
		$mensagemErro = $mensagemErro . $oTranslator->_('Informe a classificacao para o software.').'<BR>';
	}
} else
	if ($_POST['tiponovo'] == '') {
		$mensagemErro = $oTranslator->_('Informe a classificacao para o software.').'<BR>'; 
	}
	else {
		//Pego os aplicativos selecionados para o relatório
		$v_atualizei = 'sim';
		$aplicativos_selecionados = $_SESSION["list6"][0] ;
		for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) {
			$aplicativos_selecionados = $aplicativos_selecionados .", ". $_SESSION["list6"][$i] ;
		}
	
		// Exibir as aplicações	 baseado no que foi solicitado	
		$query_select = 'UPDATE softwares_inventariados
				 SET id_tipo_software = ' . $_POST['tiponovo'] . '
				 WHERE id_software_inventariado IN (' .$aplicativos_selecionados.')';
							 
		$result_query_selecao = mysql_query($query_select);
	}

?>
<table width="62%" border="0" align="center">
  <tr> 
    <td><div align="center"></div>
      <table width="98%" border="0" align="center">
        <tr valign="top"> 
          <td nowrap > <table width="78%" border="0" align="center">
              <? 
		  while($reg_selecao = @mysql_fetch_row($result_query_selecao))
		{
	 		$reg_id_aplicativo = $reg_selecao[0]; 	
			// Exibir informações sobre a quantidade de máquinas e os softwares inventariados
			$query_aplicativo = 
			"SELECT DISTINCT a.nm_software_inventariado, COUNT(a.id_software_inventariado) as total_equip, a.id_software_inventariado
			FROM softwares_inventariados a, softwares_inventariados_estacoes b, computadores c
			WHERE 
			b.te_node_address  = c.te_node_address AND 
			b.id_so = c.id_so AND 
			a.id_software_inventariado = ".$reg_id_aplicativo. " AND 
			a.id_software_inventariado = b.id_software_inventariado 
			GROUP BY a.nm_software_inventariado
			ORDER BY a.nm_software_inventariado";

			$result_query_versoes = mysql_query($query_aplicativo);
			while($reg_versoes = @mysql_fetch_row($result_query_versoes)) 
				{ 
						$v_atualizei = '.';
						?>
              			<tr> 
		                <td nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="../inventario_softwares/rel_maquinas_softwares.php?id_software_inventariado=<? echo $reg_versoes[2]?>&nm_software_inventariado=<? echo $reg_versoes[0]?>" target="_blank"><? echo $reg_versoes[0] ?></a></font></td>
		                <td nowrap><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $reg_versoes[1] ?></font></div></td>
		              	</tr>	  
		              	<?
						echo $linha; 
		 				} //Fim do if else das versões
 				 	} //Fim do while

	 	if ($v_atualizei=='')
 			{
			echo '<tr><td colspan="2" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><B>' . $mensagemErro . '</B></td></tr>';
			} else {
			echo '<tr><td colspan="2" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><B>'.$oTranslator->_('Software(s) classificado(s) com sucesso!').'</B></td></tr>';
		}

?>
        </table></td>
        </tr>
      </table>
 </tr>  
</table>
<p align="center">
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?=$oTranslator->_('Gerado por');?>
	<strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor de Informa&ccedil;&otilde;es Computacionais
  </font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  Software desenvolvido pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo</font></p>	
</body>
</html>
