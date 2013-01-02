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
			  FROM 		usb_vendors v,
			  			usb_devices d
			  WHERE 	v.id_vendor = '".$_POST['frm_id_vendor']."' AND d.id_device = '".$_POST['frm_id_device']."'";
	$result = mysql_query($query) or die ('1-Select falhou ou sua sess�o expirou!');
	
	if (mysql_num_rows($result) > 0) 
		{
		header ("Location: ../../include/registro_ja_existente.php?chamador=../admin/usb_devices/index.php&tempo=1");							 							
		}
	else 
		{
		$query = "INSERT 
				  INTO 		usb_vendors
				  			(id_vendor, 
				  			 nm_vendor) 
				  VALUES 	('".$_POST['frm_id_vendor']."', 
						  	 '".$_POST['frm_nm_vendor']."')";							 									  						  
		$result = mysql_query($query) or die ('2-Falha na Inser��o em Fabricantes de Dispositivos USB ou sua sess�o expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'usb_vendors');			

		$query = "INSERT 
				  INTO 		usb_devices
				  			(id_vendor, 
				  			 id_device, 							 
				  			 nm_device,
							 te_observacao) 
				  VALUES 	('".$_POST['frm_id_vendor']."', 
						  	 '".$_POST['frm_id_device']."',							 									  						  
						  	 '".$_POST['frm_nm_device']."',							 									  						  
						  	 '".$_POST['frm_te_observacao']."')";							 									  						  							 
		$result = mysql_query($query) or die ('2-Falha na Inser��o em Dispositivos USB ou sua sess�o expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'usb_devices');			

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
	<title>Inclus&atilde;o de Dispositivo USB</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<SCRIPT LANGUAGE="JavaScript">
    
    function valida_form() 
        {
    
        if ( document.form.frm_id_vendor.value == "" ) 
            {	
            alert("O Identificador do Fabricante � obrigat�rio.");
            document.form.frm_id_vendor.focus();
            return false;
            }		
        else if ( document.form.frm_nm_vendor.value == "" ) 
            {	
            alert("O Nome do Fabricante.");
            document.form.frm_nm_vendor.focus();
            return false;
            }
        else if ( document.form.frm_id_device.value == "" ) 
            {	
            alert("O Identificador do Dispositivo USB � obrigat�rio.");
            document.form.frm_id_device.focus();
            return false;
            }
        else if ( document.form.frm_nm_device.value == "" ) 
            {	
            alert("O Nome do Dispositivo USB � obrigat�rio.");
            document.form.frm_nm_device.focus();
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
    
    <body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_id_vendor');">
    <script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
    <table width="90%" border="0" align="center">
      <tr> 
        <td class="cabecalho">Inclus&atilde;o 
          de Dispositivo USB</td>
      </tr>
      <tr> 
        <td class="descricao">As informa&ccedil;&otilde;es que dever&atilde;o ser 
          cadastradas abaixo referem-se a um dispositivo USB a ser utilizado pelo m&oacute;dulo de detec&ccedil;&atilde;o na esta&ccedil;&atilde;o de trabalho. </td>
      </tr>
    </table>
    <form action="incluir_usb_device.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td class="label"><br>
            Identificador do Fabricante:</td>
          <td nowrap class="label"><br>
          Nome do Fabricante:</td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
        <tr> 
          <td class="label_peq_sem_fundo"> <input name="frm_id_vendor" type="text" size="50" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_id_vendor" >
          &nbsp;&nbsp;</td>
          <td class="label_peq_sem_fundo"><input name="frm_nm_vendor" type="text" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nm_vendor" ></td>
        </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
        <tr> 
          <td class="label"><br>
            Identificador do Dispositivo:</td>
          <td nowrap class="label"><br>
          Nome do Dispositivo:</td>
        </tr>
        <tr> 
          <td height="1" bgcolor="#333333" colspan="3"></td>
        </tr>
        <tr> 
          <td class="label_peq_sem_fundo"> <input name="frm_id_device" type="text" size="50" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_id_device">
          &nbsp;&nbsp;</td>
          <td class="label_peq_sem_fundo"><input name="frm_nm_device" type="text" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nm_device" ></td>
        </tr>
    <tr>
      <td>&nbsp;</td>
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
        <label>
          <textarea name="frm_te_observacao" id="frm_te_observacao" cols="100" rows="3"></textarea>
        </label>
      </span></td>
      <td>&nbsp;</td>
    </tr>
      </table>
      
      <p align="center"> 
        <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Inclus�o de Dispositivo USB?');">
      </p>
    </form>
    <p>
      <?
    }
?>
</p>
</body>
</html>
