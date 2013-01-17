<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SrcacicActions
 *
 * @ORM\Table(name="srcacic_actions")
 * @ORM\Entity
 */
class SrcacicActions
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_srcacic_action", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSrcacicAction;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_action", type="datetime", nullable=false)
     */
    private $dtHrAction;

    /**
     * @var string
     *
     * @ORM\Column(name="te_action", type="string", length=50, nullable=false)
     */
    private $teAction;

    /**
     * @var string
     *
     * @ORM\Column(name="te_param1", type="text", nullable=false)
     */
    private $teParam1;

    /**
     * @var string
     *
     * @ORM\Column(name="te_param2", type="text", nullable=true)
     */
    private $teParam2;

    /**
     * @var integer
     *
     * @ORM\Column(name="te_flag", type="integer", nullable=false)
     */
    private $teFlag;

    /**
     * @var \SrcacicConexoes
     *
     * @ORM\ManyToOne(targetEntity="SrcacicConexoes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_conexao", referencedColumnName="id_conexao")
     * })
     */
    private $idConexao;



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
     * @return SrcacicActions
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
     * @return SrcacicActions
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
     * @return SrcacicActions
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
     * @return SrcacicActions
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
     * @return SrcacicActions
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
     * Set idConexao
     *
     * @param \Cacic\CommonBundle\Entity\SrcacicConexoes $idConexao
     * @return SrcacicActions
     */
    public function setIdConexao(\Cacic\CommonBundle\Entity\SrcacicConexoes $idConexao = null)
    {
        $this->idConexao = $idConexao;
    
        return $this;
    }

    /**
     * Get idConexao
     *
     * @return \Cacic\CommonBundle\Entity\SrcacicConexoes 
     */
    public function getIdConexao()
    {
        return $this->idConexao;
    }
}