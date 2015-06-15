<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ErrosAgente
 */
class ErrosAgente
{
    /**
     * @var integer
     */
    private $idErrosAgente;

    /**
     * @var string
     */
    private $timestampErro;

    /**
     * @var string
     */
    private $nivelErro;

    /**
     * @var string
     */
    private $mensagem;


    /**
     * Set idErrosAgente
     *
     * @param integer $idErrosAgente
     * @return ErrosAgente
     */
    public function setIdErrosAgente($idErrosAgente)
    {
        $this->idErrosAgente = $idErrosAgente;

        return $this;
    }

    /**
     * Get idErrosAgente
     *
     * @return integer 
     */
    public function getIdErrosAgente()
    {
        return $this->idErrosAgente;
    }

    /**
     * Set timestampErro
     *
     * @param string $timestampErro
     * @return ErrosAgente
     */
    public function setTimestampErro($timestampErro)
    {
        $this->timestampErro = $timestampErro;

        return $this;
    }

    /**
     * Get timestampErro
     *
     * @return string 
     */
    public function getTimestampErro()
    {
        return $this->timestampErro;
    }

    /**
     * Set nivelErro
     *
     * @param string $nivelErro
     * @return ErrosAgente
     */
    public function setNivelErro($nivelErro)
    {
        $this->nivelErro = $nivelErro;

        return $this;
    }

    /**
     * Get nivelErro
     *
     * @return string 
     */
    public function getNivelErro()
    {
        return $this->nivelErro;
    }

    /**
     * Set mensagem
     *
     * @param string $mensagem
     * @return ErrosAgente
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    /**
     * Get mensagem
     *
     * @return string 
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }
    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $computador;


    /**
     * Set computador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $computador
     * @return ErrosAgente
     */
    public function setComputador(\Cacic\CommonBundle\Entity\Computador $computador)
    {
        $this->computador = $computador;

        return $this;
    }

    /**
     * Get computador
     *
     * @return \Cacic\CommonBundle\Entity\Computador 
     */
    public function getComputador()
    {
        return $this->computador;
    }
}
