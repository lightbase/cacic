<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contas
 *
 * @ORM\Table(name="contas")
 * @ORM\Entity
 */
class Contas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_conta", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idConta;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_responsavel", type="string", length=30, nullable=false)
     */
    private $nmResponsavel;



    /**
     * Get idConta
     *
     * @return integer 
     */
    public function getIdConta()
    {
        return $this->idConta;
    }

    /**
     * Set nmResponsavel
     *
     * @param string $nmResponsavel
     * @return Contas
     */
    public function setNmResponsavel($nmResponsavel)
    {
        $this->nmResponsavel = $nmResponsavel;
    
        return $this;
    }

    /**
     * Get nmResponsavel
     *
     * @return string 
     */
    public function getNmResponsavel()
    {
        return $this->nmResponsavel;
    }
}