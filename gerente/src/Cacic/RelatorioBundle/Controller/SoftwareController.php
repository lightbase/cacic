<?php

namespace Cacic\RelatorioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SoftwareController extends Controller
{
	/**
	 * 
	 * Relatório de Configurações do Antivírus OfficeScan 
	 */
    public function officeScanAction()
    {
        return $this->render('CacicRelatorioBundle:Software:officescan.html.twig');
    }
}
