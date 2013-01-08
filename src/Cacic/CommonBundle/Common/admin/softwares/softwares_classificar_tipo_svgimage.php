<?php
/**
 * @version $Id: softwares_classificar_tipo_svgimage.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Admin
 * @subpackage SoftwaresClassificar
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Gerador de imagem svg para tipos de software a ser classificado
 */
header('Content-Type: image/svg+xml');
  echo '<?php xml version="1.0" encoding="ISO-8859-1" standalone="no"?>';
?>
<svg xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink">
    <text x="00" y="25" font-size="12"
          transform="translate(40) rotate(-80 60 105)"
    ><?php echo $_GET['texto'];?></text>
</svg>

