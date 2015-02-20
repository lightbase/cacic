<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicTransf
 */
class SrcacicTransf
{
    /**
     * @var integer
     */
    private $idSrcacicTransf;

    /**
     * @var \DateTime
     */
    private $dtSystemtime;

    /**
     * @var float
     */
    private $nuDuracao;

    /**
     * @var string
     */
    private $tePathOrigem;

    /**
     * @var string
     */
    private $tePathDestino;

    /**
     * @var string
     */
    private $nmArquivo;

    /**
     * @var integer
     */
    private $nuTamanhoArquivo;

    /**
     * @var string
     */
    private $csStatus;

    /**
     * @var string
     */
    private $csOperacao;

    /**
     * @var \Cacic\CommonBundle\Entity\SrcacicConexao
     */
    private $idSrcacicConexao;


    /**
     * Get idSrcacicTransf
     *
     * @return integer 
     */
    public function getIdSrcacicTransf()
    {
        return $this->idSrcacicTransf;
    }

    /**
     * Set dtSystemtime
     *
     * @param \DateTime $dtSystemtime
     * @return SrcacicTransf
     */
    public function setDtSystemtime($dtSystemtime)
    {
        $this->dtSystemtime = $dtSystemtime;
    
        return $this;
    }

    /**
     * Get dtSystemtime
     *
     * @return \DateTime 
     */
    public function getDtSystemtime()
    {
        return $this->dtSystemtime;
    }

    /**
     * Set nuDuracao
     *
     * @param float $nuDuracao
     * @return SrcacicTransf
     */
    public function setNuDuracao($nuDuracao)
    {
        $this->nuDuracao = $nuDuracao;
    
        return $this;
    }

    /**
     * Get nuDuracao
     *
     * @return float 
     */
    public function getNuDuracao()
    {
        return $this->nuDuracao;
    }

    /**
     * Set tePathOrigem
     *
     * @param string $tePathOrigem
     * @return SrcacicTransf
     */
    public function setTePathOrigem($tePathOrigem)
    {
        $this->tePathOrigem = $tePathOrigem;
    
        return $this;
    }

    /**
     * Get tePathOrigem
     *
     * @return string 
     */
    public function getTePathOrigem()
    {
        return $this->tePathOrigem;
    }

    /**
     * Set tePathDestino
     *
     * @param string $tePathDestino
     * @return SrcacicTransf
     */
    public function setTePathDestino($tePathDestino)
    {
        $this->tePathDestino = $tePathDestino;
    
        return $this;
    }

    /**
     * Get tePathDestino
     *
     * @return string 
     */
    public function getTePathDestino()
    {
        return $this->tePathDestino;
    }

    /**
     * Set nmArquivo
     *
     * @param string $nmArquivo
     * @return SrcacicTransf
     */
    public function setNmArquivo($nmArquivo)
    {
        $this->nmArquivo = $nmArquivo;
    
        return $this;
    }

    /**
     * Get nmArquivo
     *
     * @return string 
     */
    public function getNmArquivo()
    {
        return $this->nmArquivo;
    }

    /**
     * Set nuTamanhoArquivo
     *
     * @param integer $nuTamanhoArquivo
     * @return SrcacicTransf
     */
    public function setNuTamanhoArquivo($nuTamanhoArquivo)
    {
        $this->nuTamanhoArquivo = $nuTamanhoArquivo;
    
        return $this;
    }

    /**
     * Get nuTamanhoArquivo
     *
     * @return integer 
     */
    public function getNuTamanhoArquivo()
    {
        return $this->nuTamanhoArquivo;
    }

    /**
     * Set csStatus
     *
     * @param string $csStatus
     * @return SrcacicTransf
     */
    public function setCsStatus($csStatus)
    {
        $this->csStatus = $csStatus;
    
        return $this;
    }

    /**
     * Get csStatus
     *
     * @return string 
     */
    public function getCsStatus()
    {
        return $this->csStatus;
    }

    /**
     * Set csOperacao
     *
     * @param string $csOperacao
     * @return SrcacicTransf
     */
    public function setCsOperacao($csOperacao)
    {
        $this->csOperacao = $csOperacao;
    
        return $this;
    }

    /**
     * Get csOperacao
     *
     * @return string 
     */
    public function getCsOperacao()
    {
        return $this->csOperacao;
    }

    /**
     * Set idSrcacicConexao
     *
     * @param \Cacic\CommonBundle\Entity\SrcacicConexao $idSrcacicConexao
     * @return SrcacicTransf
     */
    public function setIdSrcacicConexao(\Cacic\CommonBundle\Entity\SrcacicConexao $idSrcacicConexao = null)
    {
        $this->idSrcacicConexao = $idSrcacicConexao;
    
        return $this;
    }

    /**
     * Get idSrcacicConexao
     *
     * @return \Cacic\CommonBundle\Entity\SrcacicConexao 
     */
    public function getIdSrcacicConexao()
    {
        return $this->idSrcacicConexao;
    }
}