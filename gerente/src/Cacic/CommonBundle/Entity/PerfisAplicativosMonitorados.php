<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PerfisAplicativosMonitorados
 *
 * @ORM\Table(name="perfis_aplicativos_monitorados")
 *  @ORM\Entity(repositoryClass="Cacic\CommonBundle\Entity\PerfisAplicativosMonitoradosRepository")
 */
class PerfisAplicativosMonitorados
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_aplicativo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAplicativo;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_aplicativo", type="string", length=100, nullable=false)
     */
    private $nmAplicativo;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_car_inst_w9x", type="string", length=2, nullable=true)
     */
    private $csCarInstW9x;

    /**
     * @var string
     *
     * @ORM\Column(name="te_car_inst_w9x", type="string", length=255, nullable=true)
     */
    private $teCarInstW9x;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_car_ver_w9x", type="string", length=2, nullable=true)
     */
    private $csCarVerW9x;

    /**
     * @var string
     *
     * @ORM\Column(name="te_car_ver_w9x", type="string", length=255, nullable=true)
     */
    private $teCarVerW9x;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_car_inst_wnt", type="string", length=2, nullable=true)
     */
    private $csCarInstWnt;

    /**
     * @var string
     *
     * @ORM\Column(name="te_car_inst_wnt", type="string", length=255, nullable=true)
     */
    private $teCarInstWnt;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_car_ver_wnt", type="string", length=2, nullable=true)
     */
    private $csCarVerWnt;

    /**
     * @var string
     *
     * @ORM\Column(name="te_car_ver_wnt", type="string", length=255, nullable=true)
     */
    private $teCarVerWnt;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_ide_licenca", type="string", length=2, nullable=true)
     */
    private $csIdeLicenca;

    /**
     * @var string
     *
     * @ORM\Column(name="te_ide_licenca", type="string", length=255, nullable=true)
     */
    private $teIdeLicenca;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_atualizacao", type="datetime", nullable=false)
     */
    private $dtAtualizacao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_arq_ver_eng_w9x", type="string", length=100, nullable=true)
     */
    private $teArqVerEngW9x;

    /**
     * @var string
     *
     * @ORM\Column(name="te_arq_ver_pat_w9x", type="string", length=100, nullable=true)
     */
    private $teArqVerPatW9x;

    /**
     * @var string
     *
     * @ORM\Column(name="te_arq_ver_eng_wnt", type="string", length=100, nullable=true)
     */
    private $teArqVerEngWnt;

    /**
     * @var string
     *
     * @ORM\Column(name="te_arq_ver_pat_wnt", type="string", length=100, nullable=true)
     */
    private $teArqVerPatWnt;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dir_padrao_w9x", type="string", length=100, nullable=true)
     */
    private $teDirPadraoW9x;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dir_padrao_wnt", type="string", length=100, nullable=true)
     */
    private $teDirPadraoWnt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=true)
     */
    private $idSo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_descritivo", type="text", nullable=true)
     */
    private $teDescritivo;

    /**
     * @var string
     *
     * @ORM\Column(name="in_disponibiliza_info", type="string", length=1, nullable=true)
     */
    private $inDisponibilizaInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="in_disponibiliza_info_usuario_comum", type="string", length=1, nullable=false)
     */
    private $inDisponibilizaInfoUsuarioComum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_registro", type="datetime", nullable=true)
     */
    private $dtRegistro;

    /**
     * @var \So
     *
     * @ORM\ManyToOne(targetEntity="So", inversedBy="perfisAplicativos")
     * @ORM\JoinColumn(name="id_so", referencedColumnName="id_so")
     */
    private $so;

    /**
     * Get idAplicativo
     *
     * @return integer 
     */
    public function getIdAplicativo()
    {
        return $this->idAplicativo;
    }

    /**
     * Set nmAplicativo
     *
     * @param string $nmAplicativo
     * @return PerfisAplicativosMonitorados
     */
    public function setNmAplicativo($nmAplicativo)
    {
        $this->nmAplicativo = $nmAplicativo;
    
        return $this;
    }

    /**
     * Get nmAplicativo
     *
     * @return string 
     */
    public function getNmAplicativo()
    {
        return $this->nmAplicativo;
    }

    /**
     * Set csCarInstW9x
     *
     * @param string $csCarInstW9x
     * @return PerfisAplicativosMonitorados
     */
    public function setCsCarInstW9x($csCarInstW9x)
    {
        $this->csCarInstW9x = $csCarInstW9x;
    
        return $this;
    }

    /**
     * Get csCarInstW9x
     *
     * @return string 
     */
    public function getCsCarInstW9x()
    {
        return $this->csCarInstW9x;
    }

    /**
     * Set teCarInstW9x
     *
     * @param string $teCarInstW9x
     * @return PerfisAplicativosMonitorados
     */
    public function setTeCarInstW9x($teCarInstW9x)
    {
        $this->teCarInstW9x = $teCarInstW9x;
    
        return $this;
    }

    /**
     * Get teCarInstW9x
     *
     * @return string 
     */
    public function getTeCarInstW9x()
    {
        return $this->teCarInstW9x;
    }

    /**
     * Set csCarVerW9x
     *
     * @param string $csCarVerW9x
     * @return PerfisAplicativosMonitorados
     */
    public function setCsCarVerW9x($csCarVerW9x)
    {
        $this->csCarVerW9x = $csCarVerW9x;
    
        return $this;
    }

    /**
     * Get csCarVerW9x
     *
     * @return string 
     */
    public function getCsCarVerW9x()
    {
        return $this->csCarVerW9x;
    }

    /**
     * Set teCarVerW9x
     *
     * @param string $teCarVerW9x
     * @return PerfisAplicativosMonitorados
     */
    public function setTeCarVerW9x($teCarVerW9x)
    {
        $this->teCarVerW9x = $teCarVerW9x;
    
        return $this;
    }

    /**
     * Get teCarVerW9x
     *
     * @return string 
     */
    public function getTeCarVerW9x()
    {
        return $this->teCarVerW9x;
    }

    /**
     * Set csCarInstWnt
     *
     * @param string $csCarInstWnt
     * @return PerfisAplicativosMonitorados
     */
    public function setCsCarInstWnt($csCarInstWnt)
    {
        $this->csCarInstWnt = $csCarInstWnt;
    
        return $this;
    }

    /**
     * Get csCarInstWnt
     *
     * @return string 
     */
    public function getCsCarInstWnt()
    {
        return $this->csCarInstWnt;
    }

    /**
     * Set teCarInstWnt
     *
     * @param string $teCarInstWnt
     * @return PerfisAplicativosMonitorados
     */
    public function setTeCarInstWnt($teCarInstWnt)
    {
        $this->teCarInstWnt = $teCarInstWnt;
    
        return $this;
    }

    /**
     * Get teCarInstWnt
     *
     * @return string 
     */
    public function getTeCarInstWnt()
    {
        return $this->teCarInstWnt;
    }

    /**
     * Set csCarVerWnt
     *
     * @param string $csCarVerWnt
     * @return PerfisAplicativosMonitorados
     */
    public function setCsCarVerWnt($csCarVerWnt)
    {
        $this->csCarVerWnt = $csCarVerWnt;
    
        return $this;
    }

    /**
     * Get csCarVerWnt
     *
     * @return string 
     */
    public function getCsCarVerWnt()
    {
        return $this->csCarVerWnt;
    }

    /**
     * Set teCarVerWnt
     *
     * @param string $teCarVerWnt
     * @return PerfisAplicativosMonitorados
     */
    public function setTeCarVerWnt($teCarVerWnt)
    {
        $this->teCarVerWnt = $teCarVerWnt;
    
        return $this;
    }

    /**
     * Get teCarVerWnt
     *
     * @return string 
     */
    public function getTeCarVerWnt()
    {
        return $this->teCarVerWnt;
    }

    /**
     * Set csIdeLicenca
     *
     * @param string $csIdeLicenca
     * @return PerfisAplicativosMonitorados
     */
    public function setCsIdeLicenca($csIdeLicenca)
    {
        $this->csIdeLicenca = $csIdeLicenca;
    
        return $this;
    }

    /**
     * Get csIdeLicenca
     *
     * @return string 
     */
    public function getCsIdeLicenca()
    {
        return $this->csIdeLicenca;
    }

    /**
     * Set teIdeLicenca
     *
     * @param string $teIdeLicenca
     * @return PerfisAplicativosMonitorados
     */
    public function setTeIdeLicenca($teIdeLicenca)
    {
        $this->teIdeLicenca = $teIdeLicenca;
    
        return $this;
    }

    /**
     * Get teIdeLicenca
     *
     * @return string 
     */
    public function getTeIdeLicenca()
    {
        return $this->teIdeLicenca;
    }

    /**
     * Set dtAtualizacao
     *
     * @param \DateTime $dtAtualizacao
     * @return PerfisAplicativosMonitorados
     */
    public function setDtAtualizacao($dtAtualizacao)
    {
        $this->dtAtualizacao = $dtAtualizacao;
    
        return $this;
    }

    /**
     * Get dtAtualizacao
     *
     * @return \DateTime 
     */
    public function getDtAtualizacao()
    {
        return $this->dtAtualizacao;
    }

    /**
     * Set teArqVerEngW9x
     *
     * @param string $teArqVerEngW9x
     * @return PerfisAplicativosMonitorados
     */
    public function setTeArqVerEngW9x($teArqVerEngW9x)
    {
        $this->teArqVerEngW9x = $teArqVerEngW9x;
    
        return $this;
    }

    /**
     * Get teArqVerEngW9x
     *
     * @return string 
     */
    public function getTeArqVerEngW9x()
    {
        return $this->teArqVerEngW9x;
    }

    /**
     * Set teArqVerPatW9x
     *
     * @param string $teArqVerPatW9x
     * @return PerfisAplicativosMonitorados
     */
    public function setTeArqVerPatW9x($teArqVerPatW9x)
    {
        $this->teArqVerPatW9x = $teArqVerPatW9x;
    
        return $this;
    }

    /**
     * Get teArqVerPatW9x
     *
     * @return string 
     */
    public function getTeArqVerPatW9x()
    {
        return $this->teArqVerPatW9x;
    }

    /**
     * Set teArqVerEngWnt
     *
     * @param string $teArqVerEngWnt
     * @return PerfisAplicativosMonitorados
     */
    public function setTeArqVerEngWnt($teArqVerEngWnt)
    {
        $this->teArqVerEngWnt = $teArqVerEngWnt;
    
        return $this;
    }

    /**
     * Get teArqVerEngWnt
     *
     * @return string 
     */
    public function getTeArqVerEngWnt()
    {
        return $this->teArqVerEngWnt;
    }

    /**
     * Set teArqVerPatWnt
     *
     * @param string $teArqVerPatWnt
     * @return PerfisAplicativosMonitorados
     */
    public function setTeArqVerPatWnt($teArqVerPatWnt)
    {
        $this->teArqVerPatWnt = $teArqVerPatWnt;
    
        return $this;
    }

    /**
     * Get teArqVerPatWnt
     *
     * @return string 
     */
    public function getTeArqVerPatWnt()
    {
        return $this->teArqVerPatWnt;
    }

    /**
     * Set teDirPadraoW9x
     *
     * @param string $teDirPadraoW9x
     * @return PerfisAplicativosMonitorados
     */
    public function setTeDirPadraoW9x($teDirPadraoW9x)
    {
        $this->teDirPadraoW9x = $teDirPadraoW9x;
    
        return $this;
    }

    /**
     * Get teDirPadraoW9x
     *
     * @return string 
     */
    public function getTeDirPadraoW9x()
    {
        return $this->teDirPadraoW9x;
    }

    /**
     * Set teDirPadraoWnt
     *
     * @param string $teDirPadraoWnt
     * @return PerfisAplicativosMonitorados
     */
    public function setTeDirPadraoWnt($teDirPadraoWnt)
    {
        $this->teDirPadraoWnt = $teDirPadraoWnt;
    
        return $this;
    }

    /**
     * Get teDirPadraoWnt
     *
     * @return string 
     */
    public function getTeDirPadraoWnt()
    {
        return $this->teDirPadraoWnt;
    }

    /**
     * Set idSo
     *
     * @param integer $idSo
     * @return PerfisAplicativosMonitorados
     */
    public function setIdSo($idSo)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

    /**
     * Get idSo
     *
     * @return integer 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }

    /**
     * Set teDescritivo
     *
     * @param string $teDescritivo
     * @return PerfisAplicativosMonitorados
     */
    public function setTeDescritivo($teDescritivo)
    {
        $this->teDescritivo = $teDescritivo;
    
        return $this;
    }

    /**
     * Get teDescritivo
     *
     * @return string 
     */
    public function getTeDescritivo()
    {
        return $this->teDescritivo;
    }

    /**
     * Set inDisponibilizaInfo
     *
     * @param string $inDisponibilizaInfo
     * @return PerfisAplicativosMonitorados
     */
    public function setInDisponibilizaInfo($inDisponibilizaInfo)
    {
        $this->inDisponibilizaInfo = $inDisponibilizaInfo;
    
        return $this;
    }

    /**
     * Get inDisponibilizaInfo
     *
     * @return string 
     */
    public function getInDisponibilizaInfo()
    {
        return $this->inDisponibilizaInfo;
    }

    /**
     * Set inDisponibilizaInfoUsuarioComum
     *
     * @param string $inDisponibilizaInfoUsuarioComum
     * @return PerfisAplicativosMonitorados
     */
    public function setInDisponibilizaInfoUsuarioComum($inDisponibilizaInfoUsuarioComum)
    {
        $this->inDisponibilizaInfoUsuarioComum = $inDisponibilizaInfoUsuarioComum;
    
        return $this;
    }

    /**
     * Get inDisponibilizaInfoUsuarioComum
     *
     * @return string 
     */
    public function getInDisponibilizaInfoUsuarioComum()
    {
        return $this->inDisponibilizaInfoUsuarioComum;
    }

    /**
     * Set dtRegistro
     *
     * @param \DateTime $dtRegistro
     * @return PerfisAplicativosMonitorados
     */
    public function setDtRegistro($dtRegistro)
    {
        $this->dtRegistro = $dtRegistro;
    
        return $this;
    }

    /**
     * Get dtRegistro
     *
     * @return \DateTime 
     */
    public function getDtRegistro()
    {
        return $this->dtRegistro;
    }
    /**
     * Set so
     *
     * @param \Cacic\CommonBundle\Entity\So $so
     * @return PerfisAplicativosMonitorados
     */
    public function setSo( \Cacic\CommonBundle\Entity\So $so = null )
    {
        $this->so = $so;

        return $this;
    }

    /**
     *
     * Get so
     * @return \Cacic\CommonBundle\Entity\So
     */
    public function getSo()
    {
        return $this->so;
    }
}