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
     * @var integer
     *
     * @ORM\Column(name="id_computador", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idComputador;

    /**
     * @var string
     *
     * @ORM\Column(name="vl_variavel_ambiente", type="string", length=100, nullable=false)
     */
    private $vlVariavelAmbiente;

    /**
     * @var \VariaveisAmbiente
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\OneToOne(targetEntity="VariaveisAmbiente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_variavel_ambiente", referencedColumnName="id_variavel_ambiente")
     * })
     */
    private $idVariavelAmbiente;



    /**
     * Get idComputador
     *
     * @return integer 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
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

    /**
     * Set idVariavelAmbiente
     *
     * @param \Cacic\CommonBundle\Entity\VariaveisAmbiente $idVariavelAmbiente
     * @return VariaveisAmbienteEstacoes
     */
    public function setIdVariavelAmbiente(\Cacic\CommonBundle\Entity\VariaveisAmbiente $idVariavelAmbiente)
    {
        $this->idVariavelAmbiente = $idVariavelAmbiente;
    
        return $this;
    }

    /**
     * Get idVariavelAmbiente
     *
     * @return \Cacic\CommonBundle\Entity\VariaveisAmbiente 
     */
    public function getIdVariavelAmbiente()
    {
        return $this->idVariavelAmbiente;
    }
}