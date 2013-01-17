<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsbVendors
 *
 * @ORM\Table(name="usb_vendors")
 * @ORM\Entity
 */
class UsbVendors
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_usb_vendor", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUsbVendor;

    /**
     * @var string
     *
     * @ORM\Column(name="id_vendor", type="string", length=5, nullable=false)
     */
    private $idVendor;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_vendor", type="string", length=127, nullable=false)
     */
    private $nmVendor;

    /**
     * @var string
     *
     * @ORM\Column(name="te_observacao", type="text", nullable=false)
     */
    private $teObservacao;

    /**
     * @var string
     *
     * @ORM\Column(name="dt_registro", type="string", length=12, nullable=true)
     */
    private $dtRegistro;



    /**
     * Get idUsbVendor
     *
     * @return integer 
     */
    public function getIdUsbVendor()
    {
        return $this->idUsbVendor;
    }

    /**
     * Set idVendor
     *
     * @param string $idVendor
     * @return UsbVendors
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
     * Set nmVendor
     *
     * @param string $nmVendor
     * @return UsbVendors
     */
    public function setNmVendor($nmVendor)
    {
        $this->nmVendor = $nmVendor;
    
        return $this;
    }

    /**
     * Get nmVendor
     *
     * @return string 
     */
    public function getNmVendor()
    {
        return $this->nmVendor;
    }

    /**
     * Set teObservacao
     *
     * @param string $teObservacao
     * @return UsbVendors
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
     * @return UsbVendors
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
}