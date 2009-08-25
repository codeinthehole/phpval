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
    
    public function testGetKeyValueQueryParam()
    {
        $this->assertSame('hello', $this->url->getQueryParam('q'));
    }
    
    public function testGetKeyQueryParamReturnsTrue()
    {
        $this->assertTrue($this->url->getQueryParam('nocache'));
    }
    
    public function testGetMissingKeyQueryParamReturnsFalse()
    {
        $this->assertFalse($this->url->getQueryParam('a'));
    }
}