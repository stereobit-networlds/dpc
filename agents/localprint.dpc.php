<?php
$__DPCSEC['LOCALPRINT_DPC']='1;1;1;1;1;1;1;1;2';

if (!defined("LOCALPRINT_DPC")) {//&& (seclevel('TEST_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("LOCALPRINT_DPC",true);

$__DPC['LOCALPRINT_DPC'] = 'localprint';

class localprint {


	function localprint($env=null) {
	
	  //var_dump($env->agn_addr);	
	  $this->env = $env;
	  
	  //$this->resources = new resources($env);
	}
	
    function lprint($message) {
	     
      printer_write($this->env->get_agent('resources')->get_resource('printer'), $message."\n\r");  
		
    }	
	
	function agents() {
	
	  print_r($this->env->agn_addr);
	}		
	
};	
}
?>