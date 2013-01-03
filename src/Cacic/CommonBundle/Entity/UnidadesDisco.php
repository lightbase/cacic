<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnidadesDisco
 *
 * @ORM\Table(name="unidades_disco")
 * @ORM\Entity
 */
class UnidadesDisco
{
    /**
     * @var string
     *
     * @ORM\Column(name="te_letra", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $teLetra;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $teNodeAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="id_tipo_unid_disco", type="string", length=1, nullable=true)
     */
    private $idTipoUnidDisco;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_serial", type="string", length=12, nullable=true)
     */
    private $nuSerial;

    /**
     * @var integer
     *
     * @ORM\Column(name="nu_capacidade", type="integer", nullable=true)
     */
    private $nuCapacidade;

    /**
     * @var integer
     *
     * @ORM\Column(name="nu_espaco_livre", type="integer", nullable=true)
     */
    private $nuEspacoLivre;

    /**
     * @var string
     *
     * @ORM\Column(name="te_unc", type="string", length=60, nullable=true)
     */
    private $teUnc;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_sist_arq", type="string", length=10, nullable=true)
     */
    private $csSistArq;



    /**
     * Set teLetra
     *
     * @param string $teLetra
     * @return UnidadesDisco
     */
    public function setTeLetra($teLetra)
    {
        $this->teLetra = $teLetra;
    
        return $this;
    }

    /**
     * Get teLetra
     *
     * @return string 
     */
    public function getTeLetra()
    {
        return $this->teLetra;
    }

    /**
     * Set idSo
     *
     * @param integer $idSo
     * @return UnidadesDisco
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

    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return UnidadesDisco
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
     * Set idTipoUnidDisco
     *
     * @param string $idTipoUnidDisco
     * @return UnidadesDisco
     */
    public function setIdTipoUnidDisco($idTipoUnidDisco)
    {
        $this->idTipoUnidDisco = $idTipoUnidDisco;
    
        return $this;
    }

    /**
     * Get idTipoUnidDisco
     *
     * @return string 
     */
    public function getIdTipoUnidDisco()
    {
        return $this->idTipoUnidDisco;
    }

    /**
     * Set nuSerial
     *
     * @param string $nuSerial
     * @return UnidadesDisco
     */
    public function setNuSerial($nuSerial)
    {
        $this->nuSerial = $nuSerial;
    
        return $this;
    }

    /**
     * Get nuSerial
     *
     * @return string 
     */
    public function getNuSerial()
    {
        return $this->nuSerial;
    }

    /**
     * Set nuCapacidade
     *
     * @param integer $nuCapacidade
     * @return UnidadesDisco
     */
    public function setNuCapacidade($nuCapacidade)
    {
        $this->nuCapacidade = $nuCapacidade;
    
        return $this;
    }

    /**
     * Get nuCapacidade
     *
     * @return integer 
     */
    public function getNuCapacidade()
    {
        return $this->nuCapacidade;
    }

    /**
     * Set nuEspacoLivre
     *
     * @param integer $nuEspacoLivre
     * @return UnidadesDisco
     */
    public function setNuEspacoLivre($nuEspacoLivre)
    {
        $this->nuEspacoLivre = $nuEspacoLivre;
    
        return $this;
    }

    /**
     * Get nuEspacoLivre
     *
     * @return integer 
     */
    public function getNuEspacoLivre()
    {
        return $this->nuEspacoLivre;
    }

    /**
     * Set teUnc
     *
     * @param string $teUnc
     * @return UnidadesDisco
     */
    public function setTeUnc($teUnc)
    {
        $this->teUnc = $teUnc;
    
        return $this;
    }

    /**
     * Get teUnc
     *
     * @return string 
     */
    public function getTeUnc()
    {
        return $this->teUnc;
    }

    /**
     * Set csSistArq
     *
     * @param string $csSistArq
     * @return UnidadesDisco
     */
    public function setCsSistArq($csSistArq)
    {
        $this->csSistArq = $csSistArq;
    
        return $this;
    }

    /**
     * Get csSistArq
     *
     * @return string 
     */
    public function getCsSistArq()
    {
        return $this->csSistArq;
    }
}