<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConfiguracaoLocal
 */
class ConfiguracaoLocal
{
    /**
     * @var string
     */
    private $teNotificarMudancasEmails;

    /**
     * @var string
     */
    private $teNotificarMudancasProperties;

    /**
     * @var string
     */
    private $teNotificarUtilizacaoUsb;

    /**
     * @var string
     */
    private $inExibeErrosCriticos;

    /**
     * @var string
     */
    private $inExibeBandeja;

    /**
     * @var integer
     */
    private $nuExecApos;

    /**
     * @var \DateTime
     */
    private $dtHrAlteracaoPatrimInterface;

    /**
     * @var \DateTime
     */
    private $dtHrAlteracaoPatrimUon1;

    /**
     * @var \DateTime
     */
    private $dtHrAlteracaoPatrimUon1a;

    /**
     * @var \DateTime
     */
    private $dtHrAlteracaoPatrimUon2;

    /**
     * @var \DateTime
     */
    private $dtHrColetaForcada;

    /**
     * @var string
     */
    private $teNotificarMudancaPatrim;

    /**
     * @var string
     */
    private $nmOrganizacao;

    /**
     * @var boolean
     */
    private $nuTimeoutSrcacic;

    /**
     * @var integer
     */
    private $nuIntervaloExec;

    /**
     * @var integer
     */
    private $nuIntervaloRenovacaoPatrim;

    /**
     * @var string
     */
    private $teSenhaAdmAgente;

    /**
     * @var string
     */
    private $teServUpdatesPadrao;

    /**
     * @var string
     */
    private $teServCacicPadrao;

    /**
     * @var string
     */
    private $teEnderecosMacInvalidos;

    /**
     * @var string
     */
    private $teJanelasExcecao;

    /**
     * @var string
     */
    private $teNotaEmailGerentes;

    /**
     * @var string
     */
    private $csAbreJanelaPatr;

    /**
     * @var string
     */
    private $idDefaultBodyBgcolor;

    /**
     * @var string
     */
    private $teExibeGraficos;

    /**
     * @var string
     */
    private $nuPortaSrcacic;

    /**
     * @var string
     */
    private $teUsbFilter;

    /**
     * @var string
     */
    private $dtDebug;

    /**
     * @var \Cacic\CommonBundle\Entity\Local
     */
    private $idLocal;


    /**
     * Set teNotificarMudancasEmails
     *
     * @param string $teNotificarMudancasEmails
     * @return ConfiguracaoLocal
     */
    public function setTeNotificarMudancasEmails($teNotificarMudancasEmails)
    {
        $this->teNotificarMudancasEmails = $teNotificarMudancasEmails;
    
        return $this;
    }

    /**
     * Get teNotificarMudancasEmails
     *
     * @return string 
     */
    public function getTeNotificarMudancasEmails()
    {
        return $this->teNotificarMudancasEmails;
    }

    /**
     * Set teNotificarMudancasProperties
     *
     * @param string $teNotificarMudancasProperties
     * @return ConfiguracaoLocal
     */
    public function setTeNotificarMudancasProperties($teNotificarMudancasProperties)
    {
        $this->teNotificarMudancasProperties = $teNotificarMudancasProperties;
    
        return $this;
    }

    /**
     * Get teNotificarMudancasProperties
     *
     * @return string 
     */
    public function getTeNotificarMudancasProperties()
    {
        return $this->teNotificarMudancasProperties;
    }

    /**
     * Set teNotificarUtilizacaoUsb
     *
     * @param string $teNotificarUtilizacaoUsb
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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
     * @return ConfiguracaoLocal
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

    /**
     * Set dtDebug
     *
     * @param string $dtDebug
     * @return ConfiguracaoLocal
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
     * @param \Cacic\CommonBundle\Entity\Local $idLocal
     * @return ConfiguracaoLocal
     */
    public function setIdLocal(\Cacic\CommonBundle\Entity\Local $idLocal = null)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return \Cacic\CommonBundle\Entity\Local 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }
}