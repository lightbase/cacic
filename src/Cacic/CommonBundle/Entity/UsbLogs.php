<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsbLogs
 *
 * @ORM\Table(name="usb_logs")
 * @ORM\Entity
 */
class UsbLogs
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_usb_log", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUsbLog;

    /**
     * @var string
     *
     * @ORM\Column(name="id_vendor", type="string", length=5, nullable=false)
     */
    private $idVendor;

    /**
     * @var string
     *
     * @ORM\Column(name="id_device", type="string", length=5, nullable=false)
     */
    private $idDevice;

    /**
     * @var string
     *
     * @ORM\Column(name="dt_event", type="string", length=14, nullable=false)
     */
    private $dtEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_event", type="string", length=1, nullable=false)
     */
    private $csEvent;

    /**
     * @var \Computadores
     *
     * @ORM\ManyToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;



    /**
     * Get idUsbLog
     *
     * @return integer 
     */
    public function getIdUsbLog()
    {
        return $this->idUsbLog;
    }

    /**
     * Set idVendor
     *
     * @param string $idVendor
     * @return UsbLogs
     */
    public function setIdVendor($idVendor)
    {
        $this->idVendor = $idVendor;
    
        return $this;
    }

    /**
     * Get idVendor
     *
     * @return string 
     */
    public function getIdVendor()
    {
        return $this->idVendor;
    }

    /**
     * Set idDevice
     *
     * @param string $idDevice
     * @return UsbLogs
     */
    public function setIdDevice($idDevice)
    {
        $this->idDevice = $idDevice;
    
        return $this;
    }

    /**
     * Get idDevice
     *
     * @return string 
     */
    public function getIdDevice()
    {
        return $this->idDevice;
    }

    /**
     * Set dtEvent
     *
     * @param string $dtEvent
     * @return UsbLogs
     */
    public function setDtEvent($dtEvent)
    {
        $this->dtEvent = $dtEvent;
    
        return $this;
    }

    /**
     * Get dtEvent
     *
     * @return string 
     */
    public function getDtEvent()
    {
        return $this->dtEvent;
    }

    /**
     * Set csEvent
     *
     * @param string $csEvent
     * @return UsbLogs
     */
    public function setCsEvent($csEvent)
    {
        $this->csEvent = $csEvent;
    
        return $this;
    }

    /**
     * Get csEvent
     *
     * @return string 
     */
    public function getCsEvent()
    {
        return $this->csEvent;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return UsbLogs
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computadores $idComputador = null)
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