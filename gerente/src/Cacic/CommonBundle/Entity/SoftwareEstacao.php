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
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $idComputador;

    /**
     * @var \Cacic\CommonBundle\Entity\Aquisicao
     */
    private $idAquisicao;


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

    /**
     * Set idAquisicao
     *
     * @param \Cacic\CommonBundle\Entity\Aquisicao $idAquisicao
     * @return SoftwareEstacao
     */
    public function setIdAquisicao(\Cacic\CommonBundle\Entity\Aquisicao $idAquisicao = null)
    {
        $this->idAquisicao = $idAquisicao;
    
        return $this;
    }

    /**
     * Get idAquisicao
     *
     * @return \Cacic\CommonBundle\Entity\Aquisicao 
     */
    public function getIdAquisicao()
    {
        return $this->idAquisicao;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $idComputador
     * @return SoftwareEstacao
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computador $idComputador)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computador 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }
}