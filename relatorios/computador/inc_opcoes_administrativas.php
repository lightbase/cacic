<? 
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
// Esse arquivo é um arquivo de include, usado pelo arquivo computador.php. 
if (!$_SESSION['opcoes_administrativas'])
	$_SESSION['opcoes_administrativas'] = false;
if ($exibir == 'opcoes_administrativas')
	{
	$_SESSION['opcoes_administrativas'] = !($_SESSION['opcoes_administrativas']);
	}
else
	{
	$_SESSION['opcoes_administrativas'] = false;
	}
?>
	<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
  	<tr> 
    <td colspan="3" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;&nbsp;<a href="computador.php?exibir=opcoes_administrativas&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><img src="../../imgs/<? if($_SESSION['opcoes_administrativas'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">&nbsp;Op&ccedil;&otilde;es Administrativas</a></td>
  	</tr>
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
	<?	
	if ($_SESSION['opcoes_administrativas']) 
		{ 
		?>
  		<tr> 
    	<td colspan="6"><table border="0">
    	<tr> 
    	<td align="left" valign="middle" class="opcao_tabela"><a href="../../admin/forca_coleta_estacao.php?te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>&te_nome_computador=<? echo mysql_result($result, 0, "te_nome_computador");?>"><img src="../../imgs/forca_coleta.gif" width="24" height="24" border="0"></a></td>
   		<td nowrap class="opcao_tabela"><a href="../../admin/forca_coleta_estacao.php?te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>&te_nome_computador=<? echo mysql_result($result, 0, "te_nome_computador");?>">For&ccedil;ar Coletas</a>&nbsp;&nbsp;&nbsp;</td>
		<?
		if ($_SESSION["cs_nivel_administracao"] == 1 ||
			$_SESSION["cs_nivel_administracao"] == 2 ||
			$_SESSION["cs_nivel_administracao"] == 3)			
			{
			?>
    		<td align="left" valign="middle" class="opcao_tabela"><a href="../../admin/remove_computador.php?te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><img src="../../imgs/exclui_computador.gif" width="24" height="24" border="0"></a></td>
    		<td nowrap class="opcao_tabela"><a href="../../admin/remove_computador.php?te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so'];?>">Remover Computador</a></td>
			<?
			}
			?>		
    	</tr>
    	</table>
		</tr>
  		<? echo $linha; 
		} 
		?> 		
	</table>
