<?
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

//if ($_SERVER['REMOTE_ADDR'] <> '10.71.0.58')
//	{
//	require_once('../include/opcao_indisponivel.php');	
//	return;
//	}

require_once('../include/library.php');
	
// Função para replicação do conteúdo do REPOSITÓRIO nos servidores de UPDATES das redes cadastradas.
if ($handle = opendir('../repositorio')) 
	{
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<title>Verifica&ccedil;&atilde;o/Atualiza&ccedil;&atilde;o dos Servidores de Updates</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	<body background="../imgs/linha_v.gif">
	<table width="90%" border="0" align="center">
	<tr nowrap> 
	<td><font color="#FF0000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><b>Verifica&ccedil;&atilde;o dos Servidores de Updates das Redes</b></font></td>
	</tr>
	<tr> 
	<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">M&oacute;dulo para verifica&ccedil;&atilde;o/atualiza&ccedil;&atilde;o das vers&otilde;es 
	dos objetos localizados nos servidores de updates das redes monitoradas.</font></td>
	</tr>
	</table>
	<br>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#666666">
		<tr bordercolor="#000000" bgcolor="#CCCCCC">	
		<td valign="center">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>IP da Rede</strong></font>
		</p>
		</td>

		<td valign="center">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;</font>
		</p>
		</td>
		
		<td valign="center">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Nome da Rede</strong></font>
		</p>
		</td>		
		<td valign="center">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;</font>
		</p>
		</td>
	
		<td valign="center">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Status</strong></font>
		</p>
		</td>			
		</tr>
	
	<?
	$v_nomes_arquivos_REP = array();
	$v_datas_arquivos_REP = array();	
	while (false !== ($v_arquivo = readdir($handle))) 
		{
		if ($v_arquivo != "." and $v_arquivo != ".." and $v_arquivo != "netlogon") 
			{
			// Armazeno o nome do arquivo do repositório
			array_push($v_nomes_arquivos_REP, $v_arquivo);
			
			// Armazeno a data/hora do arquivo do repositório
			$caminho_arquivo = '../repositorio/' . $v_arquivo;
			array_push($v_datas_arquivos_REP, strftime("%Y%m%d%H%M", filemtime($caminho_arquivo)));
			}
		}
	closedir($handle);

	$query_REDES= "	SELECT 	re.id_ip_rede,
							re.nm_rede
				FROM		redes re
				ORDER BY    re.nm_rede";
			
	conecta_bd_cacic();					
	$result_REDES = mysql_query($query_REDES);
	$v_tripa_servidores_updates = '';
	while ($row = mysql_fetch_array($result_REDES))
		{
		if ($v_cor_zebra == '#FFFFFF') $v_cor_zebra = '#EEEEEE'; else $v_cor_zebra = '#FFFFFF';								
		?>		
		<tr> 
		<td valign="center" bgcolor="<? echo $v_cor_zebra;?>"  style="height: 25">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
		<? echo $row['id_ip_rede']; ?></font>
		</p>
		</td>
		<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
		</p>
		</td>
		
		<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" nowrap>
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
		<? echo $row['nm_rede']; ?></font>
		</p>
		</td>
		<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
		<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
		</p>
		</td>
		<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" nowrap>
		<p align="left">
		<?
		if (@strpos($_SESSION['v_tripa_servidores_updates'],trim($row['te_serv_updates']))>0)
			{
			echo '<font color="#000099" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Verificação Efetuada!</strong></font>';																		
			}
		else
			{		
			update_subredes($row['id_ip_rede'],'Pagina');			
			if ($_SESSION['v_efetua_conexao_ftp'] > 0)
				{	
				echo '<font color="#000099" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Verificação Efetuada!</strong></font>';
							
				if ($_SESSION['v_conta_objetos_atualizados'] > 0)
					{
					$v_array_objetos_atualizados = explode('#',$_SESSION['v_tripa_objetos_atualizados']);
					for ($cnt_objetos = 0; $cnt_objetos < $_SESSION['v_conta_objetos_atualizados']; $cnt_objetos++)
						{
						?>
						<tr> 
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						&nbsp;</font>
						</p>
						</td>
						
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#000099" size="2" face="Verdana, Arial, Helvetica, sans-serif">Atualizando <? echo $v_array_objetos_atualizados[$cnt_objetos];?>...</font>
						<?					
						}						
					}
				if ($_SESSION['v_conta_objetos_nao_atualizados'] > 0)
					{
					$v_array_objetos_nao_atualizados = explode('#',$_SESSION['v_tripa_objetos_nao_atualizados']);					
					for ($cnt_objetos = 0; $cnt_objetos < $_SESSION['v_conta_objetos_nao_atualizados']; $cnt_objetos++) 					
						{

						?>
						<tr> 
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						&nbsp;</font>
						</p>
						</td>
					
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Não Atualizado: <? echo $v_array_objetos_nao_atualizados[$cnt_objetos];?>!</font>
						<?					
						}						
					}
				if ($_SESSION['v_conta_objetos_enviados'] > 0)
					{
					$v_array_objetos_enviados = explode('#',$_SESSION['v_tripa_objetos_enviados']);					
					for ($cnt_objetos = 0; $cnt_objetos < $_SESSION['v_conta_objetos_enviados']; $cnt_objetos++) 					
						{
						?>
						<tr> 
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						&nbsp;</font>
						</p>
						</td>
						
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#000099" size="2" face="Verdana, Arial, Helvetica, sans-serif">Enviando <? echo $v_array_objetos_enviados[$cnt_objetos];?>...</font>
						<?					
						}						
					 }
				if ($_SESSION['v_conta_objetos_nao_enviados'] > 0)
					{
					$v_array_objetos_nao_enviados = explode('#',$_SESSION['v_tripa_objetos_nao_enviados']);					
					for ($cnt_objetos = 0; $cnt_objetos < $_SESSION['v_conta_objetos_nao_enviados']; $cnt_objetos++) 					
						{
						?>
						<tr> 
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						&nbsp;</font>
						</p>
						</td>
		
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						&nbsp;</font>
						</p>
						</td>
						<td valign="center" bgcolor="<? echo $v_cor_zebra;?>">
						<p align="left"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Não Enviado <? echo $v_array_objetos_nao_enviados[$cnt_objetos];?>!</font>
						<?					
						}						
					}										
				}									
			else if($_SESSION['v_status_conexao'] == 'NC')
				{
					echo '<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'" style="color: red"><strong>FTP não configurado!</strong></a></font>';					
				}
			else if($_SESSION['v_status_conexao'] == 'OFF')
				{
					echo '<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'" style="color: red"><strong>Servidor OffLine!</strong></a></font>';															
				}
			}
		}
		?>
		</p>
		</td>			
		</tr>			
		<?
	$_SESSION['v_conta_objetos_enviados'] 			= 	0;
	$_SESSION['v_conta_objetos_nao_enviados']		= 	0;
	$_SESSION['v_conta_objetos_atualizados']		=	0;
	$_SESSION['v_conta_objetos_nao_atualizados']	= 	0;
		
	}
	session_unregister('v_conta_objetos_enviados');
	session_unregister('v_conta_objetos_nao_enviados');
	session_unregister('v_conta_objetos_atualizados');
	session_unregister('v_conta_objetos_nao_atualizados');
	session_unregister('v_tripa_objetos_enviados');
	session_unregister('v_tripa_objetos_nao_enviados');
	session_unregister('v_tripa_objetos_atualizados');
	session_unregister('v_tripa_objetos_nao_atualizados');
	session_unregister('v_tripa_servidores_updates');	
	session_unregister('v_efetua_conexao_ftp');
	session_unregister('v_conexao_ftp');
	
	?>
	<tr bordercolor="#000000" bgcolor="#999999">
	<td valign="center">
	<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
	</p>
	</td>
	<td valign="center">
	<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
	</p>
	</td>
	
	<td valign="center">
	<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
	</p>
	</td>		
	<td valign="center">
	<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
	</p>
	</td>
	
	<td valign="center">
	<p align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font>
	</p>
	</td>			
	</tr>	
	
	</table>	
	</body>
	</html>