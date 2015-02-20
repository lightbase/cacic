<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfiguracaoPadrao
 */
class ConfiguracaoPadrao
{
    /**
     * @var string
     */
    private $idConfiguracao;

    /**
     * @var string
     */
    private $nmConfiguracao;

    /**
     * @var string
     */
    private $vlConfiguracao;


    /**
     * Set idConfiguracao
     *
     * @param string $idConfiguracao
     * @return ConfiguracaoPadrao
     */
    public function setIdConfiguracao($idConfiguracao)
    {
        $this->idConfiguracao = $idConfiguracao;
    
        return $this;
    }

    /**
     * Get idConfiguracao
     *
     * @return string 
     */
    public function getIdConfiguracao()
    {
        return $this->idConfiguracao;
    }

    /**
     * Set nmConfiguracao
     *
     * @param string $nmConfiguracao
     * @return ConfiguracaoPadrao
     */
    public function setNmConfiguracao($nmConfiguracao)
    {
        $this->nmConfiguracao = $nmConfiguracao;
    
        return $this;
    }

    /**
     * Get nmConfiguracao
     *
     * @return string 
     */
    public function getNmConfiguracao()
    {
        return $this->nmConfiguracao;
    }

    /**
     * Set vlConfiguracao
     *
     * @param string $vlConfiguracao
     * @return ConfiguracaoPadrao
     */
    public function setVlConfiguracao($vlConfiguracao)
    {
        $this->vlConfiguracao = $vlConfiguracao;
    
        return $this;
    }

    /**
     * Get vlConfiguracao
     *
     * @return string 
     */
    public function getVlConfiguracao()
    {
        return $this->vlConfiguracao;
    }
}