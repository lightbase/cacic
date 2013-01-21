<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Locais
 *
 * @ORM\Table(name="locais")
 * @ORM\Entity(repositoryClass="LocaisRepository")
 */
class Locais
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_local", type="string", length=100, nullable=false)
     */
    private $nmLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="sg_local", type="string", length=20, nullable=false)
     */
    private $sgLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="te_observacao", type="string", length=255, nullable=true)
     */
    private $teObservacao;

    /**
     * @var string
     *
     * @ORM\Column(name="dt_debug", type="string", length=8, nullable=true)
     */
    private $dtDebug;



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
     * Set nmLocal
     *
     * @param string $nmLocal
     * @return Locais
     */
    public function setNmLocal($nmLocal)
    {
        $this->nmLocal = $nmLocal;
    
        return $this;
    }

    /**
     * Get nmLocal
     *
     * @return string 
     */
    public function getNmLocal()
    {
        return $this->nmLocal;
    }

    /**
     * Set sgLocal
     *
     * @param string $sgLocal
     * @return Locais
     */
    public function setSgLocal($sgLocal)
    {
        $this->sgLocal = $sgLocal;
    
        return $this;
    }

    /**
     * Get sgLocal
     *
     * @return string 
     */
    public function getSgLocal()
    {
        return $this->sgLocal;
    }

    /**
     * Set teObservacao
     *
     * @param string $teObservacao
     * @return Locais
     */
    public function setTeObservacao($teObservacao)
    {
        $this->teObservacao = $teObservacao;
    
        return $this;
    }

    /**
     * Get teObservacao
     *
     * @return string 
     */
    public function getTeObservacao()
    {
        return $this->teObservacao;
    }

    /**
     * Set dtDebug
     *
     * @param string $dtDebug
     * @return Locais
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
}