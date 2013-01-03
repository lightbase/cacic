<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TiposUnidadesDisco
 *
 * @ORM\Table(name="tipos_unidades_disco")
 * @ORM\Entity
 */
class TiposUnidadesDisco
{
    /**
     * @var string
     *
     * @ORM\Column(name="id_tipo_unid_disco", type="string", length=1, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTipoUnidDisco;

    /**
     * @var string
     *
     * @ORM\Column(name="te_tipo_unid_disco", type="string", length=25, nullable=true)
     */
    private $teTipoUnidDisco;



    /**
     * Get idTipoUnidDisco
     *
     * @return string 
     */
    public function getIdTipoUnidDisco()
    {
        return $this->idTipoUnidDisco;
    }

    /**
     * Set teTipoUnidDisco
     *
     * @param string $teTipoUnidDisco
     * @return TiposUnidadesDisco
     */
    public function setTeTipoUnidDisco($teTipoUnidDisco)
    {
        $this->teTipoUnidDisco = $teTipoUnidDisco;
    
        return $this;
    }

    /**
     * Get teTipoUnidDisco
     *
     * @return string 
     */
    public function getTeTipoUnidDisco()
    {
        return $this->teTipoUnidDisco;
    }
}