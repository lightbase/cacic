<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicConexao
 */
class SrcacicConexao
{
    /**
     * @var integer
     */
    private $idSrcacicConexao;

    /**
     * @var integer
     */
    private $idUsuarioCli;

    /**
     * @var string
     */
    private $teNodeAddressCli;

    /**
     * @var string
     */
    private $teDocumentoReferencial;

    /**
     * @var integer
     */
    private $idSoCli;

    /**
     * @var string
     */
    private $teMotivoConexao;

    /**
     * @var \DateTime
     */
    private $dtHrInicioConexao;

    /**
     * @var \DateTime
     */
    private $dtHrUltimoContato;

    /**
     * @var \Cacic\CommonBundle\Entity\SrcacicSessao
     */
    private $idSrcacicSessao;


    /**
     * Get idSrcacicConexao
     *
     * @return integer 
     */
    public function getIdSrcacicConexao()
    {
        return $this->idSrcacicConexao;
    }

    /**
     * Set idUsuarioCli
     *
     * @param integer $idUsuarioCli
     * @return SrcacicConexao
     */
    public function setIdUsuarioCli($idUsuarioCli)
    {
        $this->idUsuarioCli = $idUsuarioCli;
    
        return $this;
    }

    /**
     * Get idUsuarioCli
     *
     * @return integer 
     */
    public function getIdUsuarioCli()
    {
        return $this->idUsuarioCli;
    }

    /**
     * Set teNodeAddressCli
     *
     * @param string $teNodeAddressCli
     * @return SrcacicConexao
     */
    public function setTeNodeAddressCli($teNodeAddressCli)
    {
        $this->teNodeAddressCli = $teNodeAddressCli;
    
        return $this;
    }

    /**
     * Get teNodeAddressCli
     *
     * @return string 
     */
    public function getTeNodeAddressCli()
    {
        return $this->teNodeAddressCli;
    }

    /**
     * Set teDocumentoReferencial
     *
     * @param string $teDocumentoReferencial
     * @return SrcacicConexao
     */
    public function setTeDocumentoReferencial($teDocumentoReferencial)
    {
        $this->teDocumentoReferencial = $teDocumentoReferencial;
    
        return $this;
    }

    /**
     * Get teDocumentoReferencial
     *
     * @return string 
     */
    public function getTeDocumentoReferencial()
    {
        return $this->teDocumentoReferencial;
    }

    /**
     * Set idSoCli
     *
     * @param integer $idSoCli
     * @return SrcacicConexao
     */
    public function setIdSoCli($idSoCli)
    {
        $this->idSoCli = $idSoCli;
    
        return $this;
    }

    /**
     * Get idSoCli
     *
     * @return integer 
     */
    public function getIdSoCli()
    {
        return $this->idSoCli;
    }

    /**
     * Set teMotivoConexao
     *
     * @param string $teMotivoConexao
     * @return SrcacicConexao
     */
    public function setTeMotivoConexao($teMotivoConexao)
    {
        $this->teMotivoConexao = $teMotivoConexao;
    
        return $this;
    }

    /**
     * Get teMotivoConexao
     *
     * @return string 
     */
    public function getTeMotivoConexao()
    {
        return $this->teMotivoConexao;
    }

    /**
     * Set dtHrInicioConexao
     *
     * @param \DateTime $dtHrInicioConexao
     * @return SrcacicConexao
     */
    public function setDtHrInicioConexao($dtHrInicioConexao)
    {
        $this->dtHrInicioConexao = $dtHrInicioConexao;
    
        return $this;
    }

    /**
     * Get dtHrInicioConexao
     *
     * @return \DateTime 
     */
    public function getDtHrInicioConexao()
    {
        return $this->dtHrInicioConexao;
    }

    /**
     * Set dtHrUltimoContato
     *
     * @param \DateTime $dtHrUltimoContato
     * @return SrcacicConexao
     */
    public function setDtHrUltimoContato($dtHrUltimoContato)
    {
        $this->dtHrUltimoContato = $dtHrUltimoContato;
    
        return $this;
    }

    /**
     * Get dtHrUltimoContato
     *
     * @return \DateTime 
     */
    public function getDtHrUltimoContato()
    {
        return $this->dtHrUltimoContato;
    }

    /**
     * Set idSrcacicSessao
     *
     * @param \Cacic\CommonBundle\Entity\SrcacicSessao $idSrcacicSessao
     * @return SrcacicConexao
     */
    public function setIdSrcacicSessao(\Cacic\CommonBundle\Entity\SrcacicSessao $idSrcacicSessao = null)
    {
        $this->idSrcacicSessao = $idSrcacicSessao;
    
        return $this;
    }

    /**
     * Get idSrcacicSessao
     *
     * @return \Cacic\CommonBundle\Entity\SrcacicSessao 
     */
    public function getIdSrcacicSessao()
    {
        return $this->idSrcacicSessao;
    }
}