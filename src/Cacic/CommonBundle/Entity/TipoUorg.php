<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoUorg
 */
class TipoUorg
{
    /**
     * @var integer
     */
    private $idTipoUorg;

    /**
     * @var string
     */
    private $nmTipoUorg;

    /**
     * @var string
     */
    private $teDescricao;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $uorgs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uorgs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get idTipoUorg
     *
     * @return integer 
     */
    public function getIdTipoUorg()
    {
        return $this->idTipoUorg;
    }

    /**
     * Set nmTipoUorg
     *
     * @param string $nmTipoUorg
     * @return TipoUorg
     */
    public function setNmTipoUorg($nmTipoUorg)
    {
        $this->nmTipoUorg = $nmTipoUorg;
    
        return $this;
    }

    /**
     * Get nmTipoUorg
     *
     * @return string 
     */
    public function getNmTipoUorg()
    {
        return $this->nmTipoUorg;
    }

    /**
     * Set teDescricao
     *
     * @param string $teDescricao
     * @return TipoUorg
     */
    public function setTeDescricao($teDescricao)
    {
        $this->teDescricao = $teDescricao;
    
        return $this;
    }

    /**
     * Get teDescricao
     *
     * @return string 
     */
    public function getTeDescricao()
    {
        return $this->teDescricao;
    }

    /**
     * Add uorgs
     *
     * @param \Cacic\CommonBundle\Entity\Uorg $uorgs
     * @return TipoUorg
     */
    public function addUorg(\Cacic\CommonBundle\Entity\Uorg $uorgs)
    {
        $this->uorgs[] = $uorgs;
    
        return $this;
    }

    /**
     * Remove uorgs
     *
     * @param \Cacic\CommonBundle\Entity\Uorg $uorgs
     */
    public function removeUorg(\Cacic\CommonBundle\Entity\Uorg $uorgs)
    {
        $this->uorgs->removeElement($uorgs);
    }

    /**
     * Get uorgs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUorgs()
    {
        return $this->uorgs;
    }
}