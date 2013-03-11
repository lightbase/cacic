<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RedeGrupoFtp
 */
class RedeGrupoFtp
{
    /**
     * @var integer
     */
    private $idFtp;

    /**
     * @var \DateTime
     */
    private $nuHoraInicio;

    /**
     * @var \DateTime
     */
    private $nuHoraFim;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $idRede;

    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $idComputador;


    /**
     * Get idFtp
     *
     * @return integer 
     */
    public function getIdFtp()
    {
        return $this->idFtp;
    }

    /**
     * Set nuHoraInicio
     *
     * @param \DateTime $nuHoraInicio
     * @return RedeGrupoFtp
     */
    public function setNuHoraInicio($nuHoraInicio)
    {
        $this->nuHoraInicio = $nuHoraInicio;
    
        return $this;
    }

    /**
     * Get nuHoraInicio
     *
     * @return \DateTime 
     */
    public function getNuHoraInicio()
    {
        return $this->nuHoraInicio;
    }

    /**
     * Set nuHoraFim
     *
     * @param \DateTime $nuHoraFim
     * @return RedeGrupoFtp
     */
    public function setNuHoraFim($nuHoraFim)
    {
        $this->nuHoraFim = $nuHoraFim;
    
        return $this;
    }

    /**
     * Get nuHoraFim
     *
     * @return \DateTime 
     */
    public function getNuHoraFim()
    {
        return $this->nuHoraFim;
    }

    /**
     * Set idRede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $idRede
     * @return RedeGrupoFtp
     */
    public function setIdRede(\Cacic\CommonBundle\Entity\Rede $idRede = null)
    {
        $this->idRede = $idRede;
    
        return $this;
    }

    /**
     * Get idRede
     *
     * @return \Cacic\CommonBundle\Entity\Rede 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $idComputador
     * @return RedeGrupoFtp
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
}