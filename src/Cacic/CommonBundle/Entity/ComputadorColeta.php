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
    /**
     * @var \DateTime
     */
    private $dtHrInclusao;


    /**
     * Set dtHrInclusao
     *
     * @param \DateTime $dtHrInclusao
     * @return ComputadorColeta
     */
    public function setDtHrInclusao($dtHrInclusao)
    {
        $this->dtHrInclusao = $dtHrInclusao;
    
        return $this;
    }

    /**
     * Get dtHrInclusao
     *
     * @return \DateTime 
     */
    public function getDtHrInclusao()
    {
        return $this->dtHrInclusao;
    }

    /**
     * Set idClass
     *
     * @param \Cacic\CommonBundle\Entity\Classe $idClass
     * @return ComputadorColeta
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
     * Identifica coleta do computador
     *
     * @return string
     */
    public function __toString() {
        return $this->classProperty.": ".$this->teClassPropertyValue."| Computador:".$this->computador->getIdComputador();
    }
}
