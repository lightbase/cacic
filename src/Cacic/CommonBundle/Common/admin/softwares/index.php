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
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

if (@$_POST['submit']) {
  header ("Location: incluir_software.php");
}

include_once "../../include/library.php";
AntiSpy();
Conecta_bd_cacic();

$query = 'SELECT 	* 
		  FROM 		softwares s
		  ORDER BY 	nm_software';
$result = mysql_query($query);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title><?=$oTranslator->_('Cadastro de Softwares');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
      <td class="cabecalho"><?=$oTranslator->_('Cadastro de Softwares');?></td>
  </tr>
  <tr> 
      <td class="descricao">
        <?=$oTranslator->_('Neste modulo deverao ser cadastrados os softwares avulsos, manipulados pelo sistema');?>.
      </td>
  </tr>
</table>
<br><table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td><div align="center">
          <input name="submit" type="submit" id="submit" value="<?=$oTranslator->_('Incluir Novo Software');?>" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
      </div></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10"><? echo $msg;?></td>
  </tr>

  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
		  <tr> 
		    <td height="1" bgcolor="#333333" colspan="5"></td>
		  </tr>
	
          <tr bgcolor="#E1E1E1" nowrap> 
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap><div align="left"></div></td>
            <td align="center"  nowrap>&nbsp;</td>
            <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Nome do Software');?></div></td>
            <td nowrap >&nbsp;</td>
          </tr>
		  <tr> 
		    <td height="1" bgcolor="#333333" colspan="5"></td>
		  </tr>
		  
          <?  
if(mysql_num_rows($result)==0) 
	{
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">'.
			$oTranslator->_('Nenhum software cadastrado').'
			</font><br><br></div>';			
	}
else 
	{
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) 
		{
		?>
          <tr 
		<? if ($Cor) 
		echo 'bgcolor="#E1E1E1"';
		?>> 
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_software.php?id_software=<? echo $row['id_software'];?>"><?=$row['nm_software']." - ".$row['te_descricao_software']; ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <? 
		$Cor=!$Cor;
		$NumRegistro++;
		}
	}
	?>
		  <tr> 
		    <td height="1" bgcolor="#333333" colspan="5"></td>
		  </tr>

        </table></td>
  	</tr>
  	<tr> 
    <td height="10">&nbsp;</td>
  	</tr>
  	<tr> 
    <td height="10"><? echo $msg;?></td>
  	</tr>
  	<tr> 
    <td><div align="center">
  	<input name="submit" type="submit" id="submit" value="<?=$oTranslator->_('Incluir Novo Software');?>" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>  
  	</div></td>
  	</tr>
	</table>
    </form>
	<p>&nbsp;</p>
	</body>
	</html>
