<?php
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
 
//Remoção de computador
session_start();
/*
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para verificar permissões do usuário!
}
include_once "../include/library.php"; 
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

?>
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
<title><?php echo $oTranslator->_('Remocao de computador');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="2" topmargin="10" marginwidth="0" marginheight="0">
<?php if ($_POST['remove_sim']) //Caso o botão SIM seja pressionado...
	{
	conecta_bd_cacic();	
	$result 	= mysql_list_tables($nome_bd); //Retorna a lista de tabelas do CACIC
	while ($row = mysql_fetch_row($result)) //Percorre as tabelas comandando a exclusão, conforme TE_NODE_ADDRESS e ID_SO
		{		
		$query 		= 'DELETE FROM '.$row[0] .' WHERE id_computador = '. $_REQUEST['id_computador'];
		if (@mysql_query($query))	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela função MYSQL_QUERY()
			GravaLog('DEL',$_SERVER['SCRIPT_NAME'],$row[0],$_SESSION["id_usuario"]);				
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
	<div align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;<b><?php echo $oTranslator->_('Computador removido!');?></b></font></div>
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
	<input name="ok" 	type="submit" id="ok" 	value="<?php echo $oTranslator->_('Ok!');?>" onClick="window.close();">
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
	<?php	
	}
else
	{
     conecta_bd_cacic();	
     $query = "SELECT 	dt_hr_ult_acesso,
	 					te_nome_computador,
						te_versao_cacic,
						te_versao_gercols,
						te_ip_computador,dt_hr_inclusao,
						te_desc_so,
						dt_hr_ult_acesso 
				FROM 	computadores, so
		  		WHERE 	id_computador = ". $_GET['id_computador'] ." AND
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
	<?php echo $oTranslator->_('Confirma a remocao deste computador?');?></font></div>
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
	<input name="remove_sim" 	type="submit" id="remove_sim" 	value="<?php echo $oTranslator->_('Sim');?>" <?php echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
	&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; 
	<input name="remove_nao" 	type="submit" id="remove_nao" 	value="<?php echo $oTranslator->_('Nao');?>" onClick="window.close();">
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
	<?php
	}
	?>
</body>
</html>	