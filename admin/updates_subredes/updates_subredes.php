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

session_start();

require_once('../../include/library.php');

AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

// Função para replicação do conteúdo do REPOSITÓRIO nos servidores de UPDATES das redes cadastradas.
	if ($_REQUEST['v_parametros']<>'')
		{
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html>
		<head>
		<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
	
		<title><?=$oTranslator->_('Verificacao/Atualizacao dos Servidores de Updates');?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		</head>
		<body background="../../imgs/linha_v.gif">
		<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>	
		
		<form name="frm_update_subredes" id="frm_update_subredes">
		<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr nowrap> 
		<td class="cabecalho"><?=$oTranslator->_('Verificacao/Atualizacao dos Servidores de Updates');?></td>
		</tr>
		<tr> 
		<td class="descricao">
                   <?=$oTranslator->_('Verificacao-Atualizacao dos Servidores de Updates help');?>
                </td>
		</tr>
		</table>
		<br>
		<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#666666">
		<tr bordercolor="#000000" bgcolor="#CCCCCC">	
		<td valign="center" class="cabecalho_tabela">
		<p align="left"><?=$oTranslator->_('Endereco IP');?></p>
		</td>

		<td valign="center" class="cabecalho_tabela">
		<p align="left">&nbsp;&nbsp;&nbsp;</p>
		</td>
		
		<td valign="center" class="cabecalho_tabela">
		<p align="left"><?=$oTranslator->_('Localizacao ou nome da SubRede');?>
		</p>
		</td>		
		<td valign="center" class="cabecalho_tabela">
		<p align="left">&nbsp;&nbsp;&nbsp;</p>
		</td>
	
		<td valign="center" class="cabecalho_tabela">
		<p align="left"><?=$oTranslator->_('Status');?></p>
		</td>			
		</tr>
	
		<?
		$v_array_parametros = explode('_-_',url_decode($_REQUEST['v_parametros']));

		$v_array_redes = explode('__',str_replace('_fr_',"'",$v_array_parametros[1]));	
		$v_array_hashs = explode('#',$v_array_parametros[4]);			

		$v_tripa_agentes_hashs = '';
		for ($i=0;$i<count($v_array_hashs);$i++)
			{
			$arrTmp = explode('*',$v_array_hashs[$i]);
			$v_array_agentes_hashs[$arrTmp[0]] = $arrTmp[1];
			}

		if (count($v_array_redes)>0)
			{
			for ($i = 0;$i < count($v_array_redes);$i++)
				{
				if ($v_where <> '') $v_where .= ' or ';
				$v_where .= ' id_ip_rede="'.$v_array_redes[$i].'"';
				}
			}

		$query_REDES= "	SELECT 	re.id_ip_rede,
								re.nm_rede,
								re.id_local,
								re.te_serv_updates,
								lo.sg_local
					 FROM		redes re,
					            locais lo 
					 WHERE      re.id_local = lo.id_local AND (" . $v_where . ") 
					 ORDER BY   lo.sg_local,re.nm_rede";
		conecta_bd_cacic();					
		$result_REDES = mysql_query($query_REDES);
		$_SESSION['v_tripa_objetos_enviados']   = ''; // Conterá a lista de agentes e versões enviadas aos servidores.
		$_SESSION['v_tripa_servidores_updates'] = ''; // Conterá a lista de servidores que já receberam o UPLOAD das versões
		$tamanhoFormato = strlen(mysql_num_rows($result_REDES));
		$contaUpdates   = 1;
		while ($row = mysql_fetch_array($result_REDES))
			{
			if ($v_array_parametros[2]<>'') // Se a opção "Forçar" foi marcada...
				{					
				$query_del = "DELETE 
							  FROM 		redes_versoes_modulos 
				              WHERE 	id_local = ".$row['id_local'] ." AND 
							  			id_ip_rede in ('" . $row['id_ip_rede'] . "') and nm_modulo in (".str_replace('_fm_',"'",$v_array_parametros[2]).")";								
				conecta_bd_cacic();					

				$result_del = mysql_query($query_del) or die('Erro na Operação de DELETE ou sua sessão expirou => Query_Del: '.$query_del.' <br> '.mysql_error());
				//break;				
				}
			
			if ($v_cor_zebra == '#FFFFFF') $v_cor_zebra = '#EEEEEE'; else $v_cor_zebra = '#FFFFFF';		

			?>		
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" class="opcao_tabela">
			<p align="left"><? echo $row['id_ip_rede']; ?></p>
			</td>
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" class="opcao_tabela">
			<p align="left">&nbsp;&nbsp;&nbsp;</p>
			</td>
		
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" nowrap class="opcao_tabela">
			<p align="left"><? echo $row['sg_local'] . '/' . $row['nm_rede']; ?></p>
			</td>
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" class="opcao_tabela">
			<p align="left">&nbsp;&nbsp;&nbsp;</p>
			</td>
			<td valign="center" bgcolor="<? echo $v_cor_zebra;?>" nowrap class="opcao_tabela_blue">
			<p align="left">
			<?
			flush();

			// Verifico se o Servidor de Updates já foi atualizado.
			// Neste caso, preciso atualizar a tabela Redes_Versoes_Modulos para a rede atual.
			$strTeServUpdatesToCheck = '#'.trim($row['id_local']).trim($row['te_serv_updates']).'#';
			if (@substr_count($_SESSION['v_tripa_servidores_updates'],$strTeServUpdatesToCheck)>0)
				{
				$v_arr_agentes_versoes_enviados = explode('#',$_SESSION['v_tripa_objetos_enviados']);
				for ($intAgentesVersoesEnviados = 0;$intAgentesVersoesEnviados < count($v_arr_agentes_versoes_enviados);$intAgentesVersoesEnviados++)			
					{
					// Procuro por pacotes Linux previamente gravados na tabela de versões
					$cs_tipo_so = (stripos2($v_arr_aux[0],'.exe',false)?'MS-Windows':'GNU/LINUX');
					$cs_tipo_so = (stripos2($v_arr_aux[0],'.ini',false)?'MS-Windows':$cs_tipo_so);		
					
					if ($cs_tipo_so == 'GNU/LINUX')
						$intAgentesVersoesEnviados = count($v_arr_agentes_versoes_enviados);
					}	
		
				if ($cs_tipo_so == 'GNU/LINUX')
					{
					// Excluo o pacote Linux previamente gravado na tabela				
					$delete = 'DELETE from redes_versoes_modulos WHERE id_local = '.$row['id_local'].' AND id_ip_rede = "'.trim($row['id_ip_rede']).'" AND cs_tipo_so="GNU/LINUX"';
					$result_DELETE = mysql_query($delete);									
					}			
				
				$insert = "INSERT INTO redes_versoes_modulos (id_local,id_ip_rede,nm_modulo,te_versao_modulo,dt_atualizacao,cs_tipo_so,te_hash) ";
				$values = "";
				for ($intAgentesVersoesEnviados = 0;$intAgentesVersoesEnviados < count($v_arr_agentes_versoes_enviados);$intAgentesVersoesEnviados++)			
					{
					$v_arr_aux = explode(',',$v_arr_agentes_versoes_enviados[$intAgentesVersoesEnviados]);				
					$cs_tipo_so = (stripos2($v_arr_aux[0],'.exe',false)?'MS-Windows':'GNU/LINUX');
					$cs_tipo_so = (stripos2($v_arr_aux[0],'.ini',false)?'MS-Windows':$cs_tipo_so);		
					
					$values .= ($values?",":"VALUES ");
					$values .= '('.$row['id_local'].',"'.trim($row['id_ip_rede']).'","'.$v_arr_aux[0].'","'.$v_arr_aux[1].'",now(),"'.$cs_tipo_so.'","'.$v_array_agentes_hashs[$v_arr_aux[0]].'")';
					}	

				$result_INSERT = mysql_query($insert . $values);				
				echo '<b>Verificação Efetuada!</b>&nbsp;&nbsp;<font color=black size=1>(Servidor de Updates Verificado Anteriormente!)</font>';
				flush();				
				}
			else
				{
				update_subredes($row['id_ip_rede'],'Pagina','__'.$v_array_parametros[0],$row['id_local'],$v_array_agentes_hashs);			
				flush();
				if ($_SESSION['v_efetua_conexao_ftp'] == 1)
					{
					if (($_SESSION['v_conta_objetos_atualizados'] +
					     $_SESSION['v_conta_objetos_nao_atualizados'] +
					     $_SESSION['v_conta_objetos_enviados'] +
					     $_SESSION['v_conta_objetos_nao_enviados'])==0)
						 {
						 echo '<b>Verificação Efetuada!</b>';												
						 flush(); // POG: Se tirar esse comentário não funciona mais!  :)))))
						 }
					}
				else if($_SESSION['v_status_conexao'] == 'NC')
					{
					echo '<a href="../redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'&id_local='.$row['id_local'].'" style="color: red"><strong>FTP não configurado!</strong></a>';										
					}
				else if($_SESSION['v_status_conexao'] == 'OFF')
					{
					echo '<a href="../redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'&id_local='.$row['id_local'].'" style="color: red"><strong>Conexão Impossível ao Serviço FTP!</strong><font color="black" size=1>('.$row['te_serv_updates'].')</font></a>';																				
					}
				else
					{
					$_SESSION['v_conta_objetos_enviados'] 			= 	0;
					$_SESSION['v_conta_objetos_nao_enviados']		= 	0;
					$_SESSION['v_conta_objetos_atualizados']		=	0;
					$_SESSION['v_conta_objetos_nao_atualizados']	= 	0;									
					}
				flush();
				}
				session_unregister('v_status_conexao');					
			?>
			</p>
			</td>			
			</tr>	
			<?
			}
		session_unregister('v_conta_objetos_enviados');
		session_unregister('v_conta_objetos_nao_enviados');
		session_unregister('v_conta_objetos_atualizados');
		session_unregister('v_conta_objetos_nao_atualizados');
		session_unregister('v_tripa_servidores_updates');	
		session_unregister('v_efetua_conexao_ftp');
		session_unregister('v_conexao_ftp');
	
		?>
		
		<tr bordercolor="#000000" bgcolor="#999999">
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>
	
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>		
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>
	
		<td valign="center" class="opcao_tabela">
		<p align="left">&nbsp;</p>
		</td>			
		</tr>	
	
		</table>	
		
</form>
</body>
		</html>
		<?
		flush();
		}
		?>
