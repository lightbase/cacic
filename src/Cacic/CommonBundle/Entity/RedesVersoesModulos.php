<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RedesVersoesModulos
 *
 * @ORM\Table(name="redes_versoes_modulos")
 * @ORM\Entity
 */
class RedesVersoesModulos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_rede", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idRede;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_modulo", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $nmModulo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_modulo", type="string", length=20, nullable=true)
     */
    private $teVersaoModulo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_atualizacao", type="datetime", nullable=false)
     */
    private $dtAtualizacao;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_tipo_so", type="string", length=20, nullable=false)
     */
    private $csTipoSo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_hash", type="string", length=40, nullable=true)
     */
    private $teHash;



    /**
     * Set idRede
     *
     * @param integer $idRede
     * @return RedesVersoesModulos
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
     * Set nmModulo
     *
     * @param string $nmModulo
     * @return RedesVersoesModulos
     */
    public function setNmModulo($nmModulo)
    {
        $this->nmModulo = $nmModulo;
    
        return $this;
    }

    /**
     * Get nmModulo
     *
     * @return string 
     */
    public function getNmModulo()
    {
        return $this->nmModulo;
    }

    /**
     * Set teVersaoModulo
     *
     * @param string $teVersaoModulo
     * @return RedesVersoesModulos
     */
    public function setTeVersaoModulo($teVersaoModulo)
    {
        $this->teVersaoModulo = $teVersaoModulo;
    
        return $this;
    }

    /**
     * Get teVersaoModulo
     *
     * @return string 
     */
    public function getTeVersaoModulo()
    {
        return $this->teVersaoModulo;
    }

    /**
     * Set dtAtualizacao
     *
     * @param \DateTime $dtAtualizacao
     * @return RedesVersoesModulos
     */
    public function setDtAtualizacao($dtAtualizacao)
    {
        $this->dtAtualizacao = $dtAtualizacao;
    
        return $this;
    }

    /**
     * Get dtAtualizacao
     *
     * @return \DateTime 
     */
    public function getDtAtualizacao()
    {
        return $this->dtAtualizacao;
    }

    /**
     * Set csTipoSo
     *
     * @param string $csTipoSo
     * @return RedesVersoesModulos
     */
    public function setCsTipoSo($csTipoSo)
    {
        $this->csTipoSo = $csTipoSo;
    
        return $this;
    }

    /**
     * Get csTipoSo
     *
     * @return string 
     */
    public function getCsTipoSo()
    {
        return $this->csTipoSo;
    }

    /**
     * Set teHash
     *
     * @param string $teHash
     * @return RedesVersoesModulos
     */
    public function setTeHash($teHash)
    {
        $this->teHash = $teHash;
    
        return $this;
    }

    /**
     * Get teHash
     *
     * @return string 
     */
    public function getTeHash()
    {
        return $this->teHash;
    }
}