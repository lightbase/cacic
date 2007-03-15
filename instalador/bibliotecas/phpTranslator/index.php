<?php
    require_once( 'Translator.php');

   // fim de dados para temas


   // Classe de traducao
   $objTranslator = new Translator('pt-br', "en-us");
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
   //$objTranslator->setLangSections($_lang_sections);
   
   
   $objTranslator->BuildLangArray();
   $objTranslator->BuildLangArray('target');  
   
   $objTranslator->Translate(false);
   
?>
