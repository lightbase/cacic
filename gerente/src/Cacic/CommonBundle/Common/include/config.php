<?php
    /*
     * Arquivo de configuracoes para o CACIC
     * @version $Id: install.ajax.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
     * @package Cacic
     * @license GNU/GPL, see LICENSE.php
     */

    /*
     * Nome do banco de dados
     */
    $nome_bd = "cacic3_2802";
    
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
    $usuario_bd = "cacic";
    
    /*
     * Senha do usuario de conexao do servidor de banco de dados
     */
    $senha_usuario_bd = "cacic";
    
    /*
     * URL da aplicacao "CACIC"
     */
    $url_aplicacao = "http://10.71.0.205/cacic3/";

    /*
     * CACIC application language
     */
    $cacic_language = "pt_BR";

    /*
     * CACIC application theme
     */
    $cacic_theme = "default";

    /*
     * Caminho fisico da aplicacao "CACIC"
     */
    $path_aplicacao = "/var/www/cacic3/";

    /*
     * Atencao:
     * As chaves abaixo, ate a presente versao, sao assimeticas, ou seja, 
     * caso seja necessario altera-las, os agentes "CacicXXX.exe", "InstallCacic.exe" e   
     * "ChkSis.exe" tambem deverao ser alterados, via programacao Delphi 7.
     */
    $key = "CacicBrasil";
    $iv = "abcdefghijklmnop";
?>