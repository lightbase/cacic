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
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
require_once('../include/selecao_listbox.js');  
?>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<SCRIPT LANGUAGE="JavaScript">
function SetaServidorUpdates()	
	{
	document.form.frm_te_serv_updates_padrao.value = document.form.sel_te_serv_updates.value;	
	}
</script>

</head>

<body background="../imgs/linha_v.gif" onLoad="SetaCampo('te_notificar_mudanca_hardware');">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
	<form action="config_gerais_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma">
<table width="90%" border="0" align="center">

  <tr> 
      <td class="cabecalho">Configura&ccedil;&otilde;es do M&oacute;dulo Gerente</td>
  </tr>
  <tr> 
      <td class="descricao">As op&ccedil;&otilde;es abaixo determinam como o m&oacute;dulo gerente dever&aacute; se comportar.</td>
  </tr>
</table>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td class="label"> 
        <? 
   	require_once('../include/library.php');
	
	// Comentado temporariamente - AntiSpy();			
    conecta_bd_cacic();
	$query_configuracoes_locais = "SELECT	loc.nm_local,
											conf.*
								  FROM		locais loc,
								  			configuracoes_locais conf
								  WHERE		conf.id_local = ".$_SESSION['id_local']." AND
								  			conf.id_local = loc.id_local"; 

	$result_configuracoes_locais = mysql_query($query_configuracoes_locais) or die('Ocorreu um erro durante a consulta à tabela de configurações ou sua sessão expirou!'); 
	$row_configuracoes_locais = mysql_fetch_array($result_configuracoes_locais);
	?>
        &nbsp; &nbsp;<br>
        Nome da organiza&ccedil;&atilde;o/empresa/&oacute;rg&atilde;o: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
          <? // Atenção: o campo abaixo deve estar em "disabled", pois, a alteração desse valor só será permitida ao nível 
		   //          Administração, na opção Administração/Cadastros/Locais ?>
          <input name="nm_organizacao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" type="text" value="<? echo $row_configuracoes_locais['nm_local']; ?>" size="65" disabled>
        </p></td>
    </tr>
    <tr> 
      <td height="17">&nbsp;</td>
    </tr>
    <tr> 
      <td class="label">Notificar os seguintes e-mails ao detectar altera&ccedil;&otilde;es 
        nas configura&ccedil;&otilde;es de hardware: </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
          <textarea name="te_notificar_mudanca_hardware" cols="55"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="te_notificar_mudanca_hardware"><? echo $row_configuracoes_locais['te_notificar_mudanca_hardware']; ?></textarea>
        </p></td>
    </tr>
    <tr> 
      <td class="ajuda">Aten&ccedil;&atilde;o: informe os e-mails separados por 
        v&iacute;rgulas (&quot;,&quot;). <br>
        Exemplo: jose.silva@es.previdenciasocial.gov.br, luis.almeida@xyz.com</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">Realizar notifica&ccedil;&atilde;o caso haja altera&ccedil;&otilde;es 
              nas seguintes configura&ccedil;&otilde;es de hardware: </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td height="1" class="label"><table border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="label"><div align="left">Dispon&iacute;veis:</div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="label"><p>Selecionadas:</p></td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <?    
				        /* Consulto todos os hardwares que foram previamente selecionados. */ 
			  	$query = "SELECT nm_campo_tab_hardware, te_desc_hardware
								  FROM descricao_hardware 
										WHERE cs_notificacao_ativada = '1'";
						$result_hardwares_ja_selecionados = mysql_query($query);//  or die('Ocorreu um erro durante a consulta à tabela descricao_hardware.');
						
						/* Agora monto os itens do combo de hardwares selecionadas. */ 
						while($campos_hardwares_selecionados = mysql_fetch_array($result_hardwares_ja_selecionados)) {
						   $itens_combo_hardwares_selecionados = $itens_combo_hardwares_selecionados . '<option value="' . $campos_hardwares_selecionados['nm_campo_tab_hardware']. '">' . $campos_hardwares_selecionados['te_desc_hardware'] . '</option>'; 
//						   $not_in_ja_selecionados = $not_in_ja_selecionados . "'" . $campos_hardwares_selecionados['nm_campo_tab_hardware'] .  "',";
						}
						
						/* Consulto as hardwares que não foram previamente selecionadas. */ 
			  	$query = "SELECT nm_campo_tab_hardware, te_desc_hardware
								  FROM descricao_hardware 
										WHERE cs_notificacao_ativada <> '1'";
						$result_hardwares_nao_selecionados = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela descricao_hardware ou sua sessão expirou!');
						/* Agora monto os itens do combo de hardwares NÃO selecionadas. */ 
       while($campos_hardwares_nao_selecionados=mysql_fetch_array($result_hardwares_nao_selecionados)) 	{
						   $itens_combo_hardwares_nao_selecionados = $itens_combo_hardwares_nao_selecionados . '<option value="' . $campos_hardwares_nao_selecionados['nm_campo_tab_hardware']. '">' . $campos_hardwares_nao_selecionados['te_desc_hardware']  . '</option>';
						}  ?>
                      <select multiple size="10" name="list1[]"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <? echo $itens_combo_hardwares_nao_selecionados; ?> 
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
                  <td><select multiple size="10" name="list2[]"   class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
                      <? echo $itens_combo_hardwares_selecionados; ?> </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td height="1" class="ajuda">&nbsp;&nbsp;&nbsp;(Dica: use SHIFT ou 
              CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td class="label">Exibir Gr&aacute;ficos na P&aacute;gina Principal e Detalhes:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td height="1" class="label"><table border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td>&nbsp;&nbsp;</td>
            <td class="label"><div align="left">Dispon&iacute;veis:</div></td>
            <td>&nbsp;&nbsp;</td>
            <td width="40">&nbsp;</td>
            <td nowrap>&nbsp;&nbsp;</td>
            <td nowrap class="label"><p>Selecionados:</p></td>
            <td nowrap>&nbsp;&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <div align="left"> 
			<?
			// Gráficos disponíveis para exibição na página principal
			// [so][acessos][locais][acessos_locais]
			// A variável de sessão menu_seg->_SESSION['te_exibe_graficos'] contém os gráficos selecionados para exibição
			$te_exibe_graficos = get_valor_campo('configuracoes_locais', 'te_exibe_graficos', 'id_local='.$_SESSION['id_local']);			
//	echo 'te_exibe_graficos='.$te_exibe_graficos.'<br>';			
			?>
                <select multiple size="10" name="list3[]"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
					<? if (substr_count($te_exibe_graficos,'[so]')==0)
							echo '<option value="[so]">Totais de Computadores por Sistemas Operacionais</option>';
					   if (substr_count($te_exibe_graficos,'[acessos]')==0)
		                    echo '<option value="[acessos]">&Uacute;ltimos Acessos dos Agentes do Local</option>';
					   if (substr_count($te_exibe_graficos,'[acessos_locais]')==0)
					   		echo '<option value="[acessos_locais]">&Uacute;ltimos Acessos dos Agentes por Local na Data</option>';
					   if (substr_count($te_exibe_graficos,'[locais]')==0)							
		                    echo '<option value="[locais]">Totais de Computadores Monitorados por Local</option>';
					?>
                </select>
              </div></td>
            <td>&nbsp;</td>
            <td width="40"> <div align="center"> 
                <input type="button" value="   &gt;   " onClick="move(this.form.elements['list3[]'],this.form.elements['list4[]'])" name="B3">
                <br>
                <br>
                <input type="button" value="   &lt;   " onClick="move(this.form.elements['list4[]'],this.form.elements['list3[]'])" name="B4">
              </div></td>
            <td>&nbsp;</td>
            <td><select multiple size="10" name="list4[]"   class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
					<? if (substr_count($te_exibe_graficos,'[so]')>0)
							echo '<option value="[so]">Totais de Computadores por Sistemas Operacionais</option>';
					   if (substr_count($te_exibe_graficos,'[acessos]')>0)
		                    echo '<option value="[acessos]">&Uacute;ltimos Acessos dos Agentes do Local</option>';
					   if (substr_count($te_exibe_graficos,'[acessos_locais]')>0)
					   		echo '<option value="[acessos_locais]">&Uacute;ltimos Acessos dos Agentes por Local na Data</option>';
					   if (substr_count($te_exibe_graficos,'[locais]')>0)							
		                    echo '<option value="[locais]">Totais de Computadores Monitorados por Local</option>';
					?>

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
      <td class="label">Servidor de Aplica&ccedil;&atilde;o padr&atilde;o:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p><strong> 
          <select name="frm_te_serv_cacic_padrao" id="frm_te_serv_cacic_padrao" onChange="SetaServidorBancoDadosPadrao();"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
            <option value="0">===> Selecione <===</option>
        <?
		$query_configuracoes_padrao = "SELECT	Distinct te_serv_cacic_padrao,
		                                        		 te_serv_updates_padrao
						 			   FROM		configuracoes_padrao"; 

		$result_configuracoes_padrao = mysql_query($query_configuracoes_padrao) or die('Ocorreu um erro durante a consulta à tabela de configurações ou sua sessão expirou!'); 
		
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
      <td class="label">Servidor de Updates padr&atilde;o:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><p> 
          <select name="frm_te_serv_updates_padrao" id="frm_te_serv_updates_padrao" onChange="SetaServidorUpdatesPadrao();"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
            <option value="0">===> Selecione <===</option>
            <?
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
    <tr> 
      <td><div align="center"> 
          <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es   " onClick="SelectAll(this.form.elements['list2[]']),SelectAll(this.form.elements['list4[]'])" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
        </div></td>
    </tr>
  </table>
</form>		  
<p>&nbsp;</p>
</body>
</html>
