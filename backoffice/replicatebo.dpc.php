<?php
if (defined("BACKOFFICE_DPC")) {

$__DPCSEC['REPLICATEBO_DPC']='8;1;1;1;1;1;1;8;9';

if ((!defined("REPLICATEBO_DPC")) && (seclevel('REPLICATEBO_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("REPLICATEBO_DPC",true);

$__DPC['REPLICATEBO_DPC'] = 'replicatebo';

$__EVENTS['REPLICATEBO_DPC'][0]= "exportbo";
$__EVENTS['REPLICATEBO_DPC'][1]= "importbo";

$__ACTIONS['REPLICATEBO_DPC'][0]= "exportbo";
$__ACTIONS['REPLICATEBO_DPC'][1]= "importbo";

require_once("backoffice.dpc.php");

class replicatebo extends backoffice {

    var $userLevelID;
	var $rec_counter;
	var $message;
	var $exclud;
	var $includ;
	
	var $dbpath;
	
	var $db_con;
	var $timeout;

	function replicatebo() {
	   $UserSecID = GetGlobal('UserSecID');
		
        backoffice::backoffice();			

        $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
			
		$this->dbpath = paramload('SHELL','prpath') . paramload('SHELL','dbpath');
		$this->timeout = paramload('SHELL','timeout'); 
		
		$this->exclud = arrayload('REPLICATEBO','exclude');	
		$this->includ = arrayload('REPLICATEBO','include');			
				
		$this->rec_counter = 0;
		$this->message = null;			
	}
	
    function action($action) {
	    $__USERAGENT = GetGlobal('__USERAGENT');	
		
		 
		 switch ($action) {	 
 		   case "exportbo"          : 
		                              switch ($__USERAGENT) {
	                                       case 'HTML' : $out = $this->message; break;
	                                       case 'GTK'  : break;
										   case 'CLI'  :
	                                       case 'TEXT' : break;											   
	                                  } 
		                              break;		
 		   case "importbo"          : 
		                              switch ($__USERAGENT) {
	                                       case 'HTML' : $out = $this->message; break;
	                                       case 'GTK'  : break;
										   case 'CLI'  :
	                                       case 'TEXT' : break;											   
	                                  } 
		                              break;										  			
		 }							  

         return ($out);
    }
		
    function event($sAction) {  
	  $param1 = GetGlobal('param1');	//parameters by command line
	  $param2 = GetGlobal('param2');		   	   
   
 
	   switch ($sAction) {
						
		  case "exportbo" :  if ($this->backoffice_connect())
		                       $this->message = $this->export_database(); 
							 else  
							   $this->message = "No connection"; 
							 break;		 						
		  case "importbo" :  if ($this->backoffice_connect())
		                       $this->message = $this->import_database(); 
							 else  
							   $this->message = "No connection";							   
							 break;			  
       }
    }

	function export_database() {
	   
	   //print_r($db->MetaTables());
	   $msg = null;
	    
	   foreach ($this->db_con->MetaTables() as $id=>$table) {
	   
	     if (((is_array($this->exclud)) && (!in_array($table,$this->exclud))) ||
		     ((is_array($this->includ)) && (in_array($table,$this->includ)))) {
	       if ($this->exportxt($table)) $msg .= $table ." export Ok!<br/>";
		                           else $msg .= $table ." export Error!<br/>";
		 }						 
	   }
	   
	   return($msg);
	}	
	
	function import_database($append=false) {
	   
	   //print_r($db->MetaTables());
	   $msg = null;
	   	   
	   foreach ($this->db_con->MetaTables() as $id=>$table) {
	   
	     if (((is_array($this->exclud)) && (!in_array($table,$this->exclud))) ||
		     ((is_array($this->includ)) && (in_array($table,$this->includ)))) {  
	       if ($this->importxt($table,$append)) $msg .= $table ." import Ok!<br/>";
		                                   else $msg .= $table ." import Error!<br/>";
		 }								   
	   }
	   
	   
	   return($msg);
	}	
	
	function reset_db($tablename) {
		
		$sSQL = "delete from " . $tablename;
        $this->db_con->Execute($sSQL);	
	}
	
	
	function exportxt($tablename) {
	   
	   $file = $this->dbpath . $tablename . ".txt";
	   
	   $tfields = $this->get_MetaColumns($tablename);
	   $max = count($tfields);

       $sSQL = "select * from " . $tablename; //echo $sSQL;
	   $table = $this->db_con->Execute($sSQL);	
	
	   if ($table)  {
	   
	     //read data
         $i=1;
         while(!$table->EOF) {
		 
          set_time_limit(5);		 
         
		  $i=0;	  
		  $textdata=null;
		  foreach ($tfields as $f=>$prop) {
            $textdata .= trim($table->fields[$i]) . "<!>";
			$i+=1;
		  }	
		  $lines[] = $textdata;

          //print_r($table->fields);
	      $table->MoveNext();
		  $i+=1;
	     }
		 
		 set_timelimit($this->timeout);		   
	      
		 //write data
		 $fd = @fopen( $file, "w" );
         if ($fd) {
		   fwrite($fd, implode("\n",$lines));
		   fclose($fd);		
		  
	       return ($i);		     
         }
		 else
		   return (false); 
	   }

	   return (false);
	}	
	
	function importxt($tablename,$append=false) {

	   //read data	   	
	   $lines = file($this->dbpath . $tablename . ".txt");
	   
	   $tfields = $this->get_MetaColumns($tablename);
	   $max = count($tfields)-1;	   
	   
       if ($lines) {
	   
	     //reset current data
	     if (!$append) $this->reset_db($tablename);
		 
		 foreach ($lines as $recnum=>$rec) {
		 //if ($recnum<1) {//test
           set_time_limit(5);		 
		 
		   $recfields = explode("<!>",$rec);
		   
           $sSQL = "insert into [".$tablename."] (";
		   
		   $i=0;
		   foreach ($tfields as $f=>$prop) {
		     //if ($i<55) {//not the first field due to a auto rec
               $sSQL .= "[" . $f . "]";
			   if ($i<$max) $sSQL .= ",";
			 //}
			 $i+=1;			 
		   }
		   
		   $sSQL .= ") values (";
		   
		   $i=0;
		   foreach ($tfields as $f=>$prop) {
		     //if ($i<55) {//not the first field due to a auto rec
			 
			   //find types
			   if (!trim($recfields[$i])) $sSQL .= 'NULL';
			   else
               if (is_number($recfields[$i])) $sSQL .= $recfields[$i];
			   else
                 $sSQL .= $this->db_con->qstr($recfields[$i]);
				 
			   if ($i<$max) $sSQL .= ",";			 
			 //}  
			 $i+=1;
		   }	
		   
		   $sSQL .= ")";		   	    			   		 
				   
		   $res = $this->db_con->Execute($sSQL);				   
		  //}//test 
		 }	
		 //echo $sSQL;
		 set_time_limit($this->timeout);		 
		 	 
	     return (true);		     
       }
	   else
		 return (false); 	
	}	


};
}	
}
else die("BACKOFFICE DPC REQUIRED!");
?>