<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario']))
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
   define('CACIC',1);
}

   require_once('../include/config.php');
   require_once('../include/define.php');

   if(!@include_once( TRANSLATOR_PATH.'/Translator.php'))
     die ("<h1>There is a trouble with phpTranslator package. It isn't found.</h1>");

   $_objTranslator = new Translator( CACIC_LANGUAGE, CACIC_PATH."/language/", CACIC_LANGUAGE_STANDARD );
   $_objTranslator->setURLPath(TRANSLATOR_PATH_URL);
   $_objTranslator->initStdLanguages();

?>
<html>
 <head>
           <link type="text/css" rel="stylesheet"
                 href="../bibliotecas/phpTranslator/templates/css/template_css.css" />
           <link type="text/css" rel="stylesheet"
                 href="../bibliotecas/phpTranslator/templates/js/tabs/tabpane.css" />
           <script type="text/javascript"
                 src="../bibliotecas/phpTranslator/templates/js/tabs/tabpane_mini.js">
           </script>
 </head>
 <body>
   <h2><?php echo $_objTranslator->_('kciq_mnt_tradutor');?><h2>
<!--
          <table class="adminlist" width="100%">
               <tr>
                  <td>Idioma a traduzir</td>
                  <td>
                     <select name="translate_lang">
                        <option value="en-us">Inglês (US)</option>
                        <option value="pt-br">Português brasileiro</option>
                        <option value="add_lang">Adicionar tradução</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td>Descrição do idioma</td>
                  <td>
                     <input class="inputbox" type="text" name="mnt_language[lang]" size="50" maxlength="50" />
                  </td>
               </tr>
               <tr>
                  <td>Sigla</td>
                  <td>
                     <input class="inputbox" type="text" name="mnt_language[lang]" value="pt_BR" size="10" maxlength="10" />
                  </td>
               </tr>
               <tr>
                  <td valign="top">Conjunto de caracteres</td>
                  <td valign="top">
                     <input class="inputbox" type="text" name="mnt_language[lang]" size="10" maxlength="10" />
                  </td>
               </tr>
               <tr>
                  <td>Direção da escrita</td>
                  <td>
                     <select name="lang_direction">
                        <option value="lang_right">Direita para a esquerda</option>
                        <option value="lang_left">Esquerda para a direita</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td>Versão do CACIC</td>
                  <td>
                     <input class="inputbox" type="text" name="mnt_language[type]" value="" size="10" maxlength="10" />
                  </td>
               </tr>
               <tr>
                  <td>Versão do idioma</td>
                  <td>
                     <input class="inputbox" type="text" name="mnt_language[abbr]" value="" size="10" maxlength="10" />
                  </td>
               </tr>
             <tr align="right">
                <td colspan="2">
                    <input class="button" type="submit" name="mnt_lang_action[salvar]" value="Save" />
                    <input class="button" type="reset" name="reset" value="Reset" />
                </td>
             </tr>
               <tfoot>
               <tr>

                  <td colspan="2">
                  </td>
               </tr>
               </tfoot>
          </table>

-->
 
<?php

   $_objTranslator->translatorGUI();

?>