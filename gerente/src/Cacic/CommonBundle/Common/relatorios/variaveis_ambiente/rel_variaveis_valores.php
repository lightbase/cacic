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

//Mostrar computadores baseados no tipo de pesquisa solicitada pelo usu�rio
require_once('../../include/library.php');
AntiSpy();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Relat&oacute;rio de Vari&aacute;veis de Ambiente</title>
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
    <td class="descricao"><p align="left">Gerado 
        em <? echo date("d/m/Y �\s H:i"); ?></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?
conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>'.
			($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2?'<td height="1"></td>':'').
         '</tr>';
?>
<table border="0" align="center" width="300" >
  <tr> 
    <td align="center" nowrap class="cabecalho_tabela">Vari&aacute;vel de Ambiente: &quot;<u><? echo $_GET['nm_variavel_ambiente']; ?></u>&quot;</td>
  </tr>
</table>
<?
	 $query = "SELECT distinct 	computadores.id_so, 
	 							computadores.te_nome_computador, 
								computadores.te_ip, 
								computadores.te_node_address, 
								computadores.dt_hr_ult_acesso,
			   					b.nm_variavel_ambiente,
								IF(TRIM(c.vl_variavel_ambiente)='','Nenhum Valor', c.vl_variavel_ambiente) as vl_variavel_ambiente, 
								count(vl_variavel_ambiente) as total, 
								b.id_variavel_ambiente ".
								$_SESSION['select']. " 
			   FROM 			computadores, 
			   					variaveis_ambiente b, 
								variaveis_ambiente_estacoes c ".
								$_SESSION['from']." 
			   WHERE 			computadores.id_so IN (".$_SESSION["so_selecionados"].") ". $_SESSION["redes_selecionadas"] ." AND 
			   					computadores.te_node_address = c.te_node_address AND 
								computadores.id_so = c.id_so AND 			  		
					 			b.id_variavel_ambiente = ". $_GET['id_variavel_ambiente'] ." AND 
								b.id_variavel_ambiente = c.id_variavel_ambiente " .
								$_SESSION['query_redes']." 								
			   GROUP BY 		c.vl_variavel_ambiente
			   ORDER BY total desc,vl_variavel_ambiente ";
	$result = mysql_query($query) or die('Erro de acesso ao banco ou sua sess�o expirou!');
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td align="center" nowrap></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">Valor</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="right">M�quinas</div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap >&nbsp;</td>
          <td nowrap >&nbsp;</td>
          <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><a href="../../relatorios/variaveis_ambiente/rel_maquinas_variaveis.php?id_variavel_ambiente= <? echo $row['id_variavel_ambiente']; ?>&vl_variavel_ambiente=<? echo str_replace('"','***',$row['vl_variavel_ambiente']); ?>" target="_blank"><? echo $row['vl_variavel_ambiente'];?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="right"><? echo $row['total']; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap>&nbsp;</td>
          <td nowrap>&nbsp;</td>
          <td nowrap>&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
	}
	
?>
      </table></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td class="descricao_rel">Clique sobre o valor da vari&aacute;vel para visualizar 
      as m&aacute;quinas associadas</td>
  </tr>
  <tr><td height="30">&nbsp;</td></tr>
  <tr><td class="descricao_rel">
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	
  </td></tr>
  
</table>

</body>
</html>
