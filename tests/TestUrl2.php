<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once dirname(__FILE__).'/../classes/Url.php';

class TestUrl extends PHPUnit_Framework_TestCase
{
    public function testGetDomain()
    {
        $strUrl = 'http://www.hello.com';
        $objUrl = Url::createFromAbsoluteUrl($strUrl);

        $this->assertSame('www.hello.com', $objUrl->getDomain());
    }

    public function testGetProtocol()
    {
        $strUrl = 'http://www.hello.com';
        $objUrl = Url::createFromAbsoluteUrl($strUrl);

        var_dump($objUrl);die;

        $this->assertSame('http', $objUrl->getProtocol());
    }

    public function testChangeDomain()
    {
        $strUrl       = 'http://www.hello.com/testing/?asdf';
        $strNewDomain = 'www.goodbye.co.uk';
        $objUrl = Url::createFromAbsoluteUrl($strUrl);
        $objUrl->setDomain($strNewDomain);

        $this->assertSame($strNewDomain, $objUrl->getDomain());
        $this->assertContains($strNewDomain, $objUrl->toString());
    }

    public function testChangeProtocol()
    {
        $strUrl         = 'http://www.hello.com/testing/?asdf';
        $strNewProtocol = 'https';
        $objUrl = Url::createFromAbsoluteUrl($strUrl);
        $objUrl->setProtocol($strNewProtocol);

        $this->assertSame($strNewProtocol, $objUrl->getProtocol());
        $this->assertContains($strNewProtocol, $objUrl->toString());
    }

    public function testChangePathname()
    {
        $strUrl         = 'http://www.hello.com/testing/?asdf';
        $strNewPathname = 'hello/everybody';
        $objUrl = Url::createFromAbsoluteUrl($strUrl);
        $objUrl->setPathname($strNewPathname);

        $this->assertSame('/hello/everybody', $objUrl->getPathname());
        $this->assertSame('http://www.hello.com/hello/everybody?asdf', $objUrl->toString());
    }

    public function testGetRootPathname()
    {
        $strUrl = 'http://www.hello.com/';
        $objUrl = Url::createFromAbsoluteUrl($strUrl);

        $this->assertSame('/', $objUrl->getPathname());
    }

    public function testGetPathname()
    {
        $strUrl = 'http://www.hello.com/here/is/my/page?here=is-a-random-query';
        $objUrl = Url::createFromAbsoluteUrl($strUrl);

        $this->assertSame('/here/is/my/page', $objUrl->getPathname());
    }

    public function testGetQueryString()
    {
        $strUrl = 'http://www.hello.com/here/is/my/page?here=is-a-random-query';
        $objUrl = Url::createFromAbsoluteUrl($strUrl);

        $this->assertSame('here=is-a-random-query', $objUrl->getQueryString());
    }

    public function testGetRelativeUrlString()
    {
        $strUrl = '/here/is/my/page?here=is-a-random-query';
        $objUrl = Url::createFromRelativeUrl($strUrl);
        $this->assertSame($strUrl, $objUrl->toString());
        $this->assertSame('here=is-a-random-query', $objUrl->getQueryString());
    }

    public function testGetQueryStringWithRootPathname()
    {
        $strUrl = 'http://www.hello.com/?here=is-a-random-query';
        $objUrl = Url::createFromAbsoluteUrl($strUrl);

        $this->assertSame('here=is-a-random-query', $objUrl->getQueryString());
    }

    public function testConvenienceMethodWithFullUrl()
    {
        $strUrl = 'http://www.hello.com/?here=is-a-random-query';
        $objUrl = Url::createFromUrl($strUrl);

        $this->assertSame($strUrl, $objUrl->toString());
    }

    public function testConvenienceMethodWithPartialUrl()
    {
        $strUrl = '/?here=is-a-random-query';
        $objUrl = Url::createFromUrl($strUrl);

        $this->assertSame($strUrl, $objUrl->toString());
    }

    public function testGetDocumentRootUrl()
    {
        $strUrl = 'http://www.hello.com/?here=is-a-random-query&some=more';
        $objUrl = Url::createFromUrl($strUrl);

        $this->assertSame('/?here=is-a-random-query&some=more', $objUrl->getDocumentRootUrl());
    }
    
    public function testGetPathSegments()
    {
        $strUrl = 'http://www.hello.com/controller/method/param/?q=asdf';
        $objUrl = Url::createFromUrl($strUrl);

        $this->assertSame(array('controller', 'method', 'param'), $objUrl->getPathSegments());
    }
    
    public function testGetPathSegmentsForBareDomain()
    {
        $strUrl = 'http://www.hello.com';
        $objUrl = Url::createFromUrl($strUrl);

        $this->assertSame(array(), $objUrl->getPathSegments());
    }
    
    public function testGetPathSegmentsForDocRoot()
    {
        $strUrl = 'http://www.hello.com/';
        $objUrl = Url::createFromUrl($strUrl);

        $this->assertSame(array(), $objUrl->getPathSegments());
    }
    
    public function testGetPathSegment()
    {
        $strUrl = 'http://www.hello.com/controller/method/param/?q=asdf';
        $objUrl = Url::createFromUrl($strUrl);

        $this->assertSame('controller', $objUrl->getPathSegment(0));
        $this->assertSame('method', $objUrl->getPathSegment(1));
    }
    
    public function testGetQueryParams()
    {
        $_SERVER['HTTP_HOST']   = 'www.example.com';
        $_SERVER['REQUEST_URI'] = '/page/junk/132?q=testing&eggs=bacon&debug';
        $url = Url::createFromCurrentRequest();
        $this->assertSame(array('q' => 'testing', 'eggs' => 'bacon', 'debug' => ''), $url->getQueryParams());
    }
    
    public function testGetQueryParamsArentBrokenByExtraEquals()
    {
        $_SERVER['HTTP_HOST']   = 'www.example.com';
        $_SERVER['REQUEST_URI'] = '/page/junk/132?q=a=';
        $url = Url::createFromCurrentRequest();
        $this->assertSame(array('q' => 'a='), $url->getQueryParams());
    }
    
    public function testGetQueryParam()
    {
        $_SERVER['HTTP_HOST']   = 'www.example.com';
        $_SERVER['REQUEST_URI'] = '/page/junk/132?q=terry';
        $url = Url::createFromCurrentRequest();
        $this->assertSame('terry', $url->getQueryParam('q'));
    }
    
    public function testGetQueryParamWhenMissing()
    {
        $_SERVER['HTTP_HOST']   = 'www.example.com';
        $_SERVER['REQUEST_URI'] = '/page/junk/132?q=terry';
        $url = Url::createFromCurrentRequest();
        $this->assertSame(false, $url->getQueryParam('z'));
    }
    
    public function testSetQueryParams()
    {
        $url = Url::createFromAbsoluteUrl('http://www.example.com');
        $url->setQueryParams(array('q' => 'hello'));
        $queryParams = $url->getQueryParams();
        $this->assertArrayHasKey('q', $queryParams);
        $this->assertSame($queryParams['q'], 'hello');
    }
    
    public function testSetQueryParam()
    {
        $url = Url::createFromAbsoluteUrl('http://www.example.com/?a=test');
        $url->setQueryParam('q', 'hello');
        $queryParams = $url->getQueryParams();
        
        $this->assertArrayHasKey('q', $queryParams);
        $this->assertSame($queryParams['q'], 'hello');
        $this->assertArrayHasKey('a', $queryParams);
        $this->assertSame($queryParams['a'], 'test');
    }
    
    public function testSetQueryParamOverwrite()
    {
        $url = Url::createFromAbsoluteUrl('http://www.example.com/?a=test');
        $url->setQueryParam('a', 'hello');
        $queryParams = $url->getQueryParams();
        
        $this->assertArrayHasKey('a', $queryParams);
        $this->assertSame($queryParams['a'], 'hello');
    }
    
    public function testRemoveQueryParams()
    {
        $url = Url::createFromAbsoluteUrl('http://www.example.com/?a=test');
        $url->removeQueryParams();
        $this->assertSame('', $url->getQueryString());
    }
    
    public function testSetQueryParamWithNoValue()
    {
        $url = Url::createFromAbsoluteUrl('http://www.example.com/');
        $url->setQueryParam('nocache');
        $this->assertSame('http://www.example.com/?nocache', $url->toString());
    }

    public function testToString()
    {
        $url = Url::createFromAbsoluteUrl('http://www.example.com/');
        $this->assertSame('http://www.example.com/', $url->toString());
    }
    
    public function testGetBaseUrl()
    {
        $url = Url::createFromAbsoluteUrl('http://www.example.com/hello/this-is-a-page/?nocache');
        $this->assertSame('http://www.example.com', $url->getBaseUrl());
    }

    public function testGetBaseUrlFromPartialStartUrl()
    {
        $url = Url::createFromUrl('www.example.com/hello/this-is-a-page/?nocache');
        $this->assertSame('http://www.example.com', $url->getBaseUrl());
    }

    public function testRemoveFirstQueryParam()
    {
        $url = Url::createFromUrl('/?nocache&page=123');
        $url->removeQueryParam('nocache');
        $this->assertSame((string)Url::createFromUrl('/?page=123'), (string)$url);
    }
    
    public function testRemoveSecondQueryParam()
    {
        $url = Url::createFromUrl('/?nocache&page=123');
        $url->removeQueryParam('page');
        $this->assertSame((string)Url::createFromUrl('/?nocache'), (string)$url);
    }

    public function testCreateFromReferrer()
    {
        $referringUrl = 'http://www.example.com/books/123?q=test';
        $_SERVER['HTTP_REFERER'] = $referringUrl;
        $referrer = Url::createFromReferrer();
        $this->assertSame($referringUrl, (string)$referrer);
    }

    public function testGetQueryParamForKeyWithNoValue()
    {
        $urlString = 'http://www.example.com/books/123?logqueries';
        $url = Url::createFromAbsoluteUrl($urlString);
        $this->assertSame(true, $url->getQueryParam('logqueries'));
    }
}