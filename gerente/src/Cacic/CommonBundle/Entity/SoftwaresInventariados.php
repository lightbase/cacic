<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoftwaresInventariados
 *
 * @ORM\Table(name="softwares_inventariados")
 * @ORM\Entity
 */
class SoftwaresInventariados
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_software_inventariado", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSoftwareInventariado;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_software_inventariado", type="string", length=100, nullable=false)
     */
    private $nmSoftwareInventariado;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipo_software", type="integer", nullable=true)
     */
    private $idTipoSoftware;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_software", type="integer", nullable=true)
     */
    private $idSoftware;

    /**
     * @var string
     *
     * @ORM\Column(name="te_hash", type="string", length=40, nullable=false)
     */
    private $teHash;



    /**
     * Get idSoftwareInventariado
     *
     * @return integer 
     */
    public function getIdSoftwareInventariado()
    {
        return $this->idSoftwareInventariado;
    }

    /**
     * Set nmSoftwareInventariado
     *
     * @param string $nmSoftwareInventariado
     * @return SoftwaresInventariados
     */
    public function setNmSoftwareInventariado($nmSoftwareInventariado)
    {
        $this->nmSoftwareInventariado = $nmSoftwareInventariado;
    
        return $this;
    }

    /**
     * Get nmSoftwareInventariado
     *
     * @return string 
     */
    public function getNmSoftwareInventariado()
    {
        return $this->nmSoftwareInventariado;
    }

    /**
     * Set idTipoSoftware
     *
     * @param integer $idTipoSoftware
     * @return SoftwaresInventariados
     */
    public function setIdTipoSoftware($idTipoSoftware)
    {
        $this->idTipoSoftware = $idTipoSoftware;
    
        return $this;
    }

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
     * Set idSoftware
     *
     * @param integer $idSoftware
     * @return SoftwaresInventariados
     */
    public function setIdSoftware($idSoftware)
    {
        $this->idSoftware = $idSoftware;
    
        return $this;
    }

    /**
     * Get idSoftware
     *
     * @return integer 
     */
    public function getIdSoftware()
    {
        return $this->idSoftware;
    }

    /**
     * Set teHash
     *
     * @param string $teHash
     * @return SoftwaresInventariados
     */
    public function setTeHash($teHash)
    {
        $this->teHash = $teHash;
    
        return $this;
    }

    /**
     * Get teHash
     *
     * @return string 
     */
    public function getTeHash()
    {
        return $this->teHash;
    }
}