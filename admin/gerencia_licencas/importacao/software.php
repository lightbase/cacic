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
require_once('../../../include/library.php');
conecta_bd_cacic();


if ($_POST['consultar']) {
		$v_textarea = $_POST['txtSoftwares'];				
		$v_array_textarea = explode(";",$v_textarea);
		$v_array_textarea = str_replace("*","",$v_array_textarea);
	}
	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../include/cacic.css">

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="../../../imgs/linha_v.gif" onLoad="SetaCampo('tipo_consulta')">
<script language="JavaScript" type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'].'../../../include/cacic.js';?>"></script>

<form action="<? echo $PHP_SELF; ?>" method="post" name="form1">
<table width="90%" border="0" align="center">
<tr> 
<td class="cabecalho">Formul&aacute;rio para importar dados de software</td>
</tr>
<tr> 
<td>&nbsp;</td>
</tr>
</table>
<tr><td height="1" colspan="2" bgcolor="#333333"></td></tr>
<tr><td height="30" colspan="2"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr><td colspan="2" class="label">Ctrl-C Ctrl-V</td></tr>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
<td height="1" bgcolor="#333333"></td>
</tr>
<tr> 
<td height="28"><table width="96%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr> 
            <td> 
              <TEXTAREA name="txtSoftwares" rows=20 cols=80>
	      </TEXTAREA> 
              </td>
          </tr>
	  <tr>
            <td align="center"><input name="consultar" type="submit" id="consultar2" value="Importar"></td>
	  </tr>
        </table></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
  </table>
  </form>
<?

if ($_POST['consultar']) {

?>
<p align="center" class="descricao">Testando</p> 
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="3" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela"><div align="center">ID</div></td> 
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">Qtde Licen&ccedil;a</div></td> 
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">Descri&ccedil;&atilde;o</div></td> 
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">Nro. Midia</div></td> 
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">Local Midia</div></td> 
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">OBS</div></td> 
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">Nome do Software</div></td>
          <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;

	for ($v1=0; $v1 < count($v_array_textarea) - 1; $v1 = $v1 + 7) {	
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $v_array_textarea[$v1]; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $v_array_textarea[$v1+1]; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $v_array_textarea[$v1+2]; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $v_array_textarea[$v1+3]; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $v_array_textarea[$v1+4]; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $v_array_textarea[$v1+5]; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $v_array_textarea[$v1+6]; ?></div></td>
          <td nowrap>&nbsp;</td>
<?
	  $query = "INSERT INTO softwares(id_software, qt_licenca, te_local_midia, te_obs, nm_software) 
		    VALUES (" . $v_array_textarea[$v1] . "," . $v_array_textarea[$v1+1] . ",'"   
			      . $v_array_textarea[$v1+4] . "','" . $v_array_textarea[$v1+5] . "','" 
			      . $v_array_textarea[$v1+6] . "')";

//	  $result = mysql_query($query) or die('erro no insert');
?>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
}
		
?>
      </table></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
</table>
<?
				}
?>
</body>
</html>
