<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnidOrganizacionalNivel1a
 */
class UnidOrganizacionalNivel1a
{
    /**
     * @var integer
     */
    private $idUnidOrganizacionalNivel1a;

    /**
     * @var string
     */
    private $nmUnidOrganizacionalNivel1a;

    /**
     * @var \Cacic\CommonBundle\Entity\UnidOrganizacionalNivel1
     */
    private $idUnidOrganizacionalNivel1;


    /**
     * Get idUnidOrganizacionalNivel1a
     *
     * @return integer 
     */
    public function getIdUnidOrganizacionalNivel1a()
    {
        return $this->idUnidOrganizacionalNivel1a;
    }

    /**
     * Set nmUnidOrganizacionalNivel1a
     *
     * @param string $nmUnidOrganizacionalNivel1a
     * @return UnidOrganizacionalNivel1a
     */
    public function setNmUnidOrganizacionalNivel1a($nmUnidOrganizacionalNivel1a)
    {
        $this->nmUnidOrganizacionalNivel1a = $nmUnidOrganizacionalNivel1a;
    
        return $this;
    }

    /**
     * Get nmUnidOrganizacionalNivel1a
     *
     * @return string 
     */
    public function getNmUnidOrganizacionalNivel1a()
    {
        return $this->nmUnidOrganizacionalNivel1a;
    }

    /**
     * Set idUnidOrganizacionalNivel1
     *
     * @param \Cacic\CommonBundle\Entity\UnidOrganizacionalNivel1 $idUnidOrganizacionalNivel1
     * @return UnidOrganizacionalNivel1a
     */
    public function setIdUnidOrganizacionalNivel1(\Cacic\CommonBundle\Entity\UnidOrganizacionalNivel1 $idUnidOrganizacionalNivel1 = null)
    {
        $this->idUnidOrganizacionalNivel1 = $idUnidOrganizacionalNivel1;
    
        return $this;
    }

    /**
     * Get idUnidOrganizacionalNivel1
     *
     * @return \Cacic\CommonBundle\Entity\UnidOrganizacionalNivel1 
     */
    public function getIdUnidOrganizacionalNivel1()
    {
        return $this->idUnidOrganizacionalNivel1;
    }
}