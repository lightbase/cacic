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
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if ($_REQUEST['Fechar']) 
	header ("Location: log_suporte_remoto.php");										

include_once "../include/library.php";
Conecta_bd_cacic();

$query = 'SELECT 	DATE_FORMAT(aa.dt_hr_inicio_conexao, "%y-%m-%d %H:%i") as dt_hr_inicio_conexao,
					DATE_FORMAT(aa.dt_hr_ultimo_contato, "%y-%m-%d %H:%i") as dt_hr_ultimo_contato,									
					DATE_FORMAT(a.dt_hr_inicio_sessao, "%y-%m-%d %H:%i") as dt_hr_inicio_sessao,
					aa.te_documento_referencial,
					aa.te_motivo_conexao,							
					a.nm_completo_usuario_srv,
					aaa.dt_hr_mensagem,
					aaa.te_mensagem,
					aaa.cs_origem,							
					d.te_ip te_ip_srv,
					d.te_nome_computador te_nome_computador_srv,
					b.nm_usuario_completo,														
					b.nm_usuario_acesso,																					
					b.id_usuario,							
					c.sg_local,
					e.te_desc_so te_desc_so_cli,
					e.te_so te_so_cli,
					f.te_desc_so te_desc_so_srv,
					f.te_so te_so_srv,					
					a.id_sessao,
					aa.id_conexao
		  FROM 		srcacic_conexoes aa
		  			LEFT JOIN srcacic_chats aaa ON (aaa.id_conexao = aa.id_conexao),
					srcacic_sessoes  a,
					usuarios b, 
					locais c,
					computadores d,
					so e,
					so f
		  WHERE 	aa.id_conexao = ' .$_GET['id_conexao'].' AND
		  			a.id_sessao = aa.id_sessao AND				  			
					aa.id_usuario_cli = b.id_usuario AND
					b.id_local = c.id_local AND
					d.te_node_address = a.te_node_address_srv AND
					d.id_so = a.id_so_srv AND
					e.id_so = aa.id_so_cli AND
					f.id_so = a.id_so_srv
		  ORDER BY 	aaa.dt_hr_mensagem DESC';

$result = mysql_query($query);
$row = mysql_fetch_array($result);

list($year_inicio_sessao, $month_inicio_sessao, $day_inicio_sessao) = explode("-", $row['dt_hr_inicio_sessao']);
list($day_inicio_sessao,$hour_inicio_sessao) = explode(" ",$day_inicio_sessao); 

list($year_ultimo_contato, $month_ultimo_contato, $day_ultimo_contato) = explode("-", $row['dt_hr_ultimo_contato']);	
list($day_ultimo_contato,$hour_ultimo_contato) = explode(" ",$day_ultimo_contato); 

list($year_conexao, $month_conexao, $day_conexao) = explode("-", $row['dt_hr_inicio_conexao']);
list($day_conexao,$hour_conexao) = explode(" ",$day_conexao); 

$strCorSrv = '#CCCCCC';
$strCorCli = '#FFFFCC';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title>Lista Chats Realizados nas Conex&otilde;es srCACIC</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>

<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td colspan="2" class="cabecalho">Detalhamento de  conexão para Suporte Remoto</td>
  </tr>
  <tr>
    <td nowrap class="destaque">&nbsp;</td>
    <td class="normal">&nbsp;</td>
  </tr>

  <tr>
    <td height="1" colspan="2" bgcolor="#333333"></td>
    </tr>
  
  <tr>
    <td bgcolor="<? echo $strCorSrv; ?>" colspan="2" class="cabecalho_secao" align="left">Estação Local</td>  
  </tr>
  <tr>
    <td nowrap bgcolor="<? echo $strCorSrv; ?>" class="destaque"><div align="right">Usu&aacute;rio:</div></td>
    <td bgcolor="<? echo $strCorSrv; ?>" class="normal"><? echo $row['nm_completo_usuario_srv']; ?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<? echo $strCorSrv; ?>" class="destaque"><div align="right">Sistema Operacional:</div></td>
    <td bgcolor="<? echo $strCorCli; ?>" class="normal"><? echo $row['te_desc_so_srv'].' ('.$row['te_so_srv'].')';?></td>
  </tr>
  <tr>
    <td width="16%" nowrap bgcolor="<? echo $strCorSrv; ?>" class="destaque"><div align="right">In&iacute;cio de Sess&atilde;o:</div></td>
    <td width="83%" bgcolor="<? echo $strCorSrv; ?>" class="normal"><? echo $day_inicio_sessao.'/'.$month_inicio_sessao.'/'.$year_inicio_sessao. ' '. substr($hour_inicio_sessao,0,5).'h'; ?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<? echo $strCorSrv; ?>" class="destaque"><div align="right">Identifica&ccedil;&atilde;o da Esta&ccedil;&atilde;o</div></td>
    <td bgcolor="<? echo $strCorSrv; ?>" class="normal"><? echo $row['te_ip_srv'].' / '.$row['te_nome_computador_srv'].' ('.$row['sg_local'].')'; ?></td>
  </tr>
  <tr>
    <td height="1" colspan="2" bgcolor="#FFFFFF"><BR></td>
    </tr>
  
  <tr>
    <td bgcolor="<? echo $strCorCli; ?>" colspan="2" class="cabecalho_secao" align="left">Estação Remota</td>    
    </tr>
  
  <tr>
    <td nowrap bgcolor="<? echo $strCorCli; ?>" class="destaque"><div align="right">Usu&aacute;rio:</div></td>
    <td bgcolor="<? echo $strCorCli; ?>" class="normal"><? echo $row['nm_usuario_acesso'].' / '.$row['nm_usuario_completo']; ?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<? echo $strCorCli; ?>" class="destaque"><div align="right">Sistema Operacional:</div></td>
    <td bgcolor="<? echo $strCorCli; ?>" class="normal"><? echo $row['te_desc_so_cli'].' ('.$row['te_so_cli'].')';?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<? echo $strCorCli; ?>" class="destaque"><div align="right">Documento Referencial:</div></td>
    <td bgcolor="<? echo $strCorCli; ?>" class="normal"><? echo $row['te_documento_referencial']; ?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<? echo $strCorCli; ?>" class="destaque"><div align="right">Descritivo  do Atendimento:</div></td>
    <td bgcolor="<? echo $strCorCli; ?>" class="normal"><? echo $row['te_motivo_conexao'];?></td>
  </tr>
  <tr>
    <td nowrap bgcolor="<? echo $strCorCli; ?>" class="destaque"><div align="right">Conex&atilde;o - Data/Hora &Uacute;ltimo Contato:</div></td>
    <td bgcolor="<? echo $strCorCli; ?>" class="normal"><? echo $day_ultimo_contato.'/'.$month_ultimo_contato.'/'.$year_ultimo_contato. ' '. substr($hour_ultimo_contato,0,5).'h'; ?></td>
  </tr>
  <tr>
    <td height="1" colspan="2" bgcolor="#333333"></td>
    </tr>
</table>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<BR>
  <tr>
    <td colspan="2" class="destaque_chat"><div align="center" class="destaque_chat">Chat</div></td>
  </tr>
  <tr>
    <td height="1" colspan="2" bgcolor="#333333"></td>
    </tr>
  
  <tr> 
    <td colspan="3"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" bordercolor="#333333">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela"><div align="center">Data/Hora</div></td>
          <td nowrap  class="cabecalho_tabela">Origem/Mensagem</td>
          </tr>
  <tr> 
    <td height="1" colspan="9" bgcolor="#333333"></td>
  </tr>
          
<?  
if(mysql_num_rows($result)==0) 
	{
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Nenhum diálogo realizado</font><br><br></div>';			
	}
else 
	{
	$Cor = 0;
	$NumRegistro = 1;
	mysql_data_seek($result,0);
	while($row = mysql_fetch_array($result)) 
		{		  
		if (trim($row['dt_hr_mensagem']) <> '')
			{
			list($year_mensagem, $month_mensagem, $day_mensagem) = explode("-", $row['dt_hr_mensagem']);
			list($day_mensagem,$hour_mensagem) = explode(" ",$day_mensagem); 		
			?>
			<tr bgcolor="<? echo ($row['cs_origem']=='srv'?$strCorSrv:$strCorCli);?>">
			<td><a name="<? echo $NumRegistro?>"></a></td>
			<td class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
			<td class="opcao_tabela"><div align="center"><? echo $day_mensagem.'/'.$month_mensagem.'/'.$year_mensagem. ' '. $hour_mensagem . 'h'; ?></div></td>
			<td class="opcao_tabela"><div align="left"><? echo ($row['cs_origem']=='srv'?$row['nm_completo_usuario_srv']:$row['nm_usuario_completo']) .': '. $row['te_mensagem']; ?> </div></td>
			<? 
			$Cor=!$Cor;
			$NumRegistro++;
			}
		}
	if ($NumRegistro == 1)
		{
		?>
		<tr align="center">
		<td colspan="4" class="Aviso">N&atilde;o Houve Chat Durante a Conex&atilde;o</td>
        </tr>
		<? 				
		}
	}

?>
  </table></td>
  </tr>
  <tr> 
    <td height="1" colspan="3" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="10" colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10" colspan="3"><? echo $msg;?></td>
  </tr>
  
</table>
</form>
<p>&nbsp;</p>
</body>
</html>
