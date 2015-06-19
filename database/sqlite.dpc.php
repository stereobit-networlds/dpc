<?php

if (!defined("SQLITE_DPC"))  {
define("SQLITE_DPC",true);

$__DPC['SQLITE_DPC'] = 'sqlite';

$__PRIORITY['SQLITE_DPC'] = 1; //under construction


$d = GetGlobal('controller')->require_dpc('database/database.dpc.php');
require_once($d);

class sqlite extends database {

   var $userLevelID;
   var $dbp;

   function __construct($sqlitefile,$handler='sqlitedb') {  
      static $i;
	  
	  $sqlitedb = GetGlobal('sqlitedb');
	  
	  $this->dbp = &$sqlitedb;

      $this->userLevelID = (((decode(GetSessionParam('UserSecID')))) ? (decode(GetSessionParam('UserSecID'))) : 0);	  

      //if (/*(iniload('DATABASE')) &&*/ (!$this->dbp)) { //no re-connection at new
	  //$x = GetGlobal('sqlitedb');
	  //if (isset($x)) {
	  
	  $activedb = GetSessionParam('activeDB');
	  if (($activedb!=$sqlitefile) or (!$this->dbp)) {
	  
	    //unset($this->dbp);
	  
        //$i++; echo "<br>".$i . ">>>>>>>>>>>>";

        $_Dbtype   = 'SQLITE';
        $_Dbname   = $sqlitefile;  

        //echo $sqlitefile,"<br>";
		  
		$this->dbp = new dbconnect('SQLITE');
		$this->dbp->Connect(null, null, null, $_Dbname);		  
		
		SetGlobal('sqlitedb',&$this->dbp);//global alias
		SetSessionParam('activeDB',$sqlitefile);
		
        //$x = fopen('xxx');//WARNING!!!!
     }
	 //else
	   //echo "<br>activedb",$activedb,"++",$sqlitefile,"++";
   }
   
   function disconnect() {
   
       unset($this->dbp);
	   SetGlobal('sqlitedb',null);
   }
   
   function __destruct() {
   
      $this->disconnect();
   }

};
}
?>