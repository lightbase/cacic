<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComputadorColetaHistorico
 */
class ComputadorColetaHistorico
{
    /**
     * @var integer
     */
    private $idComputadorColetaHistorico;

    /**
     * @var \DateTime
     */
    private $dtHrInclusao;

    /**
     * @var \Cacic\CommonBundle\Entity\ComputadorColeta
     */
    private $idComputadorColeta;
    
    /**
     * @var string
     */
    private $teClassPropertyValue;

    /**
     * @var \Cacic\CommonBundle\Entity\ComputadorColeta
     */
    private $computadorColeta;

    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $computador;

    /**
     * @var \Cacic\CommonBundle\Entity\ClassProperty
     */
    private $classProperty;

    /**
     * Get idComputadorColetaHistorico
     *
     * @return integer 
     */
    public function getIdComputadorColetaHistorico()
    {
        return $this->idComputadorColetaHistorico;
    }

    /**
     * Set dtHrInclusao
     *
     * @param \DateTime $dtHrInclusao
     * @return ComputadorColetaHistorico
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
     * Set teClassPropertyValue
     *
     * @param string $teClassPropertyValue
     * @return ComputadorColetaHistorico
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
     * Set computadorColeta
     *
     * @param \Cacic\CommonBundle\Entity\ComputadorColeta $computadorColeta
     * @return ComputadorColetaHistorico
     */
    public function setComputadorColeta(\Cacic\CommonBundle\Entity\ComputadorColeta $computadorColeta = null)
    {
        $this->computadorColeta = $computadorColeta;
    
        return $this;
    }

    /**
     * Get computadorColeta
     *
     * @return \Cacic\CommonBundle\Entity\ComputadorColeta 
     */
    public function getComputadorColeta()
    {
        return $this->computadorColeta;
    }

    /**
     * Set computador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $computador
     * @return ComputadorColetaHistorico
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
     * @return ComputadorColetaHistorico
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
}