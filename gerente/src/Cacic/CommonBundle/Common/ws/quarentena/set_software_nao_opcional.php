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
// Essa coleta não é opcional, ou seja, o administrador não tem como desabilitá-la.
// Por isso foi necessário criá-la de forma independente do script set_software.php.

require_once('../include/common_top.php');

conecta_bd_cacic();

$query = "	UPDATE 	computadores 
			SET		te_versao_cacic   = '" . DeCrypt($key,$iv,$_POST['te_versao_cacic'],$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "'  
			WHERE 	id_computador     = " . $arrDadosComputador['id_computador'];
$result = mysql_query($query);

$strXML_Values .= '<STATUS>' . EnCrypt($key,$iv,'S', $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</STATUS>';		
require_once('../include/common_bottom.php');
?>