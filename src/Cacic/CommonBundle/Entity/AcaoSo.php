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
    private $idAcao;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $idRede;

    /**
     * @var \Cacic\CommonBundle\Entity\So
     */
    private $idSo;


    /**
     * Set idAcao
     *
     * @param \Cacic\CommonBundle\Entity\Acao $idAcao
     * @return AcaoSo
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
     * @return AcaoSo
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
     * Set idSo
     *
     * @param \Cacic\CommonBundle\Entity\So $idSo
     * @return AcaoSo
     */
    public function setIdSo(\Cacic\CommonBundle\Entity\So $idSo = null)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

    /**
     * Get idSo
     *
     * @return \Cacic\CommonBundle\Entity\So 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }
    /**
     * @var integer
     */
    private $idAcaoSo;


    /**
     * Get idAcaoSo
     *
     * @return integer 
     */
    public function getIdAcaoSo()
    {
        return $this->idAcaoSo;
    }
}