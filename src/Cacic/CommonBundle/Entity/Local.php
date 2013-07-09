<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Local
 */
class Local
{
    /**
     * @var integer
     */
    private $idLocal;

    /**
     * @var string
     */
    private $nmLocal;

    /**
     * @var string
     */
    private $sgLocal;

    /**
     * @var string
     */
    private $teObservacao;

    /**
     * @var string
     */
    private $teDebugging;

    /**
     * @var string
     */
    private $dtDebug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $usuarios;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $redes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $configuracoes;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $uorgs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $usuariosSecundarios;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->redes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->configuracoes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uorgs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuariosSecundarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nmLocal
     *
     * @param string $nmLocal
     * @return Local
     */
    public function setNmLocal($nmLocal)
    {
        $this->nmLocal = $nmLocal;
    
        return $this;
    }

    /**
     * Get nmLocal
     *
     * @return string 
     */
    public function getNmLocal()
    {
        return $this->nmLocal;
    }

    /**
     * Set sgLocal
     *
     * @param string $sgLocal
     * @return Local
     */
    public function setSgLocal($sgLocal)
    {
        $this->sgLocal = $sgLocal;
    
        return $this;
    }

    /**
     * Get sgLocal
     *
     * @return string 
     */
    public function getSgLocal()
    {
        return $this->sgLocal;
    }

    /**
     * Set teObservacao
     *
     * @param string $teObservacao
     * @return Local
     */
    public function setTeObservacao($teObservacao)
    {
        $this->teObservacao = $teObservacao;
    
        return $this;
    }

    /**
     * Get teObservacao
     *
     * @return string 
     */
    public function getTeObservacao()
    {
        return $this->teObservacao;
    }

    /**
     * Set teDebugging
     *
     * @param string $teDebugging
     * @return Local
     */
    public function setTeDebugging($teDebugging)
    {
        $this->teDebugging = $teDebugging;
    
        return $this;
    }

    /**
     * Get teDebugging
     *
     * @return string 
     */
    public function getTeDebugging()
    {
        return $this->teDebugging;
    }

    /**
     * Set dtDebug
     *
     * @param string $dtDebug
     * @return Local
     */
    public function setDtDebug($dtDebug)
    {
        $this->dtDebug = $dtDebug;
    
        return $this;
    }

    /**
     * Get dtDebug
     *
     * @return string 
     */
    public function getDtDebug()
    {
        return $this->dtDebug;
    }

    /**
     * Add usuarios
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $usuarios
     * @return Local
     */
    public function addUsuario(\Cacic\CommonBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;
    
        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $usuarios
     */
    public function removeUsuario(\Cacic\CommonBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Add redes
     *
     * @param \Cacic\CommonBundle\Entity\Rede $redes
     * @return Local
     */
    public function addRede(\Cacic\CommonBundle\Entity\Rede $redes)
    {
        $this->redes[] = $redes;
    
        return $this;
    }

    /**
     * Remove redes
     *
     * @param \Cacic\CommonBundle\Entity\Rede $redes
     */
    public function removeRede(\Cacic\CommonBundle\Entity\Rede $redes)
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
     * Add configuracoes
     *
     * @param \Cacic\CommonBundle\Entity\ConfiguracaoLocal $configuracoes
     * @return Local
     */
    public function addConfiguracoe(\Cacic\CommonBundle\Entity\ConfiguracaoLocal $configuracoes)
    {
        $this->configuracoes[] = $configuracoes;
    
        return $this;
    }

    /**
     * Remove configuracoes
     *
     * @param \Cacic\CommonBundle\Entity\ConfiguracaoLocal $configuracoes
     */
    public function removeConfiguracoe(\Cacic\CommonBundle\Entity\ConfiguracaoLocal $configuracoes)
    {
        $this->configuracoes->removeElement($configuracoes);
    }

    /**
     * Get configuracoes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConfiguracoes()
    {
        return $this->configuracoes;
    }

    /**
     * Add uorgs
     *
     * @param \Cacic\CommonBundle\Entity\Uorg $uorgs
     * @return Local
     */
    public function addUorg(\Cacic\CommonBundle\Entity\Uorg $uorgs)
    {
        $this->uorgs[] = $uorgs;
    
        return $this;
    }

    /**
     * Remove uorgs
     *
     * @param \Cacic\CommonBundle\Entity\Uorg $uorgs
     */
    public function removeUorg(\Cacic\CommonBundle\Entity\Uorg $uorgs)
    {
        $this->uorgs->removeElement($uorgs);
    }

    /**
     * Get uorgs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUorgs()
    {
        return $this->uorgs;
    }
    
    /**
     * Set idLocal
     * 
     * @param int $idLocal
     * @return Local
     */
    public function setIdLocal( $idLocal )
    {
    	$this->idLocal = $idLocal;
    	
    	return $this;
    }
    
	/**
     * 
     * [LifeCycle: POST INSERT] Cria as configurações do LOCAL à partir da configuração PADRÃO
     * @ORM\PostPersist
     */
    public function configurarFromConfigPadrao()
    {
    	
    }

    /**
     * Add usuariosSecundarios
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $usuariosSecundarios
     * @return Local
     */
    public function addUsuariosSecundario(\Cacic\CommonBundle\Entity\Usuario $usuariosSecundarios)
    {
        $this->usuariosSecundarios[] = $usuariosSecundarios;
    
        return $this;
    }

    /**
     * Remove usuariosSecundarios
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $usuariosSecundarios
     */
    public function removeUsuariosSecundario(\Cacic\CommonBundle\Entity\Usuario $usuariosSecundarios)
    {
        $this->usuariosSecundarios->removeElement($usuariosSecundarios);
    }

    /**
     * Get usuariosSecundarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuariosSecundarios()
    {
        return $this->usuariosSecundarios;
    }
}