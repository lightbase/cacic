<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Softwares
 *
 * @ORM\Table(name="softwares")
 * @ORM\Entity(repositoryClass="Cacic\CommonBundle\Entity\SoftwaresRepository")
 */
class Softwares
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_software", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSoftware;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_software", type="string", length=150, nullable=true)
     */
    private $nmSoftware;

    /**
     * @var string
     *
     * @ORM\Column(name="te_descricao_software", type="string", length=255, nullable=true)
     */
    private $teDescricaoSoftware;

    /**
     * @var integer
     *
     * @ORM\Column(name="qt_licenca", type="integer", nullable=true)
     */
    private $qtLicenca;

    /**
     * @var string
     *
     * @ORM\Column(name="nr_midia", type="string", length=10, nullable=true)
     */
    private $nrMidia;

    /**
     * @var string
     *
     * @ORM\Column(name="te_local_midia", type="string", length=30, nullable=true)
     */
    private $teLocalMidia;

    /**
     * @var string
     *
     * @ORM\Column(name="te_obs", type="string", length=200, nullable=true)
     */
    private $teObs;



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
     * Set nmSoftware
     *
     * @param string $nmSoftware
     * @return Softwares
     */
    public function setNmSoftware($nmSoftware)
    {
        $this->nmSoftware = $nmSoftware;
    
        return $this;
    }

    /**
     * Get nmSoftware
     *
     * @return string 
     */
    public function getNmSoftware()
    {
        return $this->nmSoftware;
    }

    /**
     * Set teDescricaoSoftware
     *
     * @param string $teDescricaoSoftware
     * @return Softwares
     */
    public function setTeDescricaoSoftware($teDescricaoSoftware)
    {
        $this->teDescricaoSoftware = $teDescricaoSoftware;
    
        return $this;
    }

    /**
     * Get teDescricaoSoftware
     *
     * @return string 
     */
    public function getTeDescricaoSoftware()
    {
        return $this->teDescricaoSoftware;
    }

    /**
     * Set qtLicenca
     *
     * @param integer $qtLicenca
     * @return Softwares
     */
    public function setQtLicenca($qtLicenca)
    {
        $this->qtLicenca = $qtLicenca;
    
        return $this;
    }

    /**
     * Get qtLicenca
     *
     * @return integer 
     */
    public function getQtLicenca()
    {
        return $this->qtLicenca;
    }

    /**
     * Set nrMidia
     *
     * @param string $nrMidia
     * @return Softwares
     */
    public function setNrMidia($nrMidia)
    {
        $this->nrMidia = $nrMidia;
    
        return $this;
    }

    /**
     * Get nrMidia
     *
     * @return string 
     */
    public function getNrMidia()
    {
        return $this->nrMidia;
    }

    /**
     * Set teLocalMidia
     *
     * @param string $teLocalMidia
     * @return Softwares
     */
    public function setTeLocalMidia($teLocalMidia)
    {
        $this->teLocalMidia = $teLocalMidia;
    
        return $this;
    }

    /**
     * Get teLocalMidia
     *
     * @return string 
     */
    public function getTeLocalMidia()
    {
        return $this->teLocalMidia;
    }

    /**
     * Set teObs
     *
     * @param string $teObs
     * @return Softwares
     */
    public function setTeObs($teObs)
    {
        $this->teObs = $teObs;
    
        return $this;
    }

    /**
     * Get teObs
     *
     * @return string 
     */
    public function getTeObs()
    {
        return $this->teObs;
    }
}