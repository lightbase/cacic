<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table(name="log")
 * @ORM\Entity
 */
class Log
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_log", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLog;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_acao", type="datetime", nullable=false)
     */
    private $dtAcao;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_acao", type="string", length=20, nullable=false)
     */
    private $csAcao;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_script", type="string", length=255, nullable=false)
     */
    private $nmScript;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_tabela", type="string", length=255, nullable=false)
     */
    private $nmTabela;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_usuario", type="integer", nullable=false)
     */
    private $idUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="te_ip_origem", type="string", length=15, nullable=false)
     */
    private $teIpOrigem;



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
     * Set idUsuario
     *
     * @param integer $idUsuario
     * @return Log
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    
        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return integer 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
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
}