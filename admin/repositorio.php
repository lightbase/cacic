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

require_once($_SERVER['DOCUMENT_ROOT'] . 'include/library.php');
// Comentado temporariamente - AntiSpy();
if ($_REQUEST['nm_arquivo'])
	{
	@unlink($_SERVER['DOCUMENT_ROOT'] . 'repositorio/'.$_REQUEST['nm_arquivo']);
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . 'repositorio/'.$_REQUEST['nm_arquivo']))
		{
		echo '<script> alert("Não foi possível excluir o arquivo '.$_REQUEST['nm_arquivo'].'. Verifique as permissões de escrita no diretório repositorio!") </script>';
		}
	else
		{
		conecta_bd_cacic();
		$query = "DELETE from redes_versoes_modulos WHERE nm_modulo = '".$_REQUEST['nm_arquivo']."'";
		$result = mysql_query($query) or die('Ocorreu um erro durante exclusão de referência em redes_versoes_modulos ou sua sessão expirou!');		
		}
	}

	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	<body background="../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../../include/cacic.js'?>"></script>						
	<table width="90%" border="0" align="center">
	  <tr> 
		
    <td class="cabecalho">Reposit&oacute;rio</td>
	  </tr>
	  <tr> 
		
    <td class="descricao">Nesta p&aacute;gina &eacute; poss&iacute;vel verificar 
      o conte&uacute;do do reposit&oacute;rio, bem como excluir os objetos que 
      n&atilde;o ser&atilde;o utilizados nos updates das SubRedes.</td>
	  </tr>
	</table>
  <div align="center"> 
    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
      <?
		$v_classe = "label";
		?>
      <tr> 
        <td height="20"></td>
      </tr>
      <tr> 
        <td nowrap align="center" colspan="4" class="<? echo $v_classe; ?>"><br>
          Conteúdo do Reposit&oacute;rio:</td>
      </tr>
      <tr> 
        <td colspan="4" height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td nowrap colspan="2"></td>
      </tr>
      <tr> 
        <td nowrap colspan="2"><table border="1" align="center" cellpadding="2" bordercolor="#999999">
            <tr bgcolor="#FFFFCC"> 
              <td bgcolor="#EBEBEB" align="center"></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela">Arquivo</td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela">Tamanho(Kb)</td>
              <td align="right" colspan="3" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela">Data 
                / Hora</td>
            </tr>
            <? 
	  if ($handle = opendir($_SERVER['DOCUMENT_ROOT'] . 'repositorio')) 
		{
		$v_nomes_arquivos = array();		
		while (false !== ($v_arquivo = readdir($handle))) 
			{
			if (substr($v_arquivo,0,1) != "." and $v_arquivo != "netlogon" and $v_arquivo != "supergerentes") 		
				{
				// Armazeno o nome do arquivo
				array_push($v_nomes_arquivos, $v_arquivo);
				}
			}
		sort($v_nomes_arquivos);
		for ($cnt_arquivos = 0; $cnt_arquivos < count($v_nomes_arquivos); $cnt_arquivos++)
			{
			$v_dados_arquivo = lstat($_SERVER['DOCUMENT_ROOT'] . 'repositorio/'.$v_nomes_arquivos[$cnt_arquivos]);
			echo '<tr>';
			?>
			<td><a href="repositorio.php?nm_arquivo=<? echo $v_nomes_arquivos[$cnt_arquivos];?>" onClick="return Confirma('Confirma Exclusão do objeto <? echo $v_nomes_arquivos[$cnt_arquivos];?>?)"><img src="../imgs/lixeira.ico" width="20" height="20" border="0"></a></td>
			<?
			echo '<td>'.$v_nomes_arquivos[$cnt_arquivos].'</td>';										
			echo '<td align="right">'.number_format(($v_dados_arquivo[7]/1024), 1, '', '.').'</td>';			
			echo '<td align="right">&nbsp;'.strftime("%d/%m/%Y  %H:%Mh", $v_dados_arquivo[9]).'</td></tr>';							
			}
		}
	 ?>
          </table></td>
      </tr>
    </table>
  </div>
	</body>
	</html>