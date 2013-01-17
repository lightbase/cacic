<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * So
 *
 * @ORM\Table(name="so")
 * @ORM\Entity
 */
class So
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_desc_so", type="string", length=50, nullable=true)
     */
    private $teDescSo;

    /**
     * @var string
     *
     * @ORM\Column(name="sg_so", type="string", length=20, nullable=true)
     */
    private $sgSo;

    /**
     * @var string
     *
     * @ORM\Column(name="te_so", type="string", length=50, nullable=false)
     */
    private $teSo;

    /**
     * @var string
     *
     * @ORM\Column(name="in_mswindows", type="string", length=1, nullable=false)
     */
    private $inMswindows;



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
     * Set teDescSo
     *
     * @param string $teDescSo
     * @return So
     */
    public function setTeDescSo($teDescSo)
    {
        $this->teDescSo = $teDescSo;
    
        return $this;
    }

    /**
     * Get teDescSo
     *
     * @return string 
     */
    public function getTeDescSo()
    {
        return $this->teDescSo;
    }

    /**
     * Set sgSo
     *
     * @param string $sgSo
     * @return So
     */
    public function setSgSo($sgSo)
    {
        $this->sgSo = $sgSo;
    
        return $this;
    }

    /**
     * Get sgSo
     *
     * @return string 
     */
    public function getSgSo()
    {
        return $this->sgSo;
    }

    /**
     * Set teSo
     *
     * @param string $teSo
     * @return So
     */
    public function setTeSo($teSo)
    {
        $this->teSo = $teSo;
    
        return $this;
    }

    /**
     * Get teSo
     *
     * @return string 
     */
    public function getTeSo()
    {
        return $this->teSo;
    }

    /**
     * Set inMswindows
     *
     * @param string $inMswindows
     * @return So
     */
    public function setInMswindows($inMswindows)
    {
        $this->inMswindows = $inMswindows;
    
        return $this;
    }

    /**
     * Get inMswindows
     *
     * @return string 
     */
    public function getInMswindows()
    {
        return $this->inMswindows;
    }
}