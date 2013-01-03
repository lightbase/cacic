<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VariaveisAmbiente
 *
 * @ORM\Table(name="variaveis_ambiente")
 * @ORM\Entity
 */
class VariaveisAmbiente
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_variavel_ambiente", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVariavelAmbiente;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_variavel_ambiente", type="string", length=100, nullable=false)
     */
    private $nmVariavelAmbiente;

    /**
     * @var string
     *
     * @ORM\Column(name="te_hash", type="string", length=40, nullable=false)
     */
    private $teHash;



    /**
     * Get idVariavelAmbiente
     *
     * @return integer 
     */
    public function getIdVariavelAmbiente()
    {
        return $this->idVariavelAmbiente;
    }

    /**
     * Set nmVariavelAmbiente
     *
     * @param string $nmVariavelAmbiente
     * @return VariaveisAmbiente
     */
    public function setNmVariavelAmbiente($nmVariavelAmbiente)
    {
        $this->nmVariavelAmbiente = $nmVariavelAmbiente;
    
        return $this;
    }

    /**
     * Get nmVariavelAmbiente
     *
     * @return string 
     */
    public function getNmVariavelAmbiente()
    {
        return $this->nmVariavelAmbiente;
    }

    /**
     * Set teHash
     *
     * @param string $teHash
     * @return VariaveisAmbiente
     */
    public function setTeHash($teHash)
    {
        $this->teHash = $teHash;
    
        return $this;
    }

    /**
     * Get teHash
     *
     * @return string 
     */
    public function getTeHash()
    {
        return $this->teHash;
    }
}