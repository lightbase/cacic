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

    public function paginar( \Knp\Component\Pager\Paginator $paginator, $page = 1 )
    {
        $_dql = "SELECT  u.idUsbDevice, u.nmUsbDevice, u.teObservacao, u.dtRegistro, v.nmUsbVendor, v.idUsbVendor
				FROM CacicCommonBundle:UsbDevice u
				JOIN u.idUsbVendor v
				GROUP BY u, v";

        return $paginator->paginate(
            $this->getEntityManager()->createQuery( $_dql )->getArrayResult(),
            $page,
            10
        );
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