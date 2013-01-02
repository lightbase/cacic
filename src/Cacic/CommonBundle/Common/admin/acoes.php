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
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

require_once('../include/library.php');

AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
$id_acao = $_GET['id_acao']; 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title><?=$oTranslator->_('Acoes - Configuracoes');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
function Volta() 
	{
	top.location = '../arquivos/index.php';
	}

function PreencheFormListas(comboLista)
	{
	var strValores;
	strValores = "";
  	for (var i=0;i < comboLista.length;i++) 
		{
		if (strValores != "")
			strValores = strValores + "#FD#";
		strValores = strValores + comboLista.options[i].value;
   		}	
	return strValores;
	}
	
function valida_form() 
	{
	if ((document.forma.elements.cs_situacao[2].checked) && (document.forma.elements['list2[]'].length <= 0)) 
		{
		if (!confirm ("<?=$oTranslator->_('Nao foi selecionada nenhuma rede. Deseja continuar?');?>")) 
			{	
			return false; 
			}
		}
	else if (document.forma.elements['list4[]'].length <= 0) 
		{
		if (!confirm ("<?=$oTranslator->_('Nao foi selecionado nenhum sistema operacional. Deseja continuar?');?>")) 
			{	
			return false; 
			}
		}

	// Os procedimentos abaixo foram implementados como solu��o de contorno �s limita��es impostas pela
	// extens�o Suhosin	em um servidor de produ��o cujas altera��es levavam burocraticamente at� 5 dias!!!
	document.forma.elements['frmList1'].value = PreencheFormListas(document.forma.elements['list1[]']);
	document.forma.elements['frmList2'].value = PreencheFormListas(document.forma.elements['list2[]']);
	document.forma.elements['frmList4'].value = PreencheFormListas(document.forma.elements['list4[]']);
	document.forma.elements['frmList5'].value = PreencheFormListas(document.forma.elements['list5[]']);

	// Desabilito as listas para que n�o sejam enviadas no POST
	document.forma.elements['list1[]'].disabled=true;
	document.forma.elements['list2[]'].disabled=true;
	document.forma.elements['list4[]'].disabled=true;
	document.forma.elements['list5[]'].disabled=true;
	document.forma.elements['list6[]'].disabled=true;	
	
	return true;
	}

function verifica_status() 
	{
   	if (document.forma.elements.cs_situacao[2].checked) 
   		{
       	document.forma.elements['list1[]'].disabled=false;
       	document.forma.elements['list2[]'].disabled=false;
       	document.forma.elements['B1'].disabled=false;
       	document.forma.elements['B2'].disabled=false;
		DesSelectAll(document.forma.elements['list1[]'])
		DesSelectAll(document.forma.elements['list2[]'])
   		}
   else if(document.forma.elements.cs_situacao[0].checked) 					
   		{
		if(document.forma.elements['list2[]'].length > 0)
			{
			SelectAll(document.forma.elements['list2[]']);
			move(document.forma.elements['list2[]'],document.forma.elements['list1[]']);
			SelectAll(document.forma.elements['list1[]']);
			}			
      	document.forma.elements['list1[]'].disabled=true;
       	document.forma.elements['list2[]'].disabled=true;
       	document.forma.elements['B1'].disabled=true;
       	document.forma.elements['B2'].disabled=true;
    	}
    else
    	{
		if(document.forma.elements['list1[]'].length > 0)
			{
			SelectAll(document.forma.elements['list1[]']);
			move(document.forma.elements['list1[]'],document.forma.elements['list2[]']);
			SelectAll(document.forma.elements['list2[]']);
			}			
      	document.forma.elements['list1[]'].disabled=false;
       	document.forma.elements['list2[]'].disabled=false;
       	document.forma.elements['B1'].disabled=false;
       	document.forma.elements['B2'].disabled=false;
    	}
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

<? 
    conecta_bd_cacic();
	/*
	$query = "SELECT 	* 
			  FROM 		acoes,
			  			acoes_redes
			  WHERE		acoes.id_acao='$id_acao' AND
			  			acoes_redes.id_acao = acoes.id_acao AND
						acoes_redes.id_local = ".$_SESSION['id_local'];
	*/
	$query = "SELECT 	*
			  FROM 		acoes_redes
			  WHERE		id_acao = '".$_GET['id_acao']."' ";
	if ($_SESSION['cs_nivel_administracao'] <> 1 && $_SESSION['cs_nivel_administracao'] <> 2)			 
		$query .= " AND id_local = ".$_SESSION['id_local'];

	if ($_SESSION['te_locais_secundarios']<>'')
		{
		// Fa�o uma inser��o de "(" para ajuste da l�gica para consulta
		$query = str_replace('id_local = ','(id_local = ',$query);
		$query .= ' OR id_local IN ('.$_SESSION['te_locais_secundarios'].'))';	
		}

	$result_acoes = mysql_query($query) or die('1-'.$oTranslator->_('kciq_msg select on table fail', array('acoes_redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
	$campos_acoes = mysql_fetch_array($result_acoes);
?>

<body background="../imgs/linha_v.gif" onLoad="verifica_status();">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><? echo $_GET['te_descricao_breve']; ?></td>
  </tr>
  <tr> 
    <td class="descricao"><? echo $_GET['te_descricao']; ?></td>
  </tr>
  <tr> 
    <td class="descricao"><div align="right">&nbsp;&nbsp;<br>
        <?=$oTranslator->_('Ultima Alteracao em');?> <? echo date("d/m/Y H:i", strtotime($campos_acoes['dt_hr_alteracao'])); ?></div></td>
  </tr>
</table>
<table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr> 
    <td valign="top"> <form action="acoes_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form()">
        <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">&nbsp;<br>
              <?=$oTranslator->_('Onde executar essa acao-configuracao');?>: 
              <input name="id_acao" type="hidden" id="id_acao" value="<? echo $id_acao; ?>">
              </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td class="opcao"><p> 
                <input name="cs_situacao" type="radio" value="N" <? if (strtoupper($campos_acoes['cs_situacao']) == 'N' ||$campos_acoes['cs_situacao'] == NULL) echo 'checked'; ?> onClick="verifica_status();" >
                <?=$oTranslator->_('Desabilitar acao-configuracao nas redes');?></font><br>
                <input type="radio" name="cs_situacao" value="T" <? if (strtoupper($campos_acoes['cs_situacao']) == 'T') echo 'checked'; ?> onClick="verifica_status();" >
                <?=$oTranslator->_('Habilitar acao-configuracao em todas as redes');?><br>
                <input type="radio" name="cs_situacao" value="S" <? if (strtoupper($campos_acoes['cs_situacao']) == 'S') echo 'checked'; ?> onClick="verifica_status();" >
                <?=$oTranslator->_('Apenas nas redes selecionadas');?></p></td>
          </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td>&nbsp;&nbsp;</td>
            <td class="label"><div align="left"><?=$oTranslator->_('Disponiveis');?></div></td>
            <td>&nbsp;&nbsp;</td>
            <td width="40">&nbsp;</td>
            <td nowrap>&nbsp;&nbsp;</td>
            <td class="label" nowrap><p><?=$oTranslator->_('Selecionadas');?>:</p></td>
            <td nowrap>&nbsp;&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="opcao"> <div align="left"> 
                <?    
						$where = ($_SESSION['cs_nivel_administracao']<>1?' AND acoes_redes.id_local = '.$_SESSION['id_local']:'');

						if ($_SESSION['te_locais_secundarios']<>'')
							{
							// Fa�o uma inser��o de "(" para ajuste da l�gica para consulta
							$where = str_replace('AND acoes_redes.id_local = ','AND (acoes_redes.id_local = ',$where);
							$where .= ' OR acoes_redes.id_local IN ('.$_SESSION['te_locais_secundarios'].'))';	
							}
						
				        /* Consulto todas as redes que foram previamente selecionadas para a a a��o em quest�o. */ 
					  	$query = "SELECT 	acoes_redes.id_ip_rede, 
											acoes_redes.id_local,
											nm_rede
								  FROM 		acoes_redes, 
								  			redes
								  WHERE 	id_acao='$id_acao' AND 
								  			acoes_redes.id_ip_rede = redes.id_ip_rede AND
											acoes_redes.id_local = redes.id_local ".
											$where;
						$result_redes_ja_selecionadas = mysql_query($query) or die('2-'.$oTranslator->_('kciq_msg select on table fail', array('acoes_redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");

						/* Agora monto os itens do combo de redes selecionadas e preparo a string de exclusao (NOT IN) para a proxima consulta. */ 
						while($campos_redes_selecionadas = mysql_fetch_array($result_redes_ja_selecionadas)) 
							{
						   	$itens_combo_redes_selecionadas = $itens_combo_redes_selecionadas . '<option value="' . $campos_redes_selecionadas['id_ip_rede'].'#'.$campos_redes_selecionadas['id_local']. '">' . $campos_redes_selecionadas['id_ip_rede'] . ' - ' . capa_string($campos_redes_selecionadas['nm_rede'], 22) . '</option>'; 
						   	$not_in_ja_selecionadas = $not_in_ja_selecionadas . "'" . $campos_redes_selecionadas['id_ip_rede'] .  "',";
							}
						$not_in_ja_selecionadas = $not_in_ja_selecionadas . "''";

					    $where = ($_SESSION['cs_nivel_administracao']<>1?' AND id_local = '.$_SESSION['id_local']:'');						

						if ($_SESSION['te_locais_secundarios']<>'')
							{
							// Fa�o uma inser��o de "(" para ajuste da l�gica para consulta
							$where = str_replace('id_local = ','(id_local = ',$where);
							$where .= ' OR id_local IN ('.$_SESSION['te_locais_secundarios'].'))';	
							}

						/* Consulto as redes que n�o foram previamente selecionadas. */ 
					  	$query = "SELECT 	id_ip_rede, 
											nm_rede,
											id_local
								  FROM 		redes
								  WHERE 	id_ip_rede NOT IN ($not_in_ja_selecionadas) ".
								  			$where;
						$result_redes_nao_selecionadas = mysql_query($query) or die('3-'.$oTranslator->_('kciq_msg select on table fail', array('redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
						/* Agora monto os itens do combo de redes N�O selecionadas. */ 
                        while($campos_redes_nao_selecionadas=mysql_fetch_array($result_redes_nao_selecionadas)) 	
							{
							if (trim($campos_redes_nao_selecionadas['id_ip_rede']) <> '' && trim($campos_redes_nao_selecionadas['id_local']) <> '')
						   	$itens_combo_redes_nao_selecionadas = $itens_combo_redes_nao_selecionadas . '<option value="' . $campos_redes_nao_selecionadas['id_ip_rede'].'#'.$campos_redes_nao_selecionadas['id_local']. '">' . $campos_redes_nao_selecionadas['id_ip_rede'] . ' - ' . capa_string($campos_redes_nao_selecionadas['nm_rede'], 22) . '</option>';
							}  ?>
                <input name="frmList1" type="hidden" id="frmList1" value="">                        
                <select multiple size="10" name="list1[]"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                  <? echo $itens_combo_redes_nao_selecionadas; ?> 
                </select>
                </div></td>
            <td>&nbsp;</td>
            <td width="40"> <div align="center"> 
                <input type="button" value="   &gt;   " onClick="move(this.form.elements['list1[]'],this.form.elements['list2[]'])" name="B1">
                <br>
                <br>
                <input type="button" value="   &lt;   " onClick="move(this.form.elements['list2[]'],this.form.elements['list1[]'])" name="B2">
              </div></td>
            <td>&nbsp;</td>
            <td>
            <input name="frmList2" type="hidden" id="frmList2" value="">                                    
            <select multiple size="10" name="list2[]"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <? echo $itens_combo_redes_selecionadas; ?> </select></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="7" class="ajuda"><div align="center">
            	(<?=$oTranslator->_('Dica: use SHIFT ou CTRL para selecionar multiplos itens');?>)</div></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td class="label"><?=$oTranslator->_('Sistemas Operacionais onde essa acao-configuracao devera ser aplicada');?>:</td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td>&nbsp;&nbsp;</td>
            <td class="label"><div align="left"><?=$oTranslator->_('Disponiveis');?></div></td>
            <td>&nbsp;&nbsp;</td>
            <td width="40">&nbsp;</td>
            <td nowrap>&nbsp;&nbsp;</td>
            <td class="label" nowrap><?=$oTranslator->_('Selecionados');?>:</td>
            <td nowrap>&nbsp;&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="opcao"> <div align="left"> 
                <?    
						 $where = ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?' AND id_local = '.$_SESSION['id_local']:'');
						/* Consulto todas os sistemas operacionais que foram previamente selecionadas para a a a��o em quest�o. */ 
					  	$query = "SELECT 	DISTINCT so.id_so, so.te_desc_so,acoes_so.id_local
								  FROM 		acoes_so, so
								  WHERE 	acoes_so.id_acao='$id_acao' ".
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
						
						/* Consulto os so's que n�o foram previamente selecionadas. */ 
					  	$query = "SELECT 	DISTINCT id_so, 
											te_desc_so
								  FROM 		so
								  WHERE 	id_so NOT IN ($not_in_so_ja_selecionados) AND
								  			id_so <> 0
								  ORDER BY  te_desc_so";
						$result_so_nao_selecionados = mysql_query($query) or die('5-'.$oTranslator->_('kciq_msg select on table fail', array('so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
						
						/* Agora monto os itens do combo de so's N�O selecionadas. */ 
                        while($campos_so_nao_selecionados=mysql_fetch_array($result_so_nao_selecionados)) 	
							{
						   	$itens_combo_so_nao_selecionados = $itens_combo_so_nao_selecionados . '<option value="' . $campos_so_nao_selecionados['id_so']. '">' . capa_string($campos_so_nao_selecionados['te_desc_so'], 35) . '</option>';
							}  ?>
                <select multiple size="10" name="list3[]">
                  <? echo $itens_combo_so_nao_selecionados; ?> 
                </select>
                </div></td>
            <td>&nbsp;</td>
            <td width="40"> <div align="center"> 
                <input type="button" value="   &gt;   " onClick="move(this.form.elements['list3[]'],this.form.elements['list4[]'])" name="B13">
                <br>
                <br>
                <input type="button" value="   &lt;   " onClick="move(this.form.elements['list4[]'],this.form.elements['list3[]'])" name="B23">
              </div></td>
            <td>&nbsp;</td>
            <td>
            <input name="frmList4" type="hidden" id="frmList4" value="">                                    
            <select multiple size="10" name="list4[]"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <? echo $itens_combo_so_selecionados; ?> </select></td>
            <td>&nbsp;</td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="ajuda">
            	(<?=$oTranslator->_('Dica: use SHIFT ou CTRL para selecionar multiplos itens');?>)
            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td class="label">
              <?=$oTranslator->_('Computadores onde esta acao-configuracao nao devera ser aplicada');?>:
              </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr>
            <td class="ajuda"><?=$oTranslator->_('Informe o endereco MAC do computador.');?> (<?=$oTranslator->_('Exemplo');?>: 00-10-4B-65-83-C8)</td>
          </tr>
        </table>
        <table width="0" border="0" cellpadding="2" cellspacing="2">
          <tr> 
            <td valign="top"> <div align="left"> 
                <input name="list6" type="text" size="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                </font></div></td>
            <td width="40" valign="top"> <div align="center"> &nbsp; 
                <input type="button" value="   +   " onClick="Add(this.form.list6,this.form.elements['list5[]'])" name="B12">
                &nbsp;<br>
                <br>
                &nbsp; 
                <input type="button" value="   -   " onClick="remove(this.form.elements['list5[]'])" name="B22">
                &nbsp;</div></td>
            <td> 
              <?
				        // Exibo os te_node_address e o nome do computador (se estiver cadastrado). 
				 	  	$query = "SELECT 	acoes_excecoes.te_node_address, 
											te_nome_computador
								  FROM 		acoes_excecoes
								  			LEFT OUTER JOIN computadores
								  			ON  acoes_excecoes.te_node_address = computadores.te_node_address
								  WHERE 	acoes_excecoes.id_acao='$id_acao'";
						$result_excecoes = mysql_query($query) or die('6-'.$oTranslator->_('kciq_msg select on table fail', array('acoes_excecoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
				?>
              <input name="frmList5" type="hidden" id="frmList5" value="">                                        
              <select name="list5[]" size="10" multiple class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <?
				while($campos_excecoes=mysql_fetch_array($result_excecoes)) 
					{
				    if (strlen($campos_excecoes['te_nome_computador']) > 0) 
						$strAux = $campos_excecoes['te_node_address'] . ' (' . $campos_excecoes['te_nome_computador'] . ')'; 
					else 
						$strAux = $campos_excecoes['te_node_address']; 

					if (trim($strAux) <> '')
						echo '<option value="' . $campos_excecoes['te_node_address']. '">' . capa_string($strAux, 60) . '</option>';
					}	
			?>
              </select></td>
          </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> <div align="center"> 
            <?
			/*
                <input name="submit" type="submit" value="<?=$oTranslator->_('Gravar informacoes');?>" onClick="SelectAll(this.form.elements['list1[]']);SelectAll(this.form.elements['list2[]']); SelectAll(this.form.elements['list4[]']); SelectAll(this.form.elements['list5[]']);return Confirma('<?=$oTranslator->_('Confirma Configuracao de Acao?');?>');" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
			
			*/
            ?>
                <input name="submit" type="submit" value="<?=$oTranslator->_('Gravar informacoes');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma Configuracao de Acao?');?>');" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
              </div></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table>
        </form></td>
  </tr>
</table>
</body>
</html>