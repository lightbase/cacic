<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Computadores
 *
 * @ORM\Table(name="computadores")
 * @ORM\Entity
 */
class Computadores
{
    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $teNodeAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_so", type="string", length=50, nullable=true)
     */
    private $teSo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_nome_computador", type="string", length=50, nullable=true)
     */
    private $teNomeComputador;

    /**
     * @var string
     *
     * @ORM\Column(name="id_ip_rede", type="string", length=15, nullable=false)
     */
    private $idIpRede;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dominio_windows", type="string", length=50, nullable=true)
     */
    private $teDominioWindows;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dominio_dns", type="string", length=50, nullable=true)
     */
    private $teDominioDns;

    /**
     * @var string
     *
     * @ORM\Column(name="te_placa_video_desc", type="string", length=100, nullable=true)
     */
    private $tePlacaVideoDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_ip", type="string", length=15, nullable=true)
     */
    private $teIp;

    /**
     * @var string
     *
     * @ORM\Column(name="te_mascara", type="string", length=15, nullable=true)
     */
    private $teMascara;

    /**
     * @var string
     *
     * @ORM\Column(name="te_nome_host", type="string", length=50, nullable=true)
     */
    private $teNomeHost;

    /**
     * @var string
     *
     * @ORM\Column(name="te_placa_rede_desc", type="string", length=100, nullable=true)
     */
    private $tePlacaRedeDesc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_inclusao", type="datetime", nullable=true)
     */
    private $dtHrInclusao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_gateway", type="string", length=15, nullable=true)
     */
    private $teGateway;

    /**
     * @var string
     *
     * @ORM\Column(name="te_wins_primario", type="string", length=15, nullable=true)
     */
    private $teWinsPrimario;

    /**
     * @var string
     *
     * @ORM\Column(name="te_cpu_desc", type="string", length=100, nullable=true)
     */
    private $teCpuDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_wins_secundario", type="string", length=15, nullable=true)
     */
    private $teWinsSecundario;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dns_primario", type="string", length=15, nullable=true)
     */
    private $teDnsPrimario;

    /**
     * @var integer
     *
     * @ORM\Column(name="qt_placa_video_mem", type="integer", nullable=true)
     */
    private $qtPlacaVideoMem;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dns_secundario", type="string", length=15, nullable=true)
     */
    private $teDnsSecundario;

    /**
     * @var string
     *
     * @ORM\Column(name="te_placa_mae_desc", type="string", length=100, nullable=true)
     */
    private $tePlacaMaeDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_serv_dhcp", type="string", length=15, nullable=true)
     */
    private $teServDhcp;

    /**
     * @var integer
     *
     * @ORM\Column(name="qt_mem_ram", type="integer", nullable=true)
     */
    private $qtMemRam;

    /**
     * @var string
     *
     * @ORM\Column(name="te_cpu_serial", type="string", length=50, nullable=true)
     */
    private $teCpuSerial;

    /**
     * @var string
     *
     * @ORM\Column(name="te_cpu_fabricante", type="string", length=100, nullable=true)
     */
    private $teCpuFabricante;

    /**
     * @var string
     *
     * @ORM\Column(name="te_cpu_freq", type="string", length=6, nullable=true)
     */
    private $teCpuFreq;

    /**
     * @var string
     *
     * @ORM\Column(name="te_mem_ram_desc", type="string", length=200, nullable=true)
     */
    private $teMemRamDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_bios_desc", type="string", length=100, nullable=true)
     */
    private $teBiosDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_bios_data", type="string", length=10, nullable=true)
     */
    private $teBiosData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_ult_acesso", type="datetime", nullable=true)
     */
    private $dtHrUltAcesso;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_cacic", type="string", length=10, nullable=true)
     */
    private $teVersaoCacic;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_gercols", type="string", length=10, nullable=true)
     */
    private $teVersaoGercols;

    /**
     * @var string
     *
     * @ORM\Column(name="te_bios_fabricante", type="string", length=100, nullable=true)
     */
    private $teBiosFabricante;

    /**
     * @var string
     *
     * @ORM\Column(name="te_palavra_chave", type="string", length=30, nullable=false)
     */
    private $tePalavraChave;

    /**
     * @var string
     *
     * @ORM\Column(name="te_placa_mae_fabricante", type="string", length=100, nullable=true)
     */
    private $tePlacaMaeFabricante;

    /**
     * @var integer
     *
     * @ORM\Column(name="qt_placa_video_cores", type="integer", nullable=true)
     */
    private $qtPlacaVideoCores;

    /**
     * @var string
     *
     * @ORM\Column(name="te_placa_video_resolucao", type="string", length=10, nullable=true)
     */
    private $tePlacaVideoResolucao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_placa_som_desc", type="string", length=100, nullable=true)
     */
    private $tePlacaSomDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_cdrom_desc", type="string", length=100, nullable=true)
     */
    private $teCdromDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_teclado_desc", type="string", length=100, nullable=false)
     */
    private $teTecladoDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_mouse_desc", type="string", length=100, nullable=true)
     */
    private $teMouseDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_modem_desc", type="string", length=100, nullable=true)
     */
    private $teModemDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_workgroup", type="string", length=50, nullable=true)
     */
    private $teWorkgroup;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_coleta_forcada_estacao", type="datetime", nullable=true)
     */
    private $dtHrColetaForcadaEstacao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_nomes_curtos_modulos", type="string", length=255, nullable=true)
     */
    private $teNomesCurtosModulos;

    /**
     * @var string
     *
     * @ORM\Column(name="te_origem_mac", type="text", nullable=true)
     */
    private $teOrigemMac;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_conta", type="integer", nullable=true)
     */
    private $idConta;



    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return Computadores
     */
    public function setTeNodeAddress($teNodeAddress)
    {
        $this->teNodeAddress = $teNodeAddress;
    
        return $this;
    }

    /**
     * Get teNodeAddress
     *
     * @return string 
     */
    public function getTeNodeAddress()
    {
        return $this->teNodeAddress;
    }

    /**
     * Set idSo
     *
     * @param integer $idSo
     * @return Computadores
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
     * Set teSo
     *
     * @param string $teSo
     * @return Computadores
     */
    public function setTeSo($teSo)
    {
        $this->teSo = $teSo;
    
        return $this;
    }

    /**
     * Get teSo
     *
     * @return string 
     */
    public function getTeSo()
    {
        return $this->teSo;
    }

    /**
     * Set teNomeComputador
     *
     * @param string $teNomeComputador
     * @return Computadores
     */
    public function setTeNomeComputador($teNomeComputador)
    {
        $this->teNomeComputador = $teNomeComputador;
    
        return $this;
    }

    /**
     * Get teNomeComputador
     *
     * @return string 
     */
    public function getTeNomeComputador()
    {
        return $this->teNomeComputador;
    }

    /**
     * Set idIpRede
     *
     * @param string $idIpRede
     * @return Computadores
     */
    public function setIdIpRede($idIpRede)
    {
        $this->idIpRede = $idIpRede;
    
        return $this;
    }

    /**
     * Get idIpRede
     *
     * @return string 
     */
    public function getIdIpRede()
    {
        return $this->idIpRede;
    }

    /**
     * Set teDominioWindows
     *
     * @param string $teDominioWindows
     * @return Computadores
     */
    public function setTeDominioWindows($teDominioWindows)
    {
        $this->teDominioWindows = $teDominioWindows;
    
        return $this;
    }

    /**
     * Get teDominioWindows
     *
     * @return string 
     */
    public function getTeDominioWindows()
    {
        return $this->teDominioWindows;
    }

    /**
     * Set teDominioDns
     *
     * @param string $teDominioDns
     * @return Computadores
     */
    public function setTeDominioDns($teDominioDns)
    {
        $this->teDominioDns = $teDominioDns;
    
        return $this;
    }

    /**
     * Get teDominioDns
     *
     * @return string 
     */
    public function getTeDominioDns()
    {
        return $this->teDominioDns;
    }

    /**
     * Set tePlacaVideoDesc
     *
     * @param string $tePlacaVideoDesc
     * @return Computadores
     */
    public function setTePlacaVideoDesc($tePlacaVideoDesc)
    {
        $this->tePlacaVideoDesc = $tePlacaVideoDesc;
    
        return $this;
    }

    /**
     * Get tePlacaVideoDesc
     *
     * @return string 
     */
    public function getTePlacaVideoDesc()
    {
        return $this->tePlacaVideoDesc;
    }

    /**
     * Set teIp
     *
     * @param string $teIp
     * @return Computadores
     */
    public function setTeIp($teIp)
    {
        $this->teIp = $teIp;
    
        return $this;
    }

    /**
     * Get teIp
     *
     * @return string 
     */
    public function getTeIp()
    {
        return $this->teIp;
    }

    /**
     * Set teMascara
     *
     * @param string $teMascara
     * @return Computadores
     */
    public function setTeMascara($teMascara)
    {
        $this->teMascara = $teMascara;
    
        return $this;
    }

    /**
     * Get teMascara
     *
     * @return string 
     */
    public function getTeMascara()
    {
        return $this->teMascara;
    }

    /**
     * Set teNomeHost
     *
     * @param string $teNomeHost
     * @return Computadores
     */
    public function setTeNomeHost($teNomeHost)
    {
        $this->teNomeHost = $teNomeHost;
    
        return $this;
    }

    /**
     * Get teNomeHost
     *
     * @return string 
     */
    public function getTeNomeHost()
    {
        return $this->teNomeHost;
    }

    /**
     * Set tePlacaRedeDesc
     *
     * @param string $tePlacaRedeDesc
     * @return Computadores
     */
    public function setTePlacaRedeDesc($tePlacaRedeDesc)
    {
        $this->tePlacaRedeDesc = $tePlacaRedeDesc;
    
        return $this;
    }

    /**
     * Get tePlacaRedeDesc
     *
     * @return string 
     */
    public function getTePlacaRedeDesc()
    {
        return $this->tePlacaRedeDesc;
    }

    /**
     * Set dtHrInclusao
     *
     * @param \DateTime $dtHrInclusao
     * @return Computadores
     */
    public function setDtHrInclusao($dtHrInclusao)
    {
        $this->dtHrInclusao = $dtHrInclusao;
    
        return $this;
    }

    /**
     * Get dtHrInclusao
     *
     * @return \DateTime 
     */
    public function getDtHrInclusao()
    {
        return $this->dtHrInclusao;
    }

    /**
     * Set teGateway
     *
     * @param string $teGateway
     * @return Computadores
     */
    public function setTeGateway($teGateway)
    {
        $this->teGateway = $teGateway;
    
        return $this;
    }

    /**
     * Get teGateway
     *
     * @return string 
     */
    public function getTeGateway()
    {
        return $this->teGateway;
    }

    /**
     * Set teWinsPrimario
     *
     * @param string $teWinsPrimario
     * @return Computadores
     */
    public function setTeWinsPrimario($teWinsPrimario)
    {
        $this->teWinsPrimario = $teWinsPrimario;
    
        return $this;
    }

    /**
     * Get teWinsPrimario
     *
     * @return string 
     */
    public function getTeWinsPrimario()
    {
        return $this->teWinsPrimario;
    }

    /**
     * Set teCpuDesc
     *
     * @param string $teCpuDesc
     * @return Computadores
     */
    public function setTeCpuDesc($teCpuDesc)
    {
        $this->teCpuDesc = $teCpuDesc;
    
        return $this;
    }

    /**
     * Get teCpuDesc
     *
     * @return string 
     */
    public function getTeCpuDesc()
    {
        return $this->teCpuDesc;
    }

    /**
     * Set teWinsSecundario
     *
     * @param string $teWinsSecundario
     * @return Computadores
     */
    public function setTeWinsSecundario($teWinsSecundario)
    {
        $this->teWinsSecundario = $teWinsSecundario;
    
        return $this;
    }

    /**
     * Get teWinsSecundario
     *
     * @return string 
     */
    public function getTeWinsSecundario()
    {
        return $this->teWinsSecundario;
    }

    /**
     * Set teDnsPrimario
     *
     * @param string $teDnsPrimario
     * @return Computadores
     */
    public function setTeDnsPrimario($teDnsPrimario)
    {
        $this->teDnsPrimario = $teDnsPrimario;
    
        return $this;
    }

    /**
     * Get teDnsPrimario
     *
     * @return string 
     */
    public function getTeDnsPrimario()
    {
        return $this->teDnsPrimario;
    }

    /**
     * Set qtPlacaVideoMem
     *
     * @param integer $qtPlacaVideoMem
     * @return Computadores
     */
    public function setQtPlacaVideoMem($qtPlacaVideoMem)
    {
        $this->qtPlacaVideoMem = $qtPlacaVideoMem;
    
        return $this;
    }

    /**
     * Get qtPlacaVideoMem
     *
     * @return integer 
     */
    public function getQtPlacaVideoMem()
    {
        return $this->qtPlacaVideoMem;
    }

    /**
     * Set teDnsSecundario
     *
     * @param string $teDnsSecundario
     * @return Computadores
     */
    public function setTeDnsSecundario($teDnsSecundario)
    {
        $this->teDnsSecundario = $teDnsSecundario;
    
        return $this;
    }

    /**
     * Get teDnsSecundario
     *
     * @return string 
     */
    public function getTeDnsSecundario()
    {
        return $this->teDnsSecundario;
    }

    /**
     * Set tePlacaMaeDesc
     *
     * @param string $tePlacaMaeDesc
     * @return Computadores
     */
    public function setTePlacaMaeDesc($tePlacaMaeDesc)
    {
        $this->tePlacaMaeDesc = $tePlacaMaeDesc;
    
        return $this;
    }

    /**
     * Get tePlacaMaeDesc
     *
     * @return string 
     */
    public function getTePlacaMaeDesc()
    {
        return $this->tePlacaMaeDesc;
    }

    /**
     * Set teServDhcp
     *
     * @param string $teServDhcp
     * @return Computadores
     */
    public function setTeServDhcp($teServDhcp)
    {
        $this->teServDhcp = $teServDhcp;
    
        return $this;
    }

    /**
     * Get teServDhcp
     *
     * @return string 
     */
    public function getTeServDhcp()
    {
        return $this->teServDhcp;
    }

    /**
     * Set qtMemRam
     *
     * @param integer $qtMemRam
     * @return Computadores
     */
    public function setQtMemRam($qtMemRam)
    {
        $this->qtMemRam = $qtMemRam;
    
        return $this;
    }

    /**
     * Get qtMemRam
     *
     * @return integer 
     */
    public function getQtMemRam()
    {
        return $this->qtMemRam;
    }

    /**
     * Set teCpuSerial
     *
     * @param string $teCpuSerial
     * @return Computadores
     */
    public function setTeCpuSerial($teCpuSerial)
    {
        $this->teCpuSerial = $teCpuSerial;
    
        return $this;
    }

    /**
     * Get teCpuSerial
     *
     * @return string 
     */
    public function getTeCpuSerial()
    {
        return $this->teCpuSerial;
    }

    /**
     * Set teCpuFabricante
     *
     * @param string $teCpuFabricante
     * @return Computadores
     */
    public function setTeCpuFabricante($teCpuFabricante)
    {
        $this->teCpuFabricante = $teCpuFabricante;
    
        return $this;
    }

    /**
     * Get teCpuFabricante
     *
     * @return string 
     */
    public function getTeCpuFabricante()
    {
        return $this->teCpuFabricante;
    }

    /**
     * Set teCpuFreq
     *
     * @param string $teCpuFreq
     * @return Computadores
     */
    public function setTeCpuFreq($teCpuFreq)
    {
        $this->teCpuFreq = $teCpuFreq;
    
        return $this;
    }

    /**
     * Get teCpuFreq
     *
     * @return string 
     */
    public function getTeCpuFreq()
    {
        return $this->teCpuFreq;
    }

    /**
     * Set teMemRamDesc
     *
     * @param string $teMemRamDesc
     * @return Computadores
     */
    public function setTeMemRamDesc($teMemRamDesc)
    {
        $this->teMemRamDesc = $teMemRamDesc;
    
        return $this;
    }

    /**
     * Get teMemRamDesc
     *
     * @return string 
     */
    public function getTeMemRamDesc()
    {
        return $this->teMemRamDesc;
    }

    /**
     * Set teBiosDesc
     *
     * @param string $teBiosDesc
     * @return Computadores
     */
    public function setTeBiosDesc($teBiosDesc)
    {
        $this->teBiosDesc = $teBiosDesc;
    
        return $this;
    }

    /**
     * Get teBiosDesc
     *
     * @return string 
     */
    public function getTeBiosDesc()
    {
        return $this->teBiosDesc;
    }

    /**
     * Set teBiosData
     *
     * @param string $teBiosData
     * @return Computadores
     */
    public function setTeBiosData($teBiosData)
    {
        $this->teBiosData = $teBiosData;
    
        return $this;
    }

    /**
     * Get teBiosData
     *
     * @return string 
     */
    public function getTeBiosData()
    {
        return $this->teBiosData;
    }

    /**
     * Set dtHrUltAcesso
     *
     * @param \DateTime $dtHrUltAcesso
     * @return Computadores
     */
    public function setDtHrUltAcesso($dtHrUltAcesso)
    {
        $this->dtHrUltAcesso = $dtHrUltAcesso;
    
        return $this;
    }

    /**
     * Get dtHrUltAcesso
     *
     * @return \DateTime 
     */
    public function getDtHrUltAcesso()
    {
        return $this->dtHrUltAcesso;
    }

    /**
     * Set teVersaoCacic
     *
     * @param string $teVersaoCacic
     * @return Computadores
     */
    public function setTeVersaoCacic($teVersaoCacic)
    {
        $this->teVersaoCacic = $teVersaoCacic;
    
        return $this;
    }

    /**
     * Get teVersaoCacic
     *
     * @return string 
     */
    public function getTeVersaoCacic()
    {
        return $this->teVersaoCacic;
    }

    /**
     * Set teVersaoGercols
     *
     * @param string $teVersaoGercols
     * @return Computadores
     */
    public function setTeVersaoGercols($teVersaoGercols)
    {
        $this->teVersaoGercols = $teVersaoGercols;
    
        return $this;
    }

    /**
     * Get teVersaoGercols
     *
     * @return string 
     */
    public function getTeVersaoGercols()
    {
        return $this->teVersaoGercols;
    }

    /**
     * Set teBiosFabricante
     *
     * @param string $teBiosFabricante
     * @return Computadores
     */
    public function setTeBiosFabricante($teBiosFabricante)
    {
        $this->teBiosFabricante = $teBiosFabricante;
    
        return $this;
    }

    /**
     * Get teBiosFabricante
     *
     * @return string 
     */
    public function getTeBiosFabricante()
    {
        return $this->teBiosFabricante;
    }

    /**
     * Set tePalavraChave
     *
     * @param string $tePalavraChave
     * @return Computadores
     */
    public function setTePalavraChave($tePalavraChave)
    {
        $this->tePalavraChave = $tePalavraChave;
    
        return $this;
    }

    /**
     * Get tePalavraChave
     *
     * @return string 
     */
    public function getTePalavraChave()
    {
        return $this->tePalavraChave;
    }

    /**
     * Set tePlacaMaeFabricante
     *
     * @param string $tePlacaMaeFabricante
     * @return Computadores
     */
    public function setTePlacaMaeFabricante($tePlacaMaeFabricante)
    {
        $this->tePlacaMaeFabricante = $tePlacaMaeFabricante;
    
        return $this;
    }

    /**
     * Get tePlacaMaeFabricante
     *
     * @return string 
     */
    public function getTePlacaMaeFabricante()
    {
        return $this->tePlacaMaeFabricante;
    }

    /**
     * Set qtPlacaVideoCores
     *
     * @param integer $qtPlacaVideoCores
     * @return Computadores
     */
    public function setQtPlacaVideoCores($qtPlacaVideoCores)
    {
        $this->qtPlacaVideoCores = $qtPlacaVideoCores;
    
        return $this;
    }

    /**
     * Get qtPlacaVideoCores
     *
     * @return integer 
     */
    public function getQtPlacaVideoCores()
    {
        return $this->qtPlacaVideoCores;
    }

    /**
     * Set tePlacaVideoResolucao
     *
     * @param string $tePlacaVideoResolucao
     * @return Computadores
     */
    public function setTePlacaVideoResolucao($tePlacaVideoResolucao)
    {
        $this->tePlacaVideoResolucao = $tePlacaVideoResolucao;
    
        return $this;
    }

    /**
     * Get tePlacaVideoResolucao
     *
     * @return string 
     */
    public function getTePlacaVideoResolucao()
    {
        return $this->tePlacaVideoResolucao;
    }

    /**
     * Set tePlacaSomDesc
     *
     * @param string $tePlacaSomDesc
     * @return Computadores
     */
    public function setTePlacaSomDesc($tePlacaSomDesc)
    {
        $this->tePlacaSomDesc = $tePlacaSomDesc;
    
        return $this;
    }

    /**
     * Get tePlacaSomDesc
     *
     * @return string 
     */
    public function getTePlacaSomDesc()
    {
        return $this->tePlacaSomDesc;
    }

    /**
     * Set teCdromDesc
     *
     * @param string $teCdromDesc
     * @return Computadores
     */
    public function setTeCdromDesc($teCdromDesc)
    {
        $this->teCdromDesc = $teCdromDesc;
    
        return $this;
    }

    /**
     * Get teCdromDesc
     *
     * @return string 
     */
    public function getTeCdromDesc()
    {
        return $this->teCdromDesc;
    }

    /**
     * Set teTecladoDesc
     *
     * @param string $teTecladoDesc
     * @return Computadores
     */
    public function setTeTecladoDesc($teTecladoDesc)
    {
        $this->teTecladoDesc = $teTecladoDesc;
    
        return $this;
    }

    /**
     * Get teTecladoDesc
     *
     * @return string 
     */
    public function getTeTecladoDesc()
    {
        return $this->teTecladoDesc;
    }

    /**
     * Set teMouseDesc
     *
     * @param string $teMouseDesc
     * @return Computadores
     */
    public function setTeMouseDesc($teMouseDesc)
    {
        $this->teMouseDesc = $teMouseDesc;
    
        return $this;
    }

    /**
     * Get teMouseDesc
     *
     * @return string 
     */
    public function getTeMouseDesc()
    {
        return $this->teMouseDesc;
    }

    /**
     * Set teModemDesc
     *
     * @param string $teModemDesc
     * @return Computadores
     */
    public function setTeModemDesc($teModemDesc)
    {
        $this->teModemDesc = $teModemDesc;
    
        return $this;
    }

    /**
     * Get teModemDesc
     *
     * @return string 
     */
    public function getTeModemDesc()
    {
        return $this->teModemDesc;
    }

    /**
     * Set teWorkgroup
     *
     * @param string $teWorkgroup
     * @return Computadores
     */
    public function setTeWorkgroup($teWorkgroup)
    {
        $this->teWorkgroup = $teWorkgroup;
    
        return $this;
    }

    /**
     * Get teWorkgroup
     *
     * @return string 
     */
    public function getTeWorkgroup()
    {
        return $this->teWorkgroup;
    }

    /**
     * Set dtHrColetaForcadaEstacao
     *
     * @param \DateTime $dtHrColetaForcadaEstacao
     * @return Computadores
     */
    public function setDtHrColetaForcadaEstacao($dtHrColetaForcadaEstacao)
    {
        $this->dtHrColetaForcadaEstacao = $dtHrColetaForcadaEstacao;
    
        return $this;
    }

    /**
     * Get dtHrColetaForcadaEstacao
     *
     * @return \DateTime 
     */
    public function getDtHrColetaForcadaEstacao()
    {
        return $this->dtHrColetaForcadaEstacao;
    }

    /**
     * Set teNomesCurtosModulos
     *
     * @param string $teNomesCurtosModulos
     * @return Computadores
     */
    public function setTeNomesCurtosModulos($teNomesCurtosModulos)
    {
        $this->teNomesCurtosModulos = $teNomesCurtosModulos;
    
        return $this;
    }

    /**
     * Get teNomesCurtosModulos
     *
     * @return string 
     */
    public function getTeNomesCurtosModulos()
    {
        return $this->teNomesCurtosModulos;
    }

    /**
     * Set teOrigemMac
     *
     * @param string $teOrigemMac
     * @return Computadores
     */
    public function setTeOrigemMac($teOrigemMac)
    {
        $this->teOrigemMac = $teOrigemMac;
    
        return $this;
    }

    /**
     * Get teOrigemMac
     *
     * @return string 
     */
    public function getTeOrigemMac()
    {
        return $this->teOrigemMac;
    }

    /**
     * Set idConta
     *
     * @param integer $idConta
     * @return Computadores
     */
    public function setIdConta($idConta)
    {
        $this->idConta = $idConta;
    
        return $this;
    }

    /**
     * Get idConta
     *
     * @return integer 
     */
    public function getIdConta()
    {
        return $this->idConta;
    }
}