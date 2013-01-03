<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicTransfs
 *
 * @ORM\Table(name="srcacic_transfs")
 * @ORM\Entity
 */
class SrcacicTransfs
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_srcacic_transfs", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSrcacicTransfs;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_conexao", type="integer", nullable=false)
     */
    private $idConexao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_systemtime", type="datetime", nullable=false)
     */
    private $dtSystemtime;

    /**
     * @var float
     *
     * @ORM\Column(name="nu_duracao", type="float", nullable=false)
     */
    private $nuDuracao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_path_origem", type="string", length=255, nullable=false)
     */
    private $tePathOrigem;

    /**
     * @var string
     *
     * @ORM\Column(name="te_path_destino", type="string", length=255, nullable=false)
     */
    private $tePathDestino;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_arquivo", type="string", length=127, nullable=false)
     */
    private $nmArquivo;

    /**
     * @var integer
     *
     * @ORM\Column(name="nu_tamanho_arquivo", type="integer", nullable=false)
     */
    private $nuTamanhoArquivo;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_status", type="string", length=1, nullable=false)
     */
    private $csStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_operacao", type="string", length=1, nullable=false)
     */
    private $csOperacao;



    /**
     * Get idSrcacicTransfs
     *
     * @return integer 
     */
    public function getIdSrcacicTransfs()
    {
        return $this->idSrcacicTransfs;
    }

    /**
     * Set idConexao
     *
     * @param integer $idConexao
     * @return SrcacicTransfs
     */
    public function setIdConexao($idConexao)
    {
        $this->idConexao = $idConexao;
    
        return $this;
    }

    /**
     * Get idConexao
     *
     * @return integer 
     */
    public function getIdConexao()
    {
        return $this->idConexao;
    }

    /**
     * Set dtSystemtime
     *
     * @param \DateTime $dtSystemtime
     * @return SrcacicTransfs
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
     * @return SrcacicTransfs
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
     * @return SrcacicTransfs
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
     * @return SrcacicTransfs
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
     * @return SrcacicTransfs
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
     * @return SrcacicTransfs
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
     * @return SrcacicTransfs
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
     * @return SrcacicTransfs
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
}