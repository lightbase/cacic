<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * So
 */
class So
{
    /**
     * @var integer
     */
    private $idSo;

    /**
     * @var string
     */
    private $teDescSo;

    /**
     * @var string
     */
    private $sgSo;

    /**
     * @var string
     */
    private $teSo;

    /**
     * @var string
     */
    private $inMswindows;


    /**
     * Get idSo
     *
     * @return integer 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }

    /**
     * Set teDescSo
     *
     * @param string $teDescSo
     * @return So
     */
    public function setTeDescSo($teDescSo)
    {
        $this->teDescSo = $teDescSo;
    
        return $this;
    }

    /**
     * Get teDescSo
     *
     * @return string 
     */
    public function getTeDescSo()
    {
        return $this->teDescSo;
    }

    /**
     * Set sgSo
     *
     * @param string $sgSo
     * @return So
     */
    public function setSgSo($sgSo)
    {
        $this->sgSo = $sgSo;
    
        return $this;
    }

    /**
     * Get sgSo
     *
     * @return string 
     */
    public function getSgSo()
    {
        return $this->sgSo;
    }

    /**
     * Set teSo
     *
     * @param string $teSo
     * @return So
     */
    public function setTeSo($teSo)
    {
        $this->teSo = $teSo;
    
        return $this;
    }

    /**
     * Get teSo
     *
     * @return string 
     */
    public function getTeSo()
    {
        return $this->teSo;
    }

    /**
     * Set inMswindows
     *
     * @param string $inMswindows
     * @return So
     */
    public function setInMswindows($inMswindows)
    {
        $this->inMswindows = $inMswindows;
    
        return $this;
    }

    /**
     * Get inMswindows
     *
     * @return string 
     */
    public function getInMswindows()
    {
        return $this->inMswindows;
    }
    
	/**
     * Set idSo
     *
     * @param int $idSo
     * @return So
     */
    public function setIdSo($idSo)
    {
        $this->idSo = $idSo;
    
        return $this;
    }
    
    /**
     * Método mágico invocado sempre que um objeto desta classe é referenciado em contexto de string
     * @return string
     */
    public function __toString()
    {
    	return $this->teDescSo . ' ('. $this->sgSo .')';
    }
    /**
     * @var \Cacic\CommonBundle\Entity\TipoSo
     */
    private $tipo;


    /**
     * Set tipo
     *
     * @param \Cacic\CommonBundle\Entity\TipoSo $tipo
     * @return So
     */
    public function setTipo(\Cacic\CommonBundle\Entity\TipoSo $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \Cacic\CommonBundle\Entity\TipoSo 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}