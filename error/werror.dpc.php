<?php

$__DPCSEC['WERROR_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("WERROR_DPC")) && (seclevel('WERROR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("WERROR_DPC",true);

$__DPC['WERROR_DPC'] = 'werror';

$__EVENTS['WERROR_DPC'][0]='werror';

$__ACTIONS['WERROR_DPC'][0]='werror';

//$__EXCLUSIONS['WERROR_DPC'][0] = 'werror';

class werror {  

   var $userLeveID;
   
   var $action;
   var $error_codes;

   function werror() {	 
	   $UserSecID = GetGlobal('UserSecID');
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0); 
	   
	   $this->error_codes			= array(400 => "Bad Request",
											401 => "Unauthorized",
											402 => "Payment Required",
											403 => "Forbidden",
											404 => "Not Found",
											405 => "Not Allowed",
											406 => "Not Acceptable",
											407 => "Registration Required",
											408 => "Request Timeout",
											409 => "Conflict",
											500 => "Internal Server Error",
											501 => "Not Implemented",
											502 => "Remove Server Error",
											503 => "Service Unavailable",
											504 => "Remove Server Timeout",
											510 => "Disconnected");	   
	   
   }  

   function event($event=null) {
   }
   
   function action($action=null) {
	  $a = GetReq('a');
	  $g = GetReq('g');

      $msg = "<H2>" . $this->error_codes[$a] . "</H2>";   
      $msg .= "<H3>Web server countered an error during operation.<br />Please check your entry or try later.</H3>";
	   
	  $out = setnavigator("Error");   
		 
      $win = new window("Error:".$a,$msg);
	  $out .= $win->render();
	  unset ($win);
	  
	  return ($out);    
   
   }    
  	 
  
};
}
?>