<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoUsuario
 */
class GrupoUsuario
{
    /**
     * @var integer
     */
    private $idGrupoUsuario;

    /**
     * @var string
     */
    private $nmGrupoUsuarios;

    /**
     * @var string
     */
    private $teGrupoUsuarios;

    /**
     * @var string
     */
    private $teMenuGrupo;

    /**
     * @var string
     */
    private $teDescricaoGrupo;

    /**
     * @var boolean
     */
    private $csNivelAdministracao;


    /**
     * Get idGrupoUsuario
     *
     * @return integer 
     */
    public function getIdGrupoUsuario()
    {
        return $this->idGrupoUsuario;
    }

    /**
     * Set nmGrupoUsuarios
     *
     * @param string $nmGrupoUsuarios
     * @return GrupoUsuario
     */
    public function setNmGrupoUsuarios($nmGrupoUsuarios)
    {
        $this->nmGrupoUsuarios = $nmGrupoUsuarios;
    
        return $this;
    }

    /**
     * Get nmGrupoUsuarios
     *
     * @return string 
     */
    public function getNmGrupoUsuarios()
    {
        return $this->nmGrupoUsuarios;
    }

    /**
     * Set teGrupoUsuarios
     *
     * @param string $teGrupoUsuarios
     * @return GrupoUsuario
     */
    public function setTeGrupoUsuarios($teGrupoUsuarios)
    {
        $this->teGrupoUsuarios = $teGrupoUsuarios;
    
        return $this;
    }

    /**
     * Get teGrupoUsuarios
     *
     * @return string 
     */
    public function getTeGrupoUsuarios()
    {
        return $this->teGrupoUsuarios;
    }

    /**
     * Set teMenuGrupo
     *
     * @param string $teMenuGrupo
     * @return GrupoUsuario
     */
    public function setTeMenuGrupo($teMenuGrupo)
    {
        $this->teMenuGrupo = $teMenuGrupo;
    
        return $this;
    }

    /**
     * Get teMenuGrupo
     *
     * @return string 
     */
    public function getTeMenuGrupo()
    {
        return $this->teMenuGrupo;
    }

    /**
     * Set teDescricaoGrupo
     *
     * @param string $teDescricaoGrupo
     * @return GrupoUsuario
     */
    public function setTeDescricaoGrupo($teDescricaoGrupo)
    {
        $this->teDescricaoGrupo = $teDescricaoGrupo;
    
        return $this;
    }

    /**
     * Get teDescricaoGrupo
     *
     * @return string 
     */
    public function getTeDescricaoGrupo()
    {
        return $this->teDescricaoGrupo;
    }

    /**
     * Set csNivelAdministracao
     *
     * @param boolean $csNivelAdministracao
     * @return GrupoUsuario
     */
    public function setCsNivelAdministracao($csNivelAdministracao)
    {
        $this->csNivelAdministracao = $csNivelAdministracao;
    
        return $this;
    }

    /**
     * Get csNivelAdministracao
     *
     * @return boolean 
     */
    public function getCsNivelAdministracao()
    {
        return $this->csNivelAdministracao;
    }
}