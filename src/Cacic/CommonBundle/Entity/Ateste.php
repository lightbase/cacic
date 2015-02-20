<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ateste
 */
class Ateste
{
    /**
     * @var integer
     */
    private $idAteste;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var integer
     */
    private $quantidade;

    /**
     * @var string
     */
    private $detalhes;

    /**
     * @var boolean
     */
    private $atestado;

    /**
     * @var \DateTime
     */
    private $data;

    /**
     * @var string
     */
    private $qualidadeServico;


    /**
     * Get idAteste
     *
     * @return integer 
     */
    public function getIdAteste()
    {
        return $this->idAteste;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     * @return Ateste
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    
        return $this;
    }

    /**
     * Get descricao
     *
     * @return string 
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set quantidade
     *
     * @param integer $quantidade
     * @return Ateste
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    
        return $this;
    }

    /**
     * Get quantidade
     *
     * @return integer 
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set detalhes
     *
     * @param string $detalhes
     * @return Ateste
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;
    
        return $this;
    }

    /**
     * Get detalhes
     *
     * @return string 
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }

    /**
     * Set atestado
     *
     * @param boolean $atestado
     * @return Ateste
     */
    public function setAtestado($atestado)
    {
        $this->atestado = $atestado;
    
        return $this;
    }

    /**
     * Get atestado
     *
     * @return boolean 
     */
    public function getAtestado()
    {
        return $this->atestado;
    }

    /**
     * Set data
     *
     * @param \DateTime $data
     * @return Ateste
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set qualidadeServico
     *
     * @param string $qualidadeServico
     * @return Ateste
     */
    public function setQualidadeServico($qualidadeServico)
    {
        $this->qualidadeServico = $qualidadeServico;
    
        return $this;
    }

    /**
     * Get qualidadeServico
     *
     * @return string 
     */
    public function getQualidadeServico()
    {
        return $this->qualidadeServico;
    }
    /**
     * @var \Cacic\CommonBundle\Entity\Usuario
     */
    private $usuario;


    /**
     * Set usuario
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $usuario
     * @return Ateste
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
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $rede;


    /**
     * Set rede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $rede
     * @return Ateste
     */
    public function setRede(\Cacic\CommonBundle\Entity\Rede $rede = null)
    {
        $this->rede = $rede;
    
        return $this;
    }

    /**
     * Get rede
     *
     * @return \Cacic\CommonBundle\Entity\Rede 
     */
    public function getRede()
    {
        return $this->rede;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $redes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->redes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add redes
     *
     * @param \Cacic\CommonBundle\Entity\Rede $redes
     * @return Ateste
     */
    public function addRede(\Cacic\CommonBundle\Entity\Rede $redes)
    {
        $this->redes[] = $redes;
    
        return $this;
    }

    /**
     * Remove redes
     *
     * @param \Cacic\CommonBundle\Entity\Rede $redes
     */
    public function removeRede(\Cacic\CommonBundle\Entity\Rede $redes)
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