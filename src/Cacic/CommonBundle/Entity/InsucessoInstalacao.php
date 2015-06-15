<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InsucessoInstalacao
 */
class InsucessoInstalacao
{
    /**
     * @var integer
     */
    private $idInsucessoInstalacao;

    /**
     * @var string
     */
    private $teIpComputador;

    /**
     * @var string
     */
    private $teSo;

    /**
     * @var string
     */
    private $idUsuario;

    /**
     * @var \DateTime
     */
    private $dtDatahora;

    /**
     * @var string
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
     * Set teIpComputador
     *
     * @param string $teIpComputador
     * @return InsucessoInstalacao
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
     * @return InsucessoInstalacao
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
     * @return InsucessoInstalacao
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
     * @return InsucessoInstalacao
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
     * @return InsucessoInstalacao
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
     * @var string
     */
    private $mensagem;


    /**
     * Set mensagem
     *
     * @param string $mensagem
     * @return InsucessoInstalacao
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    /**
     * Get mensagem
     *
     * @return string 
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }
}
