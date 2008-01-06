<?php
    require_once( 'Translator.php');

   // fim de dados para temas


   // Classe de traducao
   $objTranslator = new Translator("en-us",'','pt-br');
   $objTranslator->SetLangFilePath("./languages/");
   
    //* Teste com "seccoes"
   $_lang_sections = array('admin'         => '',
                           'cataloging'  => '',
                           'circulation'  => '',
                           'classes'  => '',
                           'home'          => '',
                           'navbars'   => '',
                           'opac'   => '',
                           'reports'   => '',
                           'shared'   => ''
                           );
    
   //$objTranslator->setLangFilesInSections(true);
   //$objTranslator->setLangFileSections($_lang_sections);
   
   $objTranslator->BuildLangArray();
   $objTranslator->BuildLangArray('target');  
   
   $objTranslator->translatorGUI(false);
   
?>
