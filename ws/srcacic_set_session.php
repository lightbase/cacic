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

 Objetivo:
 ---------
 Esse script tem como objetivo enviar ao servidor de suporte remoto na estação as configurações (em XML) que são específicas para a 
 estação em questão. São levados em consideração a rede do agente, sistema operacional e Mac-Address.
*/

require_once('../include/library.php');

// Definição do nível de compressão (Default = 9 => máximo)
//$v_compress_level = 9;
$v_compress_level   = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						  // Há necessidade de testes para Análise de Viabilidade Técnica 

$retorno_xml_header = '<?xml version="1.0" encoding="iso-8859-1" ?>';
$retorno_xml_values = '';

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher		= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress		= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$v_cs_cipher		= '1';

// Algumas estações enviarão uma string para extensão de bloco
$strPaddingKey  	= '';
	
// Autenticação da Estação Visitada
$te_node_address   	= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_so             	= DeCrypt($key,$iv,$_POST['te_so']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_palavra_chave  	= DeCrypt($key,$iv,urldecode($_POST['te_palavra_chave'])	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

// ATENÇÃO: Apenas retornará um ARRAY contendo "id_so" e "te_so".
$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
											'',
											$te_so,
											'', 
											'', 
											'',
											'');									

$arrComputadores 	= getValores('computadores', 'te_palavra_chave,id_ip_rede' , 'te_node_address = "'.$te_node_address.'" and id_so = '.$arrSO['id_so']);
$arrRedes 			= getValores('redes'       , 'id_local'   				   , 'id_ip_rede= "'.$arrComputadores['id_ip_rede'].'"'); 
$strTePalavraChave	= $arrComputadores['te_palavra_chave'];

//LimpaTESTES();

// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos domínios
if ($te_palavra_chave == $strTePalavraChave)
	{
	GravaTESTES('SetSession: Palavra-Chave OK!'); 	
	conecta_bd_cacic();	

	if (!$_POST['id_sessao'])
		{
		// Identificador para Autenticação no Domínio
		$id_dominio  		     = DeCrypt($key,$iv,$_POST['id_dominio']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
		$nm_nome_acesso_dominio	 = DeCrypt($key,$iv,$_POST['nm_nome_acesso_dominio']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
		$te_senha_acesso_dominio = DeCrypt($key,$iv,$_POST['te_senha_acesso_dominio']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
	
		$arrDominios = getValores('dominios',  'nm_dominio,
												te_ip_dominio,
												id_tipo_protocolo,
												nu_versao_protocolo,
												te_base_consulta_raiz,
												te_base_consulta_folha,
												te_atributo_identificador,
												te_atributo_retorna_nome,
												te_atributo_retorna_email'   , 'id_dominio = '.$id_dominio.' AND in_ativo="S"');


		GravaTESTES('SetSession: Valores:'); 			
		GravaTESTES('SetSession: nm_nome_acesso_dominio: '.$nm_nome_acesso_dominio); 							
		GravaTESTES('SetSession: te_senha_acesso_dominio: '.$te_senha_acesso_dominio); 									
		GravaTESTES('SetSession: arrDominios[nm_dominio]: '.$arrDominios['nm_dominio']); 					
		GravaTESTES('SetSession: arrDominios[te_ip_dominio]: '.$arrDominios['te_ip_dominio']); 							
		GravaTESTES('SetSession: arrDominios[id_tipo_protocolo]: '.$arrDominios['id_tipo_protocolo']); 					
		GravaTESTES('SetSession: arrDominios[nu_versao_protocolo]: '.$arrDominios['nu_versao_protocolo']); 					
		GravaTESTES('SetSession: arrDominios[te_base_consulta_raiz]: '.$arrDominios['te_base_consulta_raiz']);
		GravaTESTES('SetSession: arrDominios[te_base_consulta_folha]: '.$arrDominios['te_base_consulta_folha']);
		GravaTESTES('SetSession: arrDominios[te_atributo_identificador]: '.$arrDominios['te_atributo_identificador']);		
		GravaTESTES('SetSession: arrDominios[te_atributo_retorna_nome]: '.$arrDominios['te_atributo_retorna_nome']);		
		GravaTESTES('SetSession: arrDominios[te_atributo_retorna_email]: '.$arrDominios['te_atributo_retorna_email']);				
	
		// Comunicação com o servidor de Domínio, para autenticação

		$te_atributo_retorna_nome	= $arrDominios['te_atributo_retorna_nome'];
		$te_atributo_retorna_email	= $arrDominios['te_atributo_retorna_email'];		
		$te_host 					= $arrDominios['nm_dominio'];

		$ldap = ldap_connect($te_host,389);
		
		GravaTESTES('SetSession: Após CONNECT em "'.$te_host.'" => '.ldap_error($ldap)); 			
		
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, $arrDominios['nu_versao_protocolo']);
		GravaTESTES('SetSession: Após SET_OPTION => '.ldap_error($ldap)); 			
		
		//ldap_start_tls($ldap);

		if (ldap_errno($ldap) == 0) 
			{
			GravaTESTES('SetSession: Tentando BIND Identificado com "'.$nm_nome_acesso_dominio.' / **********"');
				
   			$bindTesteAnonimo=ldap_bind($ldap);    // this is an "anonymous" bind, typically
                           // read-only access
   			GravaTESTES("Bind Anônimo retornou " . $bindTesteAnonimo);
				
			//ob_start();
			$strBind = $arrDominios['te_atributo_identificador'].'='.$nm_nome_acesso_dominio.','.$arrDominios['te_base_consulta_folha'].','.$arrDominios['te_base_consulta_raiz'];
			$bind = ldap_bind($ldap, "$strBind", $te_senha_acesso_dominio);
			//ob_end_flush();
			
			if (ldap_errno($ldap) == 0)
				{
				$strRootDN = $arrDominios['te_base_consulta_raiz'];
				$strNodeDN = $arrDominios['te_base_consulta_folha'];
				GravaTESTES('SetSession: Preparando Search : '.'"'.$strNodeDN.','.$strRootDN.'" / '.'"'.$arrDominios['te_atributo_identificador'].'='.$nm_nome_acesso_dominio.'"');
				$searchResults = ldap_search($ldap, "$strNodeDN,$strRootDN",$arrDominios['te_atributo_identificador'].'='.$nm_nome_acesso_dominio);
			
				GravaTESTES('SetSession: Após SEARCH => '.ldap_error($ldap));		
				// OK! Dados encontrados!
				if (!$searchResults === false)
					{
					$arrLDAPdata = array();
					$ldapResults = ldap_get_entries($ldap, $searchResults);
					for ($item = 0; $item < $ldapResults['count']; $item++) 
						{
						for ($attribute = 0; $attribute < $ldapResults[$item]['count']; $attribute++) 
							{
							$data = $ldapResults[$item][$attribute];
							$arrLDAPdata[$data]=$ldapResults[$item][$data][0];            
							}
						}
				
					GravaTESTES('SetSession: Após GET_ENTRIES => '.ldap_error($ldap));		
					$nm_nome_completo  		= $arrLDAPdata[strtolower($arrDominios['te_atributo_retorna_nome'])];					
					$te_email 				= $arrLDAPdata[strtolower($arrDominios['te_atributo_retorna_email'])];					
					GravaTESTES('SetSession: Após GET_ENTRIES => nm_nome_completo: '.$nm_nome_completo);							
					GravaTESTES('SetSession: Após GET_ENTRIES => te_email: '.$te_email);												
					
					if ($nm_nome_completo <> '')
						{
						$dt_hr_inicio_sessao	= date('Y-m-d H:i:s');						
					
						if ($te_email <> '')
							{
							// Envio e-mail informando da abertura de sessão
							$corpo_mail = "Prezado usuário(a) ".$nm_nome_completo.",\n\n
											informamos que foi iniciada uma sessão para Suporte Remoto Seguro através do Sistema CACIC em ".$dt_hr_inicio_sessao . "\n\n\n\n
												_______________________________________________________________________
												CACIC - Configurador Automático e Coletor de Informações Computacionais\n
												srCACIC - Módulo para Suporte Remoto Seguro do Sistema CACIC\n
												Desenvolvido pela Dataprev - Unidade Regional Espírito Santo";
	
							GravaTESTES('SetSession: Enviando Email...');													
							// Manda mail para os administradores.
							//mail("$te_email", "Sistema CACIC - Módulo srCACIC - Abertura de Sessão para Suporte Remoto Seguro", "$corpo_mail", "From: cacic@{$_SERVER['SERVER_NAME']}");
							}			
						$query_SESSAO = "INSERT INTO srcacic_sessoes 
													(dt_hr_inicio_sessao,
													 nm_acesso_usuario_visitado,
													 nm_completo_usuario_visitado,
													 te_email_usuario_visitado,													 
													 te_node_address_visitado,
													 id_so_visitado,
													 dt_hr_ultimo_contato)
										VALUES ('" . $dt_hr_inicio_sessao 		. "', 
												'" . $nm_nome_acesso_dominio 	. "',
												'" . $nm_nome_completo 			. "',									
												'" . $te_email 					. "',																					
												'" . $te_node_address			. "',
												'" . $arrSO['id_so']			. "',
												'" . $dt_hr_inicio_sessao		. "')";
					GravaTESTES('SetSession: query_SESSAO: '.$query_SESSAO);																	
						$result_SESSAO = mysql_query($query_SESSAO);	
						$arrSessoes = getValores('srcacic_sessoes','id_sessao','dt_hr_inicio_sessao="'.$dt_hr_inicio_sessao.'" AND
																				te_node_address_visitado="'.$te_node_address.'" AND
																				id_so_visitado = "'.$arrSO['id_so'].'"');

						$arrConfiguracoesLocais   = getValores('configuracoes_locais','nu_timeout_srcacic','id_local = '.$arrRedes['id_local']);
	
						$retorno_xml_values .= '<NM_COMPLETO>'.EnCrypt($key,$iv,$nm_nome_completo,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</NM_COMPLETO>';						
						$retorno_xml_values .= '<ID_SESSAO>'.EnCrypt($key,$iv,$arrSessoes['id_sessao'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</ID_SESSAO>';
						$retorno_xml_values .= '<NU_TIMEOUT_SRCACIC>'.EnCrypt($key,$iv,$arrConfiguracoesLocais['nu_timeout_srcacic'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</NU_TIMEOUT_SRCACIC>';					
						}
					}
				}
			}
		}
	else
		{
		$id_sessao 	  = DeCrypt($key,$iv,$_POST['id_sessao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 					

		$query_SESSAO = "UPDATE srcacic_sessoes 
						 SET	dt_hr_ultimo_contato = '".date('Y-m-d H:i:s')."'
						 WHERE  id_sessao = ".$id_sessao;												
		$result_SESSAO = mysql_query($query_SESSAO);			
		
		$arr_id_usuario_visitante      = explode('<REG>',$_POST['id_usuario_visitante']);
		$arr_te_node_address_visitante = explode('<REG>',$_POST['te_node_address_visitante']);		
		$arr_te_so_visitante 		   = explode('<REG>',$_POST['te_so_visitante']);				
	
		for ($i=0; $i < count($arr_id_usuario_visitante); $i++)
			{
			$id_usuario_visitante 		= DeCrypt($key,$iv,$arr_id_usuario_visitante[$i],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 			
			$te_node_address_visitante 	= DeCrypt($key,$iv,$arr_te_node_address_visitante[$i],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 					
			$te_so_visitante 			= DeCrypt($key,$iv,$arr_te_so_visitante[$i],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 								

			// ATENÇÃO: Apenas retornará um ARRAY contendo "id_so" e "te_so".
			$arrSO_visitante = inclui_computador_caso_nao_exista($te_node_address_visitante, 
																'',
																$te_so_visitante,
																'', 
																'', 
																'',
																'');									

			$query_SESSAO = "UPDATE srcacic_sessoes_logs 
							 SET	dt_hr_ultimo_contato = '".date('Y-m-d H:i:s')."'							 
							 WHERE  id_sessao = ".$id_sessao." and
									id_usuario_visitante = ".$id_usuario_visitante." and
									te_node_address_visitante = '".$te_node_address_visitante."' and
									id_so_visitante = ".$arrSO_visitante['id_so'];		
								
			GravaTESTES('SetSession: POST[id_sessao] => '.$_POST['id_sessao']);						 				 						 
			GravaTESTES('SetSession: id_sessao => '.$id_sessao);						 				 						 		
			GravaTESTES('SetSession: query_SESSAO => '.$query_SESSAO);						 				 						 		
	
			$result_SESSAO = mysql_query($query_SESSAO);			
		
			}
		
		$retorno_xml_values .= '<OK>'.EnCrypt($key,$iv,'OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</OK>';		
		}
	}

if ($retorno_xml_values <> '')
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>'.$retorno_xml_values;
else
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'SetSession ERRO! '.ldap_error($ldap),$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';

$retorno_xml = $retorno_xml_header . $retorno_xml_values; 

echo $retorno_xml;	
?>
	
