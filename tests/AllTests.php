<?php

require_once dirname(__FILE__).'/Bootstrap.php';
require_once dirname(__FILE__).'/Url/AllTests.php';

class PHPVAL_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPVAL unit tests');
        $suite->addTestSuite('PHPVAL_Url_AllTests');
        return $suite;
    }
}