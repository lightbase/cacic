<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Patrimonio
 *
 * @ORM\Table(name="patrimonio")
 * @ORM\Entity
 */
class Patrimonio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_patrimonio", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPatrimonio;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_unid_organizacional_nivel1a", type="integer", nullable=false)
     */
    private $idUnidOrganizacionalNivel1a;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     */
    private $idSo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao", type="datetime", nullable=false)
     */
    private $dtHrAlteracao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     */
    private $teNodeAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_unid_organizacional_nivel2", type="integer", nullable=true)
     */
    private $idUnidOrganizacionalNivel2;

    /**
     * @var string
     *
     * @ORM\Column(name="te_localizacao_complementar", type="string", length=100, nullable=true)
     */
    private $teLocalizacaoComplementar;

    /**
     * @var string
     *
     * @ORM\Column(name="te_info_patrimonio1", type="string", length=20, nullable=true)
     */
    private $teInfoPatrimonio1;

    /**
     * @var string
     *
     * @ORM\Column(name="te_info_patrimonio2", type="string", length=20, nullable=true)
     */
    private $teInfoPatrimonio2;

    /**
     * @var string
     *
     * @ORM\Column(name="te_info_patrimonio3", type="string", length=20, nullable=true)
     */
    private $teInfoPatrimonio3;

    /**
     * @var string
     *
     * @ORM\Column(name="te_info_patrimonio4", type="string", length=20, nullable=true)
     */
    private $teInfoPatrimonio4;

    /**
     * @var string
     *
     * @ORM\Column(name="te_info_patrimonio5", type="string", length=20, nullable=true)
     */
    private $teInfoPatrimonio5;

    /**
     * @var string
     *
     * @ORM\Column(name="te_info_patrimonio6", type="string", length=20, nullable=true)
     */
    private $teInfoPatrimonio6;



    /**
     * Get idPatrimonio
     *
     * @return integer 
     */
    public function getIdPatrimonio()
    {
        return $this->idPatrimonio;
    }

    /**
     * Set idUnidOrganizacionalNivel1a
     *
     * @param integer $idUnidOrganizacionalNivel1a
     * @return Patrimonio
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
     * Set idSo
     *
     * @param integer $idSo
     * @return Patrimonio
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
     * Set dtHrAlteracao
     *
     * @param \DateTime $dtHrAlteracao
     * @return Patrimonio
     */
    public function setDtHrAlteracao($dtHrAlteracao)
    {
        $this->dtHrAlteracao = $dtHrAlteracao;
    
        return $this;
    }

    /**
     * Get dtHrAlteracao
     *
     * @return \DateTime 
     */
    public function getDtHrAlteracao()
    {
        return $this->dtHrAlteracao;
    }

    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return Patrimonio
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
     * Set idUnidOrganizacionalNivel2
     *
     * @param integer $idUnidOrganizacionalNivel2
     * @return Patrimonio
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
     * Set teLocalizacaoComplementar
     *
     * @param string $teLocalizacaoComplementar
     * @return Patrimonio
     */
    public function setTeLocalizacaoComplementar($teLocalizacaoComplementar)
    {
        $this->teLocalizacaoComplementar = $teLocalizacaoComplementar;
    
        return $this;
    }

    /**
     * Get teLocalizacaoComplementar
     *
     * @return string 
     */
    public function getTeLocalizacaoComplementar()
    {
        return $this->teLocalizacaoComplementar;
    }

    /**
     * Set teInfoPatrimonio1
     *
     * @param string $teInfoPatrimonio1
     * @return Patrimonio
     */
    public function setTeInfoPatrimonio1($teInfoPatrimonio1)
    {
        $this->teInfoPatrimonio1 = $teInfoPatrimonio1;
    
        return $this;
    }

    /**
     * Get teInfoPatrimonio1
     *
     * @return string 
     */
    public function getTeInfoPatrimonio1()
    {
        return $this->teInfoPatrimonio1;
    }

    /**
     * Set teInfoPatrimonio2
     *
     * @param string $teInfoPatrimonio2
     * @return Patrimonio
     */
    public function setTeInfoPatrimonio2($teInfoPatrimonio2)
    {
        $this->teInfoPatrimonio2 = $teInfoPatrimonio2;
    
        return $this;
    }

    /**
     * Get teInfoPatrimonio2
     *
     * @return string 
     */
    public function getTeInfoPatrimonio2()
    {
        return $this->teInfoPatrimonio2;
    }

    /**
     * Set teInfoPatrimonio3
     *
     * @param string $teInfoPatrimonio3
     * @return Patrimonio
     */
    public function setTeInfoPatrimonio3($teInfoPatrimonio3)
    {
        $this->teInfoPatrimonio3 = $teInfoPatrimonio3;
    
        return $this;
    }

    /**
     * Get teInfoPatrimonio3
     *
     * @return string 
     */
    public function getTeInfoPatrimonio3()
    {
        return $this->teInfoPatrimonio3;
    }

    /**
     * Set teInfoPatrimonio4
     *
     * @param string $teInfoPatrimonio4
     * @return Patrimonio
     */
    public function setTeInfoPatrimonio4($teInfoPatrimonio4)
    {
        $this->teInfoPatrimonio4 = $teInfoPatrimonio4;
    
        return $this;
    }

    /**
     * Get teInfoPatrimonio4
     *
     * @return string 
     */
    public function getTeInfoPatrimonio4()
    {
        return $this->teInfoPatrimonio4;
    }

    /**
     * Set teInfoPatrimonio5
     *
     * @param string $teInfoPatrimonio5
     * @return Patrimonio
     */
    public function setTeInfoPatrimonio5($teInfoPatrimonio5)
    {
        $this->teInfoPatrimonio5 = $teInfoPatrimonio5;
    
        return $this;
    }

    /**
     * Get teInfoPatrimonio5
     *
     * @return string 
     */
    public function getTeInfoPatrimonio5()
    {
        return $this->teInfoPatrimonio5;
    }

    /**
     * Set teInfoPatrimonio6
     *
     * @param string $teInfoPatrimonio6
     * @return Patrimonio
     */
    public function setTeInfoPatrimonio6($teInfoPatrimonio6)
    {
        $this->teInfoPatrimonio6 = $teInfoPatrimonio6;
    
        return $this;
    }

    /**
     * Get teInfoPatrimonio6
     *
     * @return string 
     */
    public function getTeInfoPatrimonio6()
    {
        return $this->teInfoPatrimonio6;
    }
}