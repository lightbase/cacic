<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../../../include/library.php');
conecta_bd_cacic();

	$query_select = "select max(id_software) as ID FROM softwares";
	
	$result = mysql_query($query_select);
		
	$row = mysql_fetch_array($result);
	
	$Id = $row['ID'];
	
	if ($Id == null) {
		$Id = 0;
	}else{
		$Id += 1;
	}
	
   	$query_insert =  "insert into softwares (id_software, nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, te_obs) " .
   		             "values('$Id','$_REQUEST[nome]','$_REQUEST[descricao]','$_REQUEST[quantidadeLicenca]','$_REQUEST[numeroMidia]','$_REQUEST[localMidia]','$_REQUEST[observacao]')";
   	
   	$result_ins = mysql_query($query_insert);
   	
   	if (!$result_ins){
		echo mensagem('Não foi possível gravar o registro!');
	}else {
		echo mensagem('Registro gravado!');
   	}
?>
<br><br>
<div align="center"><input type="button" name="btnVoltar" value="Voltar" onClick="javascript:window.location='frmSoftwares.php'"/></div>