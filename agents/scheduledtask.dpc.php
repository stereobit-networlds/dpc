<?php
$__DPCSEC['SCHEDULEDTASK_DPC']='1;1;1;1;1;1;1;1;2';

if (!defined("SCHEDULEDTASK_DPC")) {//&& (seclevel('TEST_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SCHEDULEDTASK_DPC",true);

$__DPC['SCHEDULEDTASK_DPC'] = 'scheduledtask';

class scheduledtask {

    var $env, $resources;
	var $value, $printer;

	function scheduledtask($env=null) {
	
	  //var_dump($env->agn_addr);
	  //var_dump($env->agn_length);  
	  
	  $this->env = $env;

	  //$this->resources = new resources($env);
	  
	  $this->value = 100;
	  $this->x = 222;
	  
	  //$env->scheduler->schedule('scheduledtask.scheduleprint','every','10');
	  //agent now
	  $sh = $this->env->get_agent('scheduler');
	  $sh->schedule('scheduledtask.scheduleprint','every','10');
				  
	  $this->env->update_agent($sh,'scheduler');
	  
	  //print_r($this->env->get_agent('scheduler')->timeline); 	  
	}
	
	function agents() {
	
	  print_r($this->env->agn_addr);
	  
	  //$this->x =  $this->env->get_agent('resources')->get_resource('variable');		  
	  //$this->env->update_agent($this,'scheduledtask');
	}
	
    function scheduleprint() {
	
	  $this->value = 200; //ok
      $this->printer = $this->env->get_agent('resources')->get_resource('printer');   
	  
      printer_write($this->printer, date("l dS of F Y h:i:s A")."\n\r");  
		
	  //print_r($this->env->get_agent('resources')->_resptr);
	  //print_r($this->env->get_agent('resources')->_resources);	  
	  echo $this->printer;//lost?????????????????
	  echo "\n",$this->x,"........................\n"; 
	  
	  //$this->env->update_agent($this,'scheduleprint');	  	
	  //$this->x =  $this->env->get_agent('resources')->get_resource('variable');			
	  //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    }		
	
};	
}
?>