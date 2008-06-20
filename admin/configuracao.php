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

/**
 * @version $Id: configuracao.php 2008-06-18 22:10 harpiain $
 * @package CACIC-Admin
 * @subpackage AdminSetup
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

session_start();

/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');

// Ativa modo de depuração (mostra todas as mensagens na página)
// $cacicDebug = true;

require_once('../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...

/*
 * Uma classe ADO (simples) criada para o instalador (posteriormente criar uma específica ou usar PDO)
 */
require_once(CACIC_PATH.CACIC_DS.'instalador'.CACIC_DS.'classes'.CACIC_DS.'install.ado.php');

/*
 * componente (objeto) para manipulacao de banco de dados
 */
 // colocar em library depois
$oADO = new ADO();
$oADO->conecta( $ip_servidor, $usuario_bd, $senha_usuario_bd, $nome_bd );

require_once('configuracao_padrao.class.php');

$oCacicSetup = new Configuracao_Padrao();
$oCacicSetup->setup();
$oCacicSetup->run();
?>