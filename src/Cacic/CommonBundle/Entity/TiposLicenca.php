<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TiposLicenca
 *
 * @ORM\Table(name="tipos_licenca")
 * @ORM\Entity
 */
class TiposLicenca
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipo_licenca", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTipoLicenca;

    /**
     * @var string
     *
     * @ORM\Column(name="te_tipo_licenca", type="string", length=20, nullable=true)
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
     * @return TiposLicenca
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