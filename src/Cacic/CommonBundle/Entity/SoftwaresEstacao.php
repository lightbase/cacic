<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoftwaresEstacao
 *
 * @ORM\Table(name="softwares_estacao")
 * @ORM\Entity
 */
class SoftwaresEstacao
{
    /**
     * @var string
     *
     * @ORM\Column(name="nr_patrimonio", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $nrPatrimonio;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_software", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSoftware;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_computador", type="string", length=50, nullable=true)
     */
    private $nmComputador;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_autorizacao", type="date", nullable=true)
     */
    private $dtAutorizacao;

    /**
     * @var string
     *
     * @ORM\Column(name="nr_processo", type="string", length=11, nullable=true)
     */
    private $nrProcesso;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_expiracao_instalacao", type="date", nullable=true)
     */
    private $dtExpiracaoInstalacao;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_aquisicao_particular", type="integer", nullable=true)
     */
    private $idAquisicaoParticular;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_desinstalacao", type="date", nullable=true)
     */
    private $dtDesinstalacao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_observacao", type="string", length=90, nullable=true)
     */
    private $teObservacao;

    /**
     * @var string
     *
     * @ORM\Column(name="nr_patr_destino", type="string", length=20, nullable=true)
     */
    private $nrPatrDestino;



    /**
     * Set nrPatrimonio
     *
     * @param string $nrPatrimonio
     * @return SoftwaresEstacao
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
     * Set idSoftware
     *
     * @param integer $idSoftware
     * @return SoftwaresEstacao
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
     * Set nmComputador
     *
     * @param string $nmComputador
     * @return SoftwaresEstacao
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
     * @return SoftwaresEstacao
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
     * @return SoftwaresEstacao
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
     * @return SoftwaresEstacao
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
     * @return SoftwaresEstacao
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
     * @return SoftwaresEstacao
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
     * @return SoftwaresEstacao
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
     * @return SoftwaresEstacao
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
}