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
AntiSpy();
conecta_bd_cacic();


if ($_POST['consultar']) 
	{					
	$_SESSION['str_consulta'] = $_POST['string_consulta'];	
	$_SESSION['tp_consulta'] = $_POST['tipo_consulta'];			
	}
//<!-- Inicio Marisol 24-07-06 Utilizado pela consulta rapida, valida se a informação veio atraves da consulta rapida -->
        
if ($_POST['tipo_consulta'] == "consulta_rapida"){
        
        // Verifica e valida se foi passado o endereço MAC
        if (eregi("[0-9a-z]{2}-[0-9a-z]{2}-[0-9a-z]{2}-[0-9a-z]{2}-[0-9a-z]{2}-[0-9a-z]{2}", $_SESSION['str_consulta'])) {
                $_SESSION['tp_consulta']='te_node_address';
       // Verifica e valida se foi passado o endereço IP 
        } elseif (eregi(".*\..*\..*\..*", $_SESSION['str_consulta'])) {
                $_SESSION['tp_consulta']='ip';
       // Se nao foi passado endereço MAC ou IP assume como nome da estação 
        } else {
                $_SESSION['tp_consulta']='nome';
        }
}
// <!-- Final Marisol 24-07-06 -->
	

if ($_GET['campo'])
	$orderby = ' ORDER BY '.$_GET['campo'];
else
	$orderby = ' ORDER BY te_nome_computador';
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="../imgs/linha_v.gif" onLoad="SetaCampo('tipo_consulta')">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<?

if (($_SESSION['tp_consulta'] == 'nome') or ($_SESSION['tp_consulta']== '')) 
	{
	$valor_padrao = '<option value="nome" selected>'.$oTranslator->_('Nome do Computador').'</option>
					 <option value="ip">'.$oTranslator->_('IP do Computador').'</option>
					 <option value="te_node_address">'.$oTranslator->_('Endereco MAC do Computador').'</option>';
	}

if (($_SESSION['tp_consulta'])== 'ip') 
	{
	$valor_padrao = '<option value="ip" selected>'.$oTranslator->_('IP do Computador').'</option>
					 <option value="te_node_address">'.$oTranslator->_('Endereco MAC do Computador').'</option>				 
					 <option value="nome">'.$oTranslator->_('Nome do Computador').'</option>';
	}

if (($_SESSION['tp_consulta'])== 'te_node_address') 
	{
	$valor_padrao = '<option value="te_node_address" selected>'.$oTranslator->_("Endereco MAC do Computador").'</option>
					 <option value="nome">'.$oTranslator->_("Nome do Computador").'</option>
					 <option value="ip">'.$oTranslator->_("IP do Computador").'</option>';
	}
?>
<form action="<? echo $PHP_SELF; ?>" method="post" name="form1">
<table width="90%" border="0" align="center">
<tr> 
      <td class="cabecalho"><?=$oTranslator->_('Consulta de Informacoes de Computadores');?></td>
</tr>
<tr> 
<td>&nbsp;</td>
</tr>
</table>
<tr><td height="1" colspan="2" bgcolor="#333333"></td></tr>
<tr><td height="30" colspan="2"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr><td colspan="2" class="label"><?=$oTranslator->_('Selecione os filtros da consulta:');?></td></tr>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
<td height="1" bgcolor="#333333"></td>
</tr>
<tr> 
<td height="28"><table width="96%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr> 
<td> 
<select name="tipo_consulta" id="select" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
<? echo $valor_padrao ;?> 
</select>
</td>
<td> 
<input name="string_consulta" type="text" id="string_consulta2" value="<? echo $_REQUEST['string_consulta'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
</td>
<td><input name="consultar" type="submit" id="consultar2" value="Consultar"></td>
</tr>
</table></td>
</tr>
<tr> 
<td height="1" bgcolor="#333333"></td>
</tr>
</table>
</form>
<?

if ($_POST['consultar'] || ($_GET['campo'])) 
	{
	$select1	=	" SELECT 	* "; 
	$from1		=	" FROM 		computadores, 
								so";
	$where1		=	" WHERE ";								
	$from2		=	($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?",redes,locais loc ":'');
	
	if($_SESSION['tp_consulta'] == 'nome') 
		{
		$where1	.=	" te_nome_computador like '%". $_SESSION['str_consulta'] ."%' ";
		}
	if($_SESSION['tp_consulta'] == 'ip') 
		{
		$where1	.=	" te_ip like '%". $_SESSION['str_consulta'] ."%' ";		
		}
	if($_SESSION['tp_consulta'] == 'te_node_address') 
		{
		$where1	.=	" te_node_address like '%". $_SESSION['str_consulta'] ."%' ";				
		}

	$where1	.= 	" AND computadores.id_so = so.id_so ";
	$where2	= 	($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?" AND computadores.id_ip_rede = redes.id_ip_rede AND redes.id_local = loc.id_local ":'');
	$where3	= ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND loc.id_local='.$_SESSION['id_local'].' ':'');

	if ($_SESSION['te_locais_secundarios']<>'' && $where3 <> '')
		{
		// Faço uma inserção de "(" para ajuste da lógica para consulta
		$where3 = str_replace('loc.id_local=','(loc.id_local=',$where3);
		$where3 .= ' OR loc.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
		}
			
					
							
	$query = $select1 . $from1 . $from2 . $where1 . $where2 . $where3 . $orderby;

	$result = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('computadores')));
	
	if ((strlen($_SESSION['str_consulta']) < 3) && ($_SESSION['tp_consulta'] == 'nome')) 
		{
		echo $mensagem = mensagem($oTranslator->_('Digite pelo menos 03 caracteres...'));
		}
	else
		{
		if(($nu_reg= mysql_num_rows($result))==0)
			{
			echo $mensagem = mensagem($oTranslator->_('Nenhum registro encontrado!'));
			}
		else
			{
			?>
			<p align="center" class="descricao"><?=$oTranslator->_('Clique sobre o nome da maquina para ver os detalhes da mesma');?></p>
			<table border="0" align="center" cellpadding="0" cellspacing="1">
			<tr> 
			<td height="1" bgcolor="#333333"></td>
			</tr>
			<tr> 
			<td> 
			<table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
			<tr bgcolor="#E1E1E1"> 
			<td align="center"  nowrap>&nbsp;</td>
			<td align="center"  nowrap>&nbsp;</td>
			<td align="center"  nowrap>&nbsp;</td>
			<td align="center"  nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=te_nome_computador"><?=$oTranslator->_('Nome da maquina');?></a></div></td>
		  	<td nowrap >&nbsp;</td>
			<td nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=te_ip"><?=$oTranslator->_('IP');?></a></div></td>
			<td nowrap >&nbsp;</td>
			<? 
			if($_SESSION['tp_consulta'] == 'te_node_address') 
				{
				?>
			 	<td nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=te_node_address">te_node_address</a></div></td>
				<td nowrap >&nbsp;</td>
				<?
				}
				?>
			<td nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=te_versao_cacic"><?=$oTranslator->_('Versao Cacic');?></a></div></td>
		  	<td nowrap >&nbsp;</td>
			<td nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=dt_hr_ult_acesso"><?=$oTranslator->_('Ultima coleta');?></a></div></td>
			<td nowrap >&nbsp;</td>
			</tr>
			<?  
			$Cor = 0;
			$NumRegistro = 1;
				
			while($row = mysql_fetch_array($result)) 
				{					  
			 	?>
				<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
				<td nowrap>&nbsp;</td>
				<td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
				<td nowrap>&nbsp;</td>
				<td nowrap class="opcao_tabela"><div align="left"><a href="computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
				<td nowrap>&nbsp;</td>
				<td nowrap class="opcao_tabela"><a href="computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_ip']; ?></a></td>
				<td nowrap>&nbsp;</td>
				<? 
				if($_SESSION['tp_consulta'] == 'te_node_address') 
					{ 
					?>
					<td nowrap class="opcao_tabela"><div align="center"><a href="computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_node_address']; ?></a></div></td>
					<td nowrap>&nbsp;</td>
					<? 
					}
					?>
				<td nowrap class="opcao_tabela"><div align="center"><a href="computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_versao_cacic']; ?></a></div></td>
				<td nowrap>&nbsp;</td>
				<td nowrap class="opcao_tabela"><div align="center"><a href="computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo date("d/m/Y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></a></div></td>
				<td nowrap>&nbsp;</td>
				<? 
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
			<?
			}
		}
	}
	?>
</body>
</html>
