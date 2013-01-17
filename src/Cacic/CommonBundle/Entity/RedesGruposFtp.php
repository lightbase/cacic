<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RedesGruposFtp
 *
 * @ORM\Table(name="redes_grupos_ftp")
 * @ORM\Entity
 */
class RedesGruposFtp
{
    /**
     * @var integer
     *
     * @ORM\Column(name="nu_hora_inicio", type="integer", nullable=false)
     */
    private $nuHoraInicio;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_hora_fim", type="string", length=12, nullable=false)
     */
    private $nuHoraFim;

    /**
     * @var \Computadores
     *
     * @ORM\ManyToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;

    /**
     * @var \Redes
     *
     * @ORM\ManyToOne(targetEntity="Redes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_rede", referencedColumnName="id_rede")
     * })
     */
    private $idRede;

    /**
     * @var \RedesGruposFtp
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="RedesGruposFtp")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ftp", referencedColumnName="id_ftp")
     * })
     */
    private $idFtp;



    /**
     * Set nuHoraInicio
     *
     * @param integer $nuHoraInicio
     * @return RedesGruposFtp
     */
    public function setNuHoraInicio($nuHoraInicio)
    {
        $this->nuHoraInicio = $nuHoraInicio;
    
        return $this;
    }

    /**
     * Get nuHoraInicio
     *
     * @return integer 
     */
    public function getNuHoraInicio()
    {
        return $this->nuHoraInicio;
    }

    /**
     * Set nuHoraFim
     *
     * @param string $nuHoraFim
     * @return RedesGruposFtp
     */
    public function setNuHoraFim($nuHoraFim)
    {
        $this->nuHoraFim = $nuHoraFim;
    
        return $this;
    }

    /**
     * Get nuHoraFim
     *
     * @return string 
     */
    public function getNuHoraFim()
    {
        return $this->nuHoraFim;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return RedesGruposFtp
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computadores $idComputador = null)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computadores 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }

    /**
     * Set idRede
     *
     * @param \Cacic\CommonBundle\Entity\Redes $idRede
     * @return RedesGruposFtp
     */
    public function setIdRede(\Cacic\CommonBundle\Entity\Redes $idRede = null)
    {
        $this->idRede = $idRede;
    
        return $this;
    }

    /**
     * Get idRede
     *
     * @return \Cacic\CommonBundle\Entity\Redes 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }

    /**
     * Set idFtp
     *
     * @param \Cacic\CommonBundle\Entity\RedesGruposFtp $idFtp
     * @return RedesGruposFtp
     */
    public function setIdFtp(\Cacic\CommonBundle\Entity\RedesGruposFtp $idFtp)
    {
        $this->idFtp = $idFtp;
    
        return $this;
    }

    /**
     * Get idFtp
     *
     * @return \Cacic\CommonBundle\Entity\RedesGruposFtp 
     */
    public function getIdFtp()
    {
        return $this->idFtp;
    }
}