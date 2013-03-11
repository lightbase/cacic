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
    private $nmConfiguracaoPadrao;

    /**
     * @var string
     */
    private $vlConfiguracaoPadrao;


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
     * Set nmConfiguracaoPadrao
     *
     * @param string $nmConfiguracaoPadrao
     * @return ConfiguracaoPadrao
     */
    public function setNmConfiguracaoPadrao($nmConfiguracaoPadrao)
    {
        $this->nmConfiguracaoPadrao = $nmConfiguracaoPadrao;
    
        return $this;
    }

    /**
     * Get nmConfiguracaoPadrao
     *
     * @return string 
     */
    public function getNmConfiguracaoPadrao()
    {
        return $this->nmConfiguracaoPadrao;
    }

    /**
     * Set vlConfiguracaoPadrao
     *
     * @param string $vlConfiguracaoPadrao
     * @return ConfiguracaoPadrao
     */
    public function setVlConfiguracaoPadrao($vlConfiguracaoPadrao)
    {
        $this->vlConfiguracaoPadrao = $vlConfiguracaoPadrao;
    
        return $this;
    }

    /**
     * Get vlConfiguracaoPadrao
     *
     * @return string 
     */
    public function getVlConfiguracaoPadrao()
    {
        return $this->vlConfiguracaoPadrao;
    }
}