<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfiguracaoPadrao
 *
 * @ORM\Table(name="configuracao_padrao")
 * @ORM\Entity(repositoryClass="Cacic\CommonBundle\Entity\ConfiguracaoPadraoRepository")
 */
class ConfiguracaoPadrao
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_configuracao_padrao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idConfiguracaoPadrao;

    /**
     * @var string
     *
     * @ORM\Column(name="sg_variavel", type="string", length=50, nullable=false)
     */
    private $sgVariavel;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_configuracao_padrao", type="string", length=100, nullable=false)
     */
    private $nmConfiguracaoPadrao;

    /**
     * @var string
     *
     * @ORM\Column(name="vl_configuracao_padrao", type="text", nullable=true)
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