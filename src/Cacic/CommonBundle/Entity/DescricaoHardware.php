<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DescricaoHardware
 *
 * @ORM\Table(name="descricao_hardware")
 * @ORM\Entity
 */
class DescricaoHardware
{
    /**
     * @var string
     *
     * @ORM\Column(name="nm_campo_tab_hardware", type="string", length=45, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nmCampoTabHardware;

    /**
     * @var string
     *
     * @ORM\Column(name="te_desc_hardware", type="string", length=45, nullable=false)
     */
    private $teDescHardware;

    /**
     * @var string
     *
     * @ORM\Column(name="te_locais_notificacao_ativada", type="text", nullable=true)
     */
    private $teLocaisNotificacaoAtivada;



    /**
     * Get nmCampoTabHardware
     *
     * @return string 
     */
    public function getNmCampoTabHardware()
    {
        return $this->nmCampoTabHardware;
    }

    /**
     * Set teDescHardware
     *
     * @param string $teDescHardware
     * @return DescricaoHardware
     */
    public function setTeDescHardware($teDescHardware)
    {
        $this->teDescHardware = $teDescHardware;
    
        return $this;
    }

    /**
     * Get teDescHardware
     *
     * @return string 
     */
    public function getTeDescHardware()
    {
        return $this->teDescHardware;
    }

    /**
     * Set teLocaisNotificacaoAtivada
     *
     * @param string $teLocaisNotificacaoAtivada
     * @return DescricaoHardware
     */
    public function setTeLocaisNotificacaoAtivada($teLocaisNotificacaoAtivada)
    {
        $this->teLocaisNotificacaoAtivada = $teLocaisNotificacaoAtivada;
    
        return $this;
    }

    /**
     * Get teLocaisNotificacaoAtivada
     *
     * @return string 
     */
    public function getTeLocaisNotificacaoAtivada()
    {
        return $this->teLocaisNotificacaoAtivada;
    }
}