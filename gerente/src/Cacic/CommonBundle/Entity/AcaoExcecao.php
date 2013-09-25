<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AcaoExcecao
 */
class AcaoExcecao
{
    /**
     * @var integer
     */
    private $idAcaoExcecao;

    /**
     * @var string
     */
    private $teNodeAddress;

    /**
     * @var \Cacic\CommonBundle\Entity\Acao
     */
    private $idAcao;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $idRede;


    /**
     * Get idAcaoExcecao
     *
     * @return integer 
     */
    public function getIdAcaoExcecao()
    {
        return $this->idAcaoExcecao;
    }

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
     * Set idAcao
     *
     * @param \Cacic\CommonBundle\Entity\Acao $idAcao
     * @return AcaoExcecao
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
     * @return AcaoExcecao
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
}