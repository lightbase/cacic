<?php 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
// Essa coleta n�o � opcional, ou seja, o administrador n�o tem como desabilit�-la.
// Por isso foi necess�rio cri�-la de forma independente do script set_software.php.

require_once('../include/common_top.php');

conecta_bd_cacic();

$query = "	UPDATE 	computadores 
			SET		te_versao_cacic   = '" . DeCrypt($_POST['te_versao_cacic'],$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "'  
			WHERE 	id_computador     = " . $arrDadosComputador['id_computador'];
$result = mysql_query($query);

$strXML_Values .= '<STATUS>' . EnCrypt('S', $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</STATUS>';		
require_once('../include/common_bottom.php');
?>