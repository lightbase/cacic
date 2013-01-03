<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnidOrganizacionalNivel1
 *
 * @ORM\Table(name="unid_organizacional_nivel1")
 * @ORM\Entity
 */
class UnidOrganizacionalNivel1
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_unid_organizacional_nivel1", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUnidOrganizacionalNivel1;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_unid_organizacional_nivel1", type="string", length=50, nullable=true)
     */
    private $nmUnidOrganizacionalNivel1;

    /**
     * @var string
     *
     * @ORM\Column(name="te_endereco_uon1", type="string", length=80, nullable=true)
     */
    private $teEnderecoUon1;

    /**
     * @var string
     *
     * @ORM\Column(name="te_bairro_uon1", type="string", length=30, nullable=true)
     */
    private $teBairroUon1;

    /**
     * @var string
     *
     * @ORM\Column(name="te_cidade_uon1", type="string", length=50, nullable=true)
     */
    private $teCidadeUon1;

    /**
     * @var string
     *
     * @ORM\Column(name="te_uf_uon1", type="string", length=2, nullable=true)
     */
    private $teUfUon1;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_responsavel_uon1", type="string", length=80, nullable=true)
     */
    private $nmResponsavelUon1;

    /**
     * @var string
     *
     * @ORM\Column(name="te_email_responsavel_uon1", type="string", length=50, nullable=true)
     */
    private $teEmailResponsavelUon1;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_tel1_responsavel_uon1", type="string", length=10, nullable=true)
     */
    private $nuTel1ResponsavelUon1;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_tel2_responsavel_uon1", type="string", length=10, nullable=true)
     */
    private $nuTel2ResponsavelUon1;



    /**
     * Get idUnidOrganizacionalNivel1
     *
     * @return integer 
     */
    public function getIdUnidOrganizacionalNivel1()
    {
        return $this->idUnidOrganizacionalNivel1;
    }

    /**
     * Set nmUnidOrganizacionalNivel1
     *
     * @param string $nmUnidOrganizacionalNivel1
     * @return UnidOrganizacionalNivel1
     */
    public function setNmUnidOrganizacionalNivel1($nmUnidOrganizacionalNivel1)
    {
        $this->nmUnidOrganizacionalNivel1 = $nmUnidOrganizacionalNivel1;
    
        return $this;
    }

    /**
     * Get nmUnidOrganizacionalNivel1
     *
     * @return string 
     */
    public function getNmUnidOrganizacionalNivel1()
    {
        return $this->nmUnidOrganizacionalNivel1;
    }

    /**
     * Set teEnderecoUon1
     *
     * @param string $teEnderecoUon1
     * @return UnidOrganizacionalNivel1
     */
    public function setTeEnderecoUon1($teEnderecoUon1)
    {
        $this->teEnderecoUon1 = $teEnderecoUon1;
    
        return $this;
    }

    /**
     * Get teEnderecoUon1
     *
     * @return string 
     */
    public function getTeEnderecoUon1()
    {
        return $this->teEnderecoUon1;
    }

    /**
     * Set teBairroUon1
     *
     * @param string $teBairroUon1
     * @return UnidOrganizacionalNivel1
     */
    public function setTeBairroUon1($teBairroUon1)
    {
        $this->teBairroUon1 = $teBairroUon1;
    
        return $this;
    }

    /**
     * Get teBairroUon1
     *
     * @return string 
     */
    public function getTeBairroUon1()
    {
        return $this->teBairroUon1;
    }

    /**
     * Set teCidadeUon1
     *
     * @param string $teCidadeUon1
     * @return UnidOrganizacionalNivel1
     */
    public function setTeCidadeUon1($teCidadeUon1)
    {
        $this->teCidadeUon1 = $teCidadeUon1;
    
        return $this;
    }

    /**
     * Get teCidadeUon1
     *
     * @return string 
     */
    public function getTeCidadeUon1()
    {
        return $this->teCidadeUon1;
    }

    /**
     * Set teUfUon1
     *
     * @param string $teUfUon1
     * @return UnidOrganizacionalNivel1
     */
    public function setTeUfUon1($teUfUon1)
    {
        $this->teUfUon1 = $teUfUon1;
    
        return $this;
    }

    /**
     * Get teUfUon1
     *
     * @return string 
     */
    public function getTeUfUon1()
    {
        return $this->teUfUon1;
    }

    /**
     * Set nmResponsavelUon1
     *
     * @param string $nmResponsavelUon1
     * @return UnidOrganizacionalNivel1
     */
    public function setNmResponsavelUon1($nmResponsavelUon1)
    {
        $this->nmResponsavelUon1 = $nmResponsavelUon1;
    
        return $this;
    }

    /**
     * Get nmResponsavelUon1
     *
     * @return string 
     */
    public function getNmResponsavelUon1()
    {
        return $this->nmResponsavelUon1;
    }

    /**
     * Set teEmailResponsavelUon1
     *
     * @param string $teEmailResponsavelUon1
     * @return UnidOrganizacionalNivel1
     */
    public function setTeEmailResponsavelUon1($teEmailResponsavelUon1)
    {
        $this->teEmailResponsavelUon1 = $teEmailResponsavelUon1;
    
        return $this;
    }

    /**
     * Get teEmailResponsavelUon1
     *
     * @return string 
     */
    public function getTeEmailResponsavelUon1()
    {
        return $this->teEmailResponsavelUon1;
    }

    /**
     * Set nuTel1ResponsavelUon1
     *
     * @param string $nuTel1ResponsavelUon1
     * @return UnidOrganizacionalNivel1
     */
    public function setNuTel1ResponsavelUon1($nuTel1ResponsavelUon1)
    {
        $this->nuTel1ResponsavelUon1 = $nuTel1ResponsavelUon1;
    
        return $this;
    }

    /**
     * Get nuTel1ResponsavelUon1
     *
     * @return string 
     */
    public function getNuTel1ResponsavelUon1()
    {
        return $this->nuTel1ResponsavelUon1;
    }

    /**
     * Set nuTel2ResponsavelUon1
     *
     * @param string $nuTel2ResponsavelUon1
     * @return UnidOrganizacionalNivel1
     */
    public function setNuTel2ResponsavelUon1($nuTel2ResponsavelUon1)
    {
        $this->nuTel2ResponsavelUon1 = $nuTel2ResponsavelUon1;
    
        return $this;
    }

    /**
     * Get nuTel2ResponsavelUon1
     *
     * @return string 
     */
    public function getNuTel2ResponsavelUon1()
    {
        return $this->nuTel2ResponsavelUon1;
    }
}