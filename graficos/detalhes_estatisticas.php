<?
session_start();
require('../include/config.php');
require('../include/library.php'); 
//foreach($HTTP_GET_VARS as $i => $v) 
//	{
//	echo 'i => '.$i.' v => '.$v.'<br>';
//	}

$select = '';
$from   = ' FROM locais l ';
$where  = '';

// Se for Supervisão...
if ($_SESSION["cs_nivel_administracao"] == 0 || $_SESSION["cs_nivel_administracao"] == 3)
	{
	$select  = ',u.te_locais_secundarios	';
	$from   .= ' ,usuarios u ';
	$where   = ' WHERE u.id_usuario = '.$_SESSION["id_usuario"].' AND
			          (l.id_local = u.id_local OR
			          l.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';		
	}

$query_desdobra_estatisticas = 'SELECT   l.id_local,
									     l.sg_local,
									     l.nm_local '.$select . 
							    $from . 
							    $where . '						 
							    ORDER BY l.sg_local';
conecta_bd_cacic();
//echo $query_desdobra_estatisticas . '<br>';
$result_desdobra_estatisticas = mysql_query($query_desdobra_estatisticas) or die('Problemas na consulta a Usuários e Locais ou sua sessão expirou!');

?>

<table width="100%" cellspacing="0" cellpadding="0" align="center">
  		<tr valign="top"> 
    	<td><div align="center"><strong><h3><? echo $_GET['te_title'];?></h3></strong></div></td>
  		</tr>
  		<tr> 
    	<td>&nbsp;</td>
  		</tr>


	<?
	$_SESSION['in_grafico'] = $_GET['in_grafico'];
	while ($row = mysql_fetch_array($result_desdobra_estatisticas))
		{
		?>
  		<tr> 
    	<td bgcolor="#CCCCCC"><div align="center">Local: <strong><? echo $row['sg_local'].' - '.$row['nm_local'];?></strong>
        </div></td>
  		</tr>	
  		<tr> 
    	<td>
		<?		
		echo '<a href="../relatorios/software/rel_software.php?orderby='.($_GET['in_grafico']=='acessos'?'6':'4').'&principal='.$_GET['in_grafico'].'&id_local='.$row['id_local'].'&sg_local='.$row['sg_local'].'&nm_local='.$row['nm_local'].'">';		
		$where = ' AND redes.id_local = '.$row['id_local']. ' ';		

		if (substr_count($_GET['te_exibe_graficos'],'['.$_GET['in_grafico'].']')>0)
			{
			?>
			<img src="pie_<? echo $_GET['in_grafico'];?>.php?where=<? echo $where;?>" border="no" title="CLIQUE PARA LISTAR ESSES COMPUTADORES">
			<?
			}
		else
			require "../include/exibe_consultas_texto.php";		

		?>
		</a>				
		</td>
  		</tr>
  		<tr> 
    	<td>&nbsp;</td>
  		</tr>	
		<?
		}
		?>
</table>
<p>&nbsp;</p>
<p>
        
<form name="form1" method="post" action="">
  <div align="center">
    <input name="Submit_Fecha" type="submit" id="Submit_Fecha" onClick="self.close();" value="   Fecha   ">
  </div>
</form>  
</p>
