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
require_once('include/config.php');
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
      <td class="cabecalho">Sistema CACIC - Documentos e Programas Avulsos</td>
  </tr>
  <tr> 
      <td class="descricao">Os &iacute;tens  abaixo referem-se a um kit b&aacute;sico de documenta&ccedil;&atilde;o e programas para <strong>Instala&ccedil;&atilde;o de  Agentes Principais do Sistema em MS-Windows ou GNU/Linux</strong>, <strong>Coleta de Dados Patrimoniais atrav&eacute;s de M&oacute;dulo Avulso</strong> e <strong>Cliente Espec&iacute;fico</strong> para uso com o m&oacute;dulo <strong>srCACIC - Suporte Remoto Seguro</strong>. A opera&ccedil;&atilde;o de &quot;download&quot; se d&aacute; clicando-se sobre os referidos objetos. </td>
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
	<?
	/*
	    <tr> 
	    <td bgcolor="#CCCCCC" class="label"><br>
	      <a href="tribo/docs/Cacic-Implantacao-Ambiente-Centralizado.pdf" target="_self">Roteiro para Instala&ccedil;&atilde;o do Sistema CACIC em Ambiente Centralizado<span class="style6">&nbsp;(249K)</span></a></td>
    	</tr>  
    <tr> 
      <td height="17">&nbsp;</td>
    </tr>
		
    	<tr> 
      	<td height="1" bgcolor="#333333"></td>
    	</tr>
		*/ ?>
	    <tr> 
	    <td bgcolor="#CCCCCC" class="label"><br>
	      <a href="tribo/docs/Cacic-Manual-Instalacao-Utilizacao-v2601.pdf" target="_self">Manual para Instala��o e Utiliza��o - vers�o 2.6.0-Beta-1<span class="style6">&nbsp;(2.4MB)</span></a></td>
    	</tr>    
    <tr> 
      <td height="17">&nbsp;</td>
    </tr>
		
    	<tr> 
      	<td height="1" bgcolor="#333333"></td>
    	</tr>
        
	<?
	if(file_exists($path_relativo_repositorio_instalacao.'/versoes_agentes.ini') and is_readable($path_relativo_repositorio_instalacao.'/versoes_agentes.ini'))
		$v_array_versoes_agentes = parse_ini_file($path_relativo_repositorio_instalacao.'/versoes_agentes.ini');
	else 
		{
		$v_array_versoes_agentes['chkcacic.exe'] 	= '???';
		$v_array_versoes_agentes['mapacacic.exe'] 	= '???';
		$v_array_versoes_agentes['srcaciccli.exe'] 	= '???';		
		}
		
	if(file_exists($path_relativo_repositorio_instalacao.'/../versoes_agentes.ini') and is_readable($path_relativo_repositorio_instalacao.'/../versoes_agentes.ini'))
		$v_array_versoes_agentes_LINUX = parse_ini_file($path_relativo_repositorio_instalacao.'/../versoes_agentes.ini');
	else 
		{
		$v_array_versoes_agentes_LINUX['pyCACIC'] 			= '???';				
		$v_array_versoes_agentes_LINUX['te_pacote_PyCACIC'] = '???';						
		}
		
	?>		
    <tr> 
      <td class="label"> 
        &nbsp; &nbsp;<br>
        <a href="<? echo $path_relativo_repositorio_instalacao;?>/chkcacic.exe">ChkCACIC -  Instalador para plataformas MS-Windows <span class="style2"><span class="style6">(vers�o <? echo $v_array_versoes_agentes['chkcacic.exe'];?>  ~  270K)</span></span></a></td>
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
    <?

	if (file_exists($path_relativo_repositorio_instalacao . '/pyCACIC_'.$v_array_versoes_agentes_LINUX['pyCACIC'].'_Debian.deb'))
		{
		?>
    <tr> 
      <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label"><a href="<? echo $path_relativo_repositorio_instalacao;?>/pyCACIC_<? echo $v_array_versoes_agentes_LINUX['pyCACIC'];?>_Debian.deb">pyCACIC (DEBIAN) - Instalador em plataforma GNU/Linux - Distros Debian <span class="style2"><span class="style6">(vers�o <? echo $v_array_versoes_agentes_LINUX['pyCACIC'];?>  ~  800K)</span></span></a> </td>
          </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
		  
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <?
	}

	if (file_exists($path_relativo_repositorio_instalacao . '/pyCACIC_'.$v_array_versoes_agentes_LINUX['pyCACIC'].'_RedHat.rpm'))
		{
		?>
    <tr> 
      <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label"><a href="<? echo $path_relativo_repositorio_instalacao;?>/pyCACIC_<? echo $v_array_versoes_agentes_LINUX['pyCACIC'];?>_RedHat.rpm">pyCACIC (RedHat) - Instalador plataforma GNU/Linux - Distros RedHat <span class="style2"><span class="style6">(vers�o <? echo $v_array_versoes_agentes_LINUX['pyCACIC'];?>  ~  800K)</span></span></a> </td>
          </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
		  
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <?
	}

	if (file_exists($path_relativo_repositorio_instalacao . '/pyCACIC_'.$v_array_versoes_agentes_LINUX['pyCACIC'].'_Generic.tar.gz'))
		{
		?>

    <tr> 
      <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label"><a href="<? echo $path_relativo_repositorio_instalacao;?>/PyCACIC_<? echo $v_array_versoes_agentes_LINUX['pyCACIC'];?>_Generic.tar.gz">pyCACIC (Generic) - Instalador em plataforma GNU/Linux - Distros Gen�ricas <span class="style2"><span class="style6">(vers�o <? echo $v_array_versoes_agentes_LINUX['pyCACIC'];?>  ~  800K)</span></span></a> </td>
          </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
		  
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
    
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <?
	}
	?>
    <tr> 
      <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label"><a href="<? echo $path_relativo_repositorio_instalacao;?>/mapacacic.exe">MapaCACIC - M&oacute;dulo Avulso para Coleta de Informa&ccedil;&otilde;es Patrimoniais <span class="style2"><span class="style6">(vers�o <? echo $v_array_versoes_agentes['mapacacic.exe'];?>  ~  250K)</span></span></a> </td>
          </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
		  
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
    
    <tr> 
      <td class="label"> 
        &nbsp; &nbsp;<br>
        <a href="<? echo $path_relativo_repositorio_instalacao;?>/srcaciccli.exe">srCACICcli -  Cliente Espec&iacute;fico para Suporte Remoto Seguro do Sistema CACIC  <span class="style2"><span class="style6">(vers�o <? echo $v_array_versoes_agentes['srcaciccli.exe'];?>  ~  258K)</span></span></a></td>
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
