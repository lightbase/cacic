<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DescricaoColunaComputador
 */
class DescricaoColunaComputador
{
    /**
     * @var string
     */
    private $teSource;

    /**
     * @var string
     */
    private $teTarget;

    /**
     * @var string
     */
    private $teDescription;

    /**
     * @var string
     */
    private $csCondicaoPesquisa;


    /**
     * Set teSource
     *
     * @param string $teSource
     * @return DescricaoColunaComputador
     */
    public function setTeSource($teSource)
    {
        $this->teSource = $teSource;
    
        return $this;
    }

    /**
     * Get teSource
     *
     * @return string 
     */
    public function getTeSource()
    {
        return $this->teSource;
    }

    /**
     * Set teTarget
     *
     * @param string $teTarget
     * @return DescricaoColunaComputador
     */
    public function setTeTarget($teTarget)
    {
        $this->teTarget = $teTarget;
    
        return $this;
    }

    /**
     * Get teTarget
     *
     * @return string 
     */
    public function getTeTarget()
    {
        return $this->teTarget;
    }

    /**
     * Set teDescription
     *
     * @param string $teDescription
     * @return DescricaoColunaComputador
     */
    public function setTeDescription($teDescription)
    {
        $this->teDescription = $teDescription;
    
        return $this;
    }

    /**
     * Get teDescription
     *
     * @return string 
     */
    public function getTeDescription()
    {
        return $this->teDescription;
    }

    /**
     * Set csCondicaoPesquisa
     *
     * @param string $csCondicaoPesquisa
     * @return DescricaoColunaComputador
     */
    public function setCsCondicaoPesquisa($csCondicaoPesquisa)
    {
        $this->csCondicaoPesquisa = $csCondicaoPesquisa;
    
        return $this;
    }

    /**
     * Get csCondicaoPesquisa
     *
     * @return string 
     */
    public function getCsCondicaoPesquisa()
    {
        return $this->csCondicaoPesquisa;
    }
}
