<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AplicativosMonitorados
 *
 * @ORM\Table(name="aplicativos_monitorados")
 * @ORM\Entity
 */
class AplicativosMonitorados
{
    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $teNodeAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_aplicativo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idAplicativo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao", type="string", length=50, nullable=true)
     */
    private $teVersao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_licenca", type="string", length=50, nullable=true)
     */
    private $teLicenca;

    /**
     * @var string
     *
     * @ORM\Column(name="te_ver_engine", type="string", length=50, nullable=true)
     */
    private $teVerEngine;

    /**
     * @var string
     *
     * @ORM\Column(name="te_ver_pattern", type="string", length=50, nullable=true)
     */
    private $teVerPattern;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_instalado", type="string", length=1, nullable=true)
     */
    private $csInstalado;



    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return AplicativosMonitorados
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
     * Set idSo
     *
     * @param integer $idSo
     * @return AplicativosMonitorados
     */
    public function setIdSo($idSo)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

    /**
     * Get idSo
     *
     * @return integer 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }

    /**
     * Set idAplicativo
     *
     * @param integer $idAplicativo
     * @return AplicativosMonitorados
     */
    public function setIdAplicativo($idAplicativo)
    {
        $this->idAplicativo = $idAplicativo;
    
        return $this;
    }

    /**
     * Get idAplicativo
     *
     * @return integer 
     */
    public function getIdAplicativo()
    {
        return $this->idAplicativo;
    }

    /**
     * Set teVersao
     *
     * @param string $teVersao
     * @return AplicativosMonitorados
     */
    public function setTeVersao($teVersao)
    {
        $this->teVersao = $teVersao;
    
        return $this;
    }

    /**
     * Get teVersao
     *
     * @return string 
     */
    public function getTeVersao()
    {
        return $this->teVersao;
    }

    /**
     * Set teLicenca
     *
     * @param string $teLicenca
     * @return AplicativosMonitorados
     */
    public function setTeLicenca($teLicenca)
    {
        $this->teLicenca = $teLicenca;
    
        return $this;
    }

    /**
     * Get teLicenca
     *
     * @return string 
     */
    public function getTeLicenca()
    {
        return $this->teLicenca;
    }

    /**
     * Set teVerEngine
     *
     * @param string $teVerEngine
     * @return AplicativosMonitorados
     */
    public function setTeVerEngine($teVerEngine)
    {
        $this->teVerEngine = $teVerEngine;
    
        return $this;
    }

    /**
     * Get teVerEngine
     *
     * @return string 
     */
    public function getTeVerEngine()
    {
        return $this->teVerEngine;
    }

    /**
     * Set teVerPattern
     *
     * @param string $teVerPattern
     * @return AplicativosMonitorados
     */
    public function setTeVerPattern($teVerPattern)
    {
        $this->teVerPattern = $teVerPattern;
    
        return $this;
    }

    /**
     * Get teVerPattern
     *
     * @return string 
     */
    public function getTeVerPattern()
    {
        return $this->teVerPattern;
    }

    /**
     * Set csInstalado
     *
     * @param string $csInstalado
     * @return AplicativosMonitorados
     */
    public function setCsInstalado($csInstalado)
    {
        $this->csInstalado = $csInstalado;
    
        return $this;
    }

    /**
     * Get csInstalado
     *
     * @return string 
     */
    public function getCsInstalado()
    {
        return $this->csInstalado;
    }
}