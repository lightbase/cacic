<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsbDevices
 *
 * @ORM\Table(name="usb_devices")
 * @ORM\Entity
 */
class UsbDevices
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_usb_device", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUsbDevice;

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
     * @ORM\Column(name="nm_device", type="string", length=127, nullable=false)
     */
    private $nmDevice;

    /**
     * @var string
     *
     * @ORM\Column(name="te_observacao", type="text", nullable=false)
     */
    private $teObservacao;



    /**
     * Get idUsbDevice
     *
     * @return integer 
     */
    public function getIdUsbDevice()
    {
        return $this->idUsbDevice;
    }

    /**
     * Set idVendor
     *
     * @param string $idVendor
     * @return UsbDevices
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
     * @return UsbDevices
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
     * Set nmDevice
     *
     * @param string $nmDevice
     * @return UsbDevices
     */
    public function setNmDevice($nmDevice)
    {
        $this->nmDevice = $nmDevice;
    
        return $this;
    }

    /**
     * Get nmDevice
     *
     * @return string 
     */
    public function getNmDevice()
    {
        return $this->nmDevice;
    }

    /**
     * Set teObservacao
     *
     * @param string $teObservacao
     * @return UsbDevices
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
}