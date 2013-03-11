<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Patrimonio
 */
class Patrimonio
{
    /**
     * @var integer
     */
    private $idPatrimonio;

    /**
     * @var \DateTime
     */
    private $dtHrAlteracao;

    /**
     * @var string
     */
    private $teLocalizacaoComplementar;

    /**
     * @var string
     */
    private $teInfoPatrimonio1;

    /**
     * @var string
     */
    private $teInfoPatrimonio2;

    /**
     * @var string
     */
    private $teInfoPatrimonio3;

    /**
     * @var string
     */
    private $teInfoPatrimonio4;

    /**
     * @var string
     */
    private $teInfoPatrimonio5;

    /**
     * @var string
     */
    private $teInfoPatrimonio6;

    /**
     * @var integer
     */
    private $idUnidOrganizacionalNivel1;

    /**
     * @var \Cacic\CommonBundle\Entity\Usuario
     */
    private $idUsuario;

    /**
     * @var \Cacic\CommonBundle\Entity\UnidOrganizacionalNivel1a
     */
    private $idUnidOrganizacionalNivel1a;

    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $idComputador;

    /**
     * @var \Cacic\CommonBundle\Entity\UnidOrganizacionalNivel2
     */
    private $idUnidOrganizacionalNivel2;


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
     * @param \Cacic\CommonBundle\Entity\Usuario $idUsuario
     * @return Patrimonio
     */
    public function setIdUsuario(\Cacic\CommonBundle\Entity\Usuario $idUsuario = null)
    {
        $this->idUsuario = $idUsuario;
    
        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \Cacic\CommonBundle\Entity\Usuario 
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
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
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $idComputador
     * @return Patrimonio
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computador $idComputador = null)
    {
        $this->idComputador = $idComputador;
    
        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computador 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
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
}