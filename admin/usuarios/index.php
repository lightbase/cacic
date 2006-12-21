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
if ($_POST['submit']) {
  header ("Location: incluir_usuario.php");
}

include_once "../../include/library.php";

anti_spy();
Conecta_bd_cacic();
//LimpaTESTES();
$where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND usu.id_local = '.$_SESSION['id_local'] .' AND
				(g_usu.cs_nivel_administracao >= '.$_SESSION['cs_nivel_administracao']. ' OR
				 g_usu.cs_nivel_administracao = 0)':'');
	
$query = 'SELECT 	usu.id_usuario, 
					usu.nm_usuario_acesso,  
					usu.nm_usuario_completo,  
					g_usu.cs_nivel_administracao, 					
					g_usu.id_grupo_usuarios, 									
					loc.sg_local,
					loc.id_local
		  FROM 		usuarios usu, 
		  			grupo_usuarios g_usu, 
					locais loc
		  WHERE 	usu.id_grupo_usuarios=g_usu.id_grupo_usuarios and 
		  			usu.id_local=loc.id_local '.
					$where . ' 
		  ORDER BY 	usu.nm_usuario_completo';

$result = mysql_query($query);

$query_grp = 'SELECT	g_usu.te_grupo_usuarios,
						g_usu.id_grupo_usuarios
		  	  FROM 		grupo_usuarios g_usu
		  	  WHERE 	g_usu.cs_nivel_administracao <> 0
		  	  ORDER BY 	g_usu.te_grupo_usuarios';
$result_grp = mysql_query($query_grp);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Cadastro de Usu&aacute;rios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><div align="left">Cadastro 
        de Usu&aacute;rios</div></td>
  </tr>
  <tr> 
    <td class="descricao">Neste m&oacute;dulo 
      dever&atilde;o ser cadastrados os usu&aacute;rios que acessar&atilde;o o 
      sistema.</td>
  </tr>
</table>
<p><br></p><table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td><div align="center">
          <input name="submit" type="submit" id="submit" value="  Incluir Novo Usu&aacute;rio" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>        
      </div></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10"><? echo $msg;?></td>
  </tr>

  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
          <tr bgcolor="#E1E1E1"> 
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left">Acesso</div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="cabecalho_tabela"><div align="left">Nome</div></td>
            <td nowrap>&nbsp;</td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left">Local</div></td>
            <td nowrap>&nbsp;</td>
			<?
			while ($row_grp = mysql_fetch_array($result_grp))
				{				
				echo '<td nowrap class="cabecalho_tabela"><div align="center">';
//				echo '<img src="textpng.php?msg='.$row_grp['te_grupo_usuarios'].'" border="0" width="30" height="150">';								
				echo Abrevia($row_grp['te_grupo_usuarios']);
				echo '</div></td>';
	            echo '<td nowrap class="cabecalho_tabela">&nbsp;</td>';
				}
			?>
          </tr>
          <?  
if(mysql_num_rows($result)==0) 
	{
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Nenhum usuário cadastrado!</font><br><br></div>';
			
	}
else 
	{
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) 
		{		  
	 	?>
          <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap class="opcao_tabela"><? echo $NumRegistro; ?></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>"><? echo $row['nm_usuario_acesso']; ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>"><? echo PrimUltNome($row['nm_usuario_completo']); ?></a></div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>"><? echo $row['sg_local']; ?></a></div></td>
			<?
			mysql_data_seek($result_grp,0);			
			while ($row_grp = mysql_fetch_array($result_grp))
				{
            	echo '<td nowrap>&nbsp;</td>';
            	echo '<td nowrap class="opcao_tabela"><div align="center">';
				if ($row['cs_nivel_administracao']<> 0 && $row['id_grupo_usuarios']==$row_grp['id_grupo_usuarios']) 
					echo '<img src="../../imgs/checked_green.gif" width="13" height="13" border="0">';
				echo '</div></td>';
				}
			?>
            <td nowrap>&nbsp;</td>
            <? 
		$Cor=!$Cor;
		$NumRegistro++;
		}
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
  <tr> 
    <td height="10"><? echo $msg;?></td>
  </tr>
  <tr> 
    <td><div align="center">
    <input name="submit" type="submit" id="submit" value="  Incluir Novo Usu&aacute;rio" <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>        
      </div></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
</html>
