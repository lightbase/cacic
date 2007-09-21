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

if (isset($_REQUEST["palavra"])){
	$palavra = $_REQUEST["palavra"];	
}

//Paginação
$queryP = "SELECT * FROM softwares_inventariados";
if ($pesquisa!="0"){
	$queryP .= " where id_si_grupo=".$pesquisa;
}
if ($palavra!=""){
	if ($pesquisa=="0"){
		$queryP .= " where nm_software_inventariado like '%".$palavra."%'";
	} else {
		$queryP .= " and nm_software_inventariado like '%".$palavra."%'";
	}	
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
if (($pesquisa!=0) || ($palavra!="")){
	$inicial = (($pagina-1) * $total_reg);
}

$query = "SELECT * FROM softwares_inventariados";
if ($pesquisa!="0"){
	$query .= " where id_si_grupo=".$pesquisa;
}
if ($palavra!=""){
	if ($pesquisa=="0"){
		$query .= " where nm_software_inventariado like '%".$palavra."%'";
	} else {
		$query .= " and nm_software_inventariado like '%".$palavra."%'";
	}	
}
$query .= " ORDER BY nm_software_inventariado LIMIT ".$inicial.",".$total_reg."";
$result = mysql_query($query);


$queryG = 'SELECT * FROM softwares_inventariados_grupos ORDER BY id_si_grupo';
$resultG = mysql_query($queryG);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Cadastro de Grupos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function mostrar(valor)
{    
  location.href = '#'+valor;
}
function mudapagina(valor,valor1,valor3)
{
	window.open('index.php?pesquisa='+valor+'&pagina='+valor1+'&palavra='+valor3, '_self');
}
function pesquisa_()
{
	window.open('index.php?pesquisa='+document.form1.pesquisa.value+'&palavra='+document.form1.palavra.value+'&pagina=1', '_self');
}
function cria_janela_cent(horizontal,vertical,valor,numero) {
	
		var res_ver = screen.height
		var res_hor = screen.width
		
		var pos_ver_fin = (res_ver - vertical)/2
		var pos_hor_fin = (res_hor - horizontal)/2		
		
		window.open("atualizar_grupos.php?numero="+numero+"&id_software_inventariado="+valor ,"pop","width="+horizontal+",height="+vertical+",top="+pos_ver_fin+",left="+pos_hor_fin+",status=yes");		

}

</script>
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'].'/cacic2/include/cacic.js';?>"></script>

<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Cadastro de Softwares </td>
  </tr>
  <tr> 
    <td class="descricao">Neste m&oacute;dulo dever&atilde;o ser alterados os grupos dos os  Softwares cadastrados no Cacic.</td>
  </tr>
</table>
<br><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td width="13%" height="10" valign="baseline">Por grupo:<br>
      <select name="pesquisa" class="normal" id="pesquisa" ><!-- onChange="mudapagina(this.value,'1')"-->
        <option value="0">Todos</option>
		<? while($rowG = mysql_fetch_array($resultG)) {?>
			<option value="<? echo $rowG['id_si_grupo']; ?>" <? if ($pesquisa==$rowG['id_si_grupo']){?>selected<? }?>><? echo $rowG['nm_si_grupo']; ?></option>
		<? }?>	
      </select>      </td>
    <td width="18%" valign="baseline">Por palavra:<br>
      <input name="palavra" type="text" class="normal" id="palavra" value="<? echo $palavra?>"></td>
    <td width="69%" valign="bottom"><input name="Button" type="button" class="normal" value="Pesquisar" onClick="pesquisa_()"></td>
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
      << <a href="index.php?pesquisa=<? echo $pesquisa?>&pagina=<? echo ($pagina - 1)?>&palavra=<? echo $palavra?>" target="_self">anterior</a> |
        <?	}		
	?>
          <select name="select2" class="normal" onChange="mudapagina('<? echo $pesquisa?>',this.value,'<? echo $palavra?>')">
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
          <a href="index.php?pesquisa=<? echo $pesquisa?>&pagina=<? echo ($pagina + 1)?>&palavra=<? echo $palavra?>" target="_self">próxima</a>>>
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
          <td width="16" align="center"  nowrap>&nbsp;</td>
          <td width="27" align="center"  nowrap>&nbsp;</td>
          <td width="60" align="center"  nowrap class="cabecalho_tabela"><div align="center">C&oacute;digo</div></td>
          <td width="513" nowrap  class="cabecalho_tabela">Software</td>
          <td width="66" nowrap class="cabecalho_tabela">Grupo</td>
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
	 ?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
			  <td><a name="<? echo $NumRegistro?>"></a></td>
			  <td class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
			  <td class="opcao_tabela"><div align="center"><? echo $row['id_software_inventariado']; ?></div></td>
			  <td class="opcao_tabela"><div align="left"><? echo $row['nm_software_inventariado']; ?> </div></td>
			  <td>
			  <iframe src="atualizar_grupos.php?id_software_inventariado=<? echo $row['id_software_inventariado']?>" height="20" width="110"></iframe>
			  </td>
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
<p>&nbsp;</p>
</body>
</html>
