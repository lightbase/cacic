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
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if($_POST['submit']) {
	$_SESSION["list2"] = $_POST['list2']; //Redes selecionadas
	$_SESSION["list4"] = $_POST['list4']; //SO selecionados
	$_SESSION["list6"] = $_POST['list6']; //Variáveis selecionadas
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12'];		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
}

require_once('../../include/library.php');
// Comentado temporariamente - AntiSpy();

conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>'.
			($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2?'<td height="1"></td>':'').
         '</tr>';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Vari&aacute;veis de Ambiente</title>
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
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF" class="cabecalho_rel">CACIC 
      - Relat&oacute;rio de Vari&aacute;veis de Ambiente</td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td class="descricao_rel"><p align="left">Gerado em <? echo date("d/m/Y à\s H:i"); ?></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br><?

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	//if($_SESSION["cs_situacao"] == 'S') 
		//{
		// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

		$_SESSION['query_redes'] = 'AND computadores.id_ip_rede IN ('. $redes_selecionadas .')';		
		
		//}	

	}
else
	{
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";

	$_SESSION['query_redes'] = 'AND computadores.id_ip_rede = redes.id_ip_rede AND 
						redes.id_local IN ('. $locais_selecionados .') AND
						redes.id_local = locais.id_local ';
	$_SESSION['select'] = ' ,sg_local as Local ';	
	$_SESSION['from'] = ' ,redes,locais ';			
	}
$_SESSION["redes_selecionadas"] = $_SESSION['query_redes'];		

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) {
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
}
	$_SESSION["so_selecionados"] = $so_selecionados;

//Pego os aplicativos selecionados para o relatório
$v_achei = '';
$aplicativos_selecionados = $_SESSION["list6"][0] ;
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) {
	$aplicativos_selecionados = $aplicativos_selecionados .", ". $_SESSION["list6"][$i] ;
	}

	// Exibir as aplicações	 baseado no que foi solicitado	
			$query_select = 'SELECT 	id_variavel_ambiente,  
										nm_variavel_ambiente 
							 FROM 		variaveis_ambiente
							 WHERE 		id_variavel_ambiente IN (' .$aplicativos_selecionados.')
							 ORDER BY 	nm_variavel_ambiente';
							 
			$result_query_selecao = mysql_query($query_select);
?>
<table width="62%" border="0" align="center">
  <tr> 
    <td><div align="center"></div>
      <table width="98%" border="0" align="center">
        <tr valign="top"> 
          <td nowrap > <table width="58%" border="0" align="center">
              <tr valign="top" bgcolor="#E1E1E1"> 
                <td nowrap class="cabecalho_tabela"><div align="left">Vari&aacute;veis de Ambiente</div></td>
                <td nowrap bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right">M&aacute;quinas</div></td>
				<?
  				if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
					{
					?>
					<td nowrap bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right">Local</div></td>				
					<?
					}
					?>
              </tr>
              <? 
			  while($reg_selecao = @mysql_fetch_row($result_query_selecao))
					{
	 				$reg_id_aplicativo = $reg_selecao[0]; 	
					// Exibir informações sobre a quantidade de máquinas e os variáveis de ambiente
					$query_aplicativo = 
					"SELECT DISTINCT 	a.nm_variavel_ambiente, 
										COUNT(a.id_variavel_ambiente) as total_equip, 
										a.id_variavel_ambiente ".
										$_SESSION['select'] . " 
					FROM  				variaveis_ambiente a, 
										variaveis_ambiente_estacoes b, 
										computadores ".
										$_SESSION['from'] . " 
					WHERE 				b.te_node_address  = computadores.te_node_address AND 
										b.id_so = computadores.id_so AND 
										a.id_variavel_ambiente = ".$reg_id_aplicativo. " AND 
										a.id_variavel_ambiente = b.id_variavel_ambiente " . $_SESSION['query_redes'] . " AND 
										computadores.id_so IN (". $so_selecionados .") ".
										$_SESSION['query_redes'] . " 
					GROUP BY 			a.nm_variavel_ambiente
					ORDER BY 			a.nm_variavel_ambiente";

					$result_query_versoes = mysql_query($query_aplicativo);
					while($reg_versoes = mysql_fetch_row($result_query_versoes)) 
						{ 
						$v_achei = '.';
						?>
              			<tr> 
		                <td nowrap class="opcao_tabela"><a href="../variaveis_ambiente/rel_variaveis_valores.php?id_variavel_ambiente=<? echo $reg_versoes[2]?>&nm_variavel_ambiente=<? echo $reg_versoes[0];?>" target="_blank"><? echo $reg_versoes[0]; ?></a></td>
		                <td nowrap class="opcao_tabela"><div align="right"><? echo $reg_versoes[1] ?></div></td>
						<?
		  				if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
							?>
		                <td nowrap class="opcao_tabela"><div align="right"><? echo $reg_versoes[3] ?></div></td>						

		              	</tr>	  
		              	<?
						echo $linha; 
		 				} //Fim do if else das versões
 				 	} //Fim do while

	 	if ($v_achei=='')
 			{
			echo'<tr><td colspan="2" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">A pesquisa não retornou registros</td></tr>';
			}

?>
        </table></td>
        </tr>
      </table>
 
 </tr>  
 <tr><td class="descricao_rel">
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo</font></p>	

</td>
</tr>  
 
</table>

</body>
</html>
