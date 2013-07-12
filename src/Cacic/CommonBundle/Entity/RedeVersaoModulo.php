<?php

namespace Cacic\CommonBundle\Entity;

use Cacic\CommonBundle\CacicCommonBundle;
use Doctrine\ORM\Mapping as ORM;

/**
 * RedeVersaoModulo
 */
class RedeVersaoModulo
{
    /**
     * @var integer
     */
    private $idRedeVersaoModulo;

    /**
     * @var string
     */
    private $nmModulo;

    /**
     * @var string
     */
    private $teVersaoModulo;

    /**
     * @var \DateTime
     */
    private $dtAtualizacao;

    /**
     * @var string
     */
    private $csTipoSo;

    /**
     * @var string
     */
    private $teHash;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $idRede;

    public $iniFile;


    /**
     * Get idRedeVersaoModulo
     *
     * @return integer 
     */
    public function getIdRedeVersaoModulo()
    {
        return $this->idRedeVersaoModulo;
    }

    /**
     * Set nmModulo
     *
     * @param string $nmModulo
     * @return RedeVersaoModulo
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
     * @return RedeVersaoModulo
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
     * @return RedeVersaoModulo
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
     * @return RedeVersaoModulo
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
     * @return RedeVersaoModulo
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

    /**
     * Set idRede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $idRede
     * @return RedeVersaoModulo
     */
    public function setIdRede(\Cacic\CommonBundle\Entity\Rede $idRede = null)
    {
        $this->idRede = $idRede;
    
        return $this;
    }

    /**
     * Get idRede
     *
     * @return \Cacic\CommonBundle\Entity\Rede 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }

    /**
     *  Método construtor
     */

    public function __construct($nmModulo = null, $teVersaoModulo = null, $dtAtualizacao = null, $csTipoSo = null, $teHash = null, $idRede)
    {
        $this->setNmModulo($nmModulo);
        $this->setTeVersaoModulo($teVersaoModulo);
        $this->setDtAtualizacao($dtAtualizacao);
        $this->setCsTipoSo($csTipoSo);
        $this->setTeHash($teHash);
        $this->setIdRede($idRede);
    }


}