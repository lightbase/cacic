<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfiguracaoLocal
 */
class ConfiguracaoLocal
{
    /**
     * @var string
     */
    private $vlConfiguracao;

    /**
     * @var \Cacic\CommonBundle\Entity\Local
     */
    private $idLocal;

    /**
     * @var \Cacic\CommonBundle\Entity\ConfiguracaoPadrao
     */
    private $idConfiguracaoPadrao;


    /**
     * Set vlConfiguracao
     *
     * @param string $vlConfiguracao
     * @return ConfiguracaoLocal
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

    /**
     * Set idLocal
     *
     * @param \Cacic\CommonBundle\Entity\Local $idLocal
     * @return ConfiguracaoLocal
     */
    public function setIdLocal(\Cacic\CommonBundle\Entity\Local $idLocal)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return \Cacic\CommonBundle\Entity\Local 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set idConfiguracaoPadrao
     *
     * @param \Cacic\CommonBundle\Entity\ConfiguracaoPadrao $idConfiguracaoPadrao
     * @return ConfiguracaoLocal
     */
    public function setIdConfiguracaoPadrao(\Cacic\CommonBundle\Entity\ConfiguracaoPadrao $idConfiguracaoPadrao)
    {
        $this->idConfiguracaoPadrao = $idConfiguracaoPadrao;
    
        return $this;
    }

    /**
     * Get idConfiguracaoPadrao
     *
     * @return \Cacic\CommonBundle\Entity\ConfiguracaoPadrao 
     */
    public function getIdConfiguracaoPadrao()
    {
        return $this->idConfiguracaoPadrao;
    }
}