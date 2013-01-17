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
     * @var \Aquisicoes
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Aquisicoes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_aquisicao", referencedColumnName="id_aquisicao")
     * })
     */
    private $idAquisicao;

    /**
     * @var \Softwares
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Softwares")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_software", referencedColumnName="id_software")
     * })
     */
    private $idSoftware;

    /**
     * @var \TiposLicenca
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TiposLicenca")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_licenca", referencedColumnName="id_tipo_licenca")
     * })
     */
    private $idTipoLicenca;



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

    /**
     * Set idAquisicao
     *
     * @param \Cacic\CommonBundle\Entity\Aquisicoes $idAquisicao
     * @return AquisicoesItem
     */
    public function setIdAquisicao(\Cacic\CommonBundle\Entity\Aquisicoes $idAquisicao)
    {
        $this->idAquisicao = $idAquisicao;
    
        return $this;
    }

    /**
     * Get idAquisicao
     *
     * @return \Cacic\CommonBundle\Entity\Aquisicoes 
     */
    public function getIdAquisicao()
    {
        return $this->idAquisicao;
    }

    /**
     * Set idSoftware
     *
     * @param \Cacic\CommonBundle\Entity\Softwares $idSoftware
     * @return AquisicoesItem
     */
    public function setIdSoftware(\Cacic\CommonBundle\Entity\Softwares $idSoftware)
    {
        $this->idSoftware = $idSoftware;
    
        return $this;
    }

    /**
     * Get idSoftware
     *
     * @return \Cacic\CommonBundle\Entity\Softwares 
     */
    public function getIdSoftware()
    {
        return $this->idSoftware;
    }

    /**
     * Set idTipoLicenca
     *
     * @param \Cacic\CommonBundle\Entity\TiposLicenca $idTipoLicenca
     * @return AquisicoesItem
     */
    public function setIdTipoLicenca(\Cacic\CommonBundle\Entity\TiposLicenca $idTipoLicenca)
    {
        $this->idTipoLicenca = $idTipoLicenca;
    
        return $this;
    }

    /**
     * Get idTipoLicenca
     *
     * @return \Cacic\CommonBundle\Entity\TiposLicenca 
     */
    public function getIdTipoLicenca()
    {
        return $this->idTipoLicenca;
    }
}