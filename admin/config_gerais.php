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

<body background="../imgs/linha_v.gif" onLoad="SetaCampo('nm_organizacao');">
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
	$query = "SELECT	loc.nm_local,
						conf.*
			  FROM		locais loc,
			  			configuracoes_locais conf
			  WHERE		conf.id_local = ".$_SESSION['id_local']." AND
			  			conf.id_local = loc.id_local"; 

	$result_configuracoes = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela de configurações.'); 
	$campos_configuracoes = mysql_fetch_array($result_configuracoes);
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
		   
          <input name="nm_organizacao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" type="text" value="<? echo $campos_configuracoes['nm_local']; ?>" size="65" disabled>
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
          <textarea name="te_notificar_mudanca_hardware" cols="55"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" id="te_notificar_mudanca_hardware"><? echo $campos_configuracoes['te_notificar_mudanca_hardware']; ?></textarea>
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
						$result_hardwares_nao_selecionados = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela descricao_hardware.');
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
            <td height="1" class="ajuda">&nbsp;&nbsp;&nbsp;(Dica: use SHIFT or 
              CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
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
			//Conecta_bd_cacic();
			//$query = "SELECT DISTINCT 	te_serv_cacic_padrao
			//          FROM   			configuracoes_padrao";
			//mysql_query($query) or die('Select falhou');
		    //$sql_result=mysql_query($query);
			reset($result_configuracoes);			
			$v_achei = 0;
		while ($row=mysql_fetch_array($result_configuracoes))
			{ 
			$v_achei = 1;
			echo "<option value=\"" . $row["te_serv_cacic"] . "\"";
			if ($campos_configuracoes['te_serv_cacic_padrao'] == $row["te_serv_cacic_padrao"]) echo " selected ";
			echo ">" . $campos_configuracoes["te_serv_cacic_padrao"] . "</option>";
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
			Conecta_bd_cacic();
			$query = "SELECT DISTINCT 	configuracoes_locais.te_serv_updates_padrao, 
										redes.te_serv_updates
			          FROM   			redes LEFT JOIN configuracoes_locais on (configuracoes_locais.te_serv_updates_padrao = redes.te_serv_updates)
					  WHERE  			trim(redes.te_serv_updates) <> '' 
					  ORDER  BY 		configuracoes_locais.te_serv_updates_padrao";
			mysql_query($query) or die('Select falhou');
		    $sql_result=mysql_query($query);			
		while ($row=mysql_fetch_array($sql_result))
			{ 
			echo "<option value=\"" . $row["te_serv_updates"] . "\"";
			if ($campos_configuracoes['te_serv_updates_padrao'] == $row["te_serv_updates_padrao"]) echo " selected ";
			echo ">" . $campos_configuracoes["te_serv_updates_padrao"] . "</option>";
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
          <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es   " onClick="SelectAll(this.form.elements['list2[]'])">
        </div></td>
    </tr>
  </table>
</form>		  
<p>&nbsp;</p>
</body>
</html>
