<?php

$__DPCSEC['LOG_DPC']='1;1;1;1;1;1;1;1;1';

if ( (!defined("LOG_DPC")) && (seclevel('LOG_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("LOG_DPC",true);

$__DPC['LOG_DPC'] = '_log';

//require_once("log.lib.php");
//GetGlobal('controller')->include_dpc('log/log.lib.php');
$d = GetGlobal('controller')->require_dpc('log/log.lib.php');
require_once($d);

class _log { 

   var $userLeveID;
   
   var $clnlog;
   var $srvlog, $log_ison;

   function _log() {	 
	   $UserSecID = GetGlobal('UserSecID');
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
	   
	   $logpath = paramload('SHELL','prpath') . paramload('LOG','logpath');
	   $clientlogname = date("Y-m-d", time()) . paramload('LOG','extension');
	   $serverlogname = "server" . paramload('LOG','extension');
	   $this->log_ison = paramload('LOG','logison');	   
	   
           if ($this->log_ison) {
       $this->clnlog   = new Logger($logpath.$clientlogname, "500.000", 3,0,'plain');
       $this->srvlog   = new Logger($logpath.$serverlogname, "999.000", 3,0,'plain');//, "new");	 
          }   
   }  
   
   function event() {
   }

   function action() {      
   }	
   
   function writelog($srvline='',$clnline='',$leaveopen=0) {
   
       if (!$this->log_ison) return;
   
       $this->clnlog->p("C|".date("d-m-Y H:i:s", time())."|".$clnline);
	   $this->srvlog->p("S|".date("d-m-Y H:i:s", time())."|".$srvline);
	   
	   if (!$leaveopen) {
	     $this->clnlog->close();	   
	     $this->srvlog->close();
	   }	 
   } 
  
};
}
?>
