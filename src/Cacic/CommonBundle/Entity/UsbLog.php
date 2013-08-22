<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsbLog
 */
class UsbLog
{
    /**
     * @var integer
     */
    private $idUsbLog;

    /**
     * @var string
     */
    private $dtEvent;

    /**
     * @var string
     */
    private $csEvent;

    /**
     * @var \Cacic\CommonBundle\Entity\UsbVendor
     */
    private $idUsbVendor;

    /**
     * @var \Cacic\CommonBundle\Entity\UsbDevice
     */
    private $idUsbDevice;

    /**
     * @var \Cacic\CommonBundle\Entity\Computador
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
     * Set dtEvent
     *
     * @param string $dtEvent
     * @return UsbLog
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
     * @return UsbLog
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
     * Set idUsbDevice
     *
     * @param \Cacic\CommonBundle\Entity\UsbDevice $idUsbDevice
     * @return UsbLog
     */
    public function setIdUsbDevice(\Cacic\CommonBundle\Entity\UsbDevice $idUsbDevice = null)
    {
        $this->idUsbDevice = $idUsbDevice;
    
        return $this;
    }

    /**
     * Get idUsbDevice
     *
     * @return \Cacic\CommonBundle\Entity\UsbDevice 
     */
    public function getIdUsbDevice()
    {
        return $this->idUsbDevice;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $idComputador
     * @return UsbLog
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computador $idComputador = null)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computador 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }
}