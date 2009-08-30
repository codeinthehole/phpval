<?php

require_once dirname(__FILE__).'/Bootstrap.php';
require_once dirname(__FILE__).'/../PHPVAL/Url.php';

class PHPVAL_TestUrlSetters extends PHPUnit_Framework_TestCase
{
    private $url;
    
    public function setUp()
    {
        $this->url = new PHPVAL_Url('http', 'www.example.com', 80, '/page/123', 'q=hello&nocache', 'randomhash');
    }
    
    public function testSetProtocolAlsoChangesPort()
    {
        $newUrl = $this->url->setProtocol('https');
        $this->assertSame(443, $newUrl->getPort());
    }
    
    public function testSetDomainReturnsNewUrlObject()
    {
        $newUrl = $this->url->setDomain('subdomain.example.com');
        $this->assertTrue($newUrl instanceof PHPVAL_Url);
    }
    
    public function testSetDomain()
    {
        $newUrl = $this->url->setDomain('subdomain.example.com');
        $this->assertSame('http://subdomain.example.com/page/123?q=hello&nocache#randomhash', $newUrl->toString());
    }
    
    public function testSetPathnameReturnsNewUrlObject()
    {
        $newUrl = $this->url->setPathname('a-random-page');
        $this->assertTrue($newUrl instanceof PHPVAL_Url);
        $this->assertTrue($newUrl !== $this->url);
    }
    
    public function testSetPathname()
    {
        $newUrl = $this->url->setPathname('a-random-page');
        $this->assertSame('http://www.example.com/a-random-page?q=hello&nocache#randomhash', $newUrl->toString());
    }
    
    public function testSetQueryParamsReturnsNewUrlObject()
    {
        $newUrl = $this->url->setQueryParams(array('q' => 'asdf'));
        $this->assertTrue($newUrl instanceof PHPVAL_Url);
        $this->assertTrue($newUrl !== $this->url);
    }
    
    public function testSetQueryParams()
    {
        $newUrl = $this->url->setQueryParams(array('q' => 'asdf'));
        $this->assertSame('http://www.example.com/page/123?q=asdf#randomhash', $newUrl->toString());
    }
}