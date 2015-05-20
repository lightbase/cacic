<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aquisicao
 */
class Aquisicao
{
    /**
     * @var integer
     */
    private $idAquisicao;

    /**
     * @var \DateTime
     */
    private $dtAquisicao;

    /**
     * @var string
     */
    private $nrProcesso;

    /**
     * @var string
     */
    private $nmEmpresa;

    /**
     * @var string
     */
    private $nmProprietario;

    /**
     * @var string
     */
    private $nrNotafiscal;


    /**
     * Get idAquisicao
     *
     * @return integer 
     */
    public function getIdAquisicao()
    {
        return $this->idAquisicao;
    }

    /**
     * Set dtAquisicao
     *
     * @param \DateTime $dtAquisicao
     * @return Aquisicao
     */
    public function setDtAquisicao($dtAquisicao)
    {
        $this->dtAquisicao = $dtAquisicao;
    
        return $this;
    }

    /**
     * Get dtAquisicao
     *
     * @return \DateTime 
     */
    public function getDtAquisicao()
    {
        return $this->dtAquisicao;
    }

    /**
     * Set nrProcesso
     *
     * @param string $nrProcesso
     * @return Aquisicao
     */
    public function setNrProcesso($nrProcesso)
    {
        $this->nrProcesso = $nrProcesso;
    
        return $this;
    }

    /**
     * Get nrProcesso
     *
     * @return string 
     */
    public function getNrProcesso()
    {
        return $this->nrProcesso;
    }

    /**
     * Set nmEmpresa
     *
     * @param string $nmEmpresa
     * @return Aquisicao
     */
    public function setNmEmpresa($nmEmpresa)
    {
        $this->nmEmpresa = $nmEmpresa;
    
        return $this;
    }

    /**
     * Get nmEmpresa
     *
     * @return string 
     */
    public function getNmEmpresa()
    {
        return $this->nmEmpresa;
    }

    /**
     * Set nmProprietario
     *
     * @param string $nmProprietario
     * @return Aquisicao
     */
    public function setNmProprietario($nmProprietario)
    {
        $this->nmProprietario = $nmProprietario;
    
        return $this;
    }

    /**
     * Get nmProprietario
     *
     * @return string 
     */
    public function getNmProprietario()
    {
        return $this->nmProprietario;
    }

    /**
     * Set nrNotafiscal
     *
     * @param string $nrNotafiscal
     * @return Aquisicao
     */
    public function setNrNotafiscal($nrNotafiscal)
    {
        $this->nrNotafiscal = $nrNotafiscal;
    
        return $this;
    }

    /**
     * Get nrNotafiscal
     *
     * @return string 
     */
    public function getNrNotafiscal()
    {
        return $this->nrNotafiscal;
    }
    
    /**
     * 
     * Método mágico invocado sempre que um objto do tipo Aquisicao for referenciado em contexto de string
     */
    public function __toString()
    {
    	return $this->getNrProcesso();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $itens;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itens = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add itens
     *
     * @param \Cacic\CommonBundle\Entity\AquisicaoItem $itens
     * @return Aquisicao
     */
    public function addIten(\Cacic\CommonBundle\Entity\AquisicaoItem $itens)
    {
        $this->itens[] = $itens;
    
        return $this;
    }

    /**
     * Remove itens
     *
     * @param \Cacic\CommonBundle\Entity\AquisicaoItem $itens
     */
    public function removeIten(\Cacic\CommonBundle\Entity\AquisicaoItem $itens)
    {
        $this->itens->removeElement($itens);
    }

    /**
     * Get itens
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItens()
    {
        return $this->itens;
    }
}
