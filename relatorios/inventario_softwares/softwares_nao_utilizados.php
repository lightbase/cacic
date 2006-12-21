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
	$query = "DELETE FROM softwares_inventariados WHERE id_software_inventariado = '".$_REQUEST['id_software_inventariado']."'";

	mysql_query($query) or die('Delete falhou');
	
	header ("Location: ../../include/operacao_ok.php?chamador=../relatorios/inventario_softwares/softwares_nao_utilizados.php&tempo=1");									
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

$queryEstacoes = "SELECT distinct(id_software_inventariado) FROM  softwares_inventariados_estacoes";
$resultEstacoes = mysql_query($queryEstacoes);
while($rowE = mysql_fetch_array($resultEstacoes)){
	$id .= "'".$rowE['id_software_inventariado']."',";
}
$id = substr($id,0,strlen($id)-1);

$query = "SELECT * FROM  softwares_inventariados";
$query .= " where id_software_inventariado not in (".$id.")";	
if ($pesquisa!="0"){
	$query .= " and id_si_grupo=".$pesquisa;
}
if ($palavra!=""){
	$query .= " and nm_software_inventariado like '%".$palavra."%'";
}
if ($rede!="0"){
	$query .= " AND c.id_ip_rede ='".$rede."'";
}
$query .= " ORDER BY nm_software_inventariado";

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
	window.open('softwares_nao_utilizados.php?pesquisa='+valor+'&palavra='+valor3, '_self');
}
function pesquisa_()
{
	window.open('softwares_nao_utilizados.php?pesquisa='+document.form1.pesquisa.value+'&palavra='+document.form1.palavra.value+'&pagina=1', '_self');
}

</script>
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'].'/cacic2/include/cacic.js';?>"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Relat&oacute;rio 
      de Softwares N&atilde;o Utilizados </td>
  </tr>
  <tr> 
    <td class="descricao">Este relat&oacute;rio 
      exibe os softwares que n&atilde;o est&atilde;o sendo utilizados em nenhuma m&aacute;quina da Rede.</td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
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
    <td height="10" colspan="2"><div align="right"></div></td>
  </tr>
  <tr> 
    <td height="10" colspan="2" align="center" valign="middle"><? echo $msg;?>
      <div align="center"></div></td>
    </tr>
  <tr>
    <td height="10" colspan="2">
	<div align="right"></div></td>
  </tr>

  <tr> 
    <td height="1" colspan="2" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td colspan="2"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" bordercolor="#333333">
        <tr bgcolor="#E1E1E1"> 
          <td width="9" align="center"  nowrap>&nbsp;</td>
          <td width="39" align="center"  nowrap><strong>
            <input name="nomeanterior" type="hidden" id="nomeanterior" />
          </strong></td>
          <td width="66" align="center"  nowrap class="cabecalho_tabela"><div align="center">C&oacute;digo</div></td>
          <td colspan="2" nowrap  class="cabecalho_tabela">Software</td>
          </tr>
<?  
if(mysql_num_rows($result)==0) {
	$msg = '<div align="center"><font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">Nenhum software cadastrado</font><br><br></div>';
			
}
else {
	$Cor = 0;
	$NumRegistro = 1;
	$marcador = 0; 
	while($row = mysql_fetch_array($result)) {		  
		$SqlCon  ="select distinct(s1.id_software_inventariado) from softwares_inventariados s, historico_softwares_inventariados_estacoes s1 where s.id_software_inventariado = s1.id_software_inventariado and s1.ind_acao=2 and s.id_software_inventariado =".$row['id_software_inventariado'];
		$resultCon = mysql_query($SqlCon);
		$NUM = mysql_num_rows($resultCon);
		  								
	 ?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?> >
			  <td>&nbsp;</td>
			  <td class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
			  <td class="opcao_tabela"><div align="center"><? echo $row['id_software_inventariado']; ?></div></td>
			  <td width="372" class="opcao_tabela"><div align="left"><? echo $row['nm_software_inventariado']; ?></div></td>
			  <td width="196" valign="bottom" class="opcao_tabela">&nbsp;&nbsp;
			    <? if ($NUM == 0){?>
                <a href="?Excluir=1&id_software_inventariado=<? echo $row['id_software_inventariado']?>" onClick="return Confirma('Confirma Exclus&atilde;o do Software?');"><img src="../../admin/software/excluir.jpg" width="16" height="16" border="0"></a>
                <? }else{?>
                <img src="../../admin/software/excluir_cinza.jpg" width="16" height="16" border="0">
                <? }?></td>
			</tr>
			<tr id="tr_micros_<? echo $row['id_software_inventariado'] ?>" style="display:none">
			  <td colspan="5">
			  <table width="97%" border="0" cellpadding="0" cellspacing="0" align="center">         
			  	<div id="texto_<? echo $row['id_software_inventariado'] ?>"></div>
              </table>			  </td>
		  </tr>
		 
			  <? 
			$Cor=!$Cor;
			$NumRegistro++;
		//incrementamos o marcador...
	    $marcador = $marcador + 1;  
	}
}
?>
      </table></td>
  </tr>
  <tr> 
    <td height="1" colspan="2" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="10" colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10" colspan="2"><? echo $msg;?></td>
  </tr>
</table>
</form>
<p>
  
</p>
</body>
</html>
