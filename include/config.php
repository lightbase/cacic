<?
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
 
// -------------------------------------------------------------------------------------------------------
// Altere apenas as vari�veis abaixo, caso seja necess�rio.
// -------------------------------------------------------------------------------------------------------

$nome_bd		  	= "cacic";  // Essa vari�vel n�o dever� ser alterada.
$ip_servidor 	  	= "localhost";  // Altere, caso esteja acessando o bd em outro servidor.
$porta		      	= "3306";   // Porta tcp do MySQL. Provavelmente n�o dever� ser alterada.
$usuario_bd 	  	= "UserName";  // Usu�rio usado para acesso ao bd do cacic. 
$senha_usuario_bd   = "Password";
$path_aplicacao		= "/var/www/cacic2"; // Valor utilizado principalmente pela fun��o de Update de SubRedes...

// ATEN��O:
// As chaves abaixo, at� a presente vers�o, s�o assim�ticas, ou seja, 
// caso seja necess�rio alter�-las, os agentes "Cacic2.exe", "ChkCacic.exe" e  
// "ChkSis.exe" tamb�m dever�o ser alterados, 
// via programa��o Delphi 7.
// -------------------------------------------------------------------------------------------------
$key 				= 'CacicBrasil';
$iv 				= 'abcdefghijklmnop';
// -------------------------------------------------------------------------------------------------------
?>