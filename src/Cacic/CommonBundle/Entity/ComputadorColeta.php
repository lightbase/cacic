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
    private $teClassValues;

    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $idComputador;

    /**
     * @var \Cacic\CommonBundle\Entity\Classe
     */
    private $idClass;
    
    /**
     * @var array Valores organizados por Tags
     */
    private $tagValues = array();


    /**
     * 
     * Construtor da classe
     */
    public function __construct()
    {
    	if ( null !== $this->teClassValues )
    	{
    		preg_match_all( '#\[([^\/+?)\](.*?)\[\/.*?\]#', $this->teClassValues, $out );
    	}
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
     * Set teClassValues
     *
     * @param string $teClassValues
     * @return ComputadorColeta
     */
    public function setTeClassValues($teClassValues)
    {
        $this->teClassValues = $teClassValues;
    
        return $this;
    }

    /**
     * Get teClassValues
     *
     * @return string 
     */
    public function getTeClassValues()
    {
        return $this->teClassValues;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $idComputador
     * @return ComputadorColeta
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computador $idComputador = null)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computador 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
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