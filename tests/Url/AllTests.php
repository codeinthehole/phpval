<?php

require_once dirname(__FILE__).'/../Bootstrap.php';
require_once dirname(__FILE__).'/TestRelative.php';
require_once dirname(__FILE__).'/TestAbsolute.php';
require_once dirname(__FILE__).'/TestCreator.php';

class PHPVAL_Url_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPVAL Url unit tests');
        $suite->addTestSuite('PHPVAL_Url_TestRelative');
        $suite->addTestSuite('PHPVAL_Url_TestAbsolute');
        $suite->addTestSuite('PHPVAL_Url_TestCreator');
        return $suite;
    }
}