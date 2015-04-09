<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsbDevice
 */
class UsbDevice
{
    /**
     * @var string
     */
    private $idUsbDevice;

    /**
     * @var string
     */
    private $nmUsbDevice;

    /**
     * @var string
     */
    private $teObservacao;

    /**
     * @var string
     */
    private $dtRegistro;

    /**
     * @var \Cacic\CommonBundle\Entity\UsbVendor
     */
    private $idUsbVendor;
    
    /**
     * Set idUsbDevice
     *
     * @param string $idUsbDevice
     * @return UsbDevice
     */
    public function setIdUsbDevice( $idUsbDevice )
    {
    	$this->idUsbDevice = $idUsbDevice;
    	return $this;
    }


    /**
     * Get idUsbDevice
     *
     * @return string 
     */
    public function getIdUsbDevice()
    {
        return $this->idUsbDevice;
    }

    /**
     * Set nmUsbDevice
     *
     * @param string $nmUsbDevice
     * @return UsbDevice
     */
    public function setNmUsbDevice($nmUsbDevice)
    {
        $this->nmUsbDevice = $nmUsbDevice;
    
        return $this;
    }

    /**
     * Get nmUsbDevice
     *
     * @return string 
     */
    public function getNmUsbDevice()
    {
        return $this->nmUsbDevice;
    }

    /**
     * Set teObservacao
     *
     * @param string $teObservacao
     * @return UsbDevice
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
     * Set dtRegistro
     *
     * @param string $dtRegistro
     * @return UsbDevice
     */
    public function setDtRegistro($dtRegistro)
    {
        $this->dtRegistro = $dtRegistro;
    
        return $this;
    }

    /**
     * Get dtRegistro
     *
     * @return string 
     */
    public function getDtRegistro()
    {
        return $this->dtRegistro;
    }

    /**
     * Set idUsbVendor
     *
     * @param \Cacic\CommonBundle\Entity\UsbVendor $idUsbVendor
     * @return UsbDevice
     */
    public function setIdUsbVendor(\Cacic\CommonBundle\Entity\UsbVendor $idUsbVendor = null)
    {
        $this->idUsbVendor = $idUsbVendor;
    
        return $this;
    }

    /**
     * Get idUsbVendor
     *
     * @return \Cacic\CommonBundle\Entity\UsbVendor 
     */
    public function getIdUsbVendor()
    {
        return $this->idUsbVendor;
    }
    /**
     * @var string
     */
    private $idDevice;


    /**
     * Set idDevice
     *
     * @param string $idDevice
     * @return UsbDevice
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
}