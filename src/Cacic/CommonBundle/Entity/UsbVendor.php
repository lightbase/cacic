<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsbVendor
 */
class UsbVendor
{
    /**
     * @var string
     */
    private $idUsbVendor;

    /**
     * @var string
     */
    private $nmUsbVendor;

    /**
     * @var string
     */
    private $teObservacao;

    /**
     * @var string
     */
    private $dtRegistro;


    /**
     * Get idUsbVendor
     *
     * @return string 
     */
    public function getIdUsbVendor()
    {
        return $this->idUsbVendor;
    }

    /**
     * Set nmUsbVendor
     *
     * @param string $nmUsbVendor
     * @return UsbVendor
     */
    public function setNmUsbVendor($nmUsbVendor)
    {
        $this->nmUsbVendor = $nmUsbVendor;
    
        return $this;
    }

    /**
     * Get nmUsbVendor
     *
     * @return string 
     */
    public function getNmUsbVendor()
    {
        return $this->nmUsbVendor;
    }

    /**
     * Set teObservacao
     *
     * @param string $teObservacao
     * @return UsbVendor
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
     * @return UsbVendor
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
     * @param string $idUsbVendor
     * @return UsbVendor
     */
    public function setIdUsbVendor($idUsbVendor)
    {
        $this->idUsbVendor = $idUsbVendor;
    
        return $this;
    }
}
