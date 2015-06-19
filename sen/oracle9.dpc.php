<?php

$__DPCSEC['ORACLE9_DPC']='2;2;2;2;2;2;2;2;9';

if (!defined("ORACLE9_DPC")) { //&& (seclevel('BACKOFFICE_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("ORACLE9_DPC",true);

$__DPC['ORACLE9_DPC'] = 'oracle9test';

class oracle9test {

	var $userLevelID;

	//var $T_products;
	//var $T_family;
	//var $T_subfamily;
	//var $T_group;
	//var $T_category;
	//var $T_boxtype;
	//var $T_customers;
	
	var $table_id;	
	var $table_alias;
	var $table_name;
	
	var $cachequeries;
	var $cachetime;	
	
	var $ora_con;

	function oracle9test() {
	   $UserSecID = GetGlobal('UserSecID'); 
	   $ora_con = GetGlobal('ora_con'); 	   	   

       $this->userLevelID = (((decode($UserSecID))) ? (decode($UserSecID)) : 0);

	   $this->cachequeries = paramload('BACKOFFICE','cacheq');
	   $this->cachetime = paramload('BACKOFFICE','qcachetime');
	   
	   $this->table_id = arrayload('BACKOFFICE','tableid'); //print_r($this->table_id);
	   $this->table_name = arrayload('BACKOFFICE','tablename'); //print_r($this->table_name);
	   $this->table_alias = arrayload('BACKOFFICE','tablealias');	   	   
	   
	   $this->ora_con = &$ora_con;	
	   
	   if ($this->oracle9_connect()) {
	     //echo 'YES';
		 $this->oracle9_test_select(); 
	   }	 
	   else 
	     echo 'NO CONNECTION';	

	}
	
	function oracle9_connect() {
	   $ora_con = GetGlobal('ora_con');
	   static $i;
	
       if (iniload('BACKOFFICE')) {
  	   
	     if (!$this->ora_con) {//$i++; echo $i . ">>>>>>>>>>>>";
           $_Dbtype2   = 'odbc';//'oracle';//paramload('BACKOFFICE','pdbtype');
           $_Dbname2   = 'SEN_DB';//paramload('BACKOFFICE','pdbname');
           $_User2     = 'S01001';//paramload('BACKOFFICE','pdbuser');
           $_Password2 = 'S01001';//paramload('BACKOFFICE','pdbpwd');
           $_Host2     = 'SEN';//'sen-srv';//paramload('BACKOFFICE','pdbhost');  

           $this->ora_con = &ADONewConnection($_Dbtype2);
           $this->ora_con->PConnect($_Host2, $_User2, $_Password2, $_Dbname2);
		   
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
					  
		   $ora_con = &$this->ora_con;
         }					  
	   
	     return TRUE; 
       }
	   else
	     return FALSE;	
	}
	
	function oracle9_test_select() {
	
	   //$sSQL = 'select * from PDC_SDB';
	   $sSQL = "SELECT MCIID,CODCODE,ITMNAME,ITMACTIVE, DFTID FROM STI WHERE CODCODE='70129'";
	   echo $sSQL,"<br>";
	   
	   $result = $this->ora_con->Execute($sSQL);
	   
	   //print_r($result);
	   $i=0;
	   while(!$result->EOF) {
	     echo "<br>",$result->fields[2];
		 $result->MoveNext();
		 $i+=1;
	   }
	   echo '>>>',$this->ora_con->ErrorMsg();
	}
	
	function get_MetaTable() {
	   
	   if ($this->ora_con) {
         $table = $this->ora_con->MetaTables();

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
	 	   
	     return ($this->ora_con->MetaColumns($tablename));	

	}		

	function event() {   
	}
		
	function action() {
	}	

};
}
?>