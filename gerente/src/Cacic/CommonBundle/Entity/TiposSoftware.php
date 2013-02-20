<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TiposSoftware
 *
 * @ORM\Table(name="tipos_software")
 * @ORM\Entity(repositoryClass="Cacic\CommonBundle\Entity\TiposSoftwareRepository")
 */
class TiposSoftware
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipo_software", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTipoSoftware;

    /**
     * @var string
     *
     * @ORM\Column(name="te_descricao_tipo_software", type="string", length=30, nullable=false)
     */
    private $teDescricaoTipoSoftware;



    /**
     * Get idTipoSoftware
     *
     * @return integer 
     */
    public function getIdTipoSoftware()
    {
        return $this->idTipoSoftware;
    }

    /**
     * Set teDescricaoTipoSoftware
     *
     * @param string $teDescricaoTipoSoftware
     * @return TiposSoftware
     */
    public function setTeDescricaoTipoSoftware($teDescricaoTipoSoftware)
    {
        $this->teDescricaoTipoSoftware = $teDescricaoTipoSoftware;
    
        return $this;
    }

    /**
     * Get teDescricaoTipoSoftware
     *
     * @return string 
     */
    public function getTeDescricaoTipoSoftware()
    {
        return $this->teDescricaoTipoSoftware;
    }
}