<?php
/**
 * PHPVAL - Value objects for PHP applications
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
 * Relative URL object.
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
class PHPVAL_Url_Relative
{
    const PATH_SEPARATOR = '/';

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
     * @param string $pathname
     * @param string $queryString
     * @param string $hash
     */
    public function __construct($pathname=self::PATH_SEPARATOR, $queryString=null, $hash=null) 
    {
        $this->pathname = (string)$pathname;
        $this->queryString = (string)$queryString;
        $this->hash = $hash;
    }

    // ============
    // MANIPULATION
    // ============

    /**
     * Sets the pathname for this URL
     *
     * Note that pathnames must start with a slash. This means that the root of a site is
     * always '/'.
     *
     * @param string $pathname
     * @return PHPVAL_Url_Relative
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
     * @param array $params
     * @return PHPVAL_Url_Relative
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
     * @param string $parameter
     * @param string $value
     * @return PHPVAL_Url_Relative
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
     * @return PHPVAL_Url_Relative
     */
    public function removeQueryParams()
    {
        $newUrl = clone $this;
        $newUrl->queryString = '';
        return $newUrl;
    }
    
    /**
     * @param string $parameter
     * @return PHPVAL_Url_Relative
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
     * @return string
     */
    public function getPathname()
    {
        return $this->pathname;
    }

    /**
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
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
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