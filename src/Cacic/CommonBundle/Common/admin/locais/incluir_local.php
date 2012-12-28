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

include_once "../../include/library.php";
AntiSpy('1,2'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administra��o
// 2 - Gest�o Central


if($_POST['submit']<>'') 
{
	Conecta_bd_cacic();
	
	$query = "SELECT 	* 
			  FROM 		locais 
			  WHERE 	sg_local = '".$_POST['frm_sg_local']."'";
	$result = mysql_query($query) or die ($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('Locais')));
	
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
				  VALUES 	('".$_POST['frm_sg_local']."', 
						  	 '".$_POST['frm_nm_local']."',									  
						  	 '".$_POST['frm_te_observacao']."')";									  						  
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('Locais')));
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'locais');		
		
		// Provavelmente uma solu��o tempor�ria!...
		// Probaly a temporary solution...
		$query = "SELECT 	max(id_local) as max_id_local
				  FROM		locais";
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('Locais')));
		$row_max_id_local = mysql_fetch_array($result);
		
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'locais');

		$query = "SELECT	*
				  FROM		configuracoes_padrao";									  						  
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('configuracoes_padrao')));
		$row_padrao = mysql_fetch_array($result);
		
		$query = "INSERT
				  INTO 		configuracoes_locais 
				  			(id_local,
							te_enderecos_mac_invalidos,
							te_serv_updates_padrao,
							te_serv_cacic_padrao,
							te_senha_adm_agente,
							id_default_body_bgcolor,
							te_exibe_graficos) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",'".
				  			$row_padrao['te_enderecos_mac_invalidos']."','".
							$row_padrao['te_serv_updates_padrao']."','".
							$row_padrao['te_serv_cacic_padrao']."','".
							$row_padrao['te_senha_adm_agente']."','".
							$row_padrao['id_default_body_bgcolor']."','".
							$row_padrao['te_exibe_graficos']."')";
								
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('configuracoes_locais')));
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');		
		
		// Insiro as configura��es padr�o para as 9 etiquetas da janela de Informa��es Patrimoniais, que surge para o usu�rio preencher...
		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta1', 'Etiqueta 1', 'Entidade', '', 'Selecione a Entidade', 'Entidades',	'id_unid_organizacional_nivel1','N')";

		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (1)')));

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta1a', 'Etiqueta 1a', 'Entidade', '', 'Selecione a Linha de Neg�cio', 'Linhas de Neg�cio',	'id_unid_organizacional_nivel1a','N')";

		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (1a)')));

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta2', 'Etiqueta 2', '�rg�o', '', 'Selecione o �rg�o', '�rg�os',	'id_unid_organizacional_nivel2','N')";
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (2)')));

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta3', 'Etiqueta 3', 'Se��o', '', 'Informe a Se��o onde est� instalado o equipamento', '','te_localizacao_complementar','N')";
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (3)')));

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta4', 'Etiqueta 4', 'PIB da CPU', 'S', 'Informe o n�mero de PIB(tombamento) da CPU', '','te_info_patrimonio1','S')";
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (4)')));

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta5', 'Etiqueta 5', 'PIB do monitor', 'S', 'Informe o n�mero de PIB(tombamento) do monitor', '','te_info_patrimonio2','S')";
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (5)')));

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta6', 'Etiqueta 6', 'PIB da impressora', 'S', 'Caso haja uma impressora conectada, informe o n� de PIB(tombamento)', '','te_info_patrimonio3','S')";
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (6)')));

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta7', 'Etiqueta 7', 'N� s�rie CPU', 'S', 'Caso n�o disponha do n� de PIB, informe o n� de s�rie da CPU', '','te_info_patrimonio4','S')";
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (7)')));

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta8', 'Etiqueta 8', 'N� s�rie Monitor', 'S', 'Caso n�o disponha do n� de PIB, informe o n� de s�rie do Monitor', '','te_info_patrimonio5','S')";
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (8)')));

		$query = "INSERT 	INTO 		patrimonio_config_interface 
				  			(id_local, id_etiqueta, nm_etiqueta, te_etiqueta, in_exibir_etiqueta, te_help_etiqueta, te_plural_etiqueta, nm_campo_tab_patrimonio, in_destacar_duplicidade) 
				  VALUES 	(".$row_max_id_local['max_id_local'].",	'etiqueta9', 'Etiqueta 9', 'N� s�rie Impres. (opcional)', 'S', 'Caso haja uma impressora conectada ao micro e n�o disponha do n� de PIB, informe o n� de s�rie', '','te_info_patrimonio6','S')";
		$result = mysql_query($query) or die ($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('patrimonio_config_interface (9)')));


		// Inser��o do Local na tabela de a��es por Sistema Operacional
		$query_so = "SELECT 	* 
					 FROM 		so";
		$result_so = mysql_query($query_so) or die($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('Sistemas Operacionais'))); 
					
		$query_acoes = "SELECT 	* 
						FROM 	acoes";
		$result_acoes = mysql_query($query_acoes) or die($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('acoes'))); 
					
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
				mysql_query($query_ins) or die($oTranslator->_('Falha na Insercao em (%1) ou sua sessao expirou!',array('acoes_so')));
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
<title><?=$oTranslator->_('Inclusao de Local');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">

function valida_form() 
	{

	if ( document.form.frm_sg_local.value == "" ) 
		{	
		alert("A sigla � obrigat�ria.");
		document.form.frm_sg_local.focus();
		return false;
		}
		
	else if ( document.form.frm_nm_local.value == "" ) 
		{	
		alert("O nome do Local � obrigat�rio.");
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
    <td class="cabecalho"><?=$oTranslator->_('Inclusao de Local');?></td>
  </tr>
  <tr> 
    <td class="descricao">
      <?=$oTranslator->_('Inclusao de Local help');?>
    </td>
  </tr>
</table>
<form action="incluir_local.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
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
          <?=$oTranslator->_('Descricao');?></div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr> 
      <td nowrap colspan="2"><input name="frm_nm_local" type="text" id="frm_nm_local" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
    <tr> 
      <td class="label" colspan="2"><br>
        <?=$oTranslator->_('Observacoes');?></td>
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
    <input name="submit" type="submit" value="<?=$oTranslator->_('Gravar Informacoes');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma Inclusao de Local?');?>');">
  </p>
</form>
<p>
  <?
}
?>
</p>
</body>
</html>
