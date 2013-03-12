<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class UsbDevicesRepository extends EntityRepository
{

    public function paginar( $page )
    {

    }

    /**
     *
     * Método de listagem dos usb device cadastrados.
     */
    public function listar()
    {
        $_dql = "SELECT u
				FROM CacicCommonBundle:UsbDevices u
				GROUP BY u.idUsbDevice";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}