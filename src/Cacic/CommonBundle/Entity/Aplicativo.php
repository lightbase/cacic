<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aplicativo
 */
class Aplicativo
{
    /**
     * @var integer
     */
    private $idAplicativo;

    /**
     * @var string
     */
    private $nmAplicativo;

    /**
     * @var string
     */
    private $csCarInstW9x;

    /**
     * @var string
     */
    private $teCarInstW9x;

    /**
     * @var string
     */
    private $csCarVerW9x;

    /**
     * @var string
     */
    private $teCarVerW9x;

    /**
     * @var string
     */
    private $csCarInstWnt;

    /**
     * @var string
     */
    private $teCarInstWnt;

    /**
     * @var string
     */
    private $csCarVerWnt;

    /**
     * @var string
     */
    private $teCarVerWnt;

    /**
     * @var string
     */
    private $csIdeLicenca;

    /**
     * @var string
     */
    private $teIdeLicenca;

    /**
     * @var \DateTime
     */
    private $dtAtualizacao;

    /**
     * @var string
     */
    private $teArqVerEngW9x;

    /**
     * @var string
     */
    private $teArqVerPatW9x;

    /**
     * @var string
     */
    private $teArqVerEngWnt;

    /**
     * @var string
     */
    private $teArqVerPatWnt;

    /**
     * @var string
     */
    private $teDirPadraoW9x;

    /**
     * @var string
     */
    private $teDirPadraoWnt;

    /**
     * @var string
     */
    private $teDescritivo;

    /**
     * @var string
     */
    private $inDisponibilizaInfo;

    /**
     * @var string
     */
    private $inDisponibilizaInfoUsuarioComum;

    /**
     * @var \DateTime
     */
    private $dtRegistro;

    /**
     * @var \Cacic\CommonBundle\Entity\So
     */
    private $idSo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $idRede;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idRede = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nmAplicativo
     *
     * @param string $nmAplicativo
     * @return Aplicativo
     */
    public function setNmAplicativo($nmAplicativo)
    {
        $this->nmAplicativo = $nmAplicativo;
    
        return $this;
    }

    /**
     * Get nmAplicativo
     *
     * @return string 
     */
    public function getNmAplicativo()
    {
        return $this->nmAplicativo;
    }

    /**
     * Set csCarInstW9x
     *
     * @param string $csCarInstW9x
     * @return Aplicativo
     */
    public function setCsCarInstW9x($csCarInstW9x)
    {
        $this->csCarInstW9x = $csCarInstW9x;
    
        return $this;
    }

    /**
     * Get csCarInstW9x
     *
     * @return string 
     */
    public function getCsCarInstW9x()
    {
        return $this->csCarInstW9x;
    }

    /**
     * Set teCarInstW9x
     *
     * @param string $teCarInstW9x
     * @return Aplicativo
     */
    public function setTeCarInstW9x($teCarInstW9x)
    {
        $this->teCarInstW9x = $teCarInstW9x;
    
        return $this;
    }

    /**
     * Get teCarInstW9x
     *
     * @return string 
     */
    public function getTeCarInstW9x()
    {
        return $this->teCarInstW9x;
    }

    /**
     * Set csCarVerW9x
     *
     * @param string $csCarVerW9x
     * @return Aplicativo
     */
    public function setCsCarVerW9x($csCarVerW9x)
    {
        $this->csCarVerW9x = $csCarVerW9x;
    
        return $this;
    }

    /**
     * Get csCarVerW9x
     *
     * @return string 
     */
    public function getCsCarVerW9x()
    {
        return $this->csCarVerW9x;
    }

    /**
     * Set teCarVerW9x
     *
     * @param string $teCarVerW9x
     * @return Aplicativo
     */
    public function setTeCarVerW9x($teCarVerW9x)
    {
        $this->teCarVerW9x = $teCarVerW9x;
    
        return $this;
    }

    /**
     * Get teCarVerW9x
     *
     * @return string 
     */
    public function getTeCarVerW9x()
    {
        return $this->teCarVerW9x;
    }

    /**
     * Set csCarInstWnt
     *
     * @param string $csCarInstWnt
     * @return Aplicativo
     */
    public function setCsCarInstWnt($csCarInstWnt)
    {
        $this->csCarInstWnt = $csCarInstWnt;
    
        return $this;
    }

    /**
     * Get csCarInstWnt
     *
     * @return string 
     */
    public function getCsCarInstWnt()
    {
        return $this->csCarInstWnt;
    }

    /**
     * Set teCarInstWnt
     *
     * @param string $teCarInstWnt
     * @return Aplicativo
     */
    public function setTeCarInstWnt($teCarInstWnt)
    {
        $this->teCarInstWnt = $teCarInstWnt;
    
        return $this;
    }

    /**
     * Get teCarInstWnt
     *
     * @return string 
     */
    public function getTeCarInstWnt()
    {
        return $this->teCarInstWnt;
    }

    /**
     * Set csCarVerWnt
     *
     * @param string $csCarVerWnt
     * @return Aplicativo
     */
    public function setCsCarVerWnt($csCarVerWnt)
    {
        $this->csCarVerWnt = $csCarVerWnt;
    
        return $this;
    }

    /**
     * Get csCarVerWnt
     *
     * @return string 
     */
    public function getCsCarVerWnt()
    {
        return $this->csCarVerWnt;
    }

    /**
     * Set teCarVerWnt
     *
     * @param string $teCarVerWnt
     * @return Aplicativo
     */
    public function setTeCarVerWnt($teCarVerWnt)
    {
        $this->teCarVerWnt = $teCarVerWnt;
    
        return $this;
    }

    /**
     * Get teCarVerWnt
     *
     * @return string 
     */
    public function getTeCarVerWnt()
    {
        return $this->teCarVerWnt;
    }

    /**
     * Set csIdeLicenca
     *
     * @param string $csIdeLicenca
     * @return Aplicativo
     */
    public function setCsIdeLicenca($csIdeLicenca)
    {
        $this->csIdeLicenca = $csIdeLicenca;
    
        return $this;
    }

    /**
     * Get csIdeLicenca
     *
     * @return string 
     */
    public function getCsIdeLicenca()
    {
        return $this->csIdeLicenca;
    }

    /**
     * Set teIdeLicenca
     *
     * @param string $teIdeLicenca
     * @return Aplicativo
     */
    public function setTeIdeLicenca($teIdeLicenca)
    {
        $this->teIdeLicenca = $teIdeLicenca;
    
        return $this;
    }

    /**
     * Get teIdeLicenca
     *
     * @return string 
     */
    public function getTeIdeLicenca()
    {
        return $this->teIdeLicenca;
    }

    /**
     * Set dtAtualizacao
     *
     * @param \DateTime $dtAtualizacao
     * @return Aplicativo
     */
    public function setDtAtualizacao($dtAtualizacao)
    {
        $this->dtAtualizacao = $dtAtualizacao;
    
        return $this;
    }

    /**
     * Get dtAtualizacao
     *
     * @return \DateTime 
     */
    public function getDtAtualizacao()
    {
        return $this->dtAtualizacao;
    }

    /**
     * Set teArqVerEngW9x
     *
     * @param string $teArqVerEngW9x
     * @return Aplicativo
     */
    public function setTeArqVerEngW9x($teArqVerEngW9x)
    {
        $this->teArqVerEngW9x = $teArqVerEngW9x;
    
        return $this;
    }

    /**
     * Get teArqVerEngW9x
     *
     * @return string 
     */
    public function getTeArqVerEngW9x()
    {
        return $this->teArqVerEngW9x;
    }

    /**
     * Set teArqVerPatW9x
     *
     * @param string $teArqVerPatW9x
     * @return Aplicativo
     */
    public function setTeArqVerPatW9x($teArqVerPatW9x)
    {
        $this->teArqVerPatW9x = $teArqVerPatW9x;
    
        return $this;
    }

    /**
     * Get teArqVerPatW9x
     *
     * @return string 
     */
    public function getTeArqVerPatW9x()
    {
        return $this->teArqVerPatW9x;
    }

    /**
     * Set teArqVerEngWnt
     *
     * @param string $teArqVerEngWnt
     * @return Aplicativo
     */
    public function setTeArqVerEngWnt($teArqVerEngWnt)
    {
        $this->teArqVerEngWnt = $teArqVerEngWnt;
    
        return $this;
    }

    /**
     * Get teArqVerEngWnt
     *
     * @return string 
     */
    public function getTeArqVerEngWnt()
    {
        return $this->teArqVerEngWnt;
    }

    /**
     * Set teArqVerPatWnt
     *
     * @param string $teArqVerPatWnt
     * @return Aplicativo
     */
    public function setTeArqVerPatWnt($teArqVerPatWnt)
    {
        $this->teArqVerPatWnt = $teArqVerPatWnt;
    
        return $this;
    }

    /**
     * Get teArqVerPatWnt
     *
     * @return string 
     */
    public function getTeArqVerPatWnt()
    {
        return $this->teArqVerPatWnt;
    }

    /**
     * Set teDirPadraoW9x
     *
     * @param string $teDirPadraoW9x
     * @return Aplicativo
     */
    public function setTeDirPadraoW9x($teDirPadraoW9x)
    {
        $this->teDirPadraoW9x = $teDirPadraoW9x;
    
        return $this;
    }

    /**
     * Get teDirPadraoW9x
     *
     * @return string 
     */
    public function getTeDirPadraoW9x()
    {
        return $this->teDirPadraoW9x;
    }

    /**
     * Set teDirPadraoWnt
     *
     * @param string $teDirPadraoWnt
     * @return Aplicativo
     */
    public function setTeDirPadraoWnt($teDirPadraoWnt)
    {
        $this->teDirPadraoWnt = $teDirPadraoWnt;
    
        return $this;
    }

    /**
     * Get teDirPadraoWnt
     *
     * @return string 
     */
    public function getTeDirPadraoWnt()
    {
        return $this->teDirPadraoWnt;
    }

    /**
     * Set teDescritivo
     *
     * @param string $teDescritivo
     * @return Aplicativo
     */
    public function setTeDescritivo($teDescritivo)
    {
        $this->teDescritivo = $teDescritivo;
    
        return $this;
    }

    /**
     * Get teDescritivo
     *
     * @return string 
     */
    public function getTeDescritivo()
    {
        return $this->teDescritivo;
    }

    /**
     * Set inDisponibilizaInfo
     *
     * @param string $inDisponibilizaInfo
     * @return Aplicativo
     */
    public function setInDisponibilizaInfo($inDisponibilizaInfo)
    {
        $this->inDisponibilizaInfo = $inDisponibilizaInfo;
    
        return $this;
    }

    /**
     * Get inDisponibilizaInfo
     *
     * @return string 
     */
    public function getInDisponibilizaInfo()
    {
        return $this->inDisponibilizaInfo;
    }

    /**
     * Set inDisponibilizaInfoUsuarioComum
     *
     * @param string $inDisponibilizaInfoUsuarioComum
     * @return Aplicativo
     */
    public function setInDisponibilizaInfoUsuarioComum($inDisponibilizaInfoUsuarioComum)
    {
        $this->inDisponibilizaInfoUsuarioComum = $inDisponibilizaInfoUsuarioComum;
    
        return $this;
    }

    /**
     * Get inDisponibilizaInfoUsuarioComum
     *
     * @return string 
     */
    public function getInDisponibilizaInfoUsuarioComum()
    {
        return $this->inDisponibilizaInfoUsuarioComum;
    }

    /**
     * Set dtRegistro
     *
     * @param \DateTime $dtRegistro
     * @return Aplicativo
     */
    public function setDtRegistro($dtRegistro)
    {
        $this->dtRegistro = $dtRegistro;
    
        return $this;
    }

    /**
     * Get dtRegistro
     *
     * @return \DateTime 
     */
    public function getDtRegistro()
    {
        return $this->dtRegistro;
    }

    /**
     * Set idSo
     *
     * @param \Cacic\CommonBundle\Entity\So $idSo
     * @return Aplicativo
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
     * Add idRede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $idRede
     * @return Aplicativo
     */
    public function addIdRede(\Cacic\CommonBundle\Entity\Rede $idRede)
    {
        $this->idRede[] = $idRede;
    
        return $this;
    }

    /**
     * Remove idRede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $idRede
     */
    public function removeIdRede(\Cacic\CommonBundle\Entity\Rede $idRede)
    {
        $this->idRede->removeElement($idRede);
    }

    /**
     * Get idRede
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }
}
