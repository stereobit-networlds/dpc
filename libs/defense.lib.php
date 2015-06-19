<?php

$__DPCSEC['DEFENSE_DPC']='1;1;1;1;1;1;1;1;1';

if ((!defined("DEFENSE_DPC")) && (seclevel('DEFENSE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("DEFENSE_DPC",true);

$__DPC['DEFENSE_DPC'] = 'defense';

	/**
	* Definition of Trigger Words
	*/
	define("TRIGGER1", "GET \/default\.ida\?NNNNNN" ); /* CodeRed I  */
	define("TRIGGER2", "GET \/default\.ida\?XXXXXX" ); /* CodeRed II */
	define("TRIGGER3", "GET \/scripts\/root\.exe" ); /* Nimda */
//	define("TRIGGER4", "" ); /* W32.Klez */

class defense {  

   var $userLeveID;

   function defense() {	    
	   $UserSecID = GetGlobal("UserSecID");
	   $__USERAGENT = GetGlobal("__USERAGENT");
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);

	   if ($this->urlattack()) {
		  die($_SERVER['REMOTE_ADDR'].' get out from here!');
	   }
	   
       if ($offset>0) {
	   
	    //echo $offset,"<><><"; 
	   
        switch ($__USERAGENT) {
		  case 'WAP'  :
		               break;			
		  case 'PDA'  :
		  case 'HDML' :  			   		   		   
		  case 'HTML' :
					   break;
		  case 'PCLI' :
		  case 'TEXT' :					   
					   break;
        }	   
       }  	    
	   
   }  

   function event($event=null) {
   }
   
   function action($action=null) {
	  
	  return (null);    
   }    
  	
   function urlattack() {
   
      //print_r($_REQUEST);
	  
      foreach ($_REQUEST as $param=>$val) {
	    if ((stristr($val,TRIGGER1)) ||(stristr($param,TRIGGER1)))
		 return true;
	  }
	  return false;
   }	 
  
};
}
?>