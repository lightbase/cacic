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
include_once "../include/library.php";

AntiSpy('1');
if($_REQUEST['submit']) 
	{
	if (atualizacao_especial(	$_POST['frm_nm_servidor'],
						  		$_POST['frm_nm_usuario'],
						  		$_POST['frm_te_senha'],
						  		$_POST['frm_nu_porta'],
						  		$_POST['frm_cs_tipo_ftp'],
						  		$_POST['frm_nm_pasta_origem'],
						  		$_POST['frm_nm_arquivo_origem'],						  
						  		$_POST['frm_nm_arquivo_destino'],
						  		$_POST['frm_nm_pasta_backup']))
		{
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'atualizacao_especial_arquivo_destino_'.$_POST['frm_nm_arquivo_destino'],$_SESSION["id_usuario"]);															
		header ("Location: ../include/operacao_ok.php?chamador=../admin/atualizacao_especial.php&tempo=1");												
		}
	else
		header ("Location: ../include/nenhuma_operacao_realizada.php?chamador=../admin/atualizacao_especial.php&tempo=1");														
	}
else
	{
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
	<SCRIPT LANGUAGE="JavaScript">
	
	function validaIP(id) 
		{
		var RegExPattern = /^((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])$/;
	
		if( (!(id.value.match(RegExPattern)) && (id.value!="")) || id.value=='0.0.0.0' || id.value=='255.255.255.255' ) 
			return false;
		return true;
		}	
	function valida_form() 
		{
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
.style2 {color: #000099}
-->
    </style>
	</head>
	
	<body background="../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_pasta_origem')">
	<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>
	<table width="85%" border="0" align="center">
	  <tr> 
		<td class="cabecalho">Atualiza&ccedil;&atilde;o Especial </td>
	  </tr>
	  <tr> 
		<td class="descricao">Mecanismo para atualiza&ccedil;&atilde;o de arquivos diversos no servidor, no &acirc;mbito da aplica&ccedil;&atilde;o.</td>
	  </tr>
	</table>
	<form method="post" ENCTYPE="multipart/form-data" name="form">
	  <table border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
		  <td colspan="5" class="label">&nbsp;</td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap bgcolor="#999999" class="cabecalho_secao"><div align="center" class="cabecalho_rel style2">
		    <div align="center">Origem (remoto) </div>
		  </div></td>
		</tr>
		
		<tr> 
		<td colspan="5" nowrap class="label"><div align="center">Pasta:</div></td>
	    </tr>
		<tr> 
		  <td colspan="5" height="1" bgcolor="#333333"></td>
		</tr>
		<tr> 
		  <td colspan="5" nowrap><div align="center">
		    <input name="frm_nm_pasta_origem" type="text" id="frm_nm_pasta_origem" size="100" maxlength="300" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
	      </div></td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="descricao"><div align="center"><span class="descricao_rel">Obs.: a partir do raiz. Ex.: updates</span></div></td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="descricao">&nbsp;</td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="label"><div align="center">Arquivo:</div></td>
	    </tr>
		<tr> 
		  <td colspan="5" height="1" bgcolor="#333333"></td>
		</tr>
		
		<tr>
		  <td colspan="5" nowrap class="label"><div align="center">
		    <input name="frm_nm_arquivo_origem" type="text" id="frm_nm_arquivo_origem" size="100" maxlength="300" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
	      </div></td>
	    </tr>
		
		<tr>
		  <td colspan="5" nowrap class="descricao"><div align="center"><span class="descricao_rel">(Ex.: atualizacoes.php)</span></div></td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="label">&nbsp;</td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="label">&nbsp;</td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap bgcolor="#999999" class="cabecalho_secao style2"><div align="center" class="cabecalho_rel style2">Destino (local)</div></td>
		</tr>
		
		
		<tr>
		  <td colspan="5" nowrap class="label"><div align="center">Pasta/Arquivo:</div></td>
	    </tr>
		<tr> 
		  <td colspan="5" height="1" bgcolor="#333333"></td>
		</tr>
		
		<tr>
		  <td colspan="5" nowrap class="label"><div align="center">
		    <input name="frm_nm_arquivo_destino" type="text" id="frm_nm_arquivo_destino" size="100" maxlength="300" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
	      </div></td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="descricao"><div align="center"><span class="descricao_rel">Obs.: a partir do raiz. Ex.: admin/atualizacoes.php</span></div></td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="label">&nbsp;</td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="label">&nbsp;</td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap bgcolor="#999999" class="cabecalho_secao"><div align="center" class="cabecalho_rel style2">
		    <div align="center">Backup </div>
		  </div></td>
		</tr>
		<tr>
          <td colspan="5" nowrap class="label"><div align="center">Pasta:</div></td>
	    </tr>
		<tr> 
		  <td colspan="5" height="1" bgcolor="#333333"></td>
		</tr>
		<tr> 
		<td colspan="5" nowrap><div align="center">
		  <input name="frm_nm_pasta_backup" type="text" id="frm_nm_pasta_backup" size="100" maxlength="300" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="tmp">      
		  </div></td>
		</tr>
		<tr>
		  <td colspan="5" nowrap class="descricao"><div align="center"><span class="descricao_rel">Obs.: a partir do raiz. Ex.: tmp</span></div></td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="label">&nbsp;</td>
	    </tr>
		<tr>
		  <td colspan="5" nowrap class="label">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="5" nowrap bgcolor="#999999" class="cabecalho_secao"><div align="center" class="cabecalho_rel style2"> 
		    <div align="center">Conex&atilde;o FTP </div>
		  </div></td>
		</tr>
		
		<tr> 
		<td colspan="5" nowrap class="label"><div align="center">Servidor:</div></td>
	    </tr>
		<tr> 
		  <td colspan="5" height="1" bgcolor="#333333"></td>
		</tr>
		<tr> 
		<td colspan="5" valign="top"><div align="center">
		  <input name="frm_nm_servidor" type="text" id="frm_nm_servidor" size="30" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
		  </div></td>
	    </tr>
		<tr>
		  <td colspan="5">&nbsp;</td>
	    </tr>
		<tr>
		  <td nowrap class="label"><div align="right">Porta:</div></td>
		  <td nowrap class="label">&nbsp;</td>
		  <td nowrap class="label">&nbsp;</td>
		  <td nowrap class="label">&nbsp;</td>
		  <td nowrap class="label">Tipo:</td>
	    </tr>
		<tr> 
		  <td colspan="5" height="1" bgcolor="#333333"></td>
		</tr>
		
		<tr>
		  <td align="right" valign="top">		    <div align="right">
		    <input name="frm_nu_porta" type="text" id="frm_nu_porta" size="10" maxlength="5" class="normal_numero" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="21" >	      
	      </div></td>
		  <td valign="top" nowrap>&nbsp;</td>
		  <td valign="top" nowrap>&nbsp;</td>
		  <td valign="top" nowrap>&nbsp;</td>
		  <td valign="top" nowrap><p>
              <label>
              <input name="frm_cs_tipo_ftp" type="radio" value="A" checked>
                ASCII</label>
		    &nbsp;&nbsp;
              <label>
              <input type="radio" name="frm_cs_tipo_ftp" value="B">
                Bin&aacute;rio</label>
              <br>
          </p></td>
	    </tr>
		<tr>
		  <td colspan="5">&nbsp;</td>
	    </tr>
		
		<tr>
		  <td nowrap class="label"><div align="right">Usu&aacute;rio:</div></td>
		  <td nowrap class="label">&nbsp;</td>
		  <td nowrap class="label">&nbsp;</td>
		  <td nowrap class="label">&nbsp;</td>
		  <td nowrap class="label">Senha:</td>
        </tr>
		<tr> 
		  <td colspan="5" height="1" bgcolor="#333333"></td>
		</tr>
		
		<tr>
		  <td valign="top"><div align="right">
		    <input name="frm_nm_usuario" type="text" id="frm_nm_usuario" size="30" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
	      </div></td>
		  <td valign="top">&nbsp;</td>
		  <td valign="top">&nbsp;</td>
		  <td valign="top">&nbsp;</td>
		  <td valign="top"><span class="label">
		    <input name="frm_te_senha" type="password" id="frm_te_senha" size="30" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
		  </span></td>
	    </tr>
		<tr>
		  <td colspan="5" valign="top">&nbsp;</td>
	    </tr>
		
		<tr>
		  <td colspan="5" valign="top">&nbsp;</td>
	    </tr>
	  </table>
	  <p align="center"> 
		<input name="submit" type="submit" value="  Efetuar Atualiza&ccedil;&otilde;es"  onClick="return valida_form();return Confirma('Confirma Inclusão de Rede?');"<?php echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?' disabled ':'')?>>
	  </p>
	</form>
	</body>
	</html>
	<?php
	}
	?>