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
session_start();
require_once('../../include/library.php');

AntiSpy('1,2'); // Permitido somente a estes cs_nivel_administracao...

// 1 - Administra��o
// 2 - Gest�o Central


conecta_bd_cacic();


if ($_POST['ExcluiServidorAutenticacao'] <> '' && $_SESSION['cs_nivel_administracao']==1) 
	{
	$query = "UPDATE 	servidores_autenticacao 
			  SET 		in_ativo = 'N'
			  WHERE 	id_servidor_autenticacao = ".$_POST['frm_id_servidor_autenticacao'];
	mysql_query($query) or die('Update falhou ou sua sess�o expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'servidores_autenticacao');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/servidores_autenticacao/index.php&tempo=1");				
	}
else if ($_POST['GravaAlteracoes'] <> '' && $_SESSION['cs_nivel_administracao']==1) 
	{
	$in_ativo = ($_POST['frm_in_ativo']=='S'?$_POST['frm_in_ativo']:'N');
					
	$query = "UPDATE 	servidores_autenticacao 
			  SET		nm_servidor_autenticacao 		= '".$_POST['frm_nm_servidor_autenticacao'] 		."', 
				  		te_ip_servidor_autenticacao		= '".$_POST['frm_te_ip_servidor_autenticacao']		."',
				  		nu_porta_servidor_autenticacao	= '".$_POST['frm_nu_porta_servidor_autenticacao']	."',						
						id_tipo_protocolo				= '".$_POST['frm_id_tipo_protocolo']  				."',
						nu_versao_protocolo				= '".$_POST['frm_nu_versao_protocolo']				."',
						te_atributo_identificador		= '".$_POST['frm_te_atributo_identificador']		."',
						te_atributo_retorna_nome		= '".$_POST['frm_te_atributo_retorna_nome'] 		."',
						te_atributo_retorna_email		= '".$_POST['frm_te_atributo_retorna_email']		."',														
						te_observacao					= '".$_POST['frm_te_observacao']      				."',				  			  
						in_ativo						= '".$in_ativo 	     								."'				  			  						
			  WHERE 	id_servidor_autenticacao 		=  ".$_POST['frm_id_servidor_autenticacao'];
			
	mysql_query($query) or die('Update falhou ou sua sess�o expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'servidores_autenticacao');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/servidores_autenticacao/index.php&tempo=1");				
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		servidores_autenticacao 
			  WHERE     id_servidor_autenticacao = ".$_GET['id_servidor_autenticacao'];
	$result = mysql_query($query) or die ('Erro no acesso � tabela servidores_autenticacao ou sua sess�o expirou!');
	
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
            alert("O nome � obrigat�rio.");
            document.form.frm_nm_servidor_autenticacao.focus();
            return false;
            }		
        else if ( document.form.frm_te_ip_servidor_autenticacao.value == "" ) 
            {	
            alert("O IP � obrigat�rio.");
            document.form.frm_te_ip_servidor_autenticacao.focus();
            return false;
            }
        else if ( document.form.frm_nu_porta_servidor_autenticacao.value == "" ) 
            {	
            alert("A porta � obrigat�ria.");
            document.form.frm_nu_porta_servidor_autenticacao.focus();
            return false;
            }			
        else if ( document.form.frm_id_tipo_protocolo.value == "" ) 
            {	
            alert("Selecione o Tipo de Protocolo.");
            document.form.frm_id_tipo_protocolo.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_retorna_nome.value == "" ) 
            {	
            alert("O atributo para retorno de nome completo � obrigat�rio.");
            document.form.frm_te_atributo_retorna_nome.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_retorna_email.value == "" ) 
            {	
            alert("O atributo para retorno de email � obrigat�rio.");
            document.form.frm_te_atributo_retorna_email.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_identificador.value == "" ) 
            {	
            alert("O atributo identificador � obrigat�rio.");
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
    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
    <td class="label"><br>
      Nome:</td>
    <td class="label">&nbsp;</td>
    <td nowrap class="label">&nbsp;</td>
    <td nowrap class="label">&nbsp;</td>
    </tr>
    <tr><td height="1" bgcolor="#333333" colspan="5"></td></tr>
    <tr> 
    <td class="label_peq_sem_fundo"> <input name="frm_nm_servidor_autenticacao" type="text" size="60" maxlength="60" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row['nm_servidor_autenticacao'];?>">
	<input name="frm_id_servidor_autenticacao" type="hidden" value="<? echo $_GET['id_servidor_autenticacao'];?>"></td>
    <td class="label_peq_sem_fundo">&nbsp;</td>
    <td class="label_peq_sem_fundo">&nbsp;</td>
    <td class="label_peq_sem_fundo">&nbsp;</td>
    </tr>
    <tr>
      <td nowrap class="label"><br>
        Endere&ccedil;o IP do Servidor de Autentica&ccedil;&atilde;o:</td>
      <td nowrap class="label">&nbsp;</td>
      <td nowrap class="label"><br>
        Porta:</td>
      <td class="label">&nbsp;</td>
    </tr>
    <tr><td height="1" bgcolor="#333333" colspan="5"></td></tr>    
    <tr>
      <td class="label_peq_sem_fundo"><input name="frm_te_ip_servidor_autenticacao" type="text" size="30" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_ip_servidor_autenticacao" value="<? echo $row['te_ip_servidor_autenticacao'];?>"></td>
      <td class="label_peq_sem_fundo">&nbsp;</td>
      <td class="label_peq_sem_fundo"><input name="frm_nu_porta_servidor_autenticacao" type="text" size="15" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nu_porta_servidor_autenticacao" value="<? echo $row['nu_porta_servidor_autenticacao'];?>"></td>
      <td class="label">&nbsp;</td>
    </tr>
    
    <tr> 
    <td class="label"><div align="left"><br>Protocolo:</div></td>
    <td class="label">&nbsp;</td>
    <td class="label"><div align="left"><br>Vers&atilde;o:</div></td>
    <td class="label">&nbsp;</td>
    </tr>
    <tr> 
    <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
    <tr> 
    <td nowrap><label>
    <select name="frm_id_tipo_protocolo" class="opcao_tabela" id="frm_id_tipo_protocolo">
    <option value="LDAP" <? if ($row['id_tipo_protocolo']=='LDAP') echo 'selected';?>>LDAP</option>
    <option value="Open LDAP"<? if ($row['id_tipo_protocolo']=='Open LDAP') echo 'selected';?>>Open LDAP</option>
    </select>
    </label></td>
    <td nowrap>&nbsp;</td>
    <td class="label"><div align="left"><span class="label_peq_sem_fundo" id="frm_nu_versao_protocolo">
    <input name="frm_nu_versao_protocolo" type="text" size="60" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nu_versao_protocolo" value="<? echo $row['nu_versao_protocolo'];?>" >
    </span></div></td>
    <td class="label">&nbsp;</td>
    </tr>
    <tr>
      <td nowrap class="label"><br>
      Observa&ccedil;&otilde;es:</td>
      <td nowrap>&nbsp;</td>
      <td class="label">&nbsp;</td>
      <td class="label">&nbsp;</td>
    </tr>
    <tr><td height="1" bgcolor="#333333" colspan="5"></td></tr>    
    <tr>
      <td class="label_peq_sem_fundo"><input name="frm_te_observacao" type="text" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_observacao" value="<? echo $row['te_observacao'];?>" ></td>
      <td nowrap>&nbsp;</td>
      <td class="label">&nbsp;</td>
      <td class="label">&nbsp;</td>
    </tr>
    <tr>
      <td class="label_peq_sem_fundo">&nbsp;</td>
      <td nowrap>&nbsp;</td>
      <td class="label">&nbsp;</td>
      <td class="label">&nbsp;</td>
    </tr>
    <tr>
      <td class="label_peq_sem_fundo">&nbsp;</td>
      <td nowrap>&nbsp;</td>
      <td class="label">&nbsp;</td>
      <td class="label">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="cabecalho_secao"><div align="center">Atributos para Consulta de Dados de Pessoas no Servi&ccedil;o de Diret&oacute;rios</div></td>
      <td class="label">&nbsp;</td>
    </tr>
    
    <tr> 
    <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
    
    <tr>
      <td class="label"><p><br>
           Identifica&ccedil;&atilde;o: <span class="normal style2">(Ex.: &quot;uid&quot;</span><span class="normal style2">)</span></p></td>
      <td class="label"><p><br>
         Retorno de Nome Completo: <span class="normal style2">(Ex.: &quot;cn&quot;</span><span class="normal style2">)</span></p></td>
      <td class="label"><p><br>
        Retorno de Email: <span class="normal style2">(Ex: &quot;mail&quot;</span><span class="normal style2">)</span></p></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_identificador" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_identificador" value="<? echo $row['te_atributo_identificador'];?>" >
      </span></td>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_retorna_nome" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_retorna_nome" value="<? echo $row['te_atributo_retorna_nome'];?>" >
      </span></td>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_retorna_email" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_retorna_email" value="<? echo $row['te_atributo_retorna_email'];?>" >
      </span></td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    
    <tr>
      <td colspan="4">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" class="label"><div align="left"><br>
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
	<td colspan="7" class="label">Redes Associadas ao Servidor de Autentica��o:</td>
	</tr>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="7"></td>
	</tr>
	<?
	$query = "SELECT 	count(id_servidor_autenticacao) as Total
				FROM 	redes
				WHERE 	id_servidor_autenticacao = ".$_GET['id_servidor_autenticacao'];
	$result = mysql_query($query) or die ('Erro no acesso � tabela redes ou sua sess�o expirou!');
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
		$result = mysql_query($query) or die ('Erro no acesso � tabela redes ou sua sess�o expirou!');

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
		echo '<tr><td colspan="5" class="label_vermelho">Ainda n�o existem redes associadas ao servidor de autentica��o!</td></tr>';		
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
	<td colspan="10" class="label">Usu&aacute;rios Associados ao Servidor de Autentica��o:</td>
	</tr>
	<tr> 
	<td height="1" bgcolor="#333333" colspan="10"></td>
	</tr>
	<?
	$query = "SELECT 	count(id_servidor_autenticacao) as Total
				FROM 	usuarios
				WHERE 	id_servidor_autenticacao = ".$_GET['id_servidor_autenticacao'];
	$result = mysql_query($query) or die ('Erro no acesso � tabela usu�rios ou sua sess�o expirou!');
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
		$result = mysql_query($query) or die ('Erro no acesso � tabela redes ou sua sess�o expirou!');
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
		echo '<tr><td colspan="5" class="label_vermelho">Ainda n�o existem usu�rios associados ao servidor de autentica��o!</td></tr>';		
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
		<input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return Confirma('Confirma Informa��es para o Servidor de Autentica��o?');return valida_form();">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="ExcluiServidorAutenticacao" type="submit" id="ExcluiServidorAutenticacao" onClick="return Confirma('Confirma Exclus�o(Desativa��o) do Servidor de Autentica��o?');" value="  Excluir/Desativar Servidor de Autentica��o">
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
