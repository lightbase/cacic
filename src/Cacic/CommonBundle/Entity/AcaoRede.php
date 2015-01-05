<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AcaoRede
 */
class AcaoRede
{
    /**
     * @var \DateTime
     */
    private $dtHrColetaForcada;

    /**
     * @var \Cacic\CommonBundle\Entity\Acao
     */
    private $acao;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $rede;


    /**
     * Set dtHrColetaForcada
     *
     * @param \DateTime $dtHrColetaForcada
     * @return AcaoRede
     */
    public function setDtHrColetaForcada($dtHrColetaForcada)
    {
        $this->dtHrColetaForcada = $dtHrColetaForcada;
    
        return $this;
    }

    /**
     * Get dtHrColetaForcada
     *
     * @return \DateTime 
     */
    public function getDtHrColetaForcada()
    {
        return $this->dtHrColetaForcada;
    }

    /**
     * Set acao
     *
     * @param \Cacic\CommonBundle\Entity\Acao $acao
     * @return AcaoRede
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
     * @return AcaoRede
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
