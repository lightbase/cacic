<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicSessao
 */
class SrcacicSessao
{
    /**
     * @var integer
     */
    private $idSrcacicSessao;

    /**
     * @var \DateTime
     */
    private $dtHrInicioSessao;

    /**
     * @var string
     */
    private $nmAcessoUsuarioSrv;

    /**
     * @var string
     */
    private $nmCompletoUsuarioSrv;

    /**
     * @var string
     */
    private $teEmailUsuarioSrv;

    /**
     * @var \DateTime
     */
    private $dtHrUltimoContato;

    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $idComputador;


    /**
     * Get idSrcacicSessao
     *
     * @return integer 
     */
    public function getIdSrcacicSessao()
    {
        return $this->idSrcacicSessao;
    }

    /**
     * Set dtHrInicioSessao
     *
     * @param \DateTime $dtHrInicioSessao
     * @return SrcacicSessao
     */
    public function setDtHrInicioSessao($dtHrInicioSessao)
    {
        $this->dtHrInicioSessao = $dtHrInicioSessao;
    
        return $this;
    }

    /**
     * Get dtHrInicioSessao
     *
     * @return \DateTime 
     */
    public function getDtHrInicioSessao()
    {
        return $this->dtHrInicioSessao;
    }

    /**
     * Set nmAcessoUsuarioSrv
     *
     * @param string $nmAcessoUsuarioSrv
     * @return SrcacicSessao
     */
    public function setNmAcessoUsuarioSrv($nmAcessoUsuarioSrv)
    {
        $this->nmAcessoUsuarioSrv = $nmAcessoUsuarioSrv;
    
        return $this;
    }

    /**
     * Get nmAcessoUsuarioSrv
     *
     * @return string 
     */
    public function getNmAcessoUsuarioSrv()
    {
        return $this->nmAcessoUsuarioSrv;
    }

    /**
     * Set nmCompletoUsuarioSrv
     *
     * @param string $nmCompletoUsuarioSrv
     * @return SrcacicSessao
     */
    public function setNmCompletoUsuarioSrv($nmCompletoUsuarioSrv)
    {
        $this->nmCompletoUsuarioSrv = $nmCompletoUsuarioSrv;
    
        return $this;
    }

    /**
     * Get nmCompletoUsuarioSrv
     *
     * @return string 
     */
    public function getNmCompletoUsuarioSrv()
    {
        return $this->nmCompletoUsuarioSrv;
    }

    /**
     * Set teEmailUsuarioSrv
     *
     * @param string $teEmailUsuarioSrv
     * @return SrcacicSessao
     */
    public function setTeEmailUsuarioSrv($teEmailUsuarioSrv)
    {
        $this->teEmailUsuarioSrv = $teEmailUsuarioSrv;
    
        return $this;
    }

    /**
     * Get teEmailUsuarioSrv
     *
     * @return string 
     */
    public function getTeEmailUsuarioSrv()
    {
        return $this->teEmailUsuarioSrv;
    }

    /**
     * Set dtHrUltimoContato
     *
     * @param \DateTime $dtHrUltimoContato
     * @return SrcacicSessao
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
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $idComputador
     * @return SrcacicSessao
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computador $idComputador = null)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computador 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }
}