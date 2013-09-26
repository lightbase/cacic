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
if ($_POST['submitNewDevice'] && $_SESSION['cs_nivel_administracao']==1) 
	header ("Location: incluir_usb_device.php");
	
if ($_POST['submitNewVendor'] && $_SESSION['cs_nivel_administracao']==1) 
	header ("Location: incluir_usb_vendor.php");	

include_once "../../include/library.php";
AntiSpy();

Conecta_bd_cacic();
if($_POST['ImportarInformacoesTodosDispositivos']<>'' && $_SESSION['cs_nivel_administracao']==1) 
	{
	header ("Location: importar_usb_device_informations.php?pBoolTotalImport=1");
	}
elseif($_POST['ImportarInformacoesDispositivosDesconhecidos']<>'' && $_SESSION['cs_nivel_administracao']==1) 
	{
	header ("Location: importar_usb_device_informations.php?pBoolTotalImport=0");
	}	
else
	{
	$query = 'SELECT 	v.id_vendor,
						v.nm_vendor,
						d.id_device,
						d.nm_device
			  FROM 		usb_devices d,
						usb_vendors v
			  WHERE		d.id_vendor = v.id_vendor
			  ORDER BY '; // O valor para ORDER será concatenado abaixo...
			  
	$query .= ($_GET['cs_ordem']<>''?$_GET['cs_ordem']:'id_vendor,id_device');
	$result = mysql_query($query);
	$msg = '<div align="center">
			<font color="#c0c0c0" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Clique nas Colunas para Ordenar</font><br><br></div>';				
	?>
	
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
	<title>Cadastro de Servidor de Autentica&ccedil;&atilde;o</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	
	<body background="../../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
	<form name="form1" method="post" action="">
	<table width="85%" border="0" align="center">
	  <tr> 
		  <td class="cabecalho">Cadastro de Dispositivos USB</td>
	  </tr>
	  <tr> 
		  <td class="descricao">Neste m&oacute;dulo dever&atilde;o ser cadastrados 
		  os dispositivos USB para uso pelo m&oacute;dulo de detec&ccedil;&atilde;o.</td>
	  </tr>
	</table>
	<br><table border="0" align="center" cellpadding="0" cellspacing="1">
    <?php if ($_SESSION['cs_nivel_administracao']==1)
		{		
		?>    
      	<tr> 
    	<td><p align="center"> 
        <input name="ImportarInformacoesTodosDispositivos" type="submit" value="  Importar Informa&ccedil;&otilde;es de Todos os Dispositivos (Demorado)  " id="ImportarInformacoesTodosDispositivos">
      	</p>
  		</td>
  		</tr>
      	<tr> 
    	<td><p align="center"> 
        <input name="ImportarInformacoesDispositivosDesconhecidos" type="submit" value="  Importar Informa&ccedil;&otilde;es Apenas de Dispositivos Desconhecidos  " id="ImportarInformacoesDispositivosDesconhecidos">
      	</p>
  		</td>
  		</tr>
        
	  	<tr> 
    	<td><p align="center">
        <input name="submitNewVendor" type="submit" id="submitNewVendor" value="Incluir Fabricante de Dispositivos USB"></p>
         </tr>
	  	<tr> 
    	<td><p align="center">
        <input name="submitNewDevice" type="submit" id="submitNewDevice" value="Incluir Dispositivo USB"></p>
        <?php
		}
		?>
      </div></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10"><?php echo $msg;?></td>
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
          <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=id_vendor,id_device">Fabricante</a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="left"><a href="index.php?cs_ordem=id_device">Dispositivo</a></div></td>
          <td nowrap class="cabecalho_tabela">&nbsp;</td>
          </tr>
  	<tr> 
    <td height="1" bgcolor="#333333" colspan="7"></td>
  	</tr>
		
<?php  
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
	
	$strFabricanteAtual = '';
	$strFabricanteAnterior = '';
	while($row = mysql_fetch_array($result)) 
		{
		?>
		<tr 
		<?php 
		if ( (stripos2($row['nm_vendor'],'Desconhecido',false)) || (stripos2($row['nm_device'],'Desconhecido',false)) )
			echo 'bgcolor="yellow"';
		else if ($Cor) 
			echo 'bgcolor="#E1E1E1"';
		?>>
		<td nowrap>&nbsp;</td>
		<td nowrap class="opcao_tabela"><div align="left"><?php echo $NumRegistro; ?></div></td>
		<td nowrap>&nbsp;</td>
		<td nowrap class="opcao_tabela"><div align="left">
        <?php
		
		if ($_SESSION['cs_nivel_administracao']==1)
			{
			?>
			<a href="detalhes_usb_vendor.php?id_vendor=<?php echo trim($row['id_vendor']);?>">
			<?php
			}
		$strFabricanteAtual = $row['id_vendor'].' - '.$row['nm_vendor'];
		if($strFabricanteAnterior <> $strFabricanteAtual)
			{
			$strFabricanteAnterior = $strFabricanteAtual;
			echo $strFabricanteAtual;
			}
		if ($_SESSION['cs_nivel_administracao']==1)
			{
			?>
			</a>
			<?php
			}
			?>

		</div></td>
		<td nowrap>&nbsp;</td>
		<td nowrap class="opcao_tabela"><div align="left">
<?php
		if ($_SESSION['cs_nivel_administracao']==1)
			{
			?>
		  <a title="Clique para edição e visualização de detalhes do dispositivo, como a utilização nos últimos 3 dias" href="detalhes_usb_device.php?id_device=<?php echo trim($row['id_device']);?>&id_vendor=<?php echo trim($row['id_vendor']);?>">
		    <?php
			}
		echo $row['id_device'].' - '.$row['nm_device'];
		if ($_SESSION['cs_nivel_administracao']==1)
			{
			?>
		    </a>
		  <?php
			}
			?>

		  </div></td>
		<td nowrap class="opcao_tabela">&nbsp;</td>
		<?php 
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
    <td height="10"><?php echo $msg;?></td>
  	</tr>
    <?php if ($_SESSION['cs_nivel_administracao']==1)
		{		
		?>    
      	<tr> 
    	<td><p align="center"> 
        <input name="submitNewDevice" type="submit" id="submitNewDevice" value="Incluir Dispositivo USB">
      	</p>
  		</td>
  		</tr>
      	<tr> 
    	<td><p align="center"> 
        <input name="submitNewVendor" type="submit" id="submitNewVendor" value="Incluir Fabricante de Dispositivos USB">
      	</p>
  		</td>
  		</tr>
        
	  	<tr> 
    	<td><p align="center">
        <input name="ImportarInformacoesDispositivosDesconhecidos" type="submit" value="  Importar Informa&ccedil;&otilde;es Apenas de Dispositivos Desconhecidos  " id="ImportarInformacoesDispositivosDesconhecidos"></p>
         </tr>
	  	<tr> 
    	<td><p align="center">
        <input name="ImportarInformacoesTodosDispositivos" type="submit" value="  Importar Informa&ccedil;&otilde;es de Todos os Dispositivos (Demorado)  " id="ImportarInformacoesTodosDispositivos"></p>
        <?php
		}

	?>
	</table>
    </form>
	<p>&nbsp;</p>
	</body>
	</html>
	<?php
	}
	?>