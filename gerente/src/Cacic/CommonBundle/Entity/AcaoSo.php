<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AcaoSo
 */
class AcaoSo
{
    /**
     * @var \Cacic\CommonBundle\Entity\Acao
     */
    private $acao;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $rede;

    /**
     * @var \Cacic\CommonBundle\Entity\So
     */
    private $so;


    /**
     * Set acao
     *
     * @param \Cacic\CommonBundle\Entity\Acao $acao
     * @return AcaoSo
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
     * @return AcaoSo
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
     * Set so
     *
     * @param \Cacic\CommonBundle\Entity\So $so
     * @return AcaoSo
     */
    public function setSo(\Cacic\CommonBundle\Entity\So $so)
    {
        $this->so = $so;
    
        return $this;
    }

    /**
     * Get so
     *
     * @return \Cacic\CommonBundle\Entity\So 
     */
    public function getSo()
    {
        return $this->so;
    }
}