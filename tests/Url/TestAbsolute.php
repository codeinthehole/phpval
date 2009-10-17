<?php

require_once dirname(__FILE__).'/../Bootstrap.php';
require_once PATH_TO_PHPVAL.'/Url/Absolute.php';

class PHPVAL_Url_TestAbsolute extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPVAL_Url_Absolute
     */
    private $url;
    
    public function setUp()
    {
        $this->url = new PHPVAL_Url_Absolute('http', 'www.google.com', '/page/123', 'q=hello&nocache', 'randomhash');
    }
    
    public function testGetProtocol()
    {
        $this->assertSame('http', $this->url->getProtocol());
    }
    
    public function testGetHost()
    {
        $this->assertSame('www.google.com', $this->url->getDomain());
    }
    
    public function testGetPort()
    {
        $newUrl = $this->url->setPort(80);
        $this->assertSame(80, $newUrl->getPort());
    }
    
    public function testSetAndGetAuthentication()
    {
        $newUrl = $this->url->setUsernameAndPassword('david', 'password');
        $this->assertSame('david', $newUrl->getUsername());
        $this->assertSame('password', $newUrl->getPassword());
    }
    
    public function testGetBaseUrlReturnsUrlObject()
    {
        $this->assertTrue($this->url->getBaseUrl() instanceof PHPVAL_Url_Absolute);
    }
    
    public function testToString()
    {
        $this->assertSame('http://www.google.com/page/123?q=hello&nocache#randomhash', $this->url->toString());
    }
    
    public function testMagicToString()
    {
        $this->assertSame('http://www.google.com/page/123?q=hello&nocache#randomhash', (string)$this->url);
    }
    
    public function testSetProtocolAlsoChangesPort()
    {
        $newUrl = $this->url->setProtocol('https');
        $this->assertSame(443, $newUrl->getPort());
    }
    
    public function testSetDomainReturnsNewUrlObject()
    {
        $newUrl = $this->url->setDomain('subdomain.example.com');
        $this->assertTrue($newUrl instanceof PHPVAL_Url_Absolute);
    }
    
    public function testSetDomain()
    {
        $newUrl = $this->url->setDomain('subdomain.example.com');
        $this->assertSame('http://subdomain.example.com/page/123?q=hello&nocache#randomhash', $newUrl->toString());
    }
}