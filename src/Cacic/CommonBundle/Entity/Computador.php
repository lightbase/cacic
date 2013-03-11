<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Computador
 */
class Computador
{
    /**
     * @var integer
     */
    private $idComputador;

    /**
     * @var string
     */
    private $teNodeAddress;

    /**
     * @var string
     */
    private $teIpComputador;

    /**
     * @var \DateTime
     */
    private $dtHrInclusao;

    /**
     * @var \DateTime
     */
    private $dtHrExclusao;

    /**
     * @var \DateTime
     */
    private $dtHrUltAcesso;

    /**
     * @var string
     */
    private $teVersaoCacic;

    /**
     * @var string
     */
    private $teVersaoGercols;

    /**
     * @var string
     */
    private $tePalavraChave;

    /**
     * @var \DateTime
     */
    private $dtHrColetaForcadaEstacao;

    /**
     * @var string
     */
    private $teNomesCurtosModulos;

    /**
     * @var integer
     */
    private $idConta;

    /**
     * @var string
     */
    private $teDebugging;

    /**
     * @var string
     */
    private $teUltimoLogin;

    /**
     * @var string
     */
    private $dtDebug;

    /**
     * @var \Cacic\CommonBundle\Entity\Usuario
     */
    private $idUsuarioExclusao;

    /**
     * @var \Cacic\CommonBundle\Entity\So
     */
    private $idSo;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $idRede;


    /**
     * Get idComputador
     *
     * @return integer 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }

    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return Computador
     */
    public function setTeNodeAddress($teNodeAddress)
    {
        $this->teNodeAddress = $teNodeAddress;
    
        return $this;
    }

    /**
     * Get teNodeAddress
     *
     * @return string 
     */
    public function getTeNodeAddress()
    {
        return $this->teNodeAddress;
    }

    /**
     * Set teIpComputador
     *
     * @param string $teIpComputador
     * @return Computador
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
     * Set dtHrInclusao
     *
     * @param \DateTime $dtHrInclusao
     * @return Computador
     */
    public function setDtHrInclusao($dtHrInclusao)
    {
        $this->dtHrInclusao = $dtHrInclusao;
    
        return $this;
    }

    /**
     * Get dtHrInclusao
     *
     * @return \DateTime 
     */
    public function getDtHrInclusao()
    {
        return $this->dtHrInclusao;
    }

    /**
     * Set dtHrExclusao
     *
     * @param \DateTime $dtHrExclusao
     * @return Computador
     */
    public function setDtHrExclusao($dtHrExclusao)
    {
        $this->dtHrExclusao = $dtHrExclusao;
    
        return $this;
    }

    /**
     * Get dtHrExclusao
     *
     * @return \DateTime 
     */
    public function getDtHrExclusao()
    {
        return $this->dtHrExclusao;
    }

    /**
     * Set dtHrUltAcesso
     *
     * @param \DateTime $dtHrUltAcesso
     * @return Computador
     */
    public function setDtHrUltAcesso($dtHrUltAcesso)
    {
        $this->dtHrUltAcesso = $dtHrUltAcesso;
    
        return $this;
    }

    /**
     * Get dtHrUltAcesso
     *
     * @return \DateTime 
     */
    public function getDtHrUltAcesso()
    {
        return $this->dtHrUltAcesso;
    }

    /**
     * Set teVersaoCacic
     *
     * @param string $teVersaoCacic
     * @return Computador
     */
    public function setTeVersaoCacic($teVersaoCacic)
    {
        $this->teVersaoCacic = $teVersaoCacic;
    
        return $this;
    }

    /**
     * Get teVersaoCacic
     *
     * @return string 
     */
    public function getTeVersaoCacic()
    {
        return $this->teVersaoCacic;
    }

    /**
     * Set teVersaoGercols
     *
     * @param string $teVersaoGercols
     * @return Computador
     */
    public function setTeVersaoGercols($teVersaoGercols)
    {
        $this->teVersaoGercols = $teVersaoGercols;
    
        return $this;
    }

    /**
     * Get teVersaoGercols
     *
     * @return string 
     */
    public function getTeVersaoGercols()
    {
        return $this->teVersaoGercols;
    }

    /**
     * Set tePalavraChave
     *
     * @param string $tePalavraChave
     * @return Computador
     */
    public function setTePalavraChave($tePalavraChave)
    {
        $this->tePalavraChave = $tePalavraChave;
    
        return $this;
    }

    /**
     * Get tePalavraChave
     *
     * @return string 
     */
    public function getTePalavraChave()
    {
        return $this->tePalavraChave;
    }

    /**
     * Set dtHrColetaForcadaEstacao
     *
     * @param \DateTime $dtHrColetaForcadaEstacao
     * @return Computador
     */
    public function setDtHrColetaForcadaEstacao($dtHrColetaForcadaEstacao)
    {
        $this->dtHrColetaForcadaEstacao = $dtHrColetaForcadaEstacao;
    
        return $this;
    }

    /**
     * Get dtHrColetaForcadaEstacao
     *
     * @return \DateTime 
     */
    public function getDtHrColetaForcadaEstacao()
    {
        return $this->dtHrColetaForcadaEstacao;
    }

    /**
     * Set teNomesCurtosModulos
     *
     * @param string $teNomesCurtosModulos
     * @return Computador
     */
    public function setTeNomesCurtosModulos($teNomesCurtosModulos)
    {
        $this->teNomesCurtosModulos = $teNomesCurtosModulos;
    
        return $this;
    }

    /**
     * Get teNomesCurtosModulos
     *
     * @return string 
     */
    public function getTeNomesCurtosModulos()
    {
        return $this->teNomesCurtosModulos;
    }

    /**
     * Set idConta
     *
     * @param integer $idConta
     * @return Computador
     */
    public function setIdConta($idConta)
    {
        $this->idConta = $idConta;
    
        return $this;
    }

    /**
     * Get idConta
     *
     * @return integer 
     */
    public function getIdConta()
    {
        return $this->idConta;
    }

    /**
     * Set teDebugging
     *
     * @param string $teDebugging
     * @return Computador
     */
    public function setTeDebugging($teDebugging)
    {
        $this->teDebugging = $teDebugging;
    
        return $this;
    }

    /**
     * Get teDebugging
     *
     * @return string 
     */
    public function getTeDebugging()
    {
        return $this->teDebugging;
    }

    /**
     * Set teUltimoLogin
     *
     * @param string $teUltimoLogin
     * @return Computador
     */
    public function setTeUltimoLogin($teUltimoLogin)
    {
        $this->teUltimoLogin = $teUltimoLogin;
    
        return $this;
    }

    /**
     * Get teUltimoLogin
     *
     * @return string 
     */
    public function getTeUltimoLogin()
    {
        return $this->teUltimoLogin;
    }

    /**
     * Set dtDebug
     *
     * @param string $dtDebug
     * @return Computador
     */
    public function setDtDebug($dtDebug)
    {
        $this->dtDebug = $dtDebug;
    
        return $this;
    }

    /**
     * Get dtDebug
     *
     * @return string 
     */
    public function getDtDebug()
    {
        return $this->dtDebug;
    }

    /**
     * Set idUsuarioExclusao
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $idUsuarioExclusao
     * @return Computador
     */
    public function setIdUsuarioExclusao(\Cacic\CommonBundle\Entity\Usuario $idUsuarioExclusao = null)
    {
        $this->idUsuarioExclusao = $idUsuarioExclusao;
    
        return $this;
    }

    /**
     * Get idUsuarioExclusao
     *
     * @return \Cacic\CommonBundle\Entity\Usuario 
     */
    public function getIdUsuarioExclusao()
    {
        return $this->idUsuarioExclusao;
    }

    /**
     * Set idSo
     *
     * @param \Cacic\CommonBundle\Entity\So $idSo
     * @return Computador
     */
    public function setIdSo(\Cacic\CommonBundle\Entity\So $idSo = null)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

    /**
     * Get idSo
     *
     * @return \Cacic\CommonBundle\Entity\So 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }

    /**
     * Set idRede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $idRede
     * @return Computador
     */
    public function setIdRede(\Cacic\CommonBundle\Entity\Rede $idRede = null)
    {
        $this->idRede = $idRede;
    
        return $this;
    }

    /**
     * Get idRede
     *
     * @return \Cacic\CommonBundle\Entity\Rede 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }
}