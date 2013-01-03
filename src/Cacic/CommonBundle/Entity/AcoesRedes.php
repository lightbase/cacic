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
     * @ORM\Column(name="id_local", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idLocal;

    /**
     * @var string
     *
     * @ORM\Column(name="id_ip_rede", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idIpRede;

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
     * @var string
     *
     * @ORM\Column(name="cs_situacao", type="string", length=1, nullable=false)
     */
    private $csSituacao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao", type="datetime", nullable=true)
     */
    private $dtHrAlteracao;



    /**
     * Set idLocal
     *
     * @param integer $idLocal
     * @return AcoesRedes
     */
    public function setIdLocal($idLocal)
    {
        $this->idLocal = $idLocal;
    
        return $this;
    }

    /**
     * Get idLocal
     *
     * @return integer 
     */
    public function getIdLocal()
    {
        return $this->idLocal;
    }

    /**
     * Set idIpRede
     *
     * @param string $idIpRede
     * @return AcoesRedes
     */
    public function setIdIpRede($idIpRede)
    {
        $this->idIpRede = $idIpRede;
    
        return $this;
    }

    /**
     * Get idIpRede
     *
     * @return string 
     */
    public function getIdIpRede()
    {
        return $this->idIpRede;
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

    /**
     * Set csSituacao
     *
     * @param string $csSituacao
     * @return AcoesRedes
     */
    public function setCsSituacao($csSituacao)
    {
        $this->csSituacao = $csSituacao;
    
        return $this;
    }

    /**
     * Get csSituacao
     *
     * @return string 
     */
    public function getCsSituacao()
    {
        return $this->csSituacao;
    }

    /**
     * Set dtHrAlteracao
     *
     * @param \DateTime $dtHrAlteracao
     * @return AcoesRedes
     */
    public function setDtHrAlteracao($dtHrAlteracao)
    {
        $this->dtHrAlteracao = $dtHrAlteracao;
    
        return $this;
    }

    /**
     * Get dtHrAlteracao
     *
     * @return \DateTime 
     */
    public function getDtHrAlteracao()
    {
        return $this->dtHrAlteracao;
    }
}