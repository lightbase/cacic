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
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}
include_once("cab.html");
require_once('../../include/library.php');
conecta_bd_cacic();


$str_consulta= $_GET['string_consulta'];	
$tp_consulta = $_GET['tipo_consulta'];	
		
if ($_GET['campo'])
	$orderby = 'ORDER BY '.$_GET['campo'];
else
	$orderby = 'ORDER BY te_nome_computador';
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="/cacic2/include/cacic.css">

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<?

if ($tp_consulta) {
	if($tp_consulta == 'nome') {
		$query = "SELECT * FROM computadores, so
				  WHERE te_nome_computador like '%". $str_consulta ."%' AND 
				  computadores.id_so = so.id_so 
				  $orderby";
	}
	if($tp_consulta == 'ip') {
		$query = "SELECT * FROM computadores, so
				  WHERE te_ip like '%". $str_consulta ."%' AND 
				  computadores.id_so = so.id_so 
				  $orderby";
	}
	if($tp_consulta == 'te_node_address') {
		$query = "SELECT * FROM computadores, so
				  WHERE te_node_address like '%". $str_consulta ."%' AND 
				  computadores.id_so = so.id_so 
				  $orderby";
	}
	$result = mysql_query($query) or die('Erro no select' . mysql_error().' ou sua sess�o expirou!');
	
	if ((strlen($str_consulta) < 1) && ($tp_consulta == 'nome')) {
		echo $mensagem = mensagem('Digite pelo menos 01 caracteres...');
		}
		else
		{
			if(($nu_reg= mysql_num_rows($result))==0){
			echo $mensagem = mensagem('Nenhum registro encontrado!');
				}
				else
				{

?>
<p align="center" class="descricao">Clique 
  sobre o nome da m&aacute;quina para ver os detalhes da mesma</p>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=te_nome_computador">Nome 
              da M&aacute;quina</a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=te_ip">IP</a></div></td>
          <td nowrap >&nbsp;</td>
			  <td nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=te_node_address">MAC Address</a></div></td>
			  <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=te_versao_cacic">Vers&atilde;o 
              Cacic</a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center"><a href="<? echo $PHP_SELF; ?>?campo=dt_hr_ult_acesso">&Uacute;ltima 
              Coleta</a></div></td>
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
          <td nowrap class="opcao_tabela"><div align="left"><a href="../computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><a href="../computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_ip']; ?></a></td>
          <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="center"><a href="../computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_node_address']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="center"><a href="../computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_versao_cacic']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="center"><a href="../computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo date("d/m/Y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></a></div></td>
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
    <td height="10">&nbsp;</td>
  </tr>
</table>
<?
				}
		}
}
include_once("rod.html");
?>
</body>
</html>
