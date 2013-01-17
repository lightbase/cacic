<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VersoesSoftwares
 *
 * @ORM\Table(name="versoes_softwares")
 * @ORM\Entity
 */
class VersoesSoftwares
{
    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_bde", type="string", length=10, nullable=true)
     */
    private $teVersaoBde;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_dao", type="string", length=5, nullable=true)
     */
    private $teVersaoDao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_ado", type="string", length=5, nullable=true)
     */
    private $teVersaoAdo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_odbc", type="string", length=15, nullable=true)
     */
    private $teVersaoOdbc;

    /**
     * @var integer
     *
     * @ORM\Column(name="te_versao_directx", type="integer", nullable=true)
     */
    private $teVersaoDirectx;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_acrobat_reader", type="string", length=10, nullable=true)
     */
    private $teVersaoAcrobatReader;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_ie", type="string", length=18, nullable=true)
     */
    private $teVersaoIe;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_mozilla", type="string", length=12, nullable=true)
     */
    private $teVersaoMozilla;

    /**
     * @var string
     *
     * @ORM\Column(name="te_versao_jre", type="string", length=6, nullable=true)
     */
    private $teVersaoJre;

    /**
     * @var \Computadores
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Computadores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_computador", referencedColumnName="id_computador")
     * })
     */
    private $idComputador;



    /**
     * Set teVersaoBde
     *
     * @param string $teVersaoBde
     * @return VersoesSoftwares
     */
    public function setTeVersaoBde($teVersaoBde)
    {
        $this->teVersaoBde = $teVersaoBde;
    
        return $this;
    }

    /**
     * Get teVersaoBde
     *
     * @return string 
     */
    public function getTeVersaoBde()
    {
        return $this->teVersaoBde;
    }

    /**
     * Set teVersaoDao
     *
     * @param string $teVersaoDao
     * @return VersoesSoftwares
     */
    public function setTeVersaoDao($teVersaoDao)
    {
        $this->teVersaoDao = $teVersaoDao;
    
        return $this;
    }

    /**
     * Get teVersaoDao
     *
     * @return string 
     */
    public function getTeVersaoDao()
    {
        return $this->teVersaoDao;
    }

    /**
     * Set teVersaoAdo
     *
     * @param string $teVersaoAdo
     * @return VersoesSoftwares
     */
    public function setTeVersaoAdo($teVersaoAdo)
    {
        $this->teVersaoAdo = $teVersaoAdo;
    
        return $this;
    }

    /**
     * Get teVersaoAdo
     *
     * @return string 
     */
    public function getTeVersaoAdo()
    {
        return $this->teVersaoAdo;
    }

    /**
     * Set teVersaoOdbc
     *
     * @param string $teVersaoOdbc
     * @return VersoesSoftwares
     */
    public function setTeVersaoOdbc($teVersaoOdbc)
    {
        $this->teVersaoOdbc = $teVersaoOdbc;
    
        return $this;
    }

    /**
     * Get teVersaoOdbc
     *
     * @return string 
     */
    public function getTeVersaoOdbc()
    {
        return $this->teVersaoOdbc;
    }

    /**
     * Set teVersaoDirectx
     *
     * @param integer $teVersaoDirectx
     * @return VersoesSoftwares
     */
    public function setTeVersaoDirectx($teVersaoDirectx)
    {
        $this->teVersaoDirectx = $teVersaoDirectx;
    
        return $this;
    }

    /**
     * Get teVersaoDirectx
     *
     * @return integer 
     */
    public function getTeVersaoDirectx()
    {
        return $this->teVersaoDirectx;
    }

    /**
     * Set teVersaoAcrobatReader
     *
     * @param string $teVersaoAcrobatReader
     * @return VersoesSoftwares
     */
    public function setTeVersaoAcrobatReader($teVersaoAcrobatReader)
    {
        $this->teVersaoAcrobatReader = $teVersaoAcrobatReader;
    
        return $this;
    }

    /**
     * Get teVersaoAcrobatReader
     *
     * @return string 
     */
    public function getTeVersaoAcrobatReader()
    {
        return $this->teVersaoAcrobatReader;
    }

    /**
     * Set teVersaoIe
     *
     * @param string $teVersaoIe
     * @return VersoesSoftwares
     */
    public function setTeVersaoIe($teVersaoIe)
    {
        $this->teVersaoIe = $teVersaoIe;
    
        return $this;
    }

    /**
     * Get teVersaoIe
     *
     * @return string 
     */
    public function getTeVersaoIe()
    {
        return $this->teVersaoIe;
    }

    /**
     * Set teVersaoMozilla
     *
     * @param string $teVersaoMozilla
     * @return VersoesSoftwares
     */
    public function setTeVersaoMozilla($teVersaoMozilla)
    {
        $this->teVersaoMozilla = $teVersaoMozilla;
    
        return $this;
    }

    /**
     * Get teVersaoMozilla
     *
     * @return string 
     */
    public function getTeVersaoMozilla()
    {
        return $this->teVersaoMozilla;
    }

    /**
     * Set teVersaoJre
     *
     * @param string $teVersaoJre
     * @return VersoesSoftwares
     */
    public function setTeVersaoJre($teVersaoJre)
    {
        $this->teVersaoJre = $teVersaoJre;
    
        return $this;
    }

    /**
     * Get teVersaoJre
     *
     * @return string 
     */
    public function getTeVersaoJre()
    {
        return $this->teVersaoJre;
    }

    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computadores $idComputador
     * @return VersoesSoftwares
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