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
include_once "../../include/library.php";
AntiSpy('1,2'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administra��o
// 2 - Gest�o Central


if($_POST['submit']<>'' && $_SESSION['cs_nivel_administracao']==1) 
	{
	Conecta_bd_cacic();
	
	$query = "SELECT 	* 
			  FROM 		servidores_autenticacao 
			  WHERE 	nm_servidor_autenticacao = '".$_POST['frm_nm_servidor_autenticacao']."'";
	$result = mysql_query($query) or die ('1-Select falhou ou sua sess�o expirou!');
	
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
				  			 nu_porta_servidor_autenticacao,							 
							 id_tipo_protocolo,
							 nu_versao_protocolo,
							 te_atributo_identificador,
							 te_atributo_retorna_nome,
							 te_atributo_retorna_email,
							 te_observacao) 
				  VALUES 	('".$_POST['frm_nm_servidor_autenticacao']."', 
						  	 '".$_POST['frm_te_ip_servidor_autenticacao']."',									  
						  	 '".$_POST['frm_nu_porta_servidor_autenticacao']."',									  							 
							 '".$_POST['frm_id_tipo_protocolo']."',
							 '".$_POST['frm_nu_versao_protocolo']."',
							 '".$_POST['frm_te_atributo_identificador']."',
							 '".$_POST['frm_te_atributo_retorna_nome']."',
							 '".$_POST['frm_te_atributo_retorna_email']."',							 
							 '".$_POST['frm_te_observacao']."')";							 									  						  
		$result = mysql_query($query) or die ('2-Falha na Inser��o em Servidores de Autentica��o ou sua sess�o expirou!');
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
          de Servidor de Autentica��o</td>
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
            Nome:</td>
          <td class="label">&nbsp;</td>
          <td nowrap class="label">&nbsp;</td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="4"></td>
        </tr>
        <tr> 
          <td class="label_peq_sem_fundo"> <input name="frm_nm_servidor_autenticacao" type="text" size="60" maxlength="60" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          &nbsp;&nbsp;</td>
          <td class="label_peq_sem_fundo">&nbsp;</td>
          <td class="label_peq_sem_fundo">&nbsp;</td>
        </tr>
        <tr>
          <td nowrap class="label"><br>
            Endere&ccedil;o IP:</td>
          <td nowrap class="label">&nbsp;</td>
          <td nowrap class="label"><br>
            Porta:</td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="4"></td>
        </tr>
        
        <tr>
          <td class="label_peq_sem_fundo"><input name="frm_te_ip_servidor_autenticacao" type="text" size="30" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_ip_servidor_autenticacao" ></td>
          <td class="label_peq_sem_fundo">&nbsp;</td>
          <td class="label_peq_sem_fundo"><input name="frm_nu_porta_servidor_autenticacao" type="text" class="normal" id="frm_nu_porta_servidor_autenticacao" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="389" size="15" maxlength="5" ></td>
        </tr>
        <tr> 
          <td class="label"><div align="left"><br>
            Protocolo:</div></td>
          <td class="label">&nbsp;</td>
          <td class="label"><div align="left"><br>
            Vers&atilde;o:</div></td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="4"></td>
        </tr>
        <tr> 
          <td nowrap><label>
            <select name="frm_id_tipo_protocolo" class="opcao_tabela" id="frm_id_tipo_protocolo">
              <option value="LDAP" selected>LDAP</option>
              <option value="Open LDAP">Open LDAP</option>
            </select>
          </label></td>
            <td nowrap>&nbsp;</td>
            <td class="label"><div align="left"><span class="label_peq_sem_fundo">
              <input name="frm_nu_versao_protocolo" type="text" size="30" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nu_versao_protocolo" >
            </span></div></td>
        </tr>
        
    
    <tr>
      <td class="label"><div align="left"><br>
        Observa&ccedil;&otilde;es:</div></td>
      <td class="label">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="4"></td>
        </tr>
    
    <tr>
      <td colspan="3" class="label_peq_sem_fundo"><input name="frm_te_observacao" type="text" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_observacao" >        &nbsp;&nbsp;</td>
      </tr>
    <tr>
      <td class="label">&nbsp;</td>
      <td class="label">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="label">&nbsp;</td>
      <td class="label">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" class="cabecalho_secao"><div align="center">Atributos para Consulta de Dados de Pessoas no Servi&ccedil;o de Diret&oacute;rios</div></td>
      </tr>
    <tr>
      <td class="label"><p><br>
          Identifica&ccedil;&atilde;o: <span class="normal style2">(Ex.: &quot;uid&quot;</span><span class="normal style2">)</span></p></td>
      <td class="label"><p><br>
         Retorno de Nome Completo: <span class="normal style2">(Ex.: &quot;cn&quot;</span><span class="normal style2">)</span></p></td>
      <td class="label"><p><br>
         Retorno de Email: <span class="normal style2">(Ex: &quot;mail&quot;</span><span class="normal style2">)</span></p></td>
    </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
    
    <tr>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_identificador" type="text" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_identificador" >
      </span></td>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_retorna_nome" type="text" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_retorna_nome" >
      </span></td>
      <td><span class="label_peq_sem_fundo">
        <input name="frm_te_atributo_retorna_email" type="text" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_te_atributo_retorna_email" >
      </span></td>
    </tr>

    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
      </table>
      <p align="center"> 
        <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Inclus�o de Servidor de Autentica��o?');">
      </p>
    </form>
    <p>
      <?
    }
?>
</p>
</body>
</html>
