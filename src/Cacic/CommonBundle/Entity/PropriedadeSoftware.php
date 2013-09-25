<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PropriedadeSoftware
 */
class PropriedadeSoftware
{
    /**
     * @var string
     */
    private $idPropriedadeSoftware;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $displayVersion;

    /**
     * @var string
     */
    private $URLInfoAbout;

    /**
     * @var string
     */
    private $publisher;


    /**
     * Get idPropriedadeSoftware
     *
     * @return string 
     */
    public function getIdPropriedadeSoftware()
    {
        return $this->idPropriedadeSoftware;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return PropriedadeSoftware
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    
        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set displayVersion
     *
     * @param string $displayVersion
     * @return PropriedadeSoftware
     */
    public function setDisplayVersion($displayVersion)
    {
        $this->displayVersion = $displayVersion;
    
        return $this;
    }

    /**
     * Get displayVersion
     *
     * @return string 
     */
    public function getDisplayVersion()
    {
        return $this->displayVersion;
    }

    /**
     * Set URLInfoAbout
     *
     * @param string $uRLInfoAbout
     * @return PropriedadeSoftware
     */
    public function setURLInfoAbout($uRLInfoAbout)
    {
        $this->URLInfoAbout = $uRLInfoAbout;
    
        return $this;
    }

    /**
     * Get URLInfoAbout
     *
     * @return string 
     */
    public function getURLInfoAbout()
    {
        return $this->URLInfoAbout;
    }

    /**
     * Set publisher
     *
     * @param string $publisher
     * @return PropriedadeSoftware
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    
        return $this;
    }

    /**
     * Get publisher
     *
     * @return string 
     */
    public function getPublisher()
    {
        return $this->publisher;
    }
    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $computador;


    /**
     * Set computador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $computador
     * @return PropriedadeSoftware
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
     * @var \Cacic\CommonBundle\Entity\ClassProperty
     */
    private $classProperty;


    /**
     * Set classProperty
     *
     * @param \Cacic\CommonBundle\Entity\ClassProperty $classProperty
     * @return PropriedadeSoftware
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
     * @var \Cacic\CommonBundle\Entity\Software
     */
    private $software;


    /**
     * Set software
     *
     * @param \Cacic\CommonBundle\Entity\Software $software
     * @return PropriedadeSoftware
     */
    public function setSoftware(\Cacic\CommonBundle\Entity\Software $software = null)
    {
        $this->software = $software;
    
        return $this;
    }

    /**
     * Get software
     *
     * @return \Cacic\CommonBundle\Entity\Software 
     */
    public function getSoftware()
    {
        return $this->software;
    }
}