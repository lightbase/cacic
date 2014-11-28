<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AquisicaoItem
 */
class AquisicaoItem
{
    /**
     * @var integer
     */
    private $qtLicenca;

    /**
     * @var \DateTime
     */
    private $dtVencimentoLicenca;

    /**
     * @var string
     */
    private $teObs;

    /**
     * @var \Cacic\CommonBundle\Entity\TipoLicenca
     */
    private $idTipoLicenca;

    /**
     * @var \Cacic\CommonBundle\Entity\Aquisicao
     */
    private $idAquisicao;

    /**
     * @var \Cacic\CommonBundle\Entity\Software
     */
    private $idSoftware;


    /**
     * Set qtLicenca
     *
     * @param integer $qtLicenca
     * @return AquisicaoItem
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
     * Set dtVencimentoLicenca
     *
     * @param \DateTime $dtVencimentoLicenca
     * @return AquisicaoItem
     */
    public function setDtVencimentoLicenca($dtVencimentoLicenca)
    {
        $this->dtVencimentoLicenca = $dtVencimentoLicenca;
    
        return $this;
    }

    /**
     * Get dtVencimentoLicenca
     *
     * @return \DateTime 
     */
    public function getDtVencimentoLicenca()
    {
        return $this->dtVencimentoLicenca;
    }

    /**
     * Set teObs
     *
     * @param string $teObs
     * @return AquisicaoItem
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
     * Set idTipoLicenca
     *
     * @param \Cacic\CommonBundle\Entity\TipoLicenca $idTipoLicenca
     * @return AquisicaoItem
     */
    public function setIdTipoLicenca(\Cacic\CommonBundle\Entity\TipoLicenca $idTipoLicenca = null)
    {
        $this->idTipoLicenca = $idTipoLicenca;
    
        return $this;
    }

    /**
     * Get idTipoLicenca
     *
     * @return \Cacic\CommonBundle\Entity\TipoLicenca 
     */
    public function getIdTipoLicenca()
    {
        return $this->idTipoLicenca;
    }

    /**
     * Set idAquisicao
     *
     * @param \Cacic\CommonBundle\Entity\Aquisicao $idAquisicao
     * @return AquisicaoItem
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
     * Set idSoftware
     *
     * @param \Cacic\CommonBundle\Entity\Software $idSoftware
     * @return AquisicaoItem
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
