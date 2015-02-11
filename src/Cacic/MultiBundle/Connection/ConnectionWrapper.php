<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 08/01/15
 * Time: 17:21
 */

namespace Cacic\MultiBundle\Connection;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Events;
use Doctrine\DBAL\Event\ConnectionEventArgs;

/**
 * Class ConnectionWrapper
 * Example from http://stackoverflow.com/questions/6409167/symfony-2-multiple-and-dynamic-database-connection
 *
 * @package Cacic\MultiBundle\Connection
 */
class ConnectionWrapper extends Connection {

    const SESSION_ACTIVE_DYNAMIC_CONN = 'active_dynamic_conn';

    /**
     * @var Session
     */
    private $session;

    /**
     * @var bool
     */
    private $_isConnected = false;

    /**
     * @param Session $sess
     */
    public function setSession(Session $sess)
    {
        $this->session = $sess;
    }

    public function forceSwitch($dbName, $dbUser, $dbPassword)
    {
        if ($this->session->has(self::SESSION_ACTIVE_DYNAMIC_CONN)) {
            $current = $this->session->get(self::SESSION_ACTIVE_DYNAMIC_CONN);
            if ($current[0] === $dbName) {
                return;
            }
        }

        $this->session->set(self::SESSION_ACTIVE_DYNAMIC_CONN, [
            $dbName,
            $dbUser,
            $dbPassword
        ]);

        if ($this->isConnected()) {
            $this->close();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        if (! $this->session->has(self::SESSION_ACTIVE_DYNAMIC_CONN)) {
            throw new \InvalidArgumentException('You have to inject into valid context first');
        }
        if ($this->isConnected()) {
            return true;
        }

        $driverOptions = isset($params['driverOptions']) ? $params['driverOptions'] : array();

        $params = $this->getParams();
        $realParams = $this->session->get(self::SESSION_ACTIVE_DYNAMIC_CONN);
        $params['dbname'] = $realParams[0];
        $params['user'] = $realParams[1];
        $params['password'] = $realParams[2];

        $this->_conn = $this->_driver->connect($params, $params['user'], $params['password'], $driverOptions);

        if ($this->_eventManager->hasListeners(Events::postConnect)) {
            $eventArgs = new ConnectionEventArgs($this);
            $this->_eventManager->dispatchEvent(Events::postConnect, $eventArgs);
        }

        $this->_isConnected = true;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isConnected()
    {
        return $this->_isConnected;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if ($this->isConnected()) {
            parent::close();
            $this->_isConnected = false;
        }
    }
} 