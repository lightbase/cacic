<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoSoftware
 */
class TipoSoftware
{
    /**
     * @var integer
     */
    private $idTipoSoftware;

    /**
     * @var string
     */
    private $teDescricaoTipoSoftware;


    /**
     * Get idTipoSoftware
     *
     * @return integer 
     */
    public function getIdTipoSoftware()
    {
        return $this->idTipoSoftware;
    }

    /**
     * Set teDescricaoTipoSoftware
     *
     * @param string $teDescricaoTipoSoftware
     * @return TipoSoftware
     */
    public function setTeDescricaoTipoSoftware($teDescricaoTipoSoftware)
    {
        $this->teDescricaoTipoSoftware = $teDescricaoTipoSoftware;
    
        return $this;
    }

    /**
     * Get teDescricaoTipoSoftware
     *
     * @return string 
     */
    public function getTeDescricaoTipoSoftware()
    {
        return $this->teDescricaoTipoSoftware;
    }
    
    /**
     * 
     * Método mágico invocado quando um Objeto desta Classe é referenciado em contexto de String
     * @return string
     */
    public function __toString()
    {
    	return $this->getTeDescricaoTipoSoftware();
    }
}