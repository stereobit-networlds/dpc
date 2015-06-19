<?php
if (!defined("SENAGN_DPC")) { //&& (seclevel('BACKOFFICE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SENAGN_DPC",true);

$__DPC['SENAGN_DPC'] = 'senagn';

//echo getcwd();
require_once("adodb/adodb.ext.php"); ///local requirement .../bin directory


class senagn {


	var $sen_db;

	function __construct($env=null) {
	
	  $this->env = $env; 
	  $this->classname = get_class($this);
	  
	  $this->env->gtkds_conn->set_async_code("
		   \$lbl = new GtkButton($this->classname);
		   \$lbl->set_name($this->classname);	
		   //\$lbl->set_usize(25,25);
		   \$lbl->connect_object(\"clicked\", array(\$this, \"event_queue\"),\"sqlite_connect\",\"senagn\");
		   //\$window->add(\$lbl);
		   \$this->agentbox->pack_start(\$lbl,false);
		   \$window->show_all();
	   ");			  
    }
	
	function sen_connect() {
	   
  	   
      $this->sen_db = ADONewConnection("odbc_oracle");
      $isconnected = $this->sen_db->PConnect("SEN", "ii_usr", "usr_vk7dp", "SEN_DB");			 
			  
      //$this->sen_db = $this->oracle_native();
					
	  $sen_db = &$this->sen_db; 	
	  //echo get_resource_type($this->sen_db),">>>>>\n";
	  $this->env->get_agent('resources')->set_resource('sen_db',$this->sen_db);
	  $this->env->get_agent('resources')->set_resource('sen_con',$this->sen_db->_connectionID);
	  //print_r($this->sen_db);
	  //print_r($this->sen_db->_connectionID); 				  
	  //print_r($this->env->get_agent('resources')->_resources);	 
	  return ($isconnected); 

	}	
	
	function &sqlite_connect($return=false) {
	
	  $db = '//sen-srv/downloads2/sqlitetest.db';
	
      $this->sqlite_db = new SQLiteDatabase($db, 0666, $sqliteerror);				  
	  if ($sqliteerror) echo 'ERROR:',$sqliteerror,'\n';	
	  $this->env->get_agent('resources')->set_resource('sqlite_connection',$this->sqlite_db);
	  $this->env->get_agent('resources')->set_resource('sqlite_name',$db);
	  print_r($this->env->get_agent('resources')->_resources);	
	  print_r($this->env->get_agent('resources')->_resptr);	
	  
	  if ($return)
	    return ($this->sqlite_db);
	}
	
	function oracle_native() {
	
       putenv("ORACLE_SID=SEN");
       putenv("ORACLE_HOME=c:/ora92");
       putenv("TNS_ADMIN=c:/ora92/network/admin");	
	
	   //oracle 
       //$ora_conn = ora_logon("i_usr@SEN_DB","usr_vk7dp");
	   //oci8
       $db = "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = SEN-SRV)(PORT = 1521)) ) (CONNECT_DATA = (SID = SEN) ) )"; 
       //$ora_con = OCILogOn("s01001", "s01001",$db);
	   $ora_con = OCI_connect("s01001", "s01001",$db);
	   
       if (!$ora_con) {
         echo "Connection failed";
         echo "Error Message: [" . OCIError($ora_con) . "]";
         //exit;
       }
       else {
         echo "Native Connected!";
       }	    	   
    	
	}	
	
	function test_connection() {
 
	   if (!$this->sen_db) {  
	     //$i++; echo $i . ">>>>>>>>>>>>";
	     
		 if ($this->sen_connect()) {
		   $ret = 'SEN_DB:CONNECTED'."\n"; 
           //$this->get_MetaTable();	

		   //TEST
	       //$this->sen_test_select(); 		 
		   //$this->oracle_native();
		 }
		 else 
	       $ret = 'SEN_DB:NO CONNECTION'."\n";  
	   }	
	   else
	     $ret = 'DISCONNECTED'."\n";	
		 
	   return $ret;	 
	}
	
	function sen_test_select() {
	
	   if (!$this->sen_db) {
	   
	      $ret = "Invalid Database handler\n";
		  return ($ret);
	   }
	     
	
	   //$sSQL = 'select * from PDC_SDB';
	   $sSQL = "SELECT CODCODE,ITMNAME FROM PANIK_VIEW_EIDH WHERE CODCODE='50000'";
       //$sSQL = "SELECT LEEID,LEENAME FROM PANIK_VIEW_LEE_2 WHERE LEEID='12422'";	   
	   //$Ssql = "SELECT EPONYMIA_PELATH,EPAGGELMA,ADRSTREET,CODE_PELATH,ADRSTREET,CODE_PELATH,CODE_PELATH,KATIGORIA,TELEPHONE1,ADRSTREET FROM PANIK_VIEW_USERS WHERE LEEID=12422";
	   $ret =  $sSQL."\n";	   
	   
	   $result = $this->sen_db->Execute($sSQL);
	   //print_r($result);
	   //$arr = $result->GetArray();
	   //print_r($arr);
	   
	   $i=0;
	   while(!$result->EOF) {
	     $ret .= "\n".$result->fields[1];
		 $result->MoveNext();
		 $i+=1;
	   }
	   //echo '>>>',$this->sen_db->ErrorMsg();
	   
	   return ($ret);
	}
	
	/*function __sleep() {
	   echo "sleep:\n";
	   if ($this->sen_db)
         $this->env->get_agent('resources')->set_resource('sen_db2',$this->sen_db);	
	}*/
	
	function /*__*/wakeup() {
	
	   static $i = 0;
	  
	   echo "wakeup:$i\n";
	   //if (!$this->sen_db) 
	   	   
       //$isconnected = $this->sen_db->PConnect("SEN", "ii_usr", "usr_vk7dp", "SEN_DB");		
	   //$status = $this->test_connection();
	   //echo $status;
	  /* if ($i>0) {//after 1st transmition from server
	   if (!$this->sen_db || !is_object($this->sen_db)) {
	      
         //$this->sen_db = ADONewConnection("odbc_oracle");
         $isconnected = $this->sen_db->PConnect("SEN", "ii_usr", "usr_vk7dp", "SEN_DB");		   
		 //print_r($this);
	   }
	   }
	   else
	     $i++;*/
		 
	   //$this->sen_db = $this->env->get_agent('resources')->get_resource('sen_db2');	 
	   
	   if (!$this->sen_db->_connectionID) {
	     $this->sen_db = ADONewConnection("odbc_oracle");
	     $isconnected = $this->sen_db->PConnect("SEN", "ii_usr", "usr_vk7dp", "SEN_DB");
		 echo $isconnected,"ONNN\n";
		 //print_r($this->sen_db);
	     //$this->env->get_agent('resources')->set_resource('sen_db',$this->sen_db);		 
	   }
	   else
	     echo "CONN\n";
	   
	   	 
	}	
	
	function destroy() {
	
		$this->env->gtkds_conn->set_async_code("
		foreach (\$this->agentbox->children() as \$num=>\$child) {
		   if (\$child->get_name()==$this->classname) \$this->agentbox->remove(\$child);
		}	
	    ");		
	}		
	
	function __destruct() {
	}	
	
};
}
?>