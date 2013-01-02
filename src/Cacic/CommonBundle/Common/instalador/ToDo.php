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

    * Deve atualizar base de dados de vers�es anteriores.
    * Deve realizar c�pia de seguran�a da base de dados existente.
    * Deve verificar caracteres "especiais" em campos que n�o podem conter.
OK  * Excluir solicita��o de caminhos (url e f�sico)
OK  * Criar instala��o multi-idioma
      - deve mostrar licen�a traduzida no idioma selecionado
OK  * Criar seletor de idiomas (deve ler os idiomas dispon�veis em "language")
OK  * Gravar idioma selecionado no config.php
    * Criar op��o de atualiza��o de vers�es anteriores
    * desmembrar "templates" (navbar) dos passos da instala��o
OK  * verificar serivi�o FTP
OK    - realizar teste de conex�o
    * validar formul�rios