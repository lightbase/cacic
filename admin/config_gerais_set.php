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
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}
require_once('../include/library.php');
// Comentado temporariamente - AntiSpy();
conecta_bd_cacic();

// Preciso remover os "Enters" dados nos campos texto do formulário, pois a rotina de envio de emails
// estava dando erro quando encontrava esse tipo de caractere especial.				
$te_notificar_mudanca_hardware = str_replace("\r\n", " ", $_POST['te_notificar_mudanca_hardware']);

	$query = "UPDATE	configuracoes_locais set 
	          			te_notificar_mudanca_hardware	= '" . $te_notificar_mudanca_hardware . "', 
			  			te_serv_cacic_padrao 			= '" . $_POST['frm_te_serv_cacic_padrao'] . "', 			  
			  			te_serv_updates_padrao 			= '" . $_POST['frm_te_serv_updates_padrao'] . "'
			   WHERE	id_local = ".$_SESSION['id_local'];			  
  
	$result = mysql_query($query) or die('Ocorreu um erro durante a atualização da tabela configuracoes.'); 
	GravaLog('INS',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');		
	// Aqui pego todas os hardwares selecionados para notificação e atualizo a tabela descricao_hardware .
	$hardwares_selecionados = "'" . $_POST['list2'][0] . "'";
	for( $i = 1; $i < count($_POST['list2']); $i++ ) {
		$hardwares_selecionados = $hardwares_selecionados . ",'" . $_POST['list2'][$i] . "'";
	}
	$hardwares_selecionados = ' nm_campo_tab_hardware IN ('. $hardwares_selecionados .')';

	$query = "UPDATE 	descricao_hardware set 
	          			cs_notificacao_ativada = '0'";
	$result = mysql_query($query) or die('Ocorreu um erro durante a atualização da tabela descricao_hardware.'); 
	$query = "UPDATE 	descricao_hardware set 
	          			cs_notificacao_ativada = '1'
			  WHERE 	" . $hardwares_selecionados;
	$result = mysql_query($query) or die('Ocorreu um erro durante a atualização da tabela descricao_hardware.'); 

	header ("Location: ../include/operacao_ok.php?chamador=../admin/config_gerais.php&tempo=1");		
	
?>
