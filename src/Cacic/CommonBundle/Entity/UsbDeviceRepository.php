<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 *
 * Repositório de métodos de consulta em DQL
 * @author lightbase
 *
 */
class UsbDeviceRepository extends EntityRepository
{

    public function paginar()
    {
        $_dql = "SELECT  u.idUsbDevice, u.nmUsbDevice, u.teObservacao, u.dtRegistro, v.nmUsbVendor, v.idUsbVendor
				FROM CacicCommonBundle:UsbDevice u
				JOIN u.idUsbVendor v
				GROUP BY u, v";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }

    /**
     *
     * Método de listagem dos usb device cadastrados.
     */
    public function listar()
    {
        $_dql = "SELECT u.idUsbDevice, u.nmUsbDevice, v.nmUsbVendor, v.idUsbVendor
				FROM CacicCommonBundle:UsbDevice u
				JOIN u.idUsbVendor v
				GROUP BY u.idUsbDevice";

        return $this->getEntityManager()->createQuery( $_dql )->getArrayResult();
    }
}