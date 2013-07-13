<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acao
 */
class Acao
{
    /**
     * @var string
     */
    private $idAcao;

    /**
     * @var string
     */
    private $teDescricaoBreve;

    /**
     * @var string
     */
    private $teDescricao;

    /**
     * @var string
     */
    private $teNomeCurtoModulo;

    /**
     * @var \DateTime
     */
    private $dtHrAlteracao;

    /**
     * @var string
     */
    private $csOpcional;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $redes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->redes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get idAcao
     *
     * @return string 
     */
    public function getIdAcao()
    {
        return $this->idAcao;
    }

    /**
     * Set teDescricaoBreve
     *
     * @param string $teDescricaoBreve
     * @return Acao
     */
    public function setTeDescricaoBreve($teDescricaoBreve)
    {
        $this->teDescricaoBreve = $teDescricaoBreve;
    
        return $this;
    }

    /**
     * Get teDescricaoBreve
     *
     * @return string 
     */
    public function getTeDescricaoBreve()
    {
        return $this->teDescricaoBreve;
    }

    /**
     * Set teDescricao
     *
     * @param string $teDescricao
     * @return Acao
     */
    public function setTeDescricao($teDescricao)
    {
        $this->teDescricao = $teDescricao;
    
        return $this;
    }

    /**
     * Get teDescricao
     *
     * @return string 
     */
    public function getTeDescricao()
    {
        return $this->teDescricao;
    }

    /**
     * Set teNomeCurtoModulo
     *
     * @param string $teNomeCurtoModulo
     * @return Acao
     */
    public function setTeNomeCurtoModulo($teNomeCurtoModulo)
    {
        $this->teNomeCurtoModulo = $teNomeCurtoModulo;
    
        return $this;
    }

    /**
     * Get teNomeCurtoModulo
     *
     * @return string 
     */
    public function getTeNomeCurtoModulo()
    {
        return $this->teNomeCurtoModulo;
    }

    /**
     * Set dtHrAlteracao
     *
     * @param \DateTime $dtHrAlteracao
     * @return Acao
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
     * Set csOpcional
     *
     * @param string $csOpcional
     * @return Acao
     */
    public function setCsOpcional($csOpcional)
    {
        $this->csOpcional = $csOpcional;
    
        return $this;
    }

    /**
     * Get csOpcional
     *
     * @return string 
     */
    public function getCsOpcional()
    {
        return $this->csOpcional;
    }

    /**
     * Add redes
     *
     * @param \Cacic\CommonBundle\Entity\AcaoRede $redes
     * @return Acao
     */
    public function addRede(\Cacic\CommonBundle\Entity\AcaoRede $redes)
    {
        $this->redes[] = $redes;
    
        return $this;
    }

    /**
     * Remove redes
     *
     * @param \Cacic\CommonBundle\Entity\AcaoRede $redes
     */
    public function removeRede(\Cacic\CommonBundle\Entity\AcaoRede $redes)
    {
        $this->redes->removeElement($redes);
    }

    /**
     * Get redes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRedes()
    {
        return $this->redes;
    }

    /**
     * Set idAcao
     *
     * @param string $idAcao
     * @return Acao
     */
    public function setIdAcao($idAcao)
    {
        $this->idAcao = $idAcao;
    
        return $this;
    }
}