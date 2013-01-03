<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicSessoes
 *
 * @ORM\Table(name="srcacic_sessoes")
 * @ORM\Entity
 */
class SrcacicSessoes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_sessao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSessao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_inicio_sessao", type="datetime", nullable=false)
     */
    private $dtHrInicioSessao;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_acesso_usuario_srv", type="string", length=30, nullable=false)
     */
    private $nmAcessoUsuarioSrv;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_completo_usuario_srv", type="string", length=100, nullable=false)
     */
    private $nmCompletoUsuarioSrv;

    /**
     * @var string
     *
     * @ORM\Column(name="te_email_usuario_srv", type="string", length=60, nullable=false)
     */
    private $teEmailUsuarioSrv;

    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address_srv", type="string", length=17, nullable=false)
     */
    private $teNodeAddressSrv;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so_srv", type="integer", nullable=false)
     */
    private $idSoSrv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_ultimo_contato", type="datetime", nullable=true)
     */
    private $dtHrUltimoContato;



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
     * Set dtHrInicioSessao
     *
     * @param \DateTime $dtHrInicioSessao
     * @return SrcacicSessoes
     */
    public function setDtHrInicioSessao($dtHrInicioSessao)
    {
        $this->dtHrInicioSessao = $dtHrInicioSessao;
    
        return $this;
    }

    /**
     * Get dtHrInicioSessao
     *
     * @return \DateTime 
     */
    public function getDtHrInicioSessao()
    {
        return $this->dtHrInicioSessao;
    }

    /**
     * Set nmAcessoUsuarioSrv
     *
     * @param string $nmAcessoUsuarioSrv
     * @return SrcacicSessoes
     */
    public function setNmAcessoUsuarioSrv($nmAcessoUsuarioSrv)
    {
        $this->nmAcessoUsuarioSrv = $nmAcessoUsuarioSrv;
    
        return $this;
    }

    /**
     * Get nmAcessoUsuarioSrv
     *
     * @return string 
     */
    public function getNmAcessoUsuarioSrv()
    {
        return $this->nmAcessoUsuarioSrv;
    }

    /**
     * Set nmCompletoUsuarioSrv
     *
     * @param string $nmCompletoUsuarioSrv
     * @return SrcacicSessoes
     */
    public function setNmCompletoUsuarioSrv($nmCompletoUsuarioSrv)
    {
        $this->nmCompletoUsuarioSrv = $nmCompletoUsuarioSrv;
    
        return $this;
    }

    /**
     * Get nmCompletoUsuarioSrv
     *
     * @return string 
     */
    public function getNmCompletoUsuarioSrv()
    {
        return $this->nmCompletoUsuarioSrv;
    }

    /**
     * Set teEmailUsuarioSrv
     *
     * @param string $teEmailUsuarioSrv
     * @return SrcacicSessoes
     */
    public function setTeEmailUsuarioSrv($teEmailUsuarioSrv)
    {
        $this->teEmailUsuarioSrv = $teEmailUsuarioSrv;
    
        return $this;
    }

    /**
     * Get teEmailUsuarioSrv
     *
     * @return string 
     */
    public function getTeEmailUsuarioSrv()
    {
        return $this->teEmailUsuarioSrv;
    }

    /**
     * Set teNodeAddressSrv
     *
     * @param string $teNodeAddressSrv
     * @return SrcacicSessoes
     */
    public function setTeNodeAddressSrv($teNodeAddressSrv)
    {
        $this->teNodeAddressSrv = $teNodeAddressSrv;
    
        return $this;
    }

    /**
     * Get teNodeAddressSrv
     *
     * @return string 
     */
    public function getTeNodeAddressSrv()
    {
        return $this->teNodeAddressSrv;
    }

    /**
     * Set idSoSrv
     *
     * @param integer $idSoSrv
     * @return SrcacicSessoes
     */
    public function setIdSoSrv($idSoSrv)
    {
        $this->idSoSrv = $idSoSrv;
    
        return $this;
    }

    /**
     * Get idSoSrv
     *
     * @return integer 
     */
    public function getIdSoSrv()
    {
        return $this->idSoSrv;
    }

    /**
     * Set dtHrUltimoContato
     *
     * @param \DateTime $dtHrUltimoContato
     * @return SrcacicSessoes
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