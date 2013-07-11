<?php

namespace Cacic\WSBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

abstract class OldCacicHelper
{
	// define o nome do agente principal do CACIC
	const CACIC_MAIN_PROGRAM_NAME = 'cacic280';
  
    // define o nome da pasta local para o CACIC
    const CACIC_LOCAL_FOLDER_NAME = 'Cacic';

    // define  o nome da pasta de scripts de interface com os agentes
    const CACIC_WEB_SERVICES_FOLDER_NAME = 'ws/';

    // define  chave para agentes CACIC
    const CACIC_KEY = 'CacicBrasil';

    // define  chave para agentes CACIC
    const CACIC_PATH = '/Users/ecio/Sites/cacic/';

    // define  IV para agentes CACIC
    const CACIC_IV = 'abcdefghijklmnop';
    
    // define  path para componentes de instalação, coleta de dados de patrimônio e cliente de Suporte Remoto do CACIC
    const CACIC_PATH_RELATIVO_DOWNLOADS = 'downloads/';
    
    /**
     * 
     * Converte string no padrão Camel Case para o padrão com Underscore
     * Ex.: idGrupoUsuario -> id_grupo_usuario
     * @param string $string
     */
    public static function camelCaseToUnderscore( $string )
    {
    	$string = preg_replace(‘/(?<=\\w)(?=[A-Z])/',"_$1", $string);
		$string = strtolower($string);
		return $string;
    }
	
}