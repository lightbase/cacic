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
if (!$_SESSION['suporte_remoto'])
	$_SESSION['suporte_remoto'] = false;
if ($exibir == 'suporte_remoto')
	{
	$_SESSION['suporte_remoto'] = !($_SESSION['suporte_remoto']);
	}
else	
	{
	$_SESSION['suporte_remoto'] = false;
	}
	
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="6" height="1" bgcolor="#333333"></td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td class="cabecalho_tabela" colspan="6">&nbsp;<a href="computador.php?exibir=suporte_remoto&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['suporte_remoto'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0"> Suporte Remoto</a></td>
  </tr>
  <tr>
    <td colspan="6" height="1" bgcolor="#333333"></td>                   
  </tr>
<?
if ($_SESSION['suporte_remoto'] == true) 
	{
	$linha = '	<tr bgcolor="'.$strCorDaLinha.'"> 
				<td height="1" colspan="6"></td>
				</tr>';
		
	?>  
  	<tr>
	<td class="cabecalho_tabela">&nbsp;<u>Seq.</u></td>
	<td class="cabecalho_tabela">&nbsp;<u>Usuário Local (Iniciador da Sessão)</u></td>	    
	<td class="cabecalho_tabela">&nbsp;<u>Usuário Remoto (Técnico)</u></td>	
	<td class="cabecalho_tabela">&nbsp;<u>Conexão</u></td>		    
	<td class="cabecalho_tabela">&nbsp;<u>Documento Referencial</u></td>	
	<td class="cabecalho_tabela">&nbsp;<u>Chat?</u></td>  	
</tr>
  	<?
	// EXIBIR INFORMAÇÕES DE SUPORTE_REMOTO REALIZADOS NO COMPUTADOR
	$query = "SELECT 	cs_situacao
			  FROM 		acoes_redes 
			  WHERE 	id_acao = 'cs_suporte_remoto' AND
						id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
	$result_acoes =  mysql_query($query);
	
	if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') 
		{
		$query = "	SELECT 		DISTINCT dt_hr_inicio_conexao,
								sr2.dt_hr_ultimo_contato,
								te_motivo_conexao,
								sr2.id_conexao,
								TRIM(srcacic_chats.te_mensagem),
								te_documento_referencial,
								nm_usuario_acesso,
								nm_usuario_completo,
								nm_acesso_usuario_srv,
								nm_completo_usuario_srv								
					FROM 		srcacic_sessoes sr1,
								srcacic_conexoes sr2 LEFT JOIN srcacic_chats ON (sr2.id_conexao = srcacic_chats.id_conexao),
								usuarios
					WHERE 		sr1.te_node_address_srv = '".$_GET['te_node_address']."' AND
								sr1.id_so_srv = '". $_GET['id_so'] ."' AND
								sr2.id_sessao = sr1.id_sessao AND
								sr2.id_usuario_cli = usuarios.id_usuario
					ORDER BY	sr2.dt_hr_inicio_conexao DESC";
		$result_suporte = mysql_query($query);
		$v_achei = 0;
		$intContaItem = 0;
		$strCor = '';  					
		$strEnter = chr(13).chr(10);
		while ($row = mysql_fetch_array($result_suporte)) 
			{
			$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
			
			$arrDataHoraConexao	 		= explode(' ',$row['dt_hr_inicio_conexao']);
			$strDataConexao 			= $arrDataHoraConexao[0];
			$arrDataConexao 			= explode('-',$strDataConexao);

			$strHoraConexao 			= $arrDataHoraConexao[1];
			$arrHoraConexao 			= explode(':',$strHoraConexao);			
			
			$arrDataHoraUltimoContato 	= explode(' ',$row['dt_hr_ultimo_contato']);
			$strDataUltimoContato 		= $arrDataHoraUltimoContato[0];
			$arrDataUltimoContato 		= explode('-',$strDataUltimoContato);

			$strHoraUltimoContato		= $arrDataHoraUltimoContato[1];
			$arrHoraUltimoContato		= explode(':',$strHoraUltimoContato);				
			
			$v_achei = 1;
			$intContaItem ++;
			
			?>
			<tr bgcolor="<? echo $strCor;?>">
			<td align="right" class="descricao"><div align="right"><B><? echo $intContaItem;?></B></div></td> 
			<td align="left" nowrap="nowrap" class="descricao">&nbsp;<a href="../../admin/detalha_conexao?id_conexao=<? echo $row['id_conexao'];?>"><? echo $row['nm_acesso_usuario_srv'].'/'.$row['nm_completo_usuario_srv']; ?></a></td>                        
			<td align="left" nowrap="nowrap" class="descricao">&nbsp;<a href="../../admin/detalha_conexao?id_conexao=<? echo $row['id_conexao'];?>"><? echo $row['nm_usuario_acesso'].'/'.$row['nm_usuario_completo']; ?></a></td>
			<td align="left" nowrap="nowrap" class="descricao">&nbsp;<a href="../../admin/detalha_conexao?id_conexao=<? echo $row['id_conexao'];?>"><? echo $arrDataConexao[2].'/'.$arrDataConexao[1].'/'.$arrDataConexao[0] .' '.$arrHoraConexao[0].':'.$arrHoraConexao[1].'h => '.$arrDataUltimoContato[2].'/'.$arrDataUltimoContato[1].'/'.$arrDataUltimoContato[0] .' '.$arrHoraUltimoContato[0].':'.$arrHoraUltimoContato[1].'h'; ?></a></td>            
			<td align="left" nowrap="nowrap" class="descricao">&nbsp;<a href="../../admin/detalha_conexao?id_conexao=<? echo $row['id_conexao'];?>"><? echo $row['te_documento_referencial']; ?></a></td>
<td align="left" nowrap="nowrap" class="descricao"><? if ($row['te_mensagem']<>''){?>&nbsp;<a href="../../admin/detalha_conexao?id_conexao=<? echo $row['id_conexao'];?>"><img src="../../imgs/chat.png" border="0" height="20" width="20"></a><?}?></td>
			</tr>
			<?
			echo $linha;
			}

		if (!$v_achei)
			{
			echo '<tr><td colspan="5"> 
				<p>
				<div align="center">
				<br>
				<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
				Não foram realizadas operações de Suporte Remoto para esta máquina
				</font></div>
				</p>
			  </td></tr>';
			}
		}
	else
		{
		echo '<tr><td colspan="5"> 
				<div align="center">
				<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
				O módulo de Suporte Remoto não foi habilitado pelo Administrador do CACIC.
				</font></div>
			  </td></tr>';						
		}
	}
// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE SUPORTE REMOTO DO COMPUTADOR
?>
</table>
