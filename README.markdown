PHPVAL - Value objects for PHP applications
===========================================

This is a simple collection of Value Objects for use within PHP applications.  Statics have 
been avoided throughout the collection, making it easy to create subclasses that hold specific
behaviour within your applications.

Some of the objects within the collection are not strictly value objects as they have setter methods.
This is a design decision to prevent to keep the API cleaner, give the lack of method overloading in PHP
and the desire to avoid static factory methods.  For all intents and purposes, the objects behave
as value objects in the traditional sense.

For more information on value objects, see http://c2.com/cgi/wiki?ValueObject

Installation
------------

No PEAR installer just yet, simply copy the contents of the PHPVAL/ folder to somewhere on your 
PHP path.  For example:
    cd ~/workspace
    git clone git://github.com/codeinthehole/phpval.git
    cp -r phpval/PHPVAL/* /usr/share/php

All feedback welcome.
