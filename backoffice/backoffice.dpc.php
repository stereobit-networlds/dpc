<?php

$__DPCSEC['BACKOFFICE_DPC']='2;2;2;2;2;2;2;2;9';

if (!defined("BACKOFFICE_DPC")) { //&& (seclevel('BACKOFFICE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("BACKOFFICE_DPC",true);

$__DPC['BACKOFFICE_DPC'] = 'backoffice';

class backoffice {

	var $userLevelID;
	
	var $table_id;	
	var $table_alias;
	var $table_name;
	
	var $cachequeries;
	var $cachetime;	
	
	var $db_con;

	function backoffice() {
	   $UserSecID = GetGlobal('UserSecID');
	   $db_con = GetGlobal('db_con');	   	   

       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);

	   $this->cachequeries = paramload('BACKOFFICE','cacheq');
	   $this->cachetime = paramload('BACKOFFICE','qcachetime');
	   
	   $this->table_id = arrayload('BACKOFFICE','tableid'); //print_r($this->table_id);
	   $this->table_name = arrayload('BACKOFFICE','tablename'); //print_r($this->table_name);
	   $this->table_alias = arrayload('BACKOFFICE','tablealias');	   	   
	   
	   $this->db_con = &$db_con;			

	}
	
	function backoffice_connect() {
	   //global $db_con;
	   $db_con = GetGlobal('db_con');	   
	   static $i;
	
       if (iniload('BACKOFFICE')) {
  	   
	     if (!$this->db_con) {//$i++; echo $i . ">>>>>>>>>>>>";
           $_Dbtype2   = paramload('BACKOFFICE','pdbtype');
           $_Dbname2   = paramload('BACKOFFICE','pdbname');
           $_User2     = paramload('BACKOFFICE','pdbuser');
           $_Password2 = paramload('BACKOFFICE','pdbpwd');
           $_Host2     = paramload('BACKOFFICE','pdbhost');  

		   if (defined(_ADODB_)) { 		
		   
             $ADODB_CACHE_DIR = paramload('SHELL','prpath') . 
		                        paramload('BACKOFFICE','pathcacheq');
										      
             $this->db_con = ADONewConnection($_Dbtype2);
             $this->db_con->PConnect($_Host2, $_User2, $_Password2, $_Dbname2);
		   
			 //echo 'ADODB loaded !';
		   }
		   else {
		     echo "ADODB extension not loaded....";
		   }		   
           //$this->db_con = ADONewConnection("access");		   
           //$this->db_con->PConnect("panicdbserver", $_User2, $_Password2, "panicdbserver");		 
		   //$this->db_con->PConnect("filedsn=e:\webos\databases\oe2.dsn;DBQ=e:\webos\databases\oe2.mdb;UID=admin;");
		 
	   
	       //secont method
	      /* $this->db_con = new COM("ADODB.Connection");
	       $this->db_con->Provider = "MSDASQL";
	       //$this->db_con->Open("nwind");
	   
	       $this->db_con->Open("Provider=Microsoft.Jet.OLEDB.4.0;" .
	                  "Persist Security info=false;" . 
	                  "Data Source=\\\\panikidisbsn\\_oe2\\oe2.mdb;");// .
				      //"User Id=admin; Password=");
					  */
					  
		   $db_con = &$this->db_con;
         }					  
	   
	     return TRUE; 
       }
	   else
	     return FALSE;	
	}
	
	function get_MetaTable() {
	   
	   if ($this->backoffice_connect()) {
         $table = $this->db_con->MetaTables();

		 //print_r($table);
	   
	     //get meta data
	     /*$this->T_products  = $table[51]; //10
	     $this->T_family    = $table[5];  //5
	     $this->T_subfamily = $table[8];  //8
	     $this->T_group     = $table[6];  //6
	     $this->T_category  = $table[4];  //4
	   
	     $this->T_boxtype   = $table[7];  //7
		 
	     $this->T_customers  = $table[96]; //20		*/ 	

		 foreach ($this->table_id as $num=>$id) {
		 
		   $alias = $this->table_alias[$num];
		   $this->$alias = $table[$id];
		   //echo "$alias,$id  \n";
		 }			    
	
	   }
	}
	
	function get_MetaColumns($tablename) {
	 	   
	     return ($this->db_con->MetaColumns($tablename));	

	}		

	function action() {
	}

	function event() {   
	}

    function preparestr($str) {

		$preout = str_replace("\"","'",$str);
		//$out = str_replace("+","-",$preout);

		return ($preout);
	}	
	
	function lookup($table,$val,$field,$retcol=0) {
	  
	  $sSQL = "select * from [" . $table . "] where [" . $field . "]=" . 
	          $this->db_con->qstr($val);

      if ($sSQL) $result = $this->db_con->Execute($sSQL);
	  
	  $ret = $result->fields[$retcol];

	  return ($ret);	  
	  
	}
	
	//create sql expressions like x=val and y=val and ...
	//                         or x like val or y like val etc.
	//where criteria = array  
	function choiceSQL($wherecriteria,$operator1,$operator2,$val) {
		
	    $maxcrit = (count($wherecriteria) - 1);			  
	    $sSQL = ""; 
		
	    foreach ($wherecriteria as $cr_num => $cr) {	
		   if (trim($cr)!='') {			  
		     $sSQL .= " ". $cr . " " . $operator2 . " " . $val;
		     if ($cr_num<$maxcrit) $sSQL .= " " . $operator1;
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
		     $sSQL .= " ". $field . " " . $operator2 . " " . $this->db_con->qstr($cr);
		     if ($cr_num<$maxcrit) $sSQL .= " " . $operator1;
		   }
		}	
		
		return ($sSQL);	
	}		

};
}
?>