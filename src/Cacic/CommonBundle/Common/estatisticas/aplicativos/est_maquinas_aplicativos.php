<?
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
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

//Mostrar computadores baseados no tipo de pesquisa solicitada pelo usu�rio
require_once('../../include/library.php');
AntiSpy('1,2,3,4');
?>

<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title><?=$oTranslator->_('Estatisticas de sistemas monitorados');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td class="cabecalho_rel"<?=$oTranslator->_('Estatisticas de sistemas monitorados'); ?></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td class="descricao"><p><?=$oTranslator->_('Gerado em');?> <? echo date("d/m/Y - H:i"); ?></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?
conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1" colspan="'.($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?'2':'3').'"></td>
         </tr>';



if ($_GET['campo'])
	$orderby = 'ORDER BY '.$_GET['campo'];	
else
	$orderby = 'ORDER BY te_nome_computador';

$_SESSION['id_aplicativo']= $_GET['idaplicativo'];
$_SESSION['nm_versao']= $_GET['nmversao'];

if($_GET['teversao'])
	{
	$_SESSION['cri_pesquisa']= $_GET['teversao'];
	$sql = " AND b.te_versao = '" . $_SESSION['cri_pesquisa'] . "'";
	$tit_tabela = 'Vers�o: '. $_SESSION['cri_pesquisa'];
	}	
elseif($_GET['telicenca'])
	{
	$_SESSION['cri_pesquisa']= $_GET['telicenca'];
	$sql = " AND b.te_licenca = '" . $_SESSION['cri_pesquisa'] . "'";
	$tit_tabela = 'N� Licen�a: '. $_SESSION['cri_pesquisa'];
	}
else if($_GET['cs_car_inst']>0)
	{
	$_SESSION['cri_pesquisa']= $_GET['cs_car_inst'];
	$sql = " AND b.cs_instalado = 'S'";
	$tit_tabela = 'Instalado?';
	}
?>
<table border="0" align="center" width="300" >
  <tr> 
    <td align="center" nowrap class="cabecalho_secao"><? echo $_SESSION['nm_versao'] ?></td>
  </tr>
</table>
<br>
<?

$query = "SELECT 	computadores.id_so, 
					computadores.te_nome_computador, 
					computadores.te_ip, 
					computadores.te_node_address, 
					computadores.dt_hr_ult_acesso,
		   			b.te_versao, 
					b.te_licenca ".
					$_SESSION['select'] ." 
		  FROM 		computadores, 
		  			aplicativos_monitorados b ".
					$_SESSION['from'] ." 
		  WHERE 	computadores.id_so IN (".$_SESSION["so_selecionados"].") AND 
		  			computadores.te_node_address = b.te_node_address AND 
					computadores.id_so = b.id_so " . $sql ." AND			  		
					b.id_aplicativo = '". $_SESSION['id_aplicativo'] ."' ".
					$_SESSION['query_redes']."  
					$orderby ";

$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('aplicativos_monitorados')));
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td align="center" nowrap></td>
  </tr>
  <tr> 
    <td align="left" nowrap class="destaque">
	<? echo $tit_tabela ?> </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td nowrap >&nbsp;</td>
          <td nowrap >&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="center">
             <a href="est_maquinas_aplicativos.php?teversao=<? echo $_SESSION['te_versao'] ?>&nmversao=<? echo $_SESSION['nm_versao'] ?>&idversao=<? echo $_SESSION['id_aplicativo'] ?>&campo=te_nome_computador">
                <?=$oTranslator->_('Nome da maquina');?>
             </a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">
             <a href="est_maquinas_aplicativos.php?teversao=<? echo $_SESSION['te_versao'] ?>&nmversao=<? echo $_SESSION['nm_versao'] ?>&idversao=<? echo $_SESSION['id_aplicativo'] ?>&campo=te_ip">
               <?=$oTranslator->_('IP');?>
             </a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Local');?></div></td>		  
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">
             <a href="est_maquinas_aplicativos.php?teversao=<? echo $_SESSION['te_versao'] ?>&nmversao=<? echo $_SESSION['nm_versao'] ?>&idversao=<? echo $_SESSION['id_aplicativo'] ?>&campo=dt_hr_ult_acesso">
                <?=$oTranslator->_('ultima coleta');?>
             </a></div></td>
          <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {		  
	 	?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?> nowrap> 
          <td nowrap>&nbsp;</td>
          <td nowrap class=""><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap class="opcao_tabela"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></td>
          <td nowrap class="opcao_tabela"><div align="left"></div></td>
          <td nowrap class="opcao_tabela"><? echo $row['te_ip']; ?></td>
          <td nowrap class="opcao_tabela">&nbsp;</td>
          <td nowrap class="opcao_tabela"><? echo $row['SgLocal']; ?></td>
          <td nowrap class="opcao_tabela">&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="center"><? echo date("d/m/Y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></div></td>
          <td nowrap class="opcao_tabela">&nbsp;</td>
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
    <td class="ajuda">
       <?=$oTranslator->_('Clique sobre o nome da maquina para ver os detalhes da mesma');?>
    </td>
  </tr>
</table>
<br><br>
<table><tr><td class="descricao_rel">
<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?=$oTranslator->_('Gerado por');?> <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</td></tr></table>
</body>
</html>
