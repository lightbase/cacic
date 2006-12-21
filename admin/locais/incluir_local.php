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
include_once "../../include/library.php";
anti_spy();
if($submit) 
{
	Conecta_bd_cacic();
	
	$query = "SELECT 	* 
			  FROM 		locais 
			  WHERE 	sg_local = '$frm_sg_local'";
	$result = mysql_query($query) or die ('Select falhou');
	
	if (mysql_num_rows($result) > 0) 
		{
		header ("Location: ../../include/registro_ja_existente.php?chamador=../admin/locais/index.php&tempo=1");									 							
		}
	else 
		{
		$query = "INSERT 
				  INTO 		locais 
				  			(sg_local, 
				  			nm_local,
				  			te_observacao) 
				  VALUES 	('$frm_sg_local', 
						  	'$frm_nm_local',									  
						  	'$frm_te_observacao')";									  						  
		$result = mysql_query($query) or die ('Falha na Inserção em Locais...');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'locais');		
		
		// Provavelmente uma solução temporária!...
		// Probaly a temporary solution...
		$query = "SELECT 	max(id_local) as max_id_local
				  FROM		locais";
		$result = mysql_query($query) or die ('Falha na Consulta à tabela Locais...');
		$row_max_id_local = mysql_fetch_array($result);
		
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'locais');

		$query = "SELECT	*
				  FROM		configuracoes_padrao";									  						  
		$result = mysql_query($query) or die ('Falha na Consulta à tabela configuracoes_padrao...');
		$row_padrao = mysql_fetch_array($result);
		
		$query = "INSERT
				  INTO 		configuracoes_locais 
				  			(id_local,
							te_enderecos_mac_invalidos,
							te_serv_updates_padrao,
							te_serv_cacic_padrao,
							id_default_body_bgcolor) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",'".
				  			$row_padrao['te_enderecos_mac_invalidos']."','".
							$row_padrao['te_serv_updates_padrao']."','".
							$row_padrao['te_serv_cacic_padrao']."','".
							$row_padrao['id_default_body_bgcolor']."')";

		$result = mysql_query($query) or die ('Falha na Inserção em configuracoes_locais...');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');		
		
		// Insiro as configurações padrão para as 9 etiquetas da janela de Informações Patrimoniais, que surge para o usuário preencher...
		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta1', 'Etiqueta 1', 'Entidade', '', 'Selecione a Entidade', 'Entidades',	'id_unid_organizacional_nivel1','N')";

		$result = mysql_query($query) or die ('Falha na Inserção em patrimonio_config_interface (1)...');

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta2', 'Etiqueta 2', 'Órgão', '', 'Selecione o Órgão', 'Órgãos',	'id_unid_organizacional_nivel2','N')";
		$result = mysql_query($query) or die ('Falha na Inserção em patrimonio_config_interface (2)...');

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta3', 'Etiqueta 3', 'Seção', '', 'Informe a Seção onde está instalado o equipamento', '','te_localizacao_complementar','N')";
		$result = mysql_query($query) or die ('Falha na Inserção em patrimonio_config_interface (3)...');

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta4', 'Etiqueta 4', 'PIB da CPU', 'S', 'Informe o número de PIB(tombamento) da CPU', '','te_info_patrimonio1','S')";
		$result = mysql_query($query) or die ('Falha na Inserção em patrimonio_config_interface (4)...');

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta5', 'Etiqueta 5', 'PIB do monitor', 'S', 'Informe o número de PIB(tombamento) do monitor', '','te_info_patrimonio2','S')";
		$result = mysql_query($query) or die ('Falha na Inserção em patrimonio_config_interface (5)...');

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta6', 'Etiqueta 6', 'PIB da impressora', 'S', 'Caso haja uma impressora conectada, informe o nº de PIB(tombamento)', '','te_info_patrimonio3','S')";
		$result = mysql_query($query) or die ('Falha na Inserção em patrimonio_config_interface (6)...');

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta7', 'Etiqueta 7', 'Nº série CPU', 'S', 'Caso não disponha do nº de PIB, informe o nº de série da CPU', '','te_info_patrimonio4','S')";
		$result = mysql_query($query) or die ('Falha na Inserção em patrimonio_config_interface (7)...');

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta8', 'Etiqueta 8', 'Nº série Monitor', 'S', 'Caso não disponha do nº de PIB, informe o nº de série do Monitor', '','te_info_patrimonio5','S')";
		$result = mysql_query($query) or die ('Falha na Inserção em patrimonio_config_interface (8)...');

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta9', 'Etiqueta 9', 'Nº série Impres. (opcional)', 'S', 'Caso haja uma impressora conectada ao micro e não disponha do nº de PIB, informe o nº de série', '','te_info_patrimonio6','S')";
		$result = mysql_query($query) or die ('Falha na Inserção em patrimonio_config_interface (9)...');


		// Inserção do Local na tabela de ações por Sistema Operacional
		$query_so = "SELECT 	* 
					 FROM 		so";
		$result_so = mysql_query($query_so) or die('Ocorreu um erro durante a consulta à tabela de Sistemas Operacionais.'); 
					
		$query_acoes = "SELECT 	* 
						FROM 	acoes";
		$result_acoes = mysql_query($query_acoes) or die('Ocorreu um erro durante a consulta à tabela de ações.'); 
					
		while ($row_acoes = mysql_fetch_array($result_acoes))
			{
			mysql_data_seek($result_so,0);
			while ($row_so = mysql_fetch_array($result_so))
				{			
				$query_ins = "INSERT 
							  INTO 		acoes_so 
										(id_local, 
										id_acao, 
										id_so) 
							  VALUES	(".$row_max_id_local['max_id_local'].", 
										'".$row_acoes['id_acao']."',
										'".$row_so['id_so']."')";
				mysql_query($query_ins) or die('Ocorreu um erro durante a inclusão de registros na tabela acoes_so.');
				}
			
			}
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_so');									
		
	    header ("Location: index.php");		
		}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
}
else 
{
?>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Inclus&atilde;o de Local</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">

function valida_form() 
	{

	if ( document.form.frm_sg_local.value == "" ) 
		{	
		alert("A sigla é obrigatória.");
		document.form.frm_sg_local.focus();
		return false;
		}
		
	else if ( document.form.frm_nm_local.value == "" ) 
		{	
		alert("O nome do Local é obrigatório.");
		document.form.frm_nm_local.focus();
		return false;
		}
	return true;		
	}
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_sg_local');">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Inclus&atilde;o 
      de Local</td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es que dever&atilde;o ser 
      cadastradas abaixo referem-se a um local originário de chamada ao 
      sistema CACIC. Como por exemplo uma regi&atilde;o, um estado, um &oacute;rg&atilde;o, 
      etc. </td>
  </tr>
</table>
<form action="incluir_local.php"  method="post" ENCTYPE="multipart/form-data" name="form" onsubmit="return valida_form()">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td class="label" colspan="2"><br>
        Sigla do Local:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr> 
      <td class="label_peq_sem_fundo"> <input name="frm_sg_local" type="text" size="20" maxlength="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >&nbsp;&nbsp;Ex.: DTP - UAES</td>
    </tr>
    <tr> 
      <td class="label" colspan="2"><div align="left"><br>
          Descri&ccedil;&atilde;o:</div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr> 
      <td nowrap colspan="2"><input name="frm_nm_local" type="text" id="frm_nm_local" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
    <tr> 
      <td class="label" colspan="2"><br>
        Observa&ccedil;&otilde;es:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr> 
      <td><textarea name="frm_te_observacao" cols="75" id="textarea" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
  <p align="center"> 
    <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Inclusão de Local?');">
  </p>
</form>
<p>
  <?
}
?>
</p>
</body>
</html>
