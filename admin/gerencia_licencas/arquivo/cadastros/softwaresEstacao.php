<?php

function DataParaBd($data){
  $aux = explode("/",$data);
  $data = $aux[2]."-".$aux[1]."-".$aux[0];
  if($data=='--')
     $data='';
  return $data;
}

session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../../../include/library.php');
conecta_bd_cacic();
   	
   	$patrimonio = $_REQUEST[patrimonio];
   	$software = $_REQUEST[software];
   	$computador = $_REQUEST[computador];
   	$dataAutorizacao = DataParaBd($_REQUEST[dataAutorizacao]);
   	$processo = $_REQUEST[numeroProcesso];
	$dataExpiracao = DataParaBd($_REQUEST[dataExpiracao]);
   	$aquisicaoParticular = $_REQUEST[aquisicao];
	$dataDesinstalacao = DataParaBd($_REQUEST[dataDesinstalacao]);
   	$observacao = $_REQUEST[observacao];
   	$patDestino = $_REQUEST[patrimonioDestino];
   	
   	$query_insert =  "insert into softwares_estacao (nr_patrimonio, id_software, nm_computador, dt_autorizacao, nr_processo, dt_expiracao_instalacao, id_aquisicao_particular, dt_desinstalacao, te_observacao, nr_patr_destino) " .
   		             "values('$patrimonio','$software','$computador','$dataAutorizacao','$processo','$dataExpiracao','$aquisicaoParticular','$dataDesinstalacao','$observacao','$patDestino')";
   	
   	$result_ins = mysql_query($query_insert);
   	
   	if (!$result_ins){
		echo mensagem($oTranslator->_('Nao foi possivel gravar o registro!'));
	}else {
		echo mensagem($oTranslator->_('Registro gravado!'));
   	}
?>
<br><br>
<div align="center"><input type="button" name="btnVoltar" value="<?=$oTranslator->_('Voltar');?>" onClick="javascript:window.location='frmSoftwaresEstacao.php'"/></div>
