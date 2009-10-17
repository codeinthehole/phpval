<?php
/**
 * PHPVAL - Value objects for PHP applications
 * 
 * Copyright (c) 2009, David Winterbottom <david.winterbottom@gmail.com>.
 * All rights reserved.
 * 
 * @package phpval
 * @author David Winterbottom <david.winterbottom@gmail.com>
 * @copyright 2009 David Winterbottom <david.winterbottom@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

/**
 * Enum object for URL protocols
 *
 * @author David Winterbottom <david.winterbottom@gmail.com>
 * @copyright 2009 David Winterbottom <david.winterbottom@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class PHPVAL_Url_Protocols
{
    const HTTP = 'http';
    const HTTPS = 'https';
    const FTP = 'ftp';
    const GOPHER = 'gopher';
    const MAILTO = 'mailto';
    const TELNET = 'telnet';
}