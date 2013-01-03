<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aquisicoes
 *
 * @ORM\Table(name="aquisicoes")
 * @ORM\Entity
 */
class Aquisicoes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_aquisicao", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAquisicao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_aquisicao", type="date", nullable=true)
     */
    private $dtAquisicao;

    /**
     * @var string
     *
     * @ORM\Column(name="nr_processo", type="string", length=11, nullable=true)
     */
    private $nrProcesso;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_empresa", type="string", length=45, nullable=true)
     */
    private $nmEmpresa;

    /**
     * @var string
     *
     * @ORM\Column(name="nm_proprietario", type="string", length=45, nullable=true)
     */
    private $nmProprietario;

    /**
     * @var string
     *
     * @ORM\Column(name="nr_notafiscal", type="string", length=20, nullable=true)
     */
    private $nrNotafiscal;



    /**
     * Get idAquisicao
     *
     * @return integer 
     */
    public function getIdAquisicao()
    {
        return $this->idAquisicao;
    }

    /**
     * Set dtAquisicao
     *
     * @param \DateTime $dtAquisicao
     * @return Aquisicoes
     */
    public function setDtAquisicao($dtAquisicao)
    {
        $this->dtAquisicao = $dtAquisicao;
    
        return $this;
    }

    /**
     * Get dtAquisicao
     *
     * @return \DateTime 
     */
    public function getDtAquisicao()
    {
        return $this->dtAquisicao;
    }

    /**
     * Set nrProcesso
     *
     * @param string $nrProcesso
     * @return Aquisicoes
     */
    public function setNrProcesso($nrProcesso)
    {
        $this->nrProcesso = $nrProcesso;
    
        return $this;
    }

    /**
     * Get nrProcesso
     *
     * @return string 
     */
    public function getNrProcesso()
    {
        return $this->nrProcesso;
    }

    /**
     * Set nmEmpresa
     *
     * @param string $nmEmpresa
     * @return Aquisicoes
     */
    public function setNmEmpresa($nmEmpresa)
    {
        $this->nmEmpresa = $nmEmpresa;
    
        return $this;
    }

    /**
     * Get nmEmpresa
     *
     * @return string 
     */
    public function getNmEmpresa()
    {
        return $this->nmEmpresa;
    }

    /**
     * Set nmProprietario
     *
     * @param string $nmProprietario
     * @return Aquisicoes
     */
    public function setNmProprietario($nmProprietario)
    {
        $this->nmProprietario = $nmProprietario;
    
        return $this;
    }

    /**
     * Get nmProprietario
     *
     * @return string 
     */
    public function getNmProprietario()
    {
        return $this->nmProprietario;
    }

    /**
     * Set nrNotafiscal
     *
     * @param string $nrNotafiscal
     * @return Aquisicoes
     */
    public function setNrNotafiscal($nrNotafiscal)
    {
        $this->nrNotafiscal = $nrNotafiscal;
    
        return $this;
    }

    /**
     * Get nrNotafiscal
     *
     * @return string 
     */
    public function getNrNotafiscal()
    {
        return $this->nrNotafiscal;
    }
}