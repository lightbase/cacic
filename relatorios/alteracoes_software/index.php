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

if ($_POST['submit']) {
  header ("Location: incluir_grupos.php");
}

include_once "../../include/library.php";
Conecta_bd_cacic();


if ($_REQUEST['Excluir']) 
	{
	$query = "DELETE FROM softwares_inventariados_grupos WHERE id_si_grupo = '".$_POST['id_si_grupo']."'";
	mysql_query($query) or die('Delete falhou ou sua sessão expirou!');
	$query = "DELETE FROM acoes_redes WHERE id_ip_rede = '".$_GET['id_ip_rede']."'";
	mysql_query($query) or die('Delete falhou ou sua sessão expirou!');	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/software/index_grupos.php&tempo=1");									
	}
$datai = "";
if (isset($_REQUEST['date_input1'])){
	$datai = $_REQUEST['date_input1'];	
}
if ($datai != ""){
	//inverte as datas
	$v_elementos = explode("/",$datai);
	if ($v_elementos[1] < 10){
		$v_elementos[1] = "0".$v_elementos[1];
	}	
	if ($v_elementos[0] < 10){
		$v_elementos[0] = "0".$v_elementos[0];
	}
	$v_data_ini = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];	
	$dataf = date('j/n/Y');	
	if ($_REQUEST['date_input2']!=""){
		$dataf =$_REQUEST['date_input2'];
	}	
	$v_elementos = explode("/",$dataf);
	if ($v_elementos[1] < 10){
		$v_elementos[1] = "0".$v_elementos[1];
	}
	if ($v_elementos[0] < 10){
		$v_elementos[0] = "0".$v_elementos[0];
	}	
	$v_data_fim = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];	
}

$rede = "0";
if (isset($_REQUEST["rede"])){
	$rede = $_REQUEST["rede"];	
}

if (isset($_REQUEST["palavra"])){
	$palavra = $_REQUEST["palavra"];	
}

//Paginação
$queryP = "SELECT distinct(h.te_node_address),c.te_nome_computador,s.te_desc_so FROM computadores c,so s, historico_softwares_inventariados_estacoes h";
$queryP .= " where c.id_so = s.id_so and h.te_node_address=c.te_node_address";
if ($rede!="0"){
	$queryP .= " AND c.id_ip_rede ='".$rede."'";
}
if ($palavra!=""){
	$queryP .= " And te_nome_computador like '%".$palavra."%'";
}

if ($datai!=""){	
	$queryP .= " And  DATE_FORMAT(h.data, '%Y/%m/%d') between '".$v_data_ini."' and '".$v_data_fim."'";
}
$resultP = mysql_query($queryP);
$num = mysql_num_rows($resultP);

$total_reg = 50; // total de registros por pagina
$total_paginas=ceil($num/$total_reg); // total de paginas
$pagina = 1; // pagina atual

if (isset($_REQUEST["pagina"])){
	$pagina = $_REQUEST["pagina"];
}
$inicial = ($pagina * $total_reg);
if (($rede!=0) ||($datai!="") || ($palavra!="")){
	$inicial = (($pagina-1) * $total_reg);
}

$query = "SELECT distinct(h.te_node_address),c.te_nome_computador,s.te_desc_so,c.te_node_address,c.te_workgroup,c.te_dominio_windows FROM computadores c,so s, historico_softwares_inventariados_estacoes h";
$query .= " where c.id_so = s.id_so and h.te_node_address=c.te_node_address";
if ($rede!="0"){
	$query .= " AND c.id_ip_rede ='".$rede."'";
}
if ($palavra!=""){
	$query .= " And te_nome_computador like '%".$palavra."%'";
}

if ($datai!=""){	
	$query .= " And  DATE_FORMAT(h.data, '%Y/%m/%d') between '".$v_data_ini."' and '".$v_data_fim."'";
}

$query .= " ORDER BY te_nome_computador LIMIT ".$inicial.",".$total_reg."";

$result = mysql_query($query);

$queryR = "SELECT id_ip_rede, nm_rede FROM redes";
$resultR = mysql_query($queryR);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Cadastro de Grupos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function mudapagina(valor4,valor,valor1,valor3,valor2,valor5)
{
	window.open('index.php?rede='+valor4+'&date_input1='+valor2+'&date_input2='+valor5+'&pagina='+valor1+'&palavra='+valor3, '_self');
}
function pesquisa_()
{
	ChecaTodasAsRedes();
	window.open('index.php?rede='+document.forma.rede.value+'&date_input1='+document.forma.date_input1.value+'&date_input2='+document.forma.date_input2.value+'&palavra='+document.forma.palavra.value+'&pagina=1', '_self');
}

function mOvr(src,clrOver) {
 // if (!src.contains(event.fromElement)) {
	  if (!src.relatedTarget) {
         src.style.cursor = 'pointer';
         src.bgColor = clrOver;
      }
}
function mOut(src,clrIn) {
     // if (!src.contains(event.toElement)) {
		 if (!src.relatedTarget) {
        src.style.cursor = 'default';
        src.bgColor = clrIn;
      }
}
function carrega(nome,nome2){
	document.all.lista.src = "combo_software_inv.php?te_nome_computador="+nome2+"&v_data_ini=<? echo $v_data_ini?>&v_data_fim=<? echo $v_data_fim?>&te_node_address="+nome;
}
function mostratab(nome){
	eval("document.all.tr_micros_" + nome +".style.display= ''");				
	var nomeanterior = document.all.nomeanterior.value;

	if (nomeanterior != ""){		
		eval("document.all.tr_micros_" + nomeanterior +".style.display= 'none'");	
	}	
	document.all.nomeanterior.value = nome;
	if (nomeanterior == nome){
		document.all.nomeanterior.value = '';
	}
	
}	
function limpa(){
	document.forma.date_input1.value='';
	document.forma.date_input2.value='';
	document.forma.palavra.value='';
}
</script>
 <script src="../../include/sniffer.js" type="text/javascript" language="javascript"></script>
 <script src="../../include/dyncalendar.js" type="text/javascript" language="javascript"></script>
 <link href="../../include/dyncalendar.css" media="screen" rel="stylesheet">
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<form action="" method="post" name="forma" id="forma">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Relat&oacute;rio 
      de Altera&ccedil;&atilde;o de Software </td>
  </tr>
  <tr> 
    <td class="descricao">Este relat&oacute;rio 
      exibe as altera&ccedil;&otilde;es dos sowftares instalados
      nos computadores das redes selecionadas.</td>
  </tr>
</table>
<iframe src="" height="0" width="0" frameborder="0" name="lista"></iframe>
	<br>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td width="20%" valign="bottom">Por Rede<br>
        <select name="rede" class="normal" id="rede" >
          <option value="0">Todas</option>
          <? while($rowR = mysql_fetch_array($resultR)) {?>
          <option value="<? echo $rowR['id_ip_rede']; ?>" <? if ($rede==$rowR['id_ip_rede']){?>selected<? }?>><? echo $rowR['nm_rede']; ?></option>
          <? }?>
        </select>
      <br></td>
    <td width="33%" height="10" valign="bottom">Por per&iacute;odo:<br>
      <input name="date_input1" type="text" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" readonly value="<? echo $datai?>">
      <script type="text/javascript" language="JavaScript">
<!--
		function calendar1Callback(date, month, year)	{
			document.forms['forma'].date_input1.value = date + '/' + month + '/' + year;
		}
  	calendar1 = new dynCalendar('calendar1', 'calendar1Callback');
//-->
      </script>
&nbsp; a &nbsp;&nbsp;
<input name="date_input2" type="text" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" readonly value="<? echo $dataf?>">
<script type="text/javascript" language="JavaScript">
<!--
		function calendar2Callback(date, month, year)	{
			document.forms['forma'].date_input2.value = date + '/' + month + '/' + year;
		}
  	calendar2 = new dynCalendar('calendar2', 'calendar2Callback');
//-->
</script></td>
    <td width="47%" valign="bottom">Por palavra:<br>
      <input name="palavra" type="text" class="normal" id="palavra" value="<? echo $palavra?>">
      &nbsp;&nbsp;
      <input name="Button" type="button" class="normal" value="Pesquisar" onClick="pesquisa_()">
      <input name="Button2" type="button" class="normal" value="Limpar" onClick="limpa()"></td>
    </tr>
  <tr> 
    <td height="10" colspan="3"><div align="right"></div></td>
  </tr>
  <tr> 
    <td height="10" colspan="3" align="center" valign="middle"><? echo $msg;?>
      <div align="center"></div></td>
    </tr>
  <tr>
    <td height="10" colspan="3">
	<div align="right">
	<?  
		if ($pagina > 1) {
	?>
	
      << <a href="#" onClick="mudapagina('<? echo $rede?>','<? echo $pesquisa?>','<? echo ($pagina - 1)?>','<? echo $palavra?>','<? echo $datai?>','<? echo $dataf?>')" target="_self">anterior</a> |
        <?	}		
	?>
          <select name="select2" class="normal" onChange="mudapagina('<? echo $rede?>','<? echo $pesquisa?>',this.value,'<? echo $palavra?>','<? echo $datai?>','<? echo $dataf?>')">
            <?
		$i = 1;		
		while ($i <= $total_paginas) {
			if ($i == $pagina) {
				$seleciona = "selected";
			}
			else {
				$seleciona = "";
			} ?>
                  <option value="<? echo $i; ?>" <? echo $seleciona?>><? echo $i; ?></option>
            <? 
			$i = $i + 1; 
		}?>
          </select>
          <?	if ($pagina < $total_paginas) { ?>
          <a href="#" onClick="mudapagina('<? echo $rede?>','<? echo $pesquisa?>','<? echo ($pagina + 1)?>','<? echo $palavra?>','<? echo $datai?>','<? echo $dataf?>')" target="_self">próxima</a>>>
          <? }		
	?>
      </div></td>
  </tr>

  <tr> 
    <td height="1" colspan="3" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td colspan="3"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" bordercolor="#333333">
        <tr bgcolor="#E1E1E1"> 
          <td width="8" align="center"  nowrap>&nbsp;</td>
          <td width="50" align="center"  nowrap><strong>
            <input name="nomeanterior" type="hidden" id="nomeanterior" />
          </strong></td>
          <td colspan="3" nowrap  class="cabecalho_tabela">Computador</td>
          </tr>
<?  
if(mysql_num_rows($result)==0) {
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Nenhum software cadastrado
			</font><br><br></div>';
			
}
else {
	$Cor = 0;
	$NumRegistro = 1;
	$marcador = 0; 
	while($row = mysql_fetch_array($result)) {		  
		if ($marcador < $total_reg) {    
			/*$query_Cont = "SELECT count(*) as numero FROM softwares_inventariados_estacoes b, computadores c,so s where c.id_so = s.id_so and  b.te_node_address  = c.te_node_address AND b.id_so = c.id_so and  b.id_software_inventariado=".$row['id_software_inventariado'];
			if ($rede!="0"){
				$query_Cont .=" and c.id_ip_rede='".$rede."'";
			}			
			$result_Cont = mysql_query($query_Cont);
			$linha = mysql_fetch_array($result_Cont);*/		
	 ?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?> onMouseOver="mOvr(this,'#999999');" onMouseOut="mOut(this,'<? if ($Cor) { echo "#E1E1E1"; } ?>');" onClick="carrega('<? echo $row['te_node_address'] ?>','<? echo $row['te_nome_computador'] ?>');">
			  <td>&nbsp;</td>
			  <td class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
			  <td width="86" class="opcao_tabela"><div align="left"><? echo $row['te_nome_computador']; ?></div></td>
			  <td width="115" class="opcao_tabela"><? echo $row['te_desc_so']; ?></td>
			  <td width="423" class="opcao_tabela"><? echo $row['te_workgroup']?> (<? echo $row['te_dominio_windows']?>)</td>
			</tr>
			<tr id="tr_micros_<? echo $row['te_nome_computador'] ?>" style="display:none">
			  <td colspan="5">
			  <table width="97%" border="0" cellpadding="0" cellspacing="0" align="center">         
			  	<div id="texto_<? echo $row['te_nome_computador'] ?>"></div>
              </table>			  </td>
		  </tr>
			  <? 
			$Cor=!$Cor;
			$NumRegistro++;
		}
		//incrementamos o marcador...
	    $marcador = $marcador + 1;  
	}
}
?>
      </table></td>
  </tr>
  <tr> 
    <td height="1" colspan="3" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="10" colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10" colspan="3"><? echo $msg;?></td>
  </tr>
  
</table>
</form>
<p>
  
</p>
</body>
</html>
