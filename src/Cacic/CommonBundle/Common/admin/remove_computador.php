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
 
//Remo��o de computador
session_start();
/*
 * verifica se houve login e tamb�m as permiss�es de usu�rio
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para verificar permiss�es do usu�rio!
}
include_once "../include/library.php"; 
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administra��o
// 2 - Gest�o Central
// 3 - Supervis�o

?>
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title><?=$oTranslator->_('Remocao de computador');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="2" topmargin="10" marginwidth="0" marginheight="0">
<?
if ($_POST['remove_sim']) //Caso o bot�o SIM seja pressionado...
	{
	conecta_bd_cacic();	
	$result 	= mysql_list_tables($nome_bd); //Retorna a lista de tabelas do CACIC
	while ($row = mysql_fetch_row($result)) //Percorre as tabelas comandando a exclus�o, conforme TE_NODE_ADDRESS e ID_SO
		{		
		$query 		= 'DELETE FROM '.$row[0] .' WHERE te_node_address = "'. $_REQUEST['te_node_address'] . '" and id_so="'.$_REQUEST['id_so'].'"';
		if (@mysql_query($query))	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela fun��o MYSQL_QUERY()
			GravaLog('DEL',$_SERVER['SCRIPT_NAME'],$row[0]);				
		}			
	?>
	<table border="1" align="center" cellpadding="0" cellspacing="0">
	<br><br><br><br><br><br>
	<tr>
	<td><table border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E1E1E1">        
	<tr> 
	<td>
	<div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
	</font> 
	<tr> 
	<td nowrap>&nbsp;</td>
	</tr>				
	<tr><td nowrap>
	<div align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;<b><?=$oTranslator->_('Computador removido!');?></b></font></div>
	</td></tr>	
	<tr> 
	<td nowrap>&nbsp;</td>
	</tr>
	<tr> 
	<td nowrap>&nbsp;</td>
	</tr>				
	<tr>	
	<td nowrap><div align="center">		
	<form name="form1" method="post" action="">				
	<input name="ok" 	type="submit" id="ok" 	value="<?=$oTranslator->_('Ok!');?>" onClick="window.close();">
	</form></div>
	</td>				
	</tr>
	</div>
	</td>
	</tr>
	<tr> 
	<td>&nbsp;</td>
	</tr>
	</table></td>
	</tr>
	</table>
	<?	
	}
else
	{
     conecta_bd_cacic();	
     $query = "SELECT 	dt_hr_ult_acesso,
	 					te_nome_computador,
						te_versao_cacic,
						te_versao_gercols,
						te_ip,dt_hr_inclusao,
						te_desc_so,
						dt_hr_ult_acesso 
				FROM 	computadores, so
		  		WHERE 	te_node_address = '". $_GET['te_node_address'] ."' AND
		  				computadores.id_so = ". $_GET['id_so'] ." AND 
		  				computadores.id_so = so.id_so";

    $result = mysql_query($query);
	include_once "../relatorios/computador/inc_detalhes_computador.php"; 	
	?>
	<table border="1" align="center" cellpadding="0" cellspacing="0">
	<br><br><br>
	<tr>
	<td><table border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E1E1E1">        
	<tr> 
	<td>
	<div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
	</font> 
	<tr> 
	<td nowrap>&nbsp;</td>
	</tr>				
	<tr><td nowrap>
	<div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
	<?=$oTranslator->_('Confirma a remocao deste computador?');?></font></div>
	</td></tr>	
	<tr> 
	<td nowrap>&nbsp;</td>
	</tr>
	<tr> 
	<td nowrap>&nbsp;</td>
	</tr>				
	<tr>	
	<td nowrap><div align="center">		
	<form name="form1" method="post" action="">				
	<input name="remove_sim" 	type="submit" id="remove_sim" 	value="<?=$oTranslator->_('Sim');?>" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
	&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 
	<input name="remove_nao" 	type="submit" id="remove_nao" 	value="<?=$oTranslator->_('Nao');?>" onClick="window.close();">
	</form></div>
	</td>				
	</tr>
	</div>
	</td>
	</tr>
	<tr> 
	<td>&nbsp;</td>
	</tr>
	</table></td>
	</tr>
	</table>
	<?
	}
	?>
</body>
</html>	