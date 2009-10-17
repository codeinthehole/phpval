<?php

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'PHPVAL_Url_AllTests::main');
}


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

if (PHPUnit_MAIN_METHOD == 'PHPVAL_Url_AllTests::main') {
    PHPVAL_Url_AllTests::main();
}
