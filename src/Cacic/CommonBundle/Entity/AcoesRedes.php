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
     * @var \Redes
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Redes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ip_rede", referencedColumnName="id_ip_rede")
     * })
     */
    private $idIpRede;

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

    /**
     * Set idIpRede
     *
     * @param \Cacic\CommonBundle\Entity\Redes $idIpRede
     * @return AcoesRedes
     */
    public function setIdIpRede(\Cacic\CommonBundle\Entity\Redes $idIpRede)
    {
        $this->idIpRede = $idIpRede;
    
        return $this;
    }

    /**
     * Get idIpRede
     *
     * @return \Cacic\CommonBundle\Entity\Redes 
     */
    public function getIdIpRede()
    {
        return $this->idIpRede;
    }

    /**
     * Set idAcao
     *
     * @param \Cacic\CommonBundle\Entity\Acoes $idAcao
     * @return AcoesRedes
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
     * @return AcoesRedes
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
}