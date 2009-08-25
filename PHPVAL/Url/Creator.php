<?php
class PHPVAL_Url_Creator
{
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
}