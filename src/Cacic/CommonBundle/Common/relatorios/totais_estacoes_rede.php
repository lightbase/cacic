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
/* 
foreach($HTTP_GET_VARS as $i => $v) 
	{
	echo 'i='.$i.'<br>';
	echo 'v='.$v.'<br>';	
	}
*/ 
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
<title>Totais de Esta&ccedil;&otilde;es por Sistema Operacional</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="self.close();">
<table width="100%" border="0" align="center">
  <tr> 
      <td nowrap class="label_vermelho" align="center"><font color="#004080" size="4"><strong><u>Totais 
        de Esta&ccedil;&otilde;es por Sistema Operacional</u></strong></font><br>
        <br>
	</td>
	</tr>
	<tr>
      <td align="center">
	  <?php if ($_GET['nm_local']) 	echo '<font color="#400040"><strong>Localização:</strong></font><br><font color="#804040">'	.$_GET['nm_local'].'</font><br><br>';
	  if ($_GET['nm_subnet'])  		echo '<font color="#400040"><strong>SubRede:</strong></font><br><font color="#804040">'  		.$_GET['nm_subnet'].'</font><br><br>'; 	  
	  if ($_GET['nm_workgroup']) 	echo '<font color="#400040"><strong>WorkGroup:</strong></font><br><font color="#804040">'		.$_GET['nm_workgroup'].'</font>'; 
	  ?>
	</td>
	
  </tr>
  <tr> 
    <td class="descricao"></td>
  </tr>
</table>

<table width="50%" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr> 
    <td valign="top"> 

        <table width="19%" border="1" align="center" cellpadding="0" cellspacing="1">
          <tr> 
            <td colspan="2"><div align="center"><strong>S.O.</strong></div></td>
            <td><div align="right"><strong>Total</strong></div></td>
          </tr>
          <?php
		  $so_estacoes = explode('_',$_GET['nu_totais_estacoes']);
		  $total = 0;
		  $contador = 1;
		  for ($i=0;$i<=count($so_estacoes);$i++)
		  	{
			$total += $so_estacoes[$i];			
			if ($so_estacoes[$i]>0)
				{			
				echo '<tr><td>&nbsp;'.$contador.'&nbsp;</td><td><div align="center"><img src="../imgs/os_';
				$contador++;
				if 	   ($i==0) 	echo 'win95';
				elseif ($i==1) 	echo 'win95_osr2';
				elseif ($i==2) 	echo 'win98';
				elseif ($i==3) 	echo 'win98_se';
				elseif ($i==4) 	echo 'winme';
				elseif ($i==5) 	echo 'winnt';
				elseif ($i==6) 	echo 'win2k';
				elseif ($i==7) 	echo 'winxp';
				elseif ($i==8) 	echo 'linux';
				echo '.gif" width="35" height="31"></div></td><td><div align="right">'.$so_estacoes[$i].'</div></td></tr>';
				}
			}
		?>
          <tr> 
            <td colspan="2"><div align="center"><strong>Total</strong></div></td>
            <td><div align="right"><strong><?php echo $total;?> </strong></div></td>
          </tr>
        </table>
          <p align="center"> <br>
            <input name="Ok" type="submit" id="Ok" value="  OK  ">
          </p>
        </table>  
</table>
</form>
</body>
</html>