<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicConexoes
 *
 * @ORM\Table(name="srcacic_conexoes")
 * @ORM\Entity
 */
class SrcacicConexoes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_conexao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idConexao;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_sessao", type="integer", nullable=false)
     */
    private $idSessao;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_usuario_cli", type="integer", nullable=false)
     */
    private $idUsuarioCli;

    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address_cli", type="string", length=17, nullable=false)
     */
    private $teNodeAddressCli;

    /**
     * @var string
     *
     * @ORM\Column(name="te_documento_referencial", type="string", length=60, nullable=false)
     */
    private $teDocumentoReferencial;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so_cli", type="integer", nullable=false)
     */
    private $idSoCli;

    /**
     * @var string
     *
     * @ORM\Column(name="te_motivo_conexao", type="text", nullable=false)
     */
    private $teMotivoConexao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_inicio_conexao", type="datetime", nullable=false)
     */
    private $dtHrInicioConexao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_ultimo_contato", type="datetime", nullable=false)
     */
    private $dtHrUltimoContato;



    /**
     * Get idConexao
     *
     * @return integer 
     */
    public function getIdConexao()
    {
        return $this->idConexao;
    }

    /**
     * Set idSessao
     *
     * @param integer $idSessao
     * @return SrcacicConexoes
     */
    public function setIdSessao($idSessao)
    {
        $this->idSessao = $idSessao;
    
        return $this;
    }

    /**
     * Get idSessao
     *
     * @return integer 
     */
    public function getIdSessao()
    {
        return $this->idSessao;
    }

    /**
     * Set idUsuarioCli
     *
     * @param integer $idUsuarioCli
     * @return SrcacicConexoes
     */
    public function setIdUsuarioCli($idUsuarioCli)
    {
        $this->idUsuarioCli = $idUsuarioCli;
    
        return $this;
    }

    /**
     * Get idUsuarioCli
     *
     * @return integer 
     */
    public function getIdUsuarioCli()
    {
        return $this->idUsuarioCli;
    }

    /**
     * Set teNodeAddressCli
     *
     * @param string $teNodeAddressCli
     * @return SrcacicConexoes
     */
    public function setTeNodeAddressCli($teNodeAddressCli)
    {
        $this->teNodeAddressCli = $teNodeAddressCli;
    
        return $this;
    }

    /**
     * Get teNodeAddressCli
     *
     * @return string 
     */
    public function getTeNodeAddressCli()
    {
        return $this->teNodeAddressCli;
    }

    /**
     * Set teDocumentoReferencial
     *
     * @param string $teDocumentoReferencial
     * @return SrcacicConexoes
     */
    public function setTeDocumentoReferencial($teDocumentoReferencial)
    {
        $this->teDocumentoReferencial = $teDocumentoReferencial;
    
        return $this;
    }

    /**
     * Get teDocumentoReferencial
     *
     * @return string 
     */
    public function getTeDocumentoReferencial()
    {
        return $this->teDocumentoReferencial;
    }

    /**
     * Set idSoCli
     *
     * @param integer $idSoCli
     * @return SrcacicConexoes
     */
    public function setIdSoCli($idSoCli)
    {
        $this->idSoCli = $idSoCli;
    
        return $this;
    }

    /**
     * Get idSoCli
     *
     * @return integer 
     */
    public function getIdSoCli()
    {
        return $this->idSoCli;
    }

    /**
     * Set teMotivoConexao
     *
     * @param string $teMotivoConexao
     * @return SrcacicConexoes
     */
    public function setTeMotivoConexao($teMotivoConexao)
    {
        $this->teMotivoConexao = $teMotivoConexao;
    
        return $this;
    }

    /**
     * Get teMotivoConexao
     *
     * @return string 
     */
    public function getTeMotivoConexao()
    {
        return $this->teMotivoConexao;
    }

    /**
     * Set dtHrInicioConexao
     *
     * @param \DateTime $dtHrInicioConexao
     * @return SrcacicConexoes
     */
    public function setDtHrInicioConexao($dtHrInicioConexao)
    {
        $this->dtHrInicioConexao = $dtHrInicioConexao;
    
        return $this;
    }

    /**
     * Get dtHrInicioConexao
     *
     * @return \DateTime 
     */
    public function getDtHrInicioConexao()
    {
        return $this->dtHrInicioConexao;
    }

    /**
     * Set dtHrUltimoContato
     *
     * @param \DateTime $dtHrUltimoContato
     * @return SrcacicConexoes
     */
    public function setDtHrUltimoContato($dtHrUltimoContato)
    {
        $this->dtHrUltimoContato = $dtHrUltimoContato;
    
        return $this;
    }

    /**
     * Get dtHrUltimoContato
     *
     * @return \DateTime 
     */
    public function getDtHrUltimoContato()
    {
        return $this->dtHrUltimoContato;
    }
}