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
require_once('../../include/library.php');

AntiSpy('1,2'); // Permitido somente a estes cs_nivel_administracao...

// 1 - Administração
// 2 - Gestão Central


conecta_bd_cacic();


if (($_POST['ExcluiUSBDevice'] <> '') && $_SESSION['cs_nivel_administracao']==1) 
	{
	$query = "DELETE 	
			  FROM		usb_vendors
			  WHERE 	trim(id_vendor) = '".trim($_POST['frm_id_vendor'])."'";
	mysql_query($query) or die('Delete falhou ou sua sessão expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'usb_vendors',$_SESSION["id_usuario"]);		
	
	$query = "DELETE 	
			  FROM		usb_devices
			  WHERE 	trim(id_vendor) = '".trim($_POST['frm_id_vendor'])."'";
	mysql_query($query) or die('Delete falhou ou sua sessão expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'usb_devices',$_SESSION["id_usuario"]);		
	
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/usb_devices/index.php&tempo=1");				
	}
else if (($_POST['GravaAlteracoes'] <> '') && $_SESSION['cs_nivel_administracao']==1) 
	{
			
	$query = "UPDATE 	usb_vendors 
			  SET		nm_vendor 					= '".$_POST['frm_nm_vendor'] 	."',
			  			te_observacao				= '".$_POST['frm_te_observacao']."'			  			  
			  WHERE 	trim(id_vendor) 					=  '".trim($_POST['frm_id_vendor'])."'";
	mysql_query($query) or die('Update falhou ou sua sessão expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'usb_vendors',$_SESSION["id_usuario"]);		
		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/usb_devices/index.php&tempo=1");				
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		usb_vendors v
			  WHERE     trim(v.id_vendor) = '".trim($_GET['id_vendor'])."'";
	$result = mysql_query($query) or die ('Erro no acesso à tabela Fabricantes_USB ou sua sessão expirou!');
	
	$row = mysql_fetch_array($result);
	?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
    <link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
    <title>Detalhes de Fabricante de Dispositivos USB</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <SCRIPT LANGUAGE="JavaScript">
    
    function valida_form() 
        {
    
        if ( document.form.frm_nm_vendor.value == "" ) 
            {	
            alert("O Nome do Fabricante é OBRIGATÓRIO.");
            document.form.frm_nm_vendor.focus();
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
    <script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
    <table width="85%" border="0" align="center">
    <tr> 
    <td class="cabecalho">Detalhes do Fabricante de Dispositivos USB "<?php echo $row['nm_vendor'];?>"</td>
    </tr>
    <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es referem-se a um fabricante de dispositivos USB e são usadas pelo m&oacute;dulo de detec&ccedil;&atilde;o nas esta&ccedil;&otilde;es de trabalho.</td>
    </tr>
    </table>
    <form action="detalhes_usb_vendor.php"  method="post" ENCTYPE="multipart/form-data" name="form">
      <table width="85%" border="0" align="center" cellpadding="0" cellspacing="0">
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
          <td class="label_peq_sem_fundo"> <input name="frm_id_vendor" type="text" size="10" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_id_vendor" value="<?php echo $row['id_vendor'];?>" readonly >
          &nbsp;&nbsp;</td>
          <td class="label_peq_sem_fundo"><input name="frm_nm_vendor" type="text" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="frm_nm_vendor"  value="<?php echo $row['nm_vendor'];?>" ></td>
        </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
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
          <textarea name="frm_te_observacao" id="frm_te_observacao" cols="100" rows="3"  value="<?php echo $row['te_observacao'];?>" ></textarea>
        </label>
      </span></td>
      <td>&nbsp;</td>
    </tr>
      </table>
      
      <p align="center"> 
        <input name="GravaAlteracoes" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Alteração de Fabricante de Dispositivos USB?');">
      </p>

	</form>		              
	</body>
</html>
    <?php
    }
?>