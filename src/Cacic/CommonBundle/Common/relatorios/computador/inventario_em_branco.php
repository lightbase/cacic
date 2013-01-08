<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

//Mostrar computadores com nomes repetidos na base
require_once('../../include/library.php');

$titulo = $oTranslator->_('Relatorio de Maquinas com Inventario em Branco');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $titulo;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<link href="<?php echo CACIC_URL?>/include/css/cacic.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF">
       <font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
         <strong><?php echo $titulo;?></strong>
       </font>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
       <?php echo $oTranslator->_('Gerado em') . ' ' . date("d/m/Y - H:i"); ?></font></p>
    </td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?php
conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';
?>
<?php
	 $query = "SELECT a.te_nome_computador as nm_maquina, 
                          a.te_node_address, 
                          a.id_so, a.te_ip_computador, 
                          a.dt_hr_ult_acesso, 
                          te_cpu_desc,
                          a.te_versao_cacic,
						  a.id_computador  
		FROM computadores a 
		WHERE (a.id_computador NOT IN
			(SELECT DISTINCT id_computador 
			 FROM softwares_inventariados_estacoes))
		ORDER BY a.dt_hr_ult_acesso, te_cpu_desc, a.te_nome_computador"; 
	$result = mysql_query($query) or die($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('computadores')));
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td align="center" nowrap></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="5" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td class="cabecalho_tabela" align="center"  nowrap>&nbsp;&nbsp;</td>
          <td class="cabecalho_tabela" align="center"  nowrap><div align="left"></div></td>
          <td class="cabecalho_tabela" align="center"  nowrap>&nbsp;&nbsp;</td>
          <td class="cabecalho_tabela" align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><?php echo $oTranslator->_('Nome da Maquina');?></div></td>
          <td class="cabecalho_tabela" nowrap >&nbsp;&nbsp;</td>
	  <td class="cabecalho_tabela" nowrap ><div align="center"><?php echo $oTranslator->_('Endereco IP');?></div></td>
	  <td class="cabecalho_tabela" nowrap >&nbsp;&nbsp;</td>
	  <td class="cabecalho_tabela" nowrap ><div align="center"><?php echo $oTranslator->_('Ultima Coleta');?></div></td>
	  <td class="cabecalho_tabela" nowrap >&nbsp;&nbsp;</td>
  	  <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Versao do agente principal');?></div></td>
  	  <td nowrap class="cabecalho_tabela" >&nbsp;&nbsp;</td>
	  <td class="cabecalho_tabela" nowrap ><div align="center"><?php echo $oTranslator->_('CPU');?></div></td>
	  <td class="cabecalho_tabela" nowrap >&nbsp;&nbsp;</td>

        </tr>
        <?php  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td class="dado_med_sem_fundo" nowrap>&nbsp;&nbsp;</td>
          <td class="dado_med_sem_fundo" nowrap><div align="left"><?php echo $NumRegistro; ?></div></td>
          <td class="dado_med_sem_fundo" nowrap>&nbsp;&nbsp;</td>
          <td class="dado_med_sem_fundo" nowrap><div align="left"><a href="../../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo $row['nm_maquina']; ?></div></td>
          <td class="dado_med_sem_fundo" nowrap>&nbsp;&nbsp;</td>
	  	  <td class="dado_med_sem_fundo" align="center" nowrap><?php echo $row['te_ip_computador']; ?></td>
	  	  <td class="dado_med_sem_fundo" nowrap>&nbsp;&nbsp;</td>
	  	  <td class="dado_med_sem_fundo" align="center" nowrap><?php echo date("d/m/Y H:i", strtotime($row['dt_hr_ult_acesso'])); ?></td>
	  	  <td class="dado_med_sem_fundo" nowrap>&nbsp;&nbsp;</td>
	  	  <td class="dado_med_sem_fundo" align="center" wrap><?php echo $row['te_versao_cacic']; ?></td>
	  	  <td class="dado_med_sem_fundo" nowrap>&nbsp;&nbsp;</td>
	  	  <td class="dado_med_sem_fundo" align="center" wrap><?php echo $row['te_cpu_desc']; ?></td>
	  	  <td class="dado_med_sem_fundo" nowrap>&nbsp;&nbsp;</td>
          <?php 
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
    <td height="10">
       <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
         <?php echo $oTranslator->_('Clique sobre o nome da maquina para ver os detalhes');?>
       </font>
    </td>
  </tr>
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
   <?php echo $oTranslator->_('Gerado por');?> - <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
