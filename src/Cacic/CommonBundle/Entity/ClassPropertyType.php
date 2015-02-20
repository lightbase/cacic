<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassPropertyType
 */
class ClassPropertyType
{
    /**
     * @var integer
     */
    private $idClassPropertyType;

    /**
     * @var string
     */
    private $csType;

    /**
     * @var string
     */
    private $teTypeDescription;

    /**
     * @var \Cacic\CommonBundle\Entity\ClassProperty
     */
    private $idClassProperty;


    /**
     * Get idClassPropertyType
     *
     * @return integer 
     */
    public function getIdClassPropertyType()
    {
        return $this->idClassPropertyType;
    }

    /**
     * Set csType
     *
     * @param string $csType
     * @return ClassPropertyType
     */
    public function setCsType($csType)
    {
        $this->csType = $csType;
    
        return $this;
    }

    /**
     * Get csType
     *
     * @return string 
     */
    public function getCsType()
    {
        return $this->csType;
    }

    /**
     * Set teTypeDescription
     *
     * @param string $teTypeDescription
     * @return ClassPropertyType
     */
    public function setTeTypeDescription($teTypeDescription)
    {
        $this->teTypeDescription = $teTypeDescription;
    
        return $this;
    }

    /**
     * Get teTypeDescription
     *
     * @return string 
     */
    public function getTeTypeDescription()
    {
        return $this->teTypeDescription;
    }

    /**
     * Set idClassProperty
     *
     * @param \Cacic\CommonBundle\Entity\ClassProperty $idClassProperty
     * @return ClassPropertyType
     */
    public function setIdClassProperty(\Cacic\CommonBundle\Entity\ClassProperty $idClassProperty = null)
    {
        $this->idClassProperty = $idClassProperty;
    
        return $this;
    }

    /**
     * Get idClassProperty
     *
     * @return \Cacic\CommonBundle\Entity\ClassProperty 
     */
    public function getIdClassProperty()
    {
        return $this->idClassProperty;
    }
}