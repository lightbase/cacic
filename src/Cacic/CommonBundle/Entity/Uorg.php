<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Uorg
 */
class Uorg
{
    /**
     * @var integer
     */
    private $idUorg;

    /**
     * @var string
     */
    private $nmUorg;

    /**
     * @var string
     */
    private $teEndereco;

    /**
     * @var string
     */
    private $teBairro;

    /**
     * @var string
     */
    private $teCidade;

    /**
     * @var string
     */
    private $teUf;

    /**
     * @var string
     */
    private $nmResponsavel;

    /**
     * @var string
     */
    private $teResponsavelEmail;

    /**
     * @var string
     */
    private $nuResponsavelTel1;

    /**
     * @var string
     */
    private $nuResponsavelTel2;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $uorgFilhas;

    /**
     * @var \Cacic\CommonBundle\Entity\Uorg
     */
    private $uorgPai;
    
    /**
     * @var \Cacic\CommonBundle\Entity\TipoUorg
     */
    private $tipoUorg;
    
    /**
     * @var \Cacic\CommonBundle\Entity\Local
     */
    private $local;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uorgFilhas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get idUorg
     *
     * @return integer 
     */
    public function getIdUorg()
    {
        return $this->idUorg;
    }

    /**
     * Set nmUorg
     *
     * @param string $nmUorg
     * @return Uorg
     */
    public function setNmUorg($nmUorg)
    {
        $this->nmUorg = $nmUorg;
    
        return $this;
    }

    /**
     * Get nmUorg
     *
     * @return string 
     */
    public function getNmUorg()
    {
        return $this->nmUorg;
    }

    /**
     * Set teEndereco
     *
     * @param string $teEndereco
     * @return Uorg
     */
    public function setTeEndereco($teEndereco)
    {
        $this->teEndereco = $teEndereco;
    
        return $this;
    }

    /**
     * Get teEndereco
     *
     * @return string 
     */
    public function getTeEndereco()
    {
        return $this->teEndereco;
    }

    /**
     * Set teBairro
     *
     * @param string $teBairro
     * @return Uorg
     */
    public function setTeBairro($teBairro)
    {
        $this->teBairro = $teBairro;
    
        return $this;
    }

    /**
     * Get teBairro
     *
     * @return string 
     */
    public function getTeBairro()
    {
        return $this->teBairro;
    }

    /**
     * Set teCidade
     *
     * @param string $teCidade
     * @return Uorg
     */
    public function setTeCidade($teCidade)
    {
        $this->teCidade = $teCidade;
    
        return $this;
    }

    /**
     * Get teCidade
     *
     * @return string 
     */
    public function getTeCidade()
    {
        return $this->teCidade;
    }

    /**
     * Set teUf
     *
     * @param string $teUf
     * @return Uorg
     */
    public function setTeUf($teUf)
    {
        $this->teUf = $teUf;
    
        return $this;
    }

    /**
     * Get teUf
     *
     * @return string 
     */
    public function getTeUf()
    {
        return $this->teUf;
    }

    /**
     * Set nmResponsavel
     *
     * @param string $nmResponsavel
     * @return Uorg
     */
    public function setNmResponsavel($nmResponsavel)
    {
        $this->nmResponsavel = $nmResponsavel;
    
        return $this;
    }

    /**
     * Get nmResponsavel
     *
     * @return string 
     */
    public function getNmResponsavel()
    {
        return $this->nmResponsavel;
    }

    /**
     * Set teResponsavelEmail
     *
     * @param string $teResponsavelEmail
     * @return Uorg
     */
    public function setTeResponsavelEmail($teResponsavelEmail)
    {
        $this->teResponsavelEmail = $teResponsavelEmail;
    
        return $this;
    }

    /**
     * Get teResponsavelEmail
     *
     * @return string 
     */
    public function getTeResponsavelEmail()
    {
        return $this->teResponsavelEmail;
    }

    /**
     * Add uorgFilhas
     *
     * @param \Cacic\CommonBundle\Entity\Uorg $uorgFilhas
     * @return Uorg
     */
    public function addUorgFilha(\Cacic\CommonBundle\Entity\Uorg $uorgFilhas)
    {
        $this->uorgFilhas[] = $uorgFilhas;
    
        return $this;
    }

    /**
     * Remove uorgFilhas
     *
     * @param \Cacic\CommonBundle\Entity\Uorg $uorgFilhas
     */
    public function removeUorgFilha(\Cacic\CommonBundle\Entity\Uorg $uorgFilhas)
    {
        $this->uorgFilhas->removeElement($uorgFilhas);
    }

    /**
     * Get uorgFilhas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUorgFilhas()
    {
        return $this->uorgFilhas;
    }

    /**
     * Set uorgPai
     *
     * @param \Cacic\CommonBundle\Entity\Uorg $uorgPai
     * @return Uorg
     */
    public function setUorgPai(\Cacic\CommonBundle\Entity\Uorg $uorgPai = null)
    {
        $this->uorgPai = $uorgPai;
    
        return $this;
    }

    /**
     * Get uorgPai
     *
     * @return \Cacic\CommonBundle\Entity\Uorg 
     */
    public function getUorgPai()
    {
        return $this->uorgPai;
    }

    /**
     * Set tipoUorg
     *
     * @param \Cacic\CommonBundle\Entity\TipoUorg $tipoUorg
     * @return Uorg
     */
    public function setTipoUorg(\Cacic\CommonBundle\Entity\TipoUorg $tipoUorg = null)
    {
        $this->tipoUorg = $tipoUorg;
    
        return $this;
    }

    /**
     * Get tipoUorg
     *
     * @return \Cacic\CommonBundle\Entity\TipoUorg 
     */
    public function getTipoUorg()
    {
        return $this->tipoUorg;
    }
    
    /**
     * Set local
     *
     * @param \Cacic\CommonBundle\Entity\Local $local
     * @return Uorg
     */
    public function setLocal(\Cacic\CommonBundle\Entity\Local $local = null)
    {
        $this->local = $local;
    
        return $this;
    }

    /**
     * Get local
     *
     * @return \Cacic\CommonBundle\Entity\Local 
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set nuResponsavelTel1
     *
     * @param string $nuResponsavelTel1
     * @return Uorg
     */
    public function setNuResponsavelTel1($nuResponsavelTel1)
    {
        $this->nuResponsavelTel1 = $nuResponsavelTel1;
    
        return $this;
    }

    /**
     * Get nuResponsavelTel1
     *
     * @return string 
     */
    public function getNuResponsavelTel1()
    {
        return $this->nuResponsavelTel1;
    }

    /**
     * Set nuResponsavelTel2
     *
     * @param string $nuResponsavelTel2
     * @return Uorg
     */
    public function setNuResponsavelTel2($nuResponsavelTel2)
    {
        $this->nuResponsavelTel2 = $nuResponsavelTel2;
    
        return $this;
    }

    /**
     * Get nuResponsavelTel2
     *
     * @return string 
     */
    public function getNuResponsavelTel2()
    {
        return $this->nuResponsavelTel2;
    }
    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $rede;


    /**
     * Set rede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $rede
     * @return Uorg
     */
    public function setRede(\Cacic\CommonBundle\Entity\Rede $rede = null)
    {
        $this->rede = $rede;
    
        return $this;
    }

    /**
     * Get rede
     *
     * @return \Cacic\CommonBundle\Entity\Rede 
     */
    public function getRede()
    {
        return $this->rede;
    }
}