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
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão


/*
foreach($HTTP_POST_VARS as $i => $v) 
	{
	GravaTESTES('Em acoes_set => I : '.$i);
	GravaTESTES('Em acoes_set => V : '.$v);	
	GravaTESTES('*********************************');		
	}
*/

$id_acao = $_POST['id_acao']; 

// Leio o array 1 que contém as subredes NÃO selecionadas...
$arrListaRedesNaoSelecionadas = explode('#FD#', $_POST['frmList1']);

$queryDEL = '';
$i = 0;

while ($i < count($arrListaRedesNaoSelecionadas) && trim($_POST['frmList1'])<>'') 
	{
	$dadosRedes = explode('#',$arrListaRedesNaoSelecionadas[$i]);	
	$i++;

	if ($queryDEL)
		$queryDEL .= ' OR ';
		
    // Removo a ação em questão da rede
	$queryDEL .= "(id_acao    = '".$id_acao."' AND
				   id_ip_rede = '".$dadosRedes[0]."' AND
			  	   id_local   = ".$dadosRedes[1].")";						
	}

conecta_bd_cacic();	
if ($queryDEL)
	{
	$queryDEL = 'DELETE FROM acoes_redes WHERE '.$queryDEL;
	$result = mysql_query($queryDEL) or die('1-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_redes'))."! ".	$oTranslator->_('kciq_msg session fail',false,true)."!"); 
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes');				
	}	
	

// Leio o array 2 que contém as subredes selecionadas...
$arrListaRedesSelecionadas = explode('#FD#', $_POST['frmList2']);

// Caso não existam redes selecionadas, a situação torna-se em Nenhuma Rede
$cs_situacao = (count($arrListaRedesSelecionadas)>0?$_POST['cs_situacao']:'N');


// Caso tenha sido marcado "Em todas as redes", concateno o array 1, que contém as redes "não selecionadas".
if ($cs_situacao == 'T' || $cs_situacao == 'N')
	{
	if ($arrListaRedesSelecionadas)
		$arrListaRedesSelecionadas = array_merge($arrListaRedesNaoSelecionadas,$arrListaRedesSelecionadas);
	else
		$arrListaRedesSelecionadas = $arrListaRedesNaoSelecionadas;	
	}

$i = 0;
while ($i < count($arrListaRedesSelecionadas) && trim($_POST['frmList2']) <> '') 
	{
	$dadosRedes = explode('#',$arrListaRedesSelecionadas[$i]);	
	$i++;	
	if (trim($dadosRedes[0]) <> '' && trim($dadosRedes[1]) <> '')
		{
		// Removo a ação em questão da rede
		$query = "DELETE	from acoes_redes 
				  WHERE 	id_acao    = '".$id_acao."' AND
							id_ip_rede = '".$dadosRedes[0]."' AND
							id_local   = ".$dadosRedes[1];
		$result = mysql_query($query) or die('2-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
	
		GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes');			
	
		// Removo todos os sistemas operacionais associadas à ação em questão.
		$query = "DELETE 	
				  FROM 		acoes_so 
				  WHERE 	id_acao='".$id_acao."' AND
							id_local = ".$dadosRedes[1];
		$result = mysql_query($query) or die('3-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
		GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_so');
	
		$arrList4 = explode('#FD#',$_POST['frmList4']);
		if ($cs_situacao <> 'N')
			{
			// Incluo todas os so's selecionados
			for( $j = 0; $j < count($arrList4); $j++ ) 
				{
				$query = "INSERT 
						  INTO 		acoes_so (id_so, id_acao, id_local) 
						  VALUES 	('".$arrList4[$j]."', '".$id_acao."', ".$dadosRedes[1].")";
				mysql_query($query) or die('4-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
				}
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_so');					
			}
	
		// Removo todos os mac address associados à ação em questão.
		$query = "DELETE 
				  FROM 		acoes_excecoes 
				  WHERE 	id_acao='".$id_acao."' AND
							id_local=".$dadosRedes[1];
		$result = mysql_query($query) or die('5-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_excecoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
		GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_excecoes');
	
		$arrList5 = explode('#FD#',$_POST['frmList5']);
		if ($cs_situacao <> 'N')
			{	
			// Incluo todas os mac address selecionados.
			for( $k = 0; $k < count($arrList5); $k++ ) 
				{
				$query = "INSERT 
						  INTO 		acoes_excecoes (id_local,te_node_address, id_acao) 
						  VALUES 	(".$dadosRedes[1].",'".$arrList5[$k]."', '".$id_acao."')";
	
				// Não uso o die, pois não quero que sejam ecoadas mensagens de erro caso se tente gravar 
				// registros duplicados. lembre que é um ambiente multiusuário.
				mysql_query($query); 
				}
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_excecoes');			
			}
		
		if($cs_situacao == 'S')
			{		
			$query = "INSERT	
					  INTO 		acoes_redes (id_ip_rede, 
								id_acao, 
								id_local, 
								cs_situacao,
								dt_hr_alteracao) 
					  VALUES 	('".$dadosRedes[0]."', 
								'".$id_acao."',".$dadosRedes[1].",
								'S',
								now())";
			mysql_query($query) or die('6-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_excecoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_redes');
			}
		elseif ($cs_situacao == 'T')		
			{	
			$query = "SELECT 	id_ip_rede
					   FROM 	redes
					   WHERE	id_local=".$dadosRedes[1];
			$result = mysql_query($query) or die('7-'.$oTranslator->_('kciq_msg select on table fail', array('acoes_excecoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
				
			while($campos=mysql_fetch_array($result)) 
				{ 
				
				$sql_delete = "DELETE FROM acoes_redes WHERE id_ip_rede = '".$campos[0]."'".
															 " AND id_acao = '".$id_acao."'".
															 " AND id_local = '".$dadosRedes[1]."'";
				mysql_query($sql_delete);
				$query = "INSERT	
						  INTO 		acoes_redes (id_ip_rede, 
									id_acao, 
									id_local, 
									cs_situacao,
									dt_hr_alteracao) 
						  VALUES	('".$campos[0]."', 
									'".$id_acao."', 
									".$dadosRedes[1].",
									'T',
									now())";
				mysql_query($query) or die('8-'.mysql_error()." - ".$oTranslator->_('kciq_msg insert row on table fail', array('acoes_redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
				}
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_redes');					
			}			
		}								
	}

header ("Location: ../include/operacao_ok.php?chamador=../admin/modulos.php&tempo=1");	
?>
