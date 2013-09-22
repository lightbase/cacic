<?php
    /*
     * Arquivo de configuracoes para o CACIC
     * @version $Id: config.php 2007-02-08 22:20 harpiain $
     * @package Cacic
     * @license GNU/GPL, see LICENSE.php
     */

    /*
     * Nome do banco de dados
     */
    $nome_bd = "cacic";
    
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
    $usuario_bd = "root";
    
    /*
     * Senha do usuario de conexao do servidor de banco de dados
     */
    $senha_usuario_bd = "";
    
    /*
     * URL da aplicacao "CACIC"
     */
    $url_aplicacao = "http://10.209.8.84/cacic/";

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
    $path_aplicacao = "/var/www/cacic260b2/";

   /*
     * Caminho fisico para componentes de instalação, coleta avulsa de informações patrimoniais e cliente de Suporte Remoto
     */
    $path_relativo_repositorio_instalacao  = "repositorio/install";

    /*
     * Atencao:
     * As chaves abaixo, ate a presente versao, sao assimeticas, ou seja, 
     * caso seja necessario altera-las, os agentes "Cacic2.exe", "ChkCacic.exe" e   
     * "ChkSis.exe" tambem deverao ser alterados, via programacao Delphi 7.
     */
    $key = "CacicBrasil";
    $iv = "abcdefghijklmnop";
?>