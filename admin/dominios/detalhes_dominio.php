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

AntiSpy('1,2'); // Permitido somente a estes cs_nivel_administracao...

// 1 - Administração
// 2 - Gestão Central


conecta_bd_cacic();


if ($_POST['ExcluiDominio'] <> '' && $_SESSION['cs_nivel_administracao']==1) 
	{
	$query = "UPDATE 	dominios 
			  SET 		in_ativo = 'N'
			  WHERE 	id_dominio = ".$_POST['frm_id_dominio'];

	mysql_query($query) or die('Update falhou ou sua sessão expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'dominios');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/dominios/index.php&tempo=1");				
	}
else if ($_POST['GravaAlteracoes'] <> '' && $_SESSION['cs_nivel_administracao']==1) 
	{
	$query = "UPDATE 	dominios 
			  SET		nm_dominio 			= '".$_POST['frm_nm_dominio']         ."', 
				  		te_ip_dominio 		= '".$_POST['frm_te_ip_dominio']      ."',
						id_tipo_protocolo	= '".$_POST['frm_id_tipo_protocolo']  ."',
						nu_versao_protocolo	= '".$_POST['frm_nu_versao_protocolo']."',
						te_string_DN		= '".$_POST['frm_te_string_DN']       ."',
						te_observacao		= '".$_POST['frm_te_observacao']      ."'				  						
			  WHERE 	id_dominio 			=  ".$_POST['frm_id_dominio'];

	mysql_query($query) or die('Update falhou ou sua sessão expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'dominios');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/dominios/index.php&tempo=1");				
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		dominios 
			  WHERE     id_dominio = ".$_GET['id_dominio'];
	$result = mysql_query($query) or die ('Erro no acesso à tabela dominios ou sua sessão expirou!');
	
	$row = mysql_fetch_array($result);
	?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
    <title>Detalhes de Dom&iacute;nio</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <SCRIPT LANGUAGE="JavaScript">
    
    function valida_form() 
        {
    
        if ( document.form.frm_nm_dominio.value == "" ) 
            {	
            alert("O nome é obrigatório.");
            document.form.frm_nm_dominio.focus();
            return false;
            }		
        else if ( document.form.frm_te_ip_dominio.value == "" ) 
            {	
            alert("O IP é obrigatório.");
            document.form.frm_te_ip_dominio.focus();
            return false;
            }
        else if ( document.form.frm_id_tipo_protocolo.value == "" ) 
            {	
            alert("Selecione o Tipo de Protocolo.");
            document.form.frm_id_tipo_protocolo.focus();
            return false;
            }
        return true;	
        }
    </script>
    <style type="text/css">
<!--
.style2 {	font-size: 9px;
	color: #000099;
}
-->
    </style>
    </head>
    
    <body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_dominio');">
    <script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
    <table width="90%" border="0" align="center">
    <tr> 
    <td class="cabecalho">Detalhes do Dom&iacute;nio "<? echo $row['nm_dominio'];?>"</td>
    </tr>
    <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es referem-se a um dom&iacute;nio usado na autentica&ccedil;&atilde;o de usu&aacute;rios do suporte remoto seguro.</td>
    </tr>
    </table>
    <form action="detalhes_dominio.php"  method="post" ENCTYPE="multipart/form-data" name="form">
    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr>
    <td class="label"><br>Nome do Dom&iacute;nio:</td>
    <td nowrap class="label"><br>Endere&ccedil;o IP do Dom&iacute;nio:</td>
    </tr>
    <tr><td height="1" bgcolor="#333333" colspan="3"></td></tr>
    <tr> 
    <td class="label_peq_sem_fundo"> <input name="frm_nm_dominio" type="text" size="60" maxlength="60" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row['nm_dominio'];?>">
	<input name="frm_id_dominio" type="hidden" value="<? echo $_GET['id_dominio'];?>"></td>
    <td class="label_peq_sem_fundo"><input name="frm_te_ip_dominio" type="text" size="30" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_ip_dominio" value="<? echo $row['te_ip_dominio'];?>"></td>
    </tr>
    <tr> 
    <td class="label"><div align="left"><br>Protocolo:</div></td>
    <td class="label"><div align="left"><br>Vers&atilde;o:</div></td>
    </tr>
    <tr> 
    <td height="1" bgcolor="#333333" colspan="3"></td>
    </tr>
    <tr> 
    <td nowrap><label>
    <select name="frm_id_tipo_protocolo" class="opcao_tabela" id="frm_id_tipo_protocolo">
    <option value="LDAP" <? if ($row['id_tipo_protocolo']=='LDAP') echo 'selected';?>>LDAP</option>
    <option value="Open LDAP"<? if ($row['id_tipo_protocolo']=='Open LDAP') echo 'selected';?>>Open LDAP</option>
    </select>
    </label></td>
    <td class="label"><div align="left"><span class="label_peq_sem_fundo">
    <input name="frm_nu_versao_protocolo" type="text" size="30" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nu_versao_protocolo" value="<? echo $row['nu_versao_protocolo'];?>" >
    </span></div></td>
    </tr>
    <tr> 
    <td class="label"><br>
      String de Pesquisa: <span class="normal style2">(Ex.: o=dominio.com.br / DC=dominio, DC=com, DC=br)</span></td>
    <td class="label"><div align="left"><br>
      Observa&ccedil;&otilde;es:</div></td>
    </tr>
    <tr> 
    <td height="1" bgcolor="#333333" colspan="3"></td>
    </tr>
    <tr> 
    <td><span class="label_peq_sem_fundo">
    <input name="frm_te_string_DN" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_string_DN" value="<? echo $row['te_string_DN'];?>" >
    </span></td>
    <td><span class="label_peq_sem_fundo">
      <input name="frm_te_observacao" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_observacao" value="<? echo $row['te_observacao'];?>" >
    </span></td>   
    </tr>

    <tr> 
    <td colspan="3">&nbsp;</td>
    </tr>
    </table>
          
    <br>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
	<tr> 
	<td colspan="7" class="label">Redes Associadas ao Dom&iacute;nio:</td>
	</tr>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="7"></td>
	</tr>
	<?
	$query = "SELECT 	count(id_dominio) as Total
				FROM 	redes
				WHERE 	id_dominio = ".$_GET['id_dominio'];
	$result = mysql_query($query) or die ('Erro no acesso à tabela redes ou sua sessão expirou!');
	$rowRedesDominio = mysql_fetch_array($result);
	if ($rowRedesDominio['Total'] > 0)
		{	
		?>
		<tr>
		<td class="cabecalho_tabela">&nbsp;</td>
		<td class="cabecalho_tabela">&nbsp;</td>
		<td align="left" nowrap class="cabecalho_tabela">Local</td>
		<td class="cabecalho_tabela">&nbsp;</td>
		<td align="left" nowrap class="cabecalho_tabela">IP</td>
		<td align="left" class="cabecalho_tabela">&nbsp;</td>
		<td align="left" class="cabecalho_tabela">Rede</td>
		</tr>
		<tr> 
		<td height="1" bgcolor="#333333" colspan="7"></td>
		</tr>
		
		<?

		$query = "SELECT 	r.id_ip_rede,
					        r.nm_rede,
					        l.id_local,
					        l.sg_local,
					        l.nm_local 
					FROM 	redes r,
					        locais l
					WHERE 	r.id_dominio = ".$_GET['id_dominio'] ." AND
					        l.id_local = r.id_local
					ORDER BY  l.sg_local,l.nm_local,r.nm_rede";
		$result = mysql_query($query) or die ('Erro no acesso à tabela redes ou sua sessão expirou!');

		$seq = 1;
		$Cor = 1;	
		while ($row = mysql_fetch_array($result))
			{
			?>
			<tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
			<td width="3%" align="center" 	nowrap 	class="opcao_tabela"><a href="../redes/detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&nm_chamador=dominios"><? echo $seq; ?></a></td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela">&nbsp;&nbsp;</td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela"><a href="../locais/detalhes_local.php?id_local=<? echo $row['id_local'];?>&nm_chamador=dominios"><? echo $row['sg_local'].'/'.$row['nm_local']; ?></a></td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela">&nbsp;</td>
			<td width="3%" align="left" 	nowrap 	class="opcao_tabela"><a href="../redes/detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&nm_chamador=dominios"><? echo $row['id_ip_rede']; ?></a></td>
			<td width="1%" align="left" 			class="opcao_tabela">&nbsp;&nbsp;</td>
			<td width="92%" align="left" 			class="opcao_tabela"><a href="../redes/detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&nm_chamador=dominios"><? echo $row['nm_rede']; ?></a></td>
			</tr>
			<?
			$seq++;
			$Cor=!$Cor;
			}

		}
	else
		echo '<tr><td colspan="5" class="label_vermelho">Ainda não existem redes associadas ao domínio!</td></tr>';		
		?>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="7"></td>
	</tr>
	</table>
	<br>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
	<tr> 
	<td colspan="10" class="label">Usu&aacute;rios Associados ao Dom&iacute;nio:</td>
	</tr>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="10"></td>
	</tr>
	<?
	$query = "SELECT 	count(id_dominio) as Total
				FROM 	usuarios
				WHERE 	id_dominio = ".$_GET['id_dominio'];
	$result = mysql_query($query) or die ('Erro no acesso à tabela usuários ou sua sessão expirou!');
	$rowUsuariosDominio = mysql_fetch_array($result);
	if ($rowUsuariosDominio['Total'] > 0)
		{	
		?>
		<tr>
		<td class="cabecalho_tabela">&nbsp;</td>
		<td class="cabecalho_tabela">&nbsp;</td>
		<td align="left" class="cabecalho_tabela">Local</td>
		<td class="cabecalho_tabela">&nbsp;</td>
		<td align="left" nowrap class="cabecalho_tabela">Nome de Acesso</td>
		<td align="left" class="cabecalho_tabela">&nbsp;</td>
		<td align="left" class="cabecalho_tabela">Nome Completo</td>
		<td align="left" class="cabecalho_tabela">Email</td>
		<td align="left" class="cabecalho_tabela">Telefone/Ramal</td>
		<td align="left" class="cabecalho_tabela">&nbsp;</td>
		</tr>
		<tr> 
		<td height="1" bgcolor="#333333" colspan="10"></td>
		</tr>
		
		<?
		$query = "SELECT 	u.id_usuario,
					        u.nm_usuario_acesso,
					        u.nm_usuario_completo,
					        u.te_emails_contato,
					        u.te_telefones_contato,
					        l.sg_local,
					        l.nm_local,
					        l.id_local
					FROM 	usuarios u,
					        locais l
					WHERE 	u.id_dominio = ".$_GET['id_dominio']." AND
					        u.id_local = l.id_local
					ORDER BY  l.nm_local,u.nm_usuario_acesso";
		$result = mysql_query($query) or die ('Erro no acesso à tabela redes ou sua sessão expirou!');
		$seq = 1;
		$Cor = 1;	
		while ($row = mysql_fetch_array($result))
			{
			?>
			<tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
			<td width="3%" align="center" 	nowrap 	class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=dominios"><? echo $seq; ?></a></td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela">&nbsp;&nbsp;</td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela"><a href="../locais/detalhes_local.php?id_local=<? echo $row['id_local'];?>&nm_chamador=dominios"><? echo $row['sg_local'].'/'.$row['nm_local']; ?></a></td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela">&nbsp;</td>
			<td width="3%" align="left" 	nowrap 	class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=dominios"><? echo $row['nm_usuario_acesso']; ?></a></td>
			<td width="1%" align="left" 			class="opcao_tabela">&nbsp;&nbsp;</td>
			<td width="92%" align="left" 			class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=dominios"><? echo $row['nm_usuario_completo']; ?></a></td>
			<td width="92%" align="left" 			class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=dominios"><? echo $row['te_emails_contato']; ?></a></td>
			<td width="92%" align="left" 			class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=dominios"><? echo $row['te_telefones_contato']; ?></a></td>
			<td width="92%" align="left" 			class="opcao_tabela">&nbsp;</td>
			</tr>
			<?
			$seq++;
			$Cor=!$Cor;
			}
		}
	else
		echo '<tr><td colspan="5" class="label_vermelho">Ainda não existem usuários associados ao domínio!</td></tr>';		
		?>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="10"></td>
	</tr>
	</table>        

	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">        
	<tr><td colspan="5" align="center"><?
	if ($_SESSION['cs_nivel_administracao']==1)
		{
		?>
		<br>                
		<p>    
		<input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return Confirma('Confirma Informações para o Domínio?');return valida_form();">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="ExcluiDominio" type="submit" id="ExcluiDominio" onClick="return Confirma('Confirma Exclusão(Desativação) do Domínio?');" value="  Excluir/Desativar Dom&iacute;nio">
		</p>
		<?
		}
	?>
	</td></tr>      
	</table>
	</form>		              
	</body>
</html>
    <?
    }
?>
