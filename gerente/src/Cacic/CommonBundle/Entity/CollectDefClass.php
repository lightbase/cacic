<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CollectDefClass
 */
class CollectDefClass
{
    /**
     * @var integer
     */
    private $idCollectDefClass;

    /**
     * @var string
     */
    private $teWhereClause;

    /**
     * @var \Cacic\CommonBundle\Entity\Acao
     */
    private $idAcao;

    /**
     * @var \Cacic\CommonBundle\Entity\Classe
     */
    private $idClass;


    /**
     * Get idCollectDefClass
     *
     * @return integer 
     */
    public function getIdCollectDefClass()
    {
        return $this->idCollectDefClass;
    }

    /**
     * Set teWhereClause
     *
     * @param string $teWhereClause
     * @return CollectDefClass
     */
    public function setTeWhereClause($teWhereClause)
    {
        $this->teWhereClause = $teWhereClause;
    
        return $this;
    }

    /**
     * Get teWhereClause
     *
     * @return string 
     */
    public function getTeWhereClause()
    {
        return $this->teWhereClause;
    }

    /**
     * Set idAcao
     *
     * @param \Cacic\CommonBundle\Entity\Acao $idAcao
     * @return CollectDefClass
     */
    public function setIdAcao(\Cacic\CommonBundle\Entity\Acao $idAcao = null)
    {
        $this->idAcao = $idAcao;
    
        return $this;
    }

    /**
     * Get idAcao
     *
     * @return \Cacic\CommonBundle\Entity\Acao 
     */
    public function getIdAcao()
    {
        return $this->idAcao;
    }

    /**
     * Set idClass
     *
     * @param \Cacic\CommonBundle\Entity\Classe $idClass
     * @return CollectDefClass
     */
    public function setIdClass(\Cacic\CommonBundle\Entity\Classe $idClass = null)
    {
        $this->idClass = $idClass;
    
        return $this;
    }

    /**
     * Get idClass
     *
     * @return \Cacic\CommonBundle\Entity\Classe 
     */
    public function getIdClass()
    {
        return $this->idClass;
    }
}