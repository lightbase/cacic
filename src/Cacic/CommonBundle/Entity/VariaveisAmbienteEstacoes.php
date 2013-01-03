<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VariaveisAmbienteEstacoes
 *
 * @ORM\Table(name="variaveis_ambiente_estacoes")
 * @ORM\Entity
 */
class VariaveisAmbienteEstacoes
{
    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $teNodeAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_variavel_ambiente", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idVariavelAmbiente;

    /**
     * @var string
     *
     * @ORM\Column(name="vl_variavel_ambiente", type="string", length=100, nullable=false)
     */
    private $vlVariavelAmbiente;



    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return VariaveisAmbienteEstacoes
     */
    public function setTeNodeAddress($teNodeAddress)
    {
        $this->teNodeAddress = $teNodeAddress;
    
        return $this;
    }

    /**
     * Get teNodeAddress
     *
     * @return string 
     */
    public function getTeNodeAddress()
    {
        return $this->teNodeAddress;
    }

    /**
     * Set idSo
     *
     * @param integer $idSo
     * @return VariaveisAmbienteEstacoes
     */
    public function setIdSo($idSo)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

    /**
     * Get idSo
     *
     * @return integer 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }

    /**
     * Set idVariavelAmbiente
     *
     * @param integer $idVariavelAmbiente
     * @return VariaveisAmbienteEstacoes
     */
    public function setIdVariavelAmbiente($idVariavelAmbiente)
    {
        $this->idVariavelAmbiente = $idVariavelAmbiente;
    
        return $this;
    }

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
     * Set vlVariavelAmbiente
     *
     * @param string $vlVariavelAmbiente
     * @return VariaveisAmbienteEstacoes
     */
    public function setVlVariavelAmbiente($vlVariavelAmbiente)
    {
        $this->vlVariavelAmbiente = $vlVariavelAmbiente;
    
        return $this;
    }

    /**
     * Get vlVariavelAmbiente
     *
     * @return string 
     */
    public function getVlVariavelAmbiente()
    {
        return $this->vlVariavelAmbiente;
    }
}