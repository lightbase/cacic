<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfiguracaoPadrao
 */
class ConfiguracaoPadrao
{
    /**
     * @var integer
     */
    private $idConfiguracaoPadrao;

    /**
     * @var string
     */
    private $sgVariavel;

    /**
     * @var string
     */
    private $nmConfiguracao;

    /**
     * @var string
     */
    private $vlConfiguracao;


    /**
     * Get idConfiguracaoPadrao
     *
     * @return integer 
     */
    public function getIdConfiguracaoPadrao()
    {
        return $this->idConfiguracaoPadrao;
    }

    /**
     * Set sgVariavel
     *
     * @param string $sgVariavel
     * @return ConfiguracaoPadrao
     */
    public function setSgVariavel($sgVariavel)
    {
        $this->sgVariavel = $sgVariavel;
    
        return $this;
    }

    /**
     * Get sgVariavel
     *
     * @return string 
     */
    public function getSgVariavel()
    {
        return $this->sgVariavel;
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