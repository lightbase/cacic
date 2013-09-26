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
     * @var string
     */
    private $teNodeAddressCli;

    /**
     * @var string
     */
    private $teDocumentoReferencial;

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
    
    /**
     * @var \Cacic\CommonBundle\Entity\Usuario
     */
    private $usuario;

    /**
     * @var \Cacic\CommonBundle\Entity\So
     */
    private $so;


    /**
     * Set usuario
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $usuario
     * @return SrcacicConexao
     */
    public function setUsuario(\Cacic\CommonBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Cacic\CommonBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set so
     *
     * @param \Cacic\CommonBundle\Entity\So $so
     * @return SrcacicConexao
     */
    public function setSo(\Cacic\CommonBundle\Entity\So $so = null)
    {
        $this->so = $so;
    
        return $this;
    }

    /**
     * Get so
     *
     * @return \Cacic\CommonBundle\Entity\So 
     */
    public function getSo()
    {
        return $this->so;
    }
}