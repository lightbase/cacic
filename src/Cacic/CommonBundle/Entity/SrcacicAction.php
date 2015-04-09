<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicAction
 */
class SrcacicAction
{
    /**
     * @var integer
     */
    private $idSrcacicAction;

    /**
     * @var \DateTime
     */
    private $dtHrAction;

    /**
     * @var string
     */
    private $teAction;

    /**
     * @var string
     */
    private $teParam1;

    /**
     * @var string
     */
    private $teParam2;

    /**
     * @var integer
     */
    private $teFlag;

    /**
     * @var \Cacic\CommonBundle\Entity\SrcacicConexao
     */
    private $idSrcacicConexao;


    /**
     * Get idSrcacicAction
     *
     * @return integer 
     */
    public function getIdSrcacicAction()
    {
        return $this->idSrcacicAction;
    }

    /**
     * Set dtHrAction
     *
     * @param \DateTime $dtHrAction
     * @return SrcacicAction
     */
    public function setDtHrAction($dtHrAction)
    {
        $this->dtHrAction = $dtHrAction;
    
        return $this;
    }

    /**
     * Get dtHrAction
     *
     * @return \DateTime 
     */
    public function getDtHrAction()
    {
        return $this->dtHrAction;
    }

    /**
     * Set teAction
     *
     * @param string $teAction
     * @return SrcacicAction
     */
    public function setTeAction($teAction)
    {
        $this->teAction = $teAction;
    
        return $this;
    }

    /**
     * Get teAction
     *
     * @return string 
     */
    public function getTeAction()
    {
        return $this->teAction;
    }

    /**
     * Set teParam1
     *
     * @param string $teParam1
     * @return SrcacicAction
     */
    public function setTeParam1($teParam1)
    {
        $this->teParam1 = $teParam1;
    
        return $this;
    }

    /**
     * Get teParam1
     *
     * @return string 
     */
    public function getTeParam1()
    {
        return $this->teParam1;
    }

    /**
     * Set teParam2
     *
     * @param string $teParam2
     * @return SrcacicAction
     */
    public function setTeParam2($teParam2)
    {
        $this->teParam2 = $teParam2;
    
        return $this;
    }

    /**
     * Get teParam2
     *
     * @return string 
     */
    public function getTeParam2()
    {
        return $this->teParam2;
    }

    /**
     * Set teFlag
     *
     * @param integer $teFlag
     * @return SrcacicAction
     */
    public function setTeFlag($teFlag)
    {
        $this->teFlag = $teFlag;
    
        return $this;
    }

    /**
     * Get teFlag
     *
     * @return integer 
     */
    public function getTeFlag()
    {
        return $this->teFlag;
    }

    /**
     * Set idSrcacicConexao
     *
     * @param \Cacic\CommonBundle\Entity\SrcacicConexao $idSrcacicConexao
     * @return SrcacicAction
     */
    public function setIdSrcacicConexao(\Cacic\CommonBundle\Entity\SrcacicConexao $idSrcacicConexao = null)
    {
        $this->idSrcacicConexao = $idSrcacicConexao;
    
        return $this;
    }

    /**
     * Get idSrcacicConexao
     *
     * @return \Cacic\CommonBundle\Entity\SrcacicConexao 
     */
    public function getIdSrcacicConexao()
    {
        return $this->idSrcacicConexao;
    }
}