<?php
/**
 * phpval
 * 
 * Copyright (c) 2009, David Winterbottom <david.winterbottom@gmail.com>.
 * All rights reserved.
 * 
 * @package phpval
 * @author David Winterbottom <david.winterbottom@gmail.com>
 * @copyright 2009 David Winterbottom <david.winterbottom@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

require_once dirname(__FILE__).'/Protocols.php';
require_once dirname(__FILE__).'/Relative.php';

/**
 * Simple URL object.
 *
 * Note that the protocol and domain components are optional, it is perfectly valid
 * to use URLs that are relative to the document root (eg /my/page?q=barry).
 *
 * See http://www.ietf.org/rfc/rfc2396.txt for the appropriate RFC
 * 
 * @author David Winterbottom <david.winterbottom@gmail.com>
 * @copyright 2009 David Winterbottom <david.winterbottom@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class PHPVAL_Url_Absolute extends PHPVAL_Url_Relative
{
    const PROTOCOL_SEPARATOR = '://';
    
    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var string
     */
    protected $domain;
    
    /**
     * @var int
     */
    protected $port;
    
    /**
     * @var string
     */
    protected $username;
    
    /**
     * @var string
     */
    protected $password;
    
    /**
     * Construct using the most common URL components - use the setter methods to set
     * the less common port/user/password components of a URL.
     * 
     * @param string $protocol
     * @param string $domain
     * @param string $pathname
     * @param string $queryString
     * @param string $hash
     */
    public function __construct($protocol, $domain,
        $pathname=self::PATH_SEPARATOR, $queryString=null, $hash=null) 
    {
        $this->protocol = (string)$protocol;
        $this->domain = (string)$domain;
        $this->port = (int)$port;
        parent::__construct($pathname, $queryString, $hash);
    }
    
    /**
     * @param int $port
     * @return PHPVAL_Url_Absolute
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     * @return PHPVAL_Url_Absolute
     */
    public function setUsernameAndPassword($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        return $this;
    }
    
    // ============
    // MANIPULATION
    // ============

    /**
     * Sets the domain for this URL
     *
     * @param string $domain
     * @return url_Object
     */
    public function setProtocol($protocol=PHPVAL_Url_Protocols::HTTP)
    {
        $newUrl = clone $this;
        $newUrl->protocol = $protocol;
        if (PHPVAL_Url_Protocols::HTTPS == $protocol) {
            $newUrl->port = 443;
        }
        return $newUrl;
    }

    /**
     * Sets the domain for this URL
     *
     * @param string $domain
     * @return url_Object
     */
    public function setDomain($domain)
    {
        $newUrl = clone $this;
        $newUrl->domain = $domain;
        return $newUrl;
    }

    // =============
    // INTERROGATION
    // =============

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }
    
    /**
     * Returns the base URL
     * 
     * @return url_Object
     */
    public function getBaseUrl()
    {
        if (!$this->protocol || !$this->domain) return null;
        return new self($this->protocol, $this->domain);
    }
    
    /**
     * Returns the URL as a single string.
     *
     * If the domain and protocol are defined then the URL will be absolute (eg: http://www.egg.com).
     * Otherwise, the URL will be relative to the document root (eg: /my/path?with=query-params)
     * 
     * @return string
     */
    public function toString()
    {
        $url = $this->protocol.self::PROTOCOL_SEPARATOR;
        if ($this->username && $this->password) {
            $url .= sprintf("%s:%s", $this->username, $this->password);
        }
        $url .= $this->domain.parent::toString(); 
        return $url;
    }
}