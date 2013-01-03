<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnidOrganizacionalNivel2
 *
 * @ORM\Table(name="unid_organizacional_nivel2")
 * @ORM\Entity
 */
class UnidOrganizacionalNivel2
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_unid_organizacional_nivel2", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idUnidOrganizacionalNivel2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_unid_organizacional_nivel1a", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idUnidOrganizacionalNivel1a;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_unid_organizacional_nivel2", type="string", length=50, nullable=false)
     */
    private $nmUnidOrganizacionalNivel2;

    /**
     * @var string
     *
     * @ORM\Column(name="te_endereco_uon2", type="string", length=80, nullable=true)
     */
    private $teEnderecoUon2;

    /**
     * @var string
     *
     * @ORM\Column(name="te_bairro_uon2", type="string", length=30, nullable=true)
     */
    private $teBairroUon2;

    /**
     * @var string
     *
     * @ORM\Column(name="te_cidade_uon2", type="string", length=50, nullable=true)
     */
    private $teCidadeUon2;

    /**
     * @var string
     *
     * @ORM\Column(name="te_uf_uon2", type="string", length=2, nullable=true)
     */
    private $teUfUon2;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_responsavel_uon2", type="string", length=80, nullable=true)
     */
    private $nmResponsavelUon2;

    /**
     * @var string
     *
     * @ORM\Column(name="te_email_responsavel_uon2", type="string", length=50, nullable=true)
     */
    private $teEmailResponsavelUon2;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_tel1_responsavel_uon2", type="string", length=10, nullable=true)
     */
    private $nuTel1ResponsavelUon2;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_tel2_responsavel_uon2", type="string", length=10, nullable=true)
     */
    private $nuTel2ResponsavelUon2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_registro", type="datetime", nullable=true)
     */
    private $dtRegistro;



    /**
     * Set idUnidOrganizacionalNivel2
     *
     * @param integer $idUnidOrganizacionalNivel2
     * @return UnidOrganizacionalNivel2
     */
    public function setIdUnidOrganizacionalNivel2($idUnidOrganizacionalNivel2)
    {
        $this->idUnidOrganizacionalNivel2 = $idUnidOrganizacionalNivel2;
    
        return $this;
    }

    /**
     * Get idUnidOrganizacionalNivel2
     *
     * @return integer 
     */
    public function getIdUnidOrganizacionalNivel2()
    {
        return $this->idUnidOrganizacionalNivel2;
    }

    /**
     * Set idUnidOrganizacionalNivel1a
     *
     * @param integer $idUnidOrganizacionalNivel1a
     * @return UnidOrganizacionalNivel2
     */
    public function setIdUnidOrganizacionalNivel1a($idUnidOrganizacionalNivel1a)
    {
        $this->idUnidOrganizacionalNivel1a = $idUnidOrganizacionalNivel1a;
    
        return $this;
    }

    /**
     * Get idUnidOrganizacionalNivel1a
     *
     * @return integer 
     */
    public function getIdUnidOrganizacionalNivel1a()
    {
        return $this->idUnidOrganizacionalNivel1a;
    }

    /**
     * Set idLocal
     *
     * @param integer $idLocal
     * @return UnidOrganizacionalNivel2
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
     * Set nmUnidOrganizacionalNivel2
     *
     * @param string $nmUnidOrganizacionalNivel2
     * @return UnidOrganizacionalNivel2
     */
    public function setNmUnidOrganizacionalNivel2($nmUnidOrganizacionalNivel2)
    {
        $this->nmUnidOrganizacionalNivel2 = $nmUnidOrganizacionalNivel2;
    
        return $this;
    }

    /**
     * Get nmUnidOrganizacionalNivel2
     *
     * @return string 
     */
    public function getNmUnidOrganizacionalNivel2()
    {
        return $this->nmUnidOrganizacionalNivel2;
    }

    /**
     * Set teEnderecoUon2
     *
     * @param string $teEnderecoUon2
     * @return UnidOrganizacionalNivel2
     */
    public function setTeEnderecoUon2($teEnderecoUon2)
    {
        $this->teEnderecoUon2 = $teEnderecoUon2;
    
        return $this;
    }

    /**
     * Get teEnderecoUon2
     *
     * @return string 
     */
    public function getTeEnderecoUon2()
    {
        return $this->teEnderecoUon2;
    }

    /**
     * Set teBairroUon2
     *
     * @param string $teBairroUon2
     * @return UnidOrganizacionalNivel2
     */
    public function setTeBairroUon2($teBairroUon2)
    {
        $this->teBairroUon2 = $teBairroUon2;
    
        return $this;
    }

    /**
     * Get teBairroUon2
     *
     * @return string 
     */
    public function getTeBairroUon2()
    {
        return $this->teBairroUon2;
    }

    /**
     * Set teCidadeUon2
     *
     * @param string $teCidadeUon2
     * @return UnidOrganizacionalNivel2
     */
    public function setTeCidadeUon2($teCidadeUon2)
    {
        $this->teCidadeUon2 = $teCidadeUon2;
    
        return $this;
    }

    /**
     * Get teCidadeUon2
     *
     * @return string 
     */
    public function getTeCidadeUon2()
    {
        return $this->teCidadeUon2;
    }

    /**
     * Set teUfUon2
     *
     * @param string $teUfUon2
     * @return UnidOrganizacionalNivel2
     */
    public function setTeUfUon2($teUfUon2)
    {
        $this->teUfUon2 = $teUfUon2;
    
        return $this;
    }

    /**
     * Get teUfUon2
     *
     * @return string 
     */
    public function getTeUfUon2()
    {
        return $this->teUfUon2;
    }

    /**
     * Set nmResponsavelUon2
     *
     * @param string $nmResponsavelUon2
     * @return UnidOrganizacionalNivel2
     */
    public function setNmResponsavelUon2($nmResponsavelUon2)
    {
        $this->nmResponsavelUon2 = $nmResponsavelUon2;
    
        return $this;
    }

    /**
     * Get nmResponsavelUon2
     *
     * @return string 
     */
    public function getNmResponsavelUon2()
    {
        return $this->nmResponsavelUon2;
    }

    /**
     * Set teEmailResponsavelUon2
     *
     * @param string $teEmailResponsavelUon2
     * @return UnidOrganizacionalNivel2
     */
    public function setTeEmailResponsavelUon2($teEmailResponsavelUon2)
    {
        $this->teEmailResponsavelUon2 = $teEmailResponsavelUon2;
    
        return $this;
    }

    /**
     * Get teEmailResponsavelUon2
     *
     * @return string 
     */
    public function getTeEmailResponsavelUon2()
    {
        return $this->teEmailResponsavelUon2;
    }

    /**
     * Set nuTel1ResponsavelUon2
     *
     * @param string $nuTel1ResponsavelUon2
     * @return UnidOrganizacionalNivel2
     */
    public function setNuTel1ResponsavelUon2($nuTel1ResponsavelUon2)
    {
        $this->nuTel1ResponsavelUon2 = $nuTel1ResponsavelUon2;
    
        return $this;
    }

    /**
     * Get nuTel1ResponsavelUon2
     *
     * @return string 
     */
    public function getNuTel1ResponsavelUon2()
    {
        return $this->nuTel1ResponsavelUon2;
    }

    /**
     * Set nuTel2ResponsavelUon2
     *
     * @param string $nuTel2ResponsavelUon2
     * @return UnidOrganizacionalNivel2
     */
    public function setNuTel2ResponsavelUon2($nuTel2ResponsavelUon2)
    {
        $this->nuTel2ResponsavelUon2 = $nuTel2ResponsavelUon2;
    
        return $this;
    }

    /**
     * Get nuTel2ResponsavelUon2
     *
     * @return string 
     */
    public function getNuTel2ResponsavelUon2()
    {
        return $this->nuTel2ResponsavelUon2;
    }

    /**
     * Set dtRegistro
     *
     * @param \DateTime $dtRegistro
     * @return UnidOrganizacionalNivel2
     */
    public function setDtRegistro($dtRegistro)
    {
        $this->dtRegistro = $dtRegistro;
    
        return $this;
    }

    /**
     * Get dtRegistro
     *
     * @return \DateTime 
     */
    public function getDtRegistro()
    {
        return $this->dtRegistro;
    }
}