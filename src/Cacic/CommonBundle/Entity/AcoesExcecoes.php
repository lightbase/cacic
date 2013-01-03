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
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     */
    private $teNodeAddress;

    /**
     * @var \So
     *
     * @ORM\ManyToOne(targetEntity="So")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_so", referencedColumnName="id_so")
     * })
     */
    private $idSo;

    /**
     * @var \Locais
     *
     * @ORM\ManyToOne(targetEntity="Locais")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_local", referencedColumnName="id_local")
     * })
     */
    private $idLocal;

    /**
     * @var \Acoes
     *
     * @ORM\ManyToOne(targetEntity="Acoes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_acao", referencedColumnName="id_acao")
     * })
     */
    private $idAcao;



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
     * Set idSo
     *
     * @param \Cacic\CommonBundle\Entity\So $idSo
     * @return AcoesExcecoes
     */
    public function setIdSo(\Cacic\CommonBundle\Entity\So $idSo = null)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

    /**
     * Get idSo
     *
     * @return \Cacic\CommonBundle\Entity\So 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }

    /**
     * Set idLocal
     *
     * @param \Cacic\CommonBundle\Entity\Locais $idLocal
     * @return AcoesExcecoes
     */
    public function setIdLocal(\Cacic\CommonBundle\Entity\Locais $idLocal = null)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return \Cacic\CommonBundle\Entity\Locais 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set idAcao
     *
     * @param \Cacic\CommonBundle\Entity\Acoes $idAcao
     * @return AcoesExcecoes
     */
    public function setIdAcao(\Cacic\CommonBundle\Entity\Acoes $idAcao = null)
    {
        $this->idAcao = $idAcao;
    
        return $this;
    }

    /**
     * Get idAcao
     *
     * @return \Cacic\CommonBundle\Entity\Acoes 
     */
    public function getIdAcao()
    {
        return $this->idAcao;
    }
}