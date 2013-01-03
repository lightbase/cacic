<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acoes
 *
 * @ORM\Table(name="acoes")
 * @ORM\Entity
 */
class Acoes
{
    /**
     * @var string
     *
     * @ORM\Column(name="id_acao", type="string", length=20, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAcao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_descricao_breve", type="string", length=100, nullable=true)
     */
    private $teDescricaoBreve;

    /**
     * @var string
     *
     * @ORM\Column(name="te_descricao", type="text", nullable=true)
     */
    private $teDescricao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_nome_curto_modulo", type="string", length=20, nullable=true)
     */
    private $teNomeCurtoModulo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao", type="datetime", nullable=true)
     */
    private $dtHrAlteracao;

    /**
     * @var string
     *
     * @ORM\Column(name="cs_situacao", type="string", length=1, nullable=true)
     */
    private $csSituacao;



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
     * Set teDescricaoBreve
     *
     * @param string $teDescricaoBreve
     * @return Acoes
     */
    public function setTeDescricaoBreve($teDescricaoBreve)
    {
        $this->teDescricaoBreve = $teDescricaoBreve;
    
        return $this;
    }

    /**
     * Get teDescricaoBreve
     *
     * @return string 
     */
    public function getTeDescricaoBreve()
    {
        return $this->teDescricaoBreve;
    }

    /**
     * Set teDescricao
     *
     * @param string $teDescricao
     * @return Acoes
     */
    public function setTeDescricao($teDescricao)
    {
        $this->teDescricao = $teDescricao;
    
        return $this;
    }

    /**
     * Get teDescricao
     *
     * @return string 
     */
    public function getTeDescricao()
    {
        return $this->teDescricao;
    }

    /**
     * Set teNomeCurtoModulo
     *
     * @param string $teNomeCurtoModulo
     * @return Acoes
     */
    public function setTeNomeCurtoModulo($teNomeCurtoModulo)
    {
        $this->teNomeCurtoModulo = $teNomeCurtoModulo;
    
        return $this;
    }

    /**
     * Get teNomeCurtoModulo
     *
     * @return string 
     */
    public function getTeNomeCurtoModulo()
    {
        return $this->teNomeCurtoModulo;
    }

    /**
     * Set dtHrAlteracao
     *
     * @param \DateTime $dtHrAlteracao
     * @return Acoes
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
     * Set csSituacao
     *
     * @param string $csSituacao
     * @return Acoes
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
}