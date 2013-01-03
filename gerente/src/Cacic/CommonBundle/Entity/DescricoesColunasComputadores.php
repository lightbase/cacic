<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DescricoesColunasComputadores
 *
 * @ORM\Table(name="descricoes_colunas_computadores")
 * @ORM\Entity
 */
class DescricoesColunasComputadores
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_descricao_coluna_computador", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDescricaoColunaComputador;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_campo", type="string", length=100, nullable=false)
     */
    private $nmCampo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_descricao_campo", type="string", length=100, nullable=false)
     */
    private $teDescricaoCampo;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_condicao_pesquisa", type="string", length=1, nullable=false)
     */
    private $csCondicaoPesquisa;



    /**
     * Get idDescricaoColunaComputador
     *
     * @return integer 
     */
    public function getIdDescricaoColunaComputador()
    {
        return $this->idDescricaoColunaComputador;
    }

    /**
     * Set nmCampo
     *
     * @param string $nmCampo
     * @return DescricoesColunasComputadores
     */
    public function setNmCampo($nmCampo)
    {
        $this->nmCampo = $nmCampo;
    
        return $this;
    }

    /**
     * Get nmCampo
     *
     * @return string 
     */
    public function getNmCampo()
    {
        return $this->nmCampo;
    }

    /**
     * Set teDescricaoCampo
     *
     * @param string $teDescricaoCampo
     * @return DescricoesColunasComputadores
     */
    public function setTeDescricaoCampo($teDescricaoCampo)
    {
        $this->teDescricaoCampo = $teDescricaoCampo;
    
        return $this;
    }

    /**
     * Get teDescricaoCampo
     *
     * @return string 
     */
    public function getTeDescricaoCampo()
    {
        return $this->teDescricaoCampo;
    }

    /**
     * Set csCondicaoPesquisa
     *
     * @param string $csCondicaoPesquisa
     * @return DescricoesColunasComputadores
     */
    public function setCsCondicaoPesquisa($csCondicaoPesquisa)
    {
        $this->csCondicaoPesquisa = $csCondicaoPesquisa;
    
        return $this;
    }

    /**
     * Get csCondicaoPesquisa
     *
     * @return string 
     */
    public function getCsCondicaoPesquisa()
    {
        return $this->csCondicaoPesquisa;
    }
}