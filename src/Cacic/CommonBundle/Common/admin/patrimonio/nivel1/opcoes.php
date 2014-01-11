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

require_once('../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

conecta_bd_cacic();
if ($submit) 
	{
    // Preciso remover os "Enters" dados no campo do formulário, pois a rotina de envio de emails
    // estava dando problemas quando encontrava esse tipo de caractere especial.
    $te_notificar_mudanca_patrim = str_replace("\r\n", " ", $_POST['te_notificar_mudanca_patrim']);
    $cs_abre_janela_patr         = ($_POST['frm_cs_abre_janela_patr']?'S':'N');

    $query = "UPDATE	configuracoes_locais set 
	          			te_notificar_mudanca_patrim = '" 	. $_POST['te_notificar_mudanca_patrim'] . "',
			  			nu_intervalo_renovacao_patrim = '" 	. $_POST['nu_intervalo_renovacao_patrim'] . "',
			  			cs_abre_janela_patr = '"			.$cs_abre_janela_patr."' 
			  WHERE		id_local ="					.$_SESSION['id_local'];
	$result = mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('configuracoes_locais'))); 
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais',$_SESSION["id_usuario"]);		
	$query_etiquetas = "UPDATE	patrimonio_config_interface set 
								in_destacar_duplicidade='N' 
						WHERE	id_local = ".$_SESSION['id_local'];
	$result_etiquetas = mysql_query($query_etiquetas) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('patrimonio_config_interface'))); 				
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'patrimonio_config_interface',$_SESSION["id_usuario"]);		
	while(list($key, $value) = each($HTTP_POST_VARS))
		{
		if (substr($key,0,8)=='etiqueta') 
			{
			$query_etiquetas = "UPDATE	patrimonio_config_interface set 
										in_destacar_duplicidade='".$value."' 
								WHERE 	id_etiqueta='".$key."' AND
										id_local = ".$_SESSION['id_local'];
			$result_etiquetas = mysql_query($query_etiquetas) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('patrimonio_config_interface'))); 				
			}
		} 
	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/patrimonio/opcoes.php&tempo=1");								
	}
$query = "SELECT 	te_notificar_mudanca_patrim, 
					nu_intervalo_renovacao_patrim, 
					cs_abre_janela_patr 
          FROM 		configuracoes_locais  
		  WHERE		id_local = ".$_SESSION['id_local']." 
		  			limit 1";

$result = mysql_query($query) or die($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('patrimonio_config_interface'))); 
$campos = mysql_fetch_array($result);
?>

<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">

<title><?php echo $oTranslator->_('Opcoes da Coleta de Informacoes Patrimoniais e Localizacao Fisica');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('nu_intervalo_renovacao_patrim');">
<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
<table width="85%" border="0" align="center">
  <tr> 
    <td class="cabecalho">
    <?php echo $oTranslator->_('Opcoes da Coleta de Informacoes Patrimoniais e Localizacao Fisica');?>
    </td>
  </tr>
  <tr> 
    <td></td>
  </tr>
</table>
<table width="85%" border="0" align="center" cellpadding="5" cellspacing="1">

  <form action="opcoes.php"  method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form()">
    <table width="100%" border="0" cellpadding="0" cellspacing="1">
		<tr>
        <td class="label"><?php echo $oTranslator->_('Local de Aplicacao');?></td>	
		</tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
		
      <tr> 
        <td valign="left" class="label"><input name=frm_nm_local type="text" value="<?php echo $_SESSION['nm_local'];?>" readonly="yes" size="75"></td>
      </tr>
		<tr><td>&nbsp;</td></tr>			
  	
      <tr> 
        <td class="label">
          <?php echo $oTranslator->_('Intervalo de solicitacao aos usuarios de renovacao das informacoes de patrimonio e Local fisica');?>
        </td>
      </tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td><p> 
            <select name="nu_intervalo_renovacao_patrim"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
              <option value="0" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '0') echo 'selected'; ?>>
                 <?php echo $oTranslator->_('Nao solicitar renovacao');?>
              </option>
              <option value="1" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '1') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Um',T_SIGLA)." ".$oTranslator->_('mes');?>
              </option>
              <option value="2" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '2') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Dois',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="3" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '3') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Tres',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="4" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '4') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Quatro',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="5" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '5') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Cinco',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="6" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '6') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Seis',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="7" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '7') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Sete',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="8" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '8') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Oito',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="9" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '9') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Nove',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="10" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '10') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Dez',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="11" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '11') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Onze',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
              <option value="12" <?php if ($campos['nu_intervalo_renovacao_patrim'] == '12') echo 'selected'; ?>> 
                 <?php echo $oTranslator->_('Doze',T_SIGLA)." ".$oTranslator->_('meses');?>
              </option>
            </select>
          </p></td>
      </tr>
    </table>
    <table width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr> 
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td valign="middle" class="label"><input name=frm_cs_abre_janela_patr type="checkbox" value="S" <?php if ($campos['cs_abre_janela_patr']=='S') echo 'checked';?>>
          <?php echo $oTranslator->_('Abrir janela de coleta ao detectar alteracoes de localizacao fisica');?>
           (<?php echo $oTranslator->_('Exemplo',T_SIGLA);?> <?php echo $oTranslator->_('IP de estacao e rede');?>)
        </td>
      </tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <p> 
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td class="label">
          <?php echo $oTranslator->_('Enderooso eletronicos a notificar ao detectar alteracoes de patrimonio ou localizacao fisica');?>
        </td>
      </tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td><p> 
            <textarea name="te_notificar_mudanca_patrim" cols="55" rows="4"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"><?php echo $campos['te_notificar_mudanca_patrim']; ?></textarea>
          </p></td>
      </tr>
      <tr> 
        <td class="ajuda">
          <?php echo $oTranslator->_('Atencao');?> <?php echo $oTranslator->_('Informe os enderecos eletronicos separados por virgulas');?> 
          <br>
          <?php echo $oTranslator->_('Exemplo',T_SIGLA);?> <?php echo $oTranslator->_('jose.silva@es.previdenciasocial.gov.br, luis.almeida@xyz.com');?>
        </td>
      </tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td class="label">&nbsp;</td>
      </tr>
      <tr> 
        <td class="label">&nbsp;</td>
      </tr>
      <?php
			$query_etiquetas = "	SELECT 		id_etiqueta,
												te_etiqueta,
												in_destacar_duplicidade
									FROM 		patrimonio_config_interface
									WHERE		nm_campo_tab_patrimonio like 'te_info_patrimonio%' AND
												id_local = ".$_SESSION['id_local'] . " 									
									ORDER BY	id_etiqueta"; 
									
		$result_etiquetas = mysql_query($query_etiquetas) or die($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('patrimonio_config_interface'))); 

		if (mysql_num_rows($result_etiquetas)>0)
			{
			?>
      <tr> 
        <td class="label">
          <?php echo $oTranslator->_('Destacar as seguintes duplicidades no relatorio de Patrimonio');?>
           
        </td>
      </tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <?php
		  	while ($row = mysql_fetch_array($result_etiquetas))
			  	{ 
				?>
      <tr> 
        <td> <input name="<?php echo $row['id_etiqueta']; ?>" type="checkbox" id="<?php echo $row['id_etiqueta']; ?>" value="S" class="opcao"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php if ($row['in_destacar_duplicidade']=='S') echo "checked"; ?>> 
          <?php echo $row['te_etiqueta'];
				?>
        </td>
      </tr>
      <?php
				}
				?></td></tr>
      <?php
		  }
		  ?>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td> <div align="center"> 
            <input name="submit" type="submit" value="<?php echo $oTranslator->_('Gravar Alteracoes');?>" onClick="return Confirma('<?php echo $oTranslator->_('Confirma configuracao para coleta de Patrimonio?');?>');document.forma.elements['list2[]'].disabled=false; SelectAll(this.form.elements['list2[]']), SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list5[]'])" <?php echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
          </div></td>
      </tr>
    </table>
  </form></td></tr>
</table>
</body>
</html>
