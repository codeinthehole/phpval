<?php
/**
 * @package PHPVAL
 * @author David Winterbottom <david.winterbottom@gmail.com>
 * @version $Id$
 */

/**
 * Simple URL object.
 *
 * Note that the protocol and domain components are optional, it is perfectly valid
 * to use URLs that are relative to the document root (eg /my/page?q=barry).
 *
 * @package PHPVAL
 */
class Url
{
    const PROTOCOL_HTTP  = 'http';
    const PROTOCOL_HTTPS = 'https';

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
     * Protected constructor - use the factory methods to create
     */
    protected function __construct($protocol, $host, $port, $pathname, $queryString) 
    {
        $this->protocol = $protocol;
        $this->host = $host;
        $this->port = $port;
        $this->pathname = $pathname;
        $this->queryString = $queryString;
    }

    // ========
    // CREATION
    // ========

    /**
     * Create object using current requests data from the $_SERVER superglobal
     *
     * @return Url
     */
    public static function createFromCurrentRequest()
    {
        $url = new self;

        // Set protocol
        if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) {
            $url->setProtocol(self::PROTOCOL_HTTPS);
        } else {
            $url->setProtocol(self::PROTOCOL_HTTP);
        }
        if (isset($_SERVER['HTTP_HOST'])) {
            $url->setDomain($_SERVER['HTTP_HOST']);
        }

        $components = explode('?', $_SERVER['REQUEST_URI']);
        $url->setPathname($components[0]);

        // Set query string and params
        if (isset($components[1])) {
            $url->setQueryString($components[1]);
        }

        return $url;
    }

    /**
     * Returns the URL object from the referring URL
     * 
     * @return Url
     */
    public static function createFromReferrer()
    {
        return self::createFromAbsoluteUrl($_SERVER['HTTP_REFERER']);
    }

    /**
     * Create object with a full URL
     *
     * @param string $url
     * @return Url
     */
    public static function createFromAbsoluteUrl($urlString)
    {
        $urlString = trim($urlString);
        
        $protocol = null;
        $protocolComponents = explode('://', $urlString);
        if (count($protocolComponents) == 2) $protocol = $protocolComponents[0];
        
        $components = explode('/', $urlString);
        $host = $components[2];

        $port = null;
    
        $pathname = '/';
        $queryString = null;
        if (count($components) > 3) {
            $pathnameAndQueryString = '/'.implode('/', array_slice($components, 3));
            $pathComponents = explode('?', $pathnameAndQueryString);
            $pathname = $pathComponents[0];

            if (count($pathComponents) > 1) {
                $queryString = $pathComponents[1];
            }
        }
        return new self($protocol, $host, $port, $pathname, $queryString);
    }

    /**
     * Creates object using a relative URL
     *
     * The object returned will not have a domain or protocol
     *
     * @param $url
     * @return url_Object
     */
    public static function createFromRelativeUrl($urlString='/')
    {
        $urlString = (string)$urlString;
        $url = new self;
        if ($urlString{0} != '/') {
            $urlString = '/'.$urlString;
        }
        $pathComponents = explode('?', $urlString);
        $url->setPathname($pathComponents[0]);
        if (count($pathComponents) > 1) {
            $url->setQueryString($pathComponents[1]);
        }
        return $url;
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
        $this->domain = $domain;
        return $this;
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
    public function setPathname($pathname='/')
    {
        if ($pathname{0} != '/') {
            $pathname = '/'.$pathname;
        }
        $this->pathname = $pathname;
        return $this;
    }

    /**
     * Sets the query string for this URL
     *
     * @return string
     */
    public function setQueryString($queryString='')
    {
        // Remove starting question mark if it exists
        if ($queryString{1} == '?') {
            $queryString = substr($queryString, 1);
        }
        $this->queryString = $queryString;
        return $this;
    }
    
    /**
     * Sets the query params
     * 
     * @param array $params
     * @return url_Object
     */
    public function setQueryParams(array $params)
    {
        $pairs = array();
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $pairs[] = sprintf("%s=%s", urlencode($key), urlencode($value));
            } else {
                $pairs[] = urlencode($key);
            }
        }
        $this->queryString = implode('&', $pairs);
        return $this;
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
        $params = $this->getQueryParams();
        $params[$parameter] = $value;
        $this->setQueryParams($params);
        return $this;
    }
    
    /**
     * Removes all query parameters
     * 
     * @return url_Object
     */
    public function removeQueryParams()
    {
        $this->queryString = '';
        return $this;
    }
    
    /**
     * Removes a single query param
     * 
     * @param string $parameter
     */
    public function removeQueryParam($parameter)
    {
        $parameter = (string)$parameter;
        $params = $this->getQueryParams();
        if (array_key_exists($parameter, $params)) {
            unset($params[$parameter]);
            $this->setQueryParams($params);
        }
        return $this;
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
     * Returns the pathname
     *
     * @return string
     */
    public function getPathname()
    {
        return $this->pathname;
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
     * Returns the base URL
     * 
     * @return string|null
     */
    public function getBaseUrl()
    {
        if (!$this->domain) return null;
        $protocol = ($this->protocol) ? $this->protocol : self::PROTOCOL_HTTP;
        return sprintf("%s://%s", $protocol, $this->domain);
    }

    /**
     * Returns the URL relative to the document root
     * 
     * @return string
     */
    public function getDocumentRootUrl()
    {
        $url = $this->pathname;
        if ($this->queryString) {
            $url .= '?'.$this->queryString;
        }
        return $url;
    }

    /**
     * Returns the URL path segments
     * 
     * @return array
     */
    public function getPathSegments()
    {
        $cleanedPath = trim(preg_replace('@^/|/$@', '', $this->getPathname()));
        return (!empty($cleanedPath)) ? explode('/', $cleanedPath) : array();
    }
    
    /**
     * Returns the URL segment at a specific position
     * 
     * @param int $segmentIndex
     * @return string
     */
    public function getPathSegment($segmentIndex=0)
    {
        $segmentNum = (int)$segmentIndex;
        $segments = $this->getPathSegments();
        if (count($segments) < $segmentNum+1) {
            return false;
        }
        return $segments[$segmentNum];
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
        return false;
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
            $url = $this->protocol.'://'.$this->domain.$url;
        }
        if ($this->queryString) {
            $url .= '?'.$this->queryString;
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