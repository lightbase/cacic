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
     * @var integer
     *
     * @ORM\Column(name="id_rede", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRede;

    /**
     * @var \Acoes
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\OneToOne(targetEntity="Acoes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_acao", referencedColumnName="id_acao")
     * })
     */
    private $idAcao;

    /**
     * @var \So
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\OneToOne(targetEntity="So")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_so", referencedColumnName="id_so")
     * })
     */
    private $idSo;



    /**
     * Get idRede
     *
     * @return integer 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }

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