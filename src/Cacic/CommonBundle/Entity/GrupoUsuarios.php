<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrupoUsuarios
 *
 * @ORM\Table(name="grupo_usuarios")
 * @ORM\Entity
 */
class GrupoUsuarios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_grupo_usuarios", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idGrupoUsuarios;

    /**
     * @var string
     *
     * @ORM\Column(name="te_grupo_usuarios", type="string", length=20, nullable=true)
     */
    private $teGrupoUsuarios;

    /**
     * @var string
     *
     * @ORM\Column(name="te_menu_grupo", type="string", length=20, nullable=true)
     */
    private $teMenuGrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_descricao_grupo", type="text", nullable=false)
     */
    private $teDescricaoGrupo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cs_nivel_administracao", type="boolean", nullable=false)
     */
    private $csNivelAdministracao;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_grupo_usuarios", type="string", length=20, nullable=false)
     */
    private $nmGrupoUsuarios;



    /**
     * Get idGrupoUsuarios
     *
     * @return integer 
     */
    public function getIdGrupoUsuarios()
    {
        return $this->idGrupoUsuarios;
    }

    /**
     * Set teGrupoUsuarios
     *
     * @param string $teGrupoUsuarios
     * @return GrupoUsuarios
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
     * @return GrupoUsuarios
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
     * @return GrupoUsuarios
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
     * @return GrupoUsuarios
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

    /**
     * Set nmGrupoUsuarios
     *
     * @param string $nmGrupoUsuarios
     * @return GrupoUsuarios
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
}