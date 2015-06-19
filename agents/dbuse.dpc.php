<?php
if (!defined("DBUSE_DPC")) { //&& (seclevel('BACKOFFICE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("DBUSE_DPC",true);

$__DPC['DBUSE_DPC'] = 'dbuse';



class dbuse {


	var $sen_db;

	function __construct($env=null) {
	
	  $this->env = $env; 
	  $this->classname = get_class($this);
	  
	  $this->env->gtkds_conn->set_async_code("
		   \$lbl = new GtkButton($this->classname);
		   \$lbl->set_name($this->classname);	
		   \$lbl->connect_object(\"clicked\", array(\$this, \"event_queue\"),\"sqlite_test\",\"dbuse\");
		   \$this->agentbox->pack_start(\$lbl,false);
		   \$window->show_all();
	   ");			  
    }
	
	function sen_test_select() {
	
	   $this->sen_db = //unserialize(
	     $this->env->get_agent('resources')->get_resource('sen_db');
	   //);
	   //print_r($this->env->get_agent('resources')->_resources);	 
	   $this->sen_db->_connectionID = $this->env->get_agent('resources')->get_resource('odbc_link_persistent');
	   print_r($this->sen_db);
	   //print_r($this->sen_db->_connectionID);
	   echo ">>>\n";
	
	   if (!$this->sen_db) {
	   
	      $ret = "Invalid Database handler\n";
		  return ($ret);
	   }
	     
	
	   //$sSQL = 'select * from PDC_SDB';
	   $sSQL = "SELECT CODCODE,ITMNAME FROM PANIK_VIEW_EIDH WHERE CODCODE='50000'";
       //$sSQL = "SELECT LEEID,LEENAME FROM PANIK_VIEW_LEE_2 WHERE LEEID='12422'";	   
	   //$Ssql = "SELECT EPONYMIA_PELATH,EPAGGELMA,ADRSTREET,CODE_PELATH,ADRSTREET,CODE_PELATH,CODE_PELATH,KATIGORIA,TELEPHONE1,ADRSTREET FROM PANIK_VIEW_USERS WHERE LEEID=12422";
	   $ret =  $sSQL."\n";	  
	   echo $sSQL,">>>\n"; 
	   
	   $result = $this->sen_db->Execute($sSQL);
	   print_r($result);
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
	
	function sqlite_test() {
	
	     //auto
		 try {
           $db = $this->env->get_agent('resources')->get_resource('sqlite');	   
		 } 
		 catch (Exception $e) {
           echo 'Caught exception: ',  $e->getMessage(), "\n"; 		   
         }	
		 
		 /*if (!$db = $this->env->get_agent('resources')->get_resource('sqlite')) {//try 1
		 	$db = $this->env->get_agent('senagn')->sqlite_connect(true); //try 2
		 }*/
		  	 
		 /*if (!$db) {
		   try {
		     $db = $this->env->get_agent('senagn')->sqlite_connect(true);
		   }
           catch (Exception $e1) {
             echo 'Caught exception 2: ',  $e1->getMessage(), "\n";	
			 return;	   
		   }			 
		 }*/
		 //$db = $this->env->get_agent('resources')->get_resource('sqlite_connection');//NOT WORK!!!!
		 //echo $db,'>>>>>>';
		 //manual (call another's agent method)
		 //if (!$db) //remove db->get agent sqlite rssource below!!!
		   //$db = $this->env->get_agent('senagn')->sqlite_connect();
		   
		 $sSQL = 'select * from abcproducts where p_id=3';
	     echo $sSQL;
	   
	     $resultset = $db->unbufferedQuery($sSQL);
	     while ($rec = $resultset->fetch()) 
		   $ret[] = $rec; 
	      	   
	     $this->result = $ret; 
		 print_r($ret);
		 
		 //test GTK...stop here until exit from main!!!!! 
		 //SYNCHRONUS CODE
         //new EntryCompletion();
         //Gtk::main();			 
		 
		 //ASYNCHRONUS CODE 
		 //always call gtkds as in this class ($this->env->gtkds_conn)		 
		 $this->env->gtkds_conn->set_async_code('
           new EntryCompletion();
           Gtk::main();
		   $this->gtkds_conn->set_async_data(\'test\');
		   //$lbl = new GtkLabel(\'Clock\');
		   //$window->add($lbl);
		   //$window->show_all();
		 ');	 	
	}	
	
	function sqlite_data() {
	
	  echo $this->env->gtkds_conn->get_async_data();
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