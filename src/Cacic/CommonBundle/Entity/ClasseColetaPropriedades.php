<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClasseColetaPropriedades
 */
class ClasseColetaPropriedades
{
    /**
     * @var integer
     */
    private $idClasseColetaPropriedade;

    /**
     * @var integer
     */
    private $classIndex;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $coletas;

    /**
     * @var \Cacic\CommonBundle\Entity\ComputadorClasseColeta
     */
    private $idComputadorClasseColeta;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->coletas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idClasseColetaPropriedade
     *
     * @return integer 
     */
    public function getIdClasseColetaPropriedade()
    {
        return $this->idClasseColetaPropriedade;
    }

    /**
     * Set classIndex
     *
     * @param integer $classIndex
     * @return ClasseColetaPropriedades
     */
    public function setClassIndex($classIndex)
    {
        $this->classIndex = $classIndex;

        return $this;
    }

    /**
     * Get classIndex
     *
     * @return integer 
     */
    public function getClassIndex()
    {
        return $this->classIndex;
    }

    /**
     * Add coletas
     *
     * @param \Cacic\CommonBundle\Entity\ComputadorColeta $coletas
     * @return ClasseColetaPropriedades
     */
    public function addColeta(\Cacic\CommonBundle\Entity\ComputadorColeta $coletas)
    {
        $this->coletas[] = $coletas;

        return $this;
    }

    /**
     * Remove coletas
     *
     * @param \Cacic\CommonBundle\Entity\ComputadorColeta $coletas
     */
    public function removeColeta(\Cacic\CommonBundle\Entity\ComputadorColeta $coletas)
    {
        $this->coletas->removeElement($coletas);
    }

    /**
     * Get coletas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColetas()
    {
        return $this->coletas;
    }

    /**
     * Set idComputadorClasseColeta
     *
     * @param \Cacic\CommonBundle\Entity\ComputadorClasseColeta $idComputadorClasseColeta
     * @return ClasseColetaPropriedades
     */
    public function setIdComputadorClasseColeta(\Cacic\CommonBundle\Entity\ComputadorClasseColeta $idComputadorClasseColeta = null)
    {
        $this->idComputadorClasseColeta = $idComputadorClasseColeta;

        return $this;
    }

    /**
     * Get idComputadorClasseColeta
     *
     * @return \Cacic\CommonBundle\Entity\ComputadorClasseColeta 
     */
    public function getIdComputadorClasseColeta()
    {
        return $this->idComputadorClasseColeta;
    }
}
