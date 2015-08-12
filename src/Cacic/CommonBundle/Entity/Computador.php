<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Computador
 */
class Computador
{
    /**
     * @var integer
     */
    private $idComputador;

    /**
     * @var string
     */
    private $nmComputador;

    /**
     * @var string
     */
    private $teNodeAddress;

    /**
     * @var string
     */
    private $teIpComputador;

    /**
     * @var \DateTime
     */
    private $dtHrInclusao;

    /**
     * @var \DateTime
     */
    private $dtHrExclusao;

    /**
     * @var \DateTime
     */
    private $dtHrUltAcesso;

    /**
     * @var string
     */
    private $teVersaoCacic;

    /**
     * @var string
     */
    private $teVersaoGercols;

    /**
     * @var string
     */
    private $tePalavraChave;

    /**
     * @var string
     */
    private $forcaColeta;

    /**
     * @var \DateTime
     */
    private $dtHrColetaForcadaEstacao;

    /**
     * @var string
     */
    private $teNomesCurtosModulos;

    /**
     * @var integer
     */
    private $idConta;

    /**
     * @var string
     */
    private $teDebugging;

    /**
     * @var string
     */
    private $teUltimoLogin;

    /**
     * @var string
     */
    private $dtDebug;

    /**
     * @var string
     */
    private $isNotebook;

    /**
     * @var string
     */
    private $forcaPatrimonio;

    /**
     * @var boolean
     */
    private $ativo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $softwares;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $hardwares;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $software_coletado;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $erros_agente;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $notifications;

    /**
     * @var \Cacic\CommonBundle\Entity\Usuario
     */
    private $idUsuarioExclusao;

    /**
     * @var \Cacic\CommonBundle\Entity\So
     */
    private $idSo;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $idRede;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->softwares = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hardwares = new \Doctrine\Common\Collections\ArrayCollection();
        $this->software_coletado = new \Doctrine\Common\Collections\ArrayCollection();
        $this->erros_agente = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idComputador
     *
     * @return integer 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }

    /**
     * Set nmComputador
     *
     * @param string $nmComputador
     * @return Computador
     */
    public function setNmComputador($nmComputador)
    {
        $this->nmComputador = $nmComputador;

        return $this;
    }

    /**
     * Get nmComputador
     *
     * @return string 
     */
    public function getNmComputador()
    {
        return $this->nmComputador;
    }

    /**
     * Set teNodeAddress
     *
     * @param string $teNodeAddress
     * @return Computador
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
     * Set teIpComputador
     *
     * @param string $teIpComputador
     * @return Computador
     */
    public function setTeIpComputador($teIpComputador)
    {
        $this->teIpComputador = $teIpComputador;

        return $this;
    }

    /**
     * Get teIpComputador
     *
     * @return string 
     */
    public function getTeIpComputador()
    {
        return $this->teIpComputador;
    }

    /**
     * Set dtHrInclusao
     *
     * @param \DateTime $dtHrInclusao
     * @return Computador
     */
    public function setDtHrInclusao($dtHrInclusao)
    {
        $this->dtHrInclusao = $dtHrInclusao;

        return $this;
    }

    /**
     * Get dtHrInclusao
     *
     * @return \DateTime 
     */
    public function getDtHrInclusao()
    {
        return $this->dtHrInclusao;
    }

    /**
     * Set dtHrExclusao
     *
     * @param \DateTime $dtHrExclusao
     * @return Computador
     */
    public function setDtHrExclusao($dtHrExclusao)
    {
        $this->dtHrExclusao = $dtHrExclusao;

        return $this;
    }

    /**
     * Get dtHrExclusao
     *
     * @return \DateTime 
     */
    public function getDtHrExclusao()
    {
        return $this->dtHrExclusao;
    }

    /**
     * Set dtHrUltAcesso
     *
     * @param \DateTime $dtHrUltAcesso
     * @return Computador
     */
    public function setDtHrUltAcesso($dtHrUltAcesso)
    {
        $this->dtHrUltAcesso = $dtHrUltAcesso;

        return $this;
    }

    /**
     * Get dtHrUltAcesso
     *
     * @return \DateTime 
     */
    public function getDtHrUltAcesso()
    {
        return $this->dtHrUltAcesso;
    }

    /**
     * Set teVersaoCacic
     *
     * @param string $teVersaoCacic
     * @return Computador
     */
    public function setTeVersaoCacic($teVersaoCacic)
    {
        $this->teVersaoCacic = $teVersaoCacic;

        return $this;
    }

    /**
     * Get teVersaoCacic
     *
     * @return string 
     */
    public function getTeVersaoCacic()
    {
        return $this->teVersaoCacic;
    }

    /**
     * Set teVersaoGercols
     *
     * @param string $teVersaoGercols
     * @return Computador
     */
    public function setTeVersaoGercols($teVersaoGercols)
    {
        $this->teVersaoGercols = $teVersaoGercols;

        return $this;
    }

    /**
     * Get teVersaoGercols
     *
     * @return string 
     */
    public function getTeVersaoGercols()
    {
        return $this->teVersaoGercols;
    }

    /**
     * Set tePalavraChave
     *
     * @param string $tePalavraChave
     * @return Computador
     */
    public function setTePalavraChave($tePalavraChave)
    {
        $this->tePalavraChave = $tePalavraChave;

        return $this;
    }

    /**
     * Get tePalavraChave
     *
     * @return string 
     */
    public function getTePalavraChave()
    {
        return $this->tePalavraChave;
    }

    /**
     * Set forcaColeta
     *
     * @param string $forcaColeta
     * @return Computador
     */
    public function setForcaColeta($forcaColeta)
    {
        $this->forcaColeta = $forcaColeta;

        return $this;
    }

    /**
     * Get forcaColeta
     *
     * @return string 
     */
    public function getForcaColeta()
    {
        return $this->forcaColeta;
    }

    /**
     * Set dtHrColetaForcadaEstacao
     *
     * @param \DateTime $dtHrColetaForcadaEstacao
     * @return Computador
     */
    public function setDtHrColetaForcadaEstacao($dtHrColetaForcadaEstacao)
    {
        $this->dtHrColetaForcadaEstacao = $dtHrColetaForcadaEstacao;

        return $this;
    }

    /**
     * Get dtHrColetaForcadaEstacao
     *
     * @return \DateTime 
     */
    public function getDtHrColetaForcadaEstacao()
    {
        return $this->dtHrColetaForcadaEstacao;
    }

    /**
     * Set teNomesCurtosModulos
     *
     * @param string $teNomesCurtosModulos
     * @return Computador
     */
    public function setTeNomesCurtosModulos($teNomesCurtosModulos)
    {
        $this->teNomesCurtosModulos = $teNomesCurtosModulos;

        return $this;
    }

    /**
     * Get teNomesCurtosModulos
     *
     * @return string 
     */
    public function getTeNomesCurtosModulos()
    {
        return $this->teNomesCurtosModulos;
    }

    /**
     * Set idConta
     *
     * @param integer $idConta
     * @return Computador
     */
    public function setIdConta($idConta)
    {
        $this->idConta = $idConta;

        return $this;
    }

    /**
     * Get idConta
     *
     * @return integer 
     */
    public function getIdConta()
    {
        return $this->idConta;
    }

    /**
     * Set teDebugging
     *
     * @param string $teDebugging
     * @return Computador
     */
    public function setTeDebugging($teDebugging)
    {
        $this->teDebugging = $teDebugging;

        return $this;
    }

    /**
     * Get teDebugging
     *
     * @return string 
     */
    public function getTeDebugging()
    {
        return $this->teDebugging;
    }

    /**
     * Set teUltimoLogin
     *
     * @param string $teUltimoLogin
     * @return Computador
     */
    public function setTeUltimoLogin($teUltimoLogin)
    {
        $this->teUltimoLogin = $teUltimoLogin;

        return $this;
    }

    /**
     * Get teUltimoLogin
     *
     * @return string 
     */
    public function getTeUltimoLogin()
    {
        return $this->teUltimoLogin;
    }

    /**
     * Set dtDebug
     *
     * @param string $dtDebug
     * @return Computador
     */
    public function setDtDebug($dtDebug)
    {
        $this->dtDebug = $dtDebug;

        return $this;
    }

    /**
     * Get dtDebug
     *
     * @return string 
     */
    public function getDtDebug()
    {
        return $this->dtDebug;
    }

    /**
     * Set isNotebook
     *
     * @param string $isNotebook
     * @return Computador
     */
    public function setIsNotebook($isNotebook)
    {
        $this->isNotebook = $isNotebook;

        return $this;
    }

    /**
     * Get isNotebook
     *
     * @return string 
     */
    public function getIsNotebook()
    {
        return $this->isNotebook;
    }

    /**
     * Set forcaPatrimonio
     *
     * @param string $forcaPatrimonio
     * @return Computador
     */
    public function setForcaPatrimonio($forcaPatrimonio)
    {
        $this->forcaPatrimonio = $forcaPatrimonio;

        return $this;
    }

    /**
     * Get forcaPatrimonio
     *
     * @return string 
     */
    public function getForcaPatrimonio()
    {
        return $this->forcaPatrimonio;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     * @return Computador
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return boolean 
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Add softwares
     *
     * @param \Cacic\CommonBundle\Entity\SoftwareEstacao $softwares
     * @return Computador
     */
    public function addSoftware(\Cacic\CommonBundle\Entity\SoftwareEstacao $softwares)
    {
        $this->softwares[] = $softwares;

        return $this;
    }

    /**
     * Remove softwares
     *
     * @param \Cacic\CommonBundle\Entity\SoftwareEstacao $softwares
     */
    public function removeSoftware(\Cacic\CommonBundle\Entity\SoftwareEstacao $softwares)
    {
        $this->softwares->removeElement($softwares);
    }

    /**
     * Get softwares
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSoftwares()
    {
        return $this->softwares;
    }

    /**
     * Add hardwares
     *
     * @param \Cacic\CommonBundle\Entity\ComputadorClasseColeta $hardwares
     * @return Computador
     */
    public function addHardware(\Cacic\CommonBundle\Entity\ComputadorClasseColeta $hardwares)
    {
        $this->hardwares[] = $hardwares;

        return $this;
    }

    /**
     * Remove hardwares
     *
     * @param \Cacic\CommonBundle\Entity\ComputadorClasseColeta $hardwares
     */
    public function removeHardware(\Cacic\CommonBundle\Entity\ComputadorClasseColeta $hardwares)
    {
        $this->hardwares->removeElement($hardwares);
    }

    /**
     * Get hardwares
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHardwares()
    {
        return $this->hardwares;
    }

    /**
     * Add software_coletado
     *
     * @param \Cacic\CommonBundle\Entity\PropriedadeSoftware $softwareColetado
     * @return Computador
     */
    public function addSoftwareColetado(\Cacic\CommonBundle\Entity\PropriedadeSoftware $softwareColetado)
    {
        $this->software_coletado[] = $softwareColetado;

        return $this;
    }

    /**
     * Remove software_coletado
     *
     * @param \Cacic\CommonBundle\Entity\PropriedadeSoftware $softwareColetado
     */
    public function removeSoftwareColetado(\Cacic\CommonBundle\Entity\PropriedadeSoftware $softwareColetado)
    {
        $this->software_coletado->removeElement($softwareColetado);
    }

    /**
     * Get software_coletado
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSoftwareColetado()
    {
        return $this->software_coletado;
    }

    /**
     * Add erros_agente
     *
     * @param \Cacic\CommonBundle\Entity\ErrosAgente $errosAgente
     * @return Computador
     */
    public function addErrosAgente(\Cacic\CommonBundle\Entity\ErrosAgente $errosAgente)
    {
        $this->erros_agente[] = $errosAgente;

        return $this;
    }

    /**
     * Remove erros_agente
     *
     * @param \Cacic\CommonBundle\Entity\ErrosAgente $errosAgente
     */
    public function removeErrosAgente(\Cacic\CommonBundle\Entity\ErrosAgente $errosAgente)
    {
        $this->erros_agente->removeElement($errosAgente);
    }

    /**
     * Get erros_agente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getErrosAgente()
    {
        return $this->erros_agente;
    }

    /**
     * Add notifications
     *
     * @param \Cacic\CommonBundle\Entity\Notifications $notifications
     * @return Computador
     */
    public function addNotification(\Cacic\CommonBundle\Entity\Notifications $notifications)
    {
        $this->notifications[] = $notifications;

        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \Cacic\CommonBundle\Entity\Notifications $notifications
     */
    public function removeNotification(\Cacic\CommonBundle\Entity\Notifications $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set idUsuarioExclusao
     *
     * @param \Cacic\CommonBundle\Entity\Usuario $idUsuarioExclusao
     * @return Computador
     */
    public function setIdUsuarioExclusao(\Cacic\CommonBundle\Entity\Usuario $idUsuarioExclusao = null)
    {
        $this->idUsuarioExclusao = $idUsuarioExclusao;

        return $this;
    }

    /**
     * Get idUsuarioExclusao
     *
     * @return \Cacic\CommonBundle\Entity\Usuario 
     */
    public function getIdUsuarioExclusao()
    {
        return $this->idUsuarioExclusao;
    }

    /**
     * Set idSo
     *
     * @param \Cacic\CommonBundle\Entity\So $idSo
     * @return Computador
     */
    public function setIdSo(\Cacic\CommonBundle\Entity\So $idSo = null)
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

    /**
     * Set idRede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $idRede
     * @return Computador
     */
    public function setIdRede(\Cacic\CommonBundle\Entity\Rede $idRede = null)
    {
        $this->idRede = $idRede;

        return $this;
    }

    /**
     * Get idRede
     *
     * @return \Cacic\CommonBundle\Entity\Rede 
     */
    public function getIdRede()
    {
        return $this->idRede;
    }

    /**
     * Cria notificação para o computador
     *
     * @param $acao
     * @param $object
     * @param $subject
     * @param $body
     * @param $from
     * @param null $to
     * @return Notifications
     */
    public function createNotification($acao, $object, $subject, $body, $from, $to = null) {
        if (empty($to)) {
            $to = $this->getIdRede()->getTeEmailContato1();
            if (empty($responsavel)) {
                $to = $this->getIdRede()->getTeEmailContato2();
                if (empty($to)) {
                    // Nao foi possivel encontrar um responsavel pela rede.
                    // Manda para o usuario do sistema
                    $user = get_current_user();
                    $to = $user . "@localhost";
                }
            }
        }

        $notification = new Notifications();
        $notification->setNotificationAcao($acao);
        $notification->setObject($object);
        $notification->setToAddr($to);
        $notification->setFromAddr($from);
        $notification->setReadDate(null);
        $notification->setSubject($subject);
        $notification->setBody($body);
        $notification->setIdComputador($this);
        $notification->setCreationDate(new \DateTime());

        return $notification;
    }
}
