<?php
$__DPCSEC['REMOTEPRINT_DPC']='1;1;1;1;1;1;1;1;2';

if (!defined("REMOTEPRINT_DPC")) {//&& (seclevel('TEST_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("REMOTEPRINT_DPC",true);

$__DPC['REMOTEPRINT_DPC'] = 'remoteprint';

class remoteprint {


	function remoteprint($env=null) {
	
	  $this->env = $env;
	  
	}
	
    function rprint($message,$printserver) {

	  $this->printout = $this->env->get_agent('resources')->get_resource('printer');
   
      if ($this->printout)   
	    printer_write($this->printout,$message."\n\r");  
    }	
	
	function agents() {
	
	  print_r($this->env->agn_addr);
	}	
	
};	
}
?>