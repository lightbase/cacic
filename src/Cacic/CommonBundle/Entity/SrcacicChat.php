<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicChat
 */
class SrcacicChat
{
    /**
     * @var integer
     */
    private $idSrcacicChat;

    /**
     * @var \DateTime
     */
    private $dtHrMensagem;

    /**
     * @var string
     */
    private $teMensagem;

    /**
     * @var string
     */
    private $csOrigem;

    /**
     * @var \Cacic\CommonBundle\Entity\SrcacicConexao
     */
    private $idSrcacicConexao;


    /**
     * Get idSrcacicChat
     *
     * @return integer 
     */
    public function getIdSrcacicChat()
    {
        return $this->idSrcacicChat;
    }

    /**
     * Set dtHrMensagem
     *
     * @param \DateTime $dtHrMensagem
     * @return SrcacicChat
     */
    public function setDtHrMensagem($dtHrMensagem)
    {
        $this->dtHrMensagem = $dtHrMensagem;
    
        return $this;
    }

    /**
     * Get dtHrMensagem
     *
     * @return \DateTime 
     */
    public function getDtHrMensagem()
    {
        return $this->dtHrMensagem;
    }

    /**
     * Set teMensagem
     *
     * @param string $teMensagem
     * @return SrcacicChat
     */
    public function setTeMensagem($teMensagem)
    {
        $this->teMensagem = $teMensagem;
    
        return $this;
    }

    /**
     * Get teMensagem
     *
     * @return string 
     */
    public function getTeMensagem()
    {
        return $this->teMensagem;
    }

    /**
     * Set csOrigem
     *
     * @param string $csOrigem
     * @return SrcacicChat
     */
    public function setCsOrigem($csOrigem)
    {
        $this->csOrigem = $csOrigem;
    
        return $this;
    }

    /**
     * Get csOrigem
     *
     * @return string 
     */
    public function getCsOrigem()
    {
        return $this->csOrigem;
    }

    /**
     * Set idSrcacicConexao
     *
     * @param \Cacic\CommonBundle\Entity\SrcacicConexao $idSrcacicConexao
     * @return SrcacicChat
     */
    public function setIdSrcacicConexao(\Cacic\CommonBundle\Entity\SrcacicConexao $idSrcacicConexao = null)
    {
        $this->idSrcacicConexao = $idSrcacicConexao;
    
        return $this;
    }

    /**
     * Get idSrcacicConexao
     *
     * @return \Cacic\CommonBundle\Entity\SrcacicConexao 
     */
    public function getIdSrcacicConexao()
    {
        return $this->idSrcacicConexao;
    }
}