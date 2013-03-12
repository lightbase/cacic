<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Usuario
 */
class Usuario implements UserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $idUsuario;

    /**
     * @var string
     */
    private $idUsuarioLdap;

    /**
     * @var string
     */
    private $nmUsuarioAcesso;

    /**
     * @var string
     */
    private $nmUsuarioCompleto;

    /**
     * @var string
     */
    private $nmUsuarioCompletoLdap;

    /**
     * @var string
     */
    private $teSenha;

    /**
     * @var \DateTime
     */
    private $dtLogIn;

    /**
     * @var string
     */
    private $teEmailsContato;

    /**
     * @var string
     */
    private $teTelefonesContato;

    /**
     * @var string
     */
    private $teLocaisSecundarios;

    /**
     * @var \Cacic\CommonBundle\Entity\Local
     */
    private $idLocal;

    /**
     * @var \Cacic\CommonBundle\Entity\ServidorAutenticacao
     */
    private $idServidorAutenticacao;

    /**
     * @var \Cacic\CommonBundle\Entity\GrupoUsuario
     */
    private $idGrupoUsuario;


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
     * Set idUsuarioLdap
     *
     * @param string $idUsuarioLdap
     * @return Usuario
     */
    public function setIdUsuarioLdap($idUsuarioLdap)
    {
        $this->idUsuarioLdap = $idUsuarioLdap;
    
        return $this;
    }

    /**
     * Get idUsuarioLdap
     *
     * @return string 
     */
    public function getIdUsuarioLdap()
    {
        return $this->idUsuarioLdap;
    }

    /**
     * Set nmUsuarioAcesso
     *
     * @param string $nmUsuarioAcesso
     * @return Usuario
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
     * @return Usuario
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
     * Set nmUsuarioCompletoLdap
     *
     * @param string $nmUsuarioCompletoLdap
     * @return Usuario
     */
    public function setNmUsuarioCompletoLdap($nmUsuarioCompletoLdap)
    {
        $this->nmUsuarioCompletoLdap = $nmUsuarioCompletoLdap;
    
        return $this;
    }

    /**
     * Get nmUsuarioCompletoLdap
     *
     * @return string 
     */
    public function getNmUsuarioCompletoLdap()
    {
        return $this->nmUsuarioCompletoLdap;
    }

    /**
     * Set teSenha
     *
     * @param string $teSenha
     * @return Usuario
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
     * @return Usuario
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
     * Set teEmailsContato
     *
     * @param string $teEmailsContato
     * @return Usuario
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
     * @return Usuario
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
     * @return Usuario
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

    /**
     * Set idLocal
     *
     * @param \Cacic\CommonBundle\Entity\Local $idLocal
     * @return Usuario
     */
    public function setIdLocal(\Cacic\CommonBundle\Entity\Local $idLocal = null)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return \Cacic\CommonBundle\Entity\Local 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set idServidorAutenticacao
     *
     * @param \Cacic\CommonBundle\Entity\ServidorAutenticacao $idServidorAutenticacao
     * @return Usuario
     */
    public function setIdServidorAutenticacao(\Cacic\CommonBundle\Entity\ServidorAutenticacao $idServidorAutenticacao = null)
    {
        $this->idServidorAutenticacao = $idServidorAutenticacao;
    
        return $this;
    }

    /**
     * Get idServidorAutenticacao
     *
     * @return \Cacic\CommonBundle\Entity\ServidorAutenticacao 
     */
    public function getIdServidorAutenticacao()
    {
        return $this->idServidorAutenticacao;
    }

    /**
     * Set idGrupoUsuario
     *
     * @param \Cacic\CommonBundle\Entity\GrupoUsuario $idGrupoUsuario
     * @return Usuario
     */
    public function setIdGrupoUsuario(\Cacic\CommonBundle\Entity\GrupoUsuario $idGrupoUsuario = null)
    {
        $this->idGrupoUsuario = $idGrupoUsuario;
    
        return $this;
    }

    /**
     * Get idGrupoUsuario
     *
     * @return \Cacic\CommonBundle\Entity\GrupoUsuario 
     */
    public function getIdGrupoUsuario()
    {
        return $this->idGrupoUsuario;
    }
    
	/**
     * 
     * @see UserInterface::getUsername()
     */
    public function getUsername()
    {
    	return $this->nmUsuarioAcesso;
    }
    
    /**
     * @see UserInterface::getPassword()
     */
    public function getPassword()
    {
    	return $this->teSenha;
    }
    
    /**
     * 
     * @see UserInterface::getSalt()
     */
    public function getSalt()
    {
    	return null;
    }
    
    /**
     * 
     * @see UserInterface::getRoles()
     */
    public function getRoles()
    {
    	return array( 'ROLE_ADMIN' );
    }
    
    /**
     * 
     * @see UserInterface::eraseCredentials()
     */
    public function eraseCredentials(){}
    
    /**
     * 
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
    	return serialize( array( $this->idUsuario ) );
    }
    
    /**
     * 
     * @see \Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize( $serialized )
    {
    	list ( $this->idUsuario ) = unserialize( $serialized );
    }
}