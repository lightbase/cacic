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
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../include/library.php');

AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
<title><?php echo $oTranslator->_('Acoes - Configuracoes');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
function Volta() 
	{
	top.location = '../arquivos/index.php';
	}


function PreencheFormListas(comboLista)
	{
	var strValues;
	strValues = "";
  	for (var i=0;i < comboLista.length;i++) 
		{
		strValues = strValues + (strValues != "" ? "," : "");			
		strValues = strValues + comboLista.options[i].value;
   		}	
	return strValues;
	}
	
function valida_form() 
	{
	// Os procedimentos abaixo foram implementados como solução de contorno às limitações impostas pela
	// extensão Suhosin	em um servidor de produção cujas alterações levavam burocraticamente até 5 dias!!!
	PreencheFormChecksRedes();	
	document.forma.elements['frmSO_NaoSelecionados'].value = PreencheFormListas(document.forma.elements['list3[]']);
	document.forma.elements['frmSO_Selecionados'].value    = PreencheFormListas(document.forma.elements['list4[]']);	
	document.forma.elements['frmMAC_Selecionados'].value   = PreencheFormListas(document.forma.elements['list5[]']);

	// Desabilito as listas para que não sejam enviadas no POST
	document.forma.elements['list3[]'].disabled=true;
	document.forma.elements['list4[]'].disabled=true;	
	document.forma.elements['list5[]'].disabled=true;
	document.forma.elements['list6[]'].disabled=true;	
	
	return true;
	}

function SelectAll(combo) 
	{
  	for (var i=0;i<combo.options.length;i++) 
    	combo.options[i].selected=true;
	}

function DesSelectAll(combo) 
	{
  	for (var i=0;i<combo.options.length;i++) 
    	combo.options[i].selected=false;
	}

function move(fbox,tbox) 
	{
	for(var i=0; i<fbox.options.length; i++) 
		{
		if(fbox.options[i].selected && fbox.options[i].value != "") 
			{
			var no = new Option();
			no.value = fbox.options[i].value;
			no.id = fbox.options[i].id;				
			no.text = fbox.options[i].text;
			tbox.options[tbox.options.length] = no;
			fbox.options[i].value = "";
			fbox.options[i].id = "";				
			fbox.options[i].text = "";
		   }
		}
    BumpUp(fbox);
	}

function BumpUp(box)  
	{
	for(var i=0; i<box.options.length; i++) 
		{
	   	if(box.options[i].value == "")  
			{
			for(var j=i; j<box.options.length-1; j++)  
				{
				box.options[j].value = box.options[j+1].value;
				box.options[j].id = box.options[j+1].id;				
				box.options[j].text = box.options[j+1].text;
				}
			var ln = i;
			break;
	   		}
		}

	if(ln < box.options.length)  
		{
		box.options.length -= 1;
		BumpUp(box);
    	}
	}

function Add(fbox,tbox) 
	{
	var i = 0;
	if(fbox.value != "") 
		{
		var no = new Option();
		no.value = fbox.value;
		no.id = fbox.id;		
		no.text = fbox.value;
		tbox.options[tbox.options.length] = no;
		fbox.value = "";
    	}
	}

function remove(box) 
	{
	for(var i=0; i<box.options.length; i++) 
		{
		if(box.options[i].selected && box.options[i] != "") 
			{
			box.options[i].value = "";
			box.options[i].id = "";			
			box.options[i].text = "";
	    	}
		}
	BumpUp(box);
	} 
</script>
</head>

<?php 
    conecta_bd_cacic();
	$queryAcaoRedes = "SELECT 	re.id_rede
					  FROM 		acoes_redes ar,
					  			redes re
					  WHERE		id_acao = '".$_GET['id_acao']."' AND 
					  			ar.id_rede = re.id_rede";
	if ($_SESSION['cs_nivel_administracao'] <> 1 && $_SESSION['cs_nivel_administracao'] <> 2)			 
		$queryAcaoRedes .= " AND re.id_local = ".$_SESSION['id_local'];

	if ($_SESSION['te_locais_secundarios']<>'')
		{
		// Faço uma inserção de "(" para ajuste da lógica para consulta
		$queryAcaoRedes = str_replace('re.id_local = ','(re.id_local = ',$queryAcaoRedes);
		$queryAcaoRedes .= ' OR re.id_local IN ('.$_SESSION['te_locais_secundarios'].'))';	
		}

	$resultAcaoRedes= mysql_query($queryAcaoRedes) or die('1-'.$oTranslator->_('kciq_msg select on table fail', array('acoes_redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
	$arrSelecaoRedes = array();	
	while ($rowAcaoRedes = mysql_fetch_array($resultAcaoRedes))
		$arrSelecaoRedes[$rowAcaoRedes['id_rede']] = 1;		
?>

<body background="../imgs/linha_v.gif">
<?php require_once('../include/js/opcoes_avancadas_combos.js');  ?>
<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>
<table width="85%" border="0" align="center">
  <tr><td class="cabecalho"><?php echo $_GET['te_descricao_breve']; ?></td></tr>
  <tr><td class="descricao"><?php echo $_GET['te_descricao']; ?></td></tr>
  <tr><td class="cabecalho">&nbsp;</td></tr>  
  <tr><td><form action="acoes_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form()">
        <tr><td class="label">&nbsp;<br>
        <?php echo $oTranslator->_('Subredes onde a acao sera executada');?>: 
        <input name="id_acao" type="hidden" id="id_acao" value="<?php echo $_GET['id_acao']; ?>">
        <input name="frmRedes_Selecionadas" type="hidden" id="frmRedes_Selecionadas" value="">  
        <input name="frmRedes_NaoSelecionadas" type="hidden" id="frmRedes_NaoSelecionadas" value="">                
        </td>
        </tr>
        <tr><td height="1" bgcolor="#333333"></td></tr>
		<tr><td class="destaque" align="center" colspan="4" valign="middle"><input name="redes" type="checkbox" id="redes" onClick="MarcaDesmarcaTodos(this.form.redes);">
				  <?php echo $oTranslator->_('Marcar/desmarcar todas as subRedes');?></td>
		</tr>
		<tr><td height="10" colspan="2">&nbsp;</td></tr>
		<tr> 
		<td nowrap colspan="4"><br>
		<table border="0" align="center" cellpadding="0" bordercolor="#999999">
		<tr bgcolor="#FFFFCC"> 
		<td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Sequencia');?></td>			
		<td bgcolor="#EBEBEB" align="center"><img src="../imgs/checked.gif" border="no"></td>				
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
        <tr><td class="label" colspan="5"><?php echo $oTranslator->_('Sistemas Operacionais onde essa acao-configuracao devera ser aplicada');?>:</td></tr>
        <tr><td height="1" bgcolor="#333333" colspan="5"></td></tr>
        <tr><td colspan="5">&nbsp;</td>
        <tr><td>&nbsp;</td>
        	<td class="label"><div align="left"><?php echo $oTranslator->_('Disponiveis');?></div></td>
        	<td>&nbsp;</td>
        	<td class="label" nowrap>&nbsp;&nbsp;</td>
        	<td nowrap><?php echo $oTranslator->_('Selecionados');?>:</td>
        </tr>
        <tr><td>&nbsp;</td>
        	<td class="opcao"> <div align="left"> 
        	<?php    
		 	$where = ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?' and locais.id_local = '.$_SESSION['id_local']:'');
			/* Consulto todas os sistemas operacionais que foram previamente selecionadas para a a ação em questão. */ 
			$query = "SELECT 	DISTINCT so.id_so, so.te_desc_so
					  FROM 		acoes_so, so,locais,redes
				  	WHERE 	acoes_so.id_acao='".$_GET['id_acao']."' AND acoes_so.id_rede = redes.id_rede and redes.id_local = locais.id_local ".
								$where ." AND 
								acoes_so.id_so = so.id_so AND
								so.id_so <> 0";
			$result_so_ja_selecionados = mysql_query($query) or die('4-'.$oTranslator->_('kciq_msg select on table fail', array('acoes_so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");

			/* Agora monto os itens do combo de so's selecionados e preparo a string de exclusao (NOT IN) para a proxima consulta. */ 
			$soDisponiveis = '';
			while($campos_so_selecionados = mysql_fetch_array($result_so_ja_selecionados)) 
				{
				if (substr_count($soDisponiveis,'#'.$campos_so_selecionados['id_so'].'#')==0)
					{
					$soDisponiveis .= '#'.$campos_so_selecionados['id_so'].'#';
					$itens_combo_so_selecionados .= '<option value="' . $campos_so_selecionados['id_so'].'">' . capa_string($campos_so_selecionados['te_desc_so'], 35) . '</option>';	
					$not_in_so_ja_selecionados .= "'" . $campos_so_selecionados['id_so'] .  "',";
					}
				}
			$not_in_so_ja_selecionados .= "''";
		
			/* Consulto os so's que não foram previamente selecionadas. */ 
			$query = "SELECT 	DISTINCT id_so, 
								te_desc_so
					  FROM 		so
					  WHERE 	id_so NOT IN ($not_in_so_ja_selecionados) AND
								id_so <> 0
					  ORDER BY  te_desc_so";
			$result_so_nao_selecionados = mysql_query($query) or die('5-'.$oTranslator->_('kciq_msg select on table fail', array('so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
		
			/* Agora monto os itens do combo de so's NÃO selecionadas. */ 
			while($campos_so_nao_selecionados=mysql_fetch_array($result_so_nao_selecionados)) 	
				{
				$itens_combo_so_nao_selecionados = $itens_combo_so_nao_selecionados . '<option value="' . $campos_so_nao_selecionados['id_so']. '">' . capa_string($campos_so_nao_selecionados['te_desc_so'], 35) . '</option>';
				}  ?>
           	<select multiple size="10" name="list3[]" class="normal">
              <?php echo $itens_combo_so_nao_selecionados; ?> 
            </select>
            </div></td>
            <td>&nbsp;</td>
            <td width="40"> <div align="center"> 
                <input type="button" value="   &gt;   " onClick="move(this.form.elements['list3[]'],this.form.elements['list4[]'])" name="B13">
                <br>
                <br>
                <input type="button" value="   &lt;   " onClick="move(this.form.elements['list4[]'],this.form.elements['list3[]'])" name="B23">
              </div></td>
            <td>
    	    <input name="frmSO_NaoSelecionados" type="hidden" id="frmSO_NaoSelecionados" value="">                            
	        <input name="frmSO_Selecionados"    type="hidden" id="frmSO_Selecionados"    value="">  

            <select multiple size="10" name="list4[]"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <?php echo $itens_combo_so_selecionados; ?> </select></td>
          </tr>
          <tr><td colspan="5" class="ajuda">(<?php echo $oTranslator->_('Dica: use SHIFT ou CTRL para selecionar multiplos itens');?>)</td></tr>
          <tr><td colspan="5">&nbsp;</td></tr>
          <tr><td class="label" colspan="5"><?php echo $oTranslator->_('Computadores onde esta acao-configuracao nao devera ser aplicada');?>:</td></tr>
          <tr><td height="1" bgcolor="#333333" colspan="5"></td></tr>
          <tr><td class="ajuda" colspan="5"><?php echo $oTranslator->_('Informe o endereco MAC do computador.');?> (<?php echo $oTranslator->_('Exemplo');?>: 00-10-4B-65-83-C8)</td></tr>
          <tr>
          <td valign="top"> <div align="left"> 
          <input name="list6" type="text" size="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          </font></div></td>
          <td>&nbsp;</td>
          <td width="40" valign="top"> <div align="center"> &nbsp; 
          <input type="button" value="   +   " onClick="Add(this.form.list6,this.form.elements['list5[]'])" name="B12">
          &nbsp;<br>
          <br>
          &nbsp; 
          <input type="button" value="   -   " onClick="remove(this.form.elements['list5[]'])" name="B22">
          &nbsp;</div></td>
          <td>&nbsp;</td>          
          <td> 
          <?php
		  // Exibo os te_node_address e o nome do computador (se estiver cadastrado). 
		  $query = "SELECT 	DISTINCT ae.te_node_address
					  FROM 		acoes_excecoes ae, locais,redes
				  	WHERE 	ae.id_acao='".$_GET['id_acao']."' AND ae.id_rede = redes.id_rede and redes.id_local = locais.id_local ".
								$where;
							
		  $result_excecoes = mysql_query($query) or die('6-'.$oTranslator->_('kciq_msg select on table fail', array('acoes_excecoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
				?>
 	        <input name="frmMAC_Selecionados"    type="hidden" id="frmMAC_Selecionados"    value="">  
              <select name="list5[]" size="10" multiple class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <?php
				while($campos_excecoes=mysql_fetch_array($result_excecoes)) 
					echo '<option value="' . $campos_excecoes['te_node_address']. '">' . $campos_excecoes['te_node_address'] . '</option>';
			?>
              </select></td>
          </tr>
          <tr><td colspan="5">&nbsp;</td></tr>
          <tr><td height="1" bgcolor="#333333" colspan="5"></td></tr>
          <tr><td colspan="5">&nbsp;</td></tr>
          <tr><td colspan="5"> <div align="center"> 
            <?php
			/*
                <input name="submit" type="submit" value="<?php echo $oTranslator->_('Gravar informacoes');?>" onClick="SelectAll(this.form.elements['list1[]']);SelectAll(this.form.elements['list2[]']); SelectAll(this.form.elements['list4[]']); SelectAll(this.form.elements['list5[]']);return Confirma('<?php echo $oTranslator->_('Confirma Configuracao de Acao?');?>');" <?php echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
			
			*/
            ?>
                <input name="submit" type="submit" value="<?php echo $oTranslator->_('Gravar informacoes');?>" onClick="return Confirma('<?php echo $oTranslator->_('Confirma Configuracao de Acao?');?>');" <?php echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
              </div></td>
          </tr>
          <tr><td colspan="5">&nbsp;</td></tr>
        </table>
        </form></td>
  </tr>
</table>
</body>
</html>