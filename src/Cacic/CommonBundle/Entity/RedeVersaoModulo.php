<?php

namespace Cacic\CommonBundle\Entity;

use Cacic\CommonBundle\CacicCommonBundle;
use Doctrine\ORM\Mapping as ORM;

/**
 * RedeVersaoModulo
 */
class RedeVersaoModulo
{
    /**
     * @var integer
     */
    private $idRedeVersaoModulo;

    /**
     * @var string
     */
    private $nmModulo;

    /**
     * @var string
     */
    private $teVersaoModulo;

    /**
     * @var \DateTime
     */
    private $dtAtualizacao;

    /**
     * @var string
     */
    private $csTipoSo;

    /**
     * @var string
     */
    private $teHash;

    /**
     * @var \Cacic\CommonBundle\Entity\Rede
     */
    private $idRede;


    /**
     * Get idRedeVersaoModulo
     *
     * @return integer 
     */
    public function getIdRedeVersaoModulo()
    {
        return $this->idRedeVersaoModulo;
    }

    /**
     * Set nmModulo
     *
     * @param string $nmModulo
     * @return RedeVersaoModulo
     */
    public function setNmModulo($nmModulo)
    {
        $this->nmModulo = $nmModulo;
    
        return $this;
    }

    /**
     * Get nmModulo
     *
     * @return string 
     */
    public function getNmModulo()
    {
        return $this->nmModulo;
    }

    /**
     * Set teVersaoModulo
     *
     * @param string $teVersaoModulo
     * @return RedeVersaoModulo
     */
    public function setTeVersaoModulo($teVersaoModulo)
    {
        $this->teVersaoModulo = $teVersaoModulo;
    
        return $this;
    }

    /**
     * Get teVersaoModulo
     *
     * @return string 
     */
    public function getTeVersaoModulo()
    {
        return $this->teVersaoModulo;
    }

    /**
     * Set dtAtualizacao
     *
     * @param \DateTime $dtAtualizacao
     * @return RedeVersaoModulo
     */
    public function setDtAtualizacao($dtAtualizacao)
    {
        $this->dtAtualizacao = $dtAtualizacao;
    
        return $this;
    }

    /**
     * Get dtAtualizacao
     *
     * @return \DateTime 
     */
    public function getDtAtualizacao()
    {
        return $this->dtAtualizacao;
    }

    /**
     * Set csTipoSo
     *
     * @param string $csTipoSo
     * @return RedeVersaoModulo
     */
    public function setCsTipoSo($csTipoSo)
    {
        $this->csTipoSo = $csTipoSo;
    
        return $this;
    }

    /**
     * Get csTipoSo
     *
     * @return string 
     */
    public function getCsTipoSo()
    {
        return $this->csTipoSo;
    }

    /**
     * Set teHash
     *
     * @param string $teHash
     * @return RedeVersaoModulo
     */
    public function setTeHash($teHash)
    {
        $this->teHash = $teHash;
    
        return $this;
    }

    /**
     * Get teHash
     *
     * @return string 
     */
    public function getTeHash()
    {
        return $this->teHash;
    }

    /**
     * Set idRede
     *
     * @param \Cacic\CommonBundle\Entity\Rede $idRede
     * @return RedeVersaoModulo
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
   * --------------------------------------------------------------------------------------
   * Função usada para fazer updates de subredes...
   *A variável p_origem poderá conter "Agente" ou "Pagina" para o tratamento de variáveis $_SESSION
   *--------------------------------------------------------------------------------------
   */
    public function updateSubredes()
    {
        $pIntIdRede = $this->getIdRede();
        $iniFile = 'Common/downloads/versions_and_hashes.ini';

        $itemArray = parse_ini_file($iniFile, TRUE);

        $intLoopSEL 		= 1;
        $intLoopVersionsIni = 0;
        $sessStrTripaItensEnviados = '';
        while ($intLoopVersionsIni >= 0)
        {
            $intLoopVersionsIni ++;
            $arrItemDefinitions = explode(',',$itemArray['Item_' . $intLoopVersionsIni]);
            if (($arrItemDefinitions[0] <> '') && ($arrItemDefinitions[1] <> 'S' && ($arrItemDefinitions[2] <> 'S'))
            {
                $pStrNmItem = getOnlyFileName(trim($arrItemDefinitions[0]));

                $boolEqualVersions = ($arrVersoesEnviadas[$strItemName]  == $itemArray[$strItemName . '_VER'] );
                $boolEqualHashs	   = ($arrHashsEnviados[$strItemName]    == $itemArray[$strItemName . '_HASH']);

                $strSendProcess   = 'Nao Enviado!';
                $strProcessStatus = '';

                $em = $this->getDoctrine()->getManager();

                // Trocar esse array por um SELECT no Doctrine que retorna os dados das redes num array
                $arrDadosRede = array( 'rede' => $em->getRepository( 'CacicCommonBundle:Rede' )->listar() );

                // Caso o servidor de updates ainda não tenha sido trabalhado...
                if(!(stripos2($sessStrTripaItensEnviados,$arrDadosRede[0]['te_serv_updates'].'_'.$arrDadosRede[0]['te_path_serv_updates'].'_'.$_GET['pStrNmItem'].'_',false)))
                {
                    $sessStrTripaItensEnviados .= $arrDadosRede[0]['te_serv_updates'].'_'.$arrDadosRede[0]['te_path_serv_updates'].'_'.$_GET['pStrNmItem'] . '_';
                    require_once('../../include/ftp_check_and_send.php');

                    $strResult = checkAndSend($pStrNmItem,
                        CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . ($_SESSION['sessArrVersionsIni'][$_GET['pStrNmItem'] . '_PATH']),
                        $arrDadosRede[0]['te_serv_updates'],
                        $arrDadosRede[0]['te_path_serv_updates'],
                        $arrDadosRede[0]['nm_usuario_login_serv_updates_gerente'],
                        $arrDadosRede[0]['te_senha_login_serv_updates_gerente'],
                        $arrDadosRede[0]['nu_porta_serv_updates']);
                }
                else
                    $strResult = 'Ja Enviado ao Servidor!_=_Ok!_=_Resended';

                $arrResult = explode('_=_',$strResult);
                if ($arrResult[1] == 'Ok!')
                {
                    // Consertar CRUD no Symfony
                    $rede = $em->getRepository('CacicCommonBundle:Rede')->findBy(array(
                        'idRede' => $pIntIdRede,
                        'nmModulo' => $pStrNmItem
                    ));

                    $redeVersaoModulo = $em->getRepository('CacicCommonBundle:RedeVersaoModulo')->findBy(
                      array(
                          'idRede' => $pIntIdRede
                      )
                    );

                    $em->remove($redeVersaoModulo);
                    $em->flush();

                    //$cadastroRedeVersaoModulo = array( $em->getRepository( 'CacicCommonBundle:RedeVersaoModulo' ));

                    $redeVersaoModulo = new RedeVersaoModulo();
                    $redeVersaoModulo->setIdRede($pIntIdRede);
                    $redeVersaoModulo->setCsTipoSo();

                    // Adicione o restante dos atributos


                    $em->persist($cadastroRedeVersaoModulo);
                    $em->flush();





                    $queryINS  = 'INSERT INTO redes_versoes_modulos (id_rede,
														 nm_modulo,
														 te_versao_modulo,
														 dt_atualizacao,
														 cs_tipo_so,
														 te_hash) VALUES ('  .
                        $_GET['pIntIdRede'] .  ',
													"' . $_GET['pStrNmItem'] . '",
													"' . $_SESSION['sessArrVersionsIni'][$_GET['pStrNmItem'] . '_VER'] . '",
														 now(),
													"' . (stripos2($_GET['pStrNmItem'],'.exe',false) ? 'MS-Windows' : 'GNU/LINUX') . '",
													"' . $_SESSION['sessArrVersionsIni'][$_GET['pStrNmItem'] . '_HASH'] . '")';
                    $resultINS = mysql_query($queryINS) or die($oTranslator->_('Falha inserindo item em (%1) ou sua sessao expirou!',array('redes_versoes_modulos')));
                }

                //echo $_GET['pIntIdRede'] . '_=_' . $_GET['pStrNmItem'] . '_=_' . $strResult;
            }

                $intLoopSEL++;
            }
            else
                $intLoopVersionsIni = -1;
        }











    }



}