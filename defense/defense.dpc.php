<?php

if ((!defined("DEFENSE_DPC"))) {
define("DEFENSE_DPC",true);

$__DPC['DEFENSE_DPC'] = 'defense';

	/**
	* Definition of Trigger Words
	*/
	define("TRIGGER1", "GET \/default\.ida\?NNNNNN" ); /* CodeRed I  */
	define("TRIGGER2", "GET \/default.ida\?XXXXXX" ); /* CodeRed II */
	define("TRIGGER3", "GET \/scripts\/root\.exe" ); /* Nimda */
//	define("TRIGGER4", "" ); /* W32.Klez */

class defense {  

   var $userLeveID;

   function defense() {
	   $UserSecID = GetGlobal('UserSecID');
	   $__USERAGENT = GetGlobal('__USERAGENT');	   
	   
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);

	   if (($this->urlattack()) || ($this->is_resticted())) {
        switch ($__USERAGENT) {
		  case 'WAP'  :
		               break;			
		  case 'PCLI' :
		  case 'TEXT' :die($_SERVER['REMOTE_ADDR']. " access denied!\n");					   
					   break;					   
		  case 'PDA'  :
		  case 'HDML' :  			   		   		   
		  case 'HTML' :
		  		       die('<H1>'.$_SERVER['REMOTE_ADDR'].
		                   ' has been reported as a malicious ip . Access resticted!!'.
			               '</H1>'.
			               '<H5><br>'.'Please contact webmaster for further informations.</H5>');
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
   
      $uri = $_SERVER['REQUEST_URI'];
	  $query = $_SERVER['QUERY_STRING'];
	  
	  if ((strstr($uri,TRIGGER1)) || 
	      (strstr($uri,TRIGGER2))) {
		   $this->save_ip();
		   return true;	  
	  }
	  
	  
      //print_r($_REQUEST);
	  
      foreach ($_REQUEST as $param=>$val) {
	    if (((stristr($val,TRIGGER1)) ||(stristr($param,TRIGGER1))) ||
		    ((stristr($val,TRIGGER2)) ||(stristr($param,TRIGGER2)))) {
		
		   $this->save_ip();
		   return true;
		} 
	  }
	  return false;
   }
   
   function is_resticted() {
   
      $ips = $this->load_ip();
	  
	  if (in_array($_SERVER['REMOTE_ADDR'],$ips)) return true;
	  
	  return false;
   }
   
   
   function save_ip() {
       
	  $logfile = paramload('SHELL','prpath') . "iprestict.log";
	  $remote_ip = $_SERVER['REMOTE_ADDR'];
	  
	  $sdate = date('d/m/Y');
	  $stime = date('h:i:s A');
	  $logline = "$sdate;$stime;$remote_ip\n"; //save counter value as the last access record updated
	  	   
	  if ($fp = @fopen ($logfile , "a+")) {
               fwrite ($fp, $logline);
               fclose ($fp);
			   $message .= "Log file updated successfully !\n";							  
      }
      else {
               $message .= "Log file failed to update !\n";	
      }	
	  setInfo($message);	     
   }
   
   function load_ip() {
	  $logfile = paramload('SHELL','prpath') . "iprestict.log";
	  
	  if ($fp = @fopen ($logfile , "r")) {
               $recs = fread ($fp, filesize($logfile));
               fclose ($fp);	
			   
	           $line = explode("\n",$recs);
			   
			   foreach ($line as $rec=>$val) {
	             $rec = explode(";",$val);
				 $ip[] = $rec[2];
			   } 	
			   //print_r($ip); 
	           return ($ip);			   						  
      }
	   
	  return 1;       
   }	   	 
  
};
}
?>