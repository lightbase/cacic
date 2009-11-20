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
include_once "../../include/library.php";
AntiSpy('1,2'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central


if($_POST['submit']<>'' && $_SESSION['cs_nivel_administracao']==1) 
	{
	Conecta_bd_cacic();
	
	$query = "SELECT 	* 
			  FROM 		servidores_autenticacao 
			  WHERE 	nm_servidor_autenticacao = '".$_POST['frm_nm_servidor_autenticacao']."'";
	$result = mysql_query($query) or die ('1-Select falhou ou sua sessão expirou!');
	
	if (mysql_num_rows($result) > 0) 
		{
		header ("Location: ../../include/registro_ja_existente.php?chamador=../admin/servidores_autenticacao/index.php&tempo=1");									 							
		}
	else 
		{
		$query = "INSERT 
				  INTO 		servidores_autenticacao 
				  			(nm_servidor_autenticacao, 
				  			 te_ip_servidor_autenticacao,
							 id_tipo_protocolo,
							 nu_versao_protocolo,
							 te_base_consulta_raiz,
							 te_base_consulta_folha,							 
							 te_atributo_identificador,
							 te_atributo_retorna_nome,
							 te_atributo_retorna_email,
							 te_observacao) 
				  VALUES 	('".$_POST['frm_nm_servidor_autenticacao']."', 
						  	 '".$_POST['frm_te_ip_servidor_autenticacao']."',									  
							 '".$_POST['frm_id_tipo_protocolo']."',
							 '".$_POST['frm_nu_versao_protocolo']."',
							 '".$_POST['frm_te_base_consulta_raiz']."',
							 '".$_POST['frm_te_base_consulta_folha']."',
							 '".$_POST['frm_te_atributo_identificador']."',
							 '".$_POST['frm_te_atributo_retorna_nome']."',
							 '".$_POST['frm_te_atributo_retorna_email']."',							 
							 '".$_POST['frm_te_observacao']."')";							 									  						  
		$result = mysql_query($query) or die ('2-Falha na Inserção em Servidores de Autenticação ou sua sessão expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'servidores_autenticacao');			
		
	    header ("Location: index.php");		
		}
	}
else 
	{
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
	<title>Inclus&atilde;o de Servidor de Autentica&ccedil;&atilde;o</title>
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
    <script language="JavaScript" type="text/JavaScript">
    <!--
    function MM_reloadPage(init) {  //reloads the window if Nav4 resized
      if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
        document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
      else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
    }
    MM_reloadPage(true);
    //-->
    </script>
    <style type="text/css">
<!--
.style2 {
	font-size: 9px;
	color: #000099;
}
-->
    </style>
    </head>
    
    <body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_servidor_autenticacao');">
    <script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
    <table width="90%" border="0" align="center">
      <tr> 
        <td class="cabecalho">Inclus&atilde;o 
          de Servidor de Autenticação</td>
      </tr>
      <tr> 
        <td class="descricao">As informa&ccedil;&otilde;es que dever&atilde;o ser 
          cadastradas abaixo referem-se a um servidor a ser utilizado na autentica&ccedil;&atilde;o de usu&aacute;rios do suporte remoto seguro. </td>
      </tr>
    </table>
    <form action="incluir_servidor_autenticacao.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td class="label"><br>
            Nome do Servidor de Autenticação:</td>
          <td nowrap class="label"><br>
          Endere&ccedil;o IP do Servidor de Autenticação:</td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
        <tr> 
          <td class="label_peq_sem_fundo"> <input name="frm_nm_servidor_autenticacao" type="text" size="60" maxlength="60" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          &nbsp;&nbsp;</td>
          <td class="label_peq_sem_fundo"><input name="frm_te_ip_servidor_autenticacao" type="text" size="30" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_ip_servidor_autenticacao" ></td>
        </tr>
        <tr> 
          <td class="label"><div align="left"><br>
            Protocolo:</div></td>
          <td class="label"><div align="left"><br>
            Vers&atilde;o:</div></td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
        <tr> 
          <td nowrap><label>
            <select name="frm_id_tipo_protocolo" class="opcao_tabela" id="frm_id_tipo_protocolo">
              <option value="LDAP" selected>LDAP</option>
              <option value="Open LDAP">Open LDAP</option>
            </select>
          </label></td>
            <td class="label"><div align="left"><span class="label_peq_sem_fundo">
              <input name="frm_nu_versao_protocolo" type="text" size="30" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nu_versao_protocolo" >
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
    <input name="frm_te_base_consulta_raiz" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_base_consulta_raiz" >
    </span></td>
    <td><span class="label_peq_sem_fundo">
      <input name="frm_te_base_consulta_folha" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_base_consulta_folha" >
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
        <input name="frm_te_atributo_identificador" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_identificador" >
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
        <input name="frm_te_atributo_retorna_nome" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_retorna_nome" >
      </span></td>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_retorna_email" type="text" size="60" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_retorna_email" >
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
        <input name="frm_te_observacao" type="text" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_observacao" >
      </span></td>
      <td>&nbsp;</td>
    </tr>
      </table>
      <p align="center"> 
        <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Inclusão de Servidor de Autenticação?');">
      </p>
    </form>
    <p>
      <?
    }
?>
</p>
</body>
</html>
