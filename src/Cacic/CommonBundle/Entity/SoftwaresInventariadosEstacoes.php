<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoftwaresInventariadosEstacoes
 *
 * @ORM\Table(name="softwares_inventariados_estacoes")
 * @ORM\Entity
 */
class SoftwaresInventariadosEstacoes
{
    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $teNodeAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_software_inventariado", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSoftwareInventariado;



    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return SoftwaresInventariadosEstacoes
     */
    public function setTeNodeAddress($teNodeAddress)
    {
        $this->teNodeAddress = $teNodeAddress;
    
        return $this;
    }

    /**
     * Get teNodeAddress
     *
     * @return string 
     */
    public function getTeNodeAddress()
    {
        return $this->teNodeAddress;
    }

    /**
     * Set idSo
     *
     * @param integer $idSo
     * @return SoftwaresInventariadosEstacoes
     */
    public function setIdSo($idSo)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

    /**
     * Get idSo
     *
     * @return integer 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }

    /**
     * Set idSoftwareInventariado
     *
     * @param integer $idSoftwareInventariado
     * @return SoftwaresInventariadosEstacoes
     */
    public function setIdSoftwareInventariado($idSoftwareInventariado)
    {
        $this->idSoftwareInventariado = $idSoftwareInventariado;
    
        return $this;
    }

    /**
     * Get idSoftwareInventariado
     *
     * @return integer 
     */
    public function getIdSoftwareInventariado()
    {
        return $this->idSoftwareInventariado;
    }
}