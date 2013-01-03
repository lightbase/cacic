<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ComponentesEstacoesHistorico
 *
 * @ORM\Table(name="componentes_estacoes_historico")
 * @ORM\Entity
 */
class ComponentesEstacoesHistorico
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_componente_estacao_historico", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idComponenteEstacaoHistorico;

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
     * @ORM\Column(name="te_valor", type="string", length=200, nullable=false)
     */
    private $teValor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_alteracao", type="datetime", nullable=false)
     */
    private $dtAlteracao;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_tipo_alteracao", type="string", length=3, nullable=false)
     */
    private $csTipoAlteracao;



    /**
     * Get idComponenteEstacaoHistorico
     *
     * @return integer 
     */
    public function getIdComponenteEstacaoHistorico()
    {
        return $this->idComponenteEstacaoHistorico;
    }

    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return ComponentesEstacoesHistorico
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
     * @return ComponentesEstacoesHistorico
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
     * @return ComponentesEstacoesHistorico
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
     * @return ComponentesEstacoesHistorico
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
     * Set dtAlteracao
     *
     * @param \DateTime $dtAlteracao
     * @return ComponentesEstacoesHistorico
     */
    public function setDtAlteracao($dtAlteracao)
    {
        $this->dtAlteracao = $dtAlteracao;
    
        return $this;
    }

    /**
     * Get dtAlteracao
     *
     * @return \DateTime 
     */
    public function getDtAlteracao()
    {
        return $this->dtAlteracao;
    }

    /**
     * Set csTipoAlteracao
     *
     * @param string $csTipoAlteracao
     * @return ComponentesEstacoesHistorico
     */
    public function setCsTipoAlteracao($csTipoAlteracao)
    {
        $this->csTipoAlteracao = $csTipoAlteracao;
    
        return $this;
    }

    /**
     * Get csTipoAlteracao
     *
     * @return string 
     */
    public function getCsTipoAlteracao()
    {
        return $this->csTipoAlteracao;
    }
}