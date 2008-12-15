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
AntiSpy('1,2,3');

conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title>OfficeScan</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script LANGUAGE="JavaScript">
<!-- Begin
function open_window(theURL,winName,features) { 
    window.open(theURL,winName,features);
}
//-->
</script>
</head>
<body bgcolor="#FFFFFF" background="../imgs/linha_v.gif" leftmargin="2" topmargin="10" marginwidth="0" marginheight="0">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<br>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?=$oTranslator->_('Estatisticas de Antivirus OfficeScan');?></td>
  </tr>
  <tr> 
    <td class="descricao">
      <?=$oTranslator->_('Estatisticas de Antivirus OfficeScan - informe');?>
    </td>
  </tr>
  <tr> 
    <td> </td>
  </tr>
</table>
<br>
 <?
$where = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	$where = ' AND locais.id_local = '.$_SESSION['id_local'];
	}

if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
	{
	// Faço uma inserção de "(" para ajuste da lógica para consulta	
	$where = str_replace(' locais.id_local = ',' (locais.id_local = ',$where);
	$where .= ' OR locais.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
	}
 
// Exibir informações do Engine
$query_engine = "SELECT 	o.nu_versao_engine, 
							COUNT(o.nu_versao_engine) as total_equip, 
							o.te_node_address,
							sg_local as Local  
				 FROM 		computadores comp,
							redes,
							locais,
							officescan o 
				 WHERE 		trim(o.nu_versao_engine) <> '' and 
							o.nu_versao_engine <> '0' AND 	
							comp.te_node_address = o.te_node_address AND
							comp.id_so = o.id_so AND
							comp.id_ip_rede = redes.id_ip_rede AND 
							redes.id_local = locais.id_local ".								
							$where . " 
				 GROUP BY 	o.nu_versao_engine
				 ORDER BY	total_equip DESC";							 

$result_query_engine = mysql_query($query_engine);

?>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="1">
<tr valign="top">
<td valign="top">
<table width="450" height="62" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr align="center" bordercolor="#FFFFFF" bgcolor="#E1E1E1"> 
    <td height="18" colspan="3" class="cabecalho_tabela">
        <?=$oTranslator->_('Estatisticas de Antivirus OfficeScan');?>
    </td>
  </tr>
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="18" align="center" class="cabecalho_tabela"><?=$oTranslator->_('Engine');?></td>
	<td width="50%">&nbsp;</td>
    <td height="18" align="center" class="cabecalho_tabela"><?=$oTranslator->_('Pattern');?></td>
  </tr>
  <tr valign="top"> 
    <td width="50%" align="center"><table width="100%" border="0">
        <tr align="center"> 
          <td width="50%" valign="top" bgcolor="#E1E1E1" class="cabecalho_tabela"><?=$oTranslator->_('Versao');?></td>
          <td width="50%" valign="top" bgcolor="#E1E1E1" class="cabecalho_tabela"><?=$oTranslator->_('Maquinas');?></td>
        </tr>
        <tr> 
          <? while($reg_engine = mysql_fetch_row($result_query_engine)) 
		  		{ ?>
          		<td width="50%" align="left" class="opcao_tabela"> 
            	<a href="officescan.php?versao=<? echo $reg_engine[0] ?> " target="_self"><? echo $reg_engine[0] ?></a></td>
          		<td width="50%" align="center" class="opcao_tabela"><? echo $reg_engine[1] ?></td>
   		</tr>
        		<? echo $linha;
				}
				?> 
      </table>
      <?
  
		// Informações do Pattern
		$query_pattern = "SELECT 	o.nu_versao_pattern, 
									COUNT(o.nu_versao_pattern) as total_equip,
									sg_local as Local
						  FROM 		officescan o,
						  			redes,
									computadores comp,
									locais 
						  WHERE 	trim(o.nu_versao_pattern) <> '' and 
						  			o.nu_versao_pattern <> '0' AND 	
									comp.te_node_address = o.te_node_address AND
									comp.id_so = o.id_so AND
									comp.id_ip_rede = redes.id_ip_rede AND 
									redes.id_local = locais.id_local ".								
									$where . " 									
						  GROUP BY  o.nu_versao_pattern
						  ORDER BY	total_equip DESC";

		$result_query_pattern = mysql_query($query_pattern);
	  	?>
    </td>
	<td>&nbsp;</td>
    <td width="50%" align="center"><table width="100%" border="0">
        <tr align="center"> 
          <td width="50%" valign="top" bgcolor="#E1E1E1" class="cabecalho_tabela"><?=$oTranslator->_('Versao');?></td>
          <td width="50%" valign="top" bgcolor="#E1E1E1" class="cabecalho_tabela"><?=$oTranslator->_('Maquinas');?></td>
        </tr>
        <tr> 
          <? while($reg_pattern = mysql_fetch_row($result_query_pattern)) 
		  		{ ?>
          		<td width="50%" align="left" class="opcao_tabela"><a href="officescan.php?pattern=<? echo $reg_pattern[0] ?>" target="_self"><? echo $reg_pattern[0] ?> 
          		</td>
          		<td width="50%" align="center" class="opcao_tabela"><? echo $reg_pattern[1] ?></td>
   		</tr>
        		<? echo $linha;
				}
				?> 
	</table></td>
  </tr>  
</table>
</td>
</tr>
</table>
<?	
//Mostrar computadores baseados no tipo de pesquisa solicitada pelo usuário
if ($_GET['campo'])
	$orderby = 'ORDER BY '.$_GET['campo'];
else
	$orderby = 'ORDER BY te_nome_computador';

if ($_GET['versao']) 
	{
	$query = "SELECT 	a.id_so, 
						a.te_nome_computador, 
						a.te_ip, a.te_node_address, 
					 	b.nu_versao_pattern, 
						b.dt_hr_coleta
			  FROM 		computadores a, 
			  			officescan b,
						locais,
						redes
			  WHERE 	a.te_node_address = b.te_node_address AND
			  			a.id_so = b.id_so AND
						a.id_ip_rede = redes.id_ip_rede AND
						redes.id_local = locais.id_local AND 
						b.nu_versao_engine = '". $_GET['versao'] ."' ".
						$where." 
			 $orderby ";

	$_SESSION['tipo_pesq'] = $_GET['versao'];
	$_SESSION['tipo_ordem'] = 'versao';
	$_SESSION['tit_coluna'] = 'Pattern';
	$_SESSION['tit_tabela'] = '<font size="2" face="Verdana, Arial, Helvetica, sans-serif">'.$oTranslator->_('Relacao de maquinas com o engine').'<b>' . $_GET['versao']. '</b></font>';
	}
	
if ($_GET['pattern']) 
	{
	$query = "SELECT 	a.id_so, 
						a.te_nome_computador, 
						a.te_ip, 
						a.te_node_address, 
					 	b.nu_versao_engine, 
						b.dt_hr_coleta
			  FROM 		computadores a, 
			  			officescan b,
						locais,
						redes
			  WHERE 	a.te_node_address = b.te_node_address AND
			  			a.id_so = b.id_so AND
						a.id_ip_rede = redes.id_ip_rede AND
						redes.id_local = locais.id_local AND
						b.nu_versao_pattern = '". $_GET['pattern'] ."' ".
						$where . " 
			  $orderby ";

	$_SESSION['tipo_pesq'] = $_GET['pattern'];
	$_SESSION['tipo_ordem'] = 'pattern';
	$_SESSION['tit_coluna'] = 'Engine';
	$_SESSION['tit_tabela'] = '<font size="2" face="Verdana, Arial, Helvetica, sans-serif">'.$oTranslator->_('Relacao de maquinas com o pattern').'<b>' . $_GET['pattern']. '</b></font>';
	}	
//if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
//	echo 'query: '.$query.'<br>';

if ($_GET['versao'] || $_GET['pattern'])	
	$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('officescan')));
?>
<p align="center" class="descricao">
  <?=$oTranslator->_('Clique sobre o nome da maquina para ver os detalhes da mesma');?>
</p>


<table border="0" align="center" cellpadding="0" cellspacing="1">
	<tr> 
		<td align="center" nowrap><? echo $_SESSION['tit_tabela'] ;?></td>
	</tr>
	<tr> 
		<td height="1" bgcolor="#333333"></td>
	</tr>
  <tr valign="top"> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1" valign="top"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="center">
              <a href="officescan.php?<? echo $_SESSION['tipo_ordem'] ?>=<? echo $_SESSION['tipo_pesq'] ?>&campo=te_nome_computador">
              <?=$oTranslator->_('Nome da maquina');?>
              </a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">
              <a href="officescan.php?<? echo $_SESSION['tipo_ordem'] ?>=<? echo $_SESSION['tipo_pesq'] ?>&campo=te_ip">
              <?=$oTranslator->_('IP');?>
              </a></div></td>
          <td nowrap >&nbsp;</td>		
          <td nowrap class="cabecalho_tabela"><div align="center"><a href="officescan.php?<? echo $_SESSION['tipo_ordem'] ?>=<? echo $_SESSION['tipo_pesq'] ?>&campo=nu_versao_pattern"><? echo $_SESSION['tit_coluna'] ?></a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">
              <a href="officescan.php?<? echo $_SESSION['tipo_ordem'] ?>=<? echo $_SESSION['tipo_pesq'] ?>&campo=dt_hr_ult_acesso">
              <?=$oTranslator->_('Ultima coleta');?>
              </a></div></td>
          <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) 
		{		  
	 	?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?> valign="top"> 
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><div align="left"><a href="../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></div></td>
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><? echo $row['te_ip']; ?></td>
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><div align="center"><? echo $row['4']; ?></div></td>
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><div align="center"><? echo date("d/m/Y H:i", strtotime( $row['dt_hr_coleta'] )); ?></div></td>
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
&nbsp; 
<p>&nbsp;</p>
<p>&nbsp; </p>
</body>
</html>
