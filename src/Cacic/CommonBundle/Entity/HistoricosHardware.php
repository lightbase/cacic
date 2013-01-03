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
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return HistoricosHardware
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
     * @return HistoricosHardware
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
}