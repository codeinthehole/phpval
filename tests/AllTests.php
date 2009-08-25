<?php

require_once dirname(__FILE__).'/Bootstrap.php';
require_once dirname(__FILE__).'/TestUrl.php';

class PHPVAL_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPVAL unit tests');

        $suite->addTest(NetTests::suite());

        return $suite;
    }
}