<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once dirname(__FILE__).'/../classes/Url.php';

class TestUrl extends PHPUnit_Framework_TestCase
{
    public function testCreateFromAbsoluteUrl()
    {
        $urlString = 'http://www.hello.com';
        $url = Url::createFromAbsoluteUrl($urlString);

        var_dump($url);
    }
}