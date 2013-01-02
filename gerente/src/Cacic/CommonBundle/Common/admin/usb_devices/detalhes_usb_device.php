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


if (($_POST['ExcluiUSBDevice'] <> '') && $_SESSION['cs_nivel_administracao']==1) 
	{
	$query = "DELETE 	
			  FROM		usb_devices
			  WHERE 	id_device = '".$_POST['frm_id_device']."'";
	mysql_query($query) or die('Delete falhou ou sua sess�o expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'usb_devices');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/usb_devices/index.php&tempo=1");				
	}
else if (($_POST['GravaAlteracoes'] <> '') && $_SESSION['cs_nivel_administracao']==1) 
	{
			
	$query = "UPDATE 	usb_vendors 
			  SET		nm_vendor 					= '".$_POST['frm_nm_vendor'] 	."'
			  WHERE 	id_vendor 					=  ".$_POST['frm_id_vendor'];
			
	mysql_query($query) or die('Update falhou ou sua sess�o expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'usb_vendors');		
	
	$query = "UPDATE 	usb_devices 
			  SET		nm_device 					= '".$_POST['frm_nm_device'] 	."'
			  WHERE 	id_device 					=  ".$_POST['frm_id_device'];
			
	mysql_query($query) or die('Update falhou ou sua sess�o expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'usb_devices');		
	
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/usb_devices/index.php&tempo=1");				
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		usb_vendors v,
			  			usb_devices d
			  WHERE     d.id_device = '".$_GET['id_device']."'";
	$result = mysql_query($query) or die ('Erro no acesso � tabela Dispositivos_USB ou sua sess�o expirou!');
	
	$row = mysql_fetch_array($result);
	?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
    <title>Detalhes de Dispositivo USB</title>
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
    <style type="text/css">
<!--
.style2 {	font-size: 9px;
	color: #000099;
}
-->
    </style>
    </head>
    
    <body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_vendor');">
    <script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
    <table width="90%" border="0" align="center">
    <tr> 
    <td class="cabecalho">Detalhes do Dispositivo USB "<? echo $row['nm_device'];?>"</td>
    </tr>
    <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es referem-se a um dispositivo USB usado pelo m&oacute;dulo de detec&ccedil;&atilde;o nas esta&ccedil;&otilde;es de trabalho.</td>
    </tr>
    </table>
    <form action="detalhes_usb_device.php"  method="post" ENCTYPE="multipart/form-data" name="form">
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
          <td class="label_peq_sem_fundo"> <input name="frm_id_vendor" type="text" size="50" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_id_vendor" value="<? echo $row['id_vendor'];?>" readonly >
          &nbsp;&nbsp;</td>
          <td class="label_peq_sem_fundo"><input name="frm_nm_vendor" type="text" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nm_vendor"  value="<? echo $row['nm_vendor'];?>" ></td>
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
          <td class="label_peq_sem_fundo"> <input name="frm_id_device" type="text" size="50" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_id_device" readonly  value="<? echo $row['id_device'];?>" >
          &nbsp;&nbsp;</td>
          <td class="label_peq_sem_fundo"><input name="frm_nm_device" type="text" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nm_device"  value="<? echo $row['nm_device'];?>" ></td>
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
          <textarea name="frm_te_observacao" id="frm_te_observacao" cols="100" rows="3"  value="<? echo $row['te_observacao'];?>" ></textarea>
        </label>
      </span></td>
      <td>&nbsp;</td>
    </tr>
      </table>
      
      <p align="center"> 
        <input name="GravaAlteracoes" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Altera��o de Dispositivo USB?');">
      </p>

	</form>		              
	</body>
</html>
    <?
    }
?>
