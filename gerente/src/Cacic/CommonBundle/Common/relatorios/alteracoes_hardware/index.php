<?php
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

// Essa variável é usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if($_POST['submitGerarRelatorio']) 
	{
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list6"] = $_POST['list6'];
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12'];		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];

	// Aqui eu inverto as datas para YYYYMMDD
	$v_elementos = explode("/",$_POST['date_input1']);
	$v_data_ini = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];	
 	$_SESSION["data_ini"] = $v_data_ini;
	$v_elementos = explode("/",$_POST['date_input2']);
	$v_data_fim = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];
 	$_SESSION["data_fim"] = $v_data_fim;
//	}
 
	require_once('../../include/library.php');
	AntiSpy();
	conecta_bd_cacic();
	
	$redes_selecionadas = '';
	if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
		{
		//if($_SESSION["cs_situacao"] == 'S') 
			//{
			// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
			$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
			for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
				$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";
	
			$query_redes = 'AND id_rede IN ('. $redes_selecionadas .')';
			//}	
		}
	else
		{
		// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
		$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
			$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";
	
		$query_redes = 'AND comp.id_rede = redes.id_rede AND 
							redes.id_local IN ('. $locais_selecionados .') AND
							redes.id_local = locais.id_local ';
		$select = ' ,sg_local as Local ';	
		$from = ' ,redes,locais ';			
		}
	
	// Aqui pego todos os SO selecionados
	$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
		$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
	
	// Aqui pego todas as configurações de hardware que deseja exibir
	for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
		$campos_hardware = $campos_hardware . $_SESSION["list6"][$i];
	
	// Aqui substitui todas as strings \ por vazio que a variável $campos_hardware retorna
	$campos_hardware = str_replace('\\', '', $campos_hardware);
	
	// Aqui inclui o "hist." devido à origem das informações sobre o hardware ser a tabela de históricos
	$campos_hardware = str_replace(', ', ', hist.', $campos_hardware);
	
	if ($_GET['orderby']) 
		$orderby = $_GET['orderby'];
	else 
		$orderby = '1';
	
			
	   $query =  "SELECT 
				  distinct 		comp.te_nome_computador,
								comp.id_so, 
								comp.te_node_address, 
								comp.id_computador " . 								
								$campos_hardware .
								$select . " 
				  FROM 			historico_hardware hist, 
								computadores comp ".
								$from . " 
				  WHERE 		DATE_FORMAT(hist.dt_hr_alteracao, '%Y%m%d') >= DATE_FORMAT('" . $_SESSION["data_ini"] . "', '%Y%m%d') AND 
								DATE_FORMAT(hist.dt_hr_alteracao, '%Y%m%d') <= DATE_FORMAT('" . $_SESSION["data_fim"] . "', '%Y%m%d') AND 
								comp.te_node_address = hist.te_node_address AND 
								comp.id_so = hist.id_so ".
								$query_redes. " 
				  ORDER BY 		$orderby ";
	//echo $query . '<br>';
		$result = mysql_query($query) or die ('Erro no select ou sua sessão expirou!');
	
	if (mysql_num_rows($result) > 0)
		{
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html>
		<head>
		<title>Relat&oacute;rio de Altera&ccedil;&otilde;es de Hardware</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script language="JavaScript" type="text/JavaScript">
		<!--
		function MM_openBrWindow(theURL,winName,features) 
			{
			window.open(theURL,winName,features); //v2.0
			}
		//-->
		</script>
		</head>
		
		<body bgcolor="#FFFFFF" topmargin="5">
		<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
		  <tr bgcolor="#E1E1E1"> 
			<td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
			<td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
			<td bgcolor="#FFFFFF">&nbsp;</td>
		  </tr>
		  <tr bgcolor="#E1E1E1"> 
			<td nowrap bgcolor="#FFFFFF"><div align="center"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
				- Relat&oacute;rio de Altera&ccedil;&otilde;es de Hardware</strong></font></div></td>
		  </tr>
		  <tr> 
			<td height="1" bgcolor="#333333"></td>
		  </tr>
		  <tr> 
			<td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
				em <?php echo date("d/m/Y à\s H:i"); ?></font></p></td>
		  </tr>
		</table>
		<br>
		<br>
		<br>
		<br>
		
		<?php
		$cor = 0;
		$num_registro = 1;
		
		$fields=mysql_num_fields($result);
		echo '<table align="center" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
			 <tr bgcolor="#E1E1E1" >
			  <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';
		
		for ($i=2; $i < $fields; $i++) //Table Header
			print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($i + 1) . '">'. mysql_field_name($result, $i) .'</a></font><b></td>';
		
		echo '</tr>';
		
		
		while ($row = mysql_fetch_row($result)) //Table body
			{ 
			echo '<tr ';
			if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
			echo '>';
			echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>';
			echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?id_computador=". $row[3]."' target='_blank'>" . $row[2] ."</a>&nbsp;</td>"; 
			for ($i=3; $i < $fields; $i++) 
				echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 
		
			$cor=!$cor;
			$num_registro++;
			echo '</tr>';
			}
		echo '</table>';
		echo '<br><br>';
		if (count($_SESSION["list8"])>0)
			{
			$v_opcao = 'alteracoes_hardware'; // Nome do pie que será chamado por tabela_estatisticas
			$query_redes .= " AND (DATE_FORMAT(a.dt_hr_alteracao, '%Y%m%d') >= DATE_FORMAT('".$v_data_ini."', '%Y%m%d')) 
						AND (DATE_FORMAT(a.dt_hr_alteracao, '%Y%m%d') <= DATE_FORMAT('".$v_data_fim."', '%Y%m%d')) ";
			// Os sinais -=- acima são propositais em substituição aos "'" e sofrerão replace no pie.
			require_once('../../include/tabela_estatisticas.php');
			}
		?>
		</p>
		<p></p>
		<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
		  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
		  de Informa&ccedil;&otilde;es Computacionais</font><br>
		  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
		  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
		</body>
		</html>    
		<?php		
		}
	else
		{
		header ("Location: ../../include/nenhum_registro_encontrado.php?chamador=../relatorios/alteracoes_hardware/index.php&tempo=3");						
		}
	}
else
	{
	$id_acao = 'cs_coleta_hardware';
	require_once('../../include/inicio_relatorios_inc.php');
	
	$historical_data_help = $oTranslator->_("Dados historicos obtidos de versoes anteriores a 2.4");
	?>
	 <script src="../../include/js/sniffer.js" type="text/javascript" language="javascript"></script>
	 <script src="../../include/js/dyncalendar.js" type="text/javascript" language="javascript"></script>
	 <link href="../../include/css/dyncalendar.css" media="screen" rel="stylesheet">
	
	<table width="85%" border="0" align="center">
	  <tr> 
		<td class="cabecalho">
		  <?php echo $oTranslator->_('Relatorio de alteracao de hardware'); ?>
		</td>
	  </tr>
	  <tr> 
		<td class="descricao">
		  <?php echo $oTranslator->_('Exibe as alteracoes nas configuracoes de hardware dos computadores.'); ?>
		</td>
	  </tr>
	</table>
	<form method="post" ENCTYPE="multipart/form-data" name="forma"   onsubmit="return valida_form()">
	  <table width="85%" border="0" align="center" cellpadding="5" cellspacing="1">
		<tr>
		  <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
			  <tr> 
				<td class="label" colspan="2">
				  <?php echo $oTranslator->_('Selecione o periodo em que devera ser realizada a consulta:') ?>            </td>
			  </tr>
			  <tr> 
				<td height="1" bgcolor="#333333" colspan="2"></td>
			  </tr>
			  <tr valign="middle"> 
				<td width="33%" height="1" nowrap valign="middle"> <input name="date_input1" type="text" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?php echo $date_input1;?>"> 
				  <script type="text/javascript" language="JavaScript">
		<!--
		function calendar1Callback(date, month, year)	
			{
			document.forms['forma'].date_input1.value = date + '/' + month + '/' + year;
			}
		calendar1 = new dynCalendar('calendar1', 'calendar1Callback');
		-->
		</script> &nbsp; <font size="2" face="Verdana, Arial, Helvetica, sans-serif">a</font> 
				  &nbsp;&nbsp;                 
                  <input name="date_input2" type="text" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?php echo $date_input2;?>"> 

				  <script type="text/javascript" language="JavaScript">
		<!--
		function calendar2Callback(date, month, year)	
			{
			document.forms['forma'].date_input2.value = date + '/' + month + '/' + year;
			}
		calendar2 = new dynCalendar('calendar2', 'calendar2Callback');
		-->
		</script> </td>
				<td align="left" class="descricao"><?php echo $oTranslator->_('formato:'); ?> dd/mm/aaaa</td>
			  </tr>
			  <tr> 
				<td height="1" bgcolor="#333333" colspan="2"></td>
			  </tr>
			  <tr> 
				<td colspan="2">
				 <input type="checkbox" class="checkbox" name="historical_data" value="historical_data"
						onchange="toggleDetails('hardware_type');"
						title="<?php echo $historical_data_help;?>" />
				<b title="<?php echo $historical_data_help;?>"><?php echo $oTranslator->_('Mostrar tambem dados historicos?'); ?></b></td>
			  </tr>
			  <tr> 
				<td height="1" bgcolor="#333333" colspan="2"></td>
			  </tr>
			</table>
		  </td>
		</tr>
		<tr> 
		  <td valign="top">
			<div id='hardware_type'  style="display: none;">
			<table width="85%" border="0" cellpadding="0" cellspacing="1">
			  <tr> 
				<td class="label">
				   <?php echo $oTranslator->_('Selecione os tipos de hardware a serem exibidos no relatorio.'); ?>
				</td>
			  </tr>
			  <tr> 
				<td height="1" bgcolor="#333333"></td>
			  </tr>
			  <tr> 
				<td>
				 <input type="checkbox" class="checkbox" name="historical_data_only" value="historical_data_only"
						title="<?php echo $historical_data_help;?>" />
				 <b title="<?php echo $historical_data_help;?>">
					<?php echo $oTranslator->_('Mostrar somente dados historicos?'); ?>
				 </b>
				</td>
			  </tr> 
			  <tr> 
				<td height="1"><table border="0" cellpadding="0" cellspacing="0">
					<tr> 
					  <td>&nbsp;&nbsp;</td>
					  <td class="cabecalho_tabela"><div align="left">Dispon&iacute;veis:</div></td>
					  <td>&nbsp;&nbsp;</td>
					  <td width="40">&nbsp;</td>
					  <td nowrap>&nbsp;&nbsp;</td>
					  <td nowrap class="cabecalho_tabela">Selecionados:</td>
					  <td nowrap>&nbsp;&nbsp;</td>
					</tr>
					<tr> 
					  <td>&nbsp;</td>
					  <td> <div align="left"> 
						  <select multiple name="list5[]" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
							<?php 	$query = "SELECT 	nm_campo_tab_hardware, 
													te_desc_hardware
										  FROM 		descricao_hardware 
										  ORDER BY 	te_desc_hardware";
							$result_hardwares_selecionados = mysql_query($query) or 
															 die('Ocorreu um erro durante a consulta à tabela descricao_hardware ou sua sessão expirou!');
							/* Agora monto os itens do combo de hardwares selecionadas. */ 
							while($campos_hardwares_selecionados=mysql_fetch_array($result_hardwares_selecionados)) {
							   echo '<option value=", ' . $campos_hardwares_selecionados['nm_campo_tab_hardware'] . 
											  ' as &quot;' .  $campos_hardwares_selecionados['te_desc_hardware'] . 
											  '&quot;">' . $campos_hardwares_selecionados['te_desc_hardware']  .
									'</option>\n';
							}
							?>
						  </select>
						  </div></td>
					  <td>&nbsp;</td>
					  <td width="40"> <div align="center"> 
							<?php
						  //<input type="button" value="   &gt;   " onClick="copia(this.form.elements['list5[]'],this.form.elements['list7[]']); move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">
						  ?>
						  <input type="button" value="   &gt;   " onClick="move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">					  
						  <br>
						  <br>
						  <?php
						  //<input type="button" value="   &lt;   " onClick="exclui(this.form.elements['list6[]'],this.form.elements['list8[]']); exclui(this.form.elements['list6[]'],this.form.elements['list7[]']); move(this.form.elements['list6[]'],this.form.elements['list5[]'])" name="B232">
						  ?>
						  <input type="button" value="   &lt;   " onClick="move(this.form.elements['list6[]'],this.form.elements['list5[]'])" name="B232">					  
						</div></td>
					  <td>&nbsp;</td>
					  <td><select multiple name="list6[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
						</select></td>
					  <td>&nbsp;</td>
					</tr>
				  </table>
				  </div>
				</td>
			  </tr>
			  <tr> 
				<td class="descricao">&nbsp;&nbsp;&nbsp;(Dica: 
				  use SHIFT ou CTRL para selecionar m&uacute;ltiplos itens)</td>
			  </tr>
			</table></td>
		</tr>
		<tr> 
		  <td valign="top"> 
			<?php  $v_require = '../../include/' .($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?'selecao_redes_inc.php':'selecao_locais_inc.php');
			require_once($v_require);		
			?>
	
		  </td>
		</tr>
		<tr> 
		  <td valign="top"> 
			<?php  require_once('../../include/selecao_so_inc.php');		?>
		  </td>
		</tr>
		<tr> 
		  <td valign="top">
			<table width="100%" border="0" cellpadding="0" cellspacing="1">
			  <tr> 
				<td height="1" bgcolor="#333333"></td>
			  </tr>
			  <tr> 
				<td> <div align="center"> 
					<input name="submitGerarRelatorio" id="submitGerarRelatorio" type="submit" value="        Gerar Relat&oacute;rio      " onClick="ChecaTodasAsRedes(),<?php echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?"SelectAll(this.form.elements['list2[]'])":"SelectAll(this.form.elements['list12[]'])")?>, 
																											 SelectAll(this.form.elements['list4[]']), 
																											 SelectAll(this.form.elements['list6[]'])">				
				  </div></td>
			  </tr>
			  <tr> 
				<td>&nbsp;</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</form>
	</body>
	</html>
    <?php
	}
	?>