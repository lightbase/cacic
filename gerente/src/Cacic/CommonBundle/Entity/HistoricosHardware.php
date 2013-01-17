<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricosHardware
 *
 * @ORM\Table(name="historicos_hardware")
 * @ORM\Entity
 */
class HistoricosHardware
{
    /**
     * @var string
     *
     * @ORM\Column(name="campo_alterado", type="string", length=45, nullable=true)
     */
    private $campoAlterado;

    /**
     * @var string
     *
     * @ORM\Column(name="valor_antigo", type="string", length=45, nullable=true)
     */
    private $valorAntigo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_anterior", type="datetime", nullable=true)
     */
    private $dataAnterior;

    /**
     * @var string
     *
     * @ORM\Column(name="novo_valor", type="string", length=45, nullable=true)
     */
    private $novoValor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nova_data", type="datetime", nullable=true)
     */
    private $novaData;

    /**
     * @var \Computadores
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;



    /**
     * Set campoAlterado
     *
     * @param string $campoAlterado
     * @return HistoricosHardware
     */
    public function setCampoAlterado($campoAlterado)
    {
        $this->campoAlterado = $campoAlterado;
    
        return $this;
    }

    /**
     * Get campoAlterado
     *
     * @return string 
     */
    public function getCampoAlterado()
    {
        return $this->campoAlterado;
    }

    /**
     * Set valorAntigo
     *
     * @param string $valorAntigo
     * @return HistoricosHardware
     */
    public function setValorAntigo($valorAntigo)
    {
        $this->valorAntigo = $valorAntigo;
    
        return $this;
    }

    /**
     * Get valorAntigo
     *
     * @return string 
     */
    public function getValorAntigo()
    {
        return $this->valorAntigo;
    }

    /**
     * Set dataAnterior
     *
     * @param \DateTime $dataAnterior
     * @return HistoricosHardware
     */
    public function setDataAnterior($dataAnterior)
    {
        $this->dataAnterior = $dataAnterior;
    
        return $this;
    }

    /**
     * Get dataAnterior
     *
     * @return \DateTime 
     */
    public function getDataAnterior()
    {
        return $this->dataAnterior;
    }

    /**
     * Set novoValor
     *
     * @param string $novoValor
     * @return HistoricosHardware
     */
    public function setNovoValor($novoValor)
    {
        $this->novoValor = $novoValor;
    
        return $this;
    }

    /**
     * Get novoValor
     *
     * @return string 
     */
    public function getNovoValor()
    {
        return $this->novoValor;
    }

    /**
     * Set novaData
     *
     * @param \DateTime $novaData
     * @return HistoricosHardware
     */
    public function setNovaData($novaData)
    {
        $this->novaData = $novaData;
    
        return $this;
    }

    /**
     * Get novaData
     *
     * @return \DateTime 
     */
    public function getNovaData()
    {
        return $this->novaData;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return HistoricosHardware
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