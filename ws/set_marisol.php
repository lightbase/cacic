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

/*autentica_agente($key,$iv,$_POST['cs_cipher']);
$te_node_address 			= DeCrypt($key,$iv,$_POST['te_node_address'],$_POST['cs_cipher']); 
$id_so          			= DeCrypt($key,$iv,$_POST['id_so']			,$_POST['cs_cipher']); */
$te_node_address 			= "00-08-02-3F-CE-B9"; //lu
$id_so  					= "8";
$data = date('Y-m-d H:i:s'); 

$v_tripa_inventariados = "Atualizacao 4#verific#Hotfix2#ver#ver2#Hotfix3";

conecta_bd_cacic();
// Verifico se o computador em questão já foi inserido anteriormente, e se não foi, insiro.
/*$query = "SELECT count(*) as num_registros
          FROM versoes_softwares
		  WHERE te_node_address = '" . $te_node_address . "'
		  AND id_so = '" . $id_so . "'";
$result = mysql_query($query);
if (mysql_result($result, 0, "num_registros") == 0) {
	$query = "INSERT INTO versoes_softwares
			 (te_node_address, id_so)
			 VALUES ('" . $te_node_address . "', '" . $id_so . "'  )";
	$result = mysql_query($query);
} 

$query = "UPDATE versoes_softwares 
 									SET	te_versao_bde            = '" . DeCrypt($key,$iv,$_POST['te_versao_bde']			,$_POST['cs_cipher']) . "', 
										te_versao_dao            = '" . DeCrypt($key,$iv,$_POST['te_versao_dao']			,$_POST['cs_cipher']) . "', 
										te_versao_ado            = '" . DeCrypt($key,$iv,$_POST['te_versao_ado']			,$_POST['cs_cipher']) . "', 
										te_versao_odbc           = '" . DeCrypt($key,$iv,$_POST['te_versao_odbc']			,$_POST['cs_cipher']) . "', 
										te_versao_directx        = '" . DeCrypt($key,$iv,$_POST['te_versao_directx']		,$_POST['cs_cipher']) . "', 
										te_versao_acrobat_reader = '" . DeCrypt($key,$iv,$_POST['te_versao_acrobat_reader']	,$_POST['cs_cipher']) . "', 
										te_versao_ie             = '" . DeCrypt($key,$iv,$_POST['te_versao_ie']				,$_POST['cs_cipher']) . "', 
										te_versao_mozilla        = '" . DeCrypt($key,$iv,$_POST['te_versao_mozilla']		,$_POST['cs_cipher']) . "', 
										te_versao_jre            = '" . DeCrypt($key,$iv,$_POST['te_versao_jre']			,$_POST['cs_cipher']) . "' 
	  							WHERE 	te_node_address    		 = '" . $te_node_address . "' and
										id_so                	 = '" . $id_so . "'";

$result = mysql_query($query);

$v_tripa_inventariados = str_replace("&quot;","'",DeCrypt($key,$iv,$_POST['te_inventario_softwares'],$_POST['cs_cipher']));
*/
// Marisol 19/06/2006 - add uma # ao final e retirando a '/'
// MARISOL - 20/06/06 - Acrescenta no final o #, caso nao seja o ultimo caracter

$tripa1 = $v_tripa_inventariados;
$Caracter = substr($v_tripa_inventariados,strlen($v_tripa_inventariados)-1,strlen($v_tripa_inventariados));
if ($Caracter != "#"){
	$v_tripa_inventariados = $v_tripa_inventariados."#";
}
$tripa2 = $v_tripa_inventariados;
$v_tripa_inventariados = $v_tripa_inventariados;
$v_tripa_inventariados = str_replace("/","",$v_tripa_inventariados);
$v_tripa_inventariados = str_replace("\\","",$v_tripa_inventariados);
$v_tripa_inventariados = str_replace("&apos;","^",$v_tripa_inventariados);

//Recebimento de E-mail para visualizar a tripa
$corpo_mail2 = "Tripa 1 : ".$tripa1."\nTripa 2 : ".$tripa2."\nNome...............: ". $te_node_address;
	$destinatarios = "ropelato.e@marisol.com.br";
	// Manda mail para os administradores.	
	mail("$destinatarios", "Alteracao de Software Detectada!!", "$corpo_mail2", "From: cacic@{$_SERVER['SERVER_NAME']}");

if ($v_tripa_inventariados<>'')
	{
	/*$queryDEL = "DELETE FROM softwares_inventariados_estacoes 
				 WHERE 	te_node_address = '".$te_node_address."' AND
						id_so = '".$id_so."'";					                 
	$result = mysql_query($queryDEL);*/	

	// MARISOL (28/06/06) - Palavras que definem itens do grupo Atualização
	$queryConfiguracao = "SELECT te_software_gr_at FROM configuracoes LIMIT 1 ";
	$result_conf = mysql_query($queryConfiguracao);	
	$v_reg_conf = mysql_fetch_array($result_conf);
	// MARISOL (28/06/06) - Fazendo array com as palavras retornadas da $queryConfiguracao	
	$v_array_te_software_gr_at = explode(',',$v_reg_conf['te_software_gr_at']);
	
	$v_array_te_inventario_softwares = explode('#',$v_tripa_inventariados);	

	$query_inv = "SELECT * FROM softwares_inventariados";
	$result_inv = mysql_query($query_inv);
	$v_array_te_softwares_inventariados = array ();
	while ($v_reg_inv = mysql_fetch_array($result_inv))
		{	
		array_push($v_array_te_softwares_inventariados,$v_reg_inv['id_software_inventariado']);
		array_push($v_array_te_softwares_inventariados,trim($v_reg_inv['nm_software_inventariado']));		
		}	
	for ($v1=0; $v1 < count($v_array_te_inventario_softwares)-1; $v1 ++)		
		{
		$v_posicao = array_search(trim($v_array_te_inventario_softwares[$v1]), $v_array_te_softwares_inventariados);
		if ($v_posicao)
			{
			$v_achei = $v_array_te_softwares_inventariados[$v_posicao-1];
			}
		else
			{						
				// MARISOL (28/06/06) - Verifica se a palavra vinda da tripa é uma palavra que define itens do grupo Atualização				
				$texto = "";	
				for ($t1=0; $t1 < count($v_array_te_software_gr_at); $t1 ++){								
				  $texto .= strstr(strtoupper($v_array_te_inventario_softwares[$v1]), strtoupper($v_array_te_software_gr_at[$t1]));		
				}

				// MARISOL (28/06/06) - Se conter uma das palavras de Atualização define o id de grupos para 2
				// ID padrão é 1. Todos itens que não forem atualização receberá 1				
				if ($texto != ""){
						$id_si_grupo = 2;
				} else {
						$id_si_grupo = 1;
				}	
			
				// MARISOL (28/06/06) - Inserido campo id_si_grupo grupo na tabela softwares_inventariados 
				$query = "INSERT INTO softwares_inventariados 
									  (nm_software_inventariado,
									   id_si_grupo
									  )											
						  VALUES 	  ('".trim($v_array_te_inventario_softwares[$v1])."',
						  				".$id_si_grupo."
						  			   )";			
				$result = mysql_query($query);
			
            //Marisol 22/06/2006 - Retirado o contador + 1 pq campo id é auto incremento
			//$v_achei = mysql_insert_id()+1;
			$v_achei = mysql_insert_id();
			}
			// MARISOL (16/06/06) - Verifica se já existe o software na lista. Se não, cadastra.
			$Verificar = $Verificar.$v_achei.","; // Armazena todos os ids de software da máquina
			$query_compara = "Select * from softwares_inventariados_estacoes  where 
							  te_node_address='".$te_node_address."'and id_so='".$id_so."' and id_software_inventariado = '".$v_achei."'";
			$result_compara = mysql_query($query_compara);
			$linhas = mysql_num_rows($result_compara);
			if ($linhas == 0){
				$id_istalados .= $v_achei.",";				  	  
				$nome_instalados .= $v_array_te_inventario_softwares[$v1]."\n";
				$query = "INSERT INTO softwares_inventariados_estacoes 
									  (te_node_address,
									   id_so,
									   id_software_inventariado)											
						  VALUES 	  ('".$te_node_address."',
									   '".$id_so."',
									   '".$v_achei."')";					                  				
				
				$result = mysql_query($query);									
			 //echo "Foi inserido: ".$v_achei." - ";
			 	
				//Historico (1 - Inserido)
				$queryInser = "INSERT INTO historico_softwares_inventariados_estacoes 
								  (te_node_address,
								   id_so,
								   id_software_inventariado,
								   data,
								   ind_acao								   
								   )											
					  VALUES  ('".$te_node_address."',
								   ".$id_so.",
								   ".$v_achei.",
								   '".$data."',
								   1)";					                  				
				//echo "<br>Historico: ".$v_achei."<br>";						   
				$result = mysql_query($queryInser);				 
			} // MARISOL - fim alteração				
		}		
	} // Final do For para inserir registros
	
	//  MARISOL (16/06/06) - Variavel com todos os ids de software da máquina.
	$Verificar = substr($Verificar,0,strlen($Verificar)-1); 
	// MARISOL (29/06/06) - Verifica todos os ids que estão sendo inseridos na tabela de estação
	$id_istalados = substr($id_istalados,0,strlen($id_istalados)-1);
	
	// MARISOL (29/06/06) -	Busca todos os grupos, separando os sftwares por grupo e encaminha cada item
	// para o e-mail cadastrado de cada grupo
	$query_grupos = "Select * from softwares_inventariados_grupos order by id_si_grupo";
	$result_grupos = mysql_query($query_grupos);
	
	while ($v_reg_grupos = mysql_fetch_array($result_grupos)) 
	{				
		if ($id_istalados!= ""){			
			$query_comparaVerI = "SELECT I.id_software_inventariado, I.nm_software_inventariado  
								  FROM softwares_inventariados_estacoes C, softwares_inventariados I 
								  WHERE C.te_node_address = '".$te_node_address."' 
								  AND C.id_so = '".$id_so."' AND C.id_software_inventariado IN (".$id_istalados.")  
								  AND C.id_software_inventariado = I.id_software_inventariado 
								  AND I.id_si_grupo =".$v_reg_grupos['id_si_grupo'];				  
			$result_comparaVerI = mysql_query($query_comparaVerI);
			$nome_instalados= "";
			while ($comparaVerI = mysql_fetch_object($result_comparaVerI)){
				$nome_instalados .= $comparaVerI->nm_software_inventariado."\n";
				echo $nome_instalados;
			}			
		}
		
		// MARISOL (16/06/06) - Deletar itens da tabela softwares_inventariados_estacoes e insere em Historico como (2 - deletado)		
		$query_comparaVer = "SELECT I.id_software_inventariado, I.nm_software_inventariado  
							  FROM softwares_inventariados_estacoes C, softwares_inventariados I 
							  WHERE C.te_node_address = '".$te_node_address."' 
							  AND C.id_so = '".$id_so."' AND C.id_software_inventariado NOT IN (".$Verificar.")  
							  AND C.id_software_inventariado = I.id_software_inventariado 
							  AND I.id_si_grupo =".$v_reg_grupos['id_si_grupo'];		
		$result_comparaVer = mysql_query($query_comparaVer);
		$linha_ = mysql_num_rows($result_comparaVer);
		
		//  MARISOL (16/06/06) - Armazena todos os registros encontrados
		$nome_deletar = "";
		while ($comparaVer = mysql_fetch_object($result_comparaVer)){
			$Deletar = $Deletar.$comparaVer->id_software_inventariado."-";
			$nome_deletar .= $comparaVer->nm_software_inventariado."\n";
		}		
		// Chama função de envio de email
		if ($v_reg_grupos['email_si_grupo']!=""){
			if (($nome_instalados !="") || ($linha_>0)){										
				emails($te_node_address,$id_so,$nome_instalados,$nome_deletar,$v_reg_grupos['email_si_grupo']);
			}	
		}	
	}// Fim do while de grupos
	
	// MARISOL (16/06/06) - Prepara para excluir os registros da tabela softwares_inventariados_estacoes e inserir em Historico 
	$ListaDeletar = explode('-',$Deletar);
	for ($Di=0; $Di < count($ListaDeletar)-1; $Di ++){
	
		$queryDEL = "DELETE FROM softwares_inventariados_estacoes 
				 	 WHERE 	te_node_address = '".$te_node_address."' AND
					 id_so = '".$id_so."' AND 
					 id_software_inventariado = '".$ListaDeletar[$Di]."'";					                 
		$result = mysql_query($queryDEL);
		
		$queryInser = "INSERT INTO historico_softwares_inventariados_estacoes 
								  (te_node_address,
								   id_so,
								   id_software_inventariado,
								   data,
								   ind_acao								   
								   )											
					  VALUES  ('".$te_node_address."',
								   ".$id_so.",
								   ".$ListaDeletar[$Di].",
								   '".$data."',
								   2)";					                  				
		//echo "Deletado: ".$ListaDeletar[$Di];						   
		$result = mysql_query($queryInser);	
		
	}
	
	// Função para enviar e-mail
	function emails($te_node_address,$id_so,$nome_instalados,$nome_deletar,$destinatarios){
		//if (trim($destinatarios = get_valor_campo('configuracoes', 'te_notificar_mudanca_hardware')) != '') {
		// Envia e-mail quando ocorre mudança
		//Recupero informações sobre o computador, para montar o cabeçalho do e-mail.
		$query = "SELECT C.id_so,C.te_workgroup,C.te_dominio_windows,C.te_nome_computador, C.id_ip_rede, C.te_ip, S.te_desc_so 
				  FROM computadores C,so S
				  WHERE C.te_node_address = '" . $te_node_address . "'
				  AND C.id_so = '" . $id_so . "'
				  AND S.id_so = C.id_so";
		$result = mysql_query($query);
		// if ($cont_aux > 0) { 
		$corpo_mail = "Prezado administrador. 	  
	Foi identificada uma alteração na configuração de software do seguinte computador:\n
	Nome...............: ". mysql_result( $result, 0, "te_nome_computador" ) ." 
	Endereço TCP/IP....: ". mysql_result( $result, 0, "te_ip" ) . "
	Rede...............: ". mysql_result( $result, 0, "id_ip_rede" ) ."
	Sistema Operacional: ". mysql_result( $result, 0, "te_desc_so" ) ."
	Grupo...........: ". mysql_result( $result, 0, "te_workgroup" ) ." (". mysql_result( $result, 0, "te_dominio_windows" ) ." )\n
	Os Softwares abaixo foram instalados:\n".$nome_instalados."
	Os Softwares abaixo foram desinstalados:\n".$nome_deletar;
	
		//$destinatarios = "ropelato.e@marisol.com.br";
		// Manda mail para os administradores.	
		mail("$destinatarios", "Alteracao de Software Detectada", "$corpo_mail", "From: cacic@{$_SERVER['SERVER_NAME']}");
			
	}	
	
// Configurações do Cacic				   
$v_tripa_variaveis_coletadas = DeCrypt($key,$iv,$_POST['te_variaveis_ambiente'],$_POST['cs_cipher']);	
while (substr(trim($v_tripa_variaveis_coletadas),0,1)=='=')	
	{
	$v_tripa_variaveis_coletadas = substr(trim($v_tripa_variaveis_coletadas),1);
	}

if ($v_tripa_variaveis_coletadas<>'')
	{
	$queryDEL = "DELETE FROM variaveis_ambiente_estacoes 
				 WHERE 	te_node_address = '".$te_node_address."' AND
						id_so = '".$id_so."'";					                  
	$result = mysql_query($queryDEL);									
	
	$v_array_te_variaveis_coletadas = explode('#',$v_tripa_variaveis_coletadas);	

	$query_var = "SELECT *
				  FROM   variaveis_ambiente";
	$result_var = mysql_query($query_var );

	$v_array_te_variaveis_ambiente_na_base = array ();
	while ($v_reg_var = mysql_fetch_array($result_var))
		{	
		array_push($v_array_te_variaveis_ambiente_na_base,$v_reg_var['id_variavel_ambiente']);
		array_push($v_array_te_variaveis_ambiente_na_base,strtolower(trim($v_reg_var['nm_variavel_ambiente'])));		
		}	
	for ($v1=0; $v1 < count($v_array_te_variaveis_coletadas)-1; $v1 ++)
		{
		$v_array_variavel_ambiente_tmp = explode('=',$v_array_te_variaveis_coletadas[$v1]);
		if (trim($v_array_variavel_ambiente_tmp[0])<>'')
			{			
			$v_posicao = array_search(strtolower(trim($v_array_variavel_ambiente_tmp[0])), $v_array_te_variaveis_ambiente_na_base);
			if ($v_posicao)
				{
				$v_achei = $v_array_te_variaveis_ambiente_na_base[$v_posicao-1];
				}
			else
				{			
				$query = "INSERT INTO variaveis_ambiente 
									  (nm_variavel_ambiente)											
						  VALUES 	  ('".strtolower(trim($v_array_variavel_ambiente_tmp[0]))."')";
				$result = mysql_query($query);

				$v_achei = mysql_insert_id();
				}
				$query = "INSERT INTO variaveis_ambiente_estacoes 
									  (te_node_address,
									   id_so,
									   id_variavel_ambiente,
									   vl_variavel_ambiente)											
						  VALUES 	  ('".$te_node_address."',
						  			   '".$id_so."',
									   '".$v_achei."',
									   '".trim($v_array_variavel_ambiente_tmp[1])."')";					                  
				$result = mysql_query($query);									
			}
		}		
	}
echo '<?xml version="1.0" encoding="iso-8859-1" ?> <STATUS>OK</STATUS>';

?>
