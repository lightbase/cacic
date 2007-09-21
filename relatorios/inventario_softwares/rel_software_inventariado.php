<? 
 /* 
 
Caminho do css
 /cacic2/include/cacic.css
 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
require $_SERVER['DOCUMENT_ROOT'] . '/cacic2/verificar.php';

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

$pesquisa = "0";
if (isset($_REQUEST["pesquisa"])){
	$pesquisa = $_REQUEST["pesquisa"];	
}

$rede = "0";
if (isset($_REQUEST["rede"])){
	$rede = $_REQUEST["rede"];	
}

if (isset($_REQUEST["palavra"])){
	$palavra = $_REQUEST["palavra"];	
}

//Paginação
$queryP = "SELECT distinct(a.id_software_inventariado),a.nm_software_inventariado  FROM  softwares_inventariados a, softwares_inventariados_estacoes b, computadores c";
$queryP .= " where b.te_node_address  = c.te_node_address AND b.id_so = c.id_so and a.id_software_inventariado = b.id_software_inventariado  ";
if ($pesquisa!="0"){
	$queryP .= "and id_si_grupo=".$pesquisa;
}
if ($palavra!=""){
	if ($pesquisa=="0"){
		$queryP .= " and nm_software_inventariado like '%".$palavra."%'";
	} else {
		$queryP .= " and nm_software_inventariado like '%".$palavra."%'";
	}	
}
if ($rede!="0"){
	$queryP .= " AND c.id_ip_rede ='".$rede."'";
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
if (($rede!=0) ||($pesquisa!=0) || ($palavra!="")){
	$inicial = (($pagina-1) * $total_reg);
}

$query = "SELECT distinct(a.id_software_inventariado),a.nm_software_inventariado FROM softwares_inventariados a, softwares_inventariados_estacoes b, computadores c";
$query .= " where b.te_node_address  = c.te_node_address AND b.id_so = c.id_so and a.id_software_inventariado = b.id_software_inventariado ";
if ($pesquisa!="0"){
	$query .= "AND a.id_si_grupo=".$pesquisa;
}
if ($palavra!=""){
	if ($pesquisa=="0"){
		$query .= " AND a.nm_software_inventariado like '%".$palavra."%'";
	} else {
		$query .= " AND a.nm_software_inventariado like '%".$palavra."%'";
	}	
}
if ($rede!="0"){
	$query .= " AND c.id_ip_rede ='".$rede."'";
}

$query .= " ORDER BY nm_software_inventariado LIMIT ".$inicial.",".$total_reg."";
$result = mysql_query($query);

$queryG = 'SELECT * FROM softwares_inventariados_grupos ORDER BY id_si_grupo';
$resultG = mysql_query($queryG);

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
function mudapagina(valor4,valor,valor1,valor3)
{
	window.open('rel_software_inventariado.php?rede='+valor4+'&pesquisa='+valor+'&pagina='+valor1+'&palavra='+valor3, '_self');
}
function pesquisa_()
{
	window.open('rel_software_inventariado.php?rede='+document.form1.rede.value+'&pesquisa='+document.form1.pesquisa.value+'&palavra='+document.form1.palavra.value+'&pagina=1', '_self');
}
function cria_janela_cent(horizontal,vertical,valor) {
	
		var res_ver = screen.height
		var res_hor = screen.width
		
		var pos_ver_fin = (res_ver - vertical)/2
		var pos_hor_fin = (res_hor - horizontal)/2
		
		window.open("atualizar_grupos.php?id_software_inventariado="+valor ,"pop","width="+horizontal+",height="+vertical+",top="+pos_ver_fin+",left="+pos_hor_fin+",status=yes");
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
function carrega(nome){
	document.all.lista.src = "combo_software_inv.php?rede=<? echo $rede?>&id_software_inventariado="+nome;
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
</script>
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'].'/cacic2/include/cacic.js';?>"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Relat&oacute;rio 
      de Softwares Invent&aacute;rios</td>
  </tr>
  <tr> 
    <td class="descricao">Este relat&oacute;rio 
      exibe os softwares inventariados que est&atilde;o instalados nos computadores das redes selecionadas.</td>
  </tr>
</table>
<iframe src="" height="0" width="0" frameborder="0" name="lista"></iframe>
<br><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td width="20%" valign="bottom">Por Rede<br>
        <select name="rede" class="normal" id="rede" >
          <option value="0">Todas</option>
          <? while($rowR = mysql_fetch_array($resultR)) {?>
          <option value="<? echo $rowR['id_ip_rede']; ?>" <? if ($rede==$rowR['id_ip_rede']){?>selected<? }?>><? echo $rowR['nm_rede']; ?></option>
          <? }?>
        </select>
      <br></td>
    <td width="14%" height="10" valign="bottom">Por grupo:<br>
      <select name="pesquisa" class="normal" id="pesquisa" ><!-- onChange="mudapagina(this.value,'1')"-->
        <option value="0">Todos</option>
		<? while($rowG = mysql_fetch_array($resultG)) {?>
			<option value="<? echo $rowG['id_si_grupo']; ?>" <? if ($pesquisa==$rowG['id_si_grupo']){?>selected<? }?>><? echo $rowG['nm_si_grupo']; ?></option>
		<? }?>	
      </select></td>
    <td valign="bottom">Por palavra:<br>
      <input name="palavra" type="text" class="normal" id="palavra" value="<? echo $palavra?>">
      &nbsp;&nbsp;
      <input name="Button" type="button" class="normal" value="Pesquisar" onClick="pesquisa_()"></td>
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
      << <a href="rel_software_inventariado.php?rede=<? echo $rede?>&pesquisa=<? echo $pesquisa?>&pagina=<? echo ($pagina - 1)?>&palavra=<? echo $palavra?>" target="_self">anterior</a> |
        <?	}		
	?>
          <select name="select2" class="normal" onChange="mudapagina('<? echo $rede?>','<? echo $pesquisa?>',this.value,'<? echo $palavra?>')">
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
          <a href="rel_software_inventariado.php?rede=<? echo $rede?>&pesquisa=<? echo $pesquisa?>&pagina=<? echo ($pagina + 1)?>&palavra=<? echo $palavra?>" target="_self">próxima</a>>>
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
          <td width="9" align="center"  nowrap>&nbsp;</td>
          <td width="39" align="center"  nowrap><strong>
            <input name="nomeanterior" type="hidden" id="nomeanterior" />
          </strong></td>
          <td width="66" align="center"  nowrap class="cabecalho_tabela"><div align="center">C&oacute;digo</div></td>
          <td width="571" nowrap  class="cabecalho_tabela">Software</td>
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
			$query_Cont = "SELECT count(*) as numero FROM softwares_inventariados_estacoes b, computadores c,so s where c.id_so = s.id_so and  b.te_node_address  = c.te_node_address AND b.id_so = c.id_so and  b.id_software_inventariado=".$row['id_software_inventariado'];
			if ($rede!="0"){
				$query_Cont .=" and c.id_ip_rede='".$rede."'";
			}			
			$result_Cont = mysql_query($query_Cont);
			$linha = mysql_fetch_array($result_Cont);		
	 ?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?> onMouseOver="mOvr(this,'#999999');" onMouseOut="mOut(this,'<? if ($Cor) { echo "#E1E1E1"; } ?>');" onClick="carrega('<? echo $row['id_software_inventariado'] ?>');">
			  <td>&nbsp;</td>
			  <td class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
			  <td class="opcao_tabela"><div align="center"><? echo $row['id_software_inventariado']; ?></div></td>
			  <td class="opcao_tabela"><div align="left"><? echo $row['nm_software_inventariado']; ?> [<? echo $linha['numero']?>] </div></td>
			</tr>
			<tr id="tr_micros_<? echo $row['id_software_inventariado'] ?>" style="display:none">
			  <td colspan="4">
			  <table width="97%" border="0" cellpadding="0" cellspacing="0" align="center">         
			  	<div id="texto_<? echo $row['id_software_inventariado'] ?>"></div>
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
