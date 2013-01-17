<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Compartilhamentos
 *
 * @ORM\Table(name="compartilhamentos")
 * @ORM\Entity
 */
class Compartilhamentos
{
    /**
     * @var string
     *
     * @ORM\Column(name="nm_compartilhamento", type="string", length=30, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nmCompartilhamento;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_dir_compart", type="string", length=100, nullable=true)
     */
    private $nmDirCompart;

    /**
     * @var string
     *
     * @ORM\Column(name="in_senha_escrita", type="string", length=1, nullable=true)
     */
    private $inSenhaEscrita;

    /**
     * @var string
     *
     * @ORM\Column(name="in_senha_leitura", type="string", length=1, nullable=true)
     */
    private $inSenhaLeitura;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_tipo_permissao", type="string", length=1, nullable=true)
     */
    private $csTipoPermissao;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_tipo_compart", type="string", length=1, nullable=true)
     */
    private $csTipoCompart;

    /**
     * @var string
     *
     * @ORM\Column(name="te_comentario", type="string", length=50, nullable=true)
     */
    private $teComentario;

    /**
     * @var \Computadores
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\OneToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;



    /**
     * Get nmCompartilhamento
     *
     * @return string 
     */
    public function getNmCompartilhamento()
    {
        return $this->nmCompartilhamento;
    }

    /**
     * Set nmDirCompart
     *
     * @param string $nmDirCompart
     * @return Compartilhamentos
     */
    public function setNmDirCompart($nmDirCompart)
    {
        $this->nmDirCompart = $nmDirCompart;
    
        return $this;
    }

    /**
     * Get nmDirCompart
     *
     * @return string 
     */
    public function getNmDirCompart()
    {
        return $this->nmDirCompart;
    }

    /**
     * Set inSenhaEscrita
     *
     * @param string $inSenhaEscrita
     * @return Compartilhamentos
     */
    public function setInSenhaEscrita($inSenhaEscrita)
    {
        $this->inSenhaEscrita = $inSenhaEscrita;
    
        return $this;
    }

    /**
     * Get inSenhaEscrita
     *
     * @return string 
     */
    public function getInSenhaEscrita()
    {
        return $this->inSenhaEscrita;
    }

    /**
     * Set inSenhaLeitura
     *
     * @param string $inSenhaLeitura
     * @return Compartilhamentos
     */
    public function setInSenhaLeitura($inSenhaLeitura)
    {
        $this->inSenhaLeitura = $inSenhaLeitura;
    
        return $this;
    }

    /**
     * Get inSenhaLeitura
     *
     * @return string 
     */
    public function getInSenhaLeitura()
    {
        return $this->inSenhaLeitura;
    }

    /**
     * Set csTipoPermissao
     *
     * @param string $csTipoPermissao
     * @return Compartilhamentos
     */
    public function setCsTipoPermissao($csTipoPermissao)
    {
        $this->csTipoPermissao = $csTipoPermissao;
    
        return $this;
    }

    /**
     * Get csTipoPermissao
     *
     * @return string 
     */
    public function getCsTipoPermissao()
    {
        return $this->csTipoPermissao;
    }

    /**
     * Set csTipoCompart
     *
     * @param string $csTipoCompart
     * @return Compartilhamentos
     */
    public function setCsTipoCompart($csTipoCompart)
    {
        $this->csTipoCompart = $csTipoCompart;
    
        return $this;
    }

    /**
     * Get csTipoCompart
     *
     * @return string 
     */
    public function getCsTipoCompart()
    {
        return $this->csTipoCompart;
    }

    /**
     * Set teComentario
     *
     * @param string $teComentario
     * @return Compartilhamentos
     */
    public function setTeComentario($teComentario)
    {
        $this->teComentario = $teComentario;
    
        return $this;
    }

    /**
     * Get teComentario
     *
     * @return string 
     */
    public function getTeComentario()
    {
        return $this->teComentario;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return Compartilhamentos
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computadores $idComputador)
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
}