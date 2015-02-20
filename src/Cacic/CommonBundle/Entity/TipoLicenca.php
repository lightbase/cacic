<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoLicenca
 */
class TipoLicenca
{
    /**
     * @var integer
     */
    private $idTipoLicenca;

    /**
     * @var string
     */
    private $teTipoLicenca;


    /**
     * Get idTipoLicenca
     *
     * @return integer 
     */
    public function getIdTipoLicenca()
    {
        return $this->idTipoLicenca;
    }

    /**
     * Set teTipoLicenca
     *
     * @param string $teTipoLicenca
     * @return TipoLicenca
     */
    public function setTeTipoLicenca($teTipoLicenca)
    {
        $this->teTipoLicenca = $teTipoLicenca;
    
        return $this;
    }

    /**
     * Get teTipoLicenca
     *
     * @return string 
     */
    public function getTeTipoLicenca()
    {
        return $this->teTipoLicenca;
    }
}