<?php

namespace Unfuddle;

class Unfuddle_Connection
{
    private $domain;
    private $username;
    private $password;
    private $apiKey;
    private $ssl;

    public function __construct($domain, $username, $password, $apiKey, $ssl = true)
    {
        $this->domain = $domain;
        $this->username = $username;
        $this->password = $password;
        $this->apiKey = $apiKey;
        $this->ssl = $ssl;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
    }

    public function getSsl()
    {
        return $this->ssl;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
