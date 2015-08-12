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

    /**
     * Método mágico invocado sempre que um objeto deste tipo é referenciado em contexto de string
     */
    public function __toString()
    {
    	return $this->nmClassName;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $classe_coleta;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->classe_coleta = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add classe_coleta
     *
     * @param \Cacic\CommonBundle\Entity\ClasseColeta $classeColeta
     * @return Classe
     */
    public function addClasseColetum(\Cacic\CommonBundle\Entity\ClasseColeta $classeColeta)
    {
        $this->classe_coleta[] = $classeColeta;

        return $this;
    }

    /**
     * Remove classe_coleta
     *
     * @param \Cacic\CommonBundle\Entity\ClasseColeta $classeColeta
     */
    public function removeClasseColetum(\Cacic\CommonBundle\Entity\ClasseColeta $classeColeta)
    {
        $this->classe_coleta->removeElement($classeColeta);
    }

    /**
     * Get classe_coleta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClasseColeta()
    {
        return $this->classe_coleta;
    }
}
