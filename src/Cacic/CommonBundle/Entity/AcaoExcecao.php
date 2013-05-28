<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AcaoExcecao
 */
class AcaoExcecao
{
    /**
     * @var string
     */
    private $teNodeAddress;

    /**
     * @var \Cacic\CommonBundle\Entity\Acao
     */
    private $acao;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $rede;


    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return AcaoExcecao
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
     * Set acao
     *
     * @param \Cacic\CommonBundle\Entity\Acao $acao
     * @return AcaoExcecao
     */
    public function setAcao(\Cacic\CommonBundle\Entity\Acao $acao)
    {
        $this->acao = $acao;
    
        return $this;
    }

    /**
     * Get acao
     *
     * @return \Cacic\CommonBundle\Entity\Acao 
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * Set rede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $rede
     * @return AcaoExcecao
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
}
