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
    private $idAquisicaoItem;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $idSoftwareEstacao;

    /**
     * @var \Cacic\CommonBundle\Entity\Aquisicao
     */
    private $idAquisicao;

    /**
     * @var \Cacic\CommonBundle\Entity\TipoLicenca
     */
    private $idTipoLicenca;

    /**
     * @var \Cacic\CommonBundle\Entity\SoftwareRelatorio
     */
    private $idSoftwareRelatorio;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idSoftwareEstacao = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idAquisicaoItem
     *
     * @return integer 
     */
    public function getIdAquisicaoItem()
    {
        return $this->idAquisicaoItem;
    }

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
     * Add idSoftwareEstacao
     *
     * @param \Cacic\CommonBundle\Entity\SoftwareEstacao $idSoftwareEstacao
     * @return AquisicaoItem
     */
    public function addIdSoftwareEstacao(\Cacic\CommonBundle\Entity\SoftwareEstacao $idSoftwareEstacao)
    {
        $this->idSoftwareEstacao[] = $idSoftwareEstacao;

        return $this;
    }

    /**
     * Remove idSoftwareEstacao
     *
     * @param \Cacic\CommonBundle\Entity\SoftwareEstacao $idSoftwareEstacao
     */
    public function removeIdSoftwareEstacao(\Cacic\CommonBundle\Entity\SoftwareEstacao $idSoftwareEstacao)
    {
        $this->idSoftwareEstacao->removeElement($idSoftwareEstacao);
    }

    /**
     * Get idSoftwareEstacao
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdSoftwareEstacao()
    {
        return $this->idSoftwareEstacao;
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
     * Set idSoftwareRelatorio
     *
     * @param \Cacic\CommonBundle\Entity\SoftwareRelatorio $idSoftwareRelatorio
     * @return AquisicaoItem
     */
    public function setIdSoftwareRelatorio(\Cacic\CommonBundle\Entity\SoftwareRelatorio $idSoftwareRelatorio = null)
    {
        $this->idSoftwareRelatorio = $idSoftwareRelatorio;

        return $this;
    }

    /**
     * Get idSoftwareRelatorio
     *
     * @return \Cacic\CommonBundle\Entity\SoftwareRelatorio 
     */
    public function getIdSoftwareRelatorio()
    {
        return $this->idSoftwareRelatorio;
    }
}
