<?php


define (DC_CRITICAL_ERROR, 4);
define (DC_ERROR, 3);
define (DC_WARNING, 2);
define (DC_INFO, 1);
define (DC_NONE, 0);

$_DC_string = array("","information","trace","error","critical error");

require_once ( "console.lib.php" );

/**
 * Class to manage user-level debuging 
 *
 * php level debuging is done by the class {@link ErrorHandler} 
 *
 * @copyright 2003 Martin Rehker, <martin@mynukegenealogy.de>
 * @author $Author: mrehker $
 * @version $Id: class-debug.php,v 1.3 2003/05/22 08:21:31 mrehker Exp $
 * @link http://www.mynukegenealogy.de
 * @package MyNukeGenealogy
 * @subpackage Debuging
 * @filesource
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @uses console_class
 * @example test-class-debug.php Test-file for this class
 * @todo Use templates for display, allow user to modify templates
 * @todo Eliminate global variables
 */
class debug_class 
{
    /** The console window
		* @var class 
		* @uses console_class */		 
    var $console;
    /** The section filter to be applied
		* @var array */		 
		var $filter;
    /** The array to count messages per section
		* @var array */		 
		var $counter;
    /** Flag indicating, whether to show section names in output
		* @var bool */		 
		var $show_section;
    /** Flag indicating, whether to show filename and line information in output
		* @var bool */		 
		var $show_file;

		function _set_filter($filter)
		{
		   $this->filter = array_merge($filter,array("debug_class",4));
		}
		
    /**
    * Constructor
  	* 
    * @access public
		* @param array The filter array (array of [section] => level)
    */
    function debug_class($filter)
		{ 
			$this->console = new console_class("Debug","Debug"); 
			$this->_set_filter($filter);
			$this->show_section = true;
			$this->show_file = true;
			$this->_output_header();
		} 
				
    /**
    * Internal function to generate an array dump
  	* 
    * @access public
		* @param array The array to be dumped
    * @returns string The dumped array
    */
    function _get_var_dump_html($var)
    {
      ob_start();
      var_dump($var);
      $ret_val = ob_get_contents();
      ob_end_clean();
      return "<pre>". ereg_replace("(\r\n|\n|\r)", "<br />", htmlentities( preg_replace("/=>\s*/s"," => ",$ret_val))) . "</pre>";
    }

    /**
    * Setting the debug level for section $section
  	* 
    * @access public
		* @param string The debuging section (differentiated debugging levels possible)
		* @param string The required output level
    */
		function set_level($section,$level)
		{
		   $this->filter[$section] = $level;
		}
		
    /**
    * Function to output the header into the debuging console window
    */
		function _output_header()
		{
		   $this->console->output("<h2>Debuging console</h2>");
		}
		
    /**
    * Function to output the footer (statistics, copyright) into the debuging console window
    */
		function _output_footer()
		{
		    $this->console->output("<hr/><h2>Stats: </h2>");
				foreach ($this->counter as $key=>$value)
				   $this->console->output("In \"$key\" {$value[true]} messages shown, {$value[false]} messages hidden<br/>" );
		    $this->console->output(
				     "<br/><hr/><h2>Copyright: </h2>This console window was generated"
						." by the class debug_class, Copyright 2003, Martin Rehker,"
						." lincenced under GPL, available at <a href=\"http://www.mynukegenealogy.de/\">www.mynukegenealogy.de</a>.");
		}

    /**
    * Function counting one given displayed messages
  	* 
    * @access private
		* @param string The section in which a message is displayed
  	* @param bool Whether the message was printed or is hidden
		*/
    function _count($section,$shown = true)
		{
		    if (!array_key_exists($section,$this->counter))
				{
				   $this->counter[$section][true] = 0; 
				   $this->counter[$section][false] = 0;
				}
				$this->counter[$section][$shown]++; 
		}
				
    /**
    * Function to print the output debug text
  	* 
    * @access private
		* @param string The debugging content
		* @param string The debuging section (differentiated debugging levels possible)
		* @param string The output level
		* @param string The current filename
		* @param string The current linenumber
    */
		function _output_debug($string,$section,$level=DC_ERROR,$file="-unknown file-",$line="-unknown line-")
		{
		   global $_DC_string;
			 
       $this->console->output ( "<font color=blue><b>" . ucfirst($_DC_string[$level]));
			 if ($this->show_section)
			  	$this->console->output (" in \"$section\"");
			 $this->console->output (  ":</b></font> " ); 
			 $this->console->output ( $string );
			 if ($this->show_file)
			    $this->console->output ( "<font size=-2> (file " . addslashes($file) . ", line $line)</font>");
			 $this->console->output ( "<br /><br />");
		}
		
    /**
    * Function to output debug text
  	* 
    * @access public
		* @param string The debugging content
		* @param string The debuging section (differentiated debugging levels possible)
		* @param string The output level
		* @param string The current filename
		* @param string The current linenumber
		* @uses _output_debug
    */
    function debug($string,$section,$level=DC_ERROR,$file="-unknown file-",$line="-unknown line-")
    {
			 if (!array_key_exists($section,$this->filter))
			   $this->_output_debug("Unknown message section \"$section\" in class debug_class","debug_class",DC_ERROR,$file,$line);
       if ($this->filter[$section] >= $level or (!array_key_exists($section,$this->filter)))
			 {
			   $this->_output_debug($string,$section,$level,$file,$line);
				 $this->_count($section,true);
				 $this->last_file = $file;
				 $this->last_line = $line;
			 } else
				 $this->_count($section,false);
    }

    /**
    * Function to output debug text including an array
  	* 
    * @access public
		* @param string The debugging content
		* @param string The array to be printed
		* @param string The debuging section (differentiated debugging levels possible)
		* @param string The output level
		* @param string The current filename
		* @param string The current linenumber
		* @uses debug
    */
    function debug_record($string,$record,$section,$level=DC_ERROR,$file="-unknown file-",$line="-unknown line-")
    {  
       $this->debug($string . $this->_get_var_dump_html($record),$section,$level,$file,$line);
    }

 		
    /**
    * Query function to determine whether the console was used
  	* 
    * @access public
    * @returns bool Whether the console was used
		* @uses console_class->is_used
		*/
		function is_used()
		{
		   $result = 0;
			 
			 foreach ($this->counter as $value)
			   $result += $value[true];
				
 			 return ($result > 0);
		}
		 
    /**
    * Function to return the HTML injection required to print the console
  	* 
    * @access public
    * @returns string The HTML injection string (or "" if not used)
		* @uses console_class->injection
    */
    function injection()
    {  
		   $this->_output_footer();
		   if ($this->is_used())
			    return $this->console->injection();
			 else
			    return "";
    }
}

?>