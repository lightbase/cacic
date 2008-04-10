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
$v_id_ip_estacao = $_SERVER["REMOTE_ADDR"];
conecta_bd_cacic();		
$queryINS 	= "INSERT INTO insucessos_instalacao (te_ip, te_so, id_usuario, cs_indicador, dt_datahora)
			 	VALUES ('" . $v_id_ip_estacao . "', '" . $_POST['te_so'] . "','" . $_POST['id_usuario'] . "','" . $_POST['cs_indicador'] . "', NOW())";
$result 	= mysql_query($queryINS);
?>	