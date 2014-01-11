<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComputadorColeta
 */
class ComputadorColeta
{
    /**
     * @var integer
     */
    private $idComputadorColeta;
    
    /**
     * @var string
     */
    private $teClassPropertyValue;
    
    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $computador;
    
    /**
     * @var \Cacic\CommonBundle\Entity\ClassProperty
     */
    private $classProperty;

    /**
     * @var \Cacic\CommonBundle\Entity\Classe
     */
    private $idClass;

    /**
     * Get idComputadorColeta
     *
     * @return integer 
     */
    public function getIdComputadorColeta()
    {
        return $this->idComputadorColeta;
    }

    /**
     * Set teClassPropertyValue
     *
     * @param string $teClassPropertyValue
     * @return ComputadorColeta
     */
    public function setTeClassPropertyValue($teClassPropertyValue)
    {
        $this->teClassPropertyValue = $teClassPropertyValue;
    
        return $this;
    }

    /**
     * Get teClassPropertyValue
     *
     * @return string 
     */
    public function getTeClassPropertyValue()
    {
        return $this->teClassPropertyValue;
    }

    /**
     * Set computador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $computador
     * @return ComputadorColeta
     */
    public function setComputador(\Cacic\CommonBundle\Entity\Computador $computador = null)
    {
        $this->computador = $computador;
    
        return $this;
    }

    /**
     * Get computador
     *
     * @return \Cacic\CommonBundle\Entity\Computador 
     */
    public function getComputador()
    {
        return $this->computador;
    }

    /**
     * Set classProperty
     *
     * @param \Cacic\CommonBundle\Entity\ClassProperty $classProperty
     * @return ComputadorColeta
     */
    public function setClassProperty(\Cacic\CommonBundle\Entity\ClassProperty $classProperty = null)
    {
        $this->classProperty = $classProperty;
    
        return $this;
    }

    /**
     * Get classProperty
     *
     * @return \Cacic\CommonBundle\Entity\ClassProperty 
     */
    public function getClassProperty()
    {
        return $this->classProperty;
    }

    /**
     * Set classProperty
     *
     * @param \Cacic\CommonBundle\Entity\ClassProperty $classProperty
     * @return ComputadorColeta
     */
    public function setClasse(\Cacic\CommonBundle\Entity\Classe $idClass = null)
    {
        $this->classProperty = $idClass;

        return $this;
    }

    /**
     * Get classProperty
     *
     * @return \Cacic\CommonBundle\Entity\ClassProperty
     */
    public function getClasse()
    {
        return $this->classe;
    }
}