<?php

/**
 * Testfile for the debugging console 
 *
 * @copyright 2003 Martin Rehker, <martin@mynukegenealogy.de>
 * @author $Author: mrehker $
 * @version $Id: test-class-debug.php,v 1.2 2003/05/22 08:21:31 mrehker Exp $
 * @link http://www.mynukegenealogy.de
 * @package MyNukeGenealogy
 * @subpackage Debuging
 * @filesource
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @see console_class
 */

require_once ("./class-debug.php");

print "<html><head><title>Testing console...</title></head><body>";

print "Debugging ... ";

$filter = array("Section A" => 4, "Section B" => 2);
$debug =& new debug_class($filter);

$debug->debug("Testtext","Section A",DC_CRITICAL_ERROR,__FILE__,__LINE__);
$debug->debug("Testtext","Section B",DC_CRITICAL_ERROR,__FILE__,__LINE__);

$debug->debug("Testtext","Section A",DC_ERROR,__FILE__,__LINE__);
$debug->debug("Testtext","Section B",DC_ERROR,__FILE__,__LINE__);

$debug->debug("Testtext","Section A",DC_WARNING,__FILE__,__LINE__);
$debug->debug("Testtext","Section B",DC_WARNING,__FILE__,__LINE__);

// $debug->debug("Testtext","Section A",DC_INFO,__FILE__,__LINE__);
// $debug->debug("Testtext","Section B",DC_INFO,__FILE__,__LINE__);

$debug->debug_record("Testing record output",$filter,"Section A",DC_ERROR,__FILE__,__LINE__);

$debug->debug("Testtext","Section C",DC_WARNING,__FILE__,__LINE__);

print $debug->injection();

print "</body></html>";

?>