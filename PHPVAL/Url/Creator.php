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

require_once dirname(__FILE__).'/Absolute.php';

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
class PHPVAL_Url_Creator
{
    /**
     * @var array
     */
    private $urlComponents;
    
    private function getProtocolFromGlobals()
    {
        if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])) {
            return PHPVAL_Url_Protocols::HTTPS;
        } else {
            return PHPVAL_Url_Protocols::HTTP;
        }
    }
    
    private function getDomainFromGlobals()
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            throw new PHPVAL_Url_Exception("No HTTP_HOST header found to retrieve domain from");
        }
        return $_SERVER['HTTP_HOST'];
    }
    
    private function getPathnameFromGlobals()
    {
        if (!isset($_SERVER['REQUEST_URI'])) return '';
        $components = explode('?', $_SERVER['REQUEST_URI']);
        return $components[0];
    }
    
    private function getQueryStringFromGlobals()
    {
        if (!isset($_SERVER['REQUEST_URI'])) return '';
        $components = explode('?', $_SERVER['REQUEST_URI']);
        return (isset($components[1])) ? $components[1] : '';
    }
    
    private function getUrlComponent($key)
    {
        return isset($this->urlComponents[$key]) ? $this->urlComponents[$key] : null; 
    }
    
    private function getProtocolFromString()
    {
        return $this->getUrlComponent('scheme');
    }
    
    private function getDomainFromString()
    {
        return $this->getUrlComponent('host');
    }
    
    private function getPathnameFromString()
    {
        return $this->getUrlComponent('path');
    }
    
    private function getQueryStringFromString()
    {
        return $this->getUrlComponent('query');
    }
    
    private function getHashFromString()
    {
        return $this->getUrlComponent('fragment');
    }
    
    private function getPortFromString()
    {
        return $this->getUrlComponent('port');
    }
    
    private function getUsernameFromString()
    {
        return $this->getUrlComponent('user');
    }
    
    private function getPasswordFromString()
    {
        return $this->getUrlComponent('pass');
    }
    
    // ========
    // CREATION
    // ========

    /**
     * Create object using current requests data from the $_SERVER superglobal
     *
     * @return PHPVAL_Url_Absolute
     */
    public function createFromCurrentRequest()
    {
        return new PHPVAL_Url_Absolute($this->getProtocolFromGlobals(),
            $this->getDomainFromGlobals(),
            $this->getPathnameFromGlobals(), 
            $this->getQueryStringFromGlobals());
    }

    /**
     * @param string $urlString
     * @return PHPVAL_Url_Absolute
     */
    public function createFromAbsoluteUrl($urlString)
    {
        $this->urlComponents = parse_url((string)$urlString);
        if (!$this->urlComponents) {
            throw new PHPVAL_Url_Exception("Could not parse URL $urlString");
        }
        
        $url = new PHPVAL_Url_Absolute($this->getProtocolFromString(),
            $this->getDomainFromString(),
            $this->getPathnameFromString(), 
            $this->getQueryStringFromString(),
            $this->getHashFromString());
        
        $port = $this->getPortFromString();
        if ($port) $url->setPort($port);    
            
        $user = $this->getUsernameFromString();
        $password = $this->getPasswordFromString();
        if ($user && $password) {
            $url->setUsernameAndPassword($username, $password);
        } 
        return $url;
    }
    
    /**
     * Returns the URL object from the referring URL
     * 
     * @return PHPVAL_Url_Absolute
     */
    public function createFromReferrer()
    {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            throw new PHPVAL_Url_Exception("No HTTP_REFERER setting found");
        }
        return $this->createFromAbsoluteUrl($_SERVER['HTTP_REFERER']);
    }
}