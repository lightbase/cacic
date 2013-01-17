<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AcoesRedes
 *
 * @ORM\Table(name="acoes_redes")
 * @ORM\Entity
 */
class AcoesRedes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_rede", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idRede;

    /**
     * @var string
     *
     * @ORM\Column(name="id_acao", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idAcao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_coleta_forcada", type="datetime", nullable=true)
     */
    private $dtHrColetaForcada;



    /**
     * Set idRede
     *
     * @param integer $idRede
     * @return AcoesRedes
     */
    public function setIdRede($idRede)
    {
        $this->idRede = $idRede;
    
        return $this;
    }

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
     * @param string $idAcao
     * @return AcoesRedes
     */
    public function setIdAcao($idAcao)
    {
        $this->idAcao = $idAcao;
    
        return $this;
    }

    /**
     * Get idAcao
     *
     * @return string 
     */
    public function getIdAcao()
    {
        return $this->idAcao;
    }

    /**
     * Set dtHrColetaForcada
     *
     * @param \DateTime $dtHrColetaForcada
     * @return AcoesRedes
     */
    public function setDtHrColetaForcada($dtHrColetaForcada)
    {
        $this->dtHrColetaForcada = $dtHrColetaForcada;
    
        return $this;
    }

    /**
     * Get dtHrColetaForcada
     *
     * @return \DateTime 
     */
    public function getDtHrColetaForcada()
    {
        return $this->dtHrColetaForcada;
    }
}