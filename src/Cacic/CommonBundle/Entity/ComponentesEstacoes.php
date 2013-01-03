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
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     */
    private $teNodeAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     */
    private $idSo;

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
     * Get idComponenteEstacao
     *
     * @return integer 
     */
    public function getIdComponenteEstacao()
    {
        return $this->idComponenteEstacao;
    }

    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return ComponentesEstacoes
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
     * @return ComponentesEstacoes
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
}