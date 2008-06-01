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
/*
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para verificar permissões do usuário!
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administra<E7><E3>o
// 2 - Gest<E3>o Central
// 3 - Supervis<E3>o

if ($_REQUEST['nm_arquivo'])
	{if(!@unlink($_SERVER['DOCUMENT_ROOT'] . '/repositorio/'.$_REQUEST['nm_arquivo']))
		{
		echo '<script>alert("'.$oTranslator->_('Nao foi possivel excluir o arquivo %1. Verifique as permissoes de escrita no diretorio repositorio!', array($_REQUEST['nm_arquivo'])).'")</script>';
		}
	else
		{
		conecta_bd_cacic();
		$query = "DELETE from redes_versoes_modulos WHERE nm_modulo = '".$_REQUEST['nm_arquivo']."'";
		$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro durante exclusao de referencia em %1 ou sua sessao expirou!', array('redes_versoes_modulos')));		
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
	<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>						
	<table width="90%" border="0" align="center">
	  <tr> 
		
    <td class="cabecalho"><?=$oTranslator->_('Repositorio');?></td>
	  </tr>
	  <tr> 
		
    <td class="descricao"><?=$oTranslator->_('kciq_msg Repositorio help');?></td>
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
          <?=$oTranslator->_('Conteudo do repositorio');?>:</td>
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
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Arquivo');?></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Tamanho(kb)');?></td>
              <td align="right" colspan="3" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Data e hora');?></td>
            </tr>
            <? 
	  if ($handle = opendir($_SERVER['DOCUMENT_ROOT'] . '/repositorio')) 
		{
		$v_nomes_arquivos = array();		
		while (false !== ($v_arquivo = readdir($handle))) 
			{
			if (substr($v_arquivo,0,1) != "." and $v_arquivo != "netlogon" and $v_arquivo != "supergerentes" and $v_arquivo != "install")
				{
				// Armazeno o nome do arquivo
				array_push($v_nomes_arquivos, $v_arquivo);
				}
			}
		sort($v_nomes_arquivos);
		for ($cnt_arquivos = 0; $cnt_arquivos < count($v_nomes_arquivos); $cnt_arquivos++)
			{
			$v_dados_arquivo = lstat($_SERVER['DOCUMENT_ROOT'] . '/repositorio/'.$v_nomes_arquivos[$cnt_arquivos]);
			echo '<tr>';
			?>
			<td><a href="repositorio.php?nm_arquivo=<? echo $v_nomes_arquivos[$cnt_arquivos];?>" onClick="return Confirma('<?=$oTranslator->_('Confirma exclusao?');?><? echo $v_nomes_arquivos[$cnt_arquivos];?>)');"><img src="../imgs/lixeira.ico" width="20" height="20" border="0"></a></td>
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
