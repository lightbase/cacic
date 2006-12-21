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
require_once 'config.php';
// --------------------------------------------------------------------------------------
// Função para bloqueio de acesso indevido
// --------------------------------------------------------------------------------------
function anti_spy()
	{
	return true;
	$v_AtualPWD = getcwd(); // Conservo o caminho do script chamador...
	ChangeTo('include'); // Faço um ChangeDir para a pasta "include"...
	include getcwd().'/config.php'; // Incluo o config.php para pegar as chaves de criptografia	
	if (session_is_registered("id_usuario") && session_is_registered("id_usuario_crypted") && 
	   ($_SESSION["id_usuario_crypted"] == EnCrypt($key,$iv,$_SESSION["id_usuario"],"1","0","0")))
	   	{
		// Volto para o caminho do script chamador...		
		@chdir($v_AtualPWD); 
   		return true;
		}		
	@chdir($v_AtualPWD); 		
	$te_retornos = TotalSkipsToInclude();

	$Location = "Location: ".$te_retornos."include/acesso_nao_permitido.php?chamador=../index.html&tempo=5";

	header ($Location);		
	exit;		
	}
// --------------------------------------------------------------------------------------
// Função para busca e mudança de diretório
// --------------------------------------------------------------------------------------
function TotalSkipsToInclude()
	{
	$v_te_retornos    = '';
	$v_SO 			= strtoupper($_SERVER['SERVER_SOFTWARE']); // Windows -> WebServerName/xx.yy.zz (Win32)...
							   					 			   // Linux   -> WebServerName/xx.yy.zz (Unix)...

	$v_barra 		= (strstr($v_SO,'UNIX')?'/':'\\'); // Diferença de barra em função do S.O.
	$str_local_CWD	= getcwd();

	$arr_local_CWD 	= explode($v_barra,$str_local_CWD);

	for ($i = 0;$i <= count($arr_local_CWD); $i++) 
		{
		if (@chdir($str_local_CWD . $v_barra . 'include'))
			$i = count($arr_local_CWD);
		else
			{
			$str_local_CWD = substr($str_local_CWD,0,strrpos($str_local_CWD,$v_barra));
			$v_te_retornos .= '../'; // Acumulo os saltos...
			}
		}				
	return $v_te_retornos;
	}
// -------------------------------------------------
// Função para contar retornos até a pasta "include"
// -------------------------------------------------
function ChangeTo($p_Folder)
	{
	$v_SO 			= strtoupper($_SERVER['SERVER_SOFTWARE']); // Windows -> WebServerName/xx.yy.zz (Win32)...
							   					 			   // Linux   -> WebServerName/xx.yy.zz (Unix)...

	$v_barra 		= (strstr($v_SO,'UNIX')?'/':'\\'); // Diferença em função do S.O.
	$str_getCWD 	= getcwd(); // Exemplo de Resultado: /var/www/default/cacic2/admin
	$str_local_CWD 	= $str_getCWD;
	$arr_local_CWD 	= explode($v_barra,$str_getCWD);

	// Percorrerei o caminho encontrado a partir do retorno de getCWD()...
	for ($i = 0;$i < count($arr_local_CWD); $i++) 
		{
		if (@chdir($str_local_CWD . $v_barra . $p_Folder))
			return true;
		else
			$str_local_CWD = substr($str_local_CWD,0,strrpos($str_local_CWD,$v_barra));
		}				
	return false;
	}
// ---------------------------------
// Função usada para descriptografia 
// To decrypt values 
// p_cs_cipher => Y/N 
// ---------------------------------
function DeCrypt($p_CipherKey, $p_IV, $p_CriptedData, $p_cs_Cipher, $p_cs_UnCompress) 
	{
	if ($p_cs_Cipher=='1') 
		$v_result = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$p_CipherKey,base64_decode($p_CriptedData),MCRYPT_MODE_CBC,$p_IV);
	else
		$v_result = $p_CriptedData;	
		
	if ($p_cs_UnCompress == '1')
		$v_result = gzinflate($v_result);		
		
	return trim($v_result);
	}

// ------------------------------
// Função usada para criptografia
// To crypt values
// p_cs_cipher => Y/N 
// ------------------------------
function EnCrypt($p_CipherKey, $p_IV, $p_PlainData, $p_cs_Cipher, $p_cs_Compress, $p_compress_level) 
	{
	if ($p_cs_Cipher=='1') 
		$v_result = base64_encode(@mcrypt_cbc(MCRYPT_RIJNDAEL_128,$p_CipherKey,$p_PlainData,MCRYPT_ENCRYPT,$p_IV));		
	else
		$v_result = $p_PlainData;	

	if (($p_cs_Compress == '1' || $p_cs_Compress == '2') && $p_compress_level > 0)
		$v_result = gzdeflate($v_result,$p_compress_level);
//		$v_result = url_encode(gzcompress($v_result,$p_compress_level));		

	return trim($v_result);		
	}

// --------------------------------------------------------------------------------------
// Função de conexão a um servidor de FTP
// --------------------------------------------------------------------------------------
function conecta_ftp($p_te_serv, $p_user_name, $p_user_pass, $p_port) 
	{
	//Conecta ao servidor FTP
	$con = @ftp_connect("$p_te_serv","$p_port");

	//Faz o login no servidor FTP
	$result = @ftp_login($con, $p_user_name, $p_user_pass);
	
	return ($result?$con:'0');
	}

//________________________________________________________________________________________________
// Retorna o PRIMEIRO e ULTIMO nome no argumento $Nome
//________________________________________________________________________________________________
function PrimUltNome($Nome)
	{
	$Array_Nome = explode(" ", $Nome);
	$FatorDecremento = FatorDecremento(Count($Array_Nome));
  	return $Array_Nome[0] . " " . $Array_Nome[Count($Array_Nome) - $FatorDecremento];	
	}
//________________________________________________________________________________________________
// Retorna o PRIMEIRO nome no argumento $Nome
//________________________________________________________________________________________________
function PrimNome($Nome)
	{
	$Array_Nome = explode(" ", $Nome);
  	return $Array_Nome[0];	
	}

//________________________________________________________________________________________________
// Retorna o nome abreviado e com pontos
//________________________________________________________________________________________________
function Abrevia($Nome)
	{
	$Array_Nome = explode(" ", $Nome);
	$v_return = '';
	if (count($Array_Nome)>1)
		for ($i=0; $i<count($Array_Nome); $i++)
			$v_return .= substr($Array_Nome[$i], 0, 1).'.';
	else
		$v_return = substr($Array_Nome[0],0,3).'.';
		
  	return $v_return;	
	}

//________________________________________________________________________________________________
// Limpa a tabela TESTES, utilizada para depuração de código
//________________________________________________________________________________________________
function LimpaTESTES()
	{
	conecta_bd_cacic();
	$queryDEL  = 'DELETE from testes';
	$resultDEL = mysql_query($queryDEL);
	}

//_____________________
// Grava Log para DEBUG
//_____________________
function Log_Debug($p_msg)
	{
	$posIPsToDebug = strpos($GLOBALS["cIPsToDebug"],$_SERVER['REMOTE_ADDR']); 
	if ($_SESSION['c_Debugs'] || $GLOBALS["cIPsToDebug"] =='*' || $posIPsToDebug >= 0)// Valores setados em "menu_esq.php" (c_Debugs) e "get_config.php" (cIPsToDebug)
		{
		$pos = strpos($_SESSION['c_IpsDebugs'],$_SERVER['REMOTE_ADDR']); 
		if ($pos)
			{
			GravaTESTES($p_msg);
			}
		}
	}

//___________________________________
// Grava informações na tabela TESTES
//___________________________________
function GravaTESTES($p_Valor)
	{
	conecta_bd_cacic();
	$date = getdate(); 
	$queryINS  = "INSERT into testes(te_linha) VALUES ( '(".$date['mday'].'/'.$date['mon'].'/'.$date['year'].' - '.$date['hours'].':'.$date['minutes'].':'.$date['seconds'].")Server " .$_SERVER['HTTP_HOST']." Station: ".$_SERVER['REMOTE_ADDR']." - ".$p_Valor . "')";
	$resultINS = mysql_query($queryINS);
	}

//________________________________________________________________________________________________
// Grava na tabela LOG informações de atividades
//________________________________________________________________________________________________
function GravaLog($p_cs_acao, $p_nm_script, $p_nm_tabela)
	{
	$arr_nm_script = explode('/',$p_nm_script);	
	conecta_bd_cacic();
	$queryINS  = "INSERT 
				  INTO		log
				  			(dt_acao,
							cs_acao,
							nm_script,
							nm_tabela,
							id_usuario,
							te_ip_origem) 
				  VALUES 	(NOW(),
				  			'$p_cs_acao',
							'".$arr_nm_script[count($arr_nm_script)-1]."',
							'$p_nm_tabela',
							'".$_SESSION['id_usuario']."',
							'".$_SERVER['REMOTE_ADDR']."')";
	$resultINS = mysql_query($queryINS);
	}
	

//________________________________________________________________________________________________
// Retorna 1 ou 0 para decremento de PRIMEIRO e ULTIMO nome
//________________________________________________________________________________________________
function FatorDecremento($Numero)
	{
	if ($Numero>1)
		{
		return 1;
		}
	else
		{
		return 0;
		}
	}
// _______________________________________________________________	

// --------------------------------------------------------------------------------------
// Função de conexão ao BD do CACIC
// --------------------------------------------------------------------------------------
function conecta_bd_cacic() 
{

	$ident_bd = mysql_connect($GLOBALS["ip_servidor"] . ':' . $GLOBALS["porta"], $GLOBALS["usuario_bd"], $GLOBALS["senha_usuario_bd"]);
	if (mysql_select_db($GLOBALS["nome_bd"], $ident_bd) == 0) { die('Problemas durante a conexão ao BD.'); }
}

// ------------------------------------------------------------------------------
// Função para obtenção de dados da subrede de acesso, em função do IP e Máscara.
// Function to retrieve access subnet data, based on IP/Mask address.
// ------------------------------------------------------------------------------
function GetDadosRede()
	{
	conecta_bd_cacic();	

	$query_redes  	= "SELECT 	id_ip_rede,
								te_mascara_rede
					   FROM		redes";
	$result_redes 	= mysql_query($query_redes);

	$v_id_ip_rede 	= '';
	$v_te_ip 	  	= $_SERVER["REMOTE_ADDR"];

	// Percorro cada ID_IP_REDE + TE_MASCARA_REDE para checar se o IP da estação está na faixa de IPs
	while ($v_dados_redes = mysql_fetch_array($result_redes))
		{
       	$ip_octets = split("\.", $v_te_ip);
       	$mask_octets = split("\.", $v_dados_redes['te_mascara_rede']);
       	unset($bin_sn);      	
		for ( $o = 0; $o < count($ip_octets); $o++ )
			{
       		$bin_sn[] = decbin(intval($ip_octets[$o]) & intval($mask_octets[$o]));			
       		}
		$apply_mask = join(".", $bin_sn);

       	$ip_octets = split("\.", $apply_mask);
       	unset($bin_sn);
       	for ( $o = 0; $o < count($ip_octets); $o++ )
			{
           		$bin_sn[] = bindec($ip_octets[$o]);
       		}
       	$subnet = join(".", $bin_sn);				
		if (trim($subnet)== trim($v_dados_redes['id_ip_rede']))
			{
			$v_id_ip_rede = trim($v_dados_redes['id_ip_rede']); 
			break;
			}		
		}
	// Obs.: as colunas sg_local e nm_local são requeridas por menu_esq.php		
	//       the columns sg_local and nm_local have been requested by menu_esq.php			
	$query_ver = "	SELECT 	te_serv_cacic,
							te_serv_updates,
							nu_limite_ftp,
							nu_porta_serv_updates, 
							te_path_serv_updates, 							
							nm_usuario_login_serv_updates,
							te_senha_login_serv_updates,
							id_ip_rede,
							redes.id_local,
							sg_local,
							nm_local							
					FROM	redes,
							locais
					WHERE 	redes.id_ip_rede = '$v_id_ip_rede' AND
							redes.id_local = locais.id_local";
	$result_ver = mysql_query($query_ver);

	if (!$v_dados = @mysql_fetch_array($result_ver))
		{		

		// Neste caso, apela-se para uma rede que tenha configurações válidas...
		$query_ver = "	SELECT 	redes.id_ip_rede,	
								redes.te_serv_updates,
								redes.nu_limite_ftp,
								redes.nu_porta_serv_updates, 
								redes.te_path_serv_updates, 
								redes.nm_usuario_login_serv_updates,
								redes.te_senha_login_serv_updates,
								redes.te_serv_cacic,
								redes.id_local
						FROM	redes,
								configuracoes_locais conf
						WHERE 	conf.te_serv_updates_padrao = redes.te_serv_updates and
								trim(redes.nu_porta_serv_updates) <> '' and
								trim(redes.nm_usuario_login_serv_updates) <> '' and
								trim(redes.te_senha_login_serv_updates) <> ''
						LIMIT 1";
		$result_ver = mysql_query($query_ver);
		$v_dados	= mysql_fetch_array($result_ver);
		}	
	return $v_dados;
	}

// --------------------------------------------------------------------------------------
// Para Atualização das colunas dt_hr_alteracao_patrim_uonx da table configuracoes
// quando INCLUDE/UPDATE/DELETE em Unidades Organizacionais
// --------------------------------------------------------------------------------------

function atualiza_configuracoes_uonx($p_uonx) 
	{
    conecta_bd_cacic();
	$v_nome_coluna = 'dt_hr_alteracao_patrim_uon'.$p_uonx;
	$query = "	UPDATE  configuracoes_locais 
				SET		".$v_nome_coluna." = now()";

    if (mysql_query($query)) 
		{ 
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');		
		return '1'; 
		}
	else return '0';
	}




function conecta_bd_cacic_web() 
	{
    if (conecta_bd_cacic() == '0') 
		{
 	    echo '<br><br><br><br><br> 
		<table border="1" cellpadding="0" cellspacing="0" align="center" width="0%">
		<tr> 
		<td bgcolor="#000064" align="center"> <font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b> 
        &nbsp;&nbsp;Falha na Conex&atilde;o ao Banco de Dados CACIC&nbsp;&nbsp;&nbsp;</b></font></td>
		</tr>
		<tr>
        <td bgcolor="#c0c0c0" bordercolor="#c0c0c0" align="center">
		<table border="0" cellspacing="4" cellpadding="4">
        <tr> 
        <td><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><strong>ATEN&Ccedil;&Atilde;O:</strong> 
        <br>
        N&atilde;o foi poss&iacute;vel estabelecer comunica&ccedil;&atilde;o com o banco de dados do CACIC.</font></p>
        <p><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000">Poss&iacute;veis causas:</font></font></p>
        <ul>
		<li><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif" align="left">Computador servidor </font><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000"> do CACIC desligado;</font></font></li>
        <li><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Banco de Dados do CACIC desativado</font><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000">;</font></font></font></li>
        <li><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000">Problemas de comunica&ccedil;&atilde;o na rede local;</font></font></li>
        <li><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000">Incorre&ccedil;&atilde;o em par&acirc;metros de conex&atilde;o.</font></font></li>
        </ul></td></tr></table></td></tr></table>';
	} 
}
// Function for calculating the network address \\
##################################################
function network_address($cadpri,$cadsec) 
	{
	$tempchar="";
	$cadres="";
	$cadpri=dectobin($cadpri);
	$cadsec=dectobin($cadsec);
	
	for ($i=0;$i<8;$i++) 
		{
		$tempchar=substr($cadpri,$i,1);
		
		if ($tempchar=="1") 
			{
			$cadres=$cadres.substr($cadsec,$i,1);
			} 
		else 
			{
			$cadres=$cadres."?";
			}
		}
	return bindec(strtr($cadres,"?","0"));
	}
// Fuction for turning a decimal into a binary number \\
########################################################
function dectobin($dectobin) 
	{
	$cadtemp="";
	$dectobin = decbin($dectobin);
	$numins = 8 - strlen($dectobin);

	for ($i = 0; $i < $numins; $i++) 
		{
		$cadtemp = $cadtemp."0";
		}
	return $cadtemp.$dectobin;
	}

function autentica_agente($p_CipherKey, $p_IV, $p_cs_cipher, $p_cs_compress) 
	{
	if ((strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['HTTP_USER_AGENT'],$p_cs_cipher, $p_cs_compress)) != 'AGENTE_CACIC') ||
	    (strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_USER'],$p_cs_cipher, $p_cs_compress)) != 'USER_CACIC') ||
	    (strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_PW'],$p_cs_cipher, $p_cs_compress)) != 'PW_CACIC'))   
	   	{
        echo 'Acesso não autorizado.';
		exit;
		} 
	}


// --------------------------------------------------------------------------------------
// Função que verifica se um dado computador já esta´cadastrado no BD  
// --------------------------------------------------------------------------------------
function computador_existe($te_node_address, $id_so) 
	{
    conecta_bd_cacic();
	$query = "SELECT te_node_address, te_nome_computador, te_ip, id_ip_rede, te_workgroup
	          FROM computadores
			  WHERE te_node_address = '$te_node_address'
			  AND id_so = '$id_so'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);


    if (mysql_num_rows($result) == 0)
		{
		return '0';
		}
	elseif ($row['te_nome_computador'] == '' || 
			$row['te_ip'] == '' ||
			$row['id_ip_rede'] == '' || 
			$row['te_workgroup'] == '') 									
		{
		 return '2';  // O computador existe porém sem uma dessas informações...  - Anderson 16/04/2004 - 21:31h!!!!!!!!!!!!
		}
	else 
		{ 
		return '1'; 
		}
	}


// --------------------------------------------------------------------------------------
// Função que insere se um dado computador no BD, caso ele não esteja inserido.
// --------------------------------------------------------------------------------------
function inclui_computador_caso_nao_exista(	$te_node_address, 
											$id_so, 
											$id_ip_rede, 
											$te_ip, 
											$te_nome_computador, 
											$te_workgroup) {											
	$checa_existe = computador_existe($te_node_address, $id_so);
    if ($checa_existe == '0') 
		{ 
		conecta_bd_cacic();
		$query = "INSERT INTO computadores (te_node_address, id_so, id_ip_rede, te_ip, te_nome_computador, te_workgroup, dt_hr_inclusao, dt_hr_ult_acesso)
				  VALUES ('$te_node_address', '$id_so', '$id_ip_rede','$te_ip','$te_nome_computador','$te_workgroup', NOW(), NOW())";
		$result = mysql_query($query);
		return '1'; // Esse código indica se o computador foi incluído.
		}
	elseif ($checa_existe == '2')
		{
		conecta_bd_cacic();
		$query = "UPDATE computadores 
          		  SET id_ip_rede = '$id_ip_rede',
				      te_ip = '$te_ip',
					  te_nome_computador='$te_nome_computador',
					  te_workgroup='$te_workgroup'					  
			      WHERE te_node_address = '$te_node_address'
						AND id_so = '$id_so'";
		$result = mysql_query($query);
		return '2'; // Esse código indica se o computador foi atualizado.  Anderson.						
		} 
	else { return '0'; } // Esse código indica se o computador já estava incluído.
}


/* --------------------------------------------------------------------------------------
 Função usada para recuperar valores de campos únicos. Útil para a tabela de configurações.
 -------------------------------------------------------------------------------------- */
function get_valor_campo($tabela, $campo, $where="1") 
	{
	conecta_bd_cacic();
	$query = "SELECT 	$campo
	          FROM 		$tabela
			  WHERE 	$where
			  LIMIT 	1";
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) 
		{
		$campos = mysql_fetch_array($result);
		return $campos[$campo];
		} 
	else 
		{ 
		return ''; 
		}
	}


function get_subrede($ip) 
	{
    $subrede = explode( '.', $ip);
    return $subrede[0] . '.' . $subrede[1] . '.' . $subrede[2] . '.0';
	}


// --------------------------------------------------------------------------------------
// Função usada para uma mensagem
// --------------------------------------------------------------------------------------
function mensagem($msg) 
	{
	 $texto = '<table  width="75%" border="0" align="center" cellpadding="15" cellspacing="1" bgcolor="#666666">
					  <tr> 
						<td valign="top" bgcolor="#EEEEEE">
						<p align="center">
						<font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
						<strong>
						'. $msg .'
						</strong>
						</font>
						</p>
						</td>
					  </tr>
					</table>';
	return $texto;
	}

// --------------------------------------------------------------------------------------
// Função usada para cortar uma string
// --------------------------------------------------------------------------------------
function capa_string($string, $tamanho_desejado) 
	{
	 // A idéia dessa função é deixar os listbox do formulário com um tamanho fixo.
	 $tamanho_original = strlen($string);
	 if ($tamanho_original > $tamanho_desejado) return substr($string,0, $tamanho_desejado - 1) . '...';
	 else 
	 	{ 
		for ($i = $tamanho_original; $i < $tamanho_desejado; $i++) $string = $string . '&nbsp;';
		return $string;
	 	} 
	}

// --------------------------------------------------------------------------------------
// Função usada para fazer uma quebra de linha em uma string
// --------------------------------------------------------------------------------------
function quebra_linha($string, $tamanho_desejado) {
	 $tamanho_original = strlen($string);
	 for($i = 0; $i < $tamanho_original; $i++) {
		if($cont == $tamanho_desejado) {
			$cont = 0;
			$nova_string = $nova_string . '<br>';
			$i--;
		}
		else {
			$cont++;
			$nova_string = $nova_string . $string[$i];
		}
	 }
	 return $nova_string;
}

// --------------------------------------------------------------------------------------
// Função usada para fazer updates das versões dos módulos nas subredes...
// --------------------------------------------------------------------------------------
function atualiza_red_ver_mod($pp_id_ip_rede, $p_nm_modulo, $p_te_versao_modulo, $p_id_local)
	{
	$MainFolder		= GetMainFolder();
	conecta_bd_cacic();
	$query_UPD = "	UPDATE 	redes 
					set dt_verifica_updates = NOW() 
					WHERE 	id_ip_rede = '".$pp_id_ip_rede."' AND
					        id_local = ".$p_id_local;		
	$result = mysql_query($query_UPD);
	
	$query_DEL	= "DELETE 	
				   FROM 	redes_versoes_modulos
				   WHERE 	TRIM(id_ip_rede) = '".trim($pp_id_ip_rede)."' AND
				   			TRIM(nm_modulo)='".trim($p_nm_modulo)."' AND
							id_local = ".$p_id_local;
	$result_DEL = mysql_query($query_DEL);

	$v_te_versao_modulo = $p_te_versao_modulo;
	if (file_exists($MainFolder . '/repositorio/versoes_agentes.ini'))
		{
		$v_te_versao_modulo = str_replace('.','',$v_te_versao_modulo);
		}

	$query_INS	= "INSERT  
				   INTO 		redes_versoes_modulos (id_ip_rede,
													   nm_modulo,
													   te_versao_modulo,
													   id_local)
						   values					   ('".$pp_id_ip_rede."',
														'".$p_nm_modulo."',
														'".$v_te_versao_modulo."',".
														$p_id_local.")";

	$result_INS = mysql_query($query_INS);
	}


// --------------------------------------------------------------------------------------
// Função usada para listar o conteúdo do servidor de updates...
// --------------------------------------------------------------------------------------
function lista_updates($p_te_serv_updates, 
					   $p_nm_usuario_login_serv_updates_gerente,
					   $p_te_senha_login_serv_updates_gerente,
					   $p_nu_porta_serv_updates,
					   $p_te_path_serv_updates) 
	{
	$v_conexao_ftp = conecta_ftp($p_te_serv_updates,
								 $p_nm_usuario_login_serv_updates_gerente,
								 $p_te_senha_login_serv_updates_gerente,
								 $p_nu_porta_serv_updates
								);
	$resultado = '';
	if ($v_conexao_ftp)
		{
		// obtém a lista de arquivos para /$p_te_path_serv_updates
		$buff = @ftp_rawlist($v_conexao_ftp, $p_te_path_serv_updates);

		$buff = @implode('#',$buff);

		if ($buff)
			{
			// Elimina incidência de espaços duplicados
			while (strpos($buff,'  ') > 0) 
				{
				$buff = str_replace('  ',' ',$buff);			
				}									
		
			$buff = explode('#',$buff);
		
		 	for ($i=0;$i<count($buff);$i++)
				{
				$itens = explode(' ',$buff[$i]);
				if ($itens[8] == 'versoes_agentes.ini')
					{
					$i = count($buff);
					// define some variables
					$local_file  = '/tmp/versoes_agentes_'.$p_te_serv_updates.'.ini';
					$server_file = $p_te_path_serv_updates.'/versoes_agentes.ini';			
					if (@ftp_get($v_conexao_ftp, $local_file, $server_file, FTP_ASCII))
						{
						$v_array_versoes_agentes = parse_ini_file('/tmp/versoes_agentes_'.$p_te_serv_updates.'.ini');
						}
					}
				}
		
		 	for ($i=0;$i<count($buff);$i++)
				{
				$itens = explode(' ',$buff[$i]);
				if ($itens[8] <> 'supergerentes')
					{
					$tamanho = ($itens[4]/1024);		
					if ($itens[4]<1024) $tamanho = 0;

					$resultado .= '<tr><td align="right">'.($i+1).'</td><td>'.$itens[8].'</td><td align="right">'.$tamanho.'</td><td colspan="3">';
					if ($v_array_versoes_agentes[$itens[8]])
						{
						$resultado .= $v_array_versoes_agentes[$itens[8]];
						}
					else
						{
						$resultado .= $itens[6].'-'.$itens[5].'&nbsp;/&nbsp;'.$itens[7].'h';			
						}
					$resultado .= '</td></tr>';
					}
				}
			}
		if (!$resultado) $resultado .= '<tr><td colspan="4" align="center"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>PASTA DE UPDATES VAZIA OU INACESSÍVEL!</b></font></td></tr>';	
	}

// fecha a conexão
//ftp_close($v_conexao_ftp);
	
return $resultado;
}

// --------------------------------------------------------------------------------------
// Função usada para calcular diferença entre datas...
// É necessário usar o formato MM-DD-AAAA nessa função
// --------------------------------------------------------------------------------------
function date_diff($from, $to) 
	{ 
  	list($from_month, $from_day, $from_year) = explode("-", $from);
  	list($to_month, $to_day, $to_year) = explode("-", $to);
         
  	$from_date = mktime(0,0,0,$from_month,$from_day,$from_year);
  	$to_date = mktime(0,0,0,$to_month,$to_day,$to_year);
         
  	$days = ($to_date - $from_date)/86400;
  
	/* Adicionado o ceil($days) para garantir que o resultado seja sempre um número inteiro */

  	return ceil($days);
	}  

function GetMainFolder()
	{
	return $GLOBALS["path_aplicacao"]; // Definido em ..include/config.php
	//$LocalMainFolder = explode("/",$_SERVER['REQUEST_URI']);	
	//return $_SERVER['DOCUMENT_ROOT'] . '/' . $LocalMainFolder[1];
	}
// --------------------------------------------------------------------------------------
// Função usada para fazer updates de subredes...
// A variável p_origem poderá conter "Agente" ou "Pagina" para o tratamento de variáveis $_SESSION
// --------------------------------------------------------------------------------------
function update_subredes($p_id_ip_rede, $p_origem, $p_objetos, $p_id_local) 
{
$MainFolder		= GetMainFolder();

// Função para replicação do conteúdo do REPOSITÓRIO nos servidores de UPDATES das redes cadastradas.
if ($handle = opendir($MainFolder . '/repositorio')) 
	{
	$v_nomes_arquivos_REP = array();
	$v_versoes_arquivos_REP = array();	
	if (file_exists($MainFolder . '/repositorio/versoes_agentes.ini'))
		{
		$v_array_versoes_agentes = parse_ini_file($MainFolder . '/repositorio/versoes_agentes.ini');
		}

	while (false !== ($v_arquivo = readdir($handle))) 
		{
		if ((strpos($p_objetos,$v_arquivo) > 0 || $p_objetos=='*') and substr($v_arquivo,0,1) != ".") 				
			{
			// Armazeno o nome do arquivo
			array_push($v_nomes_arquivos_REP, $v_arquivo);

			$caminho_arquivo = $MainFolder . '/repositorio/' . $v_arquivo;

			if (isset($v_array_versoes_agentes) && $versao_agente = $v_array_versoes_agentes[$v_arquivo])			
				{				
				// A string 0103 será concatenada em virtude da inserção da informação de versão nos agentes
				// até então era usada a data do arquivo como versão, a string 0103 fará com que o Gerente de Coletas 
				// entenda que as versões atuais são maiores, ou seja, a versão 20100103 é maior que 20051201
				array_push($v_versoes_arquivos_REP, str_replace('.','',$versao_agente) . '0103');				
				}
			else
				{
				array_push($v_versoes_arquivos_REP, strftime("%Y%m%d%H%M", filemtime($caminho_arquivo)));
				}
			}
		}

	$query_SEL_REDES= "	SELECT 	*
						FROM	redes_versoes_modulos
						WHERE 	id_ip_rede = '" . $p_id_ip_rede . "' AND
						        id_local = ".$p_id_local;
	conecta_bd_cacic();
	
	$v_nomes_arquivos_FTP = array();
	$v_versoes_arquivos_FTP = array();				
	
	$Result_SEL_REDES = mysql_query($query_SEL_REDES);
	$v_achei = 0;
	while ($row = mysql_fetch_array($Result_SEL_REDES))
		{
		array_push($v_nomes_arquivos_FTP, trim($row['nm_modulo']));
		array_push($v_versoes_arquivos_FTP, trim($row['te_versao_modulo']));										
		for ($cnt_arquivos_REP = 0; $cnt_arquivos_REP < count($v_nomes_arquivos_REP); $cnt_arquivos_REP++)
			{
			if (trim($v_nomes_arquivos_REP[$cnt_arquivos_REP]) == trim($row['nm_modulo']) &&
				trim($v_versoes_arquivos_REP[$cnt_arquivos_REP]) == trim($row['te_versao_modulo']))
				{
				$v_achei ++;
				$cnt_arquivos_REP = count($v_nomes_arquivos_REP);
				}
			}
		}

	if ($v_achei < count($v_nomes_arquivos_REP))
		{	
		$query_SEL_REDES= "	SELECT 		re.id_ip_rede,
										re.nm_rede,
										re.te_serv_updates,
										re.nu_porta_serv_updates, 
										re.te_path_serv_updates, 
										re.nm_usuario_login_serv_updates_gerente,								
										re.te_senha_login_serv_updates_gerente,
										re.id_local								
							FROM		redes re 
							WHERE 		re.id_ip_rede = '" . $p_id_ip_rede . "' AND
										re.id_local = ".$p_id_local;
		conecta_bd_cacic();
		$Result_SEL_REDES = mysql_query($query_SEL_REDES);
	
		$row = mysql_fetch_array($Result_SEL_REDES);
	
		if (trim($row['te_serv_updates'] . 
				 $row['nu_porta_serv_updates'] .
				 $row['te_path_serv_updates'] .
				 $row['nm_usuario_login_serv_updates_gerente'] .
				 $row['te_senha_login_serv_updates_gerente'] .
				 $row['nu_porta_serv_updates']) != '')
				{
				$v_conta_objetos_enviados 			= 0;
				$v_conta_objetos_nao_enviados 		= 0;			
				$v_conta_objetos_atualizados 		= 0;
				$v_conta_objetos_nao_atualizados 	= 0;			
				$v_conta_objetos_diferentes			= 0;
				$v_conta_objetos_inexistentes		= 0;
				$v_array_objetos_enviados 			= array();			
				$v_array_objetos_nao_enviados 		= array();						
				$v_array_objetos_atualizados 		= array();
				$v_array_objetos_nao_atualizados	= array();
				$v_efetua_conexao_ftp 				= -1;	
				$v_conexao_ftp 						= '';
				$v_efetua_conexao_ftp = -1;												

				if (TentaPing($row['te_serv_updates']))
					{
					$v_efetua_conexao_ftp = 0 ;
					$v_conexao_ftp = conecta_ftp($row['te_serv_updates'],
												 $row['nm_usuario_login_serv_updates_gerente'],
												 $row['te_senha_login_serv_updates_gerente'],
												 $row['nu_porta_serv_updates']
												);
					}
	
				if ($v_conexao_ftp)
					{
					if ($p_origem == 'Pagina')
						{
						$_SESSION['v_tripa_servidores_updates'] .= trim($row['te_serv_updates']);
						}
					
					$v_efetua_conexao_ftp = 1;
					for ($cnt_nomes_arquivos_REP = 0; $cnt_nomes_arquivos_REP < count($v_nomes_arquivos_REP); $cnt_nomes_arquivos_REP++) 
						{
						$v_achei = 0;
						for ($cnt_nomes_arquivos_FTP = 0; $cnt_nomes_arquivos_FTP < count($v_nomes_arquivos_FTP); $cnt_nomes_arquivos_FTP++)
							{
							if ($v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP] == $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP])
								{
								$v_achei = 1;
								if ($v_versoes_arquivos_FTP[$cnt_nomes_arquivos_FTP] <> $v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP])
									{
									$v_conta_objetos_diferentes ++;
									@ftp_chdir($v_conexao_ftp,$row['te_path_serv_updates'].'/'.$v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP]);
									
									@ftp_delete($v_conexao_ftp,$row['te_path_serv_updates'].'/'.$v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP]);
									
									if (@ftp_put($v_conexao_ftp,
												$row['te_path_serv_updates'] . '/' . $v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP],
												$MainFolder . '/repositorio/' . $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],
												FTP_BINARY))
										{
										array_push($v_array_objetos_atualizados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);
										atualiza_red_ver_mod($row['id_ip_rede'],$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP],$row['id_local']);
										$v_conta_objetos_atualizados ++;
										}
									else
										{
										array_push($v_array_objetos_nao_atualizados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);											
										$v_conta_objetos_nao_atualizados ++;
										}	
									}
								$cnt_nomes_arquivos_FTP = count($v_nomes_arquivos_FTP);
								}										
							}
		
						if ($v_achei == 0)
							{
							$v_conta_objetos_inexistentes ++;
//									atualiza_red_ver_mod($row['id_ip_rede'],$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP]);
								$v_conta_objetos_enviados ++;
							if (@ftp_put($v_conexao_ftp,
										$row['te_path_serv_updates'] . '/' . $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],
										$MainFolder . '/repositorio/' . $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],
										FTP_BINARY))
								{
								array_push($v_array_objetos_enviados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);
								atualiza_red_ver_mod($row['id_ip_rede'],$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP],$row['id_local']);
								$v_conta_objetos_enviados ++;
								}
							else
								{
								array_push($v_array_objetos_nao_enviados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);
								$v_conta_objetos_nao_enviados ++;
								$v_achei = 0;
								}									
							}												
						}	
						if (($v_conta_objetos_diferentes   > 0 and $v_conta_objetos_diferentes   == $v_conta_objetos_atualizados) ||
							($v_conta_objetos_inexistentes > 0 and $v_conta_objetos_inexistentes == $v_conta_objetos_enviados)    ||
							($v_conta_objetos_diferentes  == 0 and $v_conta_objetos_inexistentes == 0))
							{
							Marca_Atualizado($p_id_ip_rede,$p_id_local);
							}
						ftp_quit($v_conexao_ftp);							
					}
				else
					{
	
					if (trim($row['te_serv_updates'])						== 	'' || 
						trim($row['nu_porta_serv_updates'])					== 	'' ||
						trim($row['te_path_serv_updates'])  				== 	'' ||
						trim($row['nm_usuario_login_serv_updates_gerente']) == 	'' ||
						trim($row['te_senha_login_serv_updates_gerente'])	==	'' ||
						trim($row['nu_porta_serv_updates'])					==	'')
						{
	
						$_SESSION['v_status_conexao'] = 'NC'; // Não Configurado
						}
					else
						{
						$_SESSION['v_status_conexao'] = 'OFF'; // Off Line
						}
					}
	
			if ($p_origem == 'Pagina')
				{
				$_SESSION['v_conta_objetos_enviados'] 			= 	$v_conta_objetos_enviados;
				$_SESSION['v_conta_objetos_nao_enviados']		= 	$v_conta_objetos_nao_enviados;
				$_SESSION['v_conta_objetos_atualizados']		=	$v_conta_objetos_atualizados;
				$_SESSION['v_conta_objetos_nao_atualizados']	= 	$v_conta_objetos_nao_atualizados;
				$_SESSION['v_tripa_objetos_enviados'] 			= 	implode('#',$v_array_objetos_enviados);
				$_SESSION['v_tripa_objetos_nao_enviados'] 		= 	implode('#',$v_array_objetos_nao_enviados);
				$_SESSION['v_tripa_objetos_atualizados'] 		= 	implode('#',$v_array_objetos_atualizados);
				$_SESSION['v_tripa_objetos_nao_atualizados'] 	= 	implode('#',$v_array_objetos_nao_atualizados);
				$_SESSION['v_efetua_conexao_ftp'] 				= 	$v_efetua_conexao_ftp;
				$_SESSION['v_conexao_ftp'] 						= 	$v_conexao_ftp;
				}
			}
		}
	else
		{
		$_SESSION['v_efetua_conexao_ftp'] = 1;
		}
	}
}


// --------------------------------------------------------------------------------------
function diferenca_em_horas($p_dt_hr_ult_acesso)
	{
	$date_time=strtotime(date("Y/m/d H:i", strtotime($p_dt_hr_ult_acesso)));	
	$date = getdate(); 
	$date_time_now = $date['year']."-".$date['mon']."-".$date['mday']." ".$date['hours'];
	$date_time_now = strtotime($date_time_now);
	return (($date_time_now - $date_time)/3600); 
	}

// --------------------------------------------------------------------------------------
function Marca_Atualizado($p_id_ip_rede,$p_id_local)
	{
	$query_UPD = "	UPDATE 	redes 
							set dt_verifica_updates = NOW() 
					WHERE 	id_ip_rede = '".$p_id_ip_rede."' AND
							id_local = ".$p_id_local;		
	conecta_bd_cacic();									
	$result_UPD = mysql_query($query_UPD);
	}

// --------------------------------------------------------------------------------------
// Função usada para teste de pings em IP específico...
// --------------------------------------------------------------------------------------
function TentaPing($link)
	{
	/*
	$v_comando_ping	= "ping ";
	$v_opcao_pings 	= ' -n';
	$v_opcao_tempo 	= ' -w';			
	$v_pings 		= 1;
	$v_tempo 		= 1;			

	if (PHP_OS == "Linux" || PHP_OS == "Unix")
		{
		$v_opcao_pings = ' -c';								
		$v_opcao_tempo = ' -i';												
		}
	$v_tentativas = 2;				
	for ($cnt_tentativas = 0; $cnt_tentativas <= $v_tentativas; $cnt_tentativas++)			
		{	
//		$v_pings ++;	
		$comando = $v_comando_ping . $v_opcao_pings . ' ' . $v_pings . ' ' .$v_opcao_tempo . ' ' . $v_tempo . ' ' . $p_te_serv_updates;
		$v_array_resultado = '';

		exec($comando, $v_array_resultado);			

		$v_efetua_conexao_ftp = 0;												
		for ($cnt_array = 0; $cnt_array < count($v_array_resultado); $cnt_array++)
			{
			if (ereg("bytes from",strtolower($v_array_resultado[$cnt_array])) || ereg("resposta de",strtolower($v_array_resultado[$cnt_array])))
				{
				$v_efetua_conexao_ftp 	= 1;
				$cnt_array 				= count($v_array_resultado);									
				$cnt_tentativas 		= $v_tentativas;
				}
			}
		}
		return $v_efetua_conexao_ftp;	
	*/

	$link=correcturl($link);
	return chkuri($link);	
	}

function chkuri($link)
	{
    $churl = @fopen("http://".$link,'r');
    return(!$churl?false:true); 
	}

function correcturl($link)
	{
    return str_replace("http://","",strtolower($link));
	}

// -----------------------------------------------------------------------
// Função para gravação de relacionamento entre redes e perfis_aplicativos
// -----------------------------------------------------------------------
function seta_perfis_rede($p_id_local, $p_id_ip_rede, $p_perfis)
	{
	$v_perfis = explode('__',$p_perfis);
	if (count($v_perfis))
		{
		conecta_bd_cacic();
		
		for ($cnt_perfis = 0; $cnt_perfis <= count($v_perfis); $cnt_perfis++)			
			{
			$query_INS	= "INSERT  
						   INTO 		aplicativos_redes (id_local,
						   								   id_ip_rede,
														   id_aplicativo)
						   values		(".$p_id_local.",
										 '".$p_id_ip_rede."',
										 ".$v_perfis[$cnt_perfis].")";
			$result_INS = mysql_query($query_INS);
			
			}
		}
	}
	
// Função usada para compactar dados de tabelas para atualização de bases...
// -------------------------------------------------------------------------
function compacta_dados_tabelas($p_dbname, $p_data_hora_inicio, $p_arquivo_saida)
	{
	conecta_bd_cacic();	
	$send = bzopen($p_arquivo_saida,"w"); //necessário ativar módulo componente bzip2

	$res = mysql_list_tables($p_dbname) or die(mysql_error()); // Pega a lista de todas as tabelas
	while ($row = mysql_fetch_row($res))
		{
		$table = $row[0]; // cada uma das tabelas
		$v_fields = mysql_list_fields($p_dbname,$table);
		
		if (mysql_field_name($v_fields,0) == 'cs_enviado')
			{			
			$res2 = mysql_query("SHOW CREATE TABLE $table");
			while ( $lin = mysql_fetch_row($res2))
				{ 
				// Para cada tabela		
				$res3 = mysql_query("SELECT * FROM $table");
				if ($sql) // Caso a variável tenha conteúdo... Caso contrário, mais abaixo será atribuído.
					{
					bzwrite($send,"#");
					}					
				while($r = mysql_fetch_row($res3))
					{ 
	
					// Dump de todos os dados das tabelas
					# Evitar fechamento de aspas simples!!
					$r = str_replace("'","\'",$r);
						
					// Na minha base tem aspas duplas e tive que acrescentar essa linha:
					$r = str_replace('"','\"',$r);
	
					$sql = "#INSERT INTO $table VALUES ('";
					$sql .= implode("','",$r);
					$sql .= "')";
				
					bzwrite($send,$sql);
					}
				}
			}			
		}
		bzclose($send);
	}

function url_encode($text)
	{
   	$text = urlencode($text);
   	if(!strpos($text,"%C3"))
		for($i=129;$i<255;$i++)
	   		{
           	$in = "%".int2hex($i);
           	$out = "%C3%".int2hex($i-64);
           	$text = str_replace($in,$out,$text);
       		}
	return $text;
	}
function url_decode($text)
	{
   	if(!strpos($text,"%C3"))
       for($i=129;$i<255;$i++)
	   		{
           	$in = "%".int2hex($i);
           	$out = "%C3%".int2hex($i-64);
           	$text = str_replace($in,$out,$text);
       		}
	return urldecode($text);
	}	
function int2hex($intega)
	{
   	$Ziffer = "0123456789ABCDEF";
	return $Ziffer[($intega%256)/16].$Ziffer[$intega%16];
	}	
//==================================================
?>
