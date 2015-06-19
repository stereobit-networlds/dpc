<?php
$__DPCSEC['SCHEDULER_DPC']='1;1;1;1;1;1;1;1;2';

if (!defined("SCHEDULER_DPC")) {
define("SCHEDULER_DPC",true);

$__DPC['SCHEDULER_DPC'] = 'scheduler';

class scheduler {

   var $timeline, $env;
 
   function scheduler($env=null) {
   
	 //var_dump($env->agn_addr);   
   
     $this->timeline = array();
	 $this->env = $env;
	 
     register_tick_function(array(&$this,"checkschedules"),true);		 
   }
   
	function agents() {
	
	  print_r($this->env->agn_addr);
	}   
   
   public function schedule($agent,$frequency,$time) {
   
     $schedules = array();
     $schedules['agent'] = $agent;
	 
	 switch ($frequency) {
		case 'at'    : $schedules['freq'] = 0; break; //once
		case 'every' : $schedules['freq'] = 1; break; //cycle
		default      : $schedules['freq'] =-1; break; //now
 	 }
	 
	 $schedules['time'] = $time;
	 
	 $timein = time();
	 //due to speed up it is possible to have
	 //multiple schedules entry in the same time, so add 1 sec
	 if (array_key_exists($timein,$this->timeline))
	   $this->timeline[$timein+1] = $schedules;  
	 else
	   $this->timeline[$timein] = $schedules; 
	 
	 //$this->env->update_agent($this,'scheduler'); must called by caller to update env
	 //print_r($this->timeline); //test scheduler
	 //var_dump($this);
	 
	 $this->env->update_agent($this,'scheduler');
	 
     register_tick_function(array($this,"checkschedules"),true);	 	 
   }
   
   public function checkschedules() {
   
     //echo "check schedules....";
	 //print_r($this->timeline);	 
	 //echo time(),"\n"; 	 
	 
	 $new_timeline = array();
	 
	 foreach ($this->timeline as $inittime=>$entry) {
	   
	   $new_element = $this->check_schedule_entry($entry,$inittime);
	   //print_r($new_element);echo 'cccc';
	   
	   if (is_array($new_element)) 
		 $new_timeline[$inittime] = $new_element;
	   else
	     $error = $new_element;	 
	 }
	 
	 //print_r($new_timeline);
	 //reconf timeline
	 //$this->timeline = null;
	 if (!empty($new_timeline))
	   $this->timeline = (array)$new_timeline;
	   
	 $ret = $error. '> check schedules... '. time();  
	 return ($ret);  
   }
   
   function check_schedule_entry(&$entry,$inittime) {  
   
     //echo $this->env->get_agent('scheduledtask')->value;
	 //echo ".\n";   
   
     if (array_key_exists('lasttime',$entry))
	   $last_time = $entry['lasttime'];
	 else
	   $last_time = $inittime;
	 
	 $agn = explode('.',$entry['agent']);
	 $agent = $agn[0];
	 $cmd = $agn[1]; 
	  
	 $now = time();  
	 if ($now-$last_time>=$entry['time']) {
	 
	   //echo $agent,"...\n";
	   $o_agent = $this->env->get_agent($agent);	 
       //print_r($o_agent);
	   if (is_object($o_agent)) {   

		  if (method_exists($o_agent,$cmd)) 
		    $ret = $o_agent->$cmd();
		  else
		    $ret = "Invalid command.\n" . var_dump(get_class_methods($o_agent));  
		  
		  $this->env->dmn->Println ($ret);  
	   	   
	      $entry['lasttime'] = $now;
		  $entry['counter'] = $entry['counter']+1;
		  
	      //$this->env->update_agent($this,'scheduler');		  
	   
	      if ($entry['freq']==0)//once
	        return 0;
	      else 
	        return ($entry);//new array element  	   
	   }
	   else {
	     //in case of 'env' execute from env'
		 if (($agent=='env') && (method_exists($this->env,$cmd))) {
		   
		   $ret = $this->env->$cmd(); 
		   $this->env->dmn->Println ($ret);
		   
	       $entry['lasttime'] = $now;
		   $entry['counter'] = $entry['counter']+1;
		  
	       //$this->env->update_agent($this,'scheduler');		  
	   
	       if ($entry['freq']==0)//once
	         return 0;
	       else 
	         return ($entry);//new array element  			   
		 }
		 else
	       //echo "ERROR";
	       return -1;//not an objet error...
	   }	           
     }
	 else
	   //return not scheduled yet entries (as is)	   
	   return ($entry);//not executed yet 
  }
  
  //show the schedules running ........
  function showschedules() {
  
     foreach ($this->timeline as $t=>$d) {
	   $ret .= "[" . $t . "]\r\n";
	   foreach ($d as $t=>$data)
	     $ret .= $t . "=" . $data . "\r\n";
	 }  
  
     return ($ret);  
  }
  
  function __destruct() {
  
	  //unregister_tick_function(array(&$this,'checkschedules'));  
	  //zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz
  }
  	 
};
}
?>