<?php

/**
 * Class to open a debugging console 
 *
 * @copyright 2003 Martin Rehker, <martin@mynukegenealogy.de>
 * @author $Author: mrehker $
 * @version $Id: class-console.php,v 1.2 2003/05/20 22:55:32 mrehker Exp $
 * @link http://www.mynukegenealogy.de
 * @package MyNukeGenealogy
 * @subpackage Debuging
 * @filesource
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @example test-class-console.php Test-file for this class
 * @todo Write method to inject a link to the console window
 * @todo Pvovide methods to cleanup text printed in the console window (?) 
 */
class console_class
{
   /** The content buffer for this console window
	 * @var string  */
   var $content;
   /** The (javascript) name of the window
	 * @var string  */
	 var $window;
   /** The title of the window
	 * @var string  */
	 var $title;

  /**
  * Constructor
  *
  * @access public
  * @param string The name of the console window to be used
  * @param string The title of the window
  */
	 function console_class($window,$title)
	 {
	     $this->content = "";
		 $this->window = $window;
		 $this->title = $title;
	 }
	 
  /**
  * Function to return the Java-Script injection required to generate the console
	* 
  * @access public
  * @returns string The injection as html-ready text or "" if the console was not used 
  */
	 function injection()
	 {
	    if ($this->is_used())
			   $result = $this->_get_injection();
			else
			   $result = "";
			$content = "";
			return $result;
	 }
	 
  /**
  * Query function to determine whether the console was used
	* 
  * @access public
  * @returns bool Whether the console was used
  */
	 function is_used()
	 {
	    return (strlen($this->content)>1);
	 }
	 
  /**
  * Output text to the console
	* 
  * @access public
  * @param string The text to be written
  */
	 function output($text)
	 {
	   $this->content .= " $this->window.document.writeln('" . $text . "');\r\n";
	 }
	 	 
  /**
  * Function constructing the injection text
	* 
  * @access private
  * @returns string The injection text
  */
   function _get_injection()
	 {  
	    return   "\n<script type=\"text/javascript\" language=\"JavaScript\">\n"
						 . "<!--\n"
						 . " $this->window = window.open(\"\", \"" . $this->window . "Window\",\"resizable=yes,scrollbars=yes,directories=no,location=no,menubar=no,status=no,toolbar=no\");\n"
						 . " $this->window.document.open();"
						 . " $this->window.document.writeln(\"<html><head><title>Console: $this->title</title></head><body>\");"
						 . $this->content 
						 . " $this->window.document.writeln(\"</body></html>\");\n"
						 . " $this->window.document.close();\n"
						 . " //--></script>\n";
	 }
}
 
?>