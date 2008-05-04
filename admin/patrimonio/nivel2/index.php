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

if ($_POST['incluirUON2']) 
	{
  	header ("Location: incluir_nivel2.php");
	}

include_once "../../../include/library.php";
// Comentado temporariamente - AntiSpy();
Conecta_bd_cacic();
$where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND id_local = '.$_SESSION['id_local']:'');
$where = ' AND id_local = '.$_SESSION['id_local'];
$queryCONFIG = "SELECT 		DISTINCT 
							id_etiqueta,
							te_etiqueta,
							te_plural_etiqueta
		  		FROM 		patrimonio_config_interface patcon
				WHERE		patcon.id_etiqueta in ('".'etiqueta1'."','".'etiqueta2'."') ".
							$where. "
		  		ORDER BY 	id_etiqueta";

$resultCONFIG 	= mysql_query($queryCONFIG);

session_register('etiqueta1');
session_register('plural_etiqueta1');
session_register('etiqueta2');
session_register('plural_etiqueta2');

$row 	= mysql_fetch_array($resultCONFIG);
$_SESSION['etiqueta1'] 	= $row['te_etiqueta'];
$_SESSION['plural_etiqueta1'] 	= $row['te_plural_etiqueta'];

$row 	= mysql_fetch_array($resultCONFIG);
$_SESSION['etiqueta2'] 	= $row['te_etiqueta'];
$_SESSION['plural_etiqueta2'] 	= $row['te_plural_etiqueta'];

$where = '';
if ($id_unid_organizacional_nivel1)
	{
	$where = ' and uo2.id_unid_organizacional_nivel1='.$id_unid_organizacional_nivel1;
	}

$where .= ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND uo2.id_local = '.$_SESSION['id_local']:'');
$where .= ' AND uo2.id_local = '.$_SESSION['id_local'];

if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
	{
	// Faço uma inserção de "(" para ajuste da lógica para consulta	
	$where = str_replace('uo2.id_local = ','(uo2.id_local = ',$where);
	$where .= ' OR uo2.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
	}

$query = 'SELECT 	uo1.id_unid_organizacional_nivel1 as uo1_id_unid_organizacional_nivel1,
					uo1.nm_unid_organizacional_nivel1 as uo1_nm_unid_organizacional_nivel1,
					uo2.id_unid_organizacional_nivel2 as uo2_id_unid_organizacional_nivel2,
					uo2.nm_unid_organizacional_nivel2 as uo2_nm_unid_organizacional_nivel2,
					loc.id_local,
					loc.sg_local
		  FROM 		unid_organizacional_nivel1 uo1,
		   			unid_organizacional_nivel2 uo2,
					locais loc
		  WHERE		uo2.id_unid_organizacional_nivel1a = uo1.id_unid_organizacional_nivel1 '.$where.' and
		  			uo2.id_local = loc.id_local			
		  ORDER BY 	loc.sg_local,
		  			uo1_nm_unid_organizacional_nivel1, 
					uo2_nm_unid_organizacional_nivel2';		  

$result = mysql_query($query);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../include/cacic.css">

<body background="../../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../../include/cacic.js"></script>
<title>Cadastro de U. O. N&iacute;vel 2</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Cadastro de <? echo $_SESSION['plural_etiqueta2'];?> (U. 
      O. Nível 2)</td>
  </tr>
  <tr> 
    <td class="descricao">M&oacute;dulo para cadastramento de Unidades Organizacionais de N&iacute;vel 2.</td>
  </tr>
</table>

<br><table width="42%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><div align="center">

          <input name="incluirUON2" type="submit" id="incluirUON2" value="Incluir <? echo $_SESSION['etiqueta2'];?>" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>

        
      </div></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10"><? echo $msg;?></td>
  </tr>

  <tr> 
    <td width="100%" height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">	
	
        <tr bgcolor="#E1E1E1"> 		
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><? echo $_SESSION['etiqueta1'] .'-'.$_SESSION['etiqueta2'];?></div></td>
          <td nowrap >&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela"><div align="left">Local</div></td>
          <td nowrap >&nbsp;</td>
        </tr>
<?  
if(mysql_num_rows($result)==0) 
	{
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Nenhuma Unidade Organizacional de nível 2 cadastrada
			</font><br><br></div>';			
	}
else 
	{
	$Cor = 0;
	$NumRegistro = 1;	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><a href="../nivel2/detalhes_nivel2.php?id_unid_organizacional_nivel1=<? echo $row['uo1_id_unid_organizacional_nivel1'];?>&id_unid_organizacional_nivel2=<? echo $row['uo2_id_unid_organizacional_nivel2'];?>&id_local=<? echo $row['id_local'];?>"><? echo PrimNome($row['uo1_nm_unid_organizacional_nivel1']) . '-' . $row['uo2_nm_unid_organizacional_nivel2']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  
            <td nowrap><div align="left"><a href="../nivel2/detalhes_nivel2.php?id_unid_organizacional_nivel1=<? echo $row['uo1_id_unid_organizacional_nivel1'];?>&id_unid_organizacional_nivel2=<? echo $row['uo2_id_unid_organizacional_nivel2'];?>&id_local=<? echo $row['id_local'];?>"><? echo $row['sg_local']; ?></a></div></td>			  
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

          <input name="incluirUON2" type="submit" id="incluirUON2" value="Incluir <? echo $_SESSION['etiqueta2'];?>" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>

        
      </div></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
</html>
