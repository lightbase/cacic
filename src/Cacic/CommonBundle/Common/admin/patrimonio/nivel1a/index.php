<?php 
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

include_once "../../../include/library.php";

AntiSpy();

if ($_POST['incluirUON1a']) 
	{
  	header ("Location: incluir_nivel1a.php");
	break;
	}

Conecta_bd_cacic();

$where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND id_local = '.$_SESSION['id_local']:'');
$where = ' AND id_local = '.$_SESSION['id_local'];
$queryCONFIG = "SELECT 		DISTINCT 
							id_etiqueta,
							te_etiqueta,
							te_plural_etiqueta
		  		FROM 		patrimonio_config_interface patcon
				WHERE		patcon.id_etiqueta in ('".'etiqueta1'."','".'etiqueta1a'."') ".
							$where. "
		  		ORDER BY 	id_etiqueta";

$resultCONFIG 		= mysql_query($queryCONFIG);

session_register('etiqueta1');
session_register('plural_etiqueta1');
$_SESSION['etiqueta1']			= mysql_result($resultCONFIG,0,'te_etiqueta');
$_SESSION['plural_etiqueta1']	= mysql_result($resultCONFIG,0,'te_plural_etiqueta');


session_register('etiqueta1a');
session_register('plural_etiqueta1a');
$_SESSION['etiqueta1a']			= mysql_result($resultCONFIG,1,'te_etiqueta');
$_SESSION['plural_etiqueta1a']	= mysql_result($resultCONFIG,1,'te_plural_etiqueta');

$query = 'SELECT 	uon1.id_unid_organizacional_nivel1,
					uon1.nm_unid_organizacional_nivel1,
					uon1a.id_unid_organizacional_nivel1a,
					uon1a.nm_unid_organizacional_nivel1a
		  FROM 		unid_organizacional_nivel1 uon1,
		  			unid_organizacional_nivel1a uon1a		  
		  WHERE		uon1.id_unid_organizacional_nivel1 = uon1a.id_unid_organizacional_nivel1
		  ORDER BY 	nm_unid_organizacional_nivel1,nm_unid_organizacional_nivel1a';
$result = mysql_query($query);

$titulo = $oTranslator->_('Cadastro de').' '. $_SESSION['plural_etiqueta1a'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../include/css/cacic.css">
<body background="../../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
<title><?php echo $titulo;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<form name="form1" method="post" action="">
<table width="85%" border="0" align="center">
  <tr> 
    <td class="cabecalho">
      <?php echo $titulo;?> 
      (<?php echo $oTranslator->_('Unidade Organizacional Nivel 1a');?>)
    </td>
  </tr>
  <tr> 
    <td class="descricao"><?php echo $oTranslator->_('Modulo para cadastramento de Unidades Organizacionais de Nivel 1a');?></td>
  </tr>
  <tr> 
    <td class="destaque_laranja">
       <u><?php echo $oTranslator->_('Importante');?>:</u>
        <?php echo $oTranslator->_('A inclusao de %1 e restrita ao Administrador do sistema', array($_SESSION['plural_etiqueta1a']));?>
    </td>
  </tr>
  
</table>
<br><table width="292" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td><div align="center">
			<?php if ($_SESSION['cs_nivel_administracao'] == 1)
			{
			?>
          <input name="incluirUON1a" type="submit" id="incluirUON1a" value="<?php echo $oTranslator->_('Incluir');?> <?php echo $_SESSION['etiqueta1a'];?>">
          <?php
		  }
		  ?>

        
      </div></td>
  </tr>
  <tr> 
    <td height="10">&nbsp;</td>
  </tr>
  <tr> 
    <td height="10" class="ajuda"><?php echo $msg;?></td>
  </tr>

<tr>
    <td height="1" bgcolor="#333333"></td>
</tr>	
  <tr>   
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><?php echo $_SESSION['plural_etiqueta1'].'/'.$_SESSION['plural_etiqueta1a'];?></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap ><div align="left"></div></td>
          <td nowrap >&nbsp;</td>
        </tr>
<?php if(mysql_num_rows($result)==0) {
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				'.$oTranslator->_('Nenhuma Unidade Organizacional de Nivel %1 cadastrada',array('1a')).'
			</font><br><br></div>';
			
}
else {
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) 
		{		  
	 	?>
			<tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><?php echo $NumRegistro; ?></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><a href="detalhes_nivel1a.php?id_unid_organizacional_nivel1a=<?php echo $row['id_unid_organizacional_nivel1a'];?>"><?php echo $row['nm_unid_organizacional_nivel1'].'/'.$row['nm_unid_organizacional_nivel1a']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap>&nbsp;</td>			  
			  <td nowrap>&nbsp;</td>			  			  
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
  <tr> 
    <td><div align="center">
			<?php if ($_SESSION['cs_nivel_administracao'] == 1)
			{
			?>

          <input name="incluirUON1a" type="submit" id="incluirUON1a" value="<?php echo $oTranslator->_('Incluir');?> <?php echo $_SESSION['etiqueta1a'];?>" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
			<?php
			}
			?>
        
      </div></td>
  </tr>
</table>
        </form>
<p>&nbsp;</p>
</body>
</html>