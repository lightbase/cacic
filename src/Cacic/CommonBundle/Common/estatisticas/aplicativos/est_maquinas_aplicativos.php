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
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

//Mostrar computadores baseados no tipo de pesquisa solicitada pelo usuário
require_once('../../include/library.php');
AntiSpy('1,2,3,4');
?>

<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
<title><?php echo $oTranslator->_('Estatisticas de sistemas monitorados');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF">
<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td class="cabecalho_rel"<?php echo $oTranslator->_('Estatisticas de sistemas monitorados'); ?></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td class="descricao"><p><?php echo $oTranslator->_('Gerado em');?> <?php echo date("d/m/Y - H:i"); ?></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?php
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
	$tit_tabela = 'Versão: '. $_SESSION['cri_pesquisa'];
	}	
elseif($_GET['telicenca'])
	{
	$_SESSION['cri_pesquisa']= $_GET['telicenca'];
	$sql = " AND b.te_licenca = '" . $_SESSION['cri_pesquisa'] . "'";
	$tit_tabela = 'Nº Licença: '. $_SESSION['cri_pesquisa'];
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
    <td align="center" nowrap class="cabecalho_secao"><?php echo $_SESSION['nm_versao'] ?></td>
  </tr>
</table>
<br>
<?php

$query = "SELECT 	computadores.id_so, 
					computadores.te_nome_computador, 
					computadores.te_ip_computador, 
					computadores.te_node_address, 
					computadores.id_computador, 					
					computadores.dt_hr_ult_acesso,
		   			b.te_versao, 
					b.te_licenca ".
					$_SESSION['select'] ." 
		  FROM 		computadores, 
		  			aplicativos_monitorados b ".
					$_SESSION['from'] ." 
		  WHERE 	computadores.id_so IN (".$_SESSION["so_selecionados"].") AND 
		  			computadores.id_computador = b.id_computador ". 
					$sql ." AND			  		
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
	<?php echo $tit_tabela ?> </td>
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
             <a href="est_maquinas_aplicativos.php?teversao=<?php echo $_SESSION['te_versao'] ?>&nmversao=<?php echo $_SESSION['nm_versao'] ?>&idversao=<?php echo $_SESSION['id_aplicativo'] ?>&campo=te_nome_computador">
                <?php echo $oTranslator->_('Nome da maquina');?>
             </a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">
             <a href="est_maquinas_aplicativos.php?teversao=<?php echo $_SESSION['te_versao'] ?>&nmversao=<?php echo $_SESSION['nm_versao'] ?>&idversao=<?php echo $_SESSION['id_aplicativo'] ?>&campo=te_ip">
               <?php echo $oTranslator->_('IP');?>
             </a></div></td>
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Local');?></div></td>		  
          <td nowrap >&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><div align="center">
             <a href="est_maquinas_aplicativos.php?teversao=<?php echo $_SESSION['te_versao'] ?>&nmversao=<?php echo $_SESSION['nm_versao'] ?>&idversao=<?php echo $_SESSION['id_aplicativo'] ?>&campo=dt_hr_ult_acesso">
                <?php echo $oTranslator->_('ultima coleta');?>
             </a></div></td>
          <td nowrap >&nbsp;</td>
        </tr>
        <?php  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {		  
	 	?>
        <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?> nowrap> 
          <td nowrap>&nbsp;</td>
          <td nowrap class=""><div align="left"><?php echo $NumRegistro; ?></div></td>
          <td nowrap class="opcao_tabela"><a href="../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo $row['te_nome_computador']; ?></a></td>
          <td nowrap class="opcao_tabela"><div align="left"></div></td>
          <td nowrap class="opcao_tabela"><?php echo $row['te_ip_computador']; ?></td>
          <td nowrap class="opcao_tabela">&nbsp;</td>
          <td nowrap class="opcao_tabela"><?php echo $row['SgLocal']; ?></td>
          <td nowrap class="opcao_tabela">&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="center"><?php echo date("d/m/Y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></div></td>
          <td nowrap class="opcao_tabela">&nbsp;</td>
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
    <td class="ajuda">
       <?php echo $oTranslator->_('Clique sobre o nome da maquina para ver os detalhes da mesma');?>
    </td>
  </tr>
</table>
<br><br>
<table><tr><td class="descricao_rel">
<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?php echo $oTranslator->_('Gerado por');?> <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</td></tr></table>
</body>
</html>
