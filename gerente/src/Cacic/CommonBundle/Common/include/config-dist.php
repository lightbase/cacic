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
 
// -------------------------------------------------------------------------------------------------------
// Altere apenas as variáveis abaixo, caso seja necessário.
// -------------------------------------------------------------------------------------------------------

    /*
     * Nome do banco de dados
     */
    $nome_bd = "cacic3";
    
    /*
     * Nome/IP do servidor de banco de dados
     */
    $ip_servidor = "localhost";
    
    /*
     * Porta no servidor de banco de dados
     */
    $porta = "3306";
    
    /*
     * Usuario de conexao do servidor de banco de dados
     */
    $usuario_bd = "cacic_db_user";
    
    /*
     * Senha do usuario de conexao do servidor de banco de dados
     */
    $senha_usuario_bd = "cacic_db_user_pass";
    
    /*
     * URL da aplicacao "CACIC"
     */
    $url_aplicacao = "http://localhost/";

    /*
     * Caminho fisico da aplicacao "CACIC"
     * Valor utilizado principalmente pela função de Update de SubRedes...
     */
    $path_aplicacao		= "/var/www/default/cacic3";

    /*
     * CACIC application language
     */
    $cacic_language = 'en_US';

    /*
     * CACIC application theme
     */
    $cacic_theme = 'default';

// ATENÇÃO:
// As chaves abaixo, até a presente versão, são assiméticas, ou seja, 
// caso seja necessário alterá-las, os agentes "CacicXXX.exe", "InstallCacic.exe" e  
// "ChkSis.exe" também deverão ser alterados, 
// via programação Delphi 7.
// -------------------------------------------------------------------------------------------------
$key 				= 'CacicBrasil';
$iv 				= 'abcdefghijklmnop';
// -------------------------------------------------------------------------------------------------------
?>