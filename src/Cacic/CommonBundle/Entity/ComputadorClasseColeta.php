<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComputadorClasseColeta
 */
class ComputadorClasseColeta
{
    /**
     * @var integer
     */
    private $idComputadorClasseColeta;

    /**
     * @var \DateTime
     */
    private $dtHrInclusao;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $classeColetaPropriedades;

    /**
     * @var \Cacic\CommonBundle\Entity\Classe
     */
    private $idClass;

    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $computador;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->classeColetaPropriedades = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idComputadorClasseColeta
     *
     * @return integer 
     */
    public function getIdComputadorClasseColeta()
    {
        return $this->idComputadorClasseColeta;
    }

    /**
     * Set dtHrInclusao
     *
     * @param \DateTime $dtHrInclusao
     * @return ComputadorClasseColeta
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
     * Add classeColetaPropriedades
     *
     * @param \Cacic\CommonBundle\Entity\ClasseColetaPropriedades $classeColetaPropriedades
     * @return ComputadorClasseColeta
     */
    public function addClasseColetaPropriedade(\Cacic\CommonBundle\Entity\ClasseColetaPropriedades $classeColetaPropriedades)
    {
        $this->classeColetaPropriedades[] = $classeColetaPropriedades;

        return $this;
    }

    /**
     * Remove classeColetaPropriedades
     *
     * @param \Cacic\CommonBundle\Entity\ClasseColetaPropriedades $classeColetaPropriedades
     */
    public function removeClasseColetaPropriedade(\Cacic\CommonBundle\Entity\ClasseColetaPropriedades $classeColetaPropriedades)
    {
        $this->classeColetaPropriedades->removeElement($classeColetaPropriedades);
    }

    /**
     * Get classeColetaPropriedades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClasseColetaPropriedades()
    {
        return $this->classeColetaPropriedades;
    }

    /**
     * Set idClass
     *
     * @param \Cacic\CommonBundle\Entity\Classe $idClass
     * @return ComputadorClasseColeta
     */
    public function setIdClass(\Cacic\CommonBundle\Entity\Classe $idClass)
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
     * Set computador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $computador
     * @return ComputadorClasseColeta
     */
    public function setComputador(\Cacic\CommonBundle\Entity\Computador $computador)
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
}
