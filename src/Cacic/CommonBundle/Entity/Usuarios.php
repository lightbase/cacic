<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios")
 * @ORM\Entity
 */
class Usuarios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_usuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUsuario;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     */
    private $idLocal;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_servidor_autenticacao", type="integer", nullable=true)
     */
    private $idServidorAutenticacao;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_usuario_acesso", type="string", length=20, nullable=false)
     */
    private $nmUsuarioAcesso;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_usuario_completo", type="string", length=60, nullable=false)
     */
    private $nmUsuarioCompleto;

    /**
     * @var string
     *
     * @ORM\Column(name="te_senha", type="string", length=60, nullable=false)
     */
    private $teSenha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_log_in", type="datetime", nullable=false)
     */
    private $dtLogIn;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_grupo_usuarios", type="integer", nullable=false)
     */
    private $idGrupoUsuarios;

    /**
     * @var string
     *
     * @ORM\Column(name="te_emails_contato", type="string", length=100, nullable=true)
     */
    private $teEmailsContato;

    /**
     * @var string
     *
     * @ORM\Column(name="te_telefones_contato", type="string", length=100, nullable=true)
     */
    private $teTelefonesContato;

    /**
     * @var string
     *
     * @ORM\Column(name="te_locais_secundarios", type="text", nullable=true)
     */
    private $teLocaisSecundarios;



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
     * Set idLocal
     *
     * @param integer $idLocal
     * @return Usuarios
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
     * Set idServidorAutenticacao
     *
     * @param integer $idServidorAutenticacao
     * @return Usuarios
     */
    public function setIdServidorAutenticacao($idServidorAutenticacao)
    {
        $this->idServidorAutenticacao = $idServidorAutenticacao;
    
        return $this;
    }

    /**
     * Get idServidorAutenticacao
     *
     * @return integer 
     */
    public function getIdServidorAutenticacao()
    {
        return $this->idServidorAutenticacao;
    }

    /**
     * Set nmUsuarioAcesso
     *
     * @param string $nmUsuarioAcesso
     * @return Usuarios
     */
    public function setNmUsuarioAcesso($nmUsuarioAcesso)
    {
        $this->nmUsuarioAcesso = $nmUsuarioAcesso;
    
        return $this;
    }

    /**
     * Get nmUsuarioAcesso
     *
     * @return string 
     */
    public function getNmUsuarioAcesso()
    {
        return $this->nmUsuarioAcesso;
    }

    /**
     * Set nmUsuarioCompleto
     *
     * @param string $nmUsuarioCompleto
     * @return Usuarios
     */
    public function setNmUsuarioCompleto($nmUsuarioCompleto)
    {
        $this->nmUsuarioCompleto = $nmUsuarioCompleto;
    
        return $this;
    }

    /**
     * Get nmUsuarioCompleto
     *
     * @return string 
     */
    public function getNmUsuarioCompleto()
    {
        return $this->nmUsuarioCompleto;
    }

    /**
     * Set teSenha
     *
     * @param string $teSenha
     * @return Usuarios
     */
    public function setTeSenha($teSenha)
    {
        $this->teSenha = $teSenha;
    
        return $this;
    }

    /**
     * Get teSenha
     *
     * @return string 
     */
    public function getTeSenha()
    {
        return $this->teSenha;
    }

    /**
     * Set dtLogIn
     *
     * @param \DateTime $dtLogIn
     * @return Usuarios
     */
    public function setDtLogIn($dtLogIn)
    {
        $this->dtLogIn = $dtLogIn;
    
        return $this;
    }

    /**
     * Get dtLogIn
     *
     * @return \DateTime 
     */
    public function getDtLogIn()
    {
        return $this->dtLogIn;
    }

    /**
     * Set idGrupoUsuarios
     *
     * @param integer $idGrupoUsuarios
     * @return Usuarios
     */
    public function setIdGrupoUsuarios($idGrupoUsuarios)
    {
        $this->idGrupoUsuarios = $idGrupoUsuarios;
    
        return $this;
    }

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
     * Set teEmailsContato
     *
     * @param string $teEmailsContato
     * @return Usuarios
     */
    public function setTeEmailsContato($teEmailsContato)
    {
        $this->teEmailsContato = $teEmailsContato;
    
        return $this;
    }

    /**
     * Get teEmailsContato
     *
     * @return string 
     */
    public function getTeEmailsContato()
    {
        return $this->teEmailsContato;
    }

    /**
     * Set teTelefonesContato
     *
     * @param string $teTelefonesContato
     * @return Usuarios
     */
    public function setTeTelefonesContato($teTelefonesContato)
    {
        $this->teTelefonesContato = $teTelefonesContato;
    
        return $this;
    }

    /**
     * Get teTelefonesContato
     *
     * @return string 
     */
    public function getTeTelefonesContato()
    {
        return $this->teTelefonesContato;
    }

    /**
     * Set teLocaisSecundarios
     *
     * @param string $teLocaisSecundarios
     * @return Usuarios
     */
    public function setTeLocaisSecundarios($teLocaisSecundarios)
    {
        $this->teLocaisSecundarios = $teLocaisSecundarios;
    
        return $this;
    }

    /**
     * Get teLocaisSecundarios
     *
     * @return string 
     */
    public function getTeLocaisSecundarios()
    {
        return $this->teLocaisSecundarios;
    }
}