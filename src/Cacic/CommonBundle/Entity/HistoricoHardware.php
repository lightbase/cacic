<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricoHardware
 *
 * @ORM\Table(name="historico_hardware")
 * @ORM\Entity
 */
class HistoricoHardware
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $dtHrAlteracao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_placa_video_desc", type="string", length=100, nullable=true)
     */
    private $tePlacaVideoDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_placa_rede_desc", type="string", length=100, nullable=true)
     */
    private $tePlacaRedeDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="te_cpu_desc", type="string", length=100, nullable=true)
     */
    private $teCpuDesc;

    /**
     * @var integer
     *
     * @ORM\Column(name="qt_placa_video_mem", type="integer", nullable=true)
     */
    private $qtPlacaVideoMem;

    /**
     * @var string
     *
     * @ORM\Column(name="te_placa_mae_desc", type="string", length=100, nullable=true)
     */
    private $tePlacaMaeDesc;

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
     * @ORM\Column(name="te_mem_ram_desc", type="string", length=100, nullable=true)
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
     * @var string
     *
     * @ORM\Column(name="te_bios_fabricante", type="string", length=100, nullable=true)
     */
    private $teBiosFabricante;

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
     * @ORM\Column(name="te_teclado_desc", type="string", length=100, nullable=true)
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
     * @ORM\Column(name="te_lic_win", type="string", length=50, nullable=true)
     */
    private $teLicWin;

    /**
     * @var string
     *
     * @ORM\Column(name="te_key_win", type="string", length=50, nullable=true)
     */
    private $teKeyWin;

    /**
     * @var \Computadores
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\OneToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;



    /**
     * Get dtHrAlteracao
     *
     * @return \DateTime 
     */
    public function getDtHrAlteracao()
    {
        return $this->dtHrAlteracao;
    }

    /**
     * Set tePlacaVideoDesc
     *
     * @param string $tePlacaVideoDesc
     * @return HistoricoHardware
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
     * Set tePlacaRedeDesc
     *
     * @param string $tePlacaRedeDesc
     * @return HistoricoHardware
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
     * Set teCpuDesc
     *
     * @param string $teCpuDesc
     * @return HistoricoHardware
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
     * Set qtPlacaVideoMem
     *
     * @param integer $qtPlacaVideoMem
     * @return HistoricoHardware
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
     * Set tePlacaMaeDesc
     *
     * @param string $tePlacaMaeDesc
     * @return HistoricoHardware
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
     * Set qtMemRam
     *
     * @param integer $qtMemRam
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * Set teBiosFabricante
     *
     * @param string $teBiosFabricante
     * @return HistoricoHardware
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
     * Set tePlacaMaeFabricante
     *
     * @param string $tePlacaMaeFabricante
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * @return HistoricoHardware
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
     * Set teLicWin
     *
     * @param string $teLicWin
     * @return HistoricoHardware
     */
    public function setTeLicWin($teLicWin)
    {
        $this->teLicWin = $teLicWin;
    
        return $this;
    }

    /**
     * Get teLicWin
     *
     * @return string 
     */
    public function getTeLicWin()
    {
        return $this->teLicWin;
    }

    /**
     * Set teKeyWin
     *
     * @param string $teKeyWin
     * @return HistoricoHardware
     */
    public function setTeKeyWin($teKeyWin)
    {
        $this->teKeyWin = $teKeyWin;
    
        return $this;
    }

    /**
     * Get teKeyWin
     *
     * @return string 
     */
    public function getTeKeyWin()
    {
        return $this->teKeyWin;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return HistoricoHardware
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computadores $idComputador)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computadores 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }
}