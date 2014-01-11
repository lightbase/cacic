<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Software
 */
class Software
{
    /**
     * @var integer
     */
    private $idSoftware;

    /**
     * @var string
     */
    private $nmSoftware;

    /**
     * @var string
     */
    private $teDescricaoSoftware;

    /**
     * @var integer
     */
    private $qtLicenca;

    /**
     * @var string
     */
    private $nrMidia;

    /**
     * @var string
     */
    private $teLocalMidia;

    /**
     * @var string
     */
    private $teObs;

    /**
     * @var \Cacic\CommonBundle\Entity\TipoSoftware
     */
    private $idTipoSoftware;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $estacoes;

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
     * @return Software
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
     * @return Software
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
     * @return Software
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
     * @return Software
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
     * @return Software
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
     * @return Software
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

    /**
     * Set idTipoSoftware
     *
     * @param \Cacic\CommonBundle\Entity\TipoSoftware $idTipoSoftware
     * @return Software
     */
    public function setIdTipoSoftware(\Cacic\CommonBundle\Entity\TipoSoftware $idTipoSoftware = null)
    {
        $this->idTipoSoftware = $idTipoSoftware;
    
        return $this;
    }

    /**
     * Get idTipoSoftware
     *
     * @return \Cacic\CommonBundle\Entity\TipoSoftware 
     */
    public function getIdTipoSoftware()
    {
        return $this->idTipoSoftware;
    }
    
	/**
     * Add SoftwareEstacao
     *
     * @param \Cacic\CommonBundle\Entity\SoftwareEstacao $estacao
     * @return Software
     */
    public function addEstacoes(\Cacic\CommonBundle\Entity\SoftwareEstacao $estacao)
    {
        $this->estacoes[] = $estacao;
    
        return $this;
    }

    /**
     * Remove SoftwareEstacao
     *
     * @param \Cacic\CommonBundle\Entity\SoftwareEstacao $estacao
     */
    public function removeEstacoes(\Cacic\CommonBundle\Entity\SoftwareEstacao $estacao)
    {
        $this->estacoes->removeElement($estacao);
    }

    /**
     * Get estacoes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstacoes()
    {
        return $this->estacoes;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->estacoes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add estacoes
     *
     * @param \Cacic\CommonBundle\Entity\SoftwareEstacao $estacoes
     * @return Software
     */
    public function addEstacoe(\Cacic\CommonBundle\Entity\SoftwareEstacao $estacoes)
    {
        $this->estacoes[] = $estacoes;
    
        return $this;
    }

    /**
     * Remove estacoes
     *
     * @param \Cacic\CommonBundle\Entity\SoftwareEstacao $estacoes
     */
    public function removeEstacoe(\Cacic\CommonBundle\Entity\SoftwareEstacao $estacoes)
    {
        $this->estacoes->removeElement($estacoes);
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $licencas;


    /**
     * Add licencas
     *
     * @param \Cacic\CommonBundle\Entity\AquisicaoItem $licencas
     * @return Software
     */
    public function addLicenca(\Cacic\CommonBundle\Entity\AquisicaoItem $licencas)
    {
        $this->licencas[] = $licencas;
    
        return $this;
    }

    /**
     * Remove licencas
     *
     * @param \Cacic\CommonBundle\Entity\AquisicaoItem $licencas
     */
    public function removeLicenca(\Cacic\CommonBundle\Entity\AquisicaoItem $licencas)
    {
        $this->licencas->removeElement($licencas);
    }

    /**
     * Get licencas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLicencas()
    {
        return $this->licencas;
    }
}