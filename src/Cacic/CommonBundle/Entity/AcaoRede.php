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
    private $idAcao;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $idRede;


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
     * Set idAcao
     *
     * @param \Cacic\CommonBundle\Entity\Acao $idAcao
     * @return AcaoRede
     */
    public function setIdAcao(\Cacic\CommonBundle\Entity\Acao $idAcao = null)
    {
        $this->idAcao = $idAcao;
    
        return $this;
    }

    /**
     * Get idAcao
     *
     * @return \Cacic\CommonBundle\Entity\Acao 
     */
    public function getIdAcao()
    {
        return $this->idAcao;
    }

    /**
     * Set idRede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $idRede
     * @return AcaoRede
     */
    public function setIdRede(\Cacic\CommonBundle\Entity\Rede $idRede = null)
    {
        $this->idRede = $idRede;
    
        return $this;
    }

    /**
     * Get idRede
     *
     * @return \Cacic\CommonBundle\Entity\Rede 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }
    /**
     * @var integer
     */
    private $idAcaoRede;


    /**
     * Get idAcaoRede
     *
     * @return integer 
     */
    public function getIdAcaoRede()
    {
        return $this->idAcaoRede;
    }
}