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
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}
require_once('../../include/library.php');
// Comentado temporariamente - AntiSpy();
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
	$result = mysql_query($query) or die('Ocorreu um erro durante a atualização da tabela configuracoes.'); 
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');		
	$query_etiquetas = "UPDATE	patrimonio_config_interface set 
								in_destacar_duplicidade='N' 
						WHERE	id_local = ".$_SESSION['id_local'];
	$result_etiquetas = mysql_query($query_etiquetas) or die('Ocorreu um erro durante a inicialização de in_destacar_duplicidade na tabela patrimonio_config_interface.'); 				
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'patrimonio_config_interface');		
	while(list($key, $value) = each($HTTP_POST_VARS))
		{
		if (substr($key,0,8)=='etiqueta') 
			{
			$query_etiquetas = "UPDATE	patrimonio_config_interface set 
										in_destacar_duplicidade='".$value."' 
								WHERE 	id_etiqueta='".$key."' AND
										id_local = ".$_SESSION['id_local'];
			$result_etiquetas = mysql_query($query_etiquetas) or die('Ocorreu um erro durante a atualização da tabela patrimonio_config_interface.'); 				
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

$result = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela "configuracoes".'); 
$campos = mysql_fetch_array($result);
?>

<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">

<title>A&ccedil;&otilde;es/Configura&ccedil;&otilde;es</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('nu_intervalo_renovacao_patrim');">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Op&ccedil;&otilde;es da Coleta de Informa&ccedil;&otilde;es 
      Patrimoniais e Localiza&ccedil;&atilde;o F&iacute;sica</td>
  </tr>
  <tr> 
    <td></td>
  </tr>
</table>
<table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">

  <form action="opcoes.php"  method="post" ENCTYPE="multipart/form-data" name="forma" onsubmit="return valida_form()">
    <table width="100%" border="0" cellpadding="0" cellspacing="1">
		<tr>
        <td class="label">Local de Aplicação:</td>	
		</tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
		
      <tr> 
        <td valign="left" class="label"><input name=frm_nm_local type="text" value="<? echo $_SESSION['nm_local'];?>" readonly="yes" size="75"></td>
      </tr>
		<tr><td>&nbsp;</td></tr>			
  	
      <tr> 
        <td class="label">Intervalo de solicita&ccedil;&atilde;o aos usu&aacute;rios 
          de renova&ccedil;&atilde;o das informa&ccedil;&otilde;es de patrim&ocirc;nio 
          e Local f&iacute;sica: </td>
      </tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td><p> 
            <select name="nu_intervalo_renovacao_patrim"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
              <option value="0" <? if ($campos['nu_intervalo_renovacao_patrim'] == '0') echo 'selected'; ?>>N&atilde;o 
              solicitar renova&ccedil;&atilde;o</option>
              <option value="1" <? if ($campos['nu_intervalo_renovacao_patrim'] == '1') echo 'selected'; ?>>1 
              m&ecirc;s</option>
              <option value="2" <? if ($campos['nu_intervalo_renovacao_patrim'] == '2') echo 'selected'; ?>>2 
              meses</option>
              <option value="3" <? if ($campos['nu_intervalo_renovacao_patrim'] == '3') echo 'selected'; ?>>3 
              meses</option>
              <option value="4" <? if ($campos['nu_intervalo_renovacao_patrim'] == '4') echo 'selected'; ?>>4 
              meses</option>
              <option value="5" <? if ($campos['nu_intervalo_renovacao_patrim'] == '5') echo 'selected'; ?>>5 
              meses</option>
              <option value="6" <? if ($campos['nu_intervalo_renovacao_patrim'] == '6') echo 'selected'; ?>>6 
              meses</option>
              <option value="7" <? if ($campos['nu_intervalo_renovacao_patrim'] == '7') echo 'selected'; ?>>7 
              meses</option>
              <option value="8" <? if ($campos['nu_intervalo_renovacao_patrim'] == '8') echo 'selected'; ?>>8 
              meses</option>
              <option value="9" <? if ($campos['nu_intervalo_renovacao_patrim'] == '9') echo 'selected'; ?>>9 
              meses</option>
              <option value="10" <? if ($campos['nu_intervalo_renovacao_patrim'] == '10') echo 'selected'; ?>>10 
              meses</option>
              <option value="11" <? if ($campos['nu_intervalo_renovacao_patrim'] == '11') echo 'selected'; ?>>11 
              meses</option>
              <option value="12" <? if ($campos['nu_intervalo_renovacao_patrim'] == '12') echo 'selected'; ?>>12 
              meses</option>
            </select>
          </p></td>
      </tr>
    </table>
    <table width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr> 
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td valign="middle" class="label"><input name=frm_cs_abre_janela_patr type="checkbox" value="S" <? if ($campos['cs_abre_janela_patr']=='S') echo 'checked';?>>
          Abrir janela de coleta ao detectar altera&ccedil;&otilde;es de localização 
          f&iacute;sica (IP&acute;s de esta&ccedil;&atilde;o e rede)</td>
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
        <td class="label"> Notificar os e-mails abaixo ao detectar altera&ccedil;&otilde;es 
          nas informa&ccedil;&otilde;es de patrim&ocirc;nio e Local 
          f&iacute;sica:</td>
      </tr>
      <tr> 
        <td height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td><p> 
            <textarea name="te_notificar_mudanca_patrim" cols="55" rows="4"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"><? echo $campos['te_notificar_mudanca_patrim']; ?></textarea>
          </p></td>
      </tr>
      <tr> 
        <td class="ajuda">Aten&ccedil;&atilde;o: informe os e-mails separados 
          por v&iacute;rgulas (&quot;,&quot;). <br>
          Exemplo: jose.silva@es.previdenciasocial.gov.br, luis.almeida@xyz.com</td>
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
      <?
			$query_etiquetas = "	SELECT 		id_etiqueta,
												te_etiqueta,
												in_destacar_duplicidade
									FROM 		patrimonio_config_interface
									WHERE		nm_campo_tab_patrimonio like 'te_info_patrimonio%' AND
												id_local = ".$_SESSION['id_local'] . " 									
									ORDER BY	id_etiqueta"; 
									
		$result_etiquetas = mysql_query($query_etiquetas) or die('Ocorreu um erro durante a consulta à tabela de etiquetas.'); 

		if (mysql_num_rows($result_etiquetas)>0)
			{
			?>
      <tr> 
        <td class="label">No Relat&oacute;rio de Patrim&ocirc;nio, destacar as 
          seguintes duplicidades:</td>
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
            <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Configuração para Coleta de Patrimônio?');document.forma.elements['list2[]'].disabled=false; SelectAll(this.form.elements['list2[]']), SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list5[]'])" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
          </div></td>
      </tr>
    </table>
  </form></td></tr>
</table>
</body>
</html>
