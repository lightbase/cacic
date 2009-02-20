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
?>
<html>
<head>
<title>Configurar Gerente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
require_once('include/selecao_listbox.js');  
?>
<link rel="stylesheet"   type="text/css" href="include/cacic.css">
<style type="text/css">
<!--
.style2 {font-size: 9px}
.style6 {color: #000099}
-->
</style>
</head>

<body background="imgs/linha_v.gif" onLoad="SetaCampo('te_notificar_mudanca_hardware');">
	<form action="admin/config_gerais_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form();return valida_notificacao_hardware();">
<table width="90%" border="0" align="center">

  <tr> 
      <td class="cabecalho">Documentos e Programas para Instala&ccedil;&atilde;o do Sistema CACIC</td>
  </tr>
  <tr> 
      <td class="descricao">Os &iacute;tens  abaixo referem-se a um kit b&aacute;sico de documenta&ccedil;&atilde;o e programas para verifica&ccedil;&atilde;o/instala&ccedil;&atilde;o/atualiza&ccedil;&atilde;o dos agentes principais do sistema CACIC. A opera&ccedil;&atilde;o de &quot;download&quot; se d&aacute; clicando-se sobre os referidos objetos. </td>
  </tr>
</table>
<br><br>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
	    <tr>
	      <td bgcolor="#CCCCCC" class="label">&nbsp;</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
	
	    <tr> 
	    <td bgcolor="#CCCCCC" class="label"><br>
	      <a href="tribo/docs/Cacic-Implantacao-Ambiente-Centralizado.pdf" target="_self">Roteiro para Instala&ccedil;&atilde;o do Sistema CACIC em Ambiente Centralizado<span class="style2"><a href="repositorio/install/Cacic-Implantacao-Ambiente-Centralizado.pdf" target="_self"><span class="style2"><span class="style6">&nbsp;(249K)</span></span></a></td>
    	</tr>  
    <tr> 
      <td height="17">&nbsp;</td>
    </tr>
		
    	<tr> 
      	<td height="1" bgcolor="#333333"></td>
    	</tr>
	<?
	if(file_exists('repositorio/versoes_agentes.ini') and is_readable('repositorio/versoes_agentes.ini'))
		$v_array_versoes_agentes = parse_ini_file('repositorio/versoes_agentes.ini');
	else {
		$v_array_versoes_agentes['chkcacic.exe'] = '???';
		$v_array_versoes_agentes['mapacacic.exe'] = '???';
	}
	?>		
    <tr> 
      <td class="label"> 
        &nbsp; &nbsp;<br>
        <a href="repositorio/chkcacic.exe">ChkCACIC -  Verificador/Instalador/Atualizador do Sistema CACIC  <span class="style2"><span class="style6">(versão <? echo $v_array_versoes_agentes['chkcacic.exe'];?>  ~  260K)</span></span></a></td>
    </tr>
    
	  
    
    <tr> 
      <td height="17">&nbsp;</td>
    </tr>
	
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label"><a href="repositorio/install/mapacacic.exe">MapaCACIC - M&oacute;dulo Avulso para Coleta de Informa&ccedil;&otilde;es Patrimoniais <span class="style2"><span class="style6">(versão <? echo $v_array_versoes_agentes['mapacacic.exe'];?>  ~  248K)</span></span></a> </td>
          </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
		  
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          
        </table></td>
    </tr>
    
   
    <tr> 
      <td>&nbsp;</td>
    </tr>
  </table>
</form>		  
<p>&nbsp;</p>
</body>
</html>
