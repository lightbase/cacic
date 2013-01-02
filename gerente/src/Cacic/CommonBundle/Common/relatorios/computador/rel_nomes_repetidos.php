<?
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

//Mostrar computadores baseados no tipo de pesquisa solicitada pelo usuário
require_once('../../include/library.php');

$titulo = $oTranslator->_('Relatorio de Maquinas com Nome Repetido');

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
<link href="<?=CACIC_URL?>/include/cacic.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
      <strong><?php echo $titulo;?></strong></font>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
        <?php echo $oTranslator->_('Gerado em') . ' ' . date("d/m/Y à\s H:i"); ?></font></p>
    </td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?
conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';
?>
<?
	 $query = "SELECT a.te_nome_computador, a.te_node_address, a.id_so,
   			a.te_ip, a.dt_hr_ult_acesso, te_versao_cacic, te_versao_gercols
		FROM computadores a
		WHERE a.te_nome_computador = '" . $_GET['te_nome_computador'] . "'";
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
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td class="cabecalho_tabela" align="center"  nowrap>&nbsp;</td>
          <td class="cabecalho_tabela" align="center"  nowrap></td>
          <td class="cabecalho_tabela" align="center"  nowrap>&nbsp;</td>
          <td class="cabecalho_tabela" align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><?=$oTranslator->_('Nome da Maquina');?></div></td>
          <td class="cabecalho_tabela" nowrap >&nbsp;</td>
	  <td class="cabecalho_tabela" nowrap ><div align="center"><?=$oTranslator->_('Endereco IP');?></div></td>
  	  <td nowrap class="cabecalho_tabela" >&nbsp;&nbsp;</td>
  	  <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Agente principal');?></div></td>
  	  <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Gerente de coletas');?></div></td>
	  <td class="cabecalho_tabela" nowrap ><div align="center"><?=$oTranslator->_('Ultima Coleta');?></a></div></td>
	  <td class="cabecalho_tabela" nowrap >&nbsp;</td>
	  <td class="cabecalho_tabela" nowrap >
               <?
                  if ($_SESSION["cs_nivel_administracao"] == 1 or
                      $_SESSION["cs_nivel_administracao"] == 2 or
                      $_SESSION["cs_nivel_administracao"] == 3)
                    echo $oTranslator->_('Remover computador');
               ?>
               &nbsp;
          </td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td class="dado_med_sem_fundo" nowrap>&nbsp;</td>
          <td class="dado_med_sem_fundo" nowrap><div align="left"><? echo $NumRegistro; ?></div></td>
          <td class="dado_med_sem_fundo" nowrap>&nbsp;</td>
          <td class="dado_med_sem_fundo" nowrap><div align="left"><a href="../../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></div></td>
          <td class="dado_med_sem_fundo" nowrap>&nbsp;</td>
	  <td class="dado_med_sem_fundo" nowrap><? echo $row['te_ip']; ?></td>
	  <td class="dado_med_sem_fundo" nowrap>&nbsp;</td>
  	  <td nowrap class="cabecalho_tabela"><div align="center" title="<?=$oTranslator->_('Versao do agente principal');?>"><? echo $row['te_versao_cacic'];?></div></td>
  	  <td nowrap class="cabecalho_tabela"><div align="center" title="<?=$oTranslator->_('Versao do gerente de coletas');?>"><? echo $row['te_versao_gercols'];?></div></td>
	  <td class="dado_med_sem_fundo" nowrap><div align="center"><? echo date("d/m/Y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></div></td>
	  <td class="dado_med_sem_fundo" nowrap>&nbsp;</td>
          <td class="dado_med_sem_fundo" nowrap>
            <div align="center">
               <?
                  if ($_SESSION["cs_nivel_administracao"] == 1 or
                      $_SESSION["cs_nivel_administracao"] == 2 or
                      $_SESSION["cs_nivel_administracao"] == 3)
                   {
               ?>
                     <a href="../../../admin/remove_computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank" title="<?=$oTranslator->_('Remover computador') ;?>">
                        <img src="../../imgs/exclui_computador.gif" width=16 height=16 border=0 > 
                     </a>
               <?
                   }
               ?>
               &nbsp;
            </div>
          </td>
          <? 
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
    <td height="10"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Clique 
      sobre o nome da m&aacute;quina para ver os detalhes da mesma</font> </td>
  </tr>
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
