<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 */
class Log
{
    /**
     * @var integer
     */
    private $idLog;

    /**
     * @var \DateTime
     */
    private $dtAcao;

    /**
     * @var string
     */
    private $csAcao;

    /**
     * @var string
     */
    private $nmScript;

    /**
     * @var string
     */
    private $nmTabela;

    /**
     * @var string
     */
    private $teIpOrigem;

    /**
     * @var \Cacic\CommonBundle\Entity\Usuario
     */
    private $idUsuario;


    /**
     * Get idLog
     *
     * @return integer 
     */
    public function getIdLog()
    {
        return $this->idLog;
    }

    /**
     * Set dtAcao
     *
     * @param \DateTime $dtAcao
     * @return Log
     */
    public function setDtAcao($dtAcao)
    {
        $this->dtAcao = $dtAcao;
    
        return $this;
    }

    /**
     * Get dtAcao
     *
     * @return \DateTime 
     */
    public function getDtAcao()
    {
        return $this->dtAcao;
    }

    /**
     * Set csAcao
     *
     * @param string $csAcao
     * @return Log
     */
    public function setCsAcao($csAcao)
    {
        $this->csAcao = $csAcao;
    
        return $this;
    }

    /**
     * Get csAcao
     *
     * @return string 
     */
    public function getCsAcao()
    {
        return $this->csAcao;
    }

    /**
     * Set nmScript
     *
     * @param string $nmScript
     * @return Log
     */
    public function setNmScript($nmScript)
    {
        $this->nmScript = $nmScript;
    
        return $this;
    }

    /**
     * Get nmScript
     *
     * @return string 
     */
    public function getNmScript()
    {
        return $this->nmScript;
    }

    /**
     * Set nmTabela
     *
     * @param string $nmTabela
     * @return Log
     */
    public function setNmTabela($nmTabela)
    {
        $this->nmTabela = $nmTabela;
    
        return $this;
    }

    /**
     * Get nmTabela
     *
     * @return string 
     */
    public function getNmTabela()
    {
        return $this->nmTabela;
    }

    /**
     * Set teIpOrigem
     *
     * @param string $teIpOrigem
     * @return Log
     */
    public function setTeIpOrigem($teIpOrigem)
    {
        $this->teIpOrigem = $teIpOrigem;
    
        return $this;
    }

    /**
     * Get teIpOrigem
     *
     * @return string 
     */
    public function getTeIpOrigem()
    {
        return $this->teIpOrigem;
    }

    /**
     * Set idUsuario
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $idUsuario
     * @return Log
     */
    public function setIdUsuario(\Cacic\CommonBundle\Entity\Usuario $idUsuario = null)
    {
        $this->idUsuario = $idUsuario;
    
        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \Cacic\CommonBundle\Entity\Usuario 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
}