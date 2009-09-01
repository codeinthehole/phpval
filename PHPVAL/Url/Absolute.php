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
    const PROTOCOL_HTTP = 'http';
    const PROTOCOL_HTTPS = 'https';
    const PROTOCOL_FTP = 'ftp';
    const PROTOCOL_GOPHER = 'gopher';
    const PROTOCOL_MAILTO = 'mailto';
    const PROTOCOL_TELNET = 'telnet';

    const PROTOCOL_SEPARATOR = '://';
    
    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var string
     */
    protected $host;
    
    /**
     * @var int
     */
    protected $port;
    
    /**
     * Protected constructor - use the factory methods to create
     */
    public function __construct($protocol, $domain, $port=80, $pathname=self::PATH_SEPARATOR, $queryString='', $hash='') 
    {
        $this->protocol = (string)$protocol;
        $this->domain = (string)$domain;
        $this->port = (int)$port;
        parent::__construct($pathname, $queryString, $hash);
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
    public function setProtocol($protocol=self::PROTOCOL_HTTP)
    {
        $newUrl = clone $this;
        $newUrl->protocol = $protocol;
        if (self::PROTOCOL_HTTPS == $protocol) {
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
     * Returns the protocol of the URL
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Returns the domain of the URL
     *
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
        $url = parent::toString();
        if (!empty($this->protocol) && !empty($this->domain)) {
            $url = $this->protocol.self::PROTOCOL_SEPARATOR.$this->domain.$url;
        }
        return $url;
    }
}