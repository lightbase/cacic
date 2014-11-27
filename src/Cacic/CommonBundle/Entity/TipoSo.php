<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoSo
 */
class TipoSo
{
    /**
     * @var integer
     */
    private $idTipoSo;

    /**
     * @var string
     */
    private $tipo;


    /**
     * Get idTipoSo
     *
     * @return integer 
     */
    public function getIdTipoSo()
    {
        return $this->idTipoSo;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return TipoSo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $so;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->so = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add so
     *
     * @param \Cacic\CommonBundle\Entity\So $so
     * @return TipoSo
     */
    public function addSo(\Cacic\CommonBundle\Entity\So $so)
    {
        $this->so[] = $so;

        return $this;
    }

    /**
     * Remove so
     *
     * @param \Cacic\CommonBundle\Entity\So $so
     */
    public function removeSo(\Cacic\CommonBundle\Entity\So $so)
    {
        $this->so->removeElement($so);
    }

    /**
     * Get so
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSo()
    {
        return $this->so;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $redeVersaoModulo;


    /**
     * Add redeVersaoModulo
     *
     * @param \Cacic\CommonBundle\Entity\RedeVersaoModulo $redeVersaoModulo
     * @return TipoSo
     */
    public function addRedeVersaoModulo(\Cacic\CommonBundle\Entity\RedeVersaoModulo $redeVersaoModulo)
    {
        $this->redeVersaoModulo[] = $redeVersaoModulo;

        return $this;
    }

    /**
     * Remove redeVersaoModulo
     *
     * @param \Cacic\CommonBundle\Entity\RedeVersaoModulo $redeVersaoModulo
     */
    public function removeRedeVersaoModulo(\Cacic\CommonBundle\Entity\RedeVersaoModulo $redeVersaoModulo)
    {
        $this->redeVersaoModulo->removeElement($redeVersaoModulo);
    }

    /**
     * Get redeVersaoModulo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRedeVersaoModulo()
    {
        return $this->redeVersaoModulo;
    }
}
