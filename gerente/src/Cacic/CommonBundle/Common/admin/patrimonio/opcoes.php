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
			  			cs_abre_janela_patr = '"			.$cs_abre_janela_patr."' 
			  WHERE		id_local ="					.$_SESSION['id_local'];
	$result = mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('configuracoes_locais'))); 
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');		
	$query_etiquetas = "UPDATE	patrimonio_config_interface set 
								in_destacar_duplicidade='N' 
						WHERE	id_local = ".$_SESSION['id_local'];
	$result_etiquetas = mysql_query($query_etiquetas) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('patrimonio_config_interface'))); 				
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'patrimonio_config_interface');		
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
$query = "SELECT 	te_notificar_mudanca_patrim 
          FROM 		configuracoes_locais  
		  WHERE		id_local = ".$_SESSION['id_local']." 
		  			limit 1";

$result = mysql_query($query) or die($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('patrimonio_config_interface'))); 
$campos = mysql_fetch_array($result);
?>

<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">

<title><?=$oTranslator->_('Opcoes da Coleta de Informacoes Patrimoniais e Localizacao Fisica');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('nu_intervalo_renovacao_patrim');">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">
    <?=$oTranslator->_('Opcoes da Coleta de Informacoes Patrimoniais e Localizacao Fisica');?>
    </td>
  </tr>
  <tr> 
    <td></td>
  </tr>
</table>
<table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">

  <form action="opcoes.php"  method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form()">
    <table width="100%" border="0" cellpadding="0" cellspacing="1">
		<tr>
        <td class="label"><?=$oTranslator->_('Local de Aplicacao');?></td>	
		</tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
		
      <tr> 
        <td valign="left" class="label"><input name=frm_nm_local type="text" value="<? echo $_SESSION['nm_local'];?>" readonly="yes" size="75"></td>
      </tr>
		<tr><td>&nbsp;</td></tr>			
  	
    </table>
    <table width="100%" border="0" cellpadding="0" cellspacing="1">

      <?
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
          <?=$oTranslator->_('Destacar as seguintes duplicidades no relatorio de Patrimonio');?>
           
        </td>
      </tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <?
		  	while ($row = mysql_fetch_array($result_etiquetas))
			  	{ 
				?>
      <tr> 
        <td> <input name="<? echo $row['id_etiqueta']; ?>" type="checkbox" id="<? echo $row['id_etiqueta']; ?>" value="S" class="opcao"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? if ($row['in_destacar_duplicidade']=='S') echo "checked"; ?>> 
          <?
				echo $row['te_etiqueta'];
				?>
        </td>
      </tr>
      <?
				}
				?></td></tr>
      <?
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
            <input name="submit" type="submit" value="<?=$oTranslator->_('Gravar Alteracoes');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma configuracao para coleta de Patrimonio?');?>');document.forma.elements['list2[]'].disabled=false; SelectAll(this.form.elements['list2[]']), SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list5[]'])" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
          </div></td>
      </tr>
    </table>
  </form></td></tr>
</table>
</body>
</html>
