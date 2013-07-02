<?php

namespace Cacic\CommonBundle\Helper;


class Constantes {
    //define o nome do agente principal do CACIC
    const CACIC_MAIN_PROGRAM_NAME = 'cacic280';
  
    //define o nome da pasta local para o CACIC
    const CACIC_LOCAL_FOLDER_NAME = 'Cacic';

    //define  o nome da pasta de scripts de interface com os agentes
    const CACIC_WEB_SERVICES_FOLDER_NAME = 'ws/';

    //define  chave para agentes CACIC
    const CACIC_KEY = 'CacicBrasil';
    
    //define  IV para agentes CACIC
    const CACIC_IV = 'abcdefghijklmnop';
    
    //define  path para componentes de instalação, coleta de dados de patrim�nio e cliente de Suporte Remoto do CACIC
    const CACIC_PATH_RELATIVO_DOWNLOADS = 'downloads/';
}