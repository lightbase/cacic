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

require_once('../include/library.php');

AntiSpy('1,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

conecta_bd_cacic();

// Preciso remover os "Enters" dados nos campos texto do formulário, pois a rotina de envio de emails
// estava dando erro quando encontrava esse tipo de caractere especial.				
$te_notificar_mudanca_hardware = str_replace("\r\n", " ", $_POST['te_notificar_mudanca_hardware']);

// Verifico a existencia de registro do local e insiro/atualizo as configuracoes 
$query  = "SELECT count(id_local) Total FROM configuracoes_locais WHERE id_local = ".$_POST['frm_id_local'];
$result = mysql_query($query);
$row = mysql_fetch_array($result);

if ($row['Total']==0)
	{
	$query = "INSERT INTO configuracoes_locais (id_local,te_notificar_mudanca_hardware,te_notificar_utilizacao_usb,te_serv_cacic_padrao,te_serv_updates_padrao) 
                  VALUES ('".$_POST['frm_id_local']."','" . $te_notificar_mudanca_hardware . "','" . $te_notificar_utilizacao_usb . "','" . $_POST['frm_te_serv_cacic_padrao'] . "','" . $_POST['frm_te_serv_updates_padrao'] . "')";
	}
else
	{
	$query = "UPDATE	configuracoes_locais set 
					te_notificar_mudanca_hardware	= '" . $te_notificar_mudanca_hardware . "', 
					te_notificar_utilizacao_usb	 	= '" . $te_notificar_utilizacao_usb . "', 					
					te_serv_cacic_padrao 			= '" . $_POST['frm_te_serv_cacic_padrao'] . "', 			  
					te_serv_updates_padrao 			= '" . $_POST['frm_te_serv_updates_padrao'] . "'
		   WHERE	id_local = ".$_POST['frm_id_local'];			  
	}

$result = mysql_query($query) or die($_SERVER['SCRIPT_NAME'].' 1-'.$oTranslator->_('Ocorreu um erro durante a atualizacao da tabela %1 ou sua sessao expirou', array('configuracoes_locais')).'!'); 
GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais',$_SESSION["id_usuario"]);		

// Aqui pego todos os hardwares selecionados para notificação e atualizo a tabela descricao_hardware .
$hardwares_selecionados = "'" . $_POST['list2'][0] . "'";
for( $i = 1; $i < count($_POST['list2']); $i++ ) 
	{
	$hardwares_selecionados .= ",'" . $_POST['list2'][$i] . "'";
	}
$hardwares_selecionados = ' nm_campo_tab_hardware IN ('. $hardwares_selecionados .')';

// Processo todas as descrições de hardware e retiro as referências ao local atual
$querySELECT = "SELECT 	*
		  		FROM		descricao_hardware";
$resultSELECT = mysql_query($querySELECT) or die($_SERVER['SCRIPT_NAME'] . ' 2-'.$oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou',array('descricao_hardware')).'!'); 

$strPesquisa = ','.$_POST['frm_id_local'].',';
while ($row = mysql_fetch_array($resultSELECT))
	{
	$strRow = ','.str_replace(' ','',$row['te_locais_notificacao_ativada']).',';
	$intPos = strpos($strRow,$strPesquisa);
	if ($intPos > -1) // Achei o id_local dentro do campo te_locais_notificacao_ativada
		{
		$strRow = ','.str_replace($strPesquisa,'',$strRow).',';		

		// Retiro as vírgulas duplicadas...
		while (strpos($strRow,',,')>-1)
			$strRow = str_replace(',,',',',$strRow);				

		$queryUPDATE = "UPDATE 	descricao_hardware set 
							te_locais_notificacao_ativada = '".$strRow."'
				  WHERE 	nm_campo_tab_hardware = '".$row['nm_campo_tab_hardware']."'";
		$resultUPDATE = mysql_query($queryUPDATE) or die($_SERVER['SCRIPT_NAME'] . ' 3-'.$oTranslator->_('Ocorreu um erro durante a atualizacao da tabela %1 ou sua sessao expirou', array('descricao_hardware')).'!'); 		
		}		
	}

$querySELECT = "SELECT 	*
		  		FROM		descricao_hardware
		  		WHERE 	".$hardwares_selecionados;
$resultSELECT = mysql_query($querySELECT) or die('4-'.$oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou',array('descricao_hardware')).'!'); 
while ($row = mysql_fetch_array($resultSELECT))
	{
	$queryUPDATE = "UPDATE 	descricao_hardware 
					SET		te_locais_notificacao_ativada = CONCAT(te_locais_notificacao_ativada,',','".$_POST['frm_id_local']."',',')
		  		    WHERE 	nm_campo_tab_hardware = '".$row['nm_campo_tab_hardware']."'";
	$resultUPDATE = mysql_query($queryUPDATE) or die($_SERVER['SCRIPT_NAME'] . ' 5-'.$oTranslator->_('Ocorreu um erro durante a atualizacao da tabela %1 ou sua sessao expirou', array('descricao_hardware')).'!'); 		
	}		

// Aqui pego todos os gráficos selecionados para serem exibidos e atualizo a tabela configuracoes_locais.
$te_exibe_graficos = $_POST['listaExibeGraficosSelecionados'][0];
for( $i = 1; $i < count($_POST['listaExibeGraficosSelecionados']); $i++ ) 
	{
	$te_exibe_graficos .= $_POST['listaExibeGraficosSelecionados'][$i];
	}

// Aqui pego todos os dispositivos USB selecionados para notificação .
$te_usb_filter = $_POST['listaUSBs'][0];
for( $i = 1; $i < count($_POST['listaUSBs']); $i++ ) 
	{
	
	$te_usb_filter .= "#" . $_POST['listaUSBs'][$i];
	}

$queryUPDATE = "UPDATE 	configuracoes_locais set 
						te_exibe_graficos = '".$te_exibe_graficos."',
						te_usb_filter = '".$te_usb_filter."'						
		  		WHERE   id_local=".$_POST['frm_id_local'];
$resultUPDATE = mysql_query($queryUPDATE) or die($_SERVER['SCRIPT_NAME'] . ' 6-'.$oTranslator->_('Ocorreu um erro durante a atualizacao da tabela %1 ou sua sessao expirou', array('configuracoes_locais')).'!'); 

header ("Location: ../include/operacao_ok.php?chamador=../admin/config_gerais.php&tempo=1");		
?>