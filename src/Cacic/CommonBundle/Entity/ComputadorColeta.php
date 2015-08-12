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
     * @var \DateTime
     */
    private $dtHrInclusao;

    /**
     * @var boolean
     */
    private $ativo;

    /**
     * @var \DateTime
     */
    private $dtHrExclusao;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $historico;

    /**
     * @var \Cacic\CommonBundle\Entity\ClasseColetaPropriedades
     */
    private $idClasseColetaPropriedade;

    /**
     * @var \Cacic\CommonBundle\Entity\ClassProperty
     */
    private $classProperty;

    /**
     * @var \Cacic\CommonBundle\Entity\Classe
     */
    private $idClass;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->historico = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set ativo
     *
     * @param boolean $ativo
     * @return ComputadorColeta
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return boolean 
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Set dtHrExclusao
     *
     * @param \DateTime $dtHrExclusao
     * @return ComputadorColeta
     */
    public function setDtHrExclusao($dtHrExclusao)
    {
        $this->dtHrExclusao = $dtHrExclusao;

        return $this;
    }

    /**
     * Get dtHrExclusao
     *
     * @return \DateTime 
     */
    public function getDtHrExclusao()
    {
        return $this->dtHrExclusao;
    }

    /**
     * Add historico
     *
     * @param \Cacic\CommonBundle\Entity\ComputadorColetaHistorico $historico
     * @return ComputadorColeta
     */
    public function addHistorico(\Cacic\CommonBundle\Entity\ComputadorColetaHistorico $historico)
    {
        $this->historico[] = $historico;

        return $this;
    }

    /**
     * Remove historico
     *
     * @param \Cacic\CommonBundle\Entity\ComputadorColetaHistorico $historico
     */
    public function removeHistorico(\Cacic\CommonBundle\Entity\ComputadorColetaHistorico $historico)
    {
        $this->historico->removeElement($historico);
    }

    /**
     * Get historico
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * Set idClasseColetaPropriedade
     *
     * @param \Cacic\CommonBundle\Entity\ClasseColetaPropriedades $idClasseColetaPropriedade
     * @return ComputadorColeta
     */
    public function setIdClasseColetaPropriedade(\Cacic\CommonBundle\Entity\ClasseColetaPropriedades $idClasseColetaPropriedade)
    {
        $this->idClasseColetaPropriedade = $idClasseColetaPropriedade;

        return $this;
    }

    /**
     * Get idClasseColetaPropriedade
     *
     * @return \Cacic\CommonBundle\Entity\ClasseColetaPropriedades 
     */
    public function getIdClasseColetaPropriedade()
    {
        return $this->idClasseColetaPropriedade;
    }

    /**
     * Set classProperty
     *
     * @param \Cacic\CommonBundle\Entity\ClassProperty $classProperty
     * @return ComputadorColeta
     */
    public function setClassProperty(\Cacic\CommonBundle\Entity\ClassProperty $classProperty)
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
}
