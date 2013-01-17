<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServidoresAutenticacao
 *
 * @ORM\Table(name="servidores_autenticacao")
 * @ORM\Entity
 */
class ServidoresAutenticacao
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_servidor_autenticacao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idServidorAutenticacao;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_servidor_autenticacao", type="string", length=60, nullable=false)
     */
    private $nmServidorAutenticacao;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_servidor_autenticacao_dns", type="string", length=60, nullable=false)
     */
    private $nmServidorAutenticacaoDns;

    /**
     * @var string
     *
     * @ORM\Column(name="te_ip_servidor_autenticacao", type="string", length=15, nullable=false)
     */
    private $teIpServidorAutenticacao;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_porta_servidor_autenticacao", type="string", length=5, nullable=false)
     */
    private $nuPortaServidorAutenticacao;

    /**
     * @var string
     *
     * @ORM\Column(name="id_tipo_protocolo", type="string", length=20, nullable=false)
     */
    private $idTipoProtocolo;

    /**
     * @var string
     *
     * @ORM\Column(name="nu_versao_protocolo", type="string", length=10, nullable=false)
     */
    private $nuVersaoProtocolo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_atributo_identificador", type="string", length=100, nullable=false)
     */
    private $teAtributoIdentificador;

    /**
     * @var string
     *
     * @ORM\Column(name="te_atributo_identificador_alternativo", type="string", length=100, nullable=true)
     */
    private $teAtributoIdentificadorAlternativo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_atributo_retorna_nome", type="string", length=100, nullable=false)
     */
    private $teAtributoRetornaNome;

    /**
     * @var string
     *
     * @ORM\Column(name="te_atributo_retorna_email", type="string", length=100, nullable=false)
     */
    private $teAtributoRetornaEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="te_atributo_retorna_telefone", type="string", length=100, nullable=true)
     */
    private $teAtributoRetornaTelefone;

    /**
     * @var string
     *
     * @ORM\Column(name="te_atributo_status_conta", type="string", length=100, nullable=true)
     */
    private $teAtributoStatusConta;

    /**
     * @var string
     *
     * @ORM\Column(name="te_atributo_valor_status_conta_valida", type="string", length=100, nullable=false)
     */
    private $teAtributoValorStatusContaValida;

    /**
     * @var string
     *
     * @ORM\Column(name="te_observacao", type="text", nullable=false)
     */
    private $teObservacao;

    /**
     * @var string
     *
     * @ORM\Column(name="in_ativo", type="string", length=1, nullable=false)
     */
    private $inAtivo;



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
     * Set nmServidorAutenticacao
     *
     * @param string $nmServidorAutenticacao
     * @return ServidoresAutenticacao
     */
    public function setNmServidorAutenticacao($nmServidorAutenticacao)
    {
        $this->nmServidorAutenticacao = $nmServidorAutenticacao;
    
        return $this;
    }

    /**
     * Get nmServidorAutenticacao
     *
     * @return string 
     */
    public function getNmServidorAutenticacao()
    {
        return $this->nmServidorAutenticacao;
    }

    /**
     * Set nmServidorAutenticacaoDns
     *
     * @param string $nmServidorAutenticacaoDns
     * @return ServidoresAutenticacao
     */
    public function setNmServidorAutenticacaoDns($nmServidorAutenticacaoDns)
    {
        $this->nmServidorAutenticacaoDns = $nmServidorAutenticacaoDns;
    
        return $this;
    }

    /**
     * Get nmServidorAutenticacaoDns
     *
     * @return string 
     */
    public function getNmServidorAutenticacaoDns()
    {
        return $this->nmServidorAutenticacaoDns;
    }

    /**
     * Set teIpServidorAutenticacao
     *
     * @param string $teIpServidorAutenticacao
     * @return ServidoresAutenticacao
     */
    public function setTeIpServidorAutenticacao($teIpServidorAutenticacao)
    {
        $this->teIpServidorAutenticacao = $teIpServidorAutenticacao;
    
        return $this;
    }

    /**
     * Get teIpServidorAutenticacao
     *
     * @return string 
     */
    public function getTeIpServidorAutenticacao()
    {
        return $this->teIpServidorAutenticacao;
    }

    /**
     * Set nuPortaServidorAutenticacao
     *
     * @param string $nuPortaServidorAutenticacao
     * @return ServidoresAutenticacao
     */
    public function setNuPortaServidorAutenticacao($nuPortaServidorAutenticacao)
    {
        $this->nuPortaServidorAutenticacao = $nuPortaServidorAutenticacao;
    
        return $this;
    }

    /**
     * Get nuPortaServidorAutenticacao
     *
     * @return string 
     */
    public function getNuPortaServidorAutenticacao()
    {
        return $this->nuPortaServidorAutenticacao;
    }

    /**
     * Set idTipoProtocolo
     *
     * @param string $idTipoProtocolo
     * @return ServidoresAutenticacao
     */
    public function setIdTipoProtocolo($idTipoProtocolo)
    {
        $this->idTipoProtocolo = $idTipoProtocolo;
    
        return $this;
    }

    /**
     * Get idTipoProtocolo
     *
     * @return string 
     */
    public function getIdTipoProtocolo()
    {
        return $this->idTipoProtocolo;
    }

    /**
     * Set nuVersaoProtocolo
     *
     * @param string $nuVersaoProtocolo
     * @return ServidoresAutenticacao
     */
    public function setNuVersaoProtocolo($nuVersaoProtocolo)
    {
        $this->nuVersaoProtocolo = $nuVersaoProtocolo;
    
        return $this;
    }

    /**
     * Get nuVersaoProtocolo
     *
     * @return string 
     */
    public function getNuVersaoProtocolo()
    {
        return $this->nuVersaoProtocolo;
    }

    /**
     * Set teAtributoIdentificador
     *
     * @param string $teAtributoIdentificador
     * @return ServidoresAutenticacao
     */
    public function setTeAtributoIdentificador($teAtributoIdentificador)
    {
        $this->teAtributoIdentificador = $teAtributoIdentificador;
    
        return $this;
    }

    /**
     * Get teAtributoIdentificador
     *
     * @return string 
     */
    public function getTeAtributoIdentificador()
    {
        return $this->teAtributoIdentificador;
    }

    /**
     * Set teAtributoIdentificadorAlternativo
     *
     * @param string $teAtributoIdentificadorAlternativo
     * @return ServidoresAutenticacao
     */
    public function setTeAtributoIdentificadorAlternativo($teAtributoIdentificadorAlternativo)
    {
        $this->teAtributoIdentificadorAlternativo = $teAtributoIdentificadorAlternativo;
    
        return $this;
    }

    /**
     * Get teAtributoIdentificadorAlternativo
     *
     * @return string 
     */
    public function getTeAtributoIdentificadorAlternativo()
    {
        return $this->teAtributoIdentificadorAlternativo;
    }

    /**
     * Set teAtributoRetornaNome
     *
     * @param string $teAtributoRetornaNome
     * @return ServidoresAutenticacao
     */
    public function setTeAtributoRetornaNome($teAtributoRetornaNome)
    {
        $this->teAtributoRetornaNome = $teAtributoRetornaNome;
    
        return $this;
    }

    /**
     * Get teAtributoRetornaNome
     *
     * @return string 
     */
    public function getTeAtributoRetornaNome()
    {
        return $this->teAtributoRetornaNome;
    }

    /**
     * Set teAtributoRetornaEmail
     *
     * @param string $teAtributoRetornaEmail
     * @return ServidoresAutenticacao
     */
    public function setTeAtributoRetornaEmail($teAtributoRetornaEmail)
    {
        $this->teAtributoRetornaEmail = $teAtributoRetornaEmail;
    
        return $this;
    }

    /**
     * Get teAtributoRetornaEmail
     *
     * @return string 
     */
    public function getTeAtributoRetornaEmail()
    {
        return $this->teAtributoRetornaEmail;
    }

    /**
     * Set teAtributoRetornaTelefone
     *
     * @param string $teAtributoRetornaTelefone
     * @return ServidoresAutenticacao
     */
    public function setTeAtributoRetornaTelefone($teAtributoRetornaTelefone)
    {
        $this->teAtributoRetornaTelefone = $teAtributoRetornaTelefone;
    
        return $this;
    }

    /**
     * Get teAtributoRetornaTelefone
     *
     * @return string 
     */
    public function getTeAtributoRetornaTelefone()
    {
        return $this->teAtributoRetornaTelefone;
    }

    /**
     * Set teAtributoStatusConta
     *
     * @param string $teAtributoStatusConta
     * @return ServidoresAutenticacao
     */
    public function setTeAtributoStatusConta($teAtributoStatusConta)
    {
        $this->teAtributoStatusConta = $teAtributoStatusConta;
    
        return $this;
    }

    /**
     * Get teAtributoStatusConta
     *
     * @return string 
     */
    public function getTeAtributoStatusConta()
    {
        return $this->teAtributoStatusConta;
    }

    /**
     * Set teAtributoValorStatusContaValida
     *
     * @param string $teAtributoValorStatusContaValida
     * @return ServidoresAutenticacao
     */
    public function setTeAtributoValorStatusContaValida($teAtributoValorStatusContaValida)
    {
        $this->teAtributoValorStatusContaValida = $teAtributoValorStatusContaValida;
    
        return $this;
    }

    /**
     * Get teAtributoValorStatusContaValida
     *
     * @return string 
     */
    public function getTeAtributoValorStatusContaValida()
    {
        return $this->teAtributoValorStatusContaValida;
    }

    /**
     * Set teObservacao
     *
     * @param string $teObservacao
     * @return ServidoresAutenticacao
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
     * Set inAtivo
     *
     * @param string $inAtivo
     * @return ServidoresAutenticacao
     */
    public function setInAtivo($inAtivo)
    {
        $this->inAtivo = $inAtivo;
    
        return $this;
    }

    /**
     * Get inAtivo
     *
     * @return string 
     */
    public function getInAtivo()
    {
        return $this->inAtivo;
    }
}