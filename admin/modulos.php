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
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

conecta_bd_cacic();

$query   = "SELECT 	acoes.id_acao,
					acoes.te_descricao_breve,
					acoes.te_descricao,
					acoes.te_nome_curto_modulo,
					acoes_redes.cs_situacao ";
					
$from    = " FROM 	acoes LEFT JOIN acoes_redes ON (acoes.id_acao = acoes_redes.id_acao) "; 
if ($_SESSION['cs_nivel_administracao'] <> 1 && $_SESSION['cs_nivel_administracao'] <> 2)
	$from = str_replace('acoes_redes.id_acao)','acoes_redes.id_acao AND acoes_redes.id_local = '.$_SESSION['id_local'].') ',$from);

$groupBy = " GROUP BY	acoes.id_acao ";
$orderBy = " ORDER BY 	acoes.id_acao";
if ($_SESSION['te_locais_secundarios']<>'')
	{
	// Faço uma inserção de "(" para ajuste da lógica para consulta
	$query = str_replace('acoes_redes.id_local = ','(acoes_redes.id_local = ',$query);
	$query = str_replace(')',' OR acoes_redes.id_local IN ('.$_SESSION['te_locais_secundarios'].')))',$query);	
	}

$result = mysql_query($query.$from.$groupBy.$orderBy) or die($oTranslator->_('kciq_msg select on table fail', array('acoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true));

$whereAcaoRede = '';
if ($_SESSION['cs_nivel_administracao'] <> 1 && $_SESSION['cs_nivel_administracao'] <> 2)
	{
	$whereAcaoRede = 'WHERE acoes_redes.id_local = '.$_SESSION['id_local'];

	if ($_SESSION['te_locais_secundarios']<>'')
		{
		// Faço uma inserção de "(" para ajuste da lógica para consulta
		$whereAcaoRede .= ' OR acoes_redes.id_local IN ('.$_SESSION['te_locais_secundarios'].')';	
		}
	}
	

// Mostrarei a quantidade de redes associadas à ação - Anderson Peterle - Março/2008
$queryAcaoRede  = 'SELECT 		id_acao,
								count(id_acao) TotalRedesNaAcao 
		  			 FROM 		acoes_redes '.
					 $whereAcaoRede . '
					 GROUP BY   id_acao';
$resultAcaoRede = mysql_query($queryAcaoRede);
$arrAcaoRede = array();
while ($rowAcaoRede = mysql_fetch_array($resultAcaoRede))
	$arrAcaoRede[$rowAcaoRede['id_acao']] = $rowAcaoRede['TotalRedesNaAcao'];


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title><?=$oTranslator->_('Modulos');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?=$oTranslator->_('Modulos');?></td>
  </tr>
  <tr> 
    <td class="descricao"><p>
    	<?=$oTranslator->_('kciq_msg Modulos help');?>
    </td>
  </tr>
  <tr> 
    <td>
      <fieldset>
      	<legend><?=$oTranslator->_('Legenda');?></legend>
		<img src="../imgs/alerta_vermelho.gif" /> <?=$oTranslator->_('Nao e executado em nenhuma rede');?>
		<img src="../imgs/alerta_amarelo.gif" /> <?=$oTranslator->_('Executado apenas nas redes selecionadas');?>
		<img src="../imgs/alerta_verde.gif" /> <?=$oTranslator->_('Executado em todas as redes');?>
      </fieldset>
    </td>
  </tr>
</table>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
	<tr> 
	  <td height="1" colspan="2" bgcolor="#e7e7e7"></td>
	</tr>
        <tr> 
          <td height="5" colspan="2"></td>
        </tr>
	
  <tr> 
    <td>
<?  
while ($row = mysql_fetch_array($result)) 
	{
	$img = '';
	if($row['cs_situacao'] == 'N' || $row['cs_situacao'] == NULL)
		$img = '<img src="../imgs/alerta_vermelho.gif" title="'.$oTranslator->_('Nao e executado em nenhuma rede').'" width="8" height="8" border="0">';
	if($row['cs_situacao'] == 'S')
		$img = '<img src="../imgs/alerta_amarelo.gif" title="'.$oTranslator->_('Executado apenas nas redes selecionadas').'" width="8" height="8" border="0">';
	if($row['cs_situacao'] == 'T')
		$img = '<img src="../imgs/alerta_verde.gif" title="'.$oTranslator->_('Executado em todas as redes').'" width="8" height="8" border="0">';
?>
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
        <tr> 
          <td class="label"><a href="acoes.php?id_acao=<? echo $row['id_acao']?>&te_descricao_breve=<? echo $row['te_descricao_breve']?>&te_descricao=<? echo $row['te_descricao']?>"><? echo $img. ' ' .$row['te_descricao_breve'].' (Total de Redes: '.number_format($arrAcaoRede[$row['id_acao']], 0, '', '.').')';?></a></td>
        </tr>
        <tr> 
          <td valign="top" scope="top" class="descricao"><div align="left"></div>
            <? echo $row['te_descricao']?>
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
<?
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
