<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoftwareEstacao
 */
class SoftwareEstacao
{
    /**
     * @var string
     */
    private $nrPatrimonio;

    /**
     * @var string
     */
    private $nmComputador;

    /**
     * @var \DateTime
     */
    private $dtAutorizacao;

    /**
     * @var string
     */
    private $nrProcesso;

    /**
     * @var \DateTime
     */
    private $dtExpiracaoInstalacao;

    /**
     * @var integer
     */
    private $idAquisicaoParticular;

    /**
     * @var \DateTime
     */
    private $dtDesinstalacao;

    /**
     * @var string
     */
    private $teObservacao;

    /**
     * @var string
     */
    private $nrPatrDestino;

    /**
     * @var \Cacic\CommonBundle\Entity\Software
     */
    private $idSoftware;


    /**
     * Set nrPatrimonio
     *
     * @param string $nrPatrimonio
     * @return SoftwareEstacao
     */
    public function setNrPatrimonio($nrPatrimonio)
    {
        $this->nrPatrimonio = $nrPatrimonio;
    
        return $this;
    }

    /**
     * Get nrPatrimonio
     *
     * @return string 
     */
    public function getNrPatrimonio()
    {
        return $this->nrPatrimonio;
    }

    /**
     * Set nmComputador
     *
     * @param string $nmComputador
     * @return SoftwareEstacao
     */
    public function setNmComputador($nmComputador)
    {
        $this->nmComputador = $nmComputador;
    
        return $this;
    }

    /**
     * Get nmComputador
     *
     * @return string 
     */
    public function getNmComputador()
    {
        return $this->nmComputador;
    }

    /**
     * Set dtAutorizacao
     *
     * @param \DateTime $dtAutorizacao
     * @return SoftwareEstacao
     */
    public function setDtAutorizacao($dtAutorizacao)
    {
        $this->dtAutorizacao = $dtAutorizacao;
    
        return $this;
    }

    /**
     * Get dtAutorizacao
     *
     * @return \DateTime 
     */
    public function getDtAutorizacao()
    {
        return $this->dtAutorizacao;
    }

    /**
     * Set nrProcesso
     *
     * @param string $nrProcesso
     * @return SoftwareEstacao
     */
    public function setNrProcesso($nrProcesso)
    {
        $this->nrProcesso = $nrProcesso;
    
        return $this;
    }

    /**
     * Get nrProcesso
     *
     * @return string 
     */
    public function getNrProcesso()
    {
        return $this->nrProcesso;
    }

    /**
     * Set dtExpiracaoInstalacao
     *
     * @param \DateTime $dtExpiracaoInstalacao
     * @return SoftwareEstacao
     */
    public function setDtExpiracaoInstalacao($dtExpiracaoInstalacao)
    {
        $this->dtExpiracaoInstalacao = $dtExpiracaoInstalacao;
    
        return $this;
    }

    /**
     * Get dtExpiracaoInstalacao
     *
     * @return \DateTime 
     */
    public function getDtExpiracaoInstalacao()
    {
        return $this->dtExpiracaoInstalacao;
    }

    /**
     * Set idAquisicaoParticular
     *
     * @param integer $idAquisicaoParticular
     * @return SoftwareEstacao
     */
    public function setIdAquisicaoParticular($idAquisicaoParticular)
    {
        $this->idAquisicaoParticular = $idAquisicaoParticular;
    
        return $this;
    }

    /**
     * Get idAquisicaoParticular
     *
     * @return integer 
     */
    public function getIdAquisicaoParticular()
    {
        return $this->idAquisicaoParticular;
    }

    /**
     * Set dtDesinstalacao
     *
     * @param \DateTime $dtDesinstalacao
     * @return SoftwareEstacao
     */
    public function setDtDesinstalacao($dtDesinstalacao)
    {
        $this->dtDesinstalacao = $dtDesinstalacao;
    
        return $this;
    }

    /**
     * Get dtDesinstalacao
     *
     * @return \DateTime 
     */
    public function getDtDesinstalacao()
    {
        return $this->dtDesinstalacao;
    }

    /**
     * Set teObservacao
     *
     * @param string $teObservacao
     * @return SoftwareEstacao
     */
    public function setTeObservacao($teObservacao)
    {
        $this->teObservacao = $teObservacao;
    
        return $this;
    }

    /**
     * Get teObservacao
     *
     * @return string 
     */
    public function getTeObservacao()
    {
        return $this->teObservacao;
    }

    /**
     * Set nrPatrDestino
     *
     * @param string $nrPatrDestino
     * @return SoftwareEstacao
     */
    public function setNrPatrDestino($nrPatrDestino)
    {
        $this->nrPatrDestino = $nrPatrDestino;
    
        return $this;
    }

    /**
     * Get nrPatrDestino
     *
     * @return string 
     */
    public function getNrPatrDestino()
    {
        return $this->nrPatrDestino;
    }

    /**
     * Set idSoftware
     *
     * @param \Cacic\CommonBundle\Entity\Software $idSoftware
     * @return SoftwareEstacao
     */
    public function setIdSoftware(\Cacic\CommonBundle\Entity\Software $idSoftware = null)
    {
        $this->idSoftware = $idSoftware;
    
        return $this;
    }

    /**
     * Get idSoftware
     *
     * @return \Cacic\CommonBundle\Entity\Software 
     */
    public function getIdSoftware()
    {
        return $this->idSoftware;
    }
}