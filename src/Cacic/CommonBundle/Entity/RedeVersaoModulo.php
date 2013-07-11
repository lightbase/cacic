<?php

namespace Cacic\CommonBundle\Entity;

use Cacic\CommonBundle\CacicCommonBundle;
use Cacic\WSBundle\Helper;
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

    public $iniFile;


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
     *  Carrega valor do arquivo de configuração
     */

    public  function setIniFile()
    {
        $this->iniFile = 'Common/downloads/versions_and_hashes.ini';

        return $this;
    }

    public function getIniFile()
    {
        return $this->iniFile;
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
        $iniFile = $this->getIniFile();

        $itemArray = parse_ini_file($iniFile, TRUE);

        $intLoopSEL 		= 1;
        $intLoopVersionsIni = 0;
        $sessStrTripaItensEnviados = '';
        while ($intLoopVersionsIni >= 0)
        {
            $intLoopVersionsIni ++;
            $arrItemDefinitions = explode(',',$itemArray['Item_' . $intLoopVersionsIni]);
            if (($arrItemDefinitions[0] <> '') && ($arrItemDefinitions[1] <> 'S') && ($arrItemDefinitions[2] <> 'S'))
            {
                $pStrNmItem = getOnlyFileName(trim($arrItemDefinitions[0]));

                //$boolEqualVersions = ($arrVersoesEnviadas[$strItemName]  == $itemArray[$strItemName . '_VER'] );
                //$boolEqualHashs	   = ($arrHashsEnviados[$strItemName]    == $itemArray[$strItemName . '_HASH']);

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
                        Helper\OldCacicHelper::CACIC_PATH . Helper\OldCacicHelper::CACIC_PATH_RELATIVO_DOWNLOADS . ($arrDadosRede[$pStrNmItem . '_PATH']),
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


                    // Adicione o restante dos atributos
                    $redeVersaoModulo = new RedeVersaoModulo();
                    $redeVersaoModulo->setIdRede($pIntIdRede);
                    $redeVersaoModulo->setNmModulo($pStrNmItem);
                    $redeVersaoModulo->setTeVersaoModulo($pStrNmItem . '_VER');
                    $redeVersaoModulo->setDtAtualizacao(now());
                    $redeVersaoModulo->setCsTipoSo( $pStrNmItem,'.exe',false ? 'MS-Windows' : 'GNU/LINUX');
                    $redeVersaoModulo->setTeHash($pStrNmItem . '_HASH');


                    $em->persist($redeVersaoModulo);
                    $em->flush();


                }

                //echo $_GET['pIntIdRede'] . '_=_' . $_GET['pStrNmItem'] . '_=_' . $strResult;

                }  else {
                    $intLoopVersionsIni = -1;
                }

            $intLoopSEL++;
        }

    }


     public function getConfig($v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey){
         if ($this->getIniFile())
         {
             $arrVersionsAndHashes = parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');
             return array(
                'INSTALLCACIC.EXE_HASH' => Helper\OldCacicHelper::EnCrypt($arrVersionsAndHashes['installcacic.exe_HASH'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey,true),
                'MainProgramName' => Helper\OldCacicHelper::CACIC_MAIN_PROGRAM_NAME.'.exe',
                'LocalFolderName' => Helper\OldCacicHelper::CACIC_LOCAL_FOLDER_NAME
             );

         }
     }


}