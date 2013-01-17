<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoftwaresInventariadosEstacoes
 *
 * @ORM\Table(name="softwares_inventariados_estacoes")
 * @ORM\Entity
 */
class SoftwaresInventariadosEstacoes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_software_inventariado", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSoftwareInventariado;

    /**
     * @var \Computadores
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\OneToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;



    /**
     * Get idSoftwareInventariado
     *
     * @return integer 
     */
    public function getIdSoftwareInventariado()
    {
        return $this->idSoftwareInventariado;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return SoftwaresInventariadosEstacoes
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computadores $idComputador)
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
}