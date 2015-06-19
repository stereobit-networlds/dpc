<?php

/**
 * Testfile for the debugging console 
 *
 * @copyright 2003 Martin Rehker, <martin@mynukegenealogy.de>
 * @author $Author: mrehker $
 * @version $Id: test-class-console.php,v 1.2 2003/05/20 22:57:39 mrehker Exp $
 * @link http://www.mynukegenealogy.de
 * @package MyNukeGenealogy
 * @subpackage Debuging
 * @filesource
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @see console_class
 */

require_once ("./class-console.php");

print "<html><head><title>Testing console...</title></head><body>";

print "Output on console 1, no output on console 2";

$console_1 =& new console_class("console1","Console 1");
$console_2 =& new console_class("console2","Console 2");

$console_1->output("Testtext");

print $console_1->injection();
print $console_2->injection();

print "</body></html>";

?>