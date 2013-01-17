<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AplicativosRedes
 *
 * @ORM\Table(name="aplicativos_redes")
 * @ORM\Entity
 */
class AplicativosRedes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_rede", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRede;

    /**
     * @var \PerfisAplicativosMonitorados
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\OneToOne(targetEntity="PerfisAplicativosMonitorados")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_aplicativo", referencedColumnName="id_aplicativo")
     * })
     */
    private $idAplicativo;



    /**
     * Get idRede
     *
     * @return integer 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }

    /**
     * Set idAplicativo
     *
     * @param \Cacic\CommonBundle\Entity\PerfisAplicativosMonitorados $idAplicativo
     * @return AplicativosRedes
     */
    public function setIdAplicativo(\Cacic\CommonBundle\Entity\PerfisAplicativosMonitorados $idAplicativo)
    {
        $this->idAplicativo = $idAplicativo;
    
        return $this;
    }

    /**
     * Get idAplicativo
     *
     * @return \Cacic\CommonBundle\Entity\PerfisAplicativosMonitorados 
     */
    public function getIdAplicativo()
    {
        return $this->idAplicativo;
    }
}