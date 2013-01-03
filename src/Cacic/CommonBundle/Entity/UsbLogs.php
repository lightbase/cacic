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
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     */
    private $teNodeAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     */
    private $idSo;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_event", type="string", length=1, nullable=false)
     */
    private $csEvent;



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
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return UsbLogs
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
     * @return UsbLogs
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
}