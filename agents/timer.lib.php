<?php
$__DPCSEC['TIMER_DPC']='1;1;1;1;1;1;1;1;2';

if (!defined("TIMER_DPC")) {
define("TIMER_DPC",true);

$__DPC['TIMER_DPC'] = 'timer';

class timer {

   var $time;

   function timer($env = null) {
   
     $this->env = $env;   
     $this->time = time();
   }
   
   public function showtime() {
   
      echo date("l dS of F Y h:i:s A",$this->time),"\n";
   }
   
   public function get_time() {
      
	  $this->time = time();
	  
      return (time());
   }
};	
}
?>