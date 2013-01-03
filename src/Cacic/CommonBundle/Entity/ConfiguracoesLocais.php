<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfiguracoesLocais
 *
 * @ORM\Table(name="configuracoes_locais")
 * @ORM\Entity
 */
class ConfiguracoesLocais
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="te_notificar_mudanca_hardware", type="text", nullable=true)
     */
    private $teNotificarMudancaHardware;

    /**
     * @var string
     *
     * @ORM\Column(name="te_notificar_utilizacao_usb", type="text", nullable=true)
     */
    private $teNotificarUtilizacaoUsb;

    /**
     * @var string
     *
     * @ORM\Column(name="in_exibe_erros_criticos", type="string", length=1, nullable=true)
     */
    private $inExibeErrosCriticos;

    /**
     * @var string
     *
     * @ORM\Column(name="in_exibe_bandeja", type="string", length=1, nullable=true)
     */
    private $inExibeBandeja;

    /**
     * @var integer
     *
     * @ORM\Column(name="nu_exec_apos", type="integer", nullable=true)
     */
    private $nuExecApos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao_patrim_interface", type="datetime", nullable=true)
     */
    private $dtHrAlteracaoPatrimInterface;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao_patrim_uon1", type="datetime", nullable=true)
     */
    private $dtHrAlteracaoPatrimUon1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao_patrim_uon1a", type="datetime", nullable=true)
     */
    private $dtHrAlteracaoPatrimUon1a;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao_patrim_uon2", type="datetime", nullable=true)
     */
    private $dtHrAlteracaoPatrimUon2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_coleta_forcada", type="datetime", nullable=true)
     */
    private $dtHrColetaForcada;

    /**
     * @var string
     *
     * @ORM\Column(name="te_notificar_mudanca_patrim", type="text", nullable=true)
     */
    private $teNotificarMudancaPatrim;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_organizacao", type="string", length=150, nullable=true)
     */
    private $nmOrganizacao;

    /**
     * @var boolean
     *
     * @ORM\Column(name="nu_timeout_srcacic", type="boolean", nullable=false)
     */
    private $nuTimeoutSrcacic;

    /**
     * @var integer
     *
     * @ORM\Column(name="nu_intervalo_exec", type="integer", nullable=true)
     */
    private $nuIntervaloExec;

    /**
     * @var integer
     *
     * @ORM\Column(name="nu_intervalo_renovacao_patrim", type="integer", nullable=true)
     */
    private $nuIntervaloRenovacaoPatrim;

    /**
     * @var string
     *
     * @ORM\Column(name="te_senha_adm_agente", type="string", length=30, nullable=true)
     */
    private $teSenhaAdmAgente;

    /**
     * @var string
     *
     * @ORM\Column(name="te_serv_updates_padrao", type="string", length=60, nullable=true)
     */
    private $teServUpdatesPadrao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_serv_cacic_padrao", type="string", length=60, nullable=true)
     */
    private $teServCacicPadrao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_enderecos_mac_invalidos", type="text", nullable=true)
     */
    private $teEnderecosMacInvalidos;

    /**
     * @var string
     *
     * @ORM\Column(name="te_janelas_excecao", type="text", nullable=true)
     */
    private $teJanelasExcecao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_nota_email_gerentes", type="text", nullable=true)
     */
    private $teNotaEmailGerentes;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_abre_janela_patr", type="string", length=1, nullable=false)
     */
    private $csAbreJanelaPatr;

    /**
     * @var string
     *
     * @ORM\Column(name="id_default_body_bgcolor", type="string", length=10, nullable=false)
     */
    private $idDefaultBodyBgcolor;

    /**
     * @var string
     *
     * @ORM\Column(name="te_exibe_graficos", type="string", length=100, nullable=false)
     */
    private $teExibeGraficos;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_porta_srcacic", type="string", length=5, nullable=false)
     */
    private $nuPortaSrcacic;

    /**
     * @var string
     *
     * @ORM\Column(name="te_usb_filter", type="text", nullable=false)
     */
    private $teUsbFilter;



    /**
     * Get idLocal
     *
     * @return integer 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set teNotificarMudancaHardware
     *
     * @param string $teNotificarMudancaHardware
     * @return ConfiguracoesLocais
     */
    public function setTeNotificarMudancaHardware($teNotificarMudancaHardware)
    {
        $this->teNotificarMudancaHardware = $teNotificarMudancaHardware;
    
        return $this;
    }

    /**
     * Get teNotificarMudancaHardware
     *
     * @return string 
     */
    public function getTeNotificarMudancaHardware()
    {
        return $this->teNotificarMudancaHardware;
    }

    /**
     * Set teNotificarUtilizacaoUsb
     *
     * @param string $teNotificarUtilizacaoUsb
     * @return ConfiguracoesLocais
     */
    public function setTeNotificarUtilizacaoUsb($teNotificarUtilizacaoUsb)
    {
        $this->teNotificarUtilizacaoUsb = $teNotificarUtilizacaoUsb;
    
        return $this;
    }

    /**
     * Get teNotificarUtilizacaoUsb
     *
     * @return string 
     */
    public function getTeNotificarUtilizacaoUsb()
    {
        return $this->teNotificarUtilizacaoUsb;
    }

    /**
     * Set inExibeErrosCriticos
     *
     * @param string $inExibeErrosCriticos
     * @return ConfiguracoesLocais
     */
    public function setInExibeErrosCriticos($inExibeErrosCriticos)
    {
        $this->inExibeErrosCriticos = $inExibeErrosCriticos;
    
        return $this;
    }

    /**
     * Get inExibeErrosCriticos
     *
     * @return string 
     */
    public function getInExibeErrosCriticos()
    {
        return $this->inExibeErrosCriticos;
    }

    /**
     * Set inExibeBandeja
     *
     * @param string $inExibeBandeja
     * @return ConfiguracoesLocais
     */
    public function setInExibeBandeja($inExibeBandeja)
    {
        $this->inExibeBandeja = $inExibeBandeja;
    
        return $this;
    }

    /**
     * Get inExibeBandeja
     *
     * @return string 
     */
    public function getInExibeBandeja()
    {
        return $this->inExibeBandeja;
    }

    /**
     * Set nuExecApos
     *
     * @param integer $nuExecApos
     * @return ConfiguracoesLocais
     */
    public function setNuExecApos($nuExecApos)
    {
        $this->nuExecApos = $nuExecApos;
    
        return $this;
    }

    /**
     * Get nuExecApos
     *
     * @return integer 
     */
    public function getNuExecApos()
    {
        return $this->nuExecApos;
    }

    /**
     * Set dtHrAlteracaoPatrimInterface
     *
     * @param \DateTime $dtHrAlteracaoPatrimInterface
     * @return ConfiguracoesLocais
     */
    public function setDtHrAlteracaoPatrimInterface($dtHrAlteracaoPatrimInterface)
    {
        $this->dtHrAlteracaoPatrimInterface = $dtHrAlteracaoPatrimInterface;
    
        return $this;
    }

    /**
     * Get dtHrAlteracaoPatrimInterface
     *
     * @return \DateTime 
     */
    public function getDtHrAlteracaoPatrimInterface()
    {
        return $this->dtHrAlteracaoPatrimInterface;
    }

    /**
     * Set dtHrAlteracaoPatrimUon1
     *
     * @param \DateTime $dtHrAlteracaoPatrimUon1
     * @return ConfiguracoesLocais
     */
    public function setDtHrAlteracaoPatrimUon1($dtHrAlteracaoPatrimUon1)
    {
        $this->dtHrAlteracaoPatrimUon1 = $dtHrAlteracaoPatrimUon1;
    
        return $this;
    }

    /**
     * Get dtHrAlteracaoPatrimUon1
     *
     * @return \DateTime 
     */
    public function getDtHrAlteracaoPatrimUon1()
    {
        return $this->dtHrAlteracaoPatrimUon1;
    }

    /**
     * Set dtHrAlteracaoPatrimUon1a
     *
     * @param \DateTime $dtHrAlteracaoPatrimUon1a
     * @return ConfiguracoesLocais
     */
    public function setDtHrAlteracaoPatrimUon1a($dtHrAlteracaoPatrimUon1a)
    {
        $this->dtHrAlteracaoPatrimUon1a = $dtHrAlteracaoPatrimUon1a;
    
        return $this;
    }

    /**
     * Get dtHrAlteracaoPatrimUon1a
     *
     * @return \DateTime 
     */
    public function getDtHrAlteracaoPatrimUon1a()
    {
        return $this->dtHrAlteracaoPatrimUon1a;
    }

    /**
     * Set dtHrAlteracaoPatrimUon2
     *
     * @param \DateTime $dtHrAlteracaoPatrimUon2
     * @return ConfiguracoesLocais
     */
    public function setDtHrAlteracaoPatrimUon2($dtHrAlteracaoPatrimUon2)
    {
        $this->dtHrAlteracaoPatrimUon2 = $dtHrAlteracaoPatrimUon2;
    
        return $this;
    }

    /**
     * Get dtHrAlteracaoPatrimUon2
     *
     * @return \DateTime 
     */
    public function getDtHrAlteracaoPatrimUon2()
    {
        return $this->dtHrAlteracaoPatrimUon2;
    }

    /**
     * Set dtHrColetaForcada
     *
     * @param \DateTime $dtHrColetaForcada
     * @return ConfiguracoesLocais
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
     * Set teNotificarMudancaPatrim
     *
     * @param string $teNotificarMudancaPatrim
     * @return ConfiguracoesLocais
     */
    public function setTeNotificarMudancaPatrim($teNotificarMudancaPatrim)
    {
        $this->teNotificarMudancaPatrim = $teNotificarMudancaPatrim;
    
        return $this;
    }

    /**
     * Get teNotificarMudancaPatrim
     *
     * @return string 
     */
    public function getTeNotificarMudancaPatrim()
    {
        return $this->teNotificarMudancaPatrim;
    }

    /**
     * Set nmOrganizacao
     *
     * @param string $nmOrganizacao
     * @return ConfiguracoesLocais
     */
    public function setNmOrganizacao($nmOrganizacao)
    {
        $this->nmOrganizacao = $nmOrganizacao;
    
        return $this;
    }

    /**
     * Get nmOrganizacao
     *
     * @return string 
     */
    public function getNmOrganizacao()
    {
        return $this->nmOrganizacao;
    }

    /**
     * Set nuTimeoutSrcacic
     *
     * @param boolean $nuTimeoutSrcacic
     * @return ConfiguracoesLocais
     */
    public function setNuTimeoutSrcacic($nuTimeoutSrcacic)
    {
        $this->nuTimeoutSrcacic = $nuTimeoutSrcacic;
    
        return $this;
    }

    /**
     * Get nuTimeoutSrcacic
     *
     * @return boolean 
     */
    public function getNuTimeoutSrcacic()
    {
        return $this->nuTimeoutSrcacic;
    }

    /**
     * Set nuIntervaloExec
     *
     * @param integer $nuIntervaloExec
     * @return ConfiguracoesLocais
     */
    public function setNuIntervaloExec($nuIntervaloExec)
    {
        $this->nuIntervaloExec = $nuIntervaloExec;
    
        return $this;
    }

    /**
     * Get nuIntervaloExec
     *
     * @return integer 
     */
    public function getNuIntervaloExec()
    {
        return $this->nuIntervaloExec;
    }

    /**
     * Set nuIntervaloRenovacaoPatrim
     *
     * @param integer $nuIntervaloRenovacaoPatrim
     * @return ConfiguracoesLocais
     */
    public function setNuIntervaloRenovacaoPatrim($nuIntervaloRenovacaoPatrim)
    {
        $this->nuIntervaloRenovacaoPatrim = $nuIntervaloRenovacaoPatrim;
    
        return $this;
    }

    /**
     * Get nuIntervaloRenovacaoPatrim
     *
     * @return integer 
     */
    public function getNuIntervaloRenovacaoPatrim()
    {
        return $this->nuIntervaloRenovacaoPatrim;
    }

    /**
     * Set teSenhaAdmAgente
     *
     * @param string $teSenhaAdmAgente
     * @return ConfiguracoesLocais
     */
    public function setTeSenhaAdmAgente($teSenhaAdmAgente)
    {
        $this->teSenhaAdmAgente = $teSenhaAdmAgente;
    
        return $this;
    }

    /**
     * Get teSenhaAdmAgente
     *
     * @return string 
     */
    public function getTeSenhaAdmAgente()
    {
        return $this->teSenhaAdmAgente;
    }

    /**
     * Set teServUpdatesPadrao
     *
     * @param string $teServUpdatesPadrao
     * @return ConfiguracoesLocais
     */
    public function setTeServUpdatesPadrao($teServUpdatesPadrao)
    {
        $this->teServUpdatesPadrao = $teServUpdatesPadrao;
    
        return $this;
    }

    /**
     * Get teServUpdatesPadrao
     *
     * @return string 
     */
    public function getTeServUpdatesPadrao()
    {
        return $this->teServUpdatesPadrao;
    }

    /**
     * Set teServCacicPadrao
     *
     * @param string $teServCacicPadrao
     * @return ConfiguracoesLocais
     */
    public function setTeServCacicPadrao($teServCacicPadrao)
    {
        $this->teServCacicPadrao = $teServCacicPadrao;
    
        return $this;
    }

    /**
     * Get teServCacicPadrao
     *
     * @return string 
     */
    public function getTeServCacicPadrao()
    {
        return $this->teServCacicPadrao;
    }

    /**
     * Set teEnderecosMacInvalidos
     *
     * @param string $teEnderecosMacInvalidos
     * @return ConfiguracoesLocais
     */
    public function setTeEnderecosMacInvalidos($teEnderecosMacInvalidos)
    {
        $this->teEnderecosMacInvalidos = $teEnderecosMacInvalidos;
    
        return $this;
    }

    /**
     * Get teEnderecosMacInvalidos
     *
     * @return string 
     */
    public function getTeEnderecosMacInvalidos()
    {
        return $this->teEnderecosMacInvalidos;
    }

    /**
     * Set teJanelasExcecao
     *
     * @param string $teJanelasExcecao
     * @return ConfiguracoesLocais
     */
    public function setTeJanelasExcecao($teJanelasExcecao)
    {
        $this->teJanelasExcecao = $teJanelasExcecao;
    
        return $this;
    }

    /**
     * Get teJanelasExcecao
     *
     * @return string 
     */
    public function getTeJanelasExcecao()
    {
        return $this->teJanelasExcecao;
    }

    /**
     * Set teNotaEmailGerentes
     *
     * @param string $teNotaEmailGerentes
     * @return ConfiguracoesLocais
     */
    public function setTeNotaEmailGerentes($teNotaEmailGerentes)
    {
        $this->teNotaEmailGerentes = $teNotaEmailGerentes;
    
        return $this;
    }

    /**
     * Get teNotaEmailGerentes
     *
     * @return string 
     */
    public function getTeNotaEmailGerentes()
    {
        return $this->teNotaEmailGerentes;
    }

    /**
     * Set csAbreJanelaPatr
     *
     * @param string $csAbreJanelaPatr
     * @return ConfiguracoesLocais
     */
    public function setCsAbreJanelaPatr($csAbreJanelaPatr)
    {
        $this->csAbreJanelaPatr = $csAbreJanelaPatr;
    
        return $this;
    }

    /**
     * Get csAbreJanelaPatr
     *
     * @return string 
     */
    public function getCsAbreJanelaPatr()
    {
        return $this->csAbreJanelaPatr;
    }

    /**
     * Set idDefaultBodyBgcolor
     *
     * @param string $idDefaultBodyBgcolor
     * @return ConfiguracoesLocais
     */
    public function setIdDefaultBodyBgcolor($idDefaultBodyBgcolor)
    {
        $this->idDefaultBodyBgcolor = $idDefaultBodyBgcolor;
    
        return $this;
    }

    /**
     * Get idDefaultBodyBgcolor
     *
     * @return string 
     */
    public function getIdDefaultBodyBgcolor()
    {
        return $this->idDefaultBodyBgcolor;
    }

    /**
     * Set teExibeGraficos
     *
     * @param string $teExibeGraficos
     * @return ConfiguracoesLocais
     */
    public function setTeExibeGraficos($teExibeGraficos)
    {
        $this->teExibeGraficos = $teExibeGraficos;
    
        return $this;
    }

    /**
     * Get teExibeGraficos
     *
     * @return string 
     */
    public function getTeExibeGraficos()
    {
        return $this->teExibeGraficos;
    }

    /**
     * Set nuPortaSrcacic
     *
     * @param string $nuPortaSrcacic
     * @return ConfiguracoesLocais
     */
    public function setNuPortaSrcacic($nuPortaSrcacic)
    {
        $this->nuPortaSrcacic = $nuPortaSrcacic;
    
        return $this;
    }

    /**
     * Get nuPortaSrcacic
     *
     * @return string 
     */
    public function getNuPortaSrcacic()
    {
        return $this->nuPortaSrcacic;
    }

    /**
     * Set teUsbFilter
     *
     * @param string $teUsbFilter
     * @return ConfiguracoesLocais
     */
    public function setTeUsbFilter($teUsbFilter)
    {
        $this->teUsbFilter = $teUsbFilter;
    
        return $this;
    }

    /**
     * Get teUsbFilter
     *
     * @return string 
     */
    public function getTeUsbFilter()
    {
        return $this->teUsbFilter;
    }
}