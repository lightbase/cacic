<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Usuarios
 *
 * @ORM\Table(name="usuarios")
 * @ORM\Entity(repositoryClass="Cacic\CommonBundle\Entity\UsuariosRepository")
 * @UniqueEntity("nmUsuarioAcesso")
 */
class Usuarios implements UserInterface, \Serializable
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
     * @ORM\Column(name="id_servidor_autenticacao", type="integer", nullable=true)
     */
    private $idServidorAutenticacao;

    /**
     * @var string
     *
     * @ORM\Column(name="id_usuario_ldap", type="string", length=100, nullable=true)
     */
    private $idUsuarioLdap;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_usuario_acesso", type="string", length=20, nullable=false, unique=true)
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
     * @ORM\Column(name="nm_usuario_completo_ldap", type="string", length=100, nullable=true)
     */
    private $nmUsuarioCompletoLdap;

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
     * @var integer
     * 
     * @ORM\Column(name="id_grupo_usuarios", type="integer", nullable=false)
     */
    private $idGrupoUsuarios;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     */
    private $idLocal;
    
    /**
     * @var \Locais
     * 
     * @ORM\ManyToOne(targetEntity="Locais", inversedBy="usuariosPrimarios")
     * @ORM\JoinColumn(name="id_local", referencedColumnName="id_local")
     */
    private $localPrimario;
    
    /**
     * @var \GrupoUsuarios
     * 
     * @ORM\ManyToOne(targetEntity="GrupoUsuarios", inversedBy="usuarios")
     * @ORM\JoinColumn(name="id_grupo_usuarios", referencedColumnName="id_grupo_usuarios")
     */
    private $grupo;
    
    /**
     * @var \ServidoresAutenticacao
     * 
     * @ORM\ManyToOne(targetEntity="ServidoresAutenticacao", inversedBy="usuarios")
     * @ORM\JoinColumn(name="id_servidor_autenticacao", referencedColumnName="id_servidor_autenticacao")
     */
    private $servidorAutenticacao;

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
     * Set idUsuarioLdap
     *
     * @param string $idUsuarioLdap
     * @return Usuarios
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
     * Set nmUsuarioCompletoLdap
     *
     * @param string $nmUsuarioCompletoLdap
     * @return Usuarios
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

    /**
     * Set idGrupoUsuarios
     *
     * @param $idGrupoUsuarios
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
    

    /**
     * Set localPrimario
     *
     * @param \Cacic\CommonBundle\Entity\Locais $localPrimario
     * @return Usuarios
     */
    public function setLocalPrimario(\Cacic\CommonBundle\Entity\Locais $localPrimario = null)
    {
        $this->localPrimario = $localPrimario;
    
        return $this;
    }

    /**
     * Get localPrimario
     *
     * @return \Cacic\CommonBundle\Entity\Locais 
     */
    public function getLocalPrimario()
    {
        return $this->localPrimario;
    }

    /**
     * Set grupo
     *
     * @param \Cacic\CommonBundle\Entity\GrupoUsuarios $grupo
     * @return Usuarios
     */
    public function setGrupo(\Cacic\CommonBundle\Entity\GrupoUsuarios $grupo = null)
    {
        $this->grupo = $grupo;
    
        return $this;
    }

    /**
     * Get grupo
     *
     * @return \Cacic\CommonBundle\Entity\GrupoUsuarios 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }
    
    /**
     * Set servidorAutenticacao
     * 
     * @param \Cacic\CommonBundle\Entity\ServidoresAutenticacao $servidorAutenticacao
     * @return Usuarios
     */
    public function setServidorAutenticacao( \Cacic\CommonBundle\Entity\ServidoresAutenticacao $servidorAutenticacao = null )
    {
    	$this->servidorAutenticacao = $servidorAutenticacao;
    	
    	return $this;
    }
    
    /**
     * 
     * Get servidorAutenticacao
     * @return \Cacic\CommonBundle\Entity\ServidoresAutenticacao
     */
    public function getServidorAutenticacao()
    {
    	return $this->servidorAutenticacao;
    }
    
    /**
     * 
     * Configura uma senha aleatória para o Usuário
     * @param string $stAlgorithm algoritmo a ser aplicado a senha gerada
     * @param integer $intLength tamanho da senha a ser gerada
     * @param string $strFormat formato da senha
     * @return string
     */
    public function gerarSenhaAleatoria( $strAlgorithm, $intLength, $strFormat = NULL )
    {
		$strChars = "abcdefghijklmnopqrstuvxwyz0123456789";
		$strLength = strlen( $strChars ) - 1;
		$strHash = "";
		$intCount = 0;
		while( strlen( $strHash ) < $intLength ) 
		{
			if( ! is_null( $strFormat ) )
			{
				if( $strFormat[ $intCount ] == "s" )
				{
					$strHash .= $strChars[ mt_rand( 0, $strLength - 10 ) ];
				}
				elseif( $strFormat[ $intCount ] == "i" )
				{
					$strHash .= $strChars[ mt_rand( 26, $strLength ) ];
				}
				$intCount++;
			}
			else
			{
           		$strHash .= $strChars[ mt_rand( 0, $strLength ) ];
			}
        }
        $this->setTeSenha( hash( $strAlgorithm, $strHash ) ); // Configura a senha deste Usuário já aplicando o MD5
        return( $strHash ); // Retorna a senha gerada aleatoriamente
    }
    
    /**
     * 
     * Configura uma senha padrão para o Usuário
     * @param $strAlgorithm algoritmo a ser aplicado a senha gerada
     * @return string
     */
    public function gerarSenhaPadrao( $strAlgorithm )
    {
    	$strSenhaPadrao = '12345678';
    	$this->setTeSenha( hash( $strAlgorithm, $strSenhaPadrao ) ); // Configura a senha deste Usuário já aplicando o MD5
    	return $strSenhaPadrao; // Retorna a senha padrão
    }
}