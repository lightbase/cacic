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
     * @ORM\Column(name="id_ftp", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFtp;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     */
    private $idLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="id_ip_rede", type="string", length=15, nullable=false)
     */
    private $idIpRede;

    /**
     * @var string
     *
     * @ORM\Column(name="id_ip_estacao", type="string", length=15, nullable=false)
     */
    private $idIpEstacao;

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
     * Get idFtp
     *
     * @return integer 
     */
    public function getIdFtp()
    {
        return $this->idFtp;
    }

    /**
     * Set idLocal
     *
     * @param integer $idLocal
     * @return RedesGruposFtp
     */
    public function setIdLocal($idLocal)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return integer 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set idIpRede
     *
     * @param string $idIpRede
     * @return RedesGruposFtp
     */
    public function setIdIpRede($idIpRede)
    {
        $this->idIpRede = $idIpRede;
    
        return $this;
    }

    /**
     * Get idIpRede
     *
     * @return string 
     */
    public function getIdIpRede()
    {
        return $this->idIpRede;
    }

    /**
     * Set idIpEstacao
     *
     * @param string $idIpEstacao
     * @return RedesGruposFtp
     */
    public function setIdIpEstacao($idIpEstacao)
    {
        $this->idIpEstacao = $idIpEstacao;
    
        return $this;
    }

    /**
     * Get idIpEstacao
     *
     * @return string 
     */
    public function getIdIpEstacao()
    {
        return $this->idIpEstacao;
    }

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
}