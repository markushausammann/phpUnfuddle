<?php

namespace Unfuddle;

/**
 * Class Connection
 * @package Unfuddle
 */
class Connection
{
    private $domain;
    private $username;
    private $password;
    private $ssl;

	/**
	 * Connection constructor.
	 *
	 * @param      $domain
	 * @param      $username
	 * @param      $password
	 * @param bool $ssl
	 */
    public function __construct($domain, $username, $password, $ssl = true)
    {
        $this->domain = $domain;
        $this->username = $username;
        $this->password = $password;
        $this->ssl = $ssl;
    }

	/**
	 * @param $domain
	 */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

	/**
	 * @return mixed
	 */
    public function getDomain()
    {
        return $this->domain;
    }

	/**
	 * @param $password
	 */
    public function setPassword($password)
    {
        $this->password = $password;
    }

	/**
	 * @return mixed
	 */
    public function getPassword()
    {
        return $this->password;
    }

	/**
	 * @param $ssl
	 */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
    }

	/**
	 * @return bool
	 */
    public function getSsl()
    {
        return $this->ssl;
    }

	/**
	 * @param $username
	 */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
