<?php
if (defined("DATABASE_DPC")) {

$__DPCSEC['DATAQUERY_DPC']='8;1;1;1;1;1;1;8;9'; //combine webos users with db sys or common users

if ((!defined("DATAQUERY_DPC")) && (seclevel('DATAQUERY_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("DATAQUERY_DPC",true);

$__DPC['DATAQUERY_DPC'] = 'dataquery';

$__EVENTS['DATAQUERY_DPC'][0]= "select";
$__EVENTS['DATAQUERY_DPC'][1]= "insert";
$__EVENTS['DATAQUERY_DPC'][2]= "update";
$__EVENTS['DATAQUERY_DPC'][3]= "delete";
$__EVENTS['DATAQUERY_DPC'][4]= "use";

$__ACTIONS['DATAQUERY_DPC'][0]= 'select';
$__ACTIONS['DATAQUERY_DPC'][1]= "insert";
$__ACTIONS['DATAQUERY_DPC'][2]= "update";
$__ACTIONS['DATAQUERY_DPC'][3]= "delete";
$__ACTIONS['DATAQUERY_DPC'][4]= "use";

GetGlobal('controller')->set_command('select',null,__FILE__);

class dataquery  {

   private $userLevelID;
   public  $message;
   private $result;
   private $db;

   function __construct() {
	    $UserSecID = GetGlobal('UserSecID');

        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
		$this->message = null;	  
		$this->result = null;		
		
        $this->db = GetGlobal('db');
   }
   
   function event($sAction) {
    
   	   $param1 = GetGlobal('param1'); 
   	   $param2 = GetGlobal('param2'); 	   
   
	   switch ($sAction) {
						
		     case "select" : $this->e_select(); break;
		     case "insert" : $this->e_insert(); break;	 						
		     case "update" : $this->e_update(); break;			 
		     case "delete" : $this->e_delete(); break;		
 		     case "use"    : $this->e_use(); break;	 
       }
   }
   
   function action($sAction) {
        $__USERAGENT = GetGlobal('__USERAGENT');
		 
		switch ($sAction) {   
   
		   case "select"           :  switch ($__USERAGENT) {
	                                       case 'HTML' :  
	                                       case 'GTK'  : 
										   case 'SH'   : 
										   case 'CLI'  :  $out = $this->a_select();
	                                       case 'TEXT' :  break;											   
	                                   }
		                               break;	
		   case "insert"           :  switch ($__USERAGENT) {
	                                       case 'HTML' :  
	                                       case 'GTK'  :  
										   case 'SH'   :
										   case 'CLI'  :  $out = $this->a_insert();
	                                       case 'TEXT' :  break;											   
	                                   }
		                               break;	
		   case "update"           :  switch ($__USERAGENT) {
	                                       case 'HTML' :  
	                                       case 'GTK'  :  
										   case 'SH'   :
										   case 'CLI'  :  $out = $this->a_update();
	                                       case 'TEXT' :  break;											   
	                                   }
		                               break;	
		   case "delete"           :  switch ($__USERAGENT) {
	                                       case 'HTML' :  
	                                       case 'GTK'  :  
										   case 'SH'   :
										   case 'CLI'  :  $out = $this->a_delete();
	                                       case 'TEXT' :  break;											   
	                                   }
		                               break;										   									   									   
									   
		   case "use"              :  $out = $this->a_use(); break;									   									   
		}					

        return ($out);   
   }
   
   function e_use() {
       $mydb = GetGlobal('param1');
	      
       $this->db = GetGlobal($mydb);
   }
   
   function a_use() {
       $mydb = GetGlobal('param1');
	    
       $ret = "database changed to " . $mydb . "\n";
	   
	   return ($ret);
   }
         
   function e_select() {
   
        //$db = $this->db;//GetGlobal('db');
        $sql = GetGlobal('cmdline');
		
		if (is_object($this->db)) 		
		  $this->result = $this->db->Execute($sql);
		else
		  $this->result = null;   
   }  
   
   function a_select() {
        
        //$db = $this->db;//GetGlobal('db');		
		//var_dump($this->result);
		//var_dump($this->db);
		
		if (is_object($this->db)) {
		
		if (($this->result)) {
		
		  //$ret = $db->model . "\n";
			
          if ($this->db->model=='ADODB') {		   
	   
            while(!$this->result->EOF) {
			
			  $maxf = count($this->result->fields);
			  $ln = '';
			  for($i=0;$i<$maxf;$i++)
			    $ln .= sprintf("%-3s|",$this->result->fields[$i]);
							
			  $ret .= $this->ln . "\n";
			  
			  $this->result->MoveNext();
			}	
		  }
		  else {
		  	
            while ($record = $this->db->fetch_array($this->result)) {	
			  
			  $maxf = count($record);
			  $ln = '';
			  for($i=0;$i<$maxf;$i++)
			    $ln .= sprintf("%-3s|",$record[$i]);
				
			  $ret .= $ln ."\n";
		    }
		  }				
		}
		else
		  $ret = "Empty table or bad command.\n";
		}
		else
		  $ret = "Please use a database....\n";  
		
		return ($ret);
   }
   
   function e_insert() {
   
        //$db = $this->db;//GetGlobal('db');
        $sql = GetGlobal('cmdline');
		
		if (is_object($this->db)) 		
		  $this->result = $this->db->Execute($sql,1);
		else
		  $this->result = null;   
   } 
   
   function a_insert() { 
   
		if (is_object($this->db)) {
		
		  if (($this->result)) { 
		    $ret = $this->result . " record inserted";
	 	  }
		  else
		    $ret = "Empty table or bad command.\n";
		}
		else
		  $ret = "Please use a database....\n"; 		  
   } 
   
   function e_update() {
   
        //$db = $this->db;//GetGlobal('db');
        $sql = GetGlobal('cmdline');
		
		if (is_object($this->db)) 		
		  $this->result = $this->db->Execute($sql,1);
		else
		  $this->result = null;   
   } 
   
   function a_update() { 
   
		if (is_object($this->db)) {
		
		  if (($this->result)) { 
		    $ret = $this->result . " record updated";
	 	  }
		  else
		    $ret = "Empty table or bad command.\n";
		}
		else
		  $ret = "Please use a database....\n"; 		  
   }       
   
   function e_delete() {
   
        //$db = $this->db;//GetGlobal('db');
        $sql = GetGlobal('cmdline');
		
		if (is_object($this->db)) 		
		  $this->result = $this->db->Execute($sql,1);
		else
		  $this->result = null;   
   } 
   
   function a_delete() { 
   
		if (is_object($this->db)) {
		
		  if (($this->result)) { 
		    $ret = $this->result . " record deleted";
	 	  }
		  else
		    $ret = "Empty table or bad command.\n";
		}
		else
		  $ret = "Please use a database....\n"; 		  
   }     
      
   function __destruct() {
   }
   
};
}   
}
else die("DATABASE DPC REQUIRED! (" . __FILE__ . ")");
?>