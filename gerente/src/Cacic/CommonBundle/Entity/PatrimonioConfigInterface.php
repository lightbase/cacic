<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PatrimonioConfigInterface
 *
 * @ORM\Table(name="patrimonio_config_interface")
 * @ORM\Entity
 */
class PatrimonioConfigInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="id_etiqueta", type="string", length=30, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idEtiqueta;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_etiqueta", type="string", length=15, nullable=true)
     */
    private $nmEtiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="te_etiqueta", type="string", length=50, nullable=false)
     */
    private $teEtiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="in_exibir_etiqueta", type="string", length=1, nullable=true)
     */
    private $inExibirEtiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="te_help_etiqueta", type="string", length=100, nullable=true)
     */
    private $teHelpEtiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="te_plural_etiqueta", type="string", length=50, nullable=true)
     */
    private $tePluralEtiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_campo_tab_patrimonio", type="string", length=50, nullable=true)
     */
    private $nmCampoTabPatrimonio;

    /**
     * @var string
     *
     * @ORM\Column(name="in_destacar_duplicidade", type="string", length=1, nullable=true)
     */
    private $inDestacarDuplicidade;



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
}