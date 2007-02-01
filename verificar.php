<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php';

//Separar o nome do arquivo da URL
//$separarNOME = explode("/", $_SERVER['SCRIPT_FILENAME']);
//$arrayReverso = array_reverse($separarNOME);
//$ArqATUAL=$arrayReverso[0];

//menu_esq.php e principal nao podem ser utilizados nesta funcao.

//if (($ArqATUAL <> "menu_esq.php") || ($ArqATUAL <> "principal.php"){
        session_start();
        if (!$_SESSION["id_usuario"]){
                echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/cacic2/include/cacic.css\">";
                echo mensagem('Você não está logado no Cacic');
                echo "<br><center><a target=\"_top\" href=/cacic2/> Clique aqui para logar!</center>";
                exit;
        }
//}

?>
