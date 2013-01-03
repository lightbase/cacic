<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricoTcpIp
 *
 * @ORM\Table(name="historico_tcp_ip")
 * @ORM\Entity
 */
class HistoricoTcpIp
{
    /**
     * @var string
     *
     * @ORM\Column(name="te_node_address", type="string", length=17, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $teNodeAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_so", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dt_hr_alteracao", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $dtHrAlteracao;

    /**
     * @var string
     *
     * @ORM\Column(name="te_nome_computador", type="string", length=25, nullable=true)
     */
    private $teNomeComputador;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dominio_windows", type="string", length=30, nullable=true)
     */
    private $teDominioWindows;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dominio_dns", type="string", length=30, nullable=true)
     */
    private $teDominioDns;

    /**
     * @var string
     *
     * @ORM\Column(name="te_ip", type="string", length=15, nullable=true)
     */
    private $teIp;

    /**
     * @var string
     *
     * @ORM\Column(name="te_mascara", type="string", length=15, nullable=true)
     */
    private $teMascara;

    /**
     * @var string
     *
     * @ORM\Column(name="id_ip_rede", type="string", length=15, nullable=true)
     */
    private $idIpRede;

    /**
     * @var string
     *
     * @ORM\Column(name="te_nome_host", type="string", length=15, nullable=true)
     */
    private $teNomeHost;

    /**
     * @var string
     *
     * @ORM\Column(name="te_gateway", type="string", length=15, nullable=true)
     */
    private $teGateway;

    /**
     * @var string
     *
     * @ORM\Column(name="te_wins_primario", type="string", length=15, nullable=true)
     */
    private $teWinsPrimario;

    /**
     * @var string
     *
     * @ORM\Column(name="te_wins_secundario", type="string", length=15, nullable=true)
     */
    private $teWinsSecundario;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dns_primario", type="string", length=15, nullable=true)
     */
    private $teDnsPrimario;

    /**
     * @var string
     *
     * @ORM\Column(name="te_dns_secundario", type="string", length=15, nullable=true)
     */
    private $teDnsSecundario;

    /**
     * @var string
     *
     * @ORM\Column(name="te_serv_dhcp", type="string", length=15, nullable=true)
     */
    private $teServDhcp;

    /**
     * @var string
     *
     * @ORM\Column(name="te_workgroup", type="string", length=20, nullable=true)
     */
    private $teWorkgroup;



    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return HistoricoTcpIp
     */
    public function setTeNodeAddress($teNodeAddress)
    {
        $this->teNodeAddress = $teNodeAddress;
    
        return $this;
    }

    /**
     * Get teNodeAddress
     *
     * @return string 
     */
    public function getTeNodeAddress()
    {
        return $this->teNodeAddress;
    }

    /**
     * Set idSo
     *
     * @param integer $idSo
     * @return HistoricoTcpIp
     */
    public function setIdSo($idSo)
    {
        $this->idSo = $idSo;
    
        return $this;
    }

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
     * Set dtHrAlteracao
     *
     * @param \DateTime $dtHrAlteracao
     * @return HistoricoTcpIp
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
     * Set teNomeComputador
     *
     * @param string $teNomeComputador
     * @return HistoricoTcpIp
     */
    public function setTeNomeComputador($teNomeComputador)
    {
        $this->teNomeComputador = $teNomeComputador;
    
        return $this;
    }

    /**
     * Get teNomeComputador
     *
     * @return string 
     */
    public function getTeNomeComputador()
    {
        return $this->teNomeComputador;
    }

    /**
     * Set teDominioWindows
     *
     * @param string $teDominioWindows
     * @return HistoricoTcpIp
     */
    public function setTeDominioWindows($teDominioWindows)
    {
        $this->teDominioWindows = $teDominioWindows;
    
        return $this;
    }

    /**
     * Get teDominioWindows
     *
     * @return string 
     */
    public function getTeDominioWindows()
    {
        return $this->teDominioWindows;
    }

    /**
     * Set teDominioDns
     *
     * @param string $teDominioDns
     * @return HistoricoTcpIp
     */
    public function setTeDominioDns($teDominioDns)
    {
        $this->teDominioDns = $teDominioDns;
    
        return $this;
    }

    /**
     * Get teDominioDns
     *
     * @return string 
     */
    public function getTeDominioDns()
    {
        return $this->teDominioDns;
    }

    /**
     * Set teIp
     *
     * @param string $teIp
     * @return HistoricoTcpIp
     */
    public function setTeIp($teIp)
    {
        $this->teIp = $teIp;
    
        return $this;
    }

    /**
     * Get teIp
     *
     * @return string 
     */
    public function getTeIp()
    {
        return $this->teIp;
    }

    /**
     * Set teMascara
     *
     * @param string $teMascara
     * @return HistoricoTcpIp
     */
    public function setTeMascara($teMascara)
    {
        $this->teMascara = $teMascara;
    
        return $this;
    }

    /**
     * Get teMascara
     *
     * @return string 
     */
    public function getTeMascara()
    {
        return $this->teMascara;
    }

    /**
     * Set idIpRede
     *
     * @param string $idIpRede
     * @return HistoricoTcpIp
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
     * Set teNomeHost
     *
     * @param string $teNomeHost
     * @return HistoricoTcpIp
     */
    public function setTeNomeHost($teNomeHost)
    {
        $this->teNomeHost = $teNomeHost;
    
        return $this;
    }

    /**
     * Get teNomeHost
     *
     * @return string 
     */
    public function getTeNomeHost()
    {
        return $this->teNomeHost;
    }

    /**
     * Set teGateway
     *
     * @param string $teGateway
     * @return HistoricoTcpIp
     */
    public function setTeGateway($teGateway)
    {
        $this->teGateway = $teGateway;
    
        return $this;
    }

    /**
     * Get teGateway
     *
     * @return string 
     */
    public function getTeGateway()
    {
        return $this->teGateway;
    }

    /**
     * Set teWinsPrimario
     *
     * @param string $teWinsPrimario
     * @return HistoricoTcpIp
     */
    public function setTeWinsPrimario($teWinsPrimario)
    {
        $this->teWinsPrimario = $teWinsPrimario;
    
        return $this;
    }

    /**
     * Get teWinsPrimario
     *
     * @return string 
     */
    public function getTeWinsPrimario()
    {
        return $this->teWinsPrimario;
    }

    /**
     * Set teWinsSecundario
     *
     * @param string $teWinsSecundario
     * @return HistoricoTcpIp
     */
    public function setTeWinsSecundario($teWinsSecundario)
    {
        $this->teWinsSecundario = $teWinsSecundario;
    
        return $this;
    }

    /**
     * Get teWinsSecundario
     *
     * @return string 
     */
    public function getTeWinsSecundario()
    {
        return $this->teWinsSecundario;
    }

    /**
     * Set teDnsPrimario
     *
     * @param string $teDnsPrimario
     * @return HistoricoTcpIp
     */
    public function setTeDnsPrimario($teDnsPrimario)
    {
        $this->teDnsPrimario = $teDnsPrimario;
    
        return $this;
    }

    /**
     * Get teDnsPrimario
     *
     * @return string 
     */
    public function getTeDnsPrimario()
    {
        return $this->teDnsPrimario;
    }

    /**
     * Set teDnsSecundario
     *
     * @param string $teDnsSecundario
     * @return HistoricoTcpIp
     */
    public function setTeDnsSecundario($teDnsSecundario)
    {
        $this->teDnsSecundario = $teDnsSecundario;
    
        return $this;
    }

    /**
     * Get teDnsSecundario
     *
     * @return string 
     */
    public function getTeDnsSecundario()
    {
        return $this->teDnsSecundario;
    }

    /**
     * Set teServDhcp
     *
     * @param string $teServDhcp
     * @return HistoricoTcpIp
     */
    public function setTeServDhcp($teServDhcp)
    {
        $this->teServDhcp = $teServDhcp;
    
        return $this;
    }

    /**
     * Get teServDhcp
     *
     * @return string 
     */
    public function getTeServDhcp()
    {
        return $this->teServDhcp;
    }

    /**
     * Set teWorkgroup
     *
     * @param string $teWorkgroup
     * @return HistoricoTcpIp
     */
    public function setTeWorkgroup($teWorkgroup)
    {
        $this->teWorkgroup = $teWorkgroup;
    
        return $this;
    }

    /**
     * Get teWorkgroup
     *
     * @return string 
     */
    public function getTeWorkgroup()
    {
        return $this->teWorkgroup;
    }
}