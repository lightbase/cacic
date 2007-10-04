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

$id_acao = $_POST['id_acao']; 

// Leio o array 1 que contém as subredes NÃO selecionadas...
$arrListaRedesNaoSelecionadas = $_POST['list1'];
$queryDEL = '';
for( $i = 0; $i < count($arrListaRedesNaoSelecionadas); $i++ ) 
	{
	$dadosRedes = explode('#',$arrListaRedesNaoSelecionadas[$i]);	

	if ($queryDEL)
		$queryDEL .= ' OR ';
		
    // Removo a ação em questão da rede
	$queryDEL = "(id_acao    = '".$id_acao."' AND
				  id_ip_rede = '".$dadosRedes[0]."' AND
			  	  id_local   = ".$dadosRedes[1].")";							
	}
	
conecta_bd_cacic();	

if ($queryDEL)
	{
	$queryDEL = 'DELETE FROM acoes_redes WHERE '.$queryDEL;
	$result = mysql_query($queryDEL) or die('2-Ocorreu um erro durante a deleção de registros na tabela acoes_redes ou sua sessão expirou!'); 
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes');				
	}	
	
	
// Leio o array 2 que contém as subredes selecionadas...
$arrListaRedesSelecionadas = $_POST['list2'];

// Caso não existam redes selecionadas, a situação torna-se em Nenhuma Rede
$cs_situacao = (count($arrListaRedesSelecionadas)>0?$_POST['cs_situacao']:'N');

// Caso tenha sido marcado "Em todas as redes", concateno o array 1, que contém as redes "não selecionadas".
if ($cs_situacao == 'T' || $cs_situacao == 'N')
	{
	if ($arrListaRedesSelecionadas)
		$arrListaRedesSelecionadas = array_merge($_POST['list1'],$_POST['list2']);
	else
		$arrListaRedesSelecionadas = $_POST['list1'];	
	}


for( $i = 0; $i < count($arrListaRedesSelecionadas); $i++ ) 
	{
	$dadosRedes = explode('#',$arrListaRedesSelecionadas[$i]);	

    // Removo a ação em questão da rede
	$query = "DELETE	from acoes_redes 
			  WHERE 	id_acao    = '".$id_acao."' AND
						id_ip_rede = '".$dadosRedes[0]."' AND
			  			id_local   = ".$dadosRedes[1];
						
	$result = mysql_query($query) or die('2-Ocorreu um erro durante a deleção de registros na tabela acoes_redes ou sua sessão expirou!'); 
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes');			

    // Removo todos os sistemas operacionais associadas à ação em questão.
	$query = "DELETE 	
			  FROM 		acoes_so 
			  WHERE 	id_acao='".$id_acao."' AND
						id_local = ".$dadosRedes[1];
	$result = mysql_query($query) or die('6-Ocorreu um erro durante a deleção de registros na tabela acoes_so ou sua sessão expirou!'); 
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_so');

	if ($cs_situacao <> 'N')
		{
		// Incluo todas os so's selecionados
		for( $j = 0; $j < count($_POST['list4']); $j++ ) 
			{
			$query = "INSERT 
				      INTO 		acoes_so (id_so, id_acao, id_local) 
					  VALUES 	('".$_POST['list4'][$j]."', '".$id_acao."', ".$dadosRedes[1].")";
			mysql_query($query) or die('7-Ocorreu um erro durante a inclusão de registros na tabela acoes_so ou sua sessão expirou!');
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_so');		
			}
		}

    // Removo todos os mac address associados à ação em questão.
	$query = "DELETE 
			  FROM 		acoes_excecoes 
			  WHERE 	id_acao='".$id_acao."' AND
			            id_local=".$dadosRedes[1];
	$result = mysql_query($query) or die('8-Ocorreu um erro durante a deleção de registros na tabela acoes_excecoes ou sua sessão expirou!'); 
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_excecoes');
	
	if ($cs_situacao <> 'N')
		{	
		// Incluo todas os mac address selecionados.
		for( $k = 0; $k < count($_POST['list5']); $k++ ) 
			{
			$query = "INSERT 
					  INTO 		acoes_excecoes (id_local,te_node_address, id_acao) 
					  VALUES 	(".$dadosRedes[1].",'".$_POST['list5'][$k]."', '".$id_acao."')";

			// Não uso o die, pois não quero que sejam ecoadas mensagens de erro caso se tente gravar 
			// registros duplicados. lembre que é um ambiente multiusuário.
			mysql_query($query); 
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_excecoes');
			}
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
		mysql_query($query) or die('3-Ocorreu um erro durante a inclusão de registros selecionados na tabela acoes_redes ou sua sessão expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_redes');
		}
	elseif ($cs_situacao == 'T')		
		{	
		$query = "SELECT 	id_ip_rede
				   FROM 	redes
				   WHERE	id_local=".$dadosRedes[1];
		$result = mysql_query($query) or die('4-Deu erro ou sua sessão expirou!');
			
		while($campos=mysql_fetch_array($result)) 
			{
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
			mysql_query($query) or die('5-Ocorreu um erro durante a inclusão de TODOS registros na tabela acoes_redes ou sua sessão expirou!');
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_redes');		
			}
		}											
	}

header ("Location: ../include/operacao_ok.php?chamador=../admin/modulos.php&tempo=1");	
?>
