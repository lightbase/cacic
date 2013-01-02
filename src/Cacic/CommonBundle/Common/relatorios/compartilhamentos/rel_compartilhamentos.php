<?
/*
Este relat�rio foi baseado nos seguintes relat�rios originais do CACIC:
antivirus e computadores.
As modifica��es foram feitas por Emerson Pellis (epellis@unerj.br).
*/
?>
<? session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

if($_POST['submit']) {
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list6"] = $_POST['list6'];
	$_SESSION["list8"] = $_POST['list8'];
	$_SESSION["list12"] = $_POST['list12'];		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
}
require_once('../../include/library.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$oTranslator->_('Relatorio de pastas compartilhadas');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<!-- Corrigir caminho absoluto abaixo...//-->
<link rel="stylesheet"   type="text/css" href="/cacic2/include/cacic.css">
<style type="text/css">
       TR {font-size:10pt ; font-family: Verdana, Arial}
</style>

</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
	
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
      <strong><?=$oTranslator->_('Relatorio de pastas compartilhadas');?></strong></font>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?=$oTranslator->_('Gerado em');?> 
        <? echo date("d/m/Y �\s H:i\h"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 
//Corrigir caminho absoluto abaixo
require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php');
conecta_bd_cacic();

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	//if($_SESSION["cs_situacao"] == 'S') 
		//{
		// Aqui pego todas as redes selecionadas e fa�o uma query p/ condi��o de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

		$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
		//}	
	}
else
	{
	// Aqui pego todos os locais selecionados e fa�o uma query p/ condi��o de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";

	$query_redes = 'AND computadores.id_ip_rede = redes.id_ip_rede AND 
						redes.id_local IN ('. $locais_selecionados .') AND
						redes.id_local = locais.id_local ';
	$select = ' ,sg_local as Local ';	
	$from = ' ,redes,locais ';			
	}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) {
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
}
$query = "SELECT DISTINCT 	compartilhamentos.id_so, 
							compartilhamentos.te_node_address, 
							computadores.te_node_address, 
							computadores.te_nome_computador, 
							so.sg_so, 
							computadores.te_ip 
							$select
          FROM 				computadores, 
		  					compartilhamentos, 
							so
							$from
          WHERE 			(compartilhamentos.te_node_address =computadores.te_node_address AND 
		  					compartilhamentos.id_so = computadores.id_so) AND 
							compartilhamentos.id_so IN ($so_selecionados) AND  
							nm_dir_compart <> '' 
							$query_redes
		  GROUP BY          computadores.te_nome_computador,compartilhamentos.id_so,compartilhamentos.te_node_address 
		  ORDER BY 			computadores.te_nome_computador ASC ";
          
$resultado = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('computadores')));

while ($linha = mysql_fetch_array($resultado))
{

       $NOME_COMPUTADOR = $linha['te_nome_computador'];

       $SO = $linha['id_so'];
       $MAC = $linha['te_node_address'];
       $SO_NOME = $linha['sg_so'];
       $IP =  $linha['te_ip'];
       
       $query = "SELECT * FROM compartilhamentos
	             WHERE te_node_address = '$MAC' AND id_so = '$SO'";

       $result_compartilhamento = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('compartilhamentos')));

                              echo" <table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
                                        <tr>
                                            <td>
                                                <font size='2' face='Verdana, Arial'>
                                                <a href=../computador/computador.php?te_node_address=$MAC&id_so=$SO target=_blank>
                                                <B>$NOME_COMPUTADOR - $SO_NOME - $IP</B></a>
                                            </td>
                                        </tr>
                                        </table>";

									if(mysql_num_rows($result_compartilhamento) > 0) {
										echo "
													<table width=\"98%\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
                                                    <tr bgcolor=\"#E1E1E1\">";

										if( mysql_result($result_compartilhamento, 0, "id_so") <= 5 ) {
											echo '<td nowrap rowspan="2" class="opcao_tabela"><div align="center">&nbsp;</div></td>
												  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Nome').'</div></td>
												  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Diretorio').'</div></td>
												  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Comentario').'</div></td>
												  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Tipo').'</div></td>';
										}
										else {
											echo '<td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Nome').'</div></td>
												  <td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Diretorio').'</div></td>
												  <td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Comentario').'</div></td>
												  <td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Tipo').'</div></td>';
										}

										if( mysql_result($result_compartilhamento, 0, "id_so") <= 5 ) {
											echo '	<td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Permissao').'</div></td>
													<td nowrap colspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Senha').'</div></td>
													</tr>
													<tr bgcolor="#E1E1E1">
													<td nowrap  bgcolor="#E1E1E1" class="opcao_tabela"><div align="center">'.$oTranslator->_('Leitura').'</div></td>
													<td nowrap  bgcolor="#E1E1E1" class="opcao_tabela"><div align="center">'.$oTranslator->_('Gravacao').'</div></td>
													</tr>';
										}
										else
											echo '</tr>';
										if( mysql_result($result_compartilhamento, 0, "id_so") <= 5 ) {
											$result_compartilhamento = mysql_query($query);
											while($row = mysql_fetch_assoc($result_compartilhamento)) {
													$img_alerta == '&nbsp;';
													if ($row['cs_tipo_compart'] == 'D')
													$tipo_compart = '<img src="/cacic2/imgs/compart_dir.gif" title="'.$oTranslator->_('Compartilhamento de diretorio').'">';
													else
													$tipo_compart = '<img src="/cacic2/imgs/compart_print.gif" title="'.$oTranslator->_('Compartilhamento de impressora').'">';

													if( $row['in_senha_leitura'] == 1 )
														$senha_leitura = '<img src="/cacic2/imgs/checked.gif">';
													else {
														$senha_leitura = '<img src="/cacic2/imgs/unchecked.gif">';
													$img_alerta = '<img src="/cacic2/imgs/alerta_amarelo.gif" title="'.$oTranslator->_('Risco medio: privacidade').'" width="8" height="8">';
													}

													if( $row['in_senha_escrita'] == 1 )
														$senha_escrita = '<img src="/cacic2/imgs/checked.gif">';
													else {
														$senha_escrita = '<img src="/cacic2/imgs/unchecked.gif">';
													$img_alerta = '<img src="/cacic2/imgs/alerta_vermelho.gif" title="'.$oTranslator->_('Risco alto: integridade e privacidade').'" width="8" height="8">';
													}

													if( $row['cs_tipo_permissao'] == 'L' )
														$tipo_permissao = 'Leitura';
													if( $row['cs_tipo_permissao'] == 'G' )
														$tipo_permissao = 'Grava�ao';
													if( $row['cs_tipo_permissao'] == 'D' )
														$tipo_permissao = 'Depende de Senha';

													echo '<tr>
														<td nowrap align="center" class="opcao_tabela">'. $img_alerta .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. $row['nm_compartilhamento'] .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. quebra_linha(strtolower($row['nm_dir_compart']), 32) .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. capa_string($row['te_comentario'], 28) .'</td>
														<td nowrap align="center" class="opcao_tabela">'. $tipo_compart .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. $tipo_permissao .'</td>
														<td nowrap align="center" class="opcao_tabela">'. $senha_leitura .'</td>
														<td nowrap align="center" class="opcao_tabela">'. $senha_escrita .'</td>
																</tr>';
											}
										}
										else {
											$result_compartilhamento = mysql_query($query);
											while($row = mysql_fetch_assoc($result_compartilhamento)) {
													if ($row['cs_tipo_compart'] == 'D')
													$tipo_compart = '<img src="/cacic2/imgs/compart_dir.gif" title="'.$oTranslator->_('Compartilhamento de diretorio').'">';
													else
													$tipo_compart = '<img src="/cacic2/imgs/compart_print.gif" title="'.$oTranslator->_('Compartilhamento de impressora').'">';

													echo '<tr>
														<td nowrap class="dado">&nbsp;'. $row['nm_compartilhamento'] .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. quebra_linha(strtolower($row['nm_dir_compart']), 68) .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. capa_string($row['te_comentario'], 28) .'</td>
														<td nowrap align="center" class="opcao_tabela">'. $tipo_compart .'</td>
																</tr>';
											}
										}
										echo '</table></td></tr>';
									}
								// FIM DA EXIBI��O DE INFORMA��ES DOS COMPARTILHAMENTOS DO COMPUTADOR
}
?>
<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?=$oTranslator->_('Gerado por');?> <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo
  </font>
  </p>
</body>
</html>
