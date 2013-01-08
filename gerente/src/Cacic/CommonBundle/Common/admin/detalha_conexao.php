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
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if ($_REQUEST['Fechar']) 
	header ("Location: log_suporte_remoto.php");										

include_once "../include/library.php";
Conecta_bd_cacic();

$queryCONEXAO = 'SELECT 	DATE_FORMAT(con.dt_hr_inicio_conexao, "%y-%m-%d %H:%i") as dt_hr_inicio_conexao,
							DATE_FORMAT(con.dt_hr_ultimo_contato, "%y-%m-%d %H:%i") as dt_hr_ultimo_contato,									
							DATE_FORMAT(ses.dt_hr_inicio_sessao,  "%y-%m-%d %H:%i") as dt_hr_inicio_sessao,
							con.te_documento_referencial,
							con.te_motivo_conexao,							
							ses.nm_completo_usuario_srv,
							com.te_ip_computador te_ip_srv,
							com.te_nome_computador te_nome_computador_srv,
							usu.nm_usuario_completo,														
							usu.nm_usuario_acesso,																					
							usu.id_usuario,							
							loc.sg_local,
							so1.te_desc_so te_desc_so_cli,
							so1.te_so te_so_cli,
							so2.te_desc_so te_desc_so_srv,
							so2.te_so te_so_srv
				  FROM 		srcacic_conexoes con,
							srcacic_sessoes  ses,
							usuarios 		 usu, 
							locais           loc,
							computadores     com,
							so 				 so1,
							so 				 so2
				  WHERE 	con.id_conexao     	= ' .$_GET['id_conexao'].' AND
				  			ses.id_sessao      	= con.id_sessao AND				  			
							con.id_usuario_cli 	= usu.id_usuario AND
							usu.id_local       	= loc.id_local AND
							com.id_computador   = ses.id_computador AND
							so1.id_so           = con.id_so_cli AND
							so2.id_so          	= ses.id_so_srv';

$resultCONEXAO = mysql_query($queryCONEXAO);
$rowCONEXAO = mysql_fetch_array($resultCONEXAO);

$strNomeUsuarioSRV = $rowCONEXAO['nm_completo_usuario_srv'];
$strNomeUsuarioCLI = $rowCONEXAO['nm_usuario_completo'];

list($year_inicio_sessao, $month_inicio_sessao, $day_inicio_sessao) = explode("-", $rowCONEXAO['dt_hr_inicio_sessao']);
list($day_inicio_sessao,$hour_inicio_sessao) = explode(" ",$day_inicio_sessao); 

list($year_ultimo_contato, $month_ultimo_contato, $day_ultimo_contato) = explode("-", $rowCONEXAO['dt_hr_ultimo_contato']);	
list($day_ultimo_contato,$hour_ultimo_contato) = explode(" ",$day_ultimo_contato); 

list($year_conexao, $month_conexao, $day_conexao) = explode("-", $rowCONEXAO['dt_hr_inicio_conexao']);
list($day_conexao,$hour_conexao) = explode(" ",$day_conexao); 

$strCorSrv = '#CCCCCC';
$strCorCli = '#FFFFCC';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
<title>Detalhamento de Conexão para Suporte Remoto Seguro - srCACIC</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>

<form name="form1" method="post" action="">
<table width="85%" border="0" align="center">
  <tr> 
    <td colspan="2" class="cabecalho">Detalhamento de conexão para Suporte Remoto</td>
  </tr>
  <tr>
    <td nowrap class="destaque">&nbsp;</td>
    <td class="normal">&nbsp;</td>
  </tr>

  <tr>
    <td height="1" colspan="2" bgcolor="#333333"></td>
    </tr>
  
  <tr>
    <td bgcolor="<?php echo $strCorSrv; ?>" colspan="2" class="cabecalho_secao" align="left">Estação Local (Visitada)</td>  
  </tr>
  <tr>
    <td nowrap bgcolor="<?php echo $strCorSrv; ?>" class="destaque"><div align="right">Usu&aacute;rio:</div></td>
    <td bgcolor="<?php echo $strCorSrv; ?>" class="normal"><?php echo $strNomeUsuarioSRV; ?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<?php echo $strCorSrv; ?>" class="destaque"><div align="right">Sistema Operacional:</div></td>
    <td bgcolor="<?php echo $strCorCli; ?>" class="normal"><?php echo $rowCONEXAO['te_desc_so_srv'].' ('.$rowCONEXAO['te_so_srv'].')';?></td>
  </tr>
  <tr>
    <td width="16%" nowrap bgcolor="<?php echo $strCorSrv; ?>" class="destaque"><div align="right">In&iacute;cio de Sess&atilde;o:</div></td>
    <td width="83%" bgcolor="<?php echo $strCorSrv; ?>" class="normal"><?php echo $day_inicio_sessao.'/'.$month_inicio_sessao.'/'.$year_inicio_sessao. ' '. substr($hour_inicio_sessao,0,5).'h'; ?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<?php echo $strCorSrv; ?>" class="destaque"><div align="right">Identifica&ccedil;&atilde;o da Esta&ccedil;&atilde;o:</div></td>
    <td bgcolor="<?php echo $strCorSrv; ?>" class="normal"><?php echo $rowCONEXAO['te_ip_srv'].' / '.$rowCONEXAO['te_nome_computador_srv'].' ('.$rowCONEXAO['sg_local'].')'; ?></td>
  </tr>
  <tr>
    <td height="1" colspan="2" bgcolor="#FFFFFF"><BR></td>
    </tr>
  
  <tr>
    <td bgcolor="<?php echo $strCorCli; ?>" colspan="2" class="cabecalho_secao" align="left">Estação Remota (Visitante)</td>    
    </tr>
  
  <tr>
    <td nowrap bgcolor="<?php echo $strCorCli; ?>" class="destaque"><div align="right">Usu&aacute;rio:</div></td>
    <td bgcolor="<?php echo $strCorCli; ?>" class="normal"><?php echo $strNomeUsuarioCLI; ?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<?php echo $strCorCli; ?>" class="destaque"><div align="right">Sistema Operacional:</div></td>
    <td bgcolor="<?php echo $strCorCli; ?>" class="normal"><?php echo $rowCONEXAO['te_desc_so_cli'].' ('.$rowCONEXAO['te_so_cli'].')';?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<?php echo $strCorCli; ?>" class="destaque"><div align="right">Documento Referencial:</div></td>
    <td bgcolor="<?php echo $strCorCli; ?>" class="normal"><?php echo $rowCONEXAO['te_documento_referencial']; ?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<?php echo $strCorCli; ?>" class="destaque"><div align="right">Descritivo  do Atendimento:</div></td>
    <td bgcolor="<?php echo $strCorCli; ?>" class="normal"><?php echo $rowCONEXAO['te_motivo_conexao'];?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<?php echo $strCorCli; ?>" class="destaque"><div align="right">Conex&atilde;o - Data/Hora &Uacute;ltimo Contato:</div></td>
    <td bgcolor="<?php echo $strCorCli; ?>" class="normal"><?php echo $day_ultimo_contato.'/'.$month_ultimo_contato.'/'.$year_ultimo_contato. ' '. substr($hour_ultimo_contato,0,5).'h'; ?></td>
  </tr>
  <tr>
    <td height="1" colspan="2" bgcolor="#333333"></td>
    </tr>
</table>
<table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr><td class="destaque_chat" colspan="5" height="10"></td></tr>
<tr><td colspan="5" class="destaque_chat"><div align="center" class="destaque_chat">Chat</div></td></tr>
<tr><td height="1" colspan="5" bgcolor="#333333"></td></tr>
  
<tr> 
<tr bgcolor="#E1E1E1"> 
<td align="center"  nowrap class="cabecalho_tabela"><div align="right">Seq.</div></td>        
<td align="center"  nowrap class="cabecalho_tabela"><div align="center">Data/Hora</div></td>
<td colspan="3"     nowrap class="cabecalho_tabela">Origem/Mensagem</td>
</tr>

<tr><td height="1" colspan="5" bgcolor="#333333"></td></tr>
          
<?php $queryCHAT = 'SELECT 		dt_hr_mensagem,
								te_mensagem,
								cs_origem							
			  		FROM 		srcacic_chats
			  		WHERE 		id_conexao = ' .$_GET['id_conexao'] . ' 
			  		ORDER BY	dt_hr_mensagem';

$resultCHAT = mysql_query($queryCHAT);
$NumRegistro = 1;
while($row = mysql_fetch_array($resultCHAT)) 
	{		  
	if (trim($row['dt_hr_mensagem']) <> '')
		{
		list($year_mensagem, $month_mensagem, $day_mensagem) = explode("-", $row['dt_hr_mensagem']);
		list($day_mensagem,$hour_mensagem) = explode(" ",$day_mensagem); 		
		?>
		<tr bgcolor="<?php echo ($row['cs_origem']=='srv'?$strCorSrv:$strCorCli);?>">
		<td class="opcao_tabela"><div align="right"><?php echo $NumRegistro; ?></div></td>
		<td class="opcao_tabela"><div align="center"><?php echo $day_mensagem.'/'.$month_mensagem.'/'.$year_mensagem. ' '. $hour_mensagem . 'h'; ?></div></td>
		<td class="opcao_tabela" colspan="3"><div align="left"><?php echo ($row['cs_origem']=='srv'?$strNomeUsuarioSRV:$strNomeUsuarioCLI) .': '. $row['te_mensagem']; ?> </div></td>
		<?php
		$NumRegistro++;
		}
	}
if ($NumRegistro == 1)
	{
	?>
	<tr align="center"><td colspan="5" class="Aviso">N&atilde;o Houve Chat Durante a Conex&atilde;o</td></tr>
	<?php 
	}
?>
<tr><td class="destaque_chat" colspan="5" height="10"></td></tr>

<tr><td colspan="5" class="destaque_chat"><div align="center" class="destaque_chat">Ações</div></td></tr>
<tr><td height="1" colspan="5" bgcolor="#333333"></td></tr>
  
<tr bgcolor="#E1E1E1"> 
<td align="center"  nowrap class="cabecalho_tabela"><div align="right">Seq.</div></td>        
<td align="center"  nowrap class="cabecalho_tabela"><div align="center">Data/Hora</div></td>        
<td nowrap  class="cabecalho_tabela">Ação</td>          
<td nowrap  class="cabecalho_tabela">Parâmetro 1</td>          
<td nowrap  class="cabecalho_tabela">Parâmetro 2</td>          
</tr>

<tr><td height="1" colspan="5" bgcolor="#333333"></td></tr>
          
<?php $queryACTIONS = 'SELECT 	dt_hr_action,
								te_action,
								te_param1,
								te_param2															
					  FROM 		srcacic_actions
					  WHERE 	id_conexao = ' .$_GET['id_conexao'] . ' 
					  ORDER BY 	dt_hr_action';
$resultACTIONS = mysql_query($queryACTIONS);

$Cor = 0;
$NumRegistro = 1;
while($row = mysql_fetch_array($resultACTIONS)) 
	{		  
	list($year_action, $month_action, $day_action) = explode("-", $row['dt_hr_action']);
	list($day_action,$hour_action) = explode(" ",$day_action); 		
	?>
	<tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
	<td class="opcao_tabela"><div align="left"><?php echo $NumRegistro; ?></div></td>
	<td class="opcao_tabela"><div align="center"><?php echo $day_action.'/'.$month_action.'/'.$year_action. ' '. $hour_action . 'h'; ?></div></td>
	<td class="opcao_tabela"><div align="left"><?php echo $row['te_action']; ?> </div></td>
	<td class="opcao_tabela"><div align="left"><?php echo $row['te_param1']; ?> </div></td>
	<td class="opcao_tabela"><div align="left"><?php echo $row['te_param2']; ?> </div></td>
	</tr>
	<?php $Cor=!$Cor;
	$NumRegistro++;
	}
if ($NumRegistro == 1)
	{
	?>
	<tr align="center"><td colspan="5" class="Aviso">N&atilde;o Houve Ações Durante a Conex&atilde;o</td></tr>
	<?php 
	}
	?>
<tr><td height="1"  colspan="5" bgcolor="#333333"></td></tr>
<tr><td height="10" colspan="5">&nbsp;</td></tr>
<tr><td height="10" colspan="3"><?php echo $msg;?></td></tr>
</table>
</form>
<p>&nbsp;</p>
</body>
</html>
