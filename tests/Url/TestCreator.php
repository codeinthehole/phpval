<?php

require_once dirname(__FILE__).'/../Bootstrap.php';
require_once PATH_TO_PHPVAL.'/Url/Creator.php';

class PHPVAL_Url_TestCreator extends PHPUnit_Framework_TestCase
{
    private $urlCreator;
    
    public function setUp()
    {
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '/path/to/file?q=test';
        
        $_SERVER['HTTP_REFERER'] = 'http://example.com/path/to/file?q=test&m=all#fragment';
        
        $this->urlCreator = new PHPVAL_Url_Creator;
    }
    
    public function tearDown()
    {
        unset($_SERVER['HTTP_HOST']);
        unset($_SERVER['REQUEST_URI']);
        unset($_SERVER['HTTP_REFERER']);
    }
    
    public function testAbsoluteUrlsAreCreated()
    {
        $url = $this->urlCreator->createFromCurrentRequest();
        $this->assertType('PHPVAL_Url_Absolute', $url);
    }
    
    public function testProtocolDefaultsToHttp()
    {
        $url = $this->urlCreator->createFromCurrentRequest();
        $this->assertSame(PHPVAL_Url_Protocols::HTTP, $url->getProtocol());
    }
    
    public function testDomain()
    {
        $url = $this->urlCreator->createFromCurrentRequest();
        $this->assertSame('example.com', $url->getDomain());
    }
    
    public function testQueryParams()
    {
        $url = $this->urlCreator->createFromCurrentRequest();
        $this->assertSame('test', $url->getQueryParam('q'));
    }
    
    public function testCreateFromAbsoluteUrl()
    {
        $urlString = 'http://example.com/path/to/file?q=test&m=all#fragment';
        $url = $this->urlCreator->createFromAbsoluteUrl($urlString);
        $this->assertType('PHPVAL_Url_Absolute', $url);
        $this->assertSame('http', $url->getProtocol());
        $this->assertSame('example.com', $url->getDomain());
        $this->assertSame('/path/to/file', $url->getPathname());
        $this->assertSame('q=test&m=all', $url->getQueryString());
        $this->assertSame('fragment', $url->getHash());
        $this->assertSame($urlString, (string)$url);
    }
    
    public function testCreateFromReferrer()
    {
        $url = $this->urlCreator->createFromReferrer();
        $this->assertType('PHPVAL_Url_Absolute', $url);
        $this->assertSame('http', $url->getProtocol());
        $this->assertSame('example.com', $url->getDomain());
        $this->assertSame('/path/to/file', $url->getPathname());
        $this->assertSame('q=test&m=all', $url->getQueryString());
        $this->assertSame('fragment', $url->getHash());
    }
}