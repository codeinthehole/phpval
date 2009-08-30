<?php

require_once dirname(__FILE__).'/Bootstrap.php';
require_once dirname(__FILE__).'/../PHPVAL/Url.php';

class PHPVAL_TestUrlGetters extends PHPUnit_Framework_TestCase
{
    private $url;
    
    public function setUp()
    {
        $this->url = new PHPVAL_Url('http', 'www.google.com', 80, '/page/123', 'q=hello&nocache', 'randomhash');
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
        $this->assertSame(80, $this->url->getPort());
    }
    
    public function testGetPathname()
    {
        $this->assertSame('/page/123', $this->url->getPathname());
    }
    
    public function testGetPathSegments()
    {
        $this->assertSame(array('page', '123'), $this->url->getPathSegments());
    }
    
    public function testGetValidPathSegment()
    {
        $this->assertSame('page', $this->url->getPathSegment(0));
    }
    
    public function testGetInvalidPathSegment()
    {
        $this->assertNull($this->url->getPathSegment(3));
    }
    
    public function testGetKeyValueQueryParam()
    {
        $this->assertSame('hello', $this->url->getQueryParam('q'));
    }
    
    public function testGetKeyQueryParamReturnsTrue()
    {
        $this->assertTrue($this->url->getQueryParam('nocache'));
    }
    
    public function testGetMissingKeyQueryParamReturnsNull()
    {
        $this->assertNull($this->url->getQueryParam('a'));
    }
    
    public function testGetQueryString()
    {
        $this->assertSame('q=hello&nocache', $this->url->getQueryString());
    }
    
    public function testGetBaseUrlReturnsUrlObject()
    {
        $this->assertTrue($this->url->getBaseUrl() instanceof PHPVAL_Url);
    }
    
    public function testToString()
    {
        $this->assertSame('http://www.google.com/page/123?q=hello&nocache#randomhash', $this->url->toString());
    }
    
    public function testMagicToString()
    {
        $this->assertSame('http://www.google.com/page/123?q=hello&nocache#randomhash', (string)$this->url);
    }
}