<?php

namespace Cacic\MultiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sites
 */
class Sites
{
    /**
     * @var integer
     */
    private $idSite;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $subdomain;

    /**
     * @var string
     */
    private $subdir;

    /**
     * @var string
     */
    private $dbHost;

    /**
     * @var string
     */
    private $dbUser;

    /**
     * @var string
     */
    private $dbUrl;

    /**
     * @var string
     */
    private $dbPassword;

    /**
     * @var integer
     */
    private $dbPort;


    /**
     * Get idSite
     *
     * @return integer 
     */
    public function getIdSite()
    {
        return $this->idSite;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Sites
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set subdomain
     *
     * @param string $subdomain
     * @return Sites
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * Get subdomain
     *
     * @return string 
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Set subdir
     *
     * @param string $subdir
     * @return Sites
     */
    public function setSubdir($subdir)
    {
        $this->subdir = $subdir;

        return $this;
    }

    /**
     * Get subdir
     *
     * @return string 
     */
    public function getSubdir()
    {
        return $this->subdir;
    }

    /**
     * Set dbHost
     *
     * @param string $dbHost
     * @return Sites
     */
    public function setDbHost($dbHost)
    {
        $this->dbHost = $dbHost;

        return $this;
    }

    /**
     * Get dbHost
     *
     * @return string 
     */
    public function getDbHost()
    {
        return $this->dbHost;
    }

    /**
     * Set dbUser
     *
     * @param string $dbUser
     * @return Sites
     */
    public function setDbUser($dbUser)
    {
        $this->dbUser = $dbUser;

        return $this;
    }

    /**
     * Get dbUser
     *
     * @return string 
     */
    public function getDbUser()
    {
        return $this->dbUser;
    }

    /**
     * Set dbUrl
     *
     * @param string $dbUrl
     * @return Sites
     */
    public function setDbUrl($dbUrl)
    {
        $this->dbUrl = $dbUrl;

        return $this;
    }

    /**
     * Get dbUrl
     *
     * @return string 
     */
    public function getDbUrl()
    {
        return $this->dbUrl;
    }

    /**
     * Set dbPassword
     *
     * @param string $dbPassword
     * @return Sites
     */
    public function setDbPassword($dbPassword)
    {
        $this->dbPassword = $dbPassword;

        return $this;
    }

    /**
     * Get dbPassword
     *
     * @return string 
     */
    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    /**
     * Set dbPort
     *
     * @param integer $dbPort
     * @return Sites
     */
    public function setDbPort($dbPort)
    {
        $this->dbPort = $dbPort;

        return $this;
    }

    /**
     * Get dbPort
     *
     * @return integer 
     */
    public function getDbPort()
    {
        return $this->dbPort;
    }
}
