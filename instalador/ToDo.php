<?php
/**
 * @version $Id: index.php 2007-02-08 22:20 harpiain $
 * @package Cacic-Installer
 * @subpackage Instalador
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2007 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC-Install is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );
?>

Necessidades para o instalador:

    * Deve atualizar base de dados de versões anteriores.
    * Deve realizar cópia de segurança da base de dados existente.
    * Deve verificar caracteres "especiais" em campos que não podem conter.
OK  * Excluir solicitação de caminhos (url e físico)
    * Criar instalação multi-idioma
      - deve mostrar licença traduzida no idioma selecionado
OK  * Criar seletor de idiomas (deve ler os idiomas disponíveis em "language")
OK  * Gravar idioma selecionado no config.php
    * Criar opção de atualização de versões anteriores
    * desmembrar "templates" (navbar) dos passos da instalação
OK  * verificar seriviço FTP
      - realizar teste de conexão
    * validar formulários