<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricosSoftwareCompleto
 *
 * @ORM\Table(name="historicos_software_completo")
 * @ORM\Entity
 */
class HistoricosSoftwareCompleto
{
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
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $dtHrInclusao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_ult_coleta", type="datetime", nullable=false)
     */
    private $dtHrUltColeta;

    /**
     * @var \Computadores
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;



    /**
     * Set idSoftwareInventariado
     *
     * @param integer $idSoftwareInventariado
     * @return HistoricosSoftwareCompleto
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
     * @return HistoricosSoftwareCompleto
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
     * @return HistoricosSoftwareCompleto
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

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return HistoricosSoftwareCompleto
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computadores $idComputador)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computadores 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }
}