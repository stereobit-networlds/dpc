<?php

$__DPCSEC['SEN_DPC']='2;2;2;2;2;2;2;2;9';

if (!defined("SEN_DPC")) { //&& (seclevel('BACKOFFICE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("SEN_DPC",true);

$__DPC['SEN_DPC'] = 'sen';


class sen {

	var $userLevelID;

	var $table_id;	
	var $table_alias;
	var $table_name;
	
	var $cachequeries;
	var $cachetime;	
	
	var $sen_db;
	var $deflan;

	function sen() {
	   //static $i;
	   $UserSecID = GetGlobal('UserSecID');   
	   $sen_db = GetGlobal('sen_db'); 	    

       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);

	   $this->cachequeries = paramload('SEN','cacheq');
	   $this->cachetime = paramload('SEN','qcachetime');
	   
	   $this->table_id = arrayload('SEN','tableid'); //print_r($this->table_id);
	   $this->table_name = arrayload('SEN','tablename'); //print_r($this->table_name);
	   $this->table_alias = arrayload('SEN','tablealias');	   	   
	   
	   $this->deflan = paramload('SHELL','dlang');//2=greek where is the native lan for this functions	   
	   
	   $this->sen_db = &$sen_db;	
 
	   if (!$this->sen_db) {  
	     //$i++; echo $i . ">>>>>>>>>>>>";
	     
		 if ($this->sen_connect()) {
		   _echo('CLI','SEN_DB:CONNECTED'); 
           //$this->get_MetaTable();	

		   //TEST
	       //$this->sen_test_select(); 		 
		   //$this->oracle_native();
		 }
		 else 
	       echo 'SEN_DB:NO CONNECTION';  
	   }	
	   //must always read metatables
       $this->get_MetaTable();		   	   	 
	   
	}
	
	function sen_connect() {
	   $sen_db = GetGlobal('sen_db');	   
	   //static $i;
	   
       if (iniload('SEN')) {
  	   
           $_Dbtype2   = paramload('SEN','pdbtype');
           $_Dbname2   = paramload('SEN','pdbname');
           $_User2     = paramload('SEN','pdbuser');
           $_Password2 = paramload('SEN','pdbpwd');
           $_Host2     = paramload('SEN','pdbhost');  

		   if (defined(_ADODB_)) { 
		   
             $ADODB_CACHE_DIR = paramload('SHELL','prpath') . 
		                        paramload('SEN','pathcacheq');
		   
             $this->sen_db = ADONewConnection($_Dbtype2);
             $isconnected = $this->sen_db->PConnect($_Host2, $_User2, $_Password2, $_Dbname2);			 
			  
		   }
		   else {
		     SetInfo("SEN ADODB extension not loaded....");
			 return false;
		   }
					
		   $sen_db = &$this->sen_db;
		   SetGlobal('sen_db',$this->sen_db);  					 
	   				  
	   
	       return ($isconnected); 
       }
	   else
	       return false;	
	}
	
	function oracle_native() {
	
       putenv("ORACLE_SID=SEN");
       putenv("ORACLE_HOME=c:/ora92");
       putenv("TNS_ADMIN=c:/ora92/network/admin");	
	
	   //oracle 
       //$ora_conn = ora_logon("i_usr@SEN_DB","usr_vk7dp");
	   //oci8
       $db = "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = SEN-SRV)(PORT = 1521)) ) (CONNECT_DATA = (SID = SEN) ) )"; 
       $ora_con = OCILogOn("s01001", "s01001",$db);
	   
       if (!$ora_con) {
         echo "Connection failed";
         echo "Error Message: [" . OCIError($ora_con) . "]";
         //exit;
       }
       else {
         echo "Native Connected!";
       }	    	   
    	
	}
	
	function sen_test_select() {
	
	   //$sSQL = 'select * from PDC_SDB';
	   $sSQL = "SELECT CODCODE,ITMNAME FROM PANIK_VIEW_EIDH WHERE CODCODE='50000'";
       //$sSQL = "SELECT LEEID,LEENAME FROM PANIK_VIEW_LEE_2 WHERE LEEID='12422'";	   
	   //$Ssql = "SELECT EPONYMIA_PELATH,EPAGGELMA,ADRSTREET,CODE_PELATH,ADRSTREET,CODE_PELATH,CODE_PELATH,KATIGORIA,TELEPHONE1,ADRSTREET FROM PANIK_VIEW_USERS WHERE LEEID=12422";
	   echo $sSQL,"<br>";	   
	   
	   $result = $this->sen_db->Execute($sSQL);
	   //print_r($result);
	   //$arr = $result->GetArray();
	   //print_r($arr);
	   
	   $i=0;
	   while(!$result->EOF) {
	     echo "<br>",$result->fields[1];
		 $result->MoveNext();
		 $i+=1;
	   }
	   echo '>>>',$this->sen_db->ErrorMsg();
	}	
	
	function get_MetaTable() {
	   
         // BASED ON ID TABLE
		 //$table = $this->sen_db->MetaTables(); //SLOW!!!!!

		 //print_r($table); 
		 //echo $this->view_MetaTable($table);	
         
		 //WARNING : TABLE NUMBERS MUST INCREMENT OR DECREMENT IF ADD OR REMOVE A TABLE FROM SCHEMA
		 /*foreach ($this->table_id as $num=>$id) {
		 
		   $alias = $this->table_alias[$num];
		   	   
		   $this->$alias = $table[$id];//!!!! WARNING!!!!
		   //echo "$alias,$id  \n";
		 }*/	
		  //BASED ON NAME
		 foreach ($this->table_alias as $id=>$alias) {
		 
		   $this->$alias = $this->table_name[$id]; 
		   //echo $this->$alias,"\n";
		 }	    
	     //print_r($this->table_name); 
		 	   
	}
	
	function view_MetaTable($metadata) {
				 				 					 				 
	    $swin = new window('',$metadata);
	    $out = $swin->render("center::100%::0::group_dir_body::left::0::0::");	
	    unset ($swin);				 	
		
		return ($out);
	}
	
	function get_MetaColumns($tablename) {
	 	   
	     return ($this->sen_db->MetaColumns($tablename));	

	}		

	function action() {
	}

	function event() {   
	}

    function preparestr($str) {

		$preout = str_replace("\"","'",$str);
		//$out = str_replace()!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		//$out = str_replace("+","-",$preout);

		return ($preout);
	}	
	
	function lookup($table,$val,$field,$retcol=0) {
	  
	  $sSQL = "select * from [" . $table . "] where [" . $field . "]=" . 
	          $this->sen_db->qstr($val);

      if ($sSQL) $result = $this->sen_db->Execute($sSQL);
	  
	  $ret = $result->fields[$retcol];

	  return ($ret);	  
	  
	}
	
	//create sql expressions like x=val and y=val and ...
	//                         or x like val or y like val etc.
	//where criteria = array  
	function choiceSQL($wherecriteria,$operator1,$operator2,$val,$no_upperlower=null) {
		
	    $maxcrit = (count($wherecriteria) - 1);			  
	    $sSQL = ""; 
		
	    foreach ($wherecriteria as $cr_num => $cr) {	
		   if (trim($cr)!='') {		
		     if (!$no_upperlower) {
               $sSQL .= " (LOWER(". $cr . ") " . $operator2 . " " . $val ." OR";
			   $sSQL .= " UPPER(". $cr . ") " . $operator2 . " " . $val .")";
		       if ($cr_num<$maxcrit) $sSQL .= " " . $operator1;			 
			 }
			 else {	  
		       $sSQL .= " ". $cr . " " . $operator2 . " " . $val;
		       if ($cr_num<$maxcrit) $sSQL .= " " . $operator1;
			 }  
		   }
		}	
		
		return ($sSQL);	
	}	
	
	//create sql expressions like field=a and field=b and ...
	//                         or field like a or field like b etc.  
	//valcriteria = string like 'asasd,asdad,asdasd'
	function SQLchoice($valcriteria,$operator1,$operator2,$field,$separator=',') {
		
        $crit = explode($separator,$valcriteria);
		
	    $maxcrit = (count($crit) - 2);			  
	    $sSQL = ""; 
		
	    foreach ($crit as $cr_num => $cr) {	
		   if (trim($cr)!='') {			  
		     $sSQL .= " ". $field . " " . $operator2 . " " . $this->sen_db->qstr($cr);
		     if ($cr_num<$maxcrit) $sSQL .= " " . $operator1;
		   }
		}	
		
		return ($sSQL);	
	}	
	
	function distinct($arr) {
	  
	   if (is_array($arr)) {
	     $out = array_unique($arr);
		 
		 asort($out);
		 
		 return ($out);
	   }	 
	}		
	
    function getgroup($localize=0) {
	
	    $group = GetReq("g");   

		$ret_a = explode("^",$group);
		$max = count($ret_a)-1;
		
		//localization............................
		if ($localize) {
		  if (($clanguage=getlocal())!=$this->deflan)
		    $localizeit = localize($ret_a[$max],$clanguage);
		  else  
		    $localizeit = $ret_a[$max];			
		
		  return ($localizeit);	 
		}
		
		return ($ret_a[$max]);	
	}	
	
    //- function returns options for HMTL control "<select>" as one string
    function get_options($sql,$is_search,$is_required,$selected_value) {  
  
        $options_str="";
		
        if ($is_search)
          $options_str.="<option value=\"\">All</option>";
        else  {
          if (!$is_required) {
            $options_str.="<option value=\"\"></option>";
          }
         }

         $result = $this->sen_db->Execute($sql);
		 
         if ($result) {
           while (!$result->EOF)  {
		   
             $id=$result->fields[0];
             $value=$result->fields[1];
             $selected="";
             if ($id == $selected_value) {
               $selected = "SELECTED";
             }
             $options_str.= "<option value='".$id."' ".$selected.">".$value."</option>";
	
	         $result->MoveNext();
           }
         }  
		 
         return $options_str;
    }			

};
}
?>