<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoftwareRelatorio
 */
class SoftwareRelatorio
{
    /**
     * @var integer
     */
    private $idRelatorio;

    /**
     * @var string
     */
    private $nomeRelatorio;

    /**
     * @var string
     */
    private $nivelAcesso;

    /**
     * @var boolean
     */
    private $habilitaNotificacao;

    /**
     * @var \Cacic\CommonBundle\Entity\Usuario
     */
    private $idUsuario;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $softwares;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $aquisicoes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->softwares = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aquisicoes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idRelatorio
     *
     * @return integer 
     */
    public function getIdRelatorio()
    {
        return $this->idRelatorio;
    }

    /**
     * Set nomeRelatorio
     *
     * @param string $nomeRelatorio
     * @return SoftwareRelatorio
     */
    public function setNomeRelatorio($nomeRelatorio)
    {
        $this->nomeRelatorio = $nomeRelatorio;

        return $this;
    }

    /**
     * Get nomeRelatorio
     *
     * @return string 
     */
    public function getNomeRelatorio()
    {
        return $this->nomeRelatorio;
    }

    /**
     * Set nivelAcesso
     *
     * @param string $nivelAcesso
     * @return SoftwareRelatorio
     */
    public function setNivelAcesso($nivelAcesso)
    {
        $this->nivelAcesso = $nivelAcesso;

        return $this;
    }

    /**
     * Get nivelAcesso
     *
     * @return string 
     */
    public function getNivelAcesso()
    {
        return $this->nivelAcesso;
    }

    /**
     * Set habilitaNotificacao
     *
     * @param boolean $habilitaNotificacao
     * @return SoftwareRelatorio
     */
    public function setHabilitaNotificacao($habilitaNotificacao)
    {
        $this->habilitaNotificacao = $habilitaNotificacao;

        return $this;
    }

    /**
     * Get habilitaNotificacao
     *
     * @return boolean 
     */
    public function getHabilitaNotificacao()
    {
        return $this->habilitaNotificacao;
    }

    /**
     * Set idUsuario
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $idUsuario
     * @return SoftwareRelatorio
     */
    public function setIdUsuario(\Cacic\CommonBundle\Entity\Usuario $idUsuario)
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
     * Add softwares
     *
     * @param \Cacic\CommonBundle\Entity\Software $softwares
     * @return SoftwareRelatorio
     */
    public function addSoftware(\Cacic\CommonBundle\Entity\Software $softwares)
    {
        $this->softwares[] = $softwares;

        return $this;
    }

    /**
     * Remove softwares
     *
     * @param \Cacic\CommonBundle\Entity\Software $softwares
     */
    public function removeSoftware(\Cacic\CommonBundle\Entity\Software $softwares)
    {
        $this->softwares->removeElement($softwares);
    }

    /**
     * Get softwares
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSoftwares()
    {
        return $this->softwares;
    }

    /**
     * Add aquisicoes
     *
     * @param \Cacic\CommonBundle\Entity\AquisicaoItem $aquisicoes
     * @return SoftwareRelatorio
     */
    public function addAquisico(\Cacic\CommonBundle\Entity\AquisicaoItem $aquisicoes)
    {
        $this->aquisicoes[] = $aquisicoes;

        return $this;
    }

    /**
     * Remove aquisicoes
     *
     * @param \Cacic\CommonBundle\Entity\AquisicaoItem $aquisicoes
     */
    public function removeAquisico(\Cacic\CommonBundle\Entity\AquisicaoItem $aquisicoes)
    {
        $this->aquisicoes->removeElement($aquisicoes);
    }

    /**
     * Get aquisicoes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAquisicoes()
    {
        return $this->aquisicoes;
    }
    /**
     * @var string
     */
    private $tipo;


    /**
     * Set tipo
     *
     * @param string $tipo
     * @return SoftwareRelatorio
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    /**
     * @var boolean
     */
    private $ativo;


    /**
     * Set ativo
     *
     * @param boolean $ativo
     * @return SoftwareRelatorio
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return boolean 
     */
    public function getAtivo()
    {
        return $this->ativo;
    }
}
