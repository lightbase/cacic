<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricosSoftware
 *
 * @ORM\Table(name="historicos_software")
 * @ORM\Entity
 */
class HistoricosSoftware
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
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_inclusao", type="datetime", nullable=false)
     */
    private $dtHrInclusao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_ult_coleta", type="datetime", nullable=false)
     */
    private $dtHrUltColeta;



    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return HistoricosSoftware
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
     * @return HistoricosSoftware
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
     * @return HistoricosSoftware
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

    /**
     * Set dtHrInclusao
     *
     * @param \DateTime $dtHrInclusao
     * @return HistoricosSoftware
     */
    public function setDtHrInclusao($dtHrInclusao)
    {
        $this->dtHrInclusao = $dtHrInclusao;
    
        return $this;
    }

    /**
     * Get dtHrInclusao
     *
     * @return \DateTime 
     */
    public function getDtHrInclusao()
    {
        return $this->dtHrInclusao;
    }

    /**
     * Set dtHrUltColeta
     *
     * @param \DateTime $dtHrUltColeta
     * @return HistoricosSoftware
     */
    public function setDtHrUltColeta($dtHrUltColeta)
    {
        $this->dtHrUltColeta = $dtHrUltColeta;
    
        return $this;
    }

    /**
     * Get dtHrUltColeta
     *
     * @return \DateTime 
     */
    public function getDtHrUltColeta()
    {
        return $this->dtHrUltColeta;
    }
}