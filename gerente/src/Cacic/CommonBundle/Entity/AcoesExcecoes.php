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
     * @ORM\Column(name="id_rede", type="integer", nullable=false)
     */
    private $idRede;

    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     */
    private $teNodeAddress;

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
     * Set idRede
     *
     * @param integer $idRede
     * @return AcoesExcecoes
     */
    public function setIdRede($idRede)
    {
        $this->idRede = $idRede;
    
        return $this;
    }

    /**
     * Get idRede
     *
     * @return integer 
     */
    public function getIdRede()
    {
        return $this->idRede;
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