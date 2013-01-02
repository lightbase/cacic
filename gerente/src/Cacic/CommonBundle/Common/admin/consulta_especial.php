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
include_once "../include/library.php";

//foreach($HTTP_POST_VARS as $i => $v) 
//	echo 'POST => i:'.$i.' v:'.$v.'<br>';

//foreach($HTTP_GET_VARS as $i => $v) 
//	echo 'GET => i:'.$i.' v:'.$v.'<br>';

AntiSpy('1'); // Permitido somente a este cs_nivel_administracao...
// 1 - Administra��o

		
if ($_POST['frmTouchTimeStamp'])
	{
	if (PHP_OS == "Linux" || PHP_OS == "Unix")	
		{
		foreach($HTTP_POST_VARS as $i => $v) 
			{
			$intPos = stripos2($i,'touchTimeStamp#',false);								
			if ($intPos)
				{
				$arrTouchTimeStamp = explode('#',$i);				
				$strTouchTimeStamp = "touch -t ".$_POST['frmTouchTimeStamp']." ".$_POST['frmPath'].str_replace('_ponto_','.',$arrTouchTimeStamp[1]);
				$cmdTouchTimeStamp = shell_exec($strTouchTimeStamp);
				}
			}
		}
	else
		{
		?>
		<script language="javascript">alert('ATEN��O: Este recurso est� dispon�vel apenas para Servidor com LINUX!');</script>
		<?
		}
	}
if ($_POST['frmChangePermissions'])
	{
	if (PHP_OS == "Linux" || PHP_OS == "Unix")	
		{
		foreach($HTTP_POST_VARS as $i => $v) 
			{
			$intPos = stripos2($i,'changePermissions#',false);								
			if ($intPos)
				{
				$arrChangePermissions = explode('#',$i);				
				$strChangePermissions = "chmod ".$_POST['frmChangePermissions']." ".$_POST['frmPath'].str_replace('_ponto_','.',$arrChangePermissions[1]);
				$cmdChangePermissions = shell_exec($strChangePermissions);
				}
			}
		}
	else
		{
		?>
		<script language="javascript">alert('ATEN��O: Este recurso est� dispon�vel apenas para Servidor com LINUX!');</script>
		<?
		}
	}

if ($_POST['frmExtractFileTGZ'])
	{
	if (PHP_OS == "Linux" || PHP_OS == "Unix")	
		{
		foreach($HTTP_POST_VARS as $i => $v) 
			{
			$intPos = stripos2($i,'extractFileTGZ#',false);								
			if ($intPos)
				{
				$arrExtractFileTGZ = explode('#',$i);				
				$strExtractFileTGZ = "tar -xvzf ".$_POST['frmPath'].str_replace('_ponto_','.',$arrExtractFileTGZ[1]);
				echo 'Tentarei executar "'.$strExtractFileTGZ.'"<br>';
				$cmdExtractFileTGZ = shell_exec($strExtractFileTGZ);
				}
			}
		}
	else
		{
		?>
		<script language="javascript">alert('ATEN��O: Este recurso est� dispon�vel apenas para Servidor com LINUX!');</script>
		<?
		}
	}

if ($_POST['frmCompactFileTGZ'])
	{
	if (PHP_OS == "Linux" || PHP_OS == "Unix")	
		{
		foreach($HTTP_POST_VARS as $i => $v) 
			{
			$intPos = stripos2($i,'compactFileTGZ#',false);								
			if ($intPos)
				{
				$arrCompactFileTGZ = explode('#',$i);				
				$strCompactFileTGZ = "tar -cvzf CompactFile.tgz ".$_POST['frmPath'].str_replace('_ponto_','.',$arrCompactFileTGZ[1]);
				echo 'Tentarei executar "'.$strCompactFileTGZ.'"<br>';
				$cmdExtractFileTGZ = shell_exec($strCompactFileTGZ);
				}
			}
		}
	else
		{
		?>
		<script language="javascript">alert('ATEN��O: Este recurso est� dispon�vel apenas para Servidor com LINUX!');</script>
		<?
		}
	}

if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
	echo $_POST['frmCreateNewFolder'].'<br>';
if ($_POST['frmCreateNewFolder'])
	{
if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
	echo PHP_OS.'<br>';
	
	if (PHP_OS == "Linux" || PHP_OS == "Unix")	
		{
		$strCreateNewFolder = "mkdir ".$_POST['frmPath'].$_POST['frmCreateNewFolder'];
if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
	echo $strCreateNewFolder.'<br>';
		
		if ($cmdCreateNewFolder = shell_exec($strCreateNewFolder))
			echo 'OK!';
		else
			echo 'Oops!';			
		}
	else
		{
		?>
		<script language="javascript">alert('ATEN��O: Este recurso est� dispon�vel apenas para Servidor com LINUX!');</script>
		<?
		}
	}

if ($_POST['frmDeleteFile'])
	{
	$intFilesToDelete = 0;
	$intFilesDeleted  = 0;

	foreach($HTTP_POST_VARS as $i => $v) 
		{
		$intPos = stripos2($i,'deleteFile#',false);								
		if ($intPos)
			{
			$intFilesToDelete ++;
			$arrDeleteFile = explode('#',$i);				

			if (unlink($_POST['frmPath'].str_replace('_ponto_','.',$arrDeleteFile[1])))
				$intFilesDeleted ++;
			}
		}

	if ($intFilesDeleted > 0)		
		if ($intFilesToDelete == $intFilesDeleted)
			header ("Location: ../include/operacao_ok.php?chamador=../admin/consulta_especial.php&frmPath=".$_GET['path']."&tempo=1");												
		else
			header ("Location: ../include/operacao_ok.php?texto=Parcialmente Realizada&chamador=../admin/consulta_especial.php&frmPath=".$_GET['path']."&tempo=1");														
	else
		header ("Location: ../include/nenhuma_operacao_realizada.php?chamador=../admin/consulta_especial.php&frmPath=".$_GET['path']."&tempo=1");																	

	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
</head>

<body background="../imgs/linha_v.gif" onLoad="SetaCampo('frmPath')">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<script>
function deleteFile()
	{
	var intFilesToDelete = 0;
	for (j=0;j<window.document.forms[0].elements.length;j++)
		{
		if (window.document.forms[0].elements[j].id.substring(0,15) == 'touchTimeStamp#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,18) == 'changePermissions#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,11) == 'deleteFile#'     && window.document.forms[0].elements[j].checked)
			intFilesToDelete ++;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'extractFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'compactFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;			
			
		}

	if (intFilesToDelete > 0 && (confirm('Confirma EXCLUS'+(intFilesToDelete == 1?'�O':'�ES')+' DO'+(intFilesToDelete == 1?'':'S') + ' ARQUIVO'+(intFilesToDelete == 1?'':'S')+' SELECIONADO'+(intFilesToDelete == 1?'':'S') + '?')))
		{
		for (j=0;j<window.document.forms[0].elements.length;j++)
			if (window.document.forms[0].elements[j].name == 'frmDeleteFile')
				window.document.forms[0].elements[j].value = 'OK';
		
		window.document.forms[0].submit();
		}
	else if (boolChamadaPorUsuario)
		{
		alert('ATEN��O: � necess�rio marcar algum �tem para processo de EXCLUS�O!');
		return false;
		}	
	}
	
function timeStamp()
	{
	var intFilesToTimeStamp = 0;
	for (j=0;j<window.document.forms[0].elements.length;j++)
		{
		if (window.document.forms[0].elements[j].id.substring(0,11) == 'deleteFile#'     && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,18) == 'changePermissions#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;
			
		if (window.document.forms[0].elements[j].id.substring(0,15) == 'touchTimeStamp#' && window.document.forms[0].elements[j].checked)
			intFilesToTimeStamp ++;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'extractFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'compactFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;						
		}

	if (intFilesToTimeStamp > 0)
		{
		var now 			= new Date();
		var nowDia, nowMes, nowHoras, nowMinutos;
		var strDia, strMes, strAno , strHoras, strMinutos;
	
		strDia     = ((nowDia     = (now.getDate())    + '')).length == 1 ? '0'+nowDia     : nowDia; 
		strMes     = ((nowMes     = (now.getMonth()+1) + '')).length == 1 ? '0'+nowMes     : nowMes; 	
		strAno     = now.getFullYear(); 	
		strHoras   = ((nowHoras   = (now.getHours())   + '')).length == 1 ? '0'+nowHoras   : nowHoras; 		
		strMinutos = ((nowMinutos = (now.getMinutes()) + '')).length == 1 ? '0'+nowMinutos : nowMinutos; 			

		var strDataHora 	= strDia + '/' + strMes + '/' + strAno + ' ' + strHoras + ':' + strMinutos;
		var strTimeStamp 	= prompt('Informe o TimeStamp no Formato: DD/MM/YYYY HH:MM', strDataHora);
		var boolData  		= validaData(strTimeStamp.substring(0, 10));
		var boolHora  		= validaHora(strTimeStamp.substring(11, 16));
															 
		if (boolData && boolHora && (confirm('Confirma ALTERA�'+(intFilesToTimeStamp == 1?'�O':'�ES') + ' DE TIMESTAMP DO'+(intFilesToTimeStamp == 1?'':'S') + ' ARQUIVO'+(intFilesToTimeStamp == 1?'':'S') + ' SELECIONADO'+(intFilesToTimeStamp == 1?'':'S') + '?')))
			{
			strDia    		= strTimeStamp.substring(0, 2);
			strMes    		= strTimeStamp.substring(3, 5);
			strAno    		= strTimeStamp.substring(6, 10);
			strHoras   		= strTimeStamp.substring(11, 13);
			strMinutos 		= strTimeStamp.substring(14, 17);

			for (j=0;j<window.document.forms[0].elements.length;j++)
				if (window.document.forms[0].elements[j].name == 'frmTouchTimeStamp')
					window.document.forms[0].elements[j].value = strAno+strMes+strDia+strHoras+strMinutos+'.00';
		
			window.document.forms[0].submit();
			}
		else
			return false;
		}
	else
		{
		alert('ATEN��O: � necess�rio marcar algum �tem para altera��o do TIMESTAMP!');
		return false;
		}	
	}
function changePermissions()
	{
	var intFilesToChangePermissions = 0;
	for (j=0;j<window.document.forms[0].elements.length;j++)
		{
		if (window.document.forms[0].elements[j].id.substring(0,11) == 'deleteFile#'     && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'touchTimeStamp#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,18) == 'changePermissions#' && window.document.forms[0].elements[j].checked)
			intFilesToChangePermissions ++;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'extractFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'compactFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;						
		}

	if (intFilesToChangePermissions > 0)
		{
		var strOldPermissions = '744';
		var strPermissions 	= prompt('Informe as Permiss�es no Formato: nnn', strOldPermissions);
															 
		if (confirm('Confirma ALTERA�'+(intFilesToChangePermissions == 1?'�O':'�ES') + ' DE PERMISSOES DO'+(intFilesToChangePermissions == 1?'':'S') + ' ARQUIVO'+(intFilesToChangePermissions == 1?'':'S') + ' SELECIONADO'+(intFilesToChangePermissions == 1?'':'S') + '?'))
			{

			for (j=0;j<window.document.forms[0].elements.length;j++)
				if (window.document.forms[0].elements[j].name == 'frmChangePermissions')
					window.document.forms[0].elements[j].value = strPermissions;
		
			window.document.forms[0].submit();
			}
		else
			return false;
		}
	else
		{
		alert('ATEN��O: � necess�rio marcar algum �tem para altera��o das PERMISS�ES!');
		return false;
		}	
	}

function extractFileTGZ()
	{
	var intFilesToExtract = 0;
	for (j=0;j<window.document.forms[0].elements.length;j++)
		{
		if (window.document.forms[0].elements[j].id.substring(0,11) == 'deleteFile#'     && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'touchTimeStamp#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,18) == 'changePermissions#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'extractFileTGZ#' && window.document.forms[0].elements[j].checked)
			intFilesToExtract ++;			

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'compactFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;						
		}

	if (intFilesToExtract > 0)
		{
															 
		if (confirm('Confirma EXTRA��O DO'+(intFilesToExtract == 1?'':'S') + ' ARQUIVO'+(intFilesToExtract == 1?'':'S') + ' SELECIONADO'+(intFilesToExtract == 1?'':'S') + '?'))
			{
			for (j=0;j<window.document.forms[0].elements.length;j++)
				if (window.document.forms[0].elements[j].name == 'frmExtractFileTGZ')
					window.document.forms[0].elements[j].value = 'A';
		
			window.document.forms[0].submit();
			}
		else
			return false;
		}
	else
		{
		alert('ATEN��O: � necess�rio marcar algum �tem para extra��o!');
		return false;
		}	
	}
function compactFileTGZ()
	{
	var intFilesToCompact = 0;
	for (j=0;j<window.document.forms[0].elements.length;j++)
		{
		if (window.document.forms[0].elements[j].id.substring(0,11) == 'deleteFile#'     && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'touchTimeStamp#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,18) == 'changePermissions#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'extractFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'compactFileTGZ#' && window.document.forms[0].elements[j].checked)
			intFilesToCompact ++;			
		}

	if (intFilesToCompact > 0)
		{
															 
		if (confirm('Confirma COMPACTA��O DO'+(intFilesToCompact == 1?'':'S') + ' ITE'+(intFilesToCompact == 1?'M':'NS') + ' SELECIONADO'+(intFilesToCompact == 1?'':'S') + '?'))
			{
			for (j=0;j<window.document.forms[0].elements.length;j++)
				if (window.document.forms[0].elements[j].name == 'frmCompactFileTGZ')
					window.document.forms[0].elements[j].value = 'A';
		
			window.document.forms[0].submit();
			}
		else
			return false;
		}
	else
		{
		alert('ATEN��O: � necess�rio marcar algum �tem para compacta��o!');
		return false;
		}	
	}

function createNewFolder()
	{
	for (j=0;j<window.document.forms[0].elements.length;j++)
		{
		if (window.document.forms[0].elements[j].id.substring(0,11) == 'deleteFile#'     && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'touchTimeStamp#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,18) == 'changePermissions#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'extractFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;

		if (window.document.forms[0].elements[j].id.substring(0,15) == 'compactFileTGZ#' && window.document.forms[0].elements[j].checked)
			window.document.forms[0].elements[j].checked = false;						
		}
	var strCreateNewFolder 	= prompt('Informe Nome para Nova Pasta');
	if (strCreateNewFolder && confirm('Confirma CRIA��O DE PASTA?'))
		{
		for (j=0;j<window.document.forms[0].elements.length;j++)
			if (window.document.forms[0].elements[j].name == 'frmCreateNewFolder')
				window.document.forms[0].elements[j].value = strCreateNewFolder;
		
		window.document.forms[0].submit();
		}
	else
		return false;
	}

function validaData(strData) 
	{
   	var err = 0
   	string = strData
   	var valid = "0123456789/"
   	var ok = "yes";
   	var temp;
   	for (var i=0; i< string.length; i++) 
		{
     	temp = "" + string.substring(i, i+1);
     	if (valid.indexOf(temp) == "-1") 
			err = 1;
  	 	}
		
   	if (string.length != 10) 
		err=1
		
   	b = string.substring(3, 5)	// month
   	c = string.substring(2, 3)	// '/'
   	d = string.substring(0, 2)	// day 
   	e = string.substring(5, 6)	// '/'
   	f = string.substring(6, 10)	// year

   	if (b<1 || b>12) 
		err = 1
		
   	if (c != '/') 
		err = 1
		
   	if (d<1 || d>31) 
		err = 1
		
   	if (e != '/') 
		err = 1
		
   	if (f < 1850 || f > 2050) 
		err = 1
		
   	if (b==4 || b==6 || b==9 || b==11)
     	if (d==31) 
			err=1

   	if (b==2)
   		{
     	var g=parseInt(f/4)
     	if (isNaN(g)) 
         	err=1 

     	if (d>29) 
			err=1
     	if (d==29 && ((f/4)!=parseInt(f/4))) 
			err=1
   		}
   	if (err==1) 
    	return false;
   	else 
    	return true;
	}

function validaHora(strHora) 
	{
    var hora, minuto;
    if (!(strHora.match(/^[0-9]{2,2}[:]{1,1}[0-9]{2,2}$/))) 
        return false;

    hora 	= parseInt(strHora.substr(0,2));
    minuto = parseInt(strHora.substr(3,2));
    if ((hora < 0) || (hora >24)) 
        return false;
		
    if ((minuto < 0) || (minuto >59)) 
        return false;
		
	return true;
	}	
function retiraCaracter(string, caracter) 
	{
    var i = 0;
    var final = '';
    while (i < string.length) 
		{
        if (string.charAt(i) == caracter) 
			{
            final += string.substr(0, i);
            string = string.substr(i+1, string.length - (i+1));
            i = 0;
        	}
        else 
            i++;
    	}
    return final + string;
	}
</script>
<?
$path = $_REQUEST['frmPath'];

if ($path <> '')
	GravaLog('SEL',$_SERVER['SCRIPT_NAME'],'consulta_especial');															
	
$path = ($path?$path:GetMainFolder().'/');

$arrPath = explode('/',$path);
if ($arrPath[count($arrPath)-2]=='..')
	{
	$path = '';
	for ($i = 0; $i < (count($arrPath)-3);$i++)
		$path .= $arrPath[$i].'/';
	}

$strRecursivo = ($_POST['chkExpande']=='S'?'R':'');

$ordem = '';
if ($_GET['ordem']=='datahora')
	$ordem = 't';


	
// Falta tratar se o S.O. � Linux ou Win...
$filelist = shell_exec( "ls $path -liahp".$ordem.$strRecursivo." --full-time" );

$file_arr = explode( "\n", $filelist );
array_pop( $file_arr ); // last line is always blank
?>
<table width="90%" border="0" align="center">
  <tr> 
	<td class="cabecalho">Consulta Especial </td>
  </tr>
  <tr> 
	<td class="descricao">Mecanismo para consulta  no &acirc;mbito do servidor de aplica&ccedil;&atilde;o.</td>
  </tr>
</table>
<?
if ($_SESSION['cs_nivel_administracao']== 1)
	{
	if ($_GET['frm_file'])
		{
		if ($path <> '')
			GravaLog('SEL',$_SERVER['SCRIPT_NAME'],'consulta_especial_file_'.$_GET['frm_file']);															
		
		$handle = @fopen($_GET['frm_file'], "r");
		if ($handle) 
			{
			?>
			<br><br>
			<table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
			<td><strong>Conte�do do Arquivo <i>"<? echo $_GET['frm_file'];?>"</i></strong></td>						
			</tr>
			<tr>
			<td><textarea name="listFile" id="listFile" cols="110" rows="10">
			<?
			while (!feof($handle)) 
				{
				$buffer = ''.fgets($handle, 4096);
				echo $buffer;				
				}
			fclose($handle);
			?>
			</textarea>
			</td>
			</tr>
			</table>		
			<br>
			<?
			}
		}
	?>
	<form method="post" ENCTYPE="multipart/form-data" name="formConsultaEspecial">
	  <table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		<td><strong>Path Atual:
		<input name="frmPath" type="text" size="130" maxlength="130" value="<? echo $path;?>" readonly="yes">
		</strong></td>						
		</tr>
	  </table>
		<P>
	  <table width="85%" border="1" align="center" cellpadding="1" cellspacing="1">		
		<tr>
		  <td width="13%"><strong>Permiss&otilde;es</strong></td>
		  <td width="6%"><div align="center"><strong>Dono</strong></div></td>
		  <td width="7%"><div align="center"><strong>Grupo</strong></div></td>
		  <td width="6%"><div align="center"><strong><a href="consulta_especial.php?frmPath=<? echo $path;?>&ordem=datahora">Data</a></strong></div></td>
		  <td width="6%"><div align="center"><strong><a href="consulta_especial.php?frmPath=<? echo $path;?>&ordem=datahora">Hora</a></strong></div></td>
		  <td width="11%"><div align="right"><strong>Tamanho</strong></div></td>
		  <td width="36%"><strong><a href="consulta_especial.php?frmPath=<? echo $path;?>&ordem=nome">Arquivo/Pasta</a>&nbsp;&nbsp;&nbsp;<img src="../imgs/newfolder.gif" alt="Nova Pasta" width="18" height="18" title="Criar Nova Pasta" onClick="return createNewFolder()" border="none"></strong></td>
		  <td align="center"><img src="../imgs/b_drop.gif" border="0" width="18" height="18" title="Exclus�o de Arquivos" onClick="return deleteFile()"></td>
		  <td align="center"><img src="../imgs/timestamp.gif" border="0" width="18" height="18" title="Alterar TimeStamp de Arquivos" onClick="return timeStamp()"></td>
		  <td align="center"><img src="../imgs/details.gif" width="18" height="18" title="Alterar Permiss�es de Arquivos" onClick="return changePermissions()"></td>
	      <td align="center"><img src="../imgs/extractfile.gif" width="18" height="18" title="Extrair Arquivo TGZ" onClick="return extractFileTGZ()"></td>
		  <td align="center"><img src="../imgs/compactfile.ico" width="18" height="18" title="Gerar Arquivo TGZ" onClick="return compactFileTGZ()"></td>
		</tr>
		  
		<?
	
		/*
		for ($i=0; $i < count($file_arr); $i++)
			{
			$Campos = explode(' ',$file_arr[$i]);
			echo '<br>';
			for ($j=0;$j<count($Campos);$j++)
				{
				echo 'Indice: '.$j.' Valor: '.$Campos[$j].'<br>';
				}
			}
		*/
		$strCorAtual = 1;
		for ($i=0; $i < count($file_arr); $i++)
			{
			$Campos = explode(' ',$file_arr[$i]);
			for ($j=count($Campos)-1;$j > 0;$j--)
				{			
				if ($strItem=='')
					{
					$strItem = $Campos[$j];						
					$j--;
					}
				elseif ($strHora=='')
					$strHora = substr($Campos[$j],0,5);
				elseif ($strData=='')
					$strData = substr($Campos[$j],8,2).'/'.substr($Campos[$j],5,2).'/'.substr($Campos[$j],0,4);
				elseif ($strTamanho=='')
					$strTamanho = $Campos[$j];
				elseif ($strGrupo=='')
					$strGrupo = $Campos[$j];
				elseif ($strDono=='')
					{
					$strDono = $Campos[$j];
					$j--;
					}
				elseif ($strPermissoes=='')
					$strPermissoes = $Campos[$j];
				}
			if ($strDono.$strGrupo.$strData.$strHora <> '')
				{
				if ($strItem <> './')
					{
					$intPos = stripos2($strItem,'/');					
					?>
					<tr <? if ($strCorAtual) echo 'bgcolor="#CCCCCC"'; ?>>													
					<td><? echo $strPermissoes;?></td>			
					<td><div align="center"><? echo $strDono;?></div></td>							
					<td><div align="center"><? echo $strGrupo;?></div></td>									  
					<td><div align="center"><? echo $strData;?></div></td>
					<td><div align="center"><? echo $strHora;?></div></td>			
					<td><div align="right"><? echo $strTamanho;?></div></td>			
					<td <? if ($intPos) echo 'colspan="2"';?>>
					<a href="consulta_especial.php?
					<? 
					$strCorAtual = !$strCorAtual;
					if ($intPos)
						{
						?>				
						frmPath=<? echo $path.$strItem;?>">
						<strong><font color="#999999">
						<?				
						} 
					else
						{
						?>
						frm_file=<? echo $path.$strItem;?>">				
						<?
						}
					$strItem = str_replace('/','',$strItem);				
					
					if ($strItem <> '..')
						echo $strItem;
					elseif ($path <> '/')
						{
						?>
						<img src="../imgs/volta_nivel.gif" border="0">					
						<?
						}
					?>
					</a>					</td>

					<?
					if (!$intPos)
						{
						?>
						<td align="center" valign="middle"><input type="checkbox" id="deleteFile#<? echo str_replace('.','_ponto_',$strItem);?>" name="deleteFile#<? echo str_replace('.','_ponto_',$strItem);?>" title="Marque para excluir o arquivo '<? echo $strItem;?>'"></td>
						<input type="hidden" id="frmDeleteFile" name="frmDeleteFile" value="">						
						<?						
						}
						?>
					<td align="center" valign="middle"><input type="checkbox" id="touchTimeStamp#<? echo str_replace('.','_ponto_',$strItem);?>" name="touchTimeStamp#<? echo str_replace('.','_ponto_',$strItem);?>" title="Marque para alterar o TimeStamp d<? echo (!$intPos?'o arquivo':'a pasta');?> '<? echo $strItem;?>'"></td>
					<td align="center" valign="middle"><input type="checkbox" id="changePermissions#<? echo str_replace('.','_ponto_',$strItem);?>" name="changePermissions#<? echo str_replace('.','_ponto_',$strItem);?>" title="Marque para alterar as Permiss�es d<? echo (!$intPos?'o arquivo':'a pasta');?> '<? echo $strItem;?>'"></td>
					<?
					$isTGZ = stripos2(strtolower($strItem),'.tgz');					
					?>
					<td align="center" valign="middle">
					<? if ($isTGZ)
							echo '<input type="checkbox" id="extractFileTGZ#'. str_replace('.','_ponto_',$strItem) . '" name="extractFileTGZ#'. str_replace('.','_ponto_',$strItem) .'" title="Marque para extrair o arquivo '. $strItem . '">';
					?>					
					</td>
					<?
						echo '<td align="center" valign="middle">';						
						echo '<input type="checkbox" id="compactFileTGZ#'. str_replace('.','_ponto_',$strItem) . '" name="compactFileTGZ#'. str_replace('.','_ponto_',$strItem) .'" title="Marque para compactar o �tem '. $strItem . '">';
						echo '</td>';
					?>
					<input type="hidden" id="frmTouchTimeStamp" name="frmTouchTimeStamp" value="">
					<input type="hidden" id="frmChangePermissions" name="frmChangePermissions" value="">					
					<input type="hidden" id="frmCreateNewFolder" name="frmCreateNewFolder" value="">										
					<input type="hidden" id="frmExtractFileTGZ" name="frmExtractFileTGZ" value="">															
					<input type="hidden" id="frmCompactFileTGZ" name="frmCompactFileTGZ" value="">																				
					</tr>				
					<?
					}
				else
					$strTotal -= str_replace('K','',$strTamanho);
				}
			else
				$strTotal = $strItem;
	
			$strPermissoes  = '';
			$strDono 		= '';
			$strGrupo		= '';
			$strData		= '';
			$strHora		= '';
			$strTamanho		= '';
			$strItem		= '';
			}	
		?>
		<tr>								
		<td colspan="6"><div align="right"><strong><? echo ($strTotal/2);?></strong></div></td>
		<TD colspan="6">&nbsp;</TD>
		</tr>		
	  </table>
	  </table>
	  <p>&nbsp;</p>
	  <p align="center">&nbsp;</p>
	</form>
	</body>
	</html>
	<?
	}
