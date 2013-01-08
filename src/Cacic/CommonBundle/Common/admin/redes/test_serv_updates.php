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
session_start();
require_once('../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
/*
Valores na Chamada:
------------------
pStrServName		String		Nome/IP do servidor
pStrUserName		String		Nome do usuário para login
pStrUserPass		String		Senha do usuário para login
pStrServPath		String		Diretório para o ChDir
pStrNuPort			String		Numero de porta do servidor
*/
require_once('../../include/ftp_check_and_send.php');	

$strResult = checkAndSend('versions_and_hashes.ini',
						  CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini',
						  $_GET['pStrServName'],
						  $_GET['pStrServPath'],							  
						  $_GET['pStrUserName'],
						  $_GET['pStrUserPass'],
						  $_GET['pStrNuPort']);
echo $strResult;		
?>