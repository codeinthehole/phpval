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
class PHPVAL_Url
{
    const PROTOCOL_HTTP = 'http';
    const PROTOCOL_HTTPS = 'https';
    const PROTOCOL_FTP = 'ftp';
    const PROTOCOL_GOPHER = 'gopher';
    const PROTOCOL_MAILTO = 'mailto';
    const PROTOCOL_TELNET = 'telnet';

    const PATH_SEPARATOR = '/';
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
     * @var string
     */
    protected $username;
    
    /**
     * @var string
     */
    protected $password;
    
    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $pathname;

    /**
     * Query string (not including question mark)
     *
     * @var string
     */
    protected $queryString;

    /**
     * @var string
     */
    protected $hash;
    
    /**
     * Protected constructor - use the factory methods to create
     */
    public function __construct($protocol, $domain, $port=80, $pathname=self::PATH_SEPARATOR, $queryString='', $hash='') 
    {
        $this->protocol = (string)$protocol;
        $this->domain = (string)$domain;
        $this->port = (int)$port;
        $this->pathname = (string)$pathname;
        $this->queryString = (string)$queryString;
        $this->hash = $hash;
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

    /**
     * Sets the pathname for this URL
     *
     * Note that pathnames must start with a slash. This means that the root of a site is
     * always '/'.
     *
     * @param string $pathname
     * @return url_Object
     */
    public function setPathname($pathname=self::PATH_SEPARATOR)
    {
        $newUrl = clone $this;
        $pathname = trim($pathname);
        if (0 == strlen($pathname)) $pathname = self::PATH_SEPARATOR;
        if ($pathname{0} != self::PATH_SEPARATOR) {
            $pathname = self::PATH_SEPARATOR.$pathname;
        }
        $newUrl->pathname = $pathname;
        return $newUrl;
    }
    
    /**
     * Sets the query params
     * 
     * @param array $params
     * @return url_Object
     */
    public function setQueryParams(array $params)
    {
        $newUrl = clone $this;
        $pairs = array();
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $pairs[] = sprintf("%s=%s", urlencode($key), urlencode($value));
            } else {
                $pairs[] = urlencode($key);
            }
        }
        $newUrl->queryString = implode('&', $pairs);
        return $newUrl;
    }
    
    /**
     * Adds query params
     * 
     * @param string $parameter
     * @param string $value
     * @return url_Object
     */
    public function setQueryParam($parameter, $value='')
    {
        $newUrl = clone $this;
        $params = $this->getQueryParams();
        $params[$parameter] = $value;
        $newUrl->setQueryParams($params);
        return $newUrl;
    }
    
    /**
     * Removes all query parameters
     * 
     * @return url_Object
     */
    public function removeQueryParams()
    {
        $newUrl = clone $this;
        $newUrl->queryString = '';
        return $newUrl;
    }
    
    /**
     * Removes a single query param
     * 
     * @param string $parameter
     */
    public function removeQueryParam($parameter)
    {
        $newUrl = clone $this;
        $parameter = (string)$parameter;
        $params = $this->getQueryParams();
        if (array_key_exists($parameter, $params)) {
            unset($params[$parameter]);
            $newUrl->setQueryParams($params);
        }
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
     * Returns the pathname
     *
     * @return string
     */
    public function getPathname()
    {
        return $this->pathname;
    }

    /**
     * Returns the URL path segments
     * 
     * @return array
     */
    public function getPathSegments()
    {
        $regExp = sprintf('@^%s|%s$@', self::PATH_SEPARATOR, self::PATH_SEPARATOR);
        $cleanedPath = trim(preg_replace($regExp, '', $this->getPathname()));
        return (!empty($cleanedPath)) ? explode(self::PATH_SEPARATOR, $cleanedPath) : array();
    }
    
    /**
     * Returns the URL segment at a specific position
     * 
     * @param int $segmentIndex
     * @return string|null
     */
    public function getPathSegment($segmentIndex=0)
    {
        $segmentNum = (int)$segmentIndex;
        $segments = $this->getPathSegments();
        if (count($segments) < $segmentNum+1) {
            return null;
        }
        return $segments[$segmentNum];
    }
    
    /**
     * Returns the query string
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Returns all the query parameters as a hash
     * 
     * @return array
     */
    public function getQueryParams()
    {
        $pairs = explode('&', $this->queryString);
        $params = array();
        foreach ($pairs as $pair) {
            if (empty($pair)) continue;
            $keyValue = explode('=', $pair);
            if (count($keyValue) == 1) {
                $params[$pair] = '';
            } elseif (count($keyValue) >= 2) {
                $params[$keyValue[0]] = implode('=', array_slice($keyValue, 1));
            }
        }
        return $params;
    }
    
    /**
     * Returns the value of a single query parameters
     * 
     * @param string $parameter
     * @return string|false
     */
    public function getQueryParam($parameter)
    {
        $params = $this->getQueryParams();
        if (array_key_exists($parameter, $params)) {
            $value = $params[$parameter];
            return empty($value) ? true : $value;
        }
        return null;
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
        $url = $this->pathname;
        if (!empty($this->protocol) && !empty($this->domain)) {
            $url = $this->protocol.self::PROTOCOL_SEPARATOR.$this->domain.$url;
        }
        if ($this->queryString) {
            $url .= '?'.$this->queryString;
        }
        if ($this->hash) {
            $url .= '#'.$this->hash;
        }
        return $url;
    }
    
    /**
     * Casts to string and returns the full URL
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}