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
     * @var string
     */
    private $teClassValues;

    /**
     * @var \DateTime
     */
    private $dtHrInclusao;

    /**
     * @var \Cacic\CommonBundle\Entity\ComputadorColeta
     */
    private $idComputadorColeta;

    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $idComputador;

    /**
     * @var \Cacic\CommonBundle\Entity\Classe
     */
    private $idClass;


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
     * Set teClassValues
     *
     * @param string $teClassValues
     * @return ComputadorColetaHistorico
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
     * Set idComputadorColeta
     *
     * @param \Cacic\CommonBundle\Entity\ComputadorColeta $idComputadorColeta
     * @return ComputadorColetaHistorico
     */
    public function setIdComputadorColeta(\Cacic\CommonBundle\Entity\ComputadorColeta $idComputadorColeta = null)
    {
        $this->idComputadorColeta = $idComputadorColeta;
    
        return $this;
    }

    /**
     * Get idComputadorColeta
     *
     * @return \Cacic\CommonBundle\Entity\ComputadorColeta 
     */
    public function getIdComputadorColeta()
    {
        return $this->idComputadorColeta;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $idComputador
     * @return ComputadorColetaHistorico
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
     * @return ComputadorColetaHistorico
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