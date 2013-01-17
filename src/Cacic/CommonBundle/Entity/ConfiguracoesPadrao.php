<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfiguracoesPadrao
 *
 * @ORM\Table(name="configuracoes_padrao")
 * @ORM\Entity
 */
class ConfiguracoesPadrao
{
    /**
     * @var string
     *
     * @ORM\Column(name="nm_organizacao", type="string", length=150, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nmOrganizacao;

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
     * @var integer
     *
     * @ORM\Column(name="nu_rel_maxlinhas", type="smallint", nullable=true)
     */
    private $nuRelMaxlinhas;

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
     * @var integer
     *
     * @ORM\Column(name="nu_resolucao_grafico_h", type="smallint", nullable=false)
     */
    private $nuResolucaoGraficoH;

    /**
     * @var integer
     *
     * @ORM\Column(name="nu_resolucao_grafico_w", type="smallint", nullable=false)
     */
    private $nuResolucaoGraficoW;



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
     * Set inExibeErrosCriticos
     *
     * @param string $inExibeErrosCriticos
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * Set nuRelMaxlinhas
     *
     * @param integer $nuRelMaxlinhas
     * @return ConfiguracoesPadrao
     */
    public function setNuRelMaxlinhas($nuRelMaxlinhas)
    {
        $this->nuRelMaxlinhas = $nuRelMaxlinhas;
    
        return $this;
    }

    /**
     * Get nuRelMaxlinhas
     *
     * @return integer 
     */
    public function getNuRelMaxlinhas()
    {
        return $this->nuRelMaxlinhas;
    }

    /**
     * Set nuTimeoutSrcacic
     *
     * @param boolean $nuTimeoutSrcacic
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * Set csAbreJanelaPatr
     *
     * @param string $csAbreJanelaPatr
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * @return ConfiguracoesPadrao
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
     * Set nuResolucaoGraficoH
     *
     * @param integer $nuResolucaoGraficoH
     * @return ConfiguracoesPadrao
     */
    public function setNuResolucaoGraficoH($nuResolucaoGraficoH)
    {
        $this->nuResolucaoGraficoH = $nuResolucaoGraficoH;
    
        return $this;
    }

    /**
     * Get nuResolucaoGraficoH
     *
     * @return integer 
     */
    public function getNuResolucaoGraficoH()
    {
        return $this->nuResolucaoGraficoH;
    }

    /**
     * Set nuResolucaoGraficoW
     *
     * @param integer $nuResolucaoGraficoW
     * @return ConfiguracoesPadrao
     */
    public function setNuResolucaoGraficoW($nuResolucaoGraficoW)
    {
        $this->nuResolucaoGraficoW = $nuResolucaoGraficoW;
    
        return $this;
    }

    /**
     * Get nuResolucaoGraficoW
     *
     * @return integer 
     */
    public function getNuResolucaoGraficoW()
    {
        return $this->nuResolucaoGraficoW;
    }
}