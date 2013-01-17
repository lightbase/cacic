<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicChats
 *
 * @ORM\Table(name="srcacic_chats")
 * @ORM\Entity
 */
class SrcacicChats
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_srcacic_chat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSrcacicChat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_mensagem", type="datetime", nullable=false)
     */
    private $dtHrMensagem;

    /**
     * @var string
     *
     * @ORM\Column(name="te_mensagem", type="text", nullable=false)
     */
    private $teMensagem;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_origem", type="string", length=3, nullable=false)
     */
    private $csOrigem;

    /**
     * @var \SrcacicConexoes
     *
     * @ORM\ManyToOne(targetEntity="SrcacicConexoes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_conexao", referencedColumnName="id_conexao")
     * })
     */
    private $idConexao;



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
     * @return SrcacicChats
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
     * @return SrcacicChats
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
     * @return SrcacicChats
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
     * Set idConexao
     *
     * @param \Cacic\CommonBundle\Entity\SrcacicConexoes $idConexao
     * @return SrcacicChats
     */
    public function setIdConexao(\Cacic\CommonBundle\Entity\SrcacicConexoes $idConexao = null)
    {
        $this->idConexao = $idConexao;
    
        return $this;
    }

    /**
     * Get idConexao
     *
     * @return \Cacic\CommonBundle\Entity\SrcacicConexoes 
     */
    public function getIdConexao()
    {
        return $this->idConexao;
    }
}