<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Officescan
 *
 * @ORM\Table(name="officescan")
 * @ORM\Entity
 */
class Officescan
{
    /**
     * @var string
     *
     * @ORM\Column(name="nu_versao_engine", type="string", length=10, nullable=true)
     */
    private $nuVersaoEngine;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_versao_pattern", type="string", length=10, nullable=true)
     */
    private $nuVersaoPattern;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_instalacao", type="datetime", nullable=true)
     */
    private $dtHrInstalacao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_coleta", type="datetime", nullable=true)
     */
    private $dtHrColeta;

    /**
     * @var string
     *
     * @ORM\Column(name="te_servidor", type="string", length=30, nullable=true)
     */
    private $teServidor;

    /**
     * @var string
     *
     * @ORM\Column(name="in_ativo", type="string", length=1, nullable=true)
     */
    private $inAtivo;

    /**
     * @var \Computadores
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;



    /**
     * Set nuVersaoEngine
     *
     * @param string $nuVersaoEngine
     * @return Officescan
     */
    public function setNuVersaoEngine($nuVersaoEngine)
    {
        $this->nuVersaoEngine = $nuVersaoEngine;
    
        return $this;
    }

    /**
     * Get nuVersaoEngine
     *
     * @return string 
     */
    public function getNuVersaoEngine()
    {
        return $this->nuVersaoEngine;
    }

    /**
     * Set nuVersaoPattern
     *
     * @param string $nuVersaoPattern
     * @return Officescan
     */
    public function setNuVersaoPattern($nuVersaoPattern)
    {
        $this->nuVersaoPattern = $nuVersaoPattern;
    
        return $this;
    }

    /**
     * Get nuVersaoPattern
     *
     * @return string 
     */
    public function getNuVersaoPattern()
    {
        return $this->nuVersaoPattern;
    }

    /**
     * Set dtHrInstalacao
     *
     * @param \DateTime $dtHrInstalacao
     * @return Officescan
     */
    public function setDtHrInstalacao($dtHrInstalacao)
    {
        $this->dtHrInstalacao = $dtHrInstalacao;
    
        return $this;
    }

    /**
     * Get dtHrInstalacao
     *
     * @return \DateTime 
     */
    public function getDtHrInstalacao()
    {
        return $this->dtHrInstalacao;
    }

    /**
     * Set dtHrColeta
     *
     * @param \DateTime $dtHrColeta
     * @return Officescan
     */
    public function setDtHrColeta($dtHrColeta)
    {
        $this->dtHrColeta = $dtHrColeta;
    
        return $this;
    }

    /**
     * Get dtHrColeta
     *
     * @return \DateTime 
     */
    public function getDtHrColeta()
    {
        return $this->dtHrColeta;
    }

    /**
     * Set teServidor
     *
     * @param string $teServidor
     * @return Officescan
     */
    public function setTeServidor($teServidor)
    {
        $this->teServidor = $teServidor;
    
        return $this;
    }

    /**
     * Get teServidor
     *
     * @return string 
     */
    public function getTeServidor()
    {
        return $this->teServidor;
    }

    /**
     * Set inAtivo
     *
     * @param string $inAtivo
     * @return Officescan
     */
    public function setInAtivo($inAtivo)
    {
        $this->inAtivo = $inAtivo;
    
        return $this;
    }

    /**
     * Get inAtivo
     *
     * @return string 
     */
    public function getInAtivo()
    {
        return $this->inAtivo;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return Officescan
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computadores $idComputador)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computadores 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }
}