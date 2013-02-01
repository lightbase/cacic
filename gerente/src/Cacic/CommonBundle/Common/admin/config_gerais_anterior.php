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
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}
require_once('../include/library.php');
?>
<html>
<head>
<title><?php echo $oTranslator->_('Configuracoes do Modulo Gerente');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
require_once('../include/js/selecao_listbox.js');
?>
<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
<SCRIPT LANGUAGE="JavaScript">
function valida_notificacao_hardware() 
	{
	if ( (document.forma.listaHardwareSelecionados.length > 0 && 
	      document.forma.te_notificar_mudanca_hardware.value == '') ||
	     (document.forma.listaHardwareSelecionados.length == 0 && 
	      document.forma.te_notificar_mudanca_hardware.value != '')) 
		{	
		alert("<?php echo $oTranslator->_('ATENCAO: Verifique os campos para notificacao de alteracao de hardware');?>");
		if (document.forma.listaHardwareSelecionados.length == 0)
			document.forma.listaHardwareSelecionados.focus()
		else
	      document.forma.te_notificar_mudanca_hardware.focus();

		return false;

		}	
	}

function valida_notificacao_usb() 
	{
	if ( (document.forma.listaUSBselecionados.length > 0 && 
	      document.forma.te_notificar_utilizacao_usb.value == '') ||
	     (document.forma.listaUSBselecionados.length == 0 && 
	      document.forma.te_notificar_utilizacao_usb.value != '')) 
		{	
		alert("<?php echo $oTranslator->_('ATENCAO: Verifique os campos para notificacao de utilizacao de dispositivos USB');?>");
		if (document.forma.listaUSBselecionados.length == 0)
			document.forma.listaUSBselecionados.focus()
		else
	      document.forma.te_notificar_utilizacao_usb.focus();

		return false;

		}	
	}

function ProcessaListas(strListas)
	{
	var arrListas = strListas.split(',');
	var intK = 0;	
	for (var intI=0; intI < arrListas.length; intI ++)
		{
		intK = 0;
		for (intJ=intK;intJ < window.document.forma.length;intJ++)		
			{		
			if (window.document.forma.elements[intJ].type == 'select-multiple' && window.document.forma.elements[intJ].name == arrListas[intI])
				{
				SelectAll(window.document.forma.elements[intJ]);
				intK = intJ+1;
				intJ = window.document.forma.length;
				}
			}
		}
	}

function MontaListasDisponiveisSelecionados(strGraficosDisponiveis,strGraficosDisponiveisDesc,strGraficosSelecionados)
	{
	var arrGraficosDisponiveisDesc  = strGraficosDisponiveisDesc.split('#');
	var arrGraficosDisponiveis  	= strGraficosDisponiveis.split(']');	
	var arrGraficosSelecionados 	= strGraficosSelecionados.split(']');
	var boolAchei = false;
	for (i=0; i < (arrGraficosDisponiveis.length-1); i++)
		{
		boolAchei = false;
		for (j=0; j < (arrGraficosSelecionados.length -1); j++)		
			if (arrGraficosDisponiveis[i] == arrGraficosSelecionados[j])
				{
				boolAchei = true;
				j = arrGraficosSelecionados.length;
				}

		var no   = new Option();
		no.value = arrGraficosDisponiveis[i]+']'; // Devolvo o "]" que foi retirado no split...
		no.text  = arrGraficosDisponiveisDesc[i];
	
		if (boolAchei) // Gravarei na lista SELECIONADOS
			document.forma.elements['listaExibeGraficosSelecionados[]'].options[document.forma.elements['listaExibeGraficosSelecionados[]'].options.length] = no;
		else		   // Gravarei na lista DISPONIVEIS
			document.forma.elements['listaExibeGraficosDisponiveis[]'].options[document.forma.elements['listaExibeGraficosDisponiveis[]'].options.length] = no;
		}
	}
	
function SetaServidorUpdates()	
	{
	document.forma.frm_te_serv_updates_padrao.value = document.forma.sel_te_serv_updates.value;	
	}

</script>

</head>

<body background="../imgs/linha_v.gif" onLoad="SetaCampo('te_notificar_mudanca_hardware');">
<?php
$frm_id_local = ($_POST['frm_id_local']<>''?$_POST['frm_id_local']:$_SESSION['id_local']);

require_once('../include/library.php');
conecta_bd_cacic();
$where = '';

if ($_SESSION['te_locais_secundarios'])
	{
	$where = str_replace('loc.id_local',' (loc.id_local',$where);
	$where .= ' AND (loc.id_local = '.$frm_id_local.' OR (loc.id_local IN ('.$_SESSION['te_locais_secundarios'].'))) ';
	}
else if ($_SESSION['cs_nivel_administracao'] <> 1)	
	$where = ' AND loc.id_local ='.$frm_id_local.' ';
	
$queryConfiguracoesLocais = "	SELECT 			loc.id_local,
												loc.sg_local,
												loc.nm_local,
												c_loc.te_notificar_mudanca_hardware,
												c_loc.te_notificar_utilizacao_usb,												
												c_loc.te_usb_filter,
												c_loc.te_exibe_graficos,
												c_loc.te_serv_cacic_padrao,
												c_loc.te_serv_updates_padrao
								FROM 			locais loc,
												configuracoes_locais c_loc
								WHERE 			loc.id_local = c_loc.id_local ".$where;
$orderby = ' ORDER BY loc.sg_local';

if ($_SESSION['id_usuario'] == 15)
	echo $queryConfiguracoesLocais . '<br>';

$resultConfiguracoesLocais = mysql_query($queryConfiguracoesLocais.$orderby) or die('1-'.$oTranslator->_('kciq_msg delete row on table fail', array('Locais/Configuracoes_Locais'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
$row_configuracoes_locais = mysql_fetch_array($resultConfiguracoesLocais);
if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2 || ($_SESSION['cs_nivel_administracao'] == 3 && $_SESSION['te_locais_secundarios']<>''))
	{	
	?>
	<div id="LayerLocais" style="position:absolute; width:200px; height:115px; z-index:1; left: 0px; top: 0px; visibility:hidden">
	<?php
	$resultConfiguracoesLocais = mysql_query($queryConfiguracoesLocais.$orderby) or die('2-'.$oTranslator->_('kciq_msg delete row on table fail', array('Locais/Configuracoes_Locais'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");

	echo '<select id="SELECTconfiguracoes_locais" name="SELECTconfiguracoes_locais">';
	while ($rowConfiguracoesLocais = mysql_fetch_array($resultConfiguracoesLocais))
		{
		echo '<option id="'.$rowConfiguracoesLocais['id_local'].'" value="'. $rowConfiguracoesLocais['nm_local'].'#FD#'.
																			 $rowConfiguracoesLocais['te_notificar_mudanca_hardware'].'#FD#'.
																			 $rowConfiguracoesLocais['te_exibe_graficos'].'#FD#'.
																			 $rowConfiguracoesLocais['te_serv_cacic_padrao'].'#FD#'.																		
																			 $rowConfiguracoesLocais['te_serv_updates_padrao'].'#FD#'.
																			 $rowConfiguracoesLocais['te_notificar_utilizacao_usb'].'#FD#'.
																			 $rowConfiguracoesLocais['te_usb_filter'].'">'.$rowConfiguracoesLocais['nm_local'].'</option>';					
		}
	echo '</select>';		

	$queryDescricaoHardware = "	SELECT 		nm_campo_tab_hardware,
											te_desc_hardware,
											te_locais_notificacao_ativada
								FROM 		descricao_hardware
								ORDER BY	te_desc_hardware";

	$resultDescricaoHardware = mysql_query($queryDescricaoHardware) or die('3-'.$oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou',array('descricao_hardware')));

	echo '<select id="SELECTdescricao_hardware" name="SELECTdescricao_hardware">';
	while ($rowDescricaoHardware = mysql_fetch_array($resultDescricaoHardware))
		{
		echo '<option value="'.$rowDescricaoHardware['te_locais_notificacao_ativada'].'" id="'. $rowDescricaoHardware['nm_campo_tab_hardware'].'">'.$rowDescricaoHardware['te_desc_hardware'].'</option>';					
		}
	echo '</select>';		

	// Consulto a base trazendo registros sobre dispositivos USB utilizados pelo(s) local(is)
	$queryUSBDevicesUtilizados = "SELECT DISTINCT 
									 usbv.id_vendor,
									 usbv.nm_vendor,
									 usbd.id_device,
									 usbd.nm_device,
									 r.id_local
							  FROM   usb_logs    usbl,
									 usb_vendors usbv,
									 usb_devices usbd,
									 computadores c,
									 redes r,
									 locais loc
							  WHERE  usbl.id_so = c.id_so AND
									 usbl.te_node_address = c.te_node_address AND						  		 
									 loc.id_local = r.id_local AND
									 c.te_ip = r.te_ip AND								 
									 usbv.id_vendor = usbl.id_vendor AND
									 usbd.id_vendor = usbl.id_vendor AND
									 usbd.id_vendor = usbv.id_vendor AND 
									 usbd.id_device = usbl.id_device ".$where." 
							  ORDER BY usbv.nm_vendor,usbd.nm_device";

	$resultUSBDevicesUtilizados = mysql_query($queryUSBDevicesUtilizados) or die('3-'.$oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou',array('descricao_hardware')));

echo '*'.mysql_num_rows($resultUSBDevicesUtilizados).'*<br>';

	echo '<select id="listaUSBDevicesUtilizados" name="listaUSBDevicesUtilizados">';	
	while ($rowUSBDevicesUtilizados = mysql_fetch_array($resultUSBDevicesUtilizados))
		{
		echo '<option value="#'.$rowUSBDevicesUtilizados['id_local'].'#'.$rowUSBDevicesUtilizados['id_vendor'].'.'.$rowUSBDevicesUtilizados['id_device'].'">('.$rowUSBDevicesUtilizados['id_vendor'].')&nbsp;'.$rowUSBDevicesUtilizados['nm_vendor'].'&nbsp;&nbsp;&nbsp;('.$rowUSBDevicesUtilizados['id_device'].')&nbsp;'.$rowUSBDevicesUtilizados['nm_device'].'</option>';					
		}
	echo '</select>';			
	?>
	</div>
	<?php
	}
	?>
<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>
<script language="JavaScript" type="text/javascript" src="../include/js/setLocalConfigGerais.js"></script>
	<form action="config_gerais_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form();return valida_notificacao_hardware();">
<table width="85%" border="0" align="center">

  <tr> 
      <td class="cabecalho"><?php echo $oTranslator->_('Configuracoes do Modulo Gerente');?></td>
  </tr>
  <tr> 
      <td class="descricao"><?php echo $oTranslator->_('As opcoes abaixo determinam como o modulo gerente devera se comportar.');?></td>
  </tr>
</table>
  <table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
  	<?php 

	// Será mostrado apenas para os níveis Administração, Gestão Central e Supervisão com acessos a locais secundários.
	if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2 || ($_SESSION['cs_nivel_administracao'] == 3 && $_SESSION['te_locais_secundarios']<>''))
		{
		?>
	    <tr> 
	    <td class="label"><br><?php echo $oTranslator->_('Locais');?>: </td>
    	</tr>  
    	<tr> 
      	<td height="1" bgcolor="#333333"></td>
    	</tr>
    	<tr> 	
		<td>
		<?php
		if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2)
			$where = '';
			
		$query_locais = "SELECT		loc.id_local,
									loc.nm_local,
									loc.sg_local
					  	FROM		locais loc 
						WHERE 		1 ". // Somente para reaproveitar a definição de where feita anteriormente...
						$where . " 
				  		ORDER BY  	loc.sg_local"; 
		$result_locais = mysql_query($query_locais) or die('4-'.$oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou',array('locais'))); 

		?>
    	<select size="5" id="SELECTlocais" name="SELECTlocais" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" onChange="setLocal(this);">	
    	<?php 		
		while ($row_locais = mysql_fetch_array($result_locais))
			{
			echo '<option id="'.$row_locais['id_local'].'" value="'. $row_locais['id_local'].'"';
			if ($row_locais['id_local']==$frm_id_local) 
				echo '  selected="selected"';
						
			echo '>'.$row_locais['sg_local'].' - '.$row_locais['nm_local'].'</option>';					
			}
 		?> 
    	</select>

		</td>
    	</tr>
		<?php
		}
		?>
		
    <tr> 
      <td class="label"> 
        <?php echo $oTranslator->_('Nome da organizacao-empresa-orgao');?>:
      </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
          <?php // Atenção: o campo abaixo deve estar em "disabled", pois, a alteração desse valor só será permitida ao nível 
		   //          Administração, na opção Administração/Cadastros/Locais ?>
          <input name="nm_organizacao" id="nm_organizacao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" type="text" value="<?php echo $row_configuracoes_locais['nm_local']; ?>" size="65" disabled>
          <input name="frm_id_local" id="frm_id_local" type="hidden" value="<?php echo $frm_id_local; ?>">		  
        </p></td>
    </tr>
    <tr> 
      <td height="17">&nbsp;</td>
    </tr>
    <tr> 
      <td class="label">
        <?php echo $oTranslator->_('E-mails para notificar alteracoes de hardware');?>: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
      <textarea name="te_notificar_mudanca_hardware" cols="55"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="te_notificar_mudanca_hardware"><?php echo $row_configuracoes_locais['te_notificar_mudanca_hardware']; ?></textarea> 
        </p></td>
    </tr>
    <tr> 
      <td class="ajuda"><?php echo $oTranslator->_('Atencao: informe os emails separados por virgulas');?> (&quot;,&quot;)
      <br>
      <?php echo $oTranslator->_('Exemplo');?>: fulano.tal@previdencia.gov.br, luis.almeida@xyz.com</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">
            	<?php echo $oTranslator->_('Realizar notificacao caso haja alteracoes nas seguintes configuracoes de hardware');?>:
            </td>            
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td height="1" class="label"><table border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="label"><div align="left"><?php echo $oTranslator->_('Disponiveis');?>:</div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="label"><p><?php echo $oTranslator->_('Selecionadas');?>:</p></td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <?php    
				        /* Consulto todos os hardwares que foram previamente selecionados. */ 
			  	$query = "SELECT nm_campo_tab_hardware, te_desc_hardware
						  FROM   descricao_hardware 
						  WHERE  ".$frm_id_local." IN (te_locais_notificacao_ativada)";
						$result_hardwares_ja_selecionados = mysql_query($query) or die('5-'.$oTranslator->_('kciq_msg select on table fail', array('descricao_hardware'))."! ".$oTranslator->_('kciq_msg session fail',false,true));
						
						/* Agora monto os itens do combo de hardwares selecionadas. */ 
				while($campos_hardwares_selecionados = mysql_fetch_array($result_hardwares_ja_selecionados)) 
					{
					$itens_combo_hardwares_selecionados = $itens_combo_hardwares_selecionados . '<option value="' . $campos_hardwares_selecionados['nm_campo_tab_hardware']. '">' . $campos_hardwares_selecionados['te_desc_hardware'] . '</option>'; 
					}
						
						/* Consulto as hardwares que não foram previamente selecionadas. */ 
			  	$query = "SELECT nm_campo_tab_hardware, te_desc_hardware
						  FROM   descricao_hardware 
						  WHERE  ".$frm_id_local." NOT IN (te_locais_notificacao_ativada)";
						$result_hardwares_nao_selecionados = mysql_query($query) or die('6-'.$oTranslator->_('kciq_msg select on table fail', array('descricao_hardware'))."! ".$oTranslator->_('kciq_msg session fail',false,true));
						/* Agora monto os itens do combo de hardwares NÃO selecionadas. */ 
       		while($campos_hardwares_nao_selecionados=mysql_fetch_array($result_hardwares_nao_selecionados)) 	
				{
				$itens_combo_hardwares_nao_selecionados = $itens_combo_hardwares_nao_selecionados . '<option value="' . $campos_hardwares_nao_selecionados['nm_campo_tab_hardware']. '">' . $campos_hardwares_nao_selecionados['te_desc_hardware']  . '</option>';
				}  ?>
                      <select multiple size="10" name="list1[]" id="listaHardwareDisponiveis"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <?php echo $itens_combo_hardwares_nao_selecionados; ?> 
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
                  <td><select multiple size="10" name="list2[]" id="listaHardwareSelecionados"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
                      <?php echo $itens_combo_hardwares_selecionados; ?> </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td height="1" class="ajuda">&nbsp;&nbsp;&nbsp;
            	(<?php echo $oTranslator->_('Dica: use SHIFT ou CTRL para selecionar multiplos itens');?>)</td>
          </tr>
          
    <tr> 
      <td height="17">&nbsp;</td>
    </tr>
    <tr> 
      <td class="label">
        <?php echo $oTranslator->_('E-mails para notificar utilizacao de dispositivos USB');?>: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
          <textarea name="te_notificar_utilizacao_usb" cols="55"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="te_notificar_utilizacao_usb"><?php echo $row_configuracoes_locais['te_notificar_utilizacao_usb']; ?></textarea>
        </p></td>
    </tr>
    <tr> 
      <td class="ajuda"><?php echo $oTranslator->_('Atencao: informe os emails separados por virgulas');?> (&quot;,&quot;)
      <br>
      <?php echo $oTranslator->_('Exemplo');?>: fulano.tal@previdencia.gov.br, luis.almeida@xyz.com</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><?php /*<table width="100%" border="0" cellpadding="0" cellspacing="1">*/?>
          <tr> 
            <td class="label">
            	<?php echo $oTranslator->_('Realizar notificacao caso haja utilização dos seguintes dispositivos USB nas estacoes de trabalho');?>:
            </td>            
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td height="1" class="label"><table border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="label"><div align="left"><?php echo $oTranslator->_('Disponiveis');?>:</div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="label"><p><?php echo $oTranslator->_('Selecionados');?>:</p></td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <select multiple size="10" name="listaUSBd[]" id="listaUSBdisponiveis"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                      </select>
                    </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['listaUSBd[]'],this.form.elements['listaUSBs[]'])" name="B1">
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['listaUSBs[]'],this.form.elements['listaUSBd[]'])" name="B2">
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple size="10" name="listaUSBs[]" id="listaUSBselecionados"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
                      </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td height="1" class="ajuda">&nbsp;&nbsp;&nbsp;
            	(<?php echo $oTranslator->_('Dica: use SHIFT ou CTRL para selecionar multiplos itens');?>)</td>
          </tr>          
        </table></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label"><?php echo $oTranslator->_('Exibir Graficos na Pagina Principal e Detalhes');?>:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td height="1" class="label"><table border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td>&nbsp;&nbsp;</td>
            <td class="label"><div align="left"><?php echo $oTranslator->_('Disponiveis');?>:</div></td>
            <td>&nbsp;&nbsp;</td>
            <td width="40">&nbsp;</td>
            <td nowrap>&nbsp;&nbsp;</td>
            <td nowrap class="label"><p><?php echo $oTranslator->_('Selecionadas');?>:</p></td>
            <td nowrap>&nbsp;&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <div align="left"> 
			<?php
			// Gráficos disponíveis para exibição na página principal
			// [so][acessos][locais][acessos_locais]
			// A variável de sessão menu_seg->_SESSION['te_exibe_graficos'] contém os gráficos selecionados para exibição
			$arrValores = getValores('configuracoes_locais', 'te_exibe_graficos', 'id_local='.$frm_id_local);
			$te_exibe_graficos = $arrValores['te_exibe_graficos'];
			?>
                <select multiple size="10" name="listaExibeGraficosDisponiveis[]" id="listaExibeGraficosDisponiveis" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                </select>
              </div></td>
            <td>&nbsp;</td>
            <td width="40"> <div align="center"> 
                <input type="button" value="   &gt;   " onClick="move(this.form.elements['listaExibeGraficosDisponiveis[]'],this.form.elements['listaExibeGraficosSelecionados[]'])" name="B3" id="B3">
                <br>
                <br>
                <input type="button" value="   &lt;   " onClick="move(this.form.elements['listaExibeGraficosSelecionados[]'],this.form.elements['listaExibeGraficosDisponiveis[]'])" name="B4" id="B4">
              </div></td>
            <td>&nbsp;</td>
            <td><select multiple size="10" name="listaExibeGraficosSelecionados[]" id="listaExibeGraficosSelecionados"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
				</select></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label"><?php echo $oTranslator->_('ServidorAutenticacao de Aplicacao padrao');?>:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p><strong> 
          <select name="frm_te_serv_cacic_padrao" id="frm_te_serv_cacic_padrao" onChange="SetaServidorBancoDadosPadrao();"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
            <option value="0"><?php echo $oTranslator->_('Selecione');?></option>
        <?php
		$query_configuracoes_padrao = "SELECT	Distinct te_serv_cacic_padrao,
		                                        		 te_serv_updates_padrao
						 			   FROM		configuracoes_padrao"; 

		$result_configuracoes_padrao = mysql_query($query_configuracoes_padrao) or die('7-'.$oTranslator->_('kciq_msg select on table fail', array('configuracoes_padrao'))."! ".$oTranslator->_('kciq_msg session fail',false,true)); 
		
		$v_achei = 0;
		while ($row_configuracoes_padrao=mysql_fetch_array($result_configuracoes_padrao))
			{ 
			$v_achei = 1;
			echo "<option value=\"" . $row_configuracoes_padrao["te_serv_cacic_padrao"] . "\"";
			if ($row_configuracoes_padrao['te_serv_cacic_padrao'] == $row_configuracoes_locais["te_serv_cacic_padrao"]) echo " selected ";
			echo ">" . $row_configuracoes_padrao["te_serv_cacic_padrao"] . "</option>";
			}

		if ($v_achei == 0)			
			{			
			echo "<option value=\"" . $_SERVER['HTTP_HOST'] . "\"";
			echo ">" . $_SERVER['HTTP_HOST'] . "</option>";			
			}
			?>
          </select>
          </strong></p></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label"><?php echo $oTranslator->_('ServidorAutenticacao de Updates padrao');?>:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
          <select name="frm_te_serv_updates_padrao" id="frm_te_serv_updates_padrao" onChange="SetaServidorUpdatesPadrao();"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
            <option value="0"><?php echo $oTranslator->_('Selecione');?></option>
            <?php
			mysql_data_seek($result_configuracoes_padrao,0);			
			while ($row_configuracoes_padrao=mysql_fetch_array($result_configuracoes_padrao))
				{ 
				echo "<option value=\"" . $row_configuracoes_padrao["te_serv_updates_padrao"] . "\"";
				if ($row_configuracoes_locais['te_serv_updates_padrao'] == $row_configuracoes_padrao["te_serv_updates_padrao"]) echo " selected ";
				echo ">" . $row_configuracoes_padrao["te_serv_updates_padrao"] . "</option>";
			   	} 			
			?>
          </select>
        </p></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
		<script language="javascript">
		setLocal(document.getElementById('SELECTlocais'));
		MontaListasDisponiveisSelecionados('[so][acessos][acessos_locais][locais]','Totais de Computadores por Sistemas Operacionais#Últimos Acessos dos Agentes do Local#Últimos Acessos dos Agentes por Local na Data#Totais de Computadores Monitorados por Local','<?php echo $te_exibe_graficos;?>');
		</script>					  	
		
    <tr> 
      <td><div align="center"> 
          <input name="submit" type="submit" value="<?php echo $oTranslator->_('Gravar informacoes');?>" onClick="ProcessaListas('list2[],list4[],listaExibeGraficosSelecionados[],listaUSBs[]')" <?php echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
        </div></td>
    </tr>
  </table>
</form>		  
<p>&nbsp;</p>
</body>
</html>
