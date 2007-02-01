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

require_once('../include/library.php');
// Comentado temporariamente - AntiSpy();
$acoes_computador = '';
foreach($HTTP_POST_VARS as $i => $v) 
	{
	if ($v && substr($i,0,9)=='cs_coleta')
		{
		if ($acoes_computador <> '')
			{
			$acoes_computador .= '#';			
			}
		$acoes_computador .= $v; 			
		}
	}

if ($acoes_computador)	
	{
	$query_computador .= '	Update 	computadores set dt_hr_coleta_forcada_estacao = now(),
														te_nomes_curtos_modulos="'.$acoes_computador.'" 
							Where 	te_node_address="'.trim($_POST['te_node_address']).'" AND
									id_so="'.trim($_POST['id_so']).'"'; 
	conecta_bd_cacic();									
	$result_computador = mysql_query($query_computador) or die('Ocorreu um erro durante a atualização de computadores.'); 		
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'computadores');		
	}
	header ("Location: ../include/operacao_ok.php?chamador=../index.html&tempo=1");	
?>
