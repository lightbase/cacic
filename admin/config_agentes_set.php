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
require_once('../include/library.php');
// Comentado temporariamente - AntiSpy();
conecta_bd_cacic();

$query = "UPDATE 	configuracoes_locais 
		  SET		in_exibe_bandeja 			= '" . $_POST['in_exibe_bandeja'] . "', 
          			in_exibe_erros_criticos 	= '" . $_POST['in_exibe_erros_criticos'] . "', 
          			nu_exec_apos 				= '" . $_POST['nu_exec_apos'] . "', 
          			te_senha_adm_agente 		= '" . $_POST['te_senha_adm_agente'] . "', 
          			nu_intervalo_exec 			= '" . $_POST['nu_intervalo_exec'] . "',
		  			te_enderecos_mac_invalidos 	= '".$_POST['te_enderecos_mac_invalidos']."',
		  			te_janelas_excecao 			= '".strtolower($_POST['te_janelas_excecao'])."'
			WHERE 	id_local 				= ".$_SESSION['id_local'];
$result_acoes_redes = mysql_query($query) or die('Ocorreu um erro durante a atulizacao da tabela de configurações.'); 
GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');	
$query_acoes_redes = '';
foreach($HTTP_POST_VARS as $i => $v) 
	{
	if ($v && substr($i,0,9)=='cs_coleta')
		{
		$v_acao_rede = explode('#',$i);		
		
		if ($query_acoes_redes == '')
			{
			$query_acoes_redes .= 'Update acoes_redes set dt_hr_coleta_forcada = now() where '; 
			$v_or = '';
			}
		$query_acoes_redes .= $v_or . " id_acao = '" . $v_acao_rede[0] . "' and id_ip_rede = '" . $v . "'"; 			
		$v_or = ' or ';
		}
	}

if ($query_acoes_redes)	
	{
	$result_acoes_redes = mysql_query($query_acoes_redes) or die('Ocorreu um erro durante a atualização de acoes_rede.'); 	
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'acoes_redes');		
	}

// if ($_POST['dt_hr_coleta_forcada']) $query = $query . ', dt_hr_coleta_forcada = NOW()';
//	$result = mysql_query($query) or die('Ocorreu um erro durante a atualização da tabela configuracoes.'); 

header ("Location: ../include/operacao_ok.php?chamador=../admin/config_agentes.php&tempo=1");
?>
