<?php 
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
  die('Acesso negado (Access denied)!');
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
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('softwares_inventariados_grupos')));
	$query = "DELETE FROM acoes_redes WHERE id_rede = '".$_GET['id_rede']."'";
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('acoes_redes')));	
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
$queryP = "SELECT distinct(h.id_computador),c.te_nome_computador,s.te_desc_so FROM computadores c,so s, historico_softwares_inventariados_estacoes h";
$queryP .= " where c.id_so = s.id_so and h.id_computador=c.id_computador";
if ($rede!="0"){
	$queryP .= " AND c.id_rede ='".$rede."'";
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

$query = "SELECT distinct(h.id_computador),c.te_nome_computador,s.te_desc_so,c.te_node_address,c.te_workgroup,c.te_dominio_windows FROM computadores c,so s, historico_softwares_inventariados_estacoes h";
$query .= " where c.id_so = s.id_so and h.id_computador=c.id_computador";
if ($rede!="0"){
	$query .= " AND c.id_rede ='".$rede."'";
}
if ($palavra!=""){
	$query .= " And te_nome_computador like '%".$palavra."%'";
}

if ($datai!=""){	
	$query .= " And  DATE_FORMAT(h.data, '%Y/%m/%d') between '".$v_data_ini."' and '".$v_data_fim."'";
}

$query .= " ORDER BY te_nome_computador LIMIT ".$inicial.",".$total_reg."";

$result = mysql_query($query);

$queryR = "SELECT id_rede, nm_rede FROM redes";
$resultR = mysql_query($queryR);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
<title><?php echo $oTranslator->_('Relatorio de alteracoes de software');?></title>
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
	document.all.lista.src = "combo_software_inv.php?te_nome_computador="+nome2+"&v_data_ini=<?php echo $v_data_ini?>&v_data_fim=<?php echo $v_data_fim?>&id_computador="+nome;
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
 <script src="../../include/js/sniffer.js" type="text/javascript" language="javascript"></script>
 <script src="../../include/js/dyncalendar.js" type="text/javascript" language="javascript"></script>
 <link href="../../include/css/dyncalendar.css" media="screen" rel="stylesheet">
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>
<form action="" method="post" name="forma" id="forma">
<table width="85%" border="0" align="center">
  <tr> 
    <td class="cabecalho">
      <?php echo $oTranslator->_('Relatorio de alteracoes de software');?>
    </td>
  </tr>
  <tr> 
    <td class="descricao">
      <?php echo $oTranslator->_('Relatorio de alteracoes de softwares instalados nos computadores da rede selecionada');?>
    </td>
  </tr>
</table>
<iframe src="" height="0" width="0" frameborder="0" name="lista"></iframe>
	<br>
	<table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td width="20%" valign="bottom"><?php echo $oTranslator->_('Rede');?><br>
        <select name="rede" class="normal" id="rede" >
          <option value="0"><?php echo $oTranslator->_('Todas');?></option>
          <?php while($rowR = mysql_fetch_array($resultR)) {?>
          <option value="<?php echo $rowR['id_rede']; ?>" <?php if ($rede==$rowR['id_rede']){?>selected<?php }?>><?php echo $rowR['nm_rede']; ?></option>
          <?php }?>
        </select>
      <br></td>
    <td width="33%" height="10" valign="bottom"><?php echo $oTranslator->_('Periodo');?><br>
      <input name="date_input1" type="text" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" readonly value="<?php echo $datai?>">
      <script type="text/javascript" language="JavaScript">
<!--
		function calendar1Callback(date, month, year)	{
			document.forms['forma'].date_input1.value = date + '/' + month + '/' + year;
		}
  	calendar1 = new dynCalendar('calendar1', 'calendar1Callback');
//-->
      </script>
&nbsp; a &nbsp;&nbsp;
<input name="date_input2" type="text" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" readonly value="<?php echo $dataf?>">
<script type="text/javascript" language="JavaScript">
<!--
		function calendar2Callback(date, month, year)	{
			document.forms['forma'].date_input2.value = date + '/' + month + '/' + year;
		}
  	calendar2 = new dynCalendar('calendar2', 'calendar2Callback');
//-->
</script></td>
    <td width="47%" valign="bottom"><?php echo $oTranslator->_('Texto');?><br>
      <input name="palavra" type="text" class="normal" id="palavra" value="<?php echo $palavra?>">
      &nbsp;&nbsp;
      <input name="Button" type="button" class="normal" value="<?php echo $oTranslator->_('Pesquisar');?>" onClick="pesquisa_()">
      <input name="Button2" type="button" class="normal" value="<?php echo $oTranslator->_('Limpar');?>" onClick="limpa()"></td>
    </tr>
  <tr> 
    <td height="10" colspan="3"><div align="right"></div></td>
  </tr>
  <tr> 
    <td height="10" colspan="3" align="center" valign="middle"><?php echo $msg;?>
      <div align="center"></div></td>
    </tr>
  <tr>
    <td height="10" colspan="3">
	<div align="right">
	<?php if ($pagina > 1) {
	?>
	
      << <a href="#" onClick="mudapagina('<?php echo $rede?>','<?php echo $pesquisa?>','<?php echo ($pagina - 1)?>','<?php echo $palavra?>','<?php echo $datai?>','<?php echo $dataf?>')" target="_self">anterior</a> |
        <?php	}		
	?>
          <select name="select2" class="normal" onChange="mudapagina('<?php echo $rede?>','<?php echo $pesquisa?>',this.value,'<?php echo $palavra?>','<?php echo $datai?>','<?php echo $dataf?>')">
            <?php
		$i = 1;		
		while ($i <= $total_paginas) {
			if ($i == $pagina) {
				$seleciona = "selected";
			}
			else {
				$seleciona = "";
			} ?>
                  <option value="<?php echo $i; ?>" <?php echo $seleciona?>><?php echo $i; ?></option>
            <?php 
			$i = $i + 1; 
		}?>
          </select>
          <?php if ($pagina < $total_paginas) { ?>
          <a href="#" onClick="mudapagina('<?php echo $rede?>','<?php echo $pesquisa?>','<?php echo ($pagina + 1)?>','<?php echo $palavra?>','<?php echo $datai?>','<?php echo $dataf?>')" target="_self">próxima</a>>>
          <?php }		
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
          <td colspan="3" nowrap  class="cabecalho_tabela"><?php echo $oTranslator->_('Computador');?></td>
          </tr>
<?php if(mysql_num_rows($result)==0) {
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				'.$oTranslator->_('Nenhum software cadastrado').'
			</font><br><br></div>';
			
}
else {
	$Cor = 0;
	$NumRegistro = 1;
	$marcador = 0; 
	while($row = mysql_fetch_array($result)) {		  
		if ($marcador < $total_reg) {    
	 ?>
			<tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?> onMouseOver="mOvr(this,'#999999');" onMouseOut="mOut(this,'<?php if ($Cor) { echo "#E1E1E1"; } ?>');" onClick="carrega('<?php echo $row['id_computador'] ?>','<?php echo $row['te_nome_computador'] ?>');">
			  <td>&nbsp;</td>
			  <td class="opcao_tabela"><div align="left"><?php echo $NumRegistro; ?></div></td>
			  <td width="86" class="opcao_tabela"><div align="left"><?php echo $row['te_nome_computador']; ?></div></td>
			  <td width="115" class="opcao_tabela"><?php echo $row['te_desc_so']; ?></td>
			  <td width="423" class="opcao_tabela"><?php echo $row['te_workgroup']?> (<?php echo $row['te_dominio_windows']?>)</td>
			</tr>
			<tr id="tr_micros_<?php echo $row['te_nome_computador'] ?>" style="display:none">
			  <td colspan="5">
			  <table width="97%" border="0" cellpadding="0" cellspacing="0" align="center">         
			  	<div id="texto_<?php echo $row['te_nome_computador'] ?>"></div>
              </table>			  </td>
		  </tr>
			  <?php 
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
    <td height="10" colspan="3"><?php echo $msg;?></td>
  </tr>
  
</table>
</form>
<p>
  
</p>
</body>
</html>
