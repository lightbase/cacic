<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComponentesEstacoes
 *
 * @ORM\Table(name="componentes_estacoes")
 * @ORM\Entity
 */
class ComponentesEstacoes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_componente_estacao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idComponenteEstacao;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_tipo_componente", type="string", length=100, nullable=false)
     */
    private $csTipoComponente;

    /**
     * @var string
     *
     * @ORM\Column(name="te_valor", type="text", nullable=false)
     */
    private $teValor;

    /**
     * @var \Computadores
     *
     * @ORM\ManyToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;



    /**
     * Get idComponenteEstacao
     *
     * @return integer 
     */
    public function getIdComponenteEstacao()
    {
        return $this->idComponenteEstacao;
    }

    /**
     * Set csTipoComponente
     *
     * @param string $csTipoComponente
     * @return ComponentesEstacoes
     */
    public function setCsTipoComponente($csTipoComponente)
    {
        $this->csTipoComponente = $csTipoComponente;
    
        return $this;
    }

    /**
     * Get csTipoComponente
     *
     * @return string 
     */
    public function getCsTipoComponente()
    {
        return $this->csTipoComponente;
    }

    /**
     * Set teValor
     *
     * @param string $teValor
     * @return ComponentesEstacoes
     */
    public function setTeValor($teValor)
    {
        $this->teValor = $teValor;
    
        return $this;
    }

    /**
     * Get teValor
     *
     * @return string 
     */
    public function getTeValor()
    {
        return $this->teValor;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return ComponentesEstacoes
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computadores $idComputador = null)
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