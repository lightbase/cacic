<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnidOrganizacionalNivel1a
 *
 * @ORM\Table(name="unid_organizacional_nivel1a")
 * @ORM\Entity
 */
class UnidOrganizacionalNivel1a
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_unid_organizacional_nivel1a", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUnidOrganizacionalNivel1a;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_unid_organizacional_nivel1", type="integer", nullable=false)
     */
    private $idUnidOrganizacionalNivel1;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_unid_organizacional_nivel1a", type="string", length=50, nullable=true)
     */
    private $nmUnidOrganizacionalNivel1a;



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
     * Set idUnidOrganizacionalNivel1
     *
     * @param integer $idUnidOrganizacionalNivel1
     * @return UnidOrganizacionalNivel1a
     */
    public function setIdUnidOrganizacionalNivel1($idUnidOrganizacionalNivel1)
    {
        $this->idUnidOrganizacionalNivel1 = $idUnidOrganizacionalNivel1;
    
        return $this;
    }

    /**
     * Get idUnidOrganizacionalNivel1
     *
     * @return integer 
     */
    public function getIdUnidOrganizacionalNivel1()
    {
        return $this->idUnidOrganizacionalNivel1;
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
}