<?php

$__DPCSEC['REPLICATOR_DPC']='2;1;1;1;1;1;1;1;9';
$__DPCSEC['SYSREP_']='2;1;1;1;1;1;1;2;9';

if ((!defined("REPLICATOR_DPC")) && (seclevel('REPLICATOR_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("REPLICATOR_DPC",true);

$__DPC['REPLICATOR_DPC'] = 'replicator';

$__EVENTS['REPLICATOR_DPC'][0]='replicate';

$__ACTIONS['REPLICATOR_DPC'][0]='replicate';

//require_once("ftp.lib.php");
GetGlobal('controller')->include_dpc('replicator/ftp.lib.php');

class replicator {

     var $UserSecID;
	 var $servers;
	 var $unames;
	 var $pwds;
	 
	 var $ftp_connection;
	 var $connected;
	 
	 var $webos_dir;
	 var $projects_dir;
	 var $dpc_dir;
	 var $this_dir; 
     var $gtk_dir;
     var $bin_dir;
	 var $jscript_dir;
	 var $cache_dir;
	 var $db_dir; 
	 var $images_dir;
	 
	 var $projectname;
	 
	 var $mode;

     function replicator() {
	    $UserSecID = GetGlobal('UserSecID');

        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);

        $this->servers = arrayload("REPLICATOR",'serverslist');//print_r($this->servers);
        $this->unames = arrayload('REPLICATOR',"userslist");//print_r($this->unames);
        $this->pwds = arrayload('REPLICATOR',"pwdslist");	
			
		$this->connected = array();
		
		$this->projectname = paramload('REPLICATOR','owner');
		
		$this->webos_dir = "../../../";
		$this->projects_dir = "../../";		
		$this->this_dir = "../../". $this->projectname;
		$this->dpc_dir = "../../../dpc";
		$this->public_dir = "../public";
		$this->cache_dir = "../cache";				
		$this->db_dir = "../databases";		
		$this->images_dir = "../images";				
		$this->themes_dir = "../themes/";	
		$this->gtk_dir = "../../../gtk";
		$this->bin_dir = "../../../bin";
		$this->jscript_dir = "../../../javascripts";		
		
		$this->mode = paramload('REPLICATOR','mode'); //'FULL'; DIFF=differencial / FULL = full	
	 }
	 
     function event($sAction) {
	 
	   echo "\nStart replication at " . implode(",",$this->servers);	 
	   $this->connect();
	   //echo "\nPath synchronization at " . implode(",",$this->servers);	 
	   //$this->setcurrentpath();
 	 }
	
	 function action($act) {   
	   $param1 = GetGlobal('param1');
	   $param2 = GetGlobal('param2');
	   
	   switch ($param1) {
	      case 'all'    : //$this->setcurrentpath('dpc'); webos root
		                  $this->replicate($this->mode,$this->webos_dir);
						  break;
	      case 'dpc'    : if ($param2) {//SPECIFIC DPC
		                    $this->setcurrentpath('dpc/'.$param2);
		                    $this->replicate($this->mode,$this->dpc_dir."/".$param2);	
						  }
						  else {//ALL DPC's'
		                    $this->setcurrentpath('dpc');
		                    $this->replicate($this->mode,$this->dpc_dir);							  
						  }
						  break;	  
	      case 'gtk'    : $this->setcurrentpath('gtk');
		                  $this->replicate($this->mode,$this->gtk_dir);	
						  break;	
	      case 'bin'    : $this->setcurrentpath('bin');
		                  $this->replicate($this->mode,$this->bin_dir);	
						  break;	
	      case 'public' : $this->setcurrentpath("projects/".$this->projectname."/public");
		                  $this->replicate($this->mode,$this->public_dir);	
						  break;		  
	      case 'cache'  : $this->setcurrentpath("projects/".$this->projectname."/cache");
		                  $this->replicate($this->mode,$this->cache_dir);	
						  break;							  
	      case 'db'     : if ($param2) {//SPECIFIC DB
						    $this->setcurrentpath("projects/".$this->projectname."/databases/".$param2);
		                    $this->replicate($this->mode,$this->db_dir."/".$param2);		  
		                  }
						  else {//ALL DATABASE DIR
						    $this->setcurrentpath("projects/".$this->projectname."/databases");
		                    $this->replicate($this->mode,$this->db_dir);	
						  }	
						  break;						  
	      case 'images' : $this->setcurrentpath("projects/".$this->projectname."/images");
		                  $this->replicate($this->mode,$this->images_dir);	
						  break;							  
	      case 'this'   : $this->setcurrentpath("projects/".$this->projectname);
		                  $this->replicate($this->mode,$this->this_dir);	
						  break;			  
	      case 'project': $this->setcurrentpath("projects/".$param2);
		                  $this->replicate($this->mode,$this->projects_dir.$param2);	
						  break;		  		  
	      case 'projects': $this->setcurrentpath("projects/");
		                  $this->replicate($this->mode,$this->projects_dir);	
						  break;			
	      case 'theme'  : if ($param2) {
		                    $this->setcurrentpath("projects/".$this->projectname."/themes/".$param2.".theme");
		                    $this->replicate($this->mode,$this->themes_dir.$param2.".theme");	
						  }	
						  break;
	      case 'jscript': $this->setcurrentpath('javascripts');
		                  $this->replicate($this->mode,$this->jscript_dir);	
						  break;						  							  				  
		  default       : $this->setcurrentpath(); //=public
		                  $this->replicate_file($param1);
	   }
	   
	   $this->disconnect();
	   $out = "\nEnd replication at " . implode(",",$this->servers);		   
	   
	   return ($out);
	 }

	 function connect() {	
	   echo "\nConnect...";
	   
	   reset($this->servers);
	   for ($i=0;$i<count($this->servers);$i++) {
	   
	      $ftp = new ftp();	
		  
	      if (($this->servers[$i]) && ($ftp->connect($this->servers[$i],$this->unames[$i],$this->pwds[$i]))) {
			    echo "\n" . $this->servers[$i] . " Ok!"; 
				$this->connected[$this->servers[$i]] = $ftp;
		  }
		  else {
		    echo "\n" . $this->servers[$i] . " Error!";
		  } 	
	   }
	   
	 }
	
	 function disconnect() {
	   echo "\nDisconnect...";	
	   
	   reset($this->connected);
	   foreach ($this->connected as $server=>$ftp) {
	      if ($ftp) {
	        $ftp->disconnect();
  	        echo "\n" . $server;
		  }
	   }
	 }
	 
	 function setcurrentpath($path='') {
	   echo "\nChange path...";	
	   
	   reset($this->connected);
	   //print_r($this->connected);
	   foreach ($this->connected as $server=>$ftp) {
	   
	      if ($path) {
		     if ($ftp->cd($path)) {
			    echo "\n" . $server . "=>" . "Current dir :".$ftp->currentdir();
			 }
		     else {
		        echo "\n$server=>Invalid path ($path)!";
			    $this->connected[$server] = null; //get out of list
			    echo "\n$server is out of replication list.";			  
		     }			 
		  }
	      else {
	      if ($ftp->cd("projects")) {
		    //echo "\n" . $server . "=>" . "Current dir :projects";
		    if ($ftp->cd($this->projectname)) {
  	          //echo "\n" . $server . "=>" . "Current dir :$this->projectname";
		      if ($ftp->cd("public")) {
		        echo "\n" . $server . "=>" . "Current dir :projects/$this->projectname/public";
			  }
		      else {
		        echo "\n$server=>Invalid path (public)!";
			    $this->connected[$server] = null; //get out of list
			    echo "\n$server is out of replication list.";				
		      }			  			  
			}
		    else {
		      echo "\n$server=>Invalid path ($this->projectname)!";
			  $this->connected[$server] = null; //get out of list
			  echo "\n$server is out of replication list.";			  
		    }			
		  }	
		  else {
		    echo "\n$server=>Invalid path (projects)!";
			$this->connected[$server] = null; //get out of list
			echo "\n$server is out of replication list.";
		  }
		  }//if path
	   }	     
	 }
	
	 function replicate($mode,$param='',$param2='') {
	
	   echo "\nReplicate...".$param;	
	   
	   reset($this->connected);
	   //print_r($this->connected);	   
	   foreach ($this->connected as $server=>$ftp) 
	      if ($ftp) {
		     $t_cpy = new ktimer;
	         $t_cpy->start('tcpy');
		     $ftp->copydir($param,".",1,$server,$mode); 
	         $t_cpy->stop('tcpy'); 
   	         echo "\n",$server,"=>Total time:",$t_cpy->value('tcpy');				 
		  }	 
	 }
	 
	 function replicate_file($param='') {
	
	   echo "\nReplicate...".$param;	
	   
	   if ($param) {
	      reset($this->connected);
	      foreach ($this->connected as $server=>$ftp) {
	   
	       if ($ftp->upload($param,$param)) {
  	         echo "\n" . $server . "=>" . "$param Ok!";
		   }	
		   else {
		     echo "\n" . $server . "=>" . "$param Failed!";
		   }
	     }
	   }	   
	 }	 

};
}
?>