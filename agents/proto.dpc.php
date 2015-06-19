<?php

$__DPCSEC['PROTO_DPC']='1;1;1;1;1;1;1;1;2';

if (!defined("PROTO_DPC")) {//&& (seclevel('SMDR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("PROTO_DPC",true);

$__DPC['PROTO_DPC'] = 'proto';

$__EVENTS['PROTO_DPC'][0]='proto';

$__ACTIONS['PROTO_DPC'][0]='proro';

class proto {


	function proto($coordinator=null) {
	
	
	  echo $coordinator->env['ip'];
	}
	//problem!!!!! when activated
	/*function __sleep() {
	
	  echo "serialize\n";
	}
	
	function __wakeup() {
	
	  echo "unserialize\n";
	}*/

    function event($evn) {
	
       switch ($evn) {		
          case "proto"   : break;	   	  
       }	
	}	

	function action($act) {
	
      switch ($act) {	
	    
          case "proto"    : $out = '!!!proto!!!'; break;  								
      }		
	    
	  return ($out);
	}
	
	function iam() {
	
	  return 'my name is proto';
	}	
	
};
}
?>