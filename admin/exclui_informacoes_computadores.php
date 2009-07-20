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
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../include/library.php'); 
AntiSpy('1,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

conecta_bd_cacic();

if ($_POST['submit_cond'])
	{
	$query_sele_exclui = '';
	$value_anterior = '';
	while(list($key, $value) = each($HTTP_POST_VARS))
		{
		if (trim($value)<>'' && trim(strpos($key,'frm_'))<>'') 
			{
			if (trim(strpos($key,'frm_condicao_'))<>'')
				{
				$query_sele_exclui .= str_replace('frm_condicao_','',$value);
				}
			else
				{
				if ($value_anterior) $query_sele_exclui .= ' and ';
				$query_sele_exclui = str_replace('frm_te_valor_condicao',$value,$query_sele_exclui);
				}
			
			$value_anterior = $value;
			}
		} 


	$query_sele_exclui = (substr($query_sele_exclui,-5)==' and '?substr($query_sele_exclui,0,strlen($query_sele_exclui)-5):$query_sele_exclui);
	$query_sele_exclui = str_replace('-MENOR-',' < ',$query_sele_exclui);
	$query_sele_exclui = str_replace('-MAIOR-',' > ',$query_sele_exclui);	

    $where 	= ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND c.id_local = '.$_SESSION['id_local']:'');		
	if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
		{
		// Faço uma inserção de "(" para ajuste da lógica para consulta	
		$where = str_replace('c.id_local = ','(c.id_local = ',$where);
		$where .= ' OR c.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
		}
	
	$Query_Pesquisa = 'SELECT 	a.id_so,
								a.te_node_address,
								a.te_nome_computador, 
								a.te_ip, 
								a.te_versao_cacic, 
								a.te_versao_gercols, 
								a.dt_hr_ult_acesso,
								a.dt_hr_inclusao,								
								b.sg_so,
								d.sg_local						 
						FROM	computadores a
						LEFT JOIN so b ON (a.id_so = b.id_so)
						LEFT JOIN redes c ON (a.id_ip_rede = c.id_ip_rede)
						LEFT JOIN locais d ON (c.id_local = d.id_local)
						WHERE   '.stripslashes($query_sele_exclui).' '.$where . ' 
						ORDER 	by a.te_nome_computador';


	$Query_Pesquisa = 'SELECT 	a.id_so,
								a.te_node_address,
								a.te_nome_computador, 
								a.te_ip, 
								a.te_versao_cacic, 
								a.te_versao_gercols, 
								a.dt_hr_ult_acesso,
								a.dt_hr_inclusao,								
								so.sg_so,
								redes.id_local
						FROM	computadores a								
								LEFT JOIN so    ON (a.id_so      = so.id_so)
								LEFT JOIN redes ON (a.id_ip_rede = redes.id_ip_rede)
						WHERE   '.stripslashes($query_sele_exclui).
								$where . ' 
						ORDER 	by a.te_nome_computador';
						
	$result = mysql_query($Query_Pesquisa) or die('Erro no select (1) ou sua sessão expirou!');

	$strIdLocal = '';
	$arrSgLocal = array();
	while($row = mysql_fetch_array($result)) 
		{		  
		if ($row['id_local']<>'' && $arrSgLocal[$row['id_local']]=='')
			{
			$arrSgLocal[$row['id_local']] = '*';
			$strIdLocal .= ($strIdLocal==''?'':',');
			$strIdLocal .= $row['id_local'];
			}
		}	
	if ($strIdLocal <> '')
		{
		$Query_Locais = 'SELECT 	locais.id_local,
									locais.sg_local
						 FROM		locais
						 WHERE   	locais.id_local in ('.$strIdLocal.')';
		if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
			echo 'Query_Locais: '.$Query_Locais.'<br>';											 
		$resultLocais = mysql_query($Query_Locais) or die('Erro no select (2) ou sua sessão expirou!');		
		while($row = mysql_fetch_array($resultLocais)) 
			$arrSgLocal[$row['id_local']] = $row['sg_local'];
		}
		
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
	<title><?=$oTranslator->_('Excluir Computadores');?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">	
	<link href="../include/cacic.css" rel="stylesheet" type="text/css">
	<SCRIPT>
	function Verifica_Check_Exclui()
		{
		var v_total_check;
		v_total_check = 0;
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].type == 'checkbox' && window.document.forms[i].elements[j].checked == true)
					{
					v_total_check ++;
					}
				}
			}		

		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].type == 'submit' && window.document.forms[i].elements[j].name == 'submit_exc')
					{
					if (v_total_check==0) window.document.forms[i].elements[j].disabled = true
					else window.document.forms[i].elements[j].disabled = false;
					}
				}
			}					
		}
	</script>	
	</head>

	<body background="../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
	<form name="form1" method="post">
	<table width="95%" border="0" align="center">
	<tr> 
	
	  <td class="cabecalho"><?=$oTranslator->_('Excluir Computadores');?></td>
	</tr>
	<tr> 
	
	  <td class="descricao"><?=$oTranslator->_('Esta opcao permite a selecao final para exclusao dos computadores selecionados na pesquisa');?>.</td>
	</tr>
	</table>
	<br><br>
  	<table width="90%" align="center"><tr>
    <td><div align="center"> 
    <?
	if ($_SESSION['cs_nivel_administracao'] == '1' || $_SESSION['cs_nivel_administracao'] == '3')
		{
		?>
   		<input name="submit_exc" type="submit" value="<?=$oTranslator->_('Excluir computadores selecionados');?>" <? if ($NumRegistro == 1) echo 'disabled'; ?> onClick="return Confirma('<?=$oTranslator->_('Confirma exclusao');?>');" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>		  
   		&nbsp;&nbsp;
        <?
		}
		?>
   	<input name="submit_nova" type="submit" value="<?=$oTranslator->_('Selecionar novamente');?>">	
	</div></td>		
   	</tr></table>
	<br><br>
	<table border="0" align="center" cellpadding="0" cellspacing="0">
  	<tr> 
    <td> <table border="1" cellpadding="1" cellspacing="0" align="center">
          <tr> 
            <td height="1" bgcolor="#333333" colspan="23"></td>
          </tr>
	
          <tr bgcolor="#E1E1E1"> 
            <td align="center" colspan="2" nowrap><img src="../imgs/exclui_computador.gif" width="23" height="23"></td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Nome da maquina');?></div></td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Local');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Endereco IP');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Endereco MAC');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Sistema operacional');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Cacic2');?></div></td>
            <td nowrap class="cabecalho_tabela"><?=$oTranslator->_('GerCols');?></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Ultimo acesso');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Inclusao');?></div></td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan="23"></td>
          </tr>
          <?  
	$Cor = 0;
	$NumRegistro = 1;
	mysql_data_seek($result,0);
	while($row = mysql_fetch_array($result)) 
		{		  
	 	?>
          <tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><? echo $NumRegistro; ?></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><input type="checkbox" name="chk_<? echo $row['te_node_address'].'#'. $row['id_so']; ?>" value="1" checked onClick="Verifica_Check_Exclui();">&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div>&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $arrSgLocal[$row['id_local']]; ?></a></div>&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_ip']; ?></a></div>&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_node_address'];?></a></div>&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['sg_so']; ?></a></div>&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_versao_cacic']; ?></a></div>&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_versao_gercols']; ?></a></div>&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="right"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo date("d/m/y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></a></div>&nbsp;</td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="right"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo date("d/m/y H:i", strtotime( $row['dt_hr_inclusao'] ));   ?></a></div>&nbsp;</td>
          </tr>
          <? 
		$Cor=!$Cor;
		$NumRegistro++;
		}		
	if ($NumRegistro == 1)
		{
		?>
          <td colspan="20" align="center" class="label_vermelho"><?=$oTranslator->_('Nao foram encontrados registros');?></TD>
          <script language="JavaScript">
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == 'submit_exc')
					{
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		</script>
          <?
		}
		?>
        </table></td>
  	</tr>
  	<tr> 
    <td height="1" bgcolor="#333333"></td>
  	</tr>
	</table>

  	<br><br>
  	<table width="90%" align="center"><tr>
    <td><div align="center"> 
    <?
	if (($_SESSION['cs_nivel_administracao'] == '1' || $_SESSION['cs_nivel_administracao'] == '3') && $NumRegistro > 1)
		{
		?>
   		<input name="submit_exc" type="submit" value="<?=$oTranslator->_('Excluir computadores selecionados');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma exclusao');?>');">		  
   		&nbsp;&nbsp;
        <?
		}
		?>
   	<input name="submit_nova" type="submit" value="<?=$oTranslator->_('Selecionar novamente');?>">	
	</div></td>		
   	</tr></table>
	<p><p><p>  
	</form>
	</html>	
	<?				
	} 
else	
	{
	if ($_POST['submit_exc'])
		{
		$v_cs_exclui = '';

		// Faço testes para identificar as tabelas válidas para as consultas...
		$strTables = '';
		$result_tables	= mysql_list_tables($nome_bd); //Retorna a lista de tabelas do CACIC
		while ($row_tables = mysql_fetch_array($result_tables)) //Percorre as tabelas comandando a exclusão, conforme TE_NODE_ADDRESS e ID_SO						
			{
			$strTables .= ($strTables <> ''?',':'');
			$strTables .= $row_tables[0];
			}
		$arrTables = explode(',',$strTables);
			
		/*
		while ($row_consulta = mysql_fetch_row($result_tables)) //Percorre as tabelas comandando a exclusão, conforme TE_NODE_ADDRESS e ID_SO				
			{
			$v_query_consulta 	= 'SELECT count(id_so) FROM '.$row_consulta[0] .' WHERE concat(te_node_address,id_so) <> ""';
			if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
				echo 'v_query_consulta: '.$v_query_consulta.'<br>';
			
			$consulta 			= @mysql_query($v_query_consulta);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela função MYSQL_QUERY()
			if ($consulta)
				$strTripaTabelasValidas .= '#'.$row_consulta[0].'#';
			}							
		*/
		$strTripaTabelasValidas =  '#acoes_excecoes#
									#aplicativos_monitorados#
									#compartilhamentos#
									#componentes_estacoes#
									#componentes_estacoes_historico#
									#historico_hardware#
									#historico_tcp_ip#
									#historicos_hardware#
									#historicos_outros_softwares#
									#historicos_software#
									#historicos_software_completo#
									#officescan#
									#patrimonio#
									#softwares_inventariados_estacoes#
									#unidades_disco#
									#variaveis_ambiente_estacoes#
									#versoes_softwares#';			
			
		//				
		$v_cs_exclui = '';
		//
		$strTripaCampos = '';
		$intContaMaquinas = 0;
		while(list($key, $value) = each($HTTP_POST_VARS))
			{
			if (strpos($key,'chk_')>-1)
				{				
				$strTripaCampos .= ($strTripaCampos == ''?'':',');				
				$strTripaCampos .= '"#'.str_replace('chk_','',$key).'#"';
				$intContaMaquinas ++;
				}			
			}


//		for ($intContaMaquinasAux = 0; $intContaMaquinasAux <= $intContaMaquinas; $intContaMaquinasAux ++)		
//			{
			//if (!$result_tables) $result_tables	= mysql_list_tables($nome_bd); //Retorna a lista de tabelas do CACIC


			//$v_arr_exclui = explode('#',$key);
			conecta_bd_cacic();								
			$boolOK = false;
			for ($i = 0; $i < count($arrTables); $i++ ) //Percorre as tabelas comandando a exclusão, conforme TE_NODE_ADDRESS e ID_SO				
				{
//				GravaTESTES('Verificando se "'.'#'.$row_exclui[0].'#'.'" está em "'.$strTripaTabelasValidas.'"');									
				$boolOK = stripos2($strTripaTabelasValidas, '#'.$arrTables[$i].'#',false);
				if ($boolOK)
					{
					$v_query_exclui = 'DELETE FROM '.$arrTables[$i] .' WHERE concat("#",te_node_address,"#",id_so,"#") in ('.$strTripaCampos.')';
					$exclui 		= @mysql_query($v_query_exclui);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela função MYSQL_QUERY()									
//					GravaTESTES('Deleção de registros de "'.$row_exclui[0].'" => '.$exclui. ' => '.$v_query_exclui);					
					$v_cs_exclui = '1';
					}
				}			
			$v_query_exclui = 'DELETE FROM computadores WHERE concat("#",te_node_address,"#",id_so,"#") in ('.$strTripaCampos.')';
			$exclui 		= @mysql_query($v_query_exclui);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela função MYSQL_QUERY()												

			if ($v_cs_exclui)
				GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'computadores');

//			}
		}
	?>	
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
	<title><?=$oTranslator->_('Excluir Computadores');?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
	<link href="../include/cacic.css" rel="stylesheet" type="text/css">
	<SCRIPT>
	function Preenche_Condicao_VAZIO(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo)
					{
					window.document.forms[i].elements[j].value = '<VAZIO>';
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		}
	function Preenche_Condicao_NULO(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo)
					{
					window.document.forms[i].elements[j].value = '<NULL>';
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		}

	function Verifica_Condicoes_Seta_Campo(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo && window.document.forms[i].elements[j].value == '<VAZIO>')
					{
					window.document.forms[i].elements[j].value = '';
					window.document.forms[i].elements[j].disabled = false;										
					}
				}
			}		
		SetaCampo(p_campo);								
		}

	function Verifica_Selecao(p_campo,p_campo_selecao)
		{
		if (p_campo.value == '')
			{
			for (i=0;i<window.document.forms.length;i++)
				{
				for (j=0;j<window.document.forms[i].elements.length;j++)
					{
					if (window.document.forms[i].elements[j].name == p_campo_selecao)
						{
						window.document.forms[i].elements[j].value = '';
						}
					}
				}		
			}
		}

	
	function Valida_Form_Pesquisa(p_argumento)
		{
		var v_conteudo = '';
		var v_tamanho = 0;
		v_tamanho = p_argumento.length;
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name.substring(0,v_tamanho) == p_argumento && 
				    window.document.forms[i].elements[j].value != '')
					{
					v_conteudo = v_conteudo + window.document.forms[i].elements[j].value;
					}
				}
			}

		if (v_conteudo == '')
			{
			alert('<?=$oTranslator->_('Eh necessario informar ao menos uma condicao para pesquisa');?>!');
			return false;
			}

		return true;
		}			
	</SCRIPT>
	</head>

	<body background="../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
	<form name="form1" method="post">
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr> 
	
  	<td class="cabecalho"><?=$oTranslator->_('Excluir Computadores');?></td>
	</tr>
	<tr> 
	
  	<td class="descricao"><?=$oTranslator->_('kciq_msg Excluir Computadores advise');?></td>
	</tr>
	</table>
	<br><br>
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
    <td colspan="3"><div align="center"> 
   	<input name="submit_cond" type="submit" value="<?=$oTranslator->_('Selecionar computadores para exclusao');?>" onClick="return Valida_Form_Pesquisa('frm_te_valor_condicao_');">
   	</div></td>
   	</tr></table>

	<br><br>	
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0">
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>	
	<tr bgcolor="#CCCCCC"> 
  	<td class="destaque"><?=$oTranslator->_('Campo');?></font></strong></td>
  	<td class="destaque"><?=$oTranslator->_('Condicao');?></font></strong></td>
  	<td class="destaque"><?=$oTranslator->_('Valor para pesquisa');?></font></strong></td>
	</tr>
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
	
	<?
	$cor = 0;
	require_once('../include/library.php');

	$res_fields = mysql_query("SHOW COLUMNS FROM computadores");
	$v_arr_nomes_campos = array();
	while ($row_fields = mysql_fetch_array($res_fields)) 
		{
		$query_desc = 'SELECT 	* 
					   FROM 	descricoes_colunas_computadores 
					   WHERE 	TRIM(nm_campo) = "'. $row_fields[0].'"';
		$res_desc 	= @mysql_query($query_desc);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela função MYSQL_QUERY()
		if (!@$row_desc = mysql_fetch_array($res_desc))
			{
			$query_ins_desc = 'INSERT 
							   INTO 	descricoes_colunas_computadores 
							   SET 		nm_campo = "'. $row_fields[0].'", 
							   			te_descricao_campo="'.$row_fields[0].'", 
										nm_tipo_campo = "datetime"';
			$res_ins_desc 	= @mysql_query($query_ins_desc);
			//GravaLog('INS',$_SERVER['SCRIPT_NAME'],'descricoes_colunas_computadores');
			}
			
		if ($row_desc['cs_condicao_pesquisa']=='S')
			{
			array_push($v_arr_nomes_campos,$row_desc['te_descricao_campo'].'#'.$row_fields[0].'#'.$row_fields[1]);
			}
		}

	sort($v_arr_nomes_campos);
	for ($i=0;$i<count($v_arr_nomes_campos);$i++)
		{
		$v_arr_campo = explode('#',$v_arr_nomes_campos[$i]);
		?>
		<tr <? if ($cor) echo 'bgcolor="#E1E1E1"';?>> 
		<td nowrap><? echo $v_arr_campo[0];?></td>
		<td><select name="frm_condicao_<? echo $v_arr_campo[1]; ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
		<option value=""></option>
		<?
		if ($v_arr_campo[2] == 'datetime') 
			{
			$v_operacao = "(TO_DAYS(NOW())-TO_DAYS(a.".$v_arr_campo[1].")";
			?>
			<option value="<? echo $v_operacao . ' =       frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('Igual a');?></option>					
			<option value="<? echo $v_operacao . ' -MAIOR- frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('MAIOR QUE');?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>					
			<option value="<? echo $v_operacao . ' -MENOR- frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('MENOR QUE');?></option>											
			<?
			}
		else
			{
			?>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." =       'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('IGUAL A');?></option>		
			<option value="<? echo 'a.'      .$v_arr_campo[1]." <>      'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('DIFERENTE DE');?></option>			
			<option value="<? echo 'a.'      .$v_arr_campo[1]." -MAIOR- 'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('MAIOR QUE');?></option>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." -MENOR- 'frm_te_valor_condicao'"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('MENOR QUE');?></option>						
			<option value="<? echo 'a.'      .$v_arr_campo[1]." like    '%frm_te_valor_condicao%'";?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('CONTENHA');?></option>
			<option value="<? echo "'%frm_te_valor_condicao%' not like  (a.".$v_arr_campo[1].")  ";?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('NAO CONTENHA');?></option>			
			<option value="<? echo 'a.'      .$v_arr_campo[1]." like    'frm_te_valor_condicao%'" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('INICIE COM');?></option>
			<option value="<? echo 'a.'      .$v_arr_campo[1]." like    '%frm_te_valor_condicao'" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"><?=$oTranslator->_('TERMINE COM');?></option>				
			<option value="<? echo 'TRIM(a.'.$v_arr_campo[1].") = '' and " 					      ;?>" onClick="Preenche_Condicao_VAZIO('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"		 ><?=$oTranslator->_('SEJA VAZIO');?></option>		
			<option value="<? echo 'a.'.$v_arr_campo[1]." IS NULL " 					          ;?>" onClick="Preenche_Condicao_NULO('<? echo "frm_te_valor_condicao_". $v_arr_campo[1]; ?>');"		 ><?=$oTranslator->_('SEJA NULO');?></option>					
			<?
			}
			?>
		</select> </td>
		<td><input name="frm_te_valor_condicao_<? echo $v_arr_campo[1]; ?>" type="text" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);Verifica_Selecao(this,'<? echo "frm_condicao_". $v_arr_campo[1]; ?>');" size="60" maxlength="100"></td>
		</tr>
		<?			
		$cor=!$cor;
		}

	?>
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
	
	</table>
	<br><br>
	<table width="90%" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
	<td colspan="3"><div align="center"> 
   	<input name="submit_cond" type="submit" value="<?=$oTranslator->_('Selecionar computadores para exclusao');?>" onClick="return Valida_Form_Pesquisa('frm_te_valor_condicao_');">	
	</div></td>
	</tr></table>

	<p><p><p>  
	</form>
	</html>	
	<?			
	}
	?>
