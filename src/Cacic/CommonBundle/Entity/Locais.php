<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Locais
 *
 * @ORM\Table(name="locais")
 * @ORM\Entity(repositoryClass="Cacic\CommonBundle\Entity\LocaisRepository")
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
     * @ORM\OneToMany(targetEntity="Usuarios", mappedBy="localPrimario")
     */
    private $usuariosPrimarios;
    
    /**
     * @ORM\OneToMany(targetEntity="Redes", mappedBy="local")
     * @ORM\JoinColumn(name="id_local", referencedColumnName="id_local")
     */
    private $redes;

    /**
     * 
     * Construtor da Entidade
     */
    public function __construct()
    {
        $this->usuariosPrimarios = new ArrayCollection();
        $this->redes = new ArrayCollection();
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

    /**
     * Add usuariosPrimarios
     *
     * @param \Cacic\CommonBundle\Entity\Usuarios $usuariosPrimarios
     * @return Locais
     */
    public function addUsuariosPrimario(\Cacic\CommonBundle\Entity\Usuarios $usuariosPrimarios)
    {
        $this->usuariosPrimarios[] = $usuariosPrimarios;
    
        return $this;
    }

    /**
     * Remove usuariosPrimarios
     *
     * @param \Cacic\CommonBundle\Entity\Usuarios $usuariosPrimarios
     */
    public function removeUsuariosPrimario(\Cacic\CommonBundle\Entity\Usuarios $usuariosPrimarios)
    {
        $this->usuariosPrimarios->removeElement($usuariosPrimarios);
    }

    /**
     * Get usuariosPrimarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuariosPrimarios()
    {
        return $this->usuariosPrimarios;
    }

    /**
     * Add redes
     *
     * @param \Cacic\CommonBundle\Entity\Redes $redes
     * @return Locais
     */
    public function addRede(\Cacic\CommonBundle\Entity\Redes $redes)
    {
        $this->redes[] = $redes;
    
        return $this;
    }

    /**
     * Remove redes
     *
     * @param \Cacic\CommonBundle\Entity\Redes $redes
     */
    public function removeRede(\Cacic\CommonBundle\Entity\Redes $redes)
    {
        $this->redes->removeElement($redes);
    }

    /**
     * Get redes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRedes()
    {
        return $this->redes;
    }
}