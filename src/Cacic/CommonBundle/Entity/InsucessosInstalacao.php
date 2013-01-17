<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InsucessosInstalacao
 *
 * @ORM\Table(name="insucessos_instalacao")
 * @ORM\Entity
 */
class InsucessosInstalacao
{
    /**
     * @var string
     *
     * @ORM\Column(name="te_ip_computador", type="string", length=15, nullable=false)
     */
    private $teIpComputador;

    /**
     * @var string
     *
     * @ORM\Column(name="te_so", type="string", length=60, nullable=false)
     */
    private $teSo;

    /**
     * @var string
     *
     * @ORM\Column(name="id_usuario", type="string", length=60, nullable=false)
     */
    private $idUsuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_datahora", type="datetime", nullable=false)
     */
    private $dtDatahora;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_indicador", type="string", length=1, nullable=false)
     */
    private $csIndicador;

    /**
     * @var \InsucessosInstalacao
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="InsucessosInstalacao")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_insucesso_instalacao", referencedColumnName="id_insucesso_instalacao")
     * })
     */
    private $idInsucessoInstalacao;



    /**
     * Set teIpComputador
     *
     * @param string $teIpComputador
     * @return InsucessosInstalacao
     */
    public function setTeIpComputador($teIpComputador)
    {
        $this->teIpComputador = $teIpComputador;
    
        return $this;
    }

    /**
     * Get teIpComputador
     *
     * @return string 
     */
    public function getTeIpComputador()
    {
        return $this->teIpComputador;
    }

    /**
     * Set teSo
     *
     * @param string $teSo
     * @return InsucessosInstalacao
     */
    public function setTeSo($teSo)
    {
        $this->teSo = $teSo;
    
        return $this;
    }

    /**
     * Get teSo
     *
     * @return string 
     */
    public function getTeSo()
    {
        return $this->teSo;
    }

    /**
     * Set idUsuario
     *
     * @param string $idUsuario
     * @return InsucessosInstalacao
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    
        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return string 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set dtDatahora
     *
     * @param \DateTime $dtDatahora
     * @return InsucessosInstalacao
     */
    public function setDtDatahora($dtDatahora)
    {
        $this->dtDatahora = $dtDatahora;
    
        return $this;
    }

    /**
     * Get dtDatahora
     *
     * @return \DateTime 
     */
    public function getDtDatahora()
    {
        return $this->dtDatahora;
    }

    /**
     * Set csIndicador
     *
     * @param string $csIndicador
     * @return InsucessosInstalacao
     */
    public function setCsIndicador($csIndicador)
    {
        $this->csIndicador = $csIndicador;
    
        return $this;
    }

    /**
     * Get csIndicador
     *
     * @return string 
     */
    public function getCsIndicador()
    {
        return $this->csIndicador;
    }

    /**
     * Set idInsucessoInstalacao
     *
     * @param \Cacic\CommonBundle\Entity\InsucessosInstalacao $idInsucessoInstalacao
     * @return InsucessosInstalacao
     */
    public function setIdInsucessoInstalacao(\Cacic\CommonBundle\Entity\InsucessosInstalacao $idInsucessoInstalacao)
    {
        $this->idInsucessoInstalacao = $idInsucessoInstalacao;
    
        return $this;
    }

    /**
     * Get idInsucessoInstalacao
     *
     * @return \Cacic\CommonBundle\Entity\InsucessosInstalacao 
     */
    public function getIdInsucessoInstalacao()
    {
        return $this->idInsucessoInstalacao;
    }
}