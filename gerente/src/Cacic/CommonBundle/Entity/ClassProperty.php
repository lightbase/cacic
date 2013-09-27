<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassProperty
 */
class ClassProperty
{
    /**
     * @var integer
     */
    private $idClassProperty;

    /**
     * @var string
     */
    private $nmPropertyName;

    /**
     * @var string
     */
    private $tePropertyDescription;

    /**
     * @var string
     */
    private $nmFunctionPreDb;

    /**
     * @var string
     */
    private $nmFunctionPosDb;

    /**
     * @var \Cacic\CommonBundle\Entity\Classe
     */
    private $idClass;


    /**
     * Get idClassProperty
     *
     * @return integer 
     */
    public function getIdClassProperty()
    {
        return $this->idClassProperty;
    }

    /**
     * Set nmPropertyName
     *
     * @param string $nmPropertyName
     * @return ClassProperty
     */
    public function setNmPropertyName($nmPropertyName)
    {
        $this->nmPropertyName = $nmPropertyName;
    
        return $this;
    }

    /**
     * Get nmPropertyName
     *
     * @return string 
     */
    public function getNmPropertyName()
    {
        return $this->nmPropertyName;
    }

    /**
     * Set tePropertyDescription
     *
     * @param string $tePropertyDescription
     * @return ClassProperty
     */
    public function setTePropertyDescription($tePropertyDescription)
    {
        $this->tePropertyDescription = $tePropertyDescription;
    
        return $this;
    }

    /**
     * Get tePropertyDescription
     *
     * @return string 
     */
    public function getTePropertyDescription()
    {
        return $this->tePropertyDescription;
    }

    /**
     * Set nmFunctionPreDb
     *
     * @param string $nmFunctionPreDb
     * @return ClassProperty
     */
    public function setNmFunctionPreDb($nmFunctionPreDb)
    {
        $this->nmFunctionPreDb = $nmFunctionPreDb;
    
        return $this;
    }

    /**
     * Get nmFunctionPreDb
     *
     * @return string 
     */
    public function getNmFunctionPreDb()
    {
        return $this->nmFunctionPreDb;
    }

    /**
     * Set nmFunctionPosDb
     *
     * @param string $nmFunctionPosDb
     * @return ClassProperty
     */
    public function setNmFunctionPosDb($nmFunctionPosDb)
    {
        $this->nmFunctionPosDb = $nmFunctionPosDb;
    
        return $this;
    }

    /**
     * Get nmFunctionPosDb
     *
     * @return string 
     */
    public function getNmFunctionPosDb()
    {
        return $this->nmFunctionPosDb;
    }

    /**
     * Set idClass
     *
     * @param \Cacic\CommonBundle\Entity\Classe $idClass
     * @return ClassProperty
     */
    public function setIdClass(\Cacic\CommonBundle\Entity\Classe $idClass = null)
    {
        $this->idClass = $idClass;
    
        return $this;
    }

    /**
     * Get idClass
     *
     * @return \Cacic\CommonBundle\Entity\Classe 
     */
    public function getIdClass()
    {
        return $this->idClass;
    }

    /**
     * Método mágico invocado sempre que um objeto deste tipo é referenciado em contexto de string
     */
    public function __toString()
    {
    	return $this->nmPropertyName;
    }
}