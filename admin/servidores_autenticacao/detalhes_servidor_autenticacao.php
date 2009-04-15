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


if ($_POST['ExcluiServidorAutencicacao'] <> '' && $_SESSION['cs_nivel_administracao']==1) 
	{
	$query = "UPDATE 	servidores_autenticacao 
			  SET 		in_ativo = 'N'
			  WHERE 	id_servidor_autenticacao = ".$_POST['frm_id_servidor_autenticacao'];

	mysql_query($query) or die('Update falhou ou sua sessão expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'servidores_autenticacao');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/servidores_autenticacao/index.php&tempo=1");				
	}
else if ($_POST['GravaAlteracoes'] <> '' && $_SESSION['cs_nivel_administracao']==1) 
	{
	$in_ativo = ($_POST['frm_in_ativo']=='S'?$_POST['frm_in_ativo']:'N');
					
	$query = "UPDATE 	servidores_autenticacao 
			  SET		nm_servidor_autenticacao 	= '".$_POST['frm_nm_servidor_autenticacao'] 	."', 
				  		te_ip_servidor_autenticacao	= '".$_POST['frm_te_ip_servidor_autenticacao']	."',
						id_tipo_protocolo			= '".$_POST['frm_id_tipo_protocolo']  			."',
						nu_versao_protocolo			= '".$_POST['frm_nu_versao_protocolo']			."',
						te_base_consulta_raiz		= '".$_POST['frm_te_base_consulta_raiz']    	."',
						te_base_consulta_folha		= '".$_POST['frm_te_base_consulta_folha']   	."',
						te_atributo_identificador	= '".$_POST['frm_te_atributo_identificador']	."',
						te_atributo_retorna_nome	= '".$_POST['frm_te_atributo_retorna_nome'] 	."',
						te_atributo_retorna_email	= '".$_POST['frm_te_atributo_retorna_email']	."',														
						te_observacao				= '".$_POST['frm_te_observacao']      			."',				  			  
						in_ativo					= '".$in_ativo 	     							."'				  			  						
			  WHERE 	id_servidor_autenticacao 	=  ".$_POST['frm_id_servidor_autenticacao'];
			
	mysql_query($query) or die('Update falhou ou sua sessão expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'servidores_autenticacao');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/servidores_autenticacao/index.php&tempo=1");				
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		servidores_autenticacao 
			  WHERE     id_servidor_autenticacao = ".$_GET['id_servidor_autenticacao'];
	$result = mysql_query($query) or die ('Erro no acesso à tabela servidores_autenticacao ou sua sessão expirou!');
	
	$row = mysql_fetch_array($result);
	?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
    <title>Detalhes de Servidor de Autentica&ccedil;&atilde;o</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <SCRIPT LANGUAGE="JavaScript">
    
    function valida_form() 
        {
    
        if ( document.form.frm_nm_servidor_autenticacao.value == "" ) 
            {	
            alert("O nome é obrigatório.");
            document.form.frm_nm_servidor_autenticacao.focus();
            return false;
            }		
        else if ( document.form.frm_te_ip_servidor_autenticacao.value == "" ) 
            {	
            alert("O IP é obrigatório.");
            document.form.frm_te_ip_servidor_autenticacao.focus();
            return false;
            }
        else if ( document.form.frm_id_tipo_protocolo.value == "" ) 
            {	
            alert("Selecione o Tipo de Protocolo.");
            document.form.frm_id_tipo_protocolo.focus();
            return false;
            }
        else if ( document.form.frm_te_base_consulta_raiz.value == "" ) 
            {	
            alert("A base para consulta em raiz é obrigatória.");
            document.form.frm_te_base_consulta_raiz.focus();
            return false;
            }
        else if ( document.form.frm_te_base_consulta_folha.value == "" ) 
            {	
            alert("A base para consulta em folha é obrigatória.");
            document.form.frm_te_base_consulta_folha.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_retorna_nome.value == "" ) 
            {	
            alert("O atributo para retorno de nome completo é obrigatório.");
            document.form.frm_te_atributo_retorna_nome.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_retorna_email.value == "" ) 
            {	
            alert("O atributo para retorno de email é obrigatório.");
            document.form.frm_te_atributo_retorna_email.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_identificador.value == "" ) 
            {	
            alert("O atributo identificador é obrigatório.");
            document.form.frm_te_atributo_identificador.focus();
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
    
    <body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_servidor_autenticacao');">
    <script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
    <table width="90%" border="0" align="center">
    <tr> 
    <td class="cabecalho">Detalhes do Servidor de Autentica&ccedil;&atilde;o "<? echo $row['nm_servidor_autenticacao'];?>"</td>
    </tr>
    <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es referem-se a um servidor usado na autentica&ccedil;&atilde;o de usu&aacute;rios do suporte remoto seguro.</td>
    </tr>
    </table>
    <form action="detalhes_servidor_autenticacao.php"  method="post" ENCTYPE="multipart/form-data" name="form">
    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr>
    <td class="label"><br>Nome do Servidor de Autenticação:</td>
    <td nowrap class="label"><br>Endere&ccedil;o IP do Servidor de Autenticação:</td>
    </tr>
    <tr><td height="1" bgcolor="#333333" colspan="3"></td></tr>
    <tr> 
    <td class="label_peq_sem_fundo"> <input name="frm_nm_servidor_autenticacao" type="text" size="60" maxlength="60" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row['nm_servidor_autenticacao'];?>">
	<input name="frm_id_servidor_autenticacao" type="hidden" value="<? echo $_GET['id_servidor_autenticacao'];?>"></td>
    <td class="label_peq_sem_fundo"><input name="frm_te_ip_servidor_autenticacao" type="text" size="30" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_ip_servidor_autenticacao" value="<? echo $row['te_ip_servidor_autenticacao'];?>"></td>
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
    <td class="label"><div align="left"><span class="label_peq_sem_fundo" id="frm_nu_versao_protocolo">
    <input name="frm_nu_versao_protocolo" type="text" size="60" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nu_versao_protocolo" value="<? echo $row['nu_versao_protocolo'];?>" >
    </span></div></td>
    </tr>
    <tr> 
    <td class="label"><p><br>
      Base para Consulta em Ra&iacute;z: <span class="normal style2">(Ex.: &quot;dc=br, dc=com, dc=dominio&quot;</span><span class="normal style2">)</span></p></td>
    <td class="label"><p><br>
      Base para Consulta em Folha: <span class="normal style2">(Ex.: &quot;ou=pessoas, ou=usuarios&quot;</span><span class="normal style2">)</span></p></td>
    </tr>
    <tr> 
    <td height="1" bgcolor="#333333" colspan="3"></td>
    </tr>
    <tr> 
    <td><span class="label_peq_sem_fundo">
    <input name="frm_te_base_consulta_raiz" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_base_consulta_raiz" value="<? echo $row['te_base_consulta_raiz'];?>" >
    </span></td>
    <td><span class="label_peq_sem_fundo">
      <input name="frm_te_base_consulta_folha" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_base_consulta_folha" value="<? echo $row['te_base_consulta_folha'];?>" >
    </span></td>   
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="label"><p><br>
        Atributo para Identifica&ccedil;&atilde;o: <span class="normal style2">(Ex.: &quot;uniqueID&quot;</span><span class="normal style2">)</span></p></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_identificador" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_identificador" value="<? echo $row['te_atributo_identificador'];?>" >
      </span></td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td class="label"><p><br>
        Atributo para Retorno de Nome Completo: <span class="normal style2">(Ex.: &quot;fullName&quot;</span><span class="normal style2">)</span></p></td>
      <td class="label"><p><br>
        Atributo para Retorno de Email: <span class="normal style2">(Ex: &quot;email&quot;</span><span class="normal style2">)</span></p></td> 
    <td>&nbsp;</td>
    </tr>
    <tr>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_retorna_nome" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_retorna_nome" value="<? echo $row['te_atributo_retorna_nome'];?>" >
      </span></td>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_retorna_email" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_retorna_email" value="<? echo $row['te_atributo_retorna_email'];?>" >
      </span></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="label"><div align="left"><br>
        Observa&ccedil;&otilde;es:</div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><span class="label_peq_sem_fundo">
        <input name="frm_te_observacao" type="text" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_observacao" value="<? echo $row['te_observacao'];?>" >
      </span></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="label"><div align="left"><br>
          <label>
            <input type="checkbox" name="frm_in_ativo" id="frm_in_ativo" value="S" <? echo ($row['in_ativo']=='S'?'checked':'');?>>
            Servidor Ativo</label>
      </div></td>
      <td>&nbsp;</td>
    </tr>
    </table>
          
    <br>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
	<tr> 
	<td colspan="7" class="label">Redes Associadas ao Servidor de Autenticação:</td>
	</tr>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="7"></td>
	</tr>
	<?
	$query = "SELECT 	count(id_servidor_autenticacao) as Total
				FROM 	redes
				WHERE 	id_servidor_autenticacao = ".$_GET['id_servidor_autenticacao'];
	$result = mysql_query($query) or die ('Erro no acesso à tabela redes ou sua sessão expirou!');
	$rowRedesServidorAutenticacao = mysql_fetch_array($result);
	if ($rowRedesServidorAutenticacao['Total'] > 0)
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
					WHERE 	r.id_servidor_autenticacao = ".$_GET['id_servidor_autenticacao'] ." AND
					        l.id_local = r.id_local
					ORDER BY  l.sg_local,l.nm_local,r.nm_rede";
		$result = mysql_query($query) or die ('Erro no acesso à tabela redes ou sua sessão expirou!');

		$seq = 1;
		$Cor = 1;	
		while ($row = mysql_fetch_array($result))
			{
			?>
			<tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
			<td width="3%" align="center" 	nowrap 	class="opcao_tabela"><a href="../redes/detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&nm_chamador=servidores_autenticacao"><? echo $seq; ?></a></td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela">&nbsp;&nbsp;</td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela"><a href="../locais/detalhes_local.php?id_local=<? echo $row['id_local'];?>&nm_chamador=servidores_autenticacao"><? echo $row['sg_local'].'/'.$row['nm_local']; ?></a></td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela">&nbsp;</td>
			<td width="3%" align="left" 	nowrap 	class="opcao_tabela"><a href="../redes/detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&nm_chamador=servidores_autenticacao"><? echo $row['id_ip_rede']; ?></a></td>
			<td width="1%" align="left" 			class="opcao_tabela">&nbsp;&nbsp;</td>
			<td width="92%" align="left" 			class="opcao_tabela"><a href="../redes/detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&nm_chamador=servidores_autenticacao"><? echo $row['nm_rede']; ?></a></td>
			</tr>
			<?
			$seq++;
			$Cor=!$Cor;
			}

		}
	else
		echo '<tr><td colspan="5" class="label_vermelho">Ainda não existem redes associadas ao servidor de autenticação!</td></tr>';		
		?>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="7"></td>
	</tr>
	</table>
    <?
	/*
	<br>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
	<tr> 
	<td colspan="10" class="label">Usu&aacute;rios Associados ao Servidor de Autenticação:</td>
	</tr>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="10"></td>
	</tr>
	<?
	$query = "SELECT 	count(id_servidor_autenticacao) as Total
				FROM 	usuarios
				WHERE 	id_servidor_autenticacao = ".$_GET['id_servidor_autenticacao'];
	$result = mysql_query($query) or die ('Erro no acesso à tabela usuários ou sua sessão expirou!');
	$rowUsuariosServidorAutenticacao = mysql_fetch_array($result);
	if ($rowUsuariosServidorAutenticacao['Total'] > 0)
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
					WHERE 	u.id_servidor_autenticacao = ".$_GET['id_servidor_autenticacao']." AND
					        u.id_local = l.id_local
					ORDER BY  l.nm_local,u.nm_usuario_acesso";
		$result = mysql_query($query) or die ('Erro no acesso à tabela redes ou sua sessão expirou!');
		$seq = 1;
		$Cor = 1;	
		while ($row = mysql_fetch_array($result))
			{
			?>
			<tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
			<td width="3%" align="center" 	nowrap 	class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=servidores_autenticacao"><? echo $seq; ?></a></td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela">&nbsp;&nbsp;</td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela"><a href="../locais/detalhes_local.php?id_local=<? echo $row['id_local'];?>&nm_chamador=servidores_autenticacao"><? echo $row['sg_local'].'/'.$row['nm_local']; ?></a></td>
			<td width="1%" align="left" 	nowrap 	class="opcao_tabela">&nbsp;</td>
			<td width="3%" align="left" 	nowrap 	class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=servidores_autenticacao"><? echo $row['nm_usuario_acesso']; ?></a></td>
			<td width="1%" align="left" 			class="opcao_tabela">&nbsp;&nbsp;</td>
			<td width="92%" align="left" 			class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=servidores_autenticacao"><? echo $row['nm_usuario_completo']; ?></a></td>
			<td width="92%" align="left" 			class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=servidores_autenticacao"><? echo $row['te_emails_contato']; ?></a></td>
			<td width="92%" align="left" 			class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&nm_chamador=servidores_autenticacao"><? echo $row['te_telefones_contato']; ?></a></td>
			<td width="92%" align="left" 			class="opcao_tabela">&nbsp;</td>
			</tr>
			<?
			$seq++;
			$Cor=!$Cor;
			}
		}
	else
		echo '<tr><td colspan="5" class="label_vermelho">Ainda não existem usuários associados ao servidor de autenticação!</td></tr>';		
		?>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="10"></td>
	</tr>
	</table>        
	*/
	?>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">        
	<tr><td colspan="5" align="center"><?
	if ($_SESSION['cs_nivel_administracao']==1)
		{
		?>
		<br>                
		<p>    
		<input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return Confirma('Confirma Informações para o Servidor de Autenticação?');return valida_form();">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="ExcluiServidorAutenticacao" type="submit" id="ExcluiServidorAutenticacao" onClick="return Confirma('Confirma Exclusão(Desativação) do Servidor de Autenticação?');" value="  Excluir/Desativar Servidor de Autenticação">
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
