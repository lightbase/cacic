<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServidorAutenticacao
 */
class ServidorAutenticacao
{
    /**
     * @var integer
     */
    private $idServidorAutenticacao;

    /**
     * @var string
     */
    private $nmServidorAutenticacao;

    /**
     * @var string
     */
    private $nmServidorAutenticacaoDns;

    /**
     * @var string
     */
    private $teIpServidorAutenticacao;

    /**
     * @var string
     */
    private $nuPortaServidorAutenticacao;

    /**
     * @var string
     */
    private $idTipoProtocolo;

    /**
     * @var string
     */
    private $nuVersaoProtocolo;

    /**
     * @var string
     */
    private $teAtributoIdentificador;

    /**
     * @var string
     */
    private $teAtributoIdentificadorAlternativo;

    /**
     * @var string
     */
    private $teAtributoRetornaNome;

    /**
     * @var string
     */
    private $teAtributoRetornaEmail;

    /**
     * @var string
     */
    private $teAtributoRetornaTelefone;

    /**
     * @var string
     */
    private $teAtributoStatusConta;

    /**
     * @var string
     */
    private $teAtributoValorStatusContaValida;

    /**
     * @var string
     */
    private $teObservacao;

    /**
     * @var string
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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
     * @return ServidorAutenticacao
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