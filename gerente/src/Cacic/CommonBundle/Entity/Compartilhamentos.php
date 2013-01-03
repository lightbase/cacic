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
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $nmCompartilhamento;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $teNodeAddress;

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
     * Set nmCompartilhamento
     *
     * @param string $nmCompartilhamento
     * @return Compartilhamentos
     */
    public function setNmCompartilhamento($nmCompartilhamento)
    {
        $this->nmCompartilhamento = $nmCompartilhamento;
    
        return $this;
    }

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
     * Set idSo
     *
     * @param integer $idSo
     * @return Compartilhamentos
     */
    public function setIdSo($idSo)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

    /**
     * Get idSo
     *
     * @return integer 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }

    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return Compartilhamentos
     */
    public function setTeNodeAddress($teNodeAddress)
    {
        $this->teNodeAddress = $teNodeAddress;
    
        return $this;
    }

    /**
     * Get teNodeAddress
     *
     * @return string 
     */
    public function getTeNodeAddress()
    {
        return $this->teNodeAddress;
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
}