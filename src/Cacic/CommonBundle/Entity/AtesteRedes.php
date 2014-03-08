<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AtesteRedes
 */
class AtesteRedes
{
    /**
     * @var integer
     */
    private $estacoes;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $rede;

    /**
     * @var \Cacic\CommonBundle\Entity\Ateste
     */
    private $ateste;


    /**
     * Set estacoes
     *
     * @param integer $estacoes
     * @return AtesteRedes
     */
    public function setEstacoes($estacoes)
    {
        $this->estacoes = $estacoes;
    
        return $this;
    }

    /**
     * Get estacoes
     *
     * @return integer 
     */
    public function getEstacoes()
    {
        return $this->estacoes;
    }

    /**
     * Set rede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $rede
     * @return AtesteRedes
     */
    public function setRede(\Cacic\CommonBundle\Entity\Rede $rede)
    {
        $this->rede = $rede;
    
        return $this;
    }

    /**
     * Get rede
     *
     * @return \Cacic\CommonBundle\Entity\Rede 
     */
    public function getRede()
    {
        return $this->rede;
    }

    /**
     * Set ateste
     *
     * @param \Cacic\CommonBundle\Entity\Ateste $ateste
     * @return AtesteRedes
     */
    public function setAteste(\Cacic\CommonBundle\Entity\Ateste $ateste)
    {
        $this->ateste = $ateste;
    
        return $this;
    }

    /**
     * Get ateste
     *
     * @return \Cacic\CommonBundle\Entity\Ateste 
     */
    public function getAteste()
    {
        return $this->ateste;
    }
}