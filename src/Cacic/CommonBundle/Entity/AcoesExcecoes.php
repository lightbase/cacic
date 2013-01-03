<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AcoesExcecoes
 *
 * @ORM\Table(name="acoes_excecoes")
 * @ORM\Entity
 */
class AcoesExcecoes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_acao_excecao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAcaoExcecao;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     */
    private $idLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     */
    private $teNodeAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="id_acao", type="string", length=20, nullable=false)
     */
    private $idAcao;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     */
    private $idSo;



    /**
     * Get idAcaoExcecao
     *
     * @return integer 
     */
    public function getIdAcaoExcecao()
    {
        return $this->idAcaoExcecao;
    }

    /**
     * Set idLocal
     *
     * @param integer $idLocal
     * @return AcoesExcecoes
     */
    public function setIdLocal($idLocal)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return integer 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return AcoesExcecoes
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
     * Set idAcao
     *
     * @param string $idAcao
     * @return AcoesExcecoes
     */
    public function setIdAcao($idAcao)
    {
        $this->idAcao = $idAcao;
    
        return $this;
    }

    /**
     * Get idAcao
     *
     * @return string 
     */
    public function getIdAcao()
    {
        return $this->idAcao;
    }

    /**
     * Set idSo
     *
     * @param integer $idSo
     * @return AcoesExcecoes
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
}