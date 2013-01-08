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
?>		
<table width="85%" border="0" align="center">
  <tr><td class="cabecalho">&nbsp;</td></tr>  
  <tr><td><input name="frmRedes_Selecionadas" type="hidden" id="frmRedes_Selecionadas" value="">  
        <input name="frmRedes_NaoSelecionadas" type="hidden" id="frmRedes_NaoSelecionadas" value="">                
		<tr><td class="destaque" align="center" colspan="4" valign="middle"><input name="redes" type="checkbox" id="redes" onClick="MarcaDesmarcaTodos(this.form.redes);">
				  <?php echo $oTranslator->_('Marcar/desmarcar todas as subRedes');?></td>
		</tr>
		<tr><td height="10" colspan="2">&nbsp;</td></tr>
		<tr> 
		<td nowrap colspan="4"><br>
		<table border="0" align="center" cellpadding="0" bordercolor="#999999">
		<tr bgcolor="#FFFFCC"> 
		<td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Sequencia');?></td>			
		<td bgcolor="#EBEBEB" align="center"><img src="../../imgs/checked.gif" border="no"></td>				
		<td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Endereco IP');?></td>				  
        <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Nome da Subrede');?></td>			
		<td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Localizacao');?></td>											
	    </tr>		    
		<?php 
	   	$whereREDES = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND loc.id_local = '.$_SESSION['id_local']:'');
		if ($_SESSION['te_locais_secundarios']<>'' && $whereREDES <> '')
			{
			// Faço uma inserção de "(" para ajuste da lógica para consulta	
			$whereREDES = str_replace(' loc.id_local = ',' (loc.id_local = ',$whereREDES);
			$whereREDES .= ' OR loc.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
			}
			
			
		$queryREDES = "	SELECT 		re.te_ip_rede,
									re.nm_rede,
									loc.id_local,
									loc.sg_local,
									re.id_rede
						FROM 		redes re,
									locais loc
						WHERE		re.id_local = loc.id_local ".
									$whereREDES ."
						ORDER BY	loc.sg_local,
									re.te_ip_rede,
									re.nm_rede"; 
									
		$resultREDES = mysql_query($queryREDES) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!',array('redes'))); 										

		$intSequencial = 1;

		while ($rowREDES = mysql_fetch_array($resultREDES))
			{
			$strIdRedes       .= ($strIdRedes ? ',' : '');			
			$strIdRedes       .=  $rowREDES['id_rede'];
			
			$strCheck 	 = '';
			$strClasseTD = 'td_normal';
							
			$strCheck = ($arrSelecaoRedes[$rowREDES['id_rede']] ? 'checked' : '');
			?>
		    <tr>
		      <td class="<?php echo $strClasseTD;?>" align="right"><?php echo $intSequencial;?></td>									
			  <td class="<?php echo $strClasseTD;?>" align="center"><input name="rede_<?php echo $rowREDES['id_rede'].'_'.str_replace('td_','',$strClasseTD);?>" id="redes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="<?php echo $rowREDES['id_rede'];?>" <?php echo $strCheck;?>></td>
			  <td class="<?php echo $strClasseTD;?>"><?php echo $rowREDES['te_ip_rede'];?></td>
			  <td class="<?php echo $strClasseTD;?>" nowrap="nowrap"><?php echo $rowREDES['nm_rede'];?></td>
			  <td class="<?php echo $strClasseTD;?>" nowrap="nowrap"><?php echo $rowREDES['sg_local'];?></td>
	        </tr>
		    <?php
			$intSequencial ++;							
			}
	?> 
	    <tr><td colspan="5">&nbsp;</td></tr>
	    <tr><td colspan="5">&nbsp;</td></tr>
        </table>

