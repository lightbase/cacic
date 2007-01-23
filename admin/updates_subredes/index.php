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
require_once('../../include/library.php');
anti_spy();
if ($_REQUEST['ExecutaUpdates']=='Executar Updates')
	{				
	foreach($HTTP_POST_VARS as $i => $v) 
		{
		if ($v && substr($i,0,7)=='update_' && $v <> 'on')
			{
			if ($v_updates <> '') $v_updates .= '__';
			$v_updates .= $v;		
			}
		if ($v && substr($i,0,6)=='redes_' && $v <> 'on')
			{
			if ($v_redes <> '') $v_redes .= '__';			
			$v_redes .= $v;

			if ($v_force_redes <> '') $v_force_redes .= ',';						
			$v_force_redes .= "'".$v."'";
			}			
		// Verifico se a versão foi FORÇADA ao update.
		if ($v && substr($i,0,6)=='force_' && $v <> 'on')
			{
			if ($v_force_modulos <> '') 
				{
				$v_force_modulos .= ",";			
				}
			$v_force_modulos .= "'".$v."'";		
			}			
					
		}

	if ($v_force_modulos)
		{
		$query_del = 'DELETE 
					  FROM 		redes_versoes_modulos 
		              WHERE 	id_local = '.$_SESSION['id_local'].' AND
					  			id_ip_rede in (' . $v_force_redes . ') and nm_modulo in ('.$v_force_modulos.')';
		conecta_bd_cacic();					
		$result_del = mysql_query($query_del);
		GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'redes_versoes_modulos');					
		}

	// O script updates_subredes.php espera receber o parâmetro v_parametros contendo uma string com a seguinte formação:
	// objeto1__objeto2__objetoN_-_rede1__rede2__rede3__redeN  
	// Onde: __  = Separador de itens
	//       _-_ = Separador de Matrizes		
        header ("Location: updates_subredes.php?v_parametros=".$v_updates.'_-_'.$v_redes);			
	}
else
	{
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<? require_once('../../include/opcoes_avancadas_combos.js');  ?>							
	</head>
	
	<body background="../../imgs/linha_v.gif">

	<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
	<table width="90%" border="0" align="center">
	  <tr> 
		<td class="cabecalho">Updates de SubRedes</td>
	  </tr>
	  <tr> 
		
    <td class="descricao">As informa&ccedil;&otilde;es referem-se aos objetos 
      constantes do reposit&oacute;rio, os quais poder&atilde;o ser assinalados 
      para verifica&ccedil;&atilde;o de exist&ecirc;ncia e/ou vers&otilde;es nas 
      SubRedes cadastradas.</td>
	  </tr>
	</table>
	<form method="post" ENCTYPE="multipart/form-data" name="forma">
	<script>
	function MarcaIncondicional(field,p_name) 
	{
	for (i = 1; i < field.length; i++) 
		if (field[i].type == 'checkbox' && field[i].name == p_name)
			field[i].checked = true;			

	return true;
	}

	</script>

  <div align="center">
    <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
      <?
		$v_classe = "label";
		?>
      <tr> 
        <td height="20"></td>
      </tr>
	  
      <tr> 
        <td nowrap align="center" colspan="5" class="<? echo $v_classe; ?>"><br>
          Conteúdo do Reposit&oacute;rio:</td>
      </tr>
      <tr> 
        <td colspan="5" height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td class="destaque" align="center" colspan="3" valign="middle"><input name="update_subredes" id="update_subredes" type="checkbox" onClick="MarcaDesmarcaTodos(this.form.update_subredes),MarcaIncondicional(this.form.update_subredes,'update_subredes_versoes_agentes.ini'),MarcaIncondicional(this.form.update_subredes,'force_update_subredes_versoes_agentes.ini');">  
          &nbsp;&nbsp;Marca/Desmarca todos os objetos
		  </td>
      </tr>
      <tr> 
        <td nowrap colspan="2">&nbsp;</td>
      </tr>
      <tr> 
        <td nowrap colspan="2"><table border="1" align="center" cellpadding="2" bordercolor="#999999">
            <tr bgcolor="#FFFFCC"> 
              <td bgcolor="#EBEBEB" align="center"><img src="../../imgs/checked.gif" border="no"></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela">Arquivo</td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela">Tamanho(Kb)</td>
              <td align="center" colspan="3" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela">Vers&atilde;o</td>
			  <td align="center" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela">For&ccedil;ar</td>			  
            </tr>
            <? 
	  if ($handle = opendir('../../repositorio')) 
		{
		$v_nomes_arquivos = array();		
		while (false !== ($v_arquivo = readdir($handle))) 
			{
			if (substr($v_arquivo,0,1) != "." and 
			    $v_arquivo != "netlogon" and 
				$v_arquivo != "supergerentes" and 
				$v_arquivo != "vaca.exe") // Versoes Agentes Creator/Atualizator //and 
				//$v_arquivo != "versoes_agentes.ini") 		
				{
				// Armazeno o nome do arquivo
				array_push($v_nomes_arquivos, $v_arquivo);
				}
			}

		if (file_exists('../../repositorio/versoes_agentes.ini'))
			{
			$v_array_versoes_agentes = parse_ini_file('../../repositorio/versoes_agentes.ini');
			}
					
		sort($v_nomes_arquivos);
		for ($cnt_arquivos = 0; $cnt_arquivos < count($v_nomes_arquivos); $cnt_arquivos++)
			{
			$v_dados_arquivo = lstat('../../repositorio/'.$v_nomes_arquivos[$cnt_arquivos]);
			echo '<tr>';
			echo '<td><input name="update_subredes_'.$v_nomes_arquivos[$cnt_arquivos].'" id="update_subredes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="'.$v_nomes_arquivos[$cnt_arquivos].'"';
			if ($v_nomes_arquivos[$cnt_arquivos] == 'versoes_agentes.ini') echo ' checked disabled'; // Implementar o OnChange para impedir o Marca/Desmarca todos para este campo...
			echo ' ></td>';
			echo '<td>'.$v_nomes_arquivos[$cnt_arquivos].'</td>';										
			echo '<td align="right">'.number_format(($v_dados_arquivo[7]/1024), 1, '', '.').'</td>';			
			if (isset($v_array_versoes_agentes) && $versao_agente = $v_array_versoes_agentes[$v_nomes_arquivos[$cnt_arquivos]])
				{
				echo '<td align="center" colspan="3">'.$versao_agente.'</td>';																
				}
			else
				{
				echo '<td align="center" colspan="3">'.strftime("%d/%m/%Y  %H:%Mh", $v_dados_arquivo[9]).'</td>';							
				}
			echo '<td align="center"><input name="force_update_subredes_'.$v_nomes_arquivos[$cnt_arquivos].'" id="force_update_subredes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="'.$v_nomes_arquivos[$cnt_arquivos].'"';
			if ($v_nomes_arquivos[$cnt_arquivos] == 'versoes_agentes.ini') echo ' checked disabled';
			echo '></td></tr>';											
			}
		}
	 ?>
          </table></td>

      </tr>
    </table>
    <br>
  </div>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
		<?
		$v_classe = "label";
		?>
		<tr> 
		  <td nowrap align="center" colspan="3" class="<? echo $v_classe; ?>"><br>
        SubRedes Cadastradas:</td>
		</tr>
		<tr> 
		  <td colspan="3" height="1" bgcolor="#333333"></td>
		</tr>
			  <tr> 
				<td class="destaque" align="center" colspan="3" valign="middle"><input name="redes" type="checkbox" id="redes" onClick="MarcaDesmarcaTodos(this.form.redes);">
        &nbsp;&nbsp;Marca/Desmarca todas as SubRedes</td>
			  </tr>
			  <tr><td height="10">&nbsp;</td></tr>
		
		<tr> 
		  <td nowrap colspan="3"><table border="1" align="center" cellpadding="2" bordercolor="#999999">
			  <tr bgcolor="#FFFFCC"> 
				<td bgcolor="#EBEBEB" align="center"><img src="../../imgs/checked.gif" border="no"></td>
				<td bgcolor="#EBEBEB" class="cabecalho_tabela">IP</td>
				
            <td bgcolor="#EBEBEB" class="cabecalho_tabela">Nome da Subrede</td>			
				<td bgcolor="#EBEBEB" class="cabecalho_tabela">Localização</td>							
			  </tr>
			  
			  <? 
	   	 	  $where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND loc.id_local = '.$_SESSION['id_local']:'');
			  $query = "	SELECT 		re.id_ip_rede,
										re.nm_rede,
										loc.id_local,
										loc.sg_local
							FROM 		redes re,
										locais loc
							WHERE		re.id_local = loc.id_local ".
										$where ."
							GROUP BY    re.id_ip_rede
							ORDER BY	loc.sg_local,
										re.nm_rede"; 			
				Conecta_bd_cacic();
				$result_redes = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela de redes.'); 										
		while ($row = mysql_fetch_array($result_redes))
			{
			echo '<tr>';
			echo '<td><input name="redes_'.$row['id_ip_rede'].'" id="redes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="'.$row['id_ip_rede'].'"></td>';
			echo '<td>'.$row['id_ip_rede'].'</td>';						
			echo '<td>'.$row['nm_rede'].'</td>';									
			echo '<td>'.$row['sg_local'].'</td>';												
			echo '</tr>';							
			}
	?> 
	</table></td>
	</tr>
	</table>        
	<p align="center">
	<br>
	<input name="ExecutaUpdates" type="submit" id="ExecutaUpdates" value="Executar Updates"  onClick="return Confirma('Confirma Verificação/Atualização de SubRedes?');" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
	</p>
	</form>		  			
	</body>
	</html>
	<?
	}
	?>
