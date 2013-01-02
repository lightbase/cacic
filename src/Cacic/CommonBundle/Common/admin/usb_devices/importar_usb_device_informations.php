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
	$arrTeImportacao = explode(chr(10),$_POST['frm_te_importacao']);
	$strIdVendor 	 = '';
	$strNmVendor 	 = '';
	$strIdDevice 	 = '';
	$strNmDevice 	 = '';
	
	$strQueryVendors = '';
	$strQueryDevices = '';
	$strVendors  	 = '';
	for ($i=0;$i < count($arrTeImportacao); $i++)
	  {
	  if ((trim($arrTeImportacao[$i])<>'') && (strspn($arrTeImportacao[$i],'V___') > 0) && (!strpos($strVendors, ('_'.$arrTeImportacao[$i].'_'))))
	  	{
	  	$strIdVendor = trim(str_replace('V___','',$arrTeImportacao[$i]));		
		$arrVendors  = explode(' ',$strIdVendor);
	  	$strIdVendor = $arrVendors[0];

		$strNmVendor = '';
		for ($intLoopVendor = 1; $intLoopVendor < count($arrVendors); $intLoopVendor++)
		  	$strNmVendor .= $arrVendors[$intLoopVendor].' ';
		
		$strQueryVendors .= ($strQueryVendors <> ''?',':'');
		$strQueryVendors .= '("'.trim($strIdVendor) . '","' . trim($strNmVendor).'")';
		}
		
	  if ((trim($arrTeImportacao[$i])<>'') && (strspn($arrTeImportacao[$i],'D___') > 0))
	  	{
	  	$strIdDevice = trim(str_replace('D___','',$arrTeImportacao[$i]));		
		$arrDevices  = explode(' ',$strIdDevice);
	  	$strIdDevice = $arrDevices[0];

		$strNmDevice = '';
		for ($intLoopDevice = 1; $intLoopDevice < count($arrDevices); $intLoopDevice++)
		  	$strNmDevice .= $arrDevices[$intLoopDevice].' ';
			
		if (trim($strIdDevice) <> '')
			{
			$strQueryDevices .= ($strQueryDevices <> ''?',':'');
			$strQueryDevices .= '("'.trim($strIdVendor) . '","' . trim($strIdDevice). '","' . trim($strNmDevice).'")';		
			}
		} 
	  }
		
	if ($strQueryVendors <> '')
		{
		Conecta_bd_cacic();			
		if ($_POST['frmCheckEmptyTable']=='S')
			{
			$strQueryDeleteVendors = 'DELETE from usb_vendors';
			$result = @mysql_query($strQueryDeleteVendors) or die ('1-DELETE falhou ou sua sess�o expirou!');
			GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'usb_vendors');					
			}
		
		$strQueryInsertVendors = 'INSERT into usb_vendors (id_vendor,nm_vendor) VALUES '.$strQueryVendors;
        if (@mysql_query($strQueryInsertVendors))
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'usb_vendors');					
		}

	if ($strQueryDevices <> '')
		{
		Conecta_bd_cacic();			
		if ($_POST['frmCheckEmptyTable']=='S')
			{		
			$strQueryDeleteDevices = 'DELETE from usb_devices';
			$result = @mysql_query($strQueryDeleteDevices) or die ('2-DELETE falhou ou sua sess�o expirou!');
			GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'usb_devices');					
			}
		
		$strQueryInsertDevices = 'INSERT into usb_devices (id_vendor,id_device,nm_device) VALUES '.$strQueryDevices;
		if (@mysql_query($strQueryInsertDevices))
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'usb_devices');					
		}
		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/usb_devices/index.php&tempo=1");						
	}
else 
	{
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
	<title>Importa&ccedil;&atilde;o de Informa&ccedil;&otilde;es Sobre Dispositivos USB</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<SCRIPT LANGUAGE="JavaScript">

    function trim(strTexto)
	{return strTexto.replace(/^\s+|\s+$/g,"");}

    function valida_form() 
        {    
        if ( document.form.frm_te_importacao.value == "" ) 
            {	
            alert("O Campo de Importa��o � obrigat�rio.");
            document.form.frm_te_importacao.focus();
            return false;
            }		

		var strLines;
		var strImportacaoFormatada;
		var strQuebraLinha;
		if(document.all) 
			strQuebraLinha = "\r\n"; // IE
		else
			strQuebraLinha = "\n";	// Mozilla
			
		strLines = (document.form.frm_te_importacao.value).split(strQuebraLinha); 

		document.form.frm_te_importacao.value = '';
		strImportacaoFormatada = '';		
		for(var i=0; i < strLines.length; i++) 
			{
			if (trim(strLines[i]) != '')
				{
				if (strLines[i].indexOf(' ') == 4)	
					strImportacaoFormatada += 'V___';
				else					
					strImportacaoFormatada += 'D___';
				
				strImportacaoFormatada += strLines[i] + strQuebraLinha;				
				}
			}
			
		document.form.frm_te_importacao.value = strImportacaoFormatada;			
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
	color: #0000FF;
	font-weight: bold;
}
.style3 {color: #0000FF}
-->
    </style>
	</head>
    
    <body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_id_vendor');">
    <script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
    <table width="90%" border="0" align="center">
      <tr> 
        <td class="cabecalho">Importa&ccedil;&atilde;o de Informa&ccedil;&otilde;es de Dispositivos USB</td>
      </tr>
      <tr> 
        <td class="descricao"><p>A importa&ccedil;&atilde;o agiliza o processo de cadastramento de informa&ccedil;&otilde;es sobre dispositivos USB a serem utilizadas pelos m&oacute;dulos de detec&ccedil;&atilde;o nas esta&ccedil;&otilde;es de trabalho.<br>
            <br>
          A arquitetura das informa&ccedil;&otilde;es para importa&ccedil;&atilde;o foi definida a partir de 
        http://www.linux-usb.org/usb.ids, devendo ser devidamente observada a instru&ccedil;&atilde;o abaixo para a realiza&ccedil;&atilde;o do procedimento.</p>
          <p>Para efetuar a importa&ccedil;&atilde;o,  <u>copie o bloco de informa&ccedil;&otilde;es</u> <span class="style2">iniciando a partir do c&oacute;digo do fabricante at&eacute; o fim do nome do dispositivo USB</span>. </p>
          <p>Exemplo:</p>
          <p class="style3"><span class="TextoSelecionado">0001&nbsp;&nbsp;Fry&acute;s Electronics </span>
          <p><span class="TextoSelecionado">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;142b Arbiter Systems, Inc.</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;
            <br>
        </td>
      </tr>
    </table>
    <form action="importar_usb_device_informations.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
    
    <tr>
      <td colspan="2" class="label"><div align="left"><br>
        Campo para Importa&ccedil;&atilde;o:</div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><span class="label_peq_sem_fundo">
        <label>
          <textarea name="frm_te_importacao" cols="160" rows="30" class="normal" id="frm_te_importacao"></textarea>
        </label>
      </span></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><span class="label_peq_sem_fundo">
        <label>
          <input name="frmCheckEmptyTable" type="checkbox" id="frmCheckEmptyTable" value="S">
          Esvaziar Tabelas de Fabricantes e Dispositivos Antes da Importa��o. Caso esta op&ccedil;&atilde;o n&atilde;o seja marcada e haja  coincid&ecirc;ncia de informa&ccedil;&otilde;es, prevalecer&atilde;o as  informa&ccedil;&otilde;es antigas.
        </label>
      </span></td>
      <td>&nbsp;</td>
    </tr>
    
      </table>
      
      <p align="center"> 
        <input name="submit" type="submit" value="  Importar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Importa��o de Informa��es Sobre Dispositivos USB?');return valida_form();">
      </p>
    </form>
    <p>
      <?
    }
?>
</p>
</body>
</html>

