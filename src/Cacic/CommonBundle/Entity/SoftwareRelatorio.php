<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoftwareRelatorio
 */
class SoftwareRelatorio
{
    /**
     * @var integer
     */
    private $idRelatorio;

    /**
     * @var string
     */
    private $nomeRelatorio;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $softwares;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->softwares = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idRelatorio
     *
     * @return integer 
     */
    public function getIdRelatorio()
    {
        return $this->idRelatorio;
    }

    /**
     * Set nomeRelatorio
     *
     * @param string $nomeRelatorio
     * @return SoftwareRelatorio
     */
    public function setNomeRelatorio($nomeRelatorio)
    {
        $this->nomeRelatorio = $nomeRelatorio;

        return $this;
    }

    /**
     * Get nomeRelatorio
     *
     * @return string 
     */
    public function getNomeRelatorio()
    {
        return $this->nomeRelatorio;
    }

    /**
     * Add softwares
     *
     * @param \Cacic\CommonBundle\Entity\Software $softwares
     * @return SoftwareRelatorio
     */
    public function addSoftware(\Cacic\CommonBundle\Entity\Software $softwares)
    {
        $this->softwares[] = $softwares;

        return $this;
    }

    /**
     * Remove softwares
     *
     * @param \Cacic\CommonBundle\Entity\Software $softwares
     */
    public function removeSoftware(\Cacic\CommonBundle\Entity\Software $softwares)
    {
        $this->softwares->removeElement($softwares);
    }

    /**
     * Get softwares
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSoftwares()
    {
        return $this->softwares;
    }
}
