<?
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

if($_POST['submit']) {
	$_SESSION["list6"] = $_POST['list6']; //Softwares selecionados
}

require_once('../../include/library.php');

conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';

?>

<?
/*
	if ($_SESSION["nm_grupo_usuarios"] <> "adm1")
		die("<h1><font color='red'>Acesso n&atilde;o autorizado!</font></h1>
			 <h3>Sua tentativa foi registrada no log!</h3>
		     <b>Nome:</b> " . $_SESSION["nm_usuario"] );
*/			 
?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$oTranslator->_('Exclusao de Softwares');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
    <strong>CACIC - <?=$oTranslator->_('Exclusao de Softwares');?></strong></font>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
        <?=$oTranslator->_('Gerado em');?> <? echo date("d/m/Y �\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br><?

$mensagemErro = '';
$v_apaguei = '';
if (count($_SESSION["list6"]) == 0) {
	$mensagemErro = $oTranslator->_('Selecione pelo menos um software').'<BR>';
} else
	{
	//Pego os aplicativos selecionados para o relat�rio
	$v_apaguei = 'sim';
	$aplicativos_selecionados = $_SESSION["list6"][0] ;
	for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) {
		$aplicativos_selecionados = $aplicativos_selecionados .", ". $_SESSION["list6"][$i] ;
	}
	
	// Exibir as aplica��es	 baseado no que foi solicitado	
	$query_select = 'DELETE FROM softwares_inventariados
			 WHERE id_software_inventariado IN (' .$aplicativos_selecionados.')';
							 
	$result_query_selecao = mysql_query($query_select);
}

?>
<table width="62%" border="0" align="center">
  <tr> 
    <td><div align="center"></div>
      <table width="98%" border="0" align="center">
        <tr valign="top"> 
          <td nowrap > <table width="100%" border="0" align="center">
              <? 

	 	if ($v_apaguei=='')
 		{
		echo '<tr><td colspan="2" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><B>' . $mensagemErro . '</B></td></tr>';
		} else {
		echo '<tr><td colspan="2" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><B>'.
		     $oTranslator->_('Software(s) deletado(s) com sucesso da base de dados.').
             '</B></td></tr>';
		}

?>
        </table></td>
        </tr>
      </table>
 
 </tr>  
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?=$oTranslator->_('Gerado por');?> <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
