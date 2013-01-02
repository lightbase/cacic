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

include_once "../include/library.php";
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

if (!$_REQUEST['date_input1'])
	{
	$from_usuarios  = '';
	$where_usuarios = '';
	$where_locais	= '';
	
	if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
		{
		$from_usuarios  = ', usuarios b';
		$where_usuarios = ' a.id_usuario = b.id_usuario ';
		$where_locais   = ' AND c.id_local = '.$_SESSION['id_local'];
		if (trim($_SESSION['te_locais_secundarios'])<>'')
			{
			$where_locais = str_replace(' AND c.id_local',' AND (c.id_local',$where_locais);
			$where_locais .= ' OR c.id_local IN ('.$_SESSION['te_locais_secundarios'].')) ';
			}		
		}
	
	conecta_bd_cacic();
	$query_minmax = 'SELECT 	DATE_FORMAT(min(a.dt_hr_ultimo_contato), "%d-%m-%Y") as minima,
								DATE_FORMAT(max(a.dt_hr_ultimo_contato), "%d-%m-%Y") as maxima
			  		 FROM 		srcacic_conexoes a '.
					 			$from_usuarios .' 
					 WHERE		'.
					 			$where_usuarios;

	$result_minmax = mysql_query($query_minmax);
	$row_minmax = mysql_fetch_array($result_minmax);
	$date_input1 = date('d/m/Y');
	$date_input2 = date('d/m/Y');	
	}
else
	{
	$date_input1 = $_REQUEST['date_input1'];
	$date_input2 = $_REQUEST['date_input2'];		
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title><?=$oTranslator->_('Log de Suporte Remoto');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
// JavaScripts para fazer a selecao entre os listbox, movendo itens entre eles.
require_once('../include/selecao_listbox.js');
?>

<style type="text/css">
<!--
.style2 {font-size: large}
-->
</style>
</head>

<body background="../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>

<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><div align="left"><?=$oTranslator->_('Log de Suporte Remoto');?></div></td>
  </tr>
  <tr> 
      <td class="descricao"><?=$oTranslator->_('kciq_msg Log de suporte remoto help');?></td>
  </tr>
</table>
	<p><br></p>
	
  <table width="90%" border="0" cellpadding="0" cellspacing="1" align="center">
    <tr> 
      <td class="label" colspan="3"><?=$oTranslator->_('Selecione o periodo no qual devera ser realizada a consulta');?>:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr valign="middle"> 
      <td width="33%" height="1" nowrap valign="middle">
	<input name="where_locais" type="hidden" value="<? echo $where_locais;?>"> 	  
<input name="date_input1" type="text" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $date_input1;?>"> 
<? /*
        <script type="text/javascript" language="JavaScript">
	<!--
	function calendar1Callback(date, month, year)	
		{
		document.forms['form1'].date_input1.value = date + '/' + month + '/' + year;
		}
  	calendar1 = new dynCalendar('calendar1', 'calendar1Callback');
	//-->
	</script>*/?> &nbsp; <font size="2" face="Verdana, Arial, Helvetica, sans-serif">a</font> 
        &nbsp;&nbsp; <input name="date_input2" type="text" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $date_input2;?>"> 
<? /*        <script type="text/javascript" language="JavaScript">
	<!--
	function calendar2Callback(date, month, year)	
		{
		document.forms['form1'].date_input2.value = date + '/' + month + '/' + year;
		}
  	calendar2 = new dynCalendar('calendar2', 'calendar2Callback');
	//-->
	</script>*/?> </td>
      <td align="left" class="descricao">(<?=$oTranslator->_('Formato da data');?> <?=$oTranslator->_('dd/mm/aaaa');?>)</td>
      <td rowspan="4" align="left" valign="middle" class="descricao"><div align="center">
          <input name="consultar" type="submit" value="   Filtrar   " onClick="SelectAll(this.form.elements['list12[]'])">
        </div></td>
    </tr>
    
	<?
	if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2 || ($_SESSION['cs_nivel_administracao'] == 3 && $_SESSION['te_locais_secundarios']<>''))
		{
		?>
		<TR><td height="20"></td></TR>
    	<tr valign="middle">
      	<td height="1" colspan="2" valign="middle" nowrap><div align="left">
	  	<?
		include_once "../include/selecao_locais_inc.php";	  
	  	?>
    	</div></td></tr>
		<?
		}
		?>
  <tr> 
  <td height="1" bgcolor="#333333" colspan="2"></td>
  </tr>
	
  </table>
	<?
	if ($_REQUEST['date_input1'])
		{
		$msg = '<div align="center">
		<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">'.
		$oTranslator->_('Nenhum suporte remoto realizado no periodo informado').'.</font><br><br></div>';
		
		$where = ($_REQUEST['nm_chamador']<>''?' AND b.id_usuario = '.$_REQUEST['id_usuario']:'');
		
		$OrderBy = ($_GET['OrderBy']<>''?$_GET['OrderBy']:'1');
		$OrderBy = ($OrderBy=='1' || $OrderBy=='3'?$OrderBy . ' DESC ':$OrderBy);		

		//$where_usuarios = '';
		$itens_locais = '';
                for ($i =0; $i < count($_POST['list12']);$i++)
                	{
                        if ($itens_locais)
                        	$itens_locais .= ',';
                        $itens_locais .= $_POST['list12'][$i];
                        }
                //$where_usuarios = ' AND b.id_local IN ('.$itens_locais.')';
                if (count($_POST['list12'])==0)
                	$msg = '<div align="center">
                                <font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
                                Nenhum suporte remoto realizado no <u>período informado</u> ou nenhum <u>local selecionado</>.</font><br><br></div>';
	
              	$where_locais   = ' AND c.id_local = '.$_SESSION['id_local'];
                if ($itens_locais <> '')
                        {
                        $where_locais = str_replace(' AND c.id_local',' AND (c.id_local',$where_locais);
                        $where_locais .= ' OR c.id_local IN ('.$itens_locais.')) ';
                        }

		conecta_bd_cacic();
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
							e.sg_so,
							a.id_sessao,
							aa.id_conexao
				  FROM 		srcacic_conexoes aa
				  			LEFT JOIN srcacic_chats aaa ON (aaa.id_conexao = aa.id_conexao)
				  			LEFT JOIN srcacic_sessoes  a  ON (a.id_sessao = aa.id_sessao) ,
				  			usuarios b, 
							locais c,
							computadores d,
							so e
				  WHERE 	a.id_sessao = aa.id_sessao AND				  			
				  			aa.id_usuario_cli = b.id_usuario AND
				  			b.id_local = c.id_local AND							
							aa.dt_hr_ultimo_contato between "' . substr($_REQUEST['date_input1'],-4)."-".substr($_REQUEST['date_input1'],3,2)."-".substr($_REQUEST['date_input1'],0,2).' 00:00" AND "' . substr($_REQUEST['date_input2'],-4)."-".substr($_REQUEST['date_input2'],3,2)."-".substr($_REQUEST['date_input2'],0,2). ' 23:59" '.
							$where .
							$where_locais . ' AND
							d.te_node_address = a.te_node_address_srv AND
							d.id_so = a.id_so_srv AND
							e.id_so = aa.id_so_cli
				  ORDER BY 	'.$OrderBy;
/*
... SELECT ...  dd.te_nome_computador te_nome_computador_cli,a.te_ip te_ip_cli,
... FROM ... computadores dd 
... WHERE ... dd.te_node_address = a.te_node_address_cli AND
                                   dd.id_so = a.id_so_cli
*/
//echo $query . '<br>';
		$result = mysql_query($query);
		$NumRegistro = mysql_num_rows($result);
		if ($NumRegistro)
			{
			?>	
			<p></p>				
	 		<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    		<tr> 
      		<td height="10" colspan="3" bgcolor="#CCCCCC" class="destaque"><div align="center" class="style2"><?=$oTranslator->_('Resultado da consulta');?></div></td>
    		</tr>
    		<tr> 
      		<td height="10" colspan="3"></td>
    		</tr>
    		<tr> 
      		<td height="1" colspan="3" bgcolor="#333333"></td>
    		</tr>
    		<tr> 
      		<td colspan="5"> 
			<table width="90%" border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
          	<tr bgcolor="#E1E1E1"> 
            <td colspan="3"></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_suporte_remoto.php?OrderBy=3&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Início de Sessão');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_suporte_remoto.php?OrderBy=7&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Estação Local');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_suporte_remoto.php?OrderBy=6&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Usuário Local');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_suporte_remoto.php?OrderBy=10&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Usuário Remoto');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_suporte_remoto.php?OrderBy=13&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Sigla S.O. Remoto');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_suporte_remoto.php?OrderBy=1&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Conexão - Último Contato');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_suporte_remoto.php#"><?=$oTranslator->_('Chat');?></a></div></td>
            <td></td>            
          	</tr>
          	<tr> 
            <td height="1" colspan="17" bgcolor="#333333"></td>
          	</tr>
          	<?  
			$Cor = 0;
			$NumRegistro = 0;
			$msg = ($NumRegistro > 1?'':$msg);	

			$strRegistroAnterior = '';
			while($row = mysql_fetch_array($result)) 
				{
				$strRegistroAtual = $row['id_sessao'] . $row['id_conexao'];
				if ($strRegistroAnterior <> $strRegistroAtual)
					{
					$strRegistroAnterior = $strRegistroAtual;
					$NumRegistro ++;
					}
				}
			
			mysql_data_seek($result,0);
			
			$strRegistroAnterior = '';
			while($row = mysql_fetch_array($result)) 
				{
				$strRegistroAtual = $row['id_sessao'] . $row['id_conexao'];
				if ($strRegistroAnterior <> $strRegistroAtual)
					{
					$strRegistroAnterior = $strRegistroAtual;
					
					list($year_inicio_sessao, $month_inicio_sessao, $day_inicio_sessao) = explode("-", $row['dt_hr_inicio_sessao']);
					list($day_inicio_sessao,$hour_inicio_sessao) = explode(" ",$day_inicio_sessao); 
				
					list($year_ultimo_contato, $month_ultimo_contato, $day_ultimo_contato) = explode("-", $row['dt_hr_ultimo_contato']);	
					list($day_ultimo_contato,$hour_ultimo_contato) = explode(" ",$day_ultimo_contato); 

					list($year_conexao, $month_conexao, $day_conexao) = explode("-", $row['dt_hr_inicio_conexao']);
					list($day_conexao,$hour_conexao) = explode(" ",$day_conexao); 

					?>                   
    	      		<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
        	    	<td nowrap></td>
	            	<td align="left" nowrap class="opcao_tabela" title="<? echo $row['te_motivo_conexao'];?>"><? echo $NumRegistro; ?></td>
	            	<td nowrap></td>
            		<td nowrap class="opcao_tabela" title="<? echo $row['te_motivo_conexao'];?>"><a href="detalha_conexao.php?id_conexao=<? echo $row['id_conexao'];?>"><? echo $day_inicio_sessao.'/'.$month_inicio_sessao.'/'.$year_inicio_sessao. ' '. substr($hour_inicio_sessao,0,5).'h';?></a></td>
            		<td nowrap></td>
					<td nowrap class="opcao_tabela" title="<? echo $row['te_motivo_conexao'];?>"><a href="detalha_conexao.php?id_conexao=<? echo $row['id_conexao'];?>"><? echo $row['te_ip_srv'].'/'.$row['te_nome_computador_srv'].' ('.$row['sg_local'].')';?></a></td>                
	            	<td nowrap></td>
    	        	<td nowrap class="opcao_tabela" title="<? echo $row['te_motivo_conexao'];?>"><a href="detalha_conexao.php?id_conexao=<? echo $row['id_conexao'];?>"><? echo $row['nm_completo_usuario_srv'];?></a></td>                
        	    	<td nowrap></td>
            		<td nowrap class="opcao_tabela" title="<? echo $row['te_motivo_conexao'];?>"><a href="detalha_conexao.php?id_conexao=<? echo $row['id_conexao'];?>"><? echo $row['nm_usuario_acesso'].'/'.$row['nm_usuario_completo'];?></a></td>	
	            	<td nowrap></td>
    	        	<td nowrap class="opcao_tabela" title="<? echo $row['te_motivo_conexao'];?>"><a href="detalha_conexao.php?id_conexao=<? echo $row['id_conexao'];?>"><? echo $row['sg_so'];?></a></td>
        	    	<td nowrap></td>
            		<td nowrap class="opcao_tabela" title="<? echo $row['te_motivo_conexao'];?>"><a href="detalha_conexao.php?id_conexao=<? echo $row['id_conexao'];?>"><? echo $day_conexao.'/'.$month_conexao.'/'.$year_conexao. ' '. substr($hour_conexao,0,5) . 'h - '.$day_ultimo_contato.'/'.$month_ultimo_contato.'/'.$year_ultimo_contato. ' '. substr($hour_ultimo_contato,0,5).'h';?></a></td>
            		<td nowrap></td>
	            	<td nowrap class="opcao_tabela">
					<? 
					if ($row['te_mensagem']<>'') 
						{
						?>
						<a href="detalha_conexao.php?id_conexao=<? echo $row['id_conexao'];?>"><img src="../imgs/chat.png" border="0" height="20" width="20"></a>
	                    <?
						}
						?>
    	             </td>
	            	<td nowrap></td>    
    	        	<? 
					$Cor=!$Cor;
					$NumRegistro--;										
					}
				}
			?>
        	</table></td>
			</tr>
			
			<?
			$msg = '';
			}
			?>
	    <tr> 
	    <td height="10" colspan="3"><? echo $msg;?></td>
	    </tr>		
		<?
		}		
		?>
    <tr> 
    <td colspan="3" height="3" align="center">
	<?
	if ($_REQUEST['nm_chamador'])
		{
		?>
	    <input name="Retorna" type="button" value="  <?=$oTranslator->_('Retorna para');?> <? echo str_replace("_"," ",$_REQUEST['nm_chamador']);?>  " onClick="history.back()">
		<?
		}
	?>

	</td>
    </tr>
  </table>
  <br>
</form>
<p>&nbsp;</p>
</body>
</html>
