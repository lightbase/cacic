<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * LogAcesso
 */
class LogAcesso
{
    /**
     * @var integer
     */
    private $idLogAcesso;

    /**
     * @var \DateTime
     */
    private $data;

    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $idComputador;

    /**
     * Get idLogAcesso
     *
     * @return integer 
     */
    public function getIdLogAcesso()
    {
        return $this->idLogAcesso;
    }

    /**
     * Set data
     *
     * @param \DateTime $data
     * @return LogAcesso
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $idComputador
     * @return LogAcesso
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

    /**
     * @PrePersist
     */
    public function onPrePersistSetRegistrationDate()
    {
        $this->data = new \DateTime();
    }

    /**
     * @var string
     */
    private $usuario;

    /**
     * Set usuario
     *
     * @param string $usuario
     * @return LogAcesso
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
