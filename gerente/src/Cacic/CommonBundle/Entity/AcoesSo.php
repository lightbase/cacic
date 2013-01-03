<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AcoesSo
 *
 * @ORM\Table(name="acoes_so")
 * @ORM\Entity
 */
class AcoesSo
{
    /**
     * @var \Acoes
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Acoes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_acao", referencedColumnName="id_acao")
     * })
     */
    private $idAcao;

    /**
     * @var \Locais
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Locais")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_local", referencedColumnName="id_local")
     * })
     */
    private $idLocal;

    /**
     * @var \So
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="So")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_so", referencedColumnName="id_so")
     * })
     */
    private $idSo;



    /**
     * Set idAcao
     *
     * @param \Cacic\CommonBundle\Entity\Acoes $idAcao
     * @return AcoesSo
     */
    public function setIdAcao(\Cacic\CommonBundle\Entity\Acoes $idAcao)
    {
        $this->idAcao = $idAcao;
    
        return $this;
    }

    /**
     * Get idAcao
     *
     * @return \Cacic\CommonBundle\Entity\Acoes 
     */
    public function getIdAcao()
    {
        return $this->idAcao;
    }

    /**
     * Set idLocal
     *
     * @param \Cacic\CommonBundle\Entity\Locais $idLocal
     * @return AcoesSo
     */
    public function setIdLocal(\Cacic\CommonBundle\Entity\Locais $idLocal)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return \Cacic\CommonBundle\Entity\Locais 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set idSo
     *
     * @param \Cacic\CommonBundle\Entity\So $idSo
     * @return AcoesSo
     */
    public function setIdSo(\Cacic\CommonBundle\Entity\So $idSo)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

    /**
     * Get idSo
     *
     * @return \Cacic\CommonBundle\Entity\So 
     */
    public function getIdSo()
    {
        return $this->idSo;
    }
}