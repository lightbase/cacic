<?
/**
 * @version $Id: rel_alteracoes_hardware_02.php 2009-03-08 18:23 harpiain $
 * @package Cacic-relatorios
 * @subpackage Alteracoes_Hardware
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC-Install is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}
require_once('../../include/library.php');
require_once('security/security.php');
AntiSpy();
conecta_bd_cacic();
$titulo = $oTranslator->_('Relatorio de alteracoes de hardware');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?=$titulo?></title>
	<meta http-equiv="Content-Language" content="<?=CACIC_LANGUAGE?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?=CACIC_LANG_CHARSET?>" />
	<link href="<?=CACIC_URL?>/include/cacic.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="<?=CACIC_URL?>/include/cacic.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?=CACIC_URL?>/bibliotecas/javascript/asv/asvAjax.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?=CACIC_URL?>/bibliotecas/javascript/asv/asvUtils.js"></script>
<script language="JavaScript" type="text/JavaScript">
</script>
</head>
<body>
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF">
      <div align="center">
        <font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
          <strong><?=$titulo?></strong>
        </font>
      </div>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
        <?php echo $oTranslator->_('Gerado em') . ' ' . date("d/m/Y - H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 

// verifica se gera somente o relatorio de dados historicos (de versoes anteriores a 2.4) devido a alteracoes na tabela e estrutura
if(!( Security::read('historical_data') == 'historical_data' and Security::read('historical_data_only') == 'historical_data_only')) {

$query_field_name = "select * from descricoes_colunas_computadores";
$result = mysql_query($query_field_name);
while ($row = mysql_fetch_assoc($result) ) {
  $descricoes_colunas_computadores[$row['nm_campo']] = $row['te_descricao_campo'];
}

function getHistData($p_hist_data) {
	global $descricoes_colunas_computadores;
   	$data = explode('#FIELD#',$p_hist_data);
   	foreach($data as $key => $value) {
   	   list($field_name, $field_data) = explode('###',$value);
   	   echo $descricoes_colunas_computadores[$field_name] .": ". $field_data . "<br>";
   	}
}

$data_inicial = Security::read('date_input1');
$data_final = Security::read('date_input2');
$locais_selecionados = Security::read('list12');
$so_selecionados = Security::read('list4');

$obteve_dados = false;
$parametros_informados = true;

if($data_inicial == null)
  $parametros_informados = false;

if($data_final == null)
  $parametros_informados = false;

if($locais_selecionados == null)
  $parametros_informados = false;

if($so_selecionados == null)
  $parametros_informados = false;

/* Novo relatorio */
if ($parametros_informados) {  // somente processa o relatorio se os paramentros foram informados

    $locais = "";
    foreach( $locais_selecionados as $key => $value)
        $locais .= ($locais ? ", ". $value: $value);

    $so = "";
    foreach( $so_selecionados as $key => $value)
        $so .= ($so ? ", ". $value: $value);

	$query = "SELECT componentes_estacoes_historico.te_node_address, componentes_estacoes_historico.id_so, 
	                 componentes_estacoes_historico.cs_tipo_componente, componentes_estacoes_historico.te_valor,
	                 componentes_estacoes_historico.dt_alteracao, componentes_estacoes_historico.cs_tipo_alteracao,
	                 locais.sg_local,
	                 so.sg_so,
	                 so.te_desc_so,
	                 computadores.te_nome_computador 
	                 		 
			  FROM 			componentes_estacoes_historico 
	                 		 
			  LEFT JOIN		computadores ON (componentes_estacoes_historico.te_node_address = computadores.te_node_address AND 
							                 componentes_estacoes_historico.id_so = computadores.id_so)  
              LEFT JOIN     redes ON (computadores.id_ip_rede = redes.id_ip_rede)
              LEFT JOIN     so ON (componentes_estacoes_historico.id_so = so.id_so)
              LEFT JOIN     locais ON (redes.id_local = locais.id_local)
	                 		 
			  WHERE 		date_format(dt_alteracao, '%d/%m/%Y') >= '" . $data_inicial . "' AND 
							date_format(dt_alteracao, '%d/%m/%Y') <= '" . $data_final ."'".
							( $locais ? " AND redes.id_local IN (".$locais.")": "" ) .
							( $so ? " AND componentes_estacoes_historico.id_so IN (".$so.")": "" ) . " 
	                 		 
			  ORDER BY 		componentes_estacoes_historico.dt_alteracao,
			  		        componentes_estacoes_historico.cs_tipo_alteracao,
			                componentes_estacoes_historico.te_node_address,
			                componentes_estacoes_historico.id_so";
			                

	$result = mysql_query($query) or 
	             die ($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('componentes_estacoes_historico')));
	             
	$num_rows = mysql_num_rows($result);

	if($num_rows) // ha dados a serem apresentados
	  $obteve_dados = true;
	  
}
?>
<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
	<tr>
		<th><?=$oTranslator->_('Local');?></th>
		<th><?=$oTranslator->_('Computador');?></th>
		<th><?=$oTranslator->_('Sistema operacional');?></th>
		<th><?=$oTranslator->_('Tipo componente');?></th>
		<th><?=$oTranslator->_('Data de alteracao');?></th>
		<th><?=$oTranslator->_('Tipo de alteracao');?></th>
		<th><?=$oTranslator->_('Dados da modificacao');?></th>
	</tr>
	<?php while ($dados_obtidos = mysql_fetch_assoc($result)) { ?>
	<tr <?= $cor?>>
		<td><?=  $dados_obtidos["sg_local"]; ?></td>
		<td>
		   <a href="../computador/computador.php?te_node_address=<?=  $dados_obtidos["te_node_address"]; ?>&id_so=<?=  $dados_obtidos["id_so"]; ?>" target='_blank'>
		      <?=  $dados_obtidos["te_nome_computador"]; ?>
		   </a>
		</td>
		<td><?=  $dados_obtidos["te_desc_so"]; ?></td>
		<td><?=  $dados_obtidos["cs_tipo_componente"]; ?></td>
		<td><?=  date('d/m/Y', strtotime($dados_obtidos["dt_alteracao"])); ?></td>
		<td><?=  ($dados_obtidos["cs_tipo_alteracao"])=='ACR'?$oTranslator->_("Acrescentado"):$oTranslator->_("Removido"); ?></td>
		<td>
		   <?=  getHistData($dados_obtidos['te_valor']); ?>
		</td>
	</tr>
	<?php } ?>
</table>

<?php
/* FIM do novo relatorio */
} // fim da verificacao se gera somente o relatorio de dados historicos (de versoes anteriores a 2.4) devido a alteracoes na tabela e estrutura

// gera relatorio de dados historicos (de versoes anteriores a 2.4) devido a alteracoes na tabela e estrutura
if(Security::read('historical_data') == 'historical_data') {
  require_once('rel_alteracoes_hardware_01.php');
}


if($obteve_dados===false)
  echo "<span class='ErroImg'>&nbsp;</span>" .
  	   "<span class='Erro'>".$oTranslator->_("Nao foram encontradas alteracoes de hardware!")."</span>";
  	   
if ($parametros_informados===false)   	   
  echo "<span class='AvisoImg'>&nbsp;</span>" .
  	   "<span class='Aviso'>".$oTranslator->_("Sao necessarios parametros para gerar o relatorios!")."</span>";
?>

<p></p>
<p align="left">
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    <?=$oTranslator->_('Gerado por');?> - 
    <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor de Informa&ccedil;&otilde;es Computacionais
  </font>
  <br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    Software desenvolvido pela Dataprev - Unidade Regional Esp&iacute;rito Santo
  </font>
</p>
</body>
</html>
