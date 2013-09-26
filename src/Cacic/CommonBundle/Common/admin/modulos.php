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
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

require_once('../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administra��o
// 2 - Gest�o Central
// 3 - Supervis�o

conecta_bd_cacic();

$queryTotalRedes   	= "SELECT 		count(id_rede) as intTotalRedes FROM redes re,locais lo ";
$whereTotalRedes   	= "WHERE   		re.id_local = lo.id_local ";

if ($_SESSION['cs_nivel_administracao'] <> 1 && $_SESSION['cs_nivel_administracao'] <> 2)
	$whereTotalRedes .= ' and re.id_local = '.$_SESSION['id_local'].' or re.id_local in (' . $_SESSION['te_locais_secundarios']. ')';
//echo $queryTotalRedes . $whereTotalRedes . '<br>';
$resultTotalRedes = mysql_query($queryTotalRedes . $whereTotalRedes);
$rowTotalRedes    = mysql_fetch_array($resultTotalRedes);
$intTotalRedes	  = $rowTotalRedes['intTotalRedes'];

$selectAcoes   	= "SELECT 	acoes.id_acao,
							acoes.te_descricao_breve,
							acoes.te_descricao,
							acoes.te_nome_curto_modulo 
				   FROM 	acoes 
				   WHERE	acoes.cs_opcional = 'S' 
				   ORDER BY acoes.id_acao";
$resultAcoes = mysql_query($selectAcoes) or die($oTranslator->_('kciq_msg select on table fail', array('acoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true));


$whereAcaoRede = '';
if ($_SESSION['cs_nivel_administracao'] <> 1 && $_SESSION['cs_nivel_administracao'] <> 2)
	{
	$whereAcaoRede = 'WHERE re.id_local = '.$_SESSION['id_local'];

	if ($_SESSION['te_locais_secundarios']<>'')
		{
		// Fa�o uma inser��o de "(" para ajuste da l�gica para consulta
		$whereAcaoRede .= ' OR re.id_local IN ('.$_SESSION['te_locais_secundarios'].')';	
		}
	}
	
// Mostrarei a quantidade de redes associadas � a��o - Anderson Peterle - Mar�o/2008
$queryAcaoRede  = 'SELECT 		ac.id_acao,
								count(ar.id_rede) intTotalRedesNaAcao 
		  			FROM 		acoes_redes ar, acoes ac
					WHERE		ar.id_acao = ac.id_acao
					GROUP BY    ar.id_acao';
$resultAcaoRede = mysql_query($queryAcaoRede);
$arrAcoes = array();
while ($rowAcaoRede = mysql_fetch_array($resultAcaoRede))
	$arrAcoes[$rowAcaoRede['id_acao']] = $rowAcaoRede['intTotalRedesNaAcao'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
<title><?php echo $oTranslator->_('Modulos');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
<table width="85%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?php echo $oTranslator->_('Modulos');?></td>
  </tr>
  <tr> 
    <td class="descricao"><p>
    	<?php echo $oTranslator->_('kciq_msg Modulos help');?>
    </td>
  </tr>
  <tr> 
    <td>
      <fieldset>
      	<legend><?php echo $oTranslator->_('Legenda');?></legend>
		<img src="../imgs/alerta_verde.gif" /> <?php echo $oTranslator->_('Executado em todas as redes');?><br>        
		<img src="../imgs/alerta_amarelo.gif" /> <?php echo $oTranslator->_('Executado em parte das redes');?><br>        
		<img src="../imgs/alerta_vermelho.gif" /> <?php echo $oTranslator->_('Executado em nenhuma rede');?>
      </fieldset>
    </td>
  </tr>
</table>
<table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
	<tr> 
	  <td height="1" colspan="2" bgcolor="#e7e7e7"></td>
	</tr>
        <tr> 
          <td height="5" colspan="2"></td>
        </tr>
	
  <tr> 
    <td>
<?php  
while ($rowAcoes = mysql_fetch_array($resultAcoes)) 
	{
	$img = '';
	if($arrAcoes[$rowAcoes['id_acao']] == $intTotalRedes)
		$img = '<img src="../imgs/alerta_verde.gif" title="'.$oTranslator->_('Executado em todas as redes').'" width="8" height="8" border="0">';	
	elseif ($arrAcoes[$rowAcoes['id_acao']] &&  ($arrAcoes[$rowAcoes['id_acao']] < $intTotalRedes))
		$img = '<img src="../imgs/alerta_amarelo.gif" title="'.$oTranslator->_('Executado apenas nas redes selecionadas').'" width="8" height="8" border="0">';
	else
		$img = '<img src="../imgs/alerta_vermelho.gif" title="'.$oTranslator->_('Nao e executado em nenhuma rede').'" width="8" height="8" border="0">';
?>
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
        <tr> 
          <td class="label"><a href="acoes.php?id_acao=<?php echo $rowAcoes['id_acao']?>&te_descricao_breve=<?php echo $rowAcoes['te_descricao_breve']?>&te_descricao=<?php echo $rowAcoes['te_descricao']?>"><?php echo $img. ' ' .$rowAcoes['te_descricao_breve'].' (Total de Redes Selecionadas para a Acao: '.number_format($arrAcoes[$rowAcoes['id_acao']], 0, '', '.').')';?></a></td>
        </tr>
        <tr> 
          <td valign="top" scope="top" class="descricao"><div align="left"></div>
            <?php echo $rowAcoes['te_descricao']?>
          </td>
        </tr>
        <tr> 
          <td></td>
        </tr>
        <tr> 
          <td height="1" colspan="2" bgcolor="#e7e7e7"></td>
        </tr>
        <tr> 
          <td height="5" colspan="2"></td>
        </tr>
		
      </table>
	<?php
	}
	?>
	</td>
  </tr>
  <tr> 
    <td><div align="center"></div></td>
  </tr>
  <tr> 
    <td><div align="center"></div></td>
  </tr>
</table>
</body>
</html>
