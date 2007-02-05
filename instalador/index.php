<?php
/*
 Copyright (C) Thomaz de Oliveira dos Reis - thor27 EM gmail PONTO com

Este arquivo é parte do programa CACIC. Você pode redistribuí-lo e/ou modificá-lo sob os termos da versão 2 da Licença Pública Geral GNU, conforme publicada pela Free Software Foundation.

Este programa é distribuído na expectativa de ser útil, mas SEM QUALQUER GARANTIA; sem mesmo a garantia implícita de COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais detalhes. Você deve ter recebido uma cópia da Licença Pública Geral GNU junto com este programa; se não, escreva para a Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
*/

include_once 'install.inc.php';
$ARQ_CONFIG='../include/config.php';
$ARQ_SQL="DB_Cacic_01022007.sql";
$INDEX="../index.html";

// ATENÇÃO:
// As chaves abaixo, até presente versão, são assiméicas, ou seja, 
// caso seja necessário alterá-las, os agentes "Cacic2.exe", "ChkCacic.exe" e  
// "ChkSis.exe" também deverão ser alterados, 
// via programação Delphi 7.
$KEY = 'CacicBrasil';
$IV = 'abcdefghijklmnop';

// Se já existe arquivo de configuração, deve-se então iniciar a página inicial...
if (file_exists($ARQ_CONFIG)) { 
    header("Location: $INDEX");
    die;
}

session_name('CacicInstall');
@session_start();

if (! isset($_SESSION['CACICCONFIG'])) {
    $_SESSION['CACICCONFIG'] = array();
}

$CONFIG = &$_SESSION['CONFIG'];
// Resetando as configurações
if (!isset($_POST['submit'])) {
	$CONFIG['arq_config']=$ARQ_CONFIG;
	$CONFIG['arq_sql']=$ARQ_SQL;
	$CONFIG['fase']=0;
	$CONFIG['db_hostname']='localhost';
	$CONFIG['db_user']='root';
	$CONFIG['db_pass']='';
	$CONFIG['db_db']='cacic';
	$CONFIG["cc_login"] = "admin"; 
	$CONFIG["cc_pass"] = "";
	$CONFIG["cc_nome"] = "";
	$CONFIG["cc_mail"] = "";
	$CONFIG["cc_tel"] = "";
	$CONFIG["cc_local"] = "";
	$CONFIG["cc_sigla"] ="";
	$CONFIG["cc_obs"] = "";
	$CONFIG["cc_pass_con"] = "";
	$CONFIG["cc_key"] = $KEY;
	$CONFIG["cc_iv"] = $IV;

}

$v_versao = '2.2.2';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Configurador da Instalação do Gerente CACIC.</title>
   <style type="text/css" title="currentStyle">
		@import "instalador.css";
</style>

<div id="topo">
<SCRIPT language=JavaScript>
<!--
function scrollit(seed) 
	{
	var msg="*** CACIC - Configurador Automático e Coletor de Informações Computacionais ***";
	var out = " ";
	var c = 1;
	if (seed > 100) 
		{
		seed--;
		cmd="scrollit("+seed+")";
		timerTwo=window.setTimeout(cmd,100);
		}
	else if (seed <= 100 && seed > 0) 
		{
		for (c=0 ; c < seed ; c++) 
			{
			out+=" ";
			}
		out+=msg;
		seed--;
		window.status=out;
		cmd="scrollit("+seed+")";
		timerTwo=window.setTimeout(cmd,100);
		}
	else if (seed <= 0) 
		{
		if (-seed < msg.length) 
			{
			out+=msg.substring(-seed,msg.length);
			seed--;
			window.status=out;
			cmd="scrollit("+seed+")";
			timerTwo=window.setTimeout(cmd,100);
			}
		else 
			{
			window.status=" ";
			timerTwo=window.setTimeout("scrollit(100)",75);
			}
		}
	}
//scrollit(100);
</SCRIPT>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="5">&nbsp;</td>
          <td><strong><font size="5" face="Verdana, Arial, Helvetica, sans-serif"><img src="../imgs/cacic_logo.png" width="50" height="50"></font></strong></td>
          <td><table width="75%" border="0">
              <tr>
                <td><img src="../imgs/cacic_tit.gif"></td>
              </tr>
              <tr>
                <td><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">v.<? echo $v_versao;?></font></div></td>
              </tr>
            </table>
            
          </td>
          <td><table border="0" align="right" cellpadding="0" cellspacing="0">
              <tr> 
                <td><img src="../imgs/cacic_ext.gif" align="bottom"></td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><div align="right"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
		Instalador Gerente CACIC
		</font></b></div></td>
                <td>&nbsp;&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="2" background="imgs/linha_h.gif"></td>
  </tr>
</table>
	Instalação do CACIC <br /><br />
	<form name="installform" method="post" action="<?php echo $PHP_SELF;?>">
	<?
		switch ( $CONFIG['fase'] ) {
			case 0:  $CONFIG=bemvindo($CONFIG);
				 break;
			case 1:  $CONFIG=teste_ambiente($CONFIG);
				 break;
			case 2:  $CONFIG=configura_conexao($CONFIG);
				 break;
			case 3:  $CONFIG=cria_tabelas($CONFIG);
				 break;
			case 4:  $CONFIG=configura_cacic($CONFIG);
				 break;
			case 5:	 $CONFIG=salva_configuracao($CONFIG);
				 break;
			default: ?><meta http-equiv="REFRESH" content="0;url=<?echo $INDEX?>"><?
			    	 die;
		}

	?>
<div>

	<br><br>
        	<input type="submit" value="<?php echo $CONFIG['nomebot'] ?>" name="submit">
	</form>



<?
	function bemvindo($CONFIG) {
		echo 'Bem-vindo ao instalador do CACIC. Nos próximos passos serão feitas algumas perguntas básicas para que o CACIC possa Funcionar.<br /><b>IMPORTANTE: <i>Este instalador está em fase experimental e ainda não funciona em um ambiente de atualização.</i></b> ';

		$CONFIG['nomebot']='Iniciar Instalação';
    		$CONFIG['fase']=$CONFIG['fase']+1;
		return $CONFIG;
	}

	function teste_ambiente($CONFIG) {
		//Testa Versão do PHP, gd, mysql, mcrypt, dev

		if (version_compare(phpversion(), '4.0', '>')) {
			echo 'Versão do PHP (> 4.0): ',phpversion(),' <img src="apply.png"><br />';
		} else echo 'Versão do PHP (> 4.0): ',phpversion(),' Inferior a recomendada<br />';

		$TESTE = 0;
		$pasta=dirname($CONFIG['arq_config']);
		$path="$pasta/tmp";
		
		if (extension_loaded("gd")) {
			echo 'Extensão Gd <img src="apply.png"> <br />';
			$TESTE = $TESTE +1;
		} else echo 'Extensão Gd <img src="cancel.png"> <br />';

		if (extension_loaded("mysql")) {
			echo 'Extensão MySQL <img src="apply.png"> <br />';
			$TESTE = $TESTE +1;
		} else echo 'Extensão MySQL <img src="cancel.png"> <br />';

		if (extension_loaded("mcrypt")) {
			echo 'Extensão mcrypt <img src="apply.png"> <br />';
			$TESTE = $TESTE +1; 
		} else echo 'Extensão mcrypt <img src="cancel.png"> <br />';
		
		if ($f = @fopen($path,"wb")) {
		        fclose($f);
		        unlink($path);
			echo 'Permissão de escrita em $pasta. <img src="apply.png"><br /> <b>IMPORTANTE: Ao termino da instalação, retire a permissão de escrita em $pasta, por motivos de segurança.</b>';
			$CONFIG['gerar_tela']=0;
		} else {
			echo "Permissão de escrita em $pasta Não autorizado.";
			echo '<img src="cancel.png"><br>'; 
			echo "O conteúdo do arquivo 'config.php' será gerado na tela.";
			$CONFIG['gerar_tela']=1; 
		}
		
		if ($TESTE == 3 ) {
			$CONFIG['nomebot']='Avançar';
    			$CONFIG['fase']=$CONFIG['fase']+1;
			return $CONFIG;
		} else {
			$CONFIG['nomebot']='Atualizar';
			return $CONFIG;
		}	
	}

	function configura_conexao($CONFIG) {
		echo 'Configurando a conexão com o banco de dados';
		?><br /><br />
			<table style="text-align: left; width: 300px; height: 120px;"
 border="0" cellpadding="0" cellspacing="0">
  			<tbody>
    			<tr>
      			<td style="width: 200px;">Nome do host:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="50" value="<?php echo $CONFIG['db_hostname'] ?>" name="HostName"></td>
    			</tr>
    			<tr>
      			<td style="width: 200px;">Usuário do MySQL:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="50" value="<?php echo $CONFIG['db_user'] ?>" name="User"></td>
    			</tr>
    			<tr>
      			<td style="width: 200px;">Senha do usuário:</td>
      			<td style="width: 92px;"><input type="password" size="12" maxlength="50" value="<?php echo $CONFIG['db_pass'] ?>" name="Pass"></td>
    			</tr>
    			<tr>
      			<td style="width: 200px;">Nome do Banco:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="50" value="<?php echo $CONFIG['db_db'] ?>" name="DB"></td>
    			</tr>
    			</tbody>
			</table>

		<?
		
		$CONFIG['nomebot']='Avançar';
		$CONFIG['fase']=$CONFIG['fase']+1;
		return $CONFIG;
	}

	function cria_tabelas($CONFIG){
		$ARQ_SQL=$CONFIG['arq_sql'];

		//Guarda as informações do Form anterior
		$CONFIG['db_hostname']=$_POST["HostName"];	
		$CONFIG['db_user']=$_POST["User"];
		$CONFIG['db_pass']=$_POST["Pass"];
		$CONFIG['db_db']=$_POST["DB"];
		
		//Conecta no mySQL
		echo "Conectando no mySQL: ";
		flush();
		if (!$ligacao = mysql_connect($CONFIG['db_hostname'], $CONFIG['db_user'], $CONFIG['db_pass']) ) {
			echo '<img src="cancel.png">'; 
			echo 'Erro <br /><br />Erro de conexão com o MySQL, por favor verifique se as informações estão corretas';
			$CONFIG['nomebot']='Voltar';
			$CONFIG['fase']=$CONFIG['fase']-1;
			return $CONFIG;
		}
	
		//Cria o banco de dados, caso não exista
		///TODO: Verificar a permissão do usuário para criar bancos. Se o banco já existe verificar se é uma atualização.
		echo '<img src="apply.png"><br>'; 
		echo "Criando o Banco de dados, caso não exista: ";
		$dbname=$CONFIG['db_db'];
		if (!mysql_query("create database if not exists $dbname", $ligacao)) {
			echo '<img src="cancel.png"><br>';
			echo 'Erro <br /><br />Erro durante a criação do banco de dados.';
			$CONFIG['nomebot']='Voltar';
			$CONFIG['fase']=$CONFIG['fase']-1;
			return $CONFIG;
		
		}

		//Abre o banco de dados
		echo '<img src="apply.png"><br>'; 
		echo "Abrindo o Banco de dados: ";
		flush();
		if (!mysql_select_db($CONFIG['db_db'], $ligacao)) {
			echo '<img src="cancel.png"><br>'; 
			echo 'O banco de dados ',$CONFIG['db_db'],' não foi encontrado.';
			$CONFIG['nomebot']='Voltar';
			$CONFIG['fase']=$CONFIG['fase']-1;
			return $CONFIG;		
		}

		//Inserindo o .SQL no banco
		echo '<img src="apply.png"><br>'; 
		echo "Inserindo $ARQ_SQL: ";
		flush();
		InstallLoadSql($ARQ_SQL, $ligacao);
		echo '<img src="apply.png"><br>'; 
		echo '<br />Banco e tabelas criadas com sucesso.';
		echo '<img src="apply.png"><br>';
		$CONFIG['nomebot']='Avançar';
		$CONFIG['fase']=$CONFIG['fase']+1;
		return $CONFIG;
	}

	function configura_cacic($CONFIG){
		
		?><br /><br />
			<table style="text-align: left; width: 517px;" border="0"
 			cellpadding="2" cellspacing="2">
  			<tbody>
    			<tr>
      			<td style="width: 407px;">Path da Aplicação:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="255" value=" <?echo dirname(dirname(__FILE__));?>" name="PathAplic"></td>
    			</tr>
    			<tr>
      			<td style="width: 407px; font-weight: bold;">Informações do usuário administrador:</td>
      			<td style="width: 92px;"></td>
    			</tr>
    			<tr>
      			<td style="width: 407px;">Login:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="10" value="<?echo $CONFIG["cc_login"] ?>" name="usr_login"></td>
    			</tr>
    			<tr>
      			<td style="width: 407px;">Senha:</td>
      			<td style="width: 92px;"><input type="password" size="12" maxlength="60" value="<?echo $CONFIG["cc_pass"] ?>" name="usr_pass"></td>
    			</tr>
    			<tr>
      			<td style="width: 407px;">Confirma Senha:</td>
      			<td style="width: 92px;"><input type="password" size="12" maxlength="60" value="<?echo $CONFIG["cc_pass_con"] ?>" name="usr_pass_con"></td>
    			</tr>
    			<tr>
      			<td style="width: 407px;">Nome:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="60" value="<?echo $CONFIG["cc_nome"] ?>" name="usr_nome"></td>
    			</tr>
  			<tr>
      			<td style="width: 407px;">E-Mail:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="100" value="<?echo $CONFIG["cc_mail"] ?>" name="usr_mail"></td>
    			</tr>
    			<tr>
      			<td style="width: 407px;">Telefone:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="100" value="<?echo $CONFIG["cc_tel"] ?>" name="usr_tel"></td>
    			</tr>
    			<tr>
      			<td style="width: 407px; font-weight: bold;">Informações do local do usuário administrador:</td>
      			<td style="width: 92px;"></td>
    			</tr>
    			<tr>
      			<td style="width: 407px;">Nome do local:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="100" value="<?echo $CONFIG["cc_local"] ?>" name="loc_nome"></td>
    			</tr>
    			<tr>
      			<td style="width: 407px;">Sigla do local:</td>
      			<td style="width: 92px;"><input type="text" size="12" maxlength="20" value="<?echo $CONFIG["cc_sigla"] ?>" name="loc_sg"></td>
    			</tr>

			</tbody>
			</table>

	
			Observações:<br /><textarea cols="50" rows="5" maxlength="255" name="loc_obs"><?echo $CONFIG["cc_obs"]?></textarea><br />
		<?

		$CONFIG['nomebot']='Avançar';
		$CONFIG['fase']=$CONFIG['fase']+1;
		return $CONFIG;
	}

	function salva_configuracao($CONFIG){
		
		//====================
		//Atualizando o Banco
		//====================
		$CONFIG["cc_login"] = $_POST["usr_login"]; 
		$CONFIG["cc_pass"] = $_POST["usr_pass"];
		$CONFIG["cc_pass_con"] = $_POST["usr_pass_con"];
		$CONFIG["cc_nome"] = $_POST["usr_nome"];
		$CONFIG["cc_mail"] = $_POST["usr_mail"];
		$CONFIG["cc_tel"] = $_POST["usr_tel"];
		$CONFIG["cc_local"] = $_POST["loc_nome"];
		$CONFIG["cc_sigla"] = $_POST["loc_sg"];
		$CONFIG["cc_obs"] = $_POST["loc_obs"];
		
		//Verifica se as informações estão corretas
		$erro=0;
		if ( $CONFIG["cc_login"] === "" ) {
			$erro = $erro+1;
			echo "O campo login está vazio <br />"; 
		}
		if ( $CONFIG["cc_pass"] === "" ) {
			$erro = $erro+1;
			echo "O campo senha está vazio <br />"; 
		}
		if ( $CONFIG["cc_pass"] != $CONFIG["cc_pass_con"] ) {
			$erro = $erro+1;
			echo "A senha não confere <br />";
		}
		if ( $CONFIG["cc_sigla"] === "" ) {
			$erro = $erro+1;
			echo "O campo sigla do local está vazio <br />"; 
		}
		
		if ($erro > 0) {
			$CONFIG['fase']=$CONFIG['fase']-1;
			$CONFIG['nomebot']='Voltar';
			return $CONFIG;
		}

		$login = $CONFIG["cc_login"]; 
		$pass = $CONFIG["cc_pass"];
		$nome = $CONFIG["cc_nome"];
		$mail = $CONFIG["cc_mail"];
		$tel = $CONFIG["cc_tel"];
		$local = $CONFIG["cc_local"];
		$sigla = $CONFIG["cc_sigla"];
		$obs = $CONFIG["cc_obs"];
		echo "<br />Inserindo informações no banco: ";
		flush();

		//Conecta no mySQL
		if (!$ligacao = mysql_connect($CONFIG['db_hostname'], $CONFIG['db_user'], $CONFIG['db_pass']) ) {
			echo 'Erro <br /><br />Erro de conexão com o MySQL. Verifique sua conexão, e tente novamente.';
			$CONFIG['nomebot']='Tentar novamente';
			$CONFIG['fase']=$CONFIG['fase']-1;
			return $CONFIG;
		}

		//Abre o banco de dados
		if (!mysql_select_db($CONFIG['db_db'], $ligacao)) {
			echo 'Erro ao acessar o banco. Verifique se o banco não foi removido, caso tenha sido, reinicie a instalação. Caso seja apenas um problema de conexão, clique em tentar novamente.';
			$CONFIG['nomebot']='Tentar novamente';
			$CONFIG['fase']=$CONFIG['fase']-1;
			return $CONFIG;		
		}
		
		//Insere as informações do local no banco
		if (!mysql_query("INSERT INTO locais VALUES (0,'$local','$sigla','$obs')", $ligacao)) {
			echo 'Erro <br /><br />Erro durante a inserção no banco. Reinicie a instalação caso não saiba o motivo do erro, caso contrário, corrija o erro e clique tentar novamente.';
			$CONFIG['nomebot']='Tentar novamente';
			$CONFIG['fase']=$CONFIG['fase']-1;
			return $CONFIG;		
		}
		
		//Recupera o código do local
		$result=mysql_query("select id_local from locais where sg_local = '$sigla'", $ligacao);	
		$row = mysql_fetch_assoc($result);
		$cod_local=$row['id_local'];

		//Insere as informações do usuário no banco
		if (!mysql_query("INSERT INTO usuarios VALUES ($cod_local, 0, '$login', '$nome', PASSWORD('$pass'), NOW(), 2,'$mail', '$tel')", $ligacao)) {
			echo 'Erro <br /><br />Erro durante a inserção no banco. Reinicie a instalação caso não saiba o motivo do erro, caso contrário, corrija o erro e clique tentar novamente.';
			$CONFIG['nomebot']='Tentar novamente';
			$CONFIG['fase']=$CONFIG['fase']-1;
			return $CONFIG;		
		}
		echo 'OK <br />';
		
		//====================
		//Criando o config.php
		//====================
		$ARQ_CONFIG=$CONFIG['arq_config'];	
		$ip_servidor=$CONFIG['db_hostname'];	
		$usuario_bd=$CONFIG['db_user'];
		$senha_usuario_bd=$CONFIG['db_pass'];
		$nome_bd=$CONFIG['db_db'];
		$path_aplicacao=$_POST["PathAplic"];
		$KEY = $CONFIG["cc_key"];
		$IV = $CONFIG["cc_iv"];
	
		
		if ( $CONFIG['gerar_tela'] === 1 ) {
			echo "Para finalizar a instalação do CACIC, crie o arquivo ", $CONFIG['arq_config'], " com o seguinte conteúdo: <br />";
			echo "&lt;?<br />//!Ver. 1<br />\$nome_bd = \"$nome_bd\";<br />\$ip_servidor = \"$ip_servidor\";<br />\$porta = \"3306\";<br />\$usuario_bd = \"$usuario_bd\";<br />\$senha_usuario_bd = \"$senha_usuario_bd\";<br />\$path_aplicacao = \"$path_aplicacao\";<br />\$key = '$KEY';<br />\$iv = '$IV';<br />?&gt;";
			echo "<br />então em seguida clique em Finalizar Instalação";
		} else {
			$conteudo="<?\n//!Ver. 1\n\$nome_bd = \"$nome_bd\";\n\$ip_servidor = \"$ip_servidor\";\n\$porta = \"3306\";\n\$usuario_bd = \"$usuario_bd\";\n\$senha_usuario_bd = \"$senha_usuario_bd\";\n\$path_aplicacao = \"$path_aplicacao\";\n\$key = '$KEY';\n\$iv = '$IV';\n?>";
			echo "Salvando as configurações em $ARQ_CONFIG: ";
			flush();
			if (!$arq=fopen($CONFIG['arq_config'], "wb")) {
				echo "Erro<br /><br /> não foi possível criar o arquivo $ARQ_CONFIG Verifique as permissões e espaço em disco.";
				$CONFIG['nomebot']='Voltar';
				$CONFIG['fase']=$CONFIG['fase']-1;
				return $CONFIG;	
			}
		
			if (! fwrite($arq,$conteudo) ) {
				echo "Erro<br /><br /> não foi possível criar o arquivo $ARQ_CONFIG Verifique as permissões e espaço em disco.";
				$CONFIG['nomebot']='Voltar';
				$CONFIG['fase']=$CONFIG['fase']-1;
				return $CONFIG;		
			}

			fclose($arq);
			echo "OK <br /> A instalação do CACIC foi finalizada com sucesso. <br /> Lembre-se de retirar a permissão de escrita em dirname($ARQ_CONFIG).";
		} 
		$CONFIG['nomebot']='Finalizar Instalação';
		$CONFIG['fase']=$CONFIG['fase']+1;
		return $CONFIG;
	
	}

?>

<div id="roda">
</body>
</html>