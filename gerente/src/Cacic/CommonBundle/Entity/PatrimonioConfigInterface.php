<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PatrimonioConfigInterface
 */
class PatrimonioConfigInterface
{
    /**
     * @var string
     */
    private $idEtiqueta;

    /**
     * @var integer
     */
    private $idLocal;

    /**
     * @var string
     */
    private $nmEtiqueta;

    /**
     * @var string
     */
    private $teEtiqueta;

    /**
     * @var string
     */
    private $inExibirEtiqueta;

    /**
     * @var string
     */
    private $teHelpEtiqueta;

    /**
     * @var string
     */
    private $tePluralEtiqueta;

    /**
     * @var string
     */
    private $nmCampoTabPatrimonio;

    /**
     * @var string
     */
    private $inDestacarDuplicidade;

    /**
     * @var string
     */
    private $inObrigatorio;


    /**
     * Set idEtiqueta
     *
     * @param string $idEtiqueta
     * @return PatrimonioConfigInterface
     */
    public function setIdEtiqueta($idEtiqueta)
    {
        $this->idEtiqueta = $idEtiqueta;
    
        return $this;
    }

    /**
     * Get idEtiqueta
     *
     * @return string 
     */
    public function getIdEtiqueta()
    {
        return $this->idEtiqueta;
    }

    /**
     * Set idLocal
     *
     * @param integer $idLocal
     * @return PatrimonioConfigInterface
     */
    public function setIdLocal($idLocal)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return integer 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set nmEtiqueta
     *
     * @param string $nmEtiqueta
     * @return PatrimonioConfigInterface
     */
    public function setNmEtiqueta($nmEtiqueta)
    {
        $this->nmEtiqueta = $nmEtiqueta;
    
        return $this;
    }

    /**
     * Get nmEtiqueta
     *
     * @return string 
     */
    public function getNmEtiqueta()
    {
        return $this->nmEtiqueta;
    }

    /**
     * Set teEtiqueta
     *
     * @param string $teEtiqueta
     * @return PatrimonioConfigInterface
     */
    public function setTeEtiqueta($teEtiqueta)
    {
        $this->teEtiqueta = $teEtiqueta;
    
        return $this;
    }

    /**
     * Get teEtiqueta
     *
     * @return string 
     */
    public function getTeEtiqueta()
    {
        return $this->teEtiqueta;
    }

    /**
     * Set inExibirEtiqueta
     *
     * @param string $inExibirEtiqueta
     * @return PatrimonioConfigInterface
     */
    public function setInExibirEtiqueta($inExibirEtiqueta)
    {
        $this->inExibirEtiqueta = $inExibirEtiqueta;
    
        return $this;
    }

    /**
     * Get inExibirEtiqueta
     *
     * @return string 
     */
    public function getInExibirEtiqueta()
    {
        return $this->inExibirEtiqueta;
    }

    /**
     * Set teHelpEtiqueta
     *
     * @param string $teHelpEtiqueta
     * @return PatrimonioConfigInterface
     */
    public function setTeHelpEtiqueta($teHelpEtiqueta)
    {
        $this->teHelpEtiqueta = $teHelpEtiqueta;
    
        return $this;
    }

    /**
     * Get teHelpEtiqueta
     *
     * @return string 
     */
    public function getTeHelpEtiqueta()
    {
        return $this->teHelpEtiqueta;
    }

    /**
     * Set tePluralEtiqueta
     *
     * @param string $tePluralEtiqueta
     * @return PatrimonioConfigInterface
     */
    public function setTePluralEtiqueta($tePluralEtiqueta)
    {
        $this->tePluralEtiqueta = $tePluralEtiqueta;
    
        return $this;
    }

    /**
     * Get tePluralEtiqueta
     *
     * @return string 
     */
    public function getTePluralEtiqueta()
    {
        return $this->tePluralEtiqueta;
    }

    /**
     * Set nmCampoTabPatrimonio
     *
     * @param string $nmCampoTabPatrimonio
     * @return PatrimonioConfigInterface
     */
    public function setNmCampoTabPatrimonio($nmCampoTabPatrimonio)
    {
        $this->nmCampoTabPatrimonio = $nmCampoTabPatrimonio;
    
        return $this;
    }

    /**
     * Get nmCampoTabPatrimonio
     *
     * @return string 
     */
    public function getNmCampoTabPatrimonio()
    {
        return $this->nmCampoTabPatrimonio;
    }

    /**
     * Set inDestacarDuplicidade
     *
     * @param string $inDestacarDuplicidade
     * @return PatrimonioConfigInterface
     */
    public function setInDestacarDuplicidade($inDestacarDuplicidade)
    {
        $this->inDestacarDuplicidade = $inDestacarDuplicidade;
    
        return $this;
    }

    /**
     * Get inDestacarDuplicidade
     *
     * @return string 
     */
    public function getInDestacarDuplicidade()
    {
        return $this->inDestacarDuplicidade;
    }

    /**
     * Set inObrigatorio
     *
     * @param string $inObrigatorio
     * @return PatrimonioConfigInterface
     */
    public function setInObrigatorio($inObrigatorio)
    {
        $this->inObrigatorio = $inObrigatorio;
    
        return $this;
    }

    /**
     * Get inObrigatorio
     *
     * @return string 
     */
    public function getInObrigatorio()
    {
        return $this->inObrigatorio;
    }
}