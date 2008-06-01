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
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if ($_POST['submit']) {
  header ("Location: incluir_local.php");
}

include_once "../../include/library.php";
AntiSpy();

Conecta_bd_cacic();
$query = 'SELECT 	locais.id_local,
					count(redes.id_local) TotaisRedes
		  FROM 		locais,
		  			redes
		  WHERE		locais.id_local = redes.id_local
		  GROUP BY  locais.id_local';
$result = mysql_query($query);		
  
$arrTotaisRedes = array();
while ($row=mysql_fetch_array($result))		  
	$arrTotaisRedes[$row['id_local']] = $row['TotaisRedes'];

$query = 'SELECT 	locais.id_local,
					count(usuarios.id_local) TotaisUsuariosPrimarios
		  FROM 		locais,
		  			usuarios
		  WHERE		locais.id_local = usuarios.id_local
		  GROUP BY  usuarios.id_local';
$result = mysql_query($query);		  
$arrTotaisUsuariosPrimarios = array();
while ($row=mysql_fetch_array($result))		  
	$arrTotaisUsuariosPrimarios[$row['id_local']] = $row['TotaisUsuariosPrimarios'];


// Infelizmente foi necessário montar essa estratégia!!!  :)
$queryUsuarios  = 'SELECT 	te_locais_secundarios
		  		   FROM 	usuarios
				   WHERE    trim(te_locais_secundarios) <> ""';
$resultUsuarios = mysql_query($queryUsuarios);		  

$arrTotaisUsuariosSecundarios = array();
while ($rowUsuarios = mysql_fetch_array($resultUsuarios)) 
	{
	$arrLocaisSecundarios = explode(',',$rowUsuarios['te_locais_secundarios']);
	for ($i=0; $i<count($arrLocaisSecundarios); $i++)
		$arrTotaisUsuariosSecundarios[$arrLocaisSecundarios[$i]] ++;
	}

$ordem = ($_GET['cs_ordem']<>''?$_GET['cs_ordem']:'sg_local');
$query = 'SELECT 	*
		  FROM 		locais 
		  ORDER BY 	'.$ordem;

$result = mysql_query($query);
$msg = '<div align="center">
		<font color="#c0c0c0" size="1" face="Verdana, Arial, Helvetica, sans-serif">
		'.$oTranslator->_('Clique nas Colunas para Ordenar').'</font><br><br></div>';				
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title><?=$oTranslator->_('Cadastro de Local');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
      <td class="cabecalho"><?=$oTranslator->_('Cadastro de Local');?></td>
  </tr>
  <tr> 
      <td class="descricao"><?=$oTranslator->_('ksiq_msg cadastro help');?></td>
  </tr>
</table>
<br><table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td><div align="center">
          <input name="submit" type="submit" id="submit" value="<?=$oTranslator->_('Incluir Informacoes de Novo Local');?>" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
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
        <tr bgcolor="#E1E1E1" nowrap> 
          <td align="center"  nowrap>&nbsp;</td>		
          <td align="center"  nowrap><div align="left"></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=sg_local">Sigla</a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=nm_local"><?=$oTranslator->_('Descripcao');?></a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Redes');?></div></td>
          <td nowrap class="cabecalho_tabela">&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Usuarios Primarios');?> </div></td>
          <td nowrap class="cabecalho_tabela">&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Usuarios Secundarios');?> </div></td>
          <td nowrap >&nbsp;</td>
        </tr>
  	<tr> 
    <td height="1" bgcolor="#333333" colspan="13"></td>
  	</tr>
		
<?  
if(mysql_num_rows($result)==0) 
	{
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				'.$oTranslator->_('Nenhum local cadastrado ou sua sessao expirou').'</font><br><br></div>';			
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
		<td nowrap class="opcao_tabela"><div align="left">
		<?
		if ($_SESSION['cs_nivel_administracao']==1)
			{
			?>
			<a href="detalhes_local.php?id_local=<? echo $row['id_local'];?>">
			<?
			}

		echo $row['sg_local'];
		if ($_SESSION['cs_nivel_administracao']==1)
			{
			?>
			</a>
			<?
			}
			?>
		
		</div></td>
		<td nowrap>&nbsp;</td>
		<td nowrap class="opcao_tabela"><div align="left">
		
		<?
		if ($_SESSION['cs_nivel_administracao']==1)
			{
			?>
			<a href="detalhes_local.php?id_local=<? echo $row['id_local'];?>">
			<?
			}
		echo $row['nm_local'];
		if ($_SESSION['cs_nivel_administracao']==1)
			{
			?>
			</a>
			<?
			}
			?>
		</div></td>
		<td nowrap>&nbsp;</td>
		<td nowrap class="opcao_tabela"><div align="right"><? echo $arrTotaisRedes[$row['id_local']]; ?></div></td>
		<td nowrap class="opcao_tabela">&nbsp;</td>
		<td nowrap class="opcao_tabela"><div align="right"><? echo $arrTotaisUsuariosPrimarios[$row['id_local']]; ?></div></td>
		<td nowrap class="opcao_tabela">&nbsp;</td>
		<td nowrap class="opcao_tabela"><div align="right"><? echo $arrTotaisUsuariosSecundarios[$row['id_local']]; ?></div></td>
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
  	<input name="submit" type="submit" id="submit" value="<?=$oTranslator->_('Incluir Informacoes de Novo Local');?>" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>  
  	</div></td>
  	</tr>
	</table>
    </form>
	<p>&nbsp;</p>
	</body>
	</html>
