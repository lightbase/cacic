<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AquisicoesItem
 *
 * @ORM\Table(name="aquisicoes_item")
 * @ORM\Entity
 */
class AquisicoesItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_aquisicao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idAquisicao;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_software", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSoftware;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipo_licenca", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idTipoLicenca;

    /**
     * @var integer
     *
     * @ORM\Column(name="qt_licenca", type="integer", nullable=true)
     */
    private $qtLicenca;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_vencimento_licenca", type="date", nullable=true)
     */
    private $dtVencimentoLicenca;

    /**
     * @var string
     *
     * @ORM\Column(name="te_obs", type="string", length=50, nullable=true)
     */
    private $teObs;



    /**
     * Set idAquisicao
     *
     * @param integer $idAquisicao
     * @return AquisicoesItem
     */
    public function setIdAquisicao($idAquisicao)
    {
        $this->idAquisicao = $idAquisicao;
    
        return $this;
    }

    /**
     * Get idAquisicao
     *
     * @return integer 
     */
    public function getIdAquisicao()
    {
        return $this->idAquisicao;
    }

    /**
     * Set idSoftware
     *
     * @param integer $idSoftware
     * @return AquisicoesItem
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
     * Set idTipoLicenca
     *
     * @param integer $idTipoLicenca
     * @return AquisicoesItem
     */
    public function setIdTipoLicenca($idTipoLicenca)
    {
        $this->idTipoLicenca = $idTipoLicenca;
    
        return $this;
    }

    /**
     * Get idTipoLicenca
     *
     * @return integer 
     */
    public function getIdTipoLicenca()
    {
        return $this->idTipoLicenca;
    }

    /**
     * Set qtLicenca
     *
     * @param integer $qtLicenca
     * @return AquisicoesItem
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
     * @return AquisicoesItem
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
     * @return AquisicoesItem
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