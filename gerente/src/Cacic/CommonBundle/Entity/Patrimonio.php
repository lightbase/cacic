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
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao", type="datetime", nullable=false)
     */
    private $dtHrAlteracao;

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
     * @var integer
     *
     * @ORM\Column(name="id_unid_organizacional_nivel1", type="integer", nullable=false)
     */
    private $idUnidOrganizacionalNivel1;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_usuario", type="integer", nullable=false)
     */
    private $idUsuario;

    /**
     * @var \Computadores
     *
     * @ORM\ManyToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;

    /**
     * @var \UnidOrganizacionalNivel1a
     *
     * @ORM\ManyToOne(targetEntity="UnidOrganizacionalNivel1a")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_unid_organizacional_nivel1a", referencedColumnName="id_unid_organizacional_nivel1a")
     * })
     */
    private $idUnidOrganizacionalNivel1a;

    /**
     * @var \UnidOrganizacionalNivel2
     *
     * @ORM\ManyToOne(targetEntity="UnidOrganizacionalNivel2")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_unid_organizacional_nivel2", referencedColumnName="id_unid_organizacional_nivel2")
     * })
     */
    private $idUnidOrganizacionalNivel2;

    /**
     * @var \Patrimonio
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Patrimonio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_patrimonio", referencedColumnName="id_patrimonio")
     * })
     */
    private $idPatrimonio;



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

    /**
     * Set idUnidOrganizacionalNivel1
     *
     * @param integer $idUnidOrganizacionalNivel1
     * @return Patrimonio
     */
    public function setIdUnidOrganizacionalNivel1($idUnidOrganizacionalNivel1)
    {
        $this->idUnidOrganizacionalNivel1 = $idUnidOrganizacionalNivel1;
    
        return $this;
    }

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
     * Set idUsuario
     *
     * @param integer $idUsuario
     * @return Patrimonio
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    
        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return integer 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return Patrimonio
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computadores $idComputador = null)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computadores 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }

    /**
     * Set idUnidOrganizacionalNivel1a
     *
     * @param \Cacic\CommonBundle\Entity\UnidOrganizacionalNivel1a $idUnidOrganizacionalNivel1a
     * @return Patrimonio
     */
    public function setIdUnidOrganizacionalNivel1a(\Cacic\CommonBundle\Entity\UnidOrganizacionalNivel1a $idUnidOrganizacionalNivel1a = null)
    {
        $this->idUnidOrganizacionalNivel1a = $idUnidOrganizacionalNivel1a;
    
        return $this;
    }

    /**
     * Get idUnidOrganizacionalNivel1a
     *
     * @return \Cacic\CommonBundle\Entity\UnidOrganizacionalNivel1a 
     */
    public function getIdUnidOrganizacionalNivel1a()
    {
        return $this->idUnidOrganizacionalNivel1a;
    }

    /**
     * Set idUnidOrganizacionalNivel2
     *
     * @param \Cacic\CommonBundle\Entity\UnidOrganizacionalNivel2 $idUnidOrganizacionalNivel2
     * @return Patrimonio
     */
    public function setIdUnidOrganizacionalNivel2(\Cacic\CommonBundle\Entity\UnidOrganizacionalNivel2 $idUnidOrganizacionalNivel2 = null)
    {
        $this->idUnidOrganizacionalNivel2 = $idUnidOrganizacionalNivel2;
    
        return $this;
    }

    /**
     * Get idUnidOrganizacionalNivel2
     *
     * @return \Cacic\CommonBundle\Entity\UnidOrganizacionalNivel2 
     */
    public function getIdUnidOrganizacionalNivel2()
    {
        return $this->idUnidOrganizacionalNivel2;
    }

    /**
     * Set idPatrimonio
     *
     * @param \Cacic\CommonBundle\Entity\Patrimonio $idPatrimonio
     * @return Patrimonio
     */
    public function setIdPatrimonio(\Cacic\CommonBundle\Entity\Patrimonio $idPatrimonio)
    {
        $this->idPatrimonio = $idPatrimonio;
    
        return $this;
    }

    /**
     * Get idPatrimonio
     *
     * @return \Cacic\CommonBundle\Entity\Patrimonio 
     */
    public function getIdPatrimonio()
    {
        return $this->idPatrimonio;
    }
}