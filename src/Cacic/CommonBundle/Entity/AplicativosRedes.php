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
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="id_ip_rede", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idIpRede;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_aplicativo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idAplicativo;



    /**
     * Set idLocal
     *
     * @param integer $idLocal
     * @return AplicativosRedes
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
     * @return AplicativosRedes
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
     * Set idAplicativo
     *
     * @param integer $idAplicativo
     * @return AplicativosRedes
     */
    public function setIdAplicativo($idAplicativo)
    {
        $this->idAplicativo = $idAplicativo;
    
        return $this;
    }

    /**
     * Get idAplicativo
     *
     * @return integer 
     */
    public function getIdAplicativo()
    {
        return $this->idAplicativo;
    }
}