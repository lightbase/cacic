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
// Esse arquivo � um arquivo de include, usado pelo arquivo computador.php. 
if (!$_SESSION['devices_cpus'])
	$_SESSION['devices_cpus'] = false;
if ($exibir == 'devices_cpus')
	{
	$_SESSION['devices_cpus'] = !($_SESSION['devices_cpus']);
	}
else
	{
	$_SESSION['devices_cpus'] = false;
	}
	
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
	<td height="1" bgcolor="#333333" colspan="4"></td>
</tr>

<tr bgcolor="#E1E1E1"> 
<td colspan="4" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=devices_cpus&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
<img src="../../imgs/<? if($_SESSION['devices_cpus'] == true) 
							echo 'menos';
   			 			else 
							echo 'mais'; ?>.gif" width="12" height="12" border="0"> CPU&acute;s</a></font></td>
</tr>
<tr> 
<td height="1" bgcolor="#333333" colspan="4"></td>
</tr>

<?
if ($_SESSION['devices_cpus'] == true) 
	{
	$linha = '	<tr bgcolor="#e7e7e7"> 
				<td height="1" colspan="6"></td>
				</tr>';
		
	?>
	<tr> 
	<td class="cabecalho_tabela">&nbsp;<u>Descri&ccedil;&atilde;o</u></td>
	<td class="cabecalho_tabela"><u>Frequ&ecirc;ncia</u></td>		
	<td class="cabecalho_tabela"><u>Fabricante</u></td>	
	<td class="cabecalho_tabela"><u>Serial</u></td>	
	</tr>
	<?		
	echo $linha;		
	// EXIBIR INFORMA��ES DE SISTEMAS MONITORADOS NO COMPUTADOR
	$query = "SELECT 	cs_situacao
			  FROM 		acoes_redes 
			  WHERE 	id_acao = 'cs_coleta_hardware' AND
						id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
	$result_acoes =  mysql_query($query);
			
	if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') 
		{
		$query = "	SELECT 		DISTINCT cpus.te_cpu_desc,
										 cpus.te_cpu_serial,
										 cpus.te_cpu_fabricante,
										 cpus.te_cpu_freq
					FROM 		cpus
					WHERE 		cpus.te_node_address = '".$_GET['te_node_address']."' AND
								cpus.id_so = '". $_GET['id_so'] ."'									
					ORDER BY	cpus.te_cpu_desc";

		$result_software = mysql_query($query);
		$v_achei = 0;
		while ($row = mysql_fetch_array($result_software)) 
			{
			$v_achei = 1;
			?>
			<tr> 
			<td class="descricao">&nbsp;<? echo $row['te_cpu_desc']; ?></td>
			<td class="descricao"><? echo $row['te_cpu_freq']; ?></td>
			<td class="descricao"><? echo $row['te_cpu_fabricante']; ?></td>	
			<td class="descricao"><? echo $row['te_cpu_serial']; ?></td>	
			</tr>
			<?
			echo $linha;
			}
		if (!$v_achei)
			{
			echo '<tr><td colspan="6"> 
					<p>
					<div align="center">
					<br>
					<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
					N�o foram coletadas informa��es de processadores referente a esta m�quina
					</font></div>
					</p>
				  </td></tr>';
			}
		}
	else 
		{
		echo '<tr><td colspan="6"> 
				<div align="center">
				<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
				O m�dulo de Coleta de Informa��es de Hardware n�o foi habilitado pelo Administrador do CACIC.
				</font></div>
			  </td></tr>';
		}
	}
	// FIM DA EXIBI��O DE INFORMA��ES DE SISTEMAS MONITORADOS DO COMPUTADOR
	?>
</table>
