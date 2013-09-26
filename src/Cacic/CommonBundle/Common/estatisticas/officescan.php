<?php
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
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
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
<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
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
<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>
<br>
<table width="85%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?php echo $oTranslator->_('Estatisticas de Antivirus OfficeScan');?></td>
  </tr>
  <tr> 
    <td class="descricao">
      <?php echo $oTranslator->_('Estatisticas de Antivirus OfficeScan - informe');?>
    </td>
  </tr>
  <tr> 
    <td> </td>
  </tr>
</table>
<br>
 <?php
$where = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	$where = ' AND locais.id_local = '.$_SESSION['id_local'];
	}

if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
	{
	// Fa�o uma inser��o de "(" para ajuste da l�gica para consulta	
	$where = str_replace(' locais.id_local = ',' (locais.id_local = ',$where);
	$where .= ' OR locais.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
	}
 
// Exibir informa��es do Engine
$query_engine = "SELECT 	o.nu_versao_engine, 
							COUNT(o.nu_versao_engine) as total_equip, 
							o.id_computador,
							sg_local as Local  
				 FROM 		computadores comp,
							redes,
							locais,
							officescan o 
				 WHERE 		trim(o.nu_versao_engine) <> '' and 
							o.nu_versao_engine <> '0' AND 	
							comp.id_computador = o.id_computador AND
							comp.id_rede = redes.id_rede AND 
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
        <?php echo $oTranslator->_('Estatisticas de Antivirus OfficeScan');?>
    </td>
  </tr>
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="18" align="center" class="cabecalho_tabela"><?php echo $oTranslator->_('Engine');?></td>
	<td width="50%">&nbsp;</td>
    <td height="18" align="center" class="cabecalho_tabela"><?php echo $oTranslator->_('Pattern');?></td>
  </tr>
  <tr valign="top"> 
    <td width="50%" align="center"><table width="100%" border="0">
        <tr align="center"> 
          <td width="50%" valign="top" bgcolor="#E1E1E1" class="cabecalho_tabela"><?php echo $oTranslator->_('Versao');?></td>
          <td width="50%" valign="top" bgcolor="#E1E1E1" class="cabecalho_tabela"><?php echo $oTranslator->_('Maquinas');?></td>
        </tr>
        <tr> 
          <?php while($reg_engine = mysql_fetch_row($result_query_engine)) 
		  		{ ?>
          		<td width="50%" align="left" class="opcao_tabela"> 
            	<a href="officescan.php?versao=<?php echo $reg_engine[0] ?> " target="_self"><?php echo $reg_engine[0] ?></a></td>
          		<td width="50%" align="center" class="opcao_tabela"><?php echo $reg_engine[1] ?></td>
   		</tr>
        		<?php echo $linha;
				}
				?> 
      </table>
      <?php
  
		// Informa��es do Pattern
		$query_pattern = "SELECT 	o.nu_versao_pattern, 
									COUNT(o.nu_versao_pattern) as total_equip,
									sg_local as Local
						  FROM 		officescan o,
						  			redes,
									computadores comp,
									locais 
						  WHERE 	trim(o.nu_versao_pattern) <> '' and 
						  			o.nu_versao_pattern <> '0' AND 	
									comp.id_computador = o.id_computador AND
									comp.id_rede = redes.id_rede AND 
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
          <td width="50%" valign="top" bgcolor="#E1E1E1" class="cabecalho_tabela"><?php echo $oTranslator->_('Versao');?></td>
          <td width="50%" valign="top" bgcolor="#E1E1E1" class="cabecalho_tabela"><?php echo $oTranslator->_('Maquinas');?></td>
        </tr>
        <tr> 
          <?php while($reg_pattern = mysql_fetch_row($result_query_pattern)) 
		  		{ ?>
          		<td width="50%" align="left" class="opcao_tabela"><a href="officescan.php?pattern=<?php echo $reg_pattern[0] ?>" target="_self"><?php echo $reg_pattern[0] ?> 
          		</td>
          		<td width="50%" align="center" class="opcao_tabela"><?php echo $reg_pattern[1] ?></td>
   		</tr>
        		<?php echo $linha;
				}
				?> 
	</table></td>
  </tr>  
</table>
</td>
</tr>
</table>
<?php	
//Mostrar computadores baseados no tipo de pesquisa solicitada pelo usu�rio
if ($_GET['campo'])
	$orderby = 'ORDER BY '.$_GET['campo'];
else
	$orderby = 'ORDER BY te_nome_computador';

if ($_GET['versao']) 
	{
	$query = "SELECT 	a.id_so, 
						a.te_nome_computador, 
						a.id_computador, 												
						a.te_ip_computador, a.te_node_address, 
					 	b.nu_versao_pattern, 
						b.dt_hr_coleta
			  FROM 		computadores a, 
			  			officescan b,
						locais,
						redes
			  WHERE 	a.id_computador = b.id_computdor AND
						a.id_rede = redes.id_rede AND
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
						a.id_computador, 						
						a.te_ip_computador, 
						a.te_node_address, 
					 	b.nu_versao_engine, 
						b.dt_hr_coleta
			  FROM 		computadores a, 
			  			officescan b,
						locais,
						redes
			  WHERE 	a.id_computador = b.id_computador AND
						a.id_rede = redes.id_rede AND
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
  <?php echo $oTranslator->_('Clique sobre o nome da maquina para ver os detalhes da mesma');?>
</p>


<table border="0" align="center" cellpadding="0" cellspacing="1">
	<tr> 
		<td align="center" nowrap><?php echo $_SESSION['tit_tabela'] ;?></td>
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
              <a href="officescan.php?<?php echo $_SESSION['tipo_ordem'] ?>=<?php echo $_SESSION['tipo_pesq'] ?>&campo=te_nome_computador">
              <?php echo $oTranslator->_('Nome da maquina');?>
              </a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">
              <a href="officescan.php?<?php echo $_SESSION['tipo_ordem'] ?>=<?php echo $_SESSION['tipo_pesq'] ?>&campo=te_ip_computador">
              <?php echo $oTranslator->_('IP');?>
              </a></div></td>
          <td nowrap >&nbsp;</td>		
          <td nowrap class="cabecalho_tabela"><div align="center"><a href="officescan.php?<?php echo $_SESSION['tipo_ordem'] ?>=<?php echo $_SESSION['tipo_pesq'] ?>&campo=nu_versao_pattern"><?php echo $_SESSION['tit_coluna'] ?></a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">
              <a href="officescan.php?<?php echo $_SESSION['tipo_ordem'] ?>=<?php echo $_SESSION['tipo_pesq'] ?>&campo=dt_hr_ult_acesso">
              <?php echo $oTranslator->_('Ultima coleta');?>
              </a></div></td>
          <td nowrap >&nbsp;</td>
        </tr>
        <?php  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) 
		{		  
	 	?>
        <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?> valign="top"> 
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><div align="left"><?php echo $NumRegistro; ?></div></td>
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><div align="left"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo $row['te_nome_computador']; ?></div></td>
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><?php echo $row['te_ip_computador']; ?></td>
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><div align="center"><?php echo $row['4']; ?></div></td>
        <td nowrap>&nbsp;</td>
        <td nowrap class="opcao_tabela"><div align="center"><?php echo date("d/m/Y H:i", strtotime( $row['dt_hr_coleta'] )); ?></div></td>
        <td nowrap>&nbsp;</td>
        <?php 
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
