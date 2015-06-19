<?php

$__DPCSEC['CONSOLE_DPC']='2;2;2;2;2;2;2;2;9';

if ( (!defined("CONSOLE_DPC")) && (seclevel('CONSOLE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("CONSOLE_DPC",true);

$__DPC['CONSOLE_DPC'] = 'console';

$__EVENTS['CONSOLE_DPC'][0]='console';

$__ACTIONS['CONSOLE_DPC'][0]='console';

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

//require_once ("console.lib.php");
//GetGlobal('controller')->include_dpc('console/console.lib.php');
$d = GetGlobal('controller')->require_dpc('console/console.lib.php');
require_once($d);

class console {

    var $con1;
	var $con2;

    function console() {

       $this->con1 =& new console_class("console1","Console 1");
       //$this->con2 =& new console_class("console2","Console 2");

    }
	
	function event($evn=null) {
	   $param1 = GetGlobal('param1');

       $this->con1->output("Console:");	
       $this->con1->output($param1);		   
	}
	
	function action($act=null) {
	  
	   $out = $this->con1->injection();
	   //$out .= $this->con2->injection();
	   
	   return ($out);
	}
	
	function console_write($text) {
	
       $this->con1->output($text);		
	}
	
	function free() {
	   
	   unset($this->con1);
	   //unset($this->con2);
	}
};	
}
?>