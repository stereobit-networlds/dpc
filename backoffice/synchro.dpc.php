<?php
if ((defined("DATABASE_DPC")) && 
    (defined("BACKOFFICE_DPC"))) {

$__DPCSEC['SYNCHRO_DPC']='9;1;1;1;1;1;1;1;9';

if ((!defined("SYNCHRO_DPC")) && (seclevel('SYNCHRO_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SYNCHRO_DPC",true);

$__DPC['SYNCHRO_DPC'] = 'synchronizer';
 
$__EVENTS['SYNCHRO_DPC'][0]="synchro";
$__EVENTS['SYNCHRO_DPC'][1]="recreateindex";

$__ACTIONS['SYNCHRO_DPC'][0]="synchro";
$__ACTIONS['SYNCHRO_DPC'][1]="recreateindex";


class synchronizer {
	
	var $userLevelID;
	var $message;
	var $counter;
	var $maxcount;
	
	var $db_con;
	
	var $ctable;
	var $cfkey;
	var $cselect;
	
	var $timeout;
	
	function synchonizer() {
	   $UserSecID = GetGlobal('UserSecID');
	
       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);
		  	   	   	   
	   $this->message = null;
	   $this->counter = 0;
	   $this->maxcount= 0;
	   	   
	   $this->timeout = paramload('SHELL','timeout');	echo ">>>",$this->timeout;
	   
	   $this->db_con = null;	     
	}
	

    function event($sAction) {
	   
	      switch ($sAction) {
             case "synchro"       : $this->access2mysql(1); break; 			
             case "recreateindex" : $this->recreate_index(); break; 			 
          }  
    }
  
    function action() {
	    $__USERAGENT = GetGlobal('__USERAGENT');	
		
        switch ($__USERAGENT) {
	         case 'HTML' : $out = $this->message; break;
	         case 'GTK'  : $out = $this->message; break;
	         case 'TEXT' : $out = $this->message; break;											   
	    }
								   
	    return ($out);
    }  
	
	function access_connect() {
	
       if (iniload('BACKOFFICE')) {
         $_Dbtype2   = paramload('BACKOFFICE','pdbtype');
         $_Dbname2   = paramload('BACKOFFICE','pdbname');
         $_User2     = paramload('BACKOFFICE','pdbuser');
         $_Password2 = paramload('BACKOFFICE','pdbpwd');
         $_Host2     = paramload('BACKOFFICE','pdbhost');  

         $this->db_con = ADONewConnection($_Dbtype2);
         $this->db_con->PConnect($_Host2, $_User2, $_Password2, $_Dbname2);
	   
	     return TRUE; 
       }
	   else
	     return FALSE;	
	}	
	
	function access2mysql($param1=1,$param2=0) {
	
	    $this->ctable = paramload('SYNCHRO','customtable'); //echo $this->ctable;	   
	    $this->cfkey = paramload('SYNCHRO','customfkey');//echo $this->cfkey;
	    $this->cselect = paramload('SYNCHRO','cselect');	//echo $this->cselect; 
	    $this->timeout = paramload('SHELL','timeout');			 	
	    
	    $this->message = "Start...\n";
        
		if ($this->access_connect()) {
		  $startup = $this->loadlog();			
		  if (isset($startup)) {
		    $this->create_access_fkey($startup,0);
		    $this->copy_access2mysql($startup,0);
		    $this->savelog();		
		  }
		}
		else    
		  $this->message = "Connection error...\n";
	}
	
    function copy_access2mysql($startnum=1,$endnum=0) {
	   $db = GetGlobal('db');	

	   if (isset($startnum)) {
         $this->rec_counter=0;
		 
	     $sSQL_1 = "select ". $this->cselect ." from ".$this->ctable." where RECID>=" . $startnum;
	     if ($endnum>0) $sSQL_1 .= " AND RecID<" . ($startnum+$endnum);	
		 $sSQL_1 .= " ORDER BY RECID"; 	 
	   
	     //print $sSQL_1;
		 
	     $mess = $sSQL_1;
	     $res = $this->db_con->Execute($sSQL_1);// print_r($res);
	     $countrec = $res->RecordCount();
	     $this->message .= "MS-Access Record(s) to copy : $countrec\n";

	     while (!$res->EOF) {

	       set_time_limit(10);		 
		 
           $sSQL_2 = "insert into users (first_name,last_name,username,password,vpassword,email,country_id,language_id," . 
                     "age,gender,notes,date_created,ip_insert,ip_update,last_login_date,security_param,session_id,security_level_id) values " .
                     "(" .
		             $db->qstr($res->fields[1]) . "," .
		             $db->qstr($res->fields[1]) . "," .
		             $db->qstr($res->fields[0]) . "," . //UNIQUE recid or afm or access code = username
		             $db->qstr($res->fields[1]) . "," .
		             $db->qstr($res->fields[1]) . "," .
		             $db->qstr($res->fields[2]) . "," .
		             "1,1,1,1,NULL,'2002-06-13 12:50:24',NULL,NULL,'2002-06-13 12:50:24',1,NULL,2)";

    	   $res2 = $db->Execute($sSQL_2); 
		   
		   $this->rec_counter++;
  
	       $res->MoveNext();
	     }
  	     set_time_limit($this->timeout);		 
		 
         $this->message .= "Record(s) copied : $this->rec_counter\n";

  	     setInfo($this->message);
	 
	     return $this->rec_counter;
	   }	 
   }

   function create_access_fkey($startnum=1,$endnum=0) {
	 $db = GetGlobal('db');	
	 
	 if (isset($startnum)) {
	 
       $this->rec_counter=0;
	 
	   //get max record num from mysql users
	   $sSQL = "select user_id from users";
	   $result = $db->Execute($sSQL);
	   $countofrecin = $result->RecordCount();
	   $this->message .= "MySql Current Max Counter : $countofrecin\n";
	 
	 
       $sSQL_1 = "select RecID from ". $this->ctable ." where RecID>=" . $startnum; //Ù_Ðåëáôþí
	   if ($endnum>0) $sSQL_1 .= " AND RecID<" . ($startnum+$endnum);
	   //$sSQL_1 .= " ORDER BY RecID";   	   
	   //print $sSQL_1;
	   $res = $this->db_con->Execute($sSQL_1); 
	   //print $res->RecordCount();
	   //print_r($res->fields);
       $reccounter = $res->RecordCount();	 
	   $this->message .= "MS-Access Total Record(s) To Synchronize : $reccounter\n";	 
	 
	   while ($this->rec_counter<$reccounter) {
	   
  	     set_time_limit(10);	   
	   
         $num = ++$countofrecin;
         $sSQL_2 = "update ". $this->ctable ." set " . $this->cfkey . "=" . $db->qstr($num) .
	  	           " where RecID=" . ($startnum+$this->rec_counter);
		//ÅÍÁËË_ÊÙÄÉÊ¼Ó		   
  	     $res2 = $this->db_con->Execute($sSQL_2);
	     $this->rec_counter++;		 
	   }
	   
       set_time_limit($this->timeout);	    
	   
       //update counter to save
	   $this->maxcount = ($startnum + $this->rec_counter);
	   $this->counter  = $this->rec_counter;
	   
	   $this->message .= "Total Synchronized Record(s) : $this->counter\n";		   
	   setInfo($this->message); 
	   	   
 	   return $this->counter;
	 }  
   }
   
   function savelog() {
       
	  $logfile = paramload('SHELL','prpath') . "synchro.log";
	  
	  $sdate = date('d/m/Y');
	  $stime = date('h:i:s A');
	  $logline = "$sdate;$stime;$this->counter;$this->maxcount;\n"; //save counter value as the last access record updated
	  	   
	  if ($fp = @fopen ($logfile , "a+")) {
               fwrite ($fp, $logline);
               fclose ($fp);
			   $this->message .= "Log file updated successfully !\n";							  
      }
      else {
               $this->message .= "Log file failed to update !\n";	
      }	   
   }
   
   function loadlog() {
	  $logfile = paramload('SHELL','prpath') . "synchro.log";
	  
	  if ($fp = @fopen ($logfile , "r")) {
               $recs = fread ($fp, filesize($logfile));
               fclose ($fp);	
			   
	           $logline = explode(";",$recs);
	           $clog = count($logline);
	  
	           $out = ($logline[$clog-2]);
   			   $this->message .= "Log file opened at $out!\n";
	  
	           return ($out);			   						  
      }

	  return 1; //start of database      
   }	
   
   
   
   //update foreign key at access 
   //usefull when a new copy of access overide customers table  
    function update_index($usergroup=2) { //2=trusted users
	   $db = GetGlobal('db');	
	 
	   //get max record num from mysql users
	   $sSQL = "SELECT user_id,username FROM users";
	   $sSQL .= " WHERE security_level_id=" . $db->qstr($usergroup);
	   $result = $db->Execute($sSQL);
	   $countofrecin = $result->RecordCount();
	   $this->message .= "MySql Total Index Counter : $countofrecin\n";
	   
	   //echo $sSQL,$countofrecin;	   
 
       $counter=0;		 
	   //while ($counter<$counbtofrecin) {
       while(!$result->EOF) {
	   
  	     set_time_limit(10);	   	   
	   
         $sSQL_2 = "update ". $this->ctable ." set " . $this->cfkey . "=" . $db->qstr($result->fields[0]) .
	  	           " where RecID=" . $result->fields[1];
   
  	     $res2 = $this->db_con->Execute($sSQL_2);
		 
		 //echo $sSQL_2,"\n";
		 
	     $result->MoveNext();		 
	     $counter+=1;	
		 //echo $counter,"+";	 
	   } 
	   set_time_limit($this->timeout);	   
	   
	   $this->message .= "Total Indexed Record(s) : $counter\n";		   
	   setInfo($this->message); 
	   //echo $this->message;	   
		   
 	   return ($counter);	
	}
   
 
	function recreate_index() {
	
	    $this->ctable = paramload('SYNCHRO','customtable'); //echo $this->ctable;	   
	    $this->cfkey = paramload('SYNCHRO','customfkey');//echo $this->cfkey;
	    $this->cselect = paramload('SYNCHRO','cselect');	//echo $this->cselect; 
 	    $this->timeout = paramload('SHELL','timeout');			 	
	    
	    $this->message = "Start indexing ...\n";
        
		if ($this->access_connect()) {
          $this->update_index();
		}
		else    
		  $this->message = "Connection error...\n";
	}   

};
}
}
else die("DATABASE DPC REQUIRED!\nBACKOFFICE DPC REQUIRED!");
?>