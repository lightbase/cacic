<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rede
 */
class Rede
{
    /**
     * @var integer
     */
    private $idRede;

    /**
     * @var string
     */
    private $teIpRede;

    /**
     * @var string
     */
    private $nmRede;

    /**
     * @var string
     */
    private $teObservacao;

    /**
     * @var string
     */
    private $nmPessoaContato1;

    /**
     * @var string
     */
    private $nmPessoaContato2;

    /**
     * @var string
     */
    private $nuTelefone1;

    /**
     * @var string
     */
    private $teEmailContato2;

    /**
     * @var string
     */
    private $nuTelefone2;

    /**
     * @var string
     */
    private $teEmailContato1;

    /**
     * @var string
     */
    private $teServCacic;

    /**
     * @var string
     */
    private $teServUpdates;

    /**
     * @var string
     */
    private $tePathServUpdates;

    /**
     * @var string
     */
    private $nmUsuarioLoginServUpdates;

    /**
     * @var string
     */
    private $teSenhaLoginServUpdates;

    /**
     * @var string
     */
    private $nuPortaServUpdates;

    /**
     * @var string
     */
    private $teMascaraRede;

    /**
     * @var \DateTime
     */
    private $dtVerificaUpdates;

    /**
     * @var string
     */
    private $nmUsuarioLoginServUpdatesGerente;

    /**
     * @var string
     */
    private $teSenhaLoginServUpdatesGerente;

    /**
     * @var integer
     */
    private $nuLimiteFtp;

    /**
     * @var string
     */
    private $csPermitirDesativarSrcacic;

    /**
     * @var string
     */
    private $teDebugging;

    /**
     * @var string
     */
    private $dtDebug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $acoes;

    /**
     * @var \Cacic\CommonBundle\Entity\Local
     */
    private $idLocal;

    /**
     * @var \Cacic\CommonBundle\Entity\ServidorAutenticacao
     */
    private $idServidorAutenticacao;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $idAplicativo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acoes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idAplicativo = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set teIpRede
     *
     * @param string $teIpRede
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * @return Rede
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
     * Set teDebugging
     *
     * @param string $teDebugging
     * @return Rede
     */
    public function setTeDebugging($teDebugging)
    {
        $this->teDebugging = $teDebugging;
    
        return $this;
    }

    /**
     * Get teDebugging
     *
     * @return string 
     */
    public function getTeDebugging()
    {
        return $this->teDebugging;
    }

    /**
     * Set dtDebug
     *
     * @param string $dtDebug
     * @return Rede
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
     * Add acoes
     *
     * @param \Cacic\CommonBundle\Entity\AcaoRede $acoes
     * @return Rede
     */
    public function addAcoe(\Cacic\CommonBundle\Entity\AcaoRede $acoes)
    {
        $this->acoes[] = $acoes;
    
        return $this;
    }

    /**
     * Remove acoes
     *
     * @param \Cacic\CommonBundle\Entity\AcaoRede $acoes
     */
    public function removeAcoe(\Cacic\CommonBundle\Entity\AcaoRede $acoes)
    {
        $this->acoes->removeElement($acoes);
    }

    /**
     * Get acoes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAcoes()
    {
        return $this->acoes;
    }

    /**
     * Set idLocal
     *
     * @param \Cacic\CommonBundle\Entity\Local $idLocal
     * @return Rede
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

    /**
     * Set idServidorAutenticacao
     *
     * @param \Cacic\CommonBundle\Entity\ServidorAutenticacao $idServidorAutenticacao
     * @return Rede
     */
    public function setIdServidorAutenticacao(\Cacic\CommonBundle\Entity\ServidorAutenticacao $idServidorAutenticacao = null)
    {
        $this->idServidorAutenticacao = $idServidorAutenticacao;
    
        return $this;
    }

    /**
     * Get idServidorAutenticacao
     *
     * @return \Cacic\CommonBundle\Entity\ServidorAutenticacao 
     */
    public function getIdServidorAutenticacao()
    {
        return $this->idServidorAutenticacao;
    }

    /**
     * Add idAplicativo
     *
     * @param \Cacic\CommonBundle\Entity\Aplicativo $idAplicativo
     * @return Rede
     */
    public function addIdAplicativo(\Cacic\CommonBundle\Entity\Aplicativo $idAplicativo)
    {
        $this->idAplicativo[] = $idAplicativo;
    
        return $this;
    }

    /**
     * Remove idAplicativo
     *
     * @param \Cacic\CommonBundle\Entity\Aplicativo $idAplicativo
     */
    public function removeIdAplicativo(\Cacic\CommonBundle\Entity\Aplicativo $idAplicativo)
    {
        $this->idAplicativo->removeElement($idAplicativo);
    }

    /**
     * Get idAplicativo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdAplicativo()
    {
        return $this->idAplicativo;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $uorgs;


    /**
     * Add uorgs
     *
     * @param \Cacic\CommonBundle\Entity\Uorg $uorgs
     * @return Rede
     */
    public function addUorg(\Cacic\CommonBundle\Entity\Uorg $uorgs)
    {
        $this->uorgs[] = $uorgs;
    
        return $this;
    }

    /**
     * Remove uorgs
     *
     * @param \Cacic\CommonBundle\Entity\Uorg $uorgs
     */
    public function removeUorg(\Cacic\CommonBundle\Entity\Uorg $uorgs)
    {
        $this->uorgs->removeElement($uorgs);
    }

    /**
     * Get uorgs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUorgs()
    {
        return $this->uorgs;
    }
    /**
     * @var string
     */
    private $downloadMethod;


    /**
     * Set downloadMethod
     *
     * @param string $downloadMethod
     * @return Rede
     */
    public function setDownloadMethod($downloadMethod)
    {
        $this->downloadMethod = $downloadMethod;

        return $this;
    }

    /**
     * Get downloadMethod
     *
     * @return string 
     * Caso não esteja cadastrado retorna FTP por padrão
     */
    public function getDownloadMethod()
    {
        if (empty($this->downloadMethod)) {
            return "ftp";
        } else {
            return $this->downloadMethod;
        }
    }

    /**
     * Add acoes
     *
     * @param \Cacic\CommonBundle\Entity\AcaoRede $acoes
     * @return Rede
     */
    public function addAco(\Cacic\CommonBundle\Entity\AcaoRede $acoes)
    {
        $this->acoes[] = $acoes;

        return $this;
    }

    /**
     * Remove acoes
     *
     * @param \Cacic\CommonBundle\Entity\AcaoRede $acoes
     */
    public function removeAco(\Cacic\CommonBundle\Entity\AcaoRede $acoes)
    {
        $this->acoes->removeElement($acoes);
    }
}
