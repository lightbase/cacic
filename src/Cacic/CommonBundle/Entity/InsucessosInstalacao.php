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
     * @var integer
     *
     * @ORM\Column(name="id_insucesso_instalacao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idInsucessoInstalacao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_ip", type="string", length=15, nullable=false)
     */
    private $teIp;

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
     * Get idInsucessoInstalacao
     *
     * @return integer 
     */
    public function getIdInsucessoInstalacao()
    {
        return $this->idInsucessoInstalacao;
    }

    /**
     * Set teIp
     *
     * @param string $teIp
     * @return InsucessosInstalacao
     */
    public function setTeIp($teIp)
    {
        $this->teIp = $teIp;
    
        return $this;
    }

    /**
     * Get teIp
     *
     * @return string 
     */
    public function getTeIp()
    {
        return $this->teIp;
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
}