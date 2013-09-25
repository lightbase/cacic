<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Teste
 */
class Teste
{
    /**
     * @var integer
     */
    private $idTransacao;

    /**
     * @var string
     */
    private $teLinha;


    /**
     * Get idTransacao
     *
     * @return integer 
     */
    public function getIdTransacao()
    {
        return $this->idTransacao;
    }

    /**
     * Set teLinha
     *
     * @param string $teLinha
     * @return Teste
     */
    public function setTeLinha($teLinha)
    {
        $this->teLinha = $teLinha;
    
        return $this;
    }

    /**
     * Get teLinha
     *
     * @return string 
     */
    public function getTeLinha()
    {
        return $this->teLinha;
    }
}