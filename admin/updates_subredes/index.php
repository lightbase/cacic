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

AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

if (isset($_POST['ExecutaUpdates']))
	{				
	// Enviarei também ao updates_subredes.php uma relação de agentes e versões para inserção na tabela redes_versoes_modulos, no caso da ocorrência de Servidor de Updates verificado anteriormente.
	// Exemplo de estrutura de agentes_versoes: col_soft.exe#22010103*col_undi.exe#22010103
	// 						   agentes_hashs:   col_soft.exe#4228204d66e268ad42d9d738a09800e8*col_undi.exe#2428204d67e268ad42d9d738a09800ff	
	$v_agentes_versoes = '';
	$v_agentes_hashs   = '';	
	foreach($HTTP_POST_VARS as $i => $v) 
		{
		//echo 'v: '.$v.'   i: '.$i.'<br>';
		if ($v && substr($i,0,7)=='update_' && $v <> 'on')
			{
			// O envio de versoes_agentes.ini deve ser incondicional!
			if ($v_updates == '') $v_updates = 'versoes_agentes.ini';
			
			if ($v_updates <> '') $v_updates .= '__';
			$v_updates .= $v;		
			}
		if ($v && substr($i,0,6)=='redes_' && $v <> 'on')
			{
			if ($v_redes <> '') $v_redes .= '__';			
			$v_redes .= $v;

			if ($v_force_redes <> '') $v_force_redes .= ',';						
			$v_force_redes .= '_fr_'.$v.'_fr_';
			}			

		// Verifico se a versão foi FORÇADA ao update.
		if ($v && substr($i,0,6)=='force_' && $v <> 'on')
			{
			// O envio de versoes_agentes.ini deve ser incondicional!
			if ($v_force_modulos == '') $v_force_modulos = '_fm_versoes_agentes.ini_fm_';
			
			if ($v_force_modulos <> '') 
				$v_force_modulos .= ",";			

			$v_force_modulos .= '_fm_'.$v.'_fm_';		
			}								

		if ($v && substr($i,0,15)=='agentes_versoes')
			$v_agentes_versoes = '_-_'.$v;

		if ($v && substr($i,0,13)=='agentes_hashs')
			$v_agentes_hashs = '_-_'.$v;
		}
		
	// O tratamento de v_force_modulos foi transferido para updates_subredes.php

	$v_parametros = urlencode($v_updates.'_-_'.$v_redes.'_-_'.$v_force_modulos.$v_agentes_versoes.$v_agentes_hashs);

	// O script updates_subredes.php espera receber o parâmetro v_parametros contendo uma string com a seguinte formação:
	// objeto1__objeto2__objetoN_-_rede1__rede2__rede3__redeN  
	// Onde: __  = Separador de itens
	//       _-_ = Separador de Matrizes		
    header ("Location: updates_subredes.php?v_parametros=".$v_parametros);			
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
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
function verificar()
	{
	var formRedes = window.document.forma;
	var boolModulos = false;
	var boolRedes   = false;
	var strFraseErro = '';

	for (j=0;j<formRedes.elements.length;j++)
		if (formRedes.elements[j].type == 'checkbox' && (formRedes.elements[j].name).substring(0,16) == 'update_subredes_')
			{
			if (formRedes[j].checked && formRedes.elements[j].value != 'versoes_agentes.ini')
				{
				boolModulos = true;
				j = formRedes.elements.length;
				}
			}

	for (j=0;j<formRedes.elements.length;j++)
		if (formRedes.elements[j].type == 'checkbox' && formRedes.elements[j].id == 'redes')
			{
			if (formRedes[j].checked)
				{
				boolRedes = true;
				j = formRedes.elements.length;				
				}
			}

	if (boolModulos && boolRedes)
		return true;
	else
		{
		if (!boolModulos)
			strFraseErro = 'Módulos';

		if (!boolRedes)
			strFraseErro = (!boolModulos?' e ':'') + 'SubRedes';

		alert('ATENÇÃO: Verifique as seleções de '+strFraseErro);	
//		formRedes.elements[min(intInicioModulos,intInicioRedes)].focus();		
		}
	return false;
	}
</script>
	</head>
	
	<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>					
	<table width="90%" border="0" align="center">
	  <tr> 
		<td class="cabecalho"><?=$oTranslator->_('Atualizacoes de subredes');?></td>
	  </tr>
	  <tr> 
		
    <td class="descricao"><?=$oTranslator->_('Atualizacoes de subredes - texto de ajuda');?></td>
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
	function MarcaDesmarcaTodoEsseLocal(strIdLocal) 
		{
		var Formulario = window.document.forms[0];
		var arrRede;
		for (i = 0; i < Formulario.length; i++) 
			if (Formulario[i].type == 'checkbox' && (Formulario[i].name).substring(0,6) == 'redes_')
				{
				arrRede = (Formulario[i].name).split('_');
				if (strIdLocal == arrRede[2])
					if (Formulario[i].checked)
						Formulario[i].checked = false;			
					else
						Formulario[i].checked = true;								
				}

		return true;
		}

	function MarcaDesmarcaTodaLegenda(strCor) 
		{
		var Formulario = window.document.forms[0];
		var arrRede;
		for (i = 0; i < Formulario.length; i++) 
			if (Formulario[i].type == 'checkbox' && (Formulario[i].name).substring(0,6) == 'redes_')
				{
				arrRede = (Formulario[i].name).split('_');
				if (strCor == arrRede[3])
					if (Formulario[i].checked)
						Formulario[i].checked = false;			
					else
						Formulario[i].checked = true;								
				}

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
          <?=$oTranslator->_('Conteudo do repositorio');?></td>
      </tr>
      <tr> 
        <td colspan="5" height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td class="destaque" align="center" colspan="3" valign="middle">
           <input name="update_subredes" id="update_subredes" type="checkbox" onClick="MarcaDesmarcaTodos(this.form.update_subredes),MarcaIncondicional(this.form.update_subredes,'update_subredes_versoes_agentes.ini'),MarcaIncondicional(this.form.update_subredes,'force_update_subredes_versoes_agentes.ini');">  
          <?=$oTranslator->_('Marca/desmarca todos os objetos');?>
        </td>
      </tr>
      <tr>
        <td nowrap colspan="2">&nbsp;</td>
      </tr>
      <tr> 
        <td nowrap colspan="2">&nbsp;</td>
      </tr>
      <tr> 
        <td nowrap colspan="2"><table border="1" align="center" cellpadding="2" bordercolor="#999999">
            <tr bgcolor="#FFFFCC"> 
              <td colspan="8" class="cabecalho_tabela" align="center"><b><?=$oTranslator->_('Agentes para MS-Windows');?></b></td>			  
            </tr>
		
            <tr bgcolor="#FFFFCC"> 
              <td bgcolor="#EBEBEB" align="center"><img src="../../imgs/checked.gif" border="no"></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Arquivo');?></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Tamanho(KB)');?></td>
              <td align="center" colspan="3" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Versao');?></td>
              <td align="center" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Hash');?></td>
			  <td align="center" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Forcar');?></td>			  
            </tr>
            <? 

	  if ($handle = opendir('../../repositorio')) 
		{
		$v_nomes_arquivos = array();	

		while (false !== ($v_arquivo = readdir($handle))) 
			{
			$v_arquivo = strtolower($v_arquivo);
			if (substr($v_arquivo,0,1) != "." and 
			    $v_arquivo != "agentes_linux" and 
			    $v_arquivo != "netlogon" and 				
				$v_arquivo != "supergerentes" and 
				$v_arquivo != "install" and 								
				$v_arquivo != "chkcacic.exe" and
				$v_arquivo != "chkcacic.ini" and				
				$v_arquivo != "vaca.exe") // Versoes Agentes Creator/Atualizator //and 				
				//$v_arquivo != "versoes_agentes.ini") 		
				{
				// Armazeno o nome do arquivo
				array_push($v_nomes_arquivos, $v_arquivo);
				}
			}

		if (file_exists('../../repositorio/versoes_agentes.ini'))
			$v_array_versoes_agentes = parse_ini_file('../../repositorio/versoes_agentes.ini');

		sort($v_nomes_arquivos,SORT_STRING);
		$v_agentes_versoes 	= ''; // Conterá as versões dos agentes para tratamento em updates_subredes.php
		$v_agentes_hashs 	= ''; // Conterá os hashies referentes aos agentes
		for ($cnt_arquivos = 0; $cnt_arquivos < count($v_nomes_arquivos); $cnt_arquivos++)
			{
			$te_hash = hash_file('md5','../../repositorio/'.$v_nomes_arquivos[$cnt_arquivos]);
			$v_dados_arquivo = lstat('../../repositorio/'.$v_nomes_arquivos[$cnt_arquivos]);
				
			echo '<tr>';
			echo '<td><input name="update_subredes_'.$v_nomes_arquivos[$cnt_arquivos].'" id="update_subredes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="'.$v_nomes_arquivos[$cnt_arquivos].'"';
			if ($v_nomes_arquivos[$cnt_arquivos] == 'versoes_agentes.ini') echo ' checked disabled'; // Implementar o OnChange para impedir o Marca/Desmarca todos para este campo...
			echo ' >';
			echo '</td>';
			echo '<td>'.$v_nomes_arquivos[$cnt_arquivos].'</td>';										

			// Adequação ao resultado no Debian Etch
			echo '<td align="right">'.number_format(($v_dados_arquivo[7]/10240), 1, '', '.').'</td>';						
			if (isset($v_array_versoes_agentes) && $versao_agente = $v_array_versoes_agentes[$v_nomes_arquivos[$cnt_arquivos]])
				{
				echo '<td align="center" colspan="3">'.$versao_agente.'</td>';																
				}
			else
				{
				$versao_agente = strftime("%d/%m/%Y  %H:%Mh", $v_dados_arquivo[9]);
				echo '<td align="center" colspan="3">'.$versao_agente.'</td>';							
				}

			$v_agentes_versoes .= ($v_agentes_versoes<>''?'#':'');			
			$v_agentes_versoes .= $v_nomes_arquivos[$cnt_arquivos].'*'.$versao_agente;				

			$v_agentes_hashs   .= ($v_agentes_hashs <>''?'#':'');			
			$v_agentes_hashs   .= $v_nomes_arquivos[$cnt_arquivos].'*'.$te_hash;	
			
			echo '<td align="center">'.$te_hash.'</td>';
						
			echo '<td align="center"><input name="force_update_subredes_'.$v_nomes_arquivos[$cnt_arquivos].'" id="force_update_subredes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="'.$v_nomes_arquivos[$cnt_arquivos].'"';
			if ($v_nomes_arquivos[$cnt_arquivos] == 'versoes_agentes.ini') echo ' checked disabled';
			echo '>';
			echo '</td></tr>';											
			}
		}

	  if ($handle = opendir('../../repositorio/agentes_linux')) 		
	  	{
	 	?>
		<tr><td colspan="8">&nbsp;</td></tr>
           <tr bgcolor="#FFFFCC"> 
              <td colspan="8" class="cabecalho_tabela" align="center"><b><?=$oTranslator->_('Agentes para GNU/Linux');?></b></td>			  
            </tr>
            <tr bgcolor="#FFFFCC"> 
              <td bgcolor="#EBEBEB" align="center"><img src="../../imgs/checked.gif" border="no"></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Arquivo');?></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Tamanho(KB)');?></td>
              <td align="center" colspan="3" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Versao');?></td>
              <td align="center" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Hash');?></td>
			  <td align="center" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Forcar');?></td>			  
            </tr>
	 <?
		$v_nomes_arquivos = array();	

		while (false !== ($v_arquivo = readdir($handle))) 
			if (substr($v_arquivo,0,1) != ".")
				array_push($v_nomes_arquivos, $v_arquivo);	// Armazeno o nome do arquivo

		for ($cnt_arquivos = 0; $cnt_arquivos < count($v_nomes_arquivos); $cnt_arquivos++)
			{
			$te_hash = hash_file('md5','../../repositorio/agentes_linux/'.$v_nomes_arquivos[$cnt_arquivos]);			
			$v_dados_arquivo = lstat('../../repositorio/'.$v_nomes_arquivos[$cnt_arquivos]);

			$arrNomeArquivo = explode('.tgz',$v_nomes_arquivos[$cnt_arquivos]);
			$arrNomeArquivo = explode('_',$arrNomeArquivo[0]);
			
			echo '<tr>';
			echo '<td><input name="update_subredes_'.$v_nomes_arquivos[$cnt_arquivos].'" id="update_subredes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="'.$v_nomes_arquivos[$cnt_arquivos].'"></td>';
			echo '<td>'.$arrNomeArquivo[0].'</td>';										

			// Adequação ao resultado no Debian Etch
			echo '<td align="right">'.number_format(($v_dados_arquivo[7]/10240), 1, '', '.').'</td>';						
			
			echo '<td align="center" colspan="3">'.$arrNomeArquivo[1].'</td>';																
			echo '<td align="center">'.$te_hash.'</td>';

			echo '<td align="center"><input name="force_update_subredes_'.$v_nomes_arquivos[$cnt_arquivos].'" id="force_update_subredes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="'.$v_nomes_arquivos[$cnt_arquivos].'"></td></tr>';											
			$v_agentes_versoes 	.= ($v_agentes_versoes<>''?'#':'');
			$v_agentes_versoes 	.= $v_nomes_arquivos[$cnt_arquivos].'*'.$arrNomeArquivo[1];	

			$v_agentes_hashs 	.= ($v_agentes_hashs<>''?'#':'');
			$v_agentes_hashs	.= $v_nomes_arquivos[$cnt_arquivos].'*'.$te_hash;				
			}

		}
	echo '<input name="agentes_versoes" id="agentes_versoes" type="hidden" value="'.$v_agentes_versoes.'">';		
	echo '<input name="agentes_hashs"   id="agentes_hashs"   type="hidden" value="'.$v_agentes_hashs.'">';			
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
		  <td nowrap align="center" colspan="4" class="<? echo $v_classe; ?>"><br>
             <?=$oTranslator->_('SubRedes Cadastradas');?></td>
		</tr>
		<tr> 
		  <td colspan="4" height="1" bgcolor="#333333"></td>
		</tr>
			  <tr> 
				<td class="destaque" align="center" colspan="4" valign="middle"><input name="redes" type="checkbox" id="redes" onClick="MarcaDesmarcaTodos(this.form.redes);">
				  <?=$oTranslator->_('Marcar/desmarcar todas as subRedes');?></td>
			  </tr>
			  <tr>
			    <td height="10" colspan="2">&nbsp;</td>
    </tr>
			  <tr>
			    <td height="10" colspan="2"></td>
    </tr>
			  <tr>
			    <td height="10" colspan="2"><table width="200" border="1" align="center">
                  <tr>
                    <td height="10" colspan="2" nowrap><div align="center"><strong><?=$oTranslator->_('Legenda para as SubRedes');?></strong></div></td>
                  </tr>
                  <tr>
                    <td height="10" nowrap bordercolor="#000000" class="td_amarelo"><a style="cursor: pointer"><div align="center" onClick="MarcaDesmarcaTodaLegenda('amarelo');" title="Clique para Marcar/Desmarcar as Redes Nesta Situação" ><?=$oTranslator->_('Amarelo');?></div></a></td>
					
                    <td align="left" valign="middle" nowrap><?=$oTranslator->_('Existencia de modulo com versao diferente');?></td>
                  </tr>
                  <tr>
                    <td height="10" nowrap bordercolor="#000000" class="td_laranja"><a style="cursor: pointer"><div align="center" onClick="MarcaDesmarcaTodaLegenda('laranja');" title="Clique para Marcar/Desmarcar as Redes Nesta Situação"><?=$oTranslator->_('Laranja');?></td>					
                    <td align="left" valign="middle" nowrap><span class="opcao_tabela"><?=$oTranslator->_('Inexistencia parcial de modulos');?></span></td>
                  </tr>
                  <tr>				  					  
                    <td height="10" nowrap bordercolor="#000000" class="td_vermelho"><a style="cursor: pointer"><div align="center" onClick="MarcaDesmarcaTodaLegenda('vermelho');" title="Clique para Marcar/Desmarcar as Redes Nesta Situação"><?=$oTranslator->_('Vermelho');?></td>
                    <td align="left" valign="middle" nowrap><span class="opcao_tabela"><?=$oTranslator->_('Inexistencia total de modulos');?></span></td>
                  </tr>
                </table>
		          <div align="center">
		            <small><?=$oTranslator->_('Dica: Clique nas Cores da legenda para marcar/desmarcar subredes em bloco');?></small>
		          </div></td>
    </tr>
			  

		<tr> 
		  <td nowrap colspan="4"><br>
		  <table border="1" align="center" cellpadding="2" bordercolor="#999999">
		    <tr bgcolor="#FFFFCC"> 
		      <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Sequencia');?></td>			
				  <td bgcolor="#EBEBEB" align="center"><img src="../../imgs/checked.gif" border="no"></td>				
				  <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Endereco IP');?></td>
				  
            <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Nome da Subrede');?></td>			
				  <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Servidor de atualizacoes');?></td>							
				  <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Caminho (path) FTP');?></td>											
				  <td colspan="2" bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Localizacao');?></td>											
	        </tr>
		    
		    <? 
	   	 	  $where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND loc.id_local = '.$_SESSION['id_local']:'');
				if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
					{
					// Faço uma inserção de "(" para ajuste da lógica para consulta	
					$where = str_replace(' loc.id_local = ',' (loc.id_local = ',$where);
					$where .= ' OR loc.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
					}

 			  Conecta_bd_cacic();			  
			  
			  $query = "	SELECT 		re.id_ip_rede,
										re.nm_rede,
										loc.id_local,
										loc.sg_local,
										re.te_serv_updates,
										re.te_path_serv_updates
							FROM 		redes re,
										locais loc
							WHERE		re.id_local = loc.id_local ".
										$where ."
							GROUP BY    re.id_ip_rede
							ORDER BY	loc.sg_local,
										re.nm_rede"; 
// ********************
			  	$queryALERTA = "	SELECT 		re.id_ip_rede,
												rvm.nm_modulo,
												rvm.te_versao_modulo,
												rvm.cs_tipo_so
									FROM 		redes re,
												redes_versoes_modulos rvm,
												locais loc
									WHERE		re.id_local = rvm.id_local and
							            		re.id_ip_rede = rvm.id_ip_rede ".
												$where ." and loc.id_local = re.id_local
									ORDER BY	re.id_ip_rede,
							            		rvm.nm_modulo"; 
			$resultALERTA = mysql_query($queryALERTA) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!',array('redes_versoes_modulos'))); 																
			$strTripaAmarelo = '#'; // Conterá os IPS das redes cujas versões de módulos divergirem das existentes no repositório
			$strTripaLaranja = '#'; // Conterá os IPS das redes cuja quantidade de módulos seja diferente do total de módulos disponíveis
			$strTripaRedes   = '#'; // Conterá os IPS de todas as redes, para verificação de ausência total de módulos			
			$intFrequenciaRede = 0; // Acumulará a frequência de cada rede e deverá ser igual ao tamanho de versoes_agentes!
			$strRedeAtual = '';

			$intTotalAgentes = 0; // Contarei no arquivo versoes_agentes.ini os nomes com a string "_HASH"

			$lines = file ('../../repositorio/versoes_agentes.ini');

			// Percorre o array, mostrando o fonte HTML com numeração de linhas.
			foreach ($lines as $line_num => $line) 
				{
				$boolHASH = stripos2($line,'_HASH',false);		
				$intTotalAgentes += ($boolHASH?1:0);		
				}

			while ($rowALERTA = mysql_fetch_array($resultALERTA))
				{
				$boolAgenteLinux = stripos2($rowALERTA['nm_modulo'],'PyCACIC',false);
				$str_nm_modulo 	 = ($boolAgenteLinux?'PyCACIC':$rowALERTA['nm_modulo']);
				
				if ($str_nm_modulo <> 'chkcacic.exe' &&
				    $str_nm_modulo <> 'chkcacic.ini' &&
					$str_nm_modulo <> 'versoes_agentes.ini' &&
					$str_nm_modulo <> 'vaca.exe' &&					
					$str_nm_modulo <> 'install' &&										
					$str_nm_modulo <> 'agentes_linux' &&															
					$str_nm_modulo <> '' &&															
					isset($v_array_versoes_agentes) && $versao_agente = $v_array_versoes_agentes[$str_nm_modulo])
					{
				
					if ($strRedeAtual <> '' && $strRedeAtual <> $rowALERTA['id_ip_rede'])
						{
						if ($intFrequenciaRede <> $intTotalAgentes)
							$strTripaLaranja .= $strRedeAtual . '#';

						$intFrequenciaRede = 1;
						}
					else
						$intFrequenciaRede ++;
	
					$strRedeAtual = $rowALERTA['id_ip_rede'];					
					
					if ($rowALERTA['cs_tipo_so'] <> 'GNU/LINUX')
						$versao_agente = str_replace('.','',$versao_agente) . '0103';
					else
						$versao_agente = str_replace('.','',$versao_agente);					

					if ($versao_agente <> $rowALERTA['te_versao_modulo'])
						{
						
						$strPesquisaRede = '#'.$strRedeAtual.'#';
						$intPos = stripos2($strTripaAmarelo,$strPesquisaRede);
						if ($intPos === false)
							$strTripaAmarelo .= $strRedeAtual.'#';
						}
					}
				$strPesquisaRede = '#'.$strRedeAtual.'#';
				$intPos = stripos2($strTripaRedes,$strPesquisaRede);
				if ($intPos === false)
					$strTripaRedes .= $strRedeAtual.'#';
					
				}

// ********************	
									
		$result_redes = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!',array('redes'))); 										
		$intSequencial = 1;
		while ($row = mysql_fetch_array($result_redes))
			{
			$strCheck = '';
			$strPesquisaRede = '#'.$row['id_ip_rede'].'#';			
			
			$intPosVermelho  = stripos2($strTripaRedes,$strPesquisaRede);

			if (!$intPosVermelho === false)
				$strClasseTD = 'normal';			
			else
				{
				$strClasseTD = 'td_vermelho';
				$strCheck    = 'checked';
				}

			if ($strCheck == '')
				{
				$intPosLaranja = stripos2($strTripaLaranja,$strPesquisaRede);
				if ($intPosLaranja === false)
					$strClasseTD = 'normal';			
				else
					{
					$strClasseTD = 'td_laranja';
					$strCheck    = 'checked';
					}
				}
			
			if ($strCheck == '')
				{
				$intPosAmarelo = stripos2($strTripaAmarelo,$strPesquisaRede);
				if ($intPosAmarelo === false)
					$strClasseTD = 'normal';			
				else
					{
					$strClasseTD = 'td_amarelo';
					$strCheck    = 'checked';
					}
				}


			
			?>
		    <tr>
		      <td class="<? echo $strClasseTD;?>" align="right"><? echo $intSequencial;?></td>									
			  <td class="<? echo $strClasseTD;?>"><input name="redes_<? echo $row['id_ip_rede'].'_'.$row['id_local'].'_'.str_replace('td_','',$strClasseTD);?>" id="redes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="<? echo $row['id_ip_rede'];?>" <? echo $strCheck;?>></td>
			  <td class="<? echo $strClasseTD;?>"><? echo $row['id_ip_rede'];?></td>
			  <td class="<? echo $strClasseTD;?>"><? echo $row['nm_rede'];?></td>
			  <td class="<? echo $strClasseTD;?>"><? echo $row['te_serv_updates'];?></td>
			  <td class="<? echo $strClasseTD;?>"><? echo $row['te_path_serv_updates'];?></td>
			  <td class="<? echo $strClasseTD;?>" nowrap="nowrap" 
			<? 
			if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
				{
				?>
				colspan="2"
				<?
				}
				?>
			><? echo $row['sg_local'];?></td>
			  <? 
			if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
				{			
				?>
		      <td class="<? echo $strClasseTD;?>" nowrap="nowrap"><img src="../../imgs/checked.gif" border="no"  onClick="MarcaDesmarcaTodoEsseLocal('<? echo $row['id_local']; ?>');"></td>
				  <?
				}
				?>
	        </tr>
		    <?
			$intSequencial ++;							
			}
	?> 
	      </table></td></tr>
	</table>        
	<p align="center">
	<input name="ExecutaUpdates" type="submit" id="ExecutaUpdates" value="<?=$oTranslator->_('Executar atualizacoes');?>"
	       onClick="return (verificar() && Confirma('<?=$oTranslator->_('Confirma verificacao/atualizacao de subredes?');?>'));"
	       <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
	</p>
	</form>		  			
	</body>
	</html>
	<?
	}
	?>
