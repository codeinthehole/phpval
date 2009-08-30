<?php

require_once dirname(__FILE__).'/Bootstrap.php';
require_once dirname(__FILE__).'/TestUrlGetters.php';
require_once dirname(__FILE__).'/TestUrlSetters.php';

class PHPVAL_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPVAL unit tests');
        $suite->addTestSuite('PHPVAL_TestUrlGetters');
        $suite->addTestSuite('PHPVAL_TestUrlSetters');
        return $suite;
    }
}