<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Redes
 *
 * @ORM\Table(name="redes")
 * @ORM\Entity
 */
class Redes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_rede", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRede;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_servidor_autenticacao", type="integer", nullable=true)
     */
    private $idServidorAutenticacao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_ip_rede", type="string", length=15, nullable=false)
     */
    private $teIpRede;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_rede", type="string", length=100, nullable=true)
     */
    private $nmRede;

    /**
     * @var string
     *
     * @ORM\Column(name="te_observacao", type="string", length=100, nullable=true)
     */
    private $teObservacao;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_pessoa_contato1", type="string", length=50, nullable=true)
     */
    private $nmPessoaContato1;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_pessoa_contato2", type="string", length=50, nullable=true)
     */
    private $nmPessoaContato2;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_telefone1", type="string", length=11, nullable=true)
     */
    private $nuTelefone1;

    /**
     * @var string
     *
     * @ORM\Column(name="te_email_contato2", type="string", length=50, nullable=true)
     */
    private $teEmailContato2;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_telefone2", type="string", length=11, nullable=true)
     */
    private $nuTelefone2;

    /**
     * @var string
     *
     * @ORM\Column(name="te_email_contato1", type="string", length=50, nullable=true)
     */
    private $teEmailContato1;

    /**
     * @var string
     *
     * @ORM\Column(name="te_serv_cacic", type="string", length=60, nullable=false)
     */
    private $teServCacic;

    /**
     * @var string
     *
     * @ORM\Column(name="te_serv_updates", type="string", length=60, nullable=false)
     */
    private $teServUpdates;

    /**
     * @var string
     *
     * @ORM\Column(name="te_path_serv_updates", type="string", length=255, nullable=true)
     */
    private $tePathServUpdates;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_usuario_login_serv_updates", type="string", length=20, nullable=true)
     */
    private $nmUsuarioLoginServUpdates;

    /**
     * @var string
     *
     * @ORM\Column(name="te_senha_login_serv_updates", type="string", length=20, nullable=true)
     */
    private $teSenhaLoginServUpdates;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_porta_serv_updates", type="string", length=4, nullable=true)
     */
    private $nuPortaServUpdates;

    /**
     * @var string
     *
     * @ORM\Column(name="te_mascara_rede", type="string", length=15, nullable=true)
     */
    private $teMascaraRede;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_verifica_updates", type="date", nullable=true)
     */
    private $dtVerificaUpdates;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_usuario_login_serv_updates_gerente", type="string", length=20, nullable=true)
     */
    private $nmUsuarioLoginServUpdatesGerente;

    /**
     * @var string
     *
     * @ORM\Column(name="te_senha_login_serv_updates_gerente", type="string", length=20, nullable=true)
     */
    private $teSenhaLoginServUpdatesGerente;

    /**
     * @var integer
     *
     * @ORM\Column(name="nu_limite_ftp", type="integer", nullable=false)
     */
    private $nuLimiteFtp;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_permitir_desativar_srcacic", type="string", length=1, nullable=false)
     */
    private $csPermitirDesativarSrcacic;

    /**
     * @var string
     *
     * @ORM\Column(name="dt_debug", type="string", length=8, nullable=true)
     */
    private $dtDebug;

    /**
     * @var \Locais
     *
     * @ORM\ManyToOne(targetEntity="Locais")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_local", referencedColumnName="id_local")
     * })
     */
    private $idLocal;



    /**
     * Get idRede
     *
     * @return integer 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }

    /**
     * Set idServidorAutenticacao
     *
     * @param integer $idServidorAutenticacao
     * @return Redes
     */
    public function setIdServidorAutenticacao($idServidorAutenticacao)
    {
        $this->idServidorAutenticacao = $idServidorAutenticacao;
    
        return $this;
    }

    /**
     * Get idServidorAutenticacao
     *
     * @return integer 
     */
    public function getIdServidorAutenticacao()
    {
        return $this->idServidorAutenticacao;
    }

    /**
     * Set teIpRede
     *
     * @param string $teIpRede
     * @return Redes
     */
    public function setTeIpRede($teIpRede)
    {
        $this->teIpRede = $teIpRede;
    
        return $this;
    }

    /**
     * Get teIpRede
     *
     * @return string 
     */
    public function getTeIpRede()
    {
        return $this->teIpRede;
    }

    /**
     * Set nmRede
     *
     * @param string $nmRede
     * @return Redes
     */
    public function setNmRede($nmRede)
    {
        $this->nmRede = $nmRede;
    
        return $this;
    }

    /**
     * Get nmRede
     *
     * @return string 
     */
    public function getNmRede()
    {
        return $this->nmRede;
    }

    /**
     * Set teObservacao
     *
     * @param string $teObservacao
     * @return Redes
     */
    public function setTeObservacao($teObservacao)
    {
        $this->teObservacao = $teObservacao;
    
        return $this;
    }

    /**
     * Get teObservacao
     *
     * @return string 
     */
    public function getTeObservacao()
    {
        return $this->teObservacao;
    }

    /**
     * Set nmPessoaContato1
     *
     * @param string $nmPessoaContato1
     * @return Redes
     */
    public function setNmPessoaContato1($nmPessoaContato1)
    {
        $this->nmPessoaContato1 = $nmPessoaContato1;
    
        return $this;
    }

    /**
     * Get nmPessoaContato1
     *
     * @return string 
     */
    public function getNmPessoaContato1()
    {
        return $this->nmPessoaContato1;
    }

    /**
     * Set nmPessoaContato2
     *
     * @param string $nmPessoaContato2
     * @return Redes
     */
    public function setNmPessoaContato2($nmPessoaContato2)
    {
        $this->nmPessoaContato2 = $nmPessoaContato2;
    
        return $this;
    }

    /**
     * Get nmPessoaContato2
     *
     * @return string 
     */
    public function getNmPessoaContato2()
    {
        return $this->nmPessoaContato2;
    }

    /**
     * Set nuTelefone1
     *
     * @param string $nuTelefone1
     * @return Redes
     */
    public function setNuTelefone1($nuTelefone1)
    {
        $this->nuTelefone1 = $nuTelefone1;
    
        return $this;
    }

    /**
     * Get nuTelefone1
     *
     * @return string 
     */
    public function getNuTelefone1()
    {
        return $this->nuTelefone1;
    }

    /**
     * Set teEmailContato2
     *
     * @param string $teEmailContato2
     * @return Redes
     */
    public function setTeEmailContato2($teEmailContato2)
    {
        $this->teEmailContato2 = $teEmailContato2;
    
        return $this;
    }

    /**
     * Get teEmailContato2
     *
     * @return string 
     */
    public function getTeEmailContato2()
    {
        return $this->teEmailContato2;
    }

    /**
     * Set nuTelefone2
     *
     * @param string $nuTelefone2
     * @return Redes
     */
    public function setNuTelefone2($nuTelefone2)
    {
        $this->nuTelefone2 = $nuTelefone2;
    
        return $this;
    }

    /**
     * Get nuTelefone2
     *
     * @return string 
     */
    public function getNuTelefone2()
    {
        return $this->nuTelefone2;
    }

    /**
     * Set teEmailContato1
     *
     * @param string $teEmailContato1
     * @return Redes
     */
    public function setTeEmailContato1($teEmailContato1)
    {
        $this->teEmailContato1 = $teEmailContato1;
    
        return $this;
    }

    /**
     * Get teEmailContato1
     *
     * @return string 
     */
    public function getTeEmailContato1()
    {
        return $this->teEmailContato1;
    }

    /**
     * Set teServCacic
     *
     * @param string $teServCacic
     * @return Redes
     */
    public function setTeServCacic($teServCacic)
    {
        $this->teServCacic = $teServCacic;
    
        return $this;
    }

    /**
     * Get teServCacic
     *
     * @return string 
     */
    public function getTeServCacic()
    {
        return $this->teServCacic;
    }

    /**
     * Set teServUpdates
     *
     * @param string $teServUpdates
     * @return Redes
     */
    public function setTeServUpdates($teServUpdates)
    {
        $this->teServUpdates = $teServUpdates;
    
        return $this;
    }

    /**
     * Get teServUpdates
     *
     * @return string 
     */
    public function getTeServUpdates()
    {
        return $this->teServUpdates;
    }

    /**
     * Set tePathServUpdates
     *
     * @param string $tePathServUpdates
     * @return Redes
     */
    public function setTePathServUpdates($tePathServUpdates)
    {
        $this->tePathServUpdates = $tePathServUpdates;
    
        return $this;
    }

    /**
     * Get tePathServUpdates
     *
     * @return string 
     */
    public function getTePathServUpdates()
    {
        return $this->tePathServUpdates;
    }

    /**
     * Set nmUsuarioLoginServUpdates
     *
     * @param string $nmUsuarioLoginServUpdates
     * @return Redes
     */
    public function setNmUsuarioLoginServUpdates($nmUsuarioLoginServUpdates)
    {
        $this->nmUsuarioLoginServUpdates = $nmUsuarioLoginServUpdates;
    
        return $this;
    }

    /**
     * Get nmUsuarioLoginServUpdates
     *
     * @return string 
     */
    public function getNmUsuarioLoginServUpdates()
    {
        return $this->nmUsuarioLoginServUpdates;
    }

    /**
     * Set teSenhaLoginServUpdates
     *
     * @param string $teSenhaLoginServUpdates
     * @return Redes
     */
    public function setTeSenhaLoginServUpdates($teSenhaLoginServUpdates)
    {
        $this->teSenhaLoginServUpdates = $teSenhaLoginServUpdates;
    
        return $this;
    }

    /**
     * Get teSenhaLoginServUpdates
     *
     * @return string 
     */
    public function getTeSenhaLoginServUpdates()
    {
        return $this->teSenhaLoginServUpdates;
    }

    /**
     * Set nuPortaServUpdates
     *
     * @param string $nuPortaServUpdates
     * @return Redes
     */
    public function setNuPortaServUpdates($nuPortaServUpdates)
    {
        $this->nuPortaServUpdates = $nuPortaServUpdates;
    
        return $this;
    }

    /**
     * Get nuPortaServUpdates
     *
     * @return string 
     */
    public function getNuPortaServUpdates()
    {
        return $this->nuPortaServUpdates;
    }

    /**
     * Set teMascaraRede
     *
     * @param string $teMascaraRede
     * @return Redes
     */
    public function setTeMascaraRede($teMascaraRede)
    {
        $this->teMascaraRede = $teMascaraRede;
    
        return $this;
    }

    /**
     * Get teMascaraRede
     *
     * @return string 
     */
    public function getTeMascaraRede()
    {
        return $this->teMascaraRede;
    }

    /**
     * Set dtVerificaUpdates
     *
     * @param \DateTime $dtVerificaUpdates
     * @return Redes
     */
    public function setDtVerificaUpdates($dtVerificaUpdates)
    {
        $this->dtVerificaUpdates = $dtVerificaUpdates;
    
        return $this;
    }

    /**
     * Get dtVerificaUpdates
     *
     * @return \DateTime 
     */
    public function getDtVerificaUpdates()
    {
        return $this->dtVerificaUpdates;
    }

    /**
     * Set nmUsuarioLoginServUpdatesGerente
     *
     * @param string $nmUsuarioLoginServUpdatesGerente
     * @return Redes
     */
    public function setNmUsuarioLoginServUpdatesGerente($nmUsuarioLoginServUpdatesGerente)
    {
        $this->nmUsuarioLoginServUpdatesGerente = $nmUsuarioLoginServUpdatesGerente;
    
        return $this;
    }

    /**
     * Get nmUsuarioLoginServUpdatesGerente
     *
     * @return string 
     */
    public function getNmUsuarioLoginServUpdatesGerente()
    {
        return $this->nmUsuarioLoginServUpdatesGerente;
    }

    /**
     * Set teSenhaLoginServUpdatesGerente
     *
     * @param string $teSenhaLoginServUpdatesGerente
     * @return Redes
     */
    public function setTeSenhaLoginServUpdatesGerente($teSenhaLoginServUpdatesGerente)
    {
        $this->teSenhaLoginServUpdatesGerente = $teSenhaLoginServUpdatesGerente;
    
        return $this;
    }

    /**
     * Get teSenhaLoginServUpdatesGerente
     *
     * @return string 
     */
    public function getTeSenhaLoginServUpdatesGerente()
    {
        return $this->teSenhaLoginServUpdatesGerente;
    }

    /**
     * Set nuLimiteFtp
     *
     * @param integer $nuLimiteFtp
     * @return Redes
     */
    public function setNuLimiteFtp($nuLimiteFtp)
    {
        $this->nuLimiteFtp = $nuLimiteFtp;
    
        return $this;
    }

    /**
     * Get nuLimiteFtp
     *
     * @return integer 
     */
    public function getNuLimiteFtp()
    {
        return $this->nuLimiteFtp;
    }

    /**
     * Set csPermitirDesativarSrcacic
     *
     * @param string $csPermitirDesativarSrcacic
     * @return Redes
     */
    public function setCsPermitirDesativarSrcacic($csPermitirDesativarSrcacic)
    {
        $this->csPermitirDesativarSrcacic = $csPermitirDesativarSrcacic;
    
        return $this;
    }

    /**
     * Get csPermitirDesativarSrcacic
     *
     * @return string 
     */
    public function getCsPermitirDesativarSrcacic()
    {
        return $this->csPermitirDesativarSrcacic;
    }

    /**
     * Set dtDebug
     *
     * @param string $dtDebug
     * @return Redes
     */
    public function setDtDebug($dtDebug)
    {
        $this->dtDebug = $dtDebug;
    
        return $this;
    }

    /**
     * Get dtDebug
     *
     * @return string 
     */
    public function getDtDebug()
    {
        return $this->dtDebug;
    }

    /**
     * Set idLocal
     *
     * @param \Cacic\CommonBundle\Entity\Locais $idLocal
     * @return Redes
     */
    public function setIdLocal(\Cacic\CommonBundle\Entity\Locais $idLocal = null)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return \Cacic\CommonBundle\Entity\Locais 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }
}