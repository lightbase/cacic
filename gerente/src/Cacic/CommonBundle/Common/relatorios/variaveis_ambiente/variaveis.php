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
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

if($_POST['submit']) {
	$_SESSION["list2"] = $_POST['list2']; //Redes selecionadas
	$_SESSION["list4"] = $_POST['list4']; //SO selecionados
	$_SESSION["list6"] = $_POST['list6']; //Vari�veis selecionadas
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12'];		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
}

require_once('../../include/library.php');
AntiSpy();

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
    <td class="descricao_rel"><p align="left">Gerado em <? echo date("d/m/Y �\s H:i"); ?></p></td>
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
		// Aqui pego todas as redes selecionadas e fa�o uma query p/ condi��o de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

		$_SESSION['query_redes'] = 'AND computadores.id_ip_rede IN ('. $redes_selecionadas .')';		
		
		//}	

	}
else
	{
	// Aqui pego todos os locais selecionados e fa�o uma query p/ condi��o de redes/locais
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

//Pego os aplicativos selecionados para o relat�rio
$v_achei = '';
$aplicativos_selecionados = $_SESSION["list6"][0] ;
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) {
	$aplicativos_selecionados = $aplicativos_selecionados .", ". $_SESSION["list6"][$i] ;
	}

	// Exibir as aplica��es	 baseado no que foi solicitado	
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
					// Exibir informa��es sobre a quantidade de m�quinas e os vari�veis de ambiente
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
		 				} //Fim do if else das vers�es
 				 	} //Fim do while

	 	if ($v_achei=='')
 			{
			echo'<tr><td colspan="2" align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">A pesquisa n�o retornou registros</td></tr>';
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
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</td>
</tr>  
 
</table>

</body>
</html>
