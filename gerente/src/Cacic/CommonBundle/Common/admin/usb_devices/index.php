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
if ($_POST['submit'] && $_SESSION['cs_nivel_administracao']==1) 
	header ("Location: incluir_usb_device.php");

include_once "../../include/library.php";
AntiSpy();

Conecta_bd_cacic();
if($_POST['ImportarInformacoes']<>'' && $_SESSION['cs_nivel_administracao']==1) 
	{
	header ("Location: importar_usb_device_informations.php");
	}
else
	{
	$query = 'SELECT 	*
			  FROM 		usb_devices d,
						usb_vendors v
			  WHERE		d.id_vendor = v.id_vendor
			  ORDER BY  ';
	  
	$query .= ($_GET['cs_ordem']<>''?$_GET['cs_ordem']:'nm_vendor,nm_device');
	$result = mysql_query($query);
	$msg = '<div align="center">
			<font color="#c0c0c0" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Clique nas Colunas para Ordenar</font><br><br></div>';				
	?>
	
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
	<title>Cadastro de Servidor de Autentica&ccedil;&atilde;o</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	
	<body background="../../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
	<form name="form1" method="post" action="">
	<table width="90%" border="0" align="center">
	  <tr> 
		  <td class="cabecalho">Cadastro de Dispositivo USB</td>
	  </tr>
	  <tr> 
		  <td class="descricao">Neste m&oacute;dulo dever&atilde;o ser cadastrados 
		  os dispositivos USB para uso pelo m&oacute;dulo de detec&ccedil;&atilde;o.</td>
	  </tr>
	</table>
	<br><table border="0" align="center" cellpadding="0" cellspacing="1">
    <?
	if ($_SESSION['cs_nivel_administracao']==1)
		{		
		?>    
      	<tr> 
    	<td><div align="center">    
      	<p align="center"> 
        <input name="ImportarInformacoes" type="submit" value="  Importar Informa&ccedil;&otilde;es  " id="ImportarInformacoes">
      	</p>
  		</div></td>
  		</tr>
	    <tr><td>&nbsp;</td></tr>
	  	<tr> 
    	<td><div align="center">        
        <input name="submit" type="submit" id="submit" value="Incluir Informa&ccedil;&otilde;es de Novo Dispositivo USB">
        <?
		}
		?>
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
          <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=nm_vendor,nm_device">Fabricante</a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=nm_device">Dispositivo</a></div></td>
          <td nowrap class="cabecalho_tabela">&nbsp;</td>
          </tr>
  	<tr> 
    <td height="1" bgcolor="#333333" colspan="7"></td>
  	</tr>
		
<?  
if(mysql_num_rows($result)==0) 
	{
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Nenhum dispositivo USB cadastrado ou sua sessão expirou!</font><br><br></div>';			
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
			<a href="detalhes_usb_device.php?id_device=<? echo $row['id_device'];?>">
			<?
			}

		echo $row['id_vendor'].' - '.$row['nm_vendor'];
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
		  <a href="detalhes_usb_device.php?id_device=<? echo $row['id_device'];?>">
		    <?
			}
		echo $row['id_device'].' - '.$row['nm_device'];
		if ($_SESSION['cs_nivel_administracao']==1)
			{
			?>
		    </a>
		  <?
			}
			?>
		  </div></td>
		<td nowrap class="opcao_tabela">&nbsp;</td>
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
    <?
	if ($_SESSION['cs_nivel_administracao']==1)
		{		
		?>
    
  	<tr> 
    <td><div align="center">
  	<input name="submit" type="submit" id="submit" value="Incluir Informa&ccedil;&otilde;es de Novo Dispositivo USB" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>  
  	</div></td>
  	</tr>
    <tr><td>&nbsp;</td></tr>
    
  	<tr> 
    <td><div align="center">    
      <p align="center"> 
        <input name="ImportarInformacoes" type="submit" value="  Importar Informa&ccedil;&otilde;es  " id="ImportarInformacoes">
      </p>
  	</div></td>
  	</tr>
    <?
	}
	?>
	</table>
    </form>
	<p>&nbsp;</p>
	</body>
	</html>
	<?
	}
	?>