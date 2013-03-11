<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classe
 */
class Classe
{
    /**
     * @var integer
     */
    private $idClass;

    /**
     * @var string
     */
    private $nmClassName;

    /**
     * @var string
     */
    private $teClassDescription;


    /**
     * Get idClass
     *
     * @return integer 
     */
    public function getIdClass()
    {
        return $this->idClass;
    }

    /**
     * Set nmClassName
     *
     * @param string $nmClassName
     * @return Classe
     */
    public function setNmClassName($nmClassName)
    {
        $this->nmClassName = $nmClassName;
    
        return $this;
    }

    /**
     * Get nmClassName
     *
     * @return string 
     */
    public function getNmClassName()
    {
        return $this->nmClassName;
    }

    /**
     * Set teClassDescription
     *
     * @param string $teClassDescription
     * @return Classe
     */
    public function setTeClassDescription($teClassDescription)
    {
        $this->teClassDescription = $teClassDescription;
    
        return $this;
    }

    /**
     * Get teClassDescription
     *
     * @return string 
     */
    public function getTeClassDescription()
    {
        return $this->teClassDescription;
    }
}