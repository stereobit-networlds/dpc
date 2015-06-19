<?php

$__DPCSEC['RCLOG_DPC']='1;1;1;1;1;1;1;1;1';

if ( (!defined("RCLOG_DPC")) && (seclevel('RCLOG_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("RCLOG_DPC",true);

$__DPC['RCLOG_DPC'] = 'rclog';

$d = GetGlobal('controller')->require_dpc('log/log.dpc.php');
require_once($d);

class rclog extends _log { 

   var $userLeveID;
   
   var $clnlog;
   var $srvlog;
   
   var $lpath, $log_ison;

   function rclog($application=null) {	 
   
	   if ($application) {
		  $this->lpath = paramload('SHELL','prpath') . paramload('LOG','logpath') . $application; 	
		
		  if (!is_dir($this->lpath))
		    mkdir($this->lpath);
		  
		  $this->lpath .= "/";	
	   }	  
	   else
		  $this->lpath = paramload('SHELL','prpath') . paramload('LOG','logpath');
	   
	   //echo $this->lpath;
	   
	   $logpath = $this->lpath;
	   $clientlogname = 'dpc_' . date("Y-m-d", time()) . paramload('LOG','extension');
	   $serverlogname = "dpc_server" . paramload('LOG','extension');
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
   
   //overwrite
   function writelog($srvline='',$clnline='',$leaveopen=0) {
   
       if (!$this->log_ison) return;   
   
	   $myaction = GetGlobal('controller')->calldpc_var("pcntl.myaction");
	   $myagent = GetGlobal('controller')->calldpc_var("pcntl.agent");
	
	   $srvline = $agent . "|" . 
		         $_SERVER['REQUEST_METHOD'] . "|" . 
				 $_SERVER['HTTP_HOST'] . "|" . 
				 /*$this->t_shell->value('shell') .*/ "|" . 
				 $myaction . "|";
				 
	   $clnline = $agent . "|" . 
		         $_SERVER['REMOTE_ADDR'] . "|" . 
				 $_SERVER['REMOTE_HOST'] . "|" . 
				 $_SERVER['HTTP_USER_AGENT'] . "|" . 
				 $this->userLevelID . "|" . 
				 /*$this->t_shell->value('shell') .*/ "|" . 
				 $myaction . "|";	   
   
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
