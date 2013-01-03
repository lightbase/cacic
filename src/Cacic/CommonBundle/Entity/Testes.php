<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Testes
 *
 * @ORM\Table(name="testes")
 * @ORM\Entity
 */
class Testes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_transacao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTransacao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_linha", type="text", nullable=true)
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
     * @return Testes
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