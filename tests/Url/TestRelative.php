<?php

require_once dirname(__FILE__).'/../Bootstrap.php';
require_once PATH_TO_PHPVAL.'/Url/Absolute.php';

class PHPVAL_Url_TestRelative extends PHPUnit_Framework_TestCase
{
    private $url;
    
    public function setUp()
    {
        $this->url = new PHPVAL_Url_Relative('/page/123', 'q=hello&nocache', 'randomhash');
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
    
    public function testSetPathnameReturnsNewUrlObject()
    {
        $newUrl = $this->url->setPathname('a-random-page');
        $this->assertTrue($newUrl instanceof PHPVAL_Url_Relative);
        $this->assertTrue($newUrl !== $this->url);
    }
    
    public function testSetPathname()
    {
        $newUrl = $this->url->setPathname('a-random-page');
        $this->assertSame('/a-random-page?q=hello&nocache#randomhash', $newUrl->toString());
    }
    
    public function testSetQueryParamsReturnsNewUrlObject()
    {
        $newUrl = $this->url->setQueryParams(array('q' => 'asdf'));
        $this->assertTrue($newUrl instanceof PHPVAL_Url_Relative);
        $this->assertTrue($newUrl !== $this->url);
    }
    
    public function testSetQueryParams()
    {
        $newUrl = $this->url->setQueryParams(array('q' => 'asdf'));
        $this->assertSame('/page/123?q=asdf#randomhash', $newUrl->toString());
    }
}