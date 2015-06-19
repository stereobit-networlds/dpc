<?php
$__DPCSEC['NHANDLER_DPC']='1;1;1;1;1;1;2;2;9';

if ( (!defined("NHANDLER_DPC")) && (seclevel('NHANDLER_DPC',decode(GetSessionParam('UserSecID')))) ) {
define("NHANDLER_DPC",true);

$__DPC['NHANDLER_DPC'] = 'nhandler';

$e = GetGlobal('controller')->require_dpc('nitobi/nitobi.xml.php');
require_once($e);

$__EVENTS['NHANDLER_DPC'][0]='nhandler';
$__EVENTS['NHANDLER_DPC'][1]='a';
$__EVENTS['NHANDLER_DPC'][2]='b';
$__EVENTS['NHANDLER_DPC'][3]='nitobigetdata';
$__EVENTS['NHANDLER_DPC'][4]='cpnitobigetdata';

$__ACTIONS['NHANDLER_DPC'][0]='nhandler';
$__ACTIONS['NHANDLER_DPC'][1]='a';
$__ACTIONS['NHANDLER_DPC'][2]='b';
$__ACTIONS['NHANDLER_DPC'][3]='nitobigetdata';
$__ACTIONS['NHANDLER_DPC'][4]='cpnitobigetdata';

class nhandler {

    var $pagesize, $ordinalStart, $SortColumn, $SortDirection;
	var $db, $dbgpath, $dbres, $result;

    function nhandler() {
	
	  $this->dbgpath = paramload('SHELL','dbgpath');
	
	  $this->pageSize=10;
	  if (isset($_GET['PageSize'])) {
		$this->pageSize = $_GET['PageSize'];
		if(empty($this->pageSize)){
			$this->pageSize=10;
		}
	  }
	  $this->ordinalStart=0;
	  if (isset($_GET['StartRecordIndex'])) {
		$this->ordinalStart = $_GET['StartRecordIndex'];
		if(empty($this->ordinalStart)){
			$this->ordinalStart=0;
		}
	  }
	  $this->sortColumn="id";
	  if (isset($_GET['SortColumn'])) {
		$this->sortColumn=$_GET["SortColumn"];
		if(empty($this->sortColumn)){
			$this->sortColumn="id";
		}
	  }

	  $this->sortDirection='Asc';
	  if (isset($_GET['SortDirection'])) {
		$this->sortDirection=$_GET["SortDirection"];
	  }	
	   
    }
	
	function event($event=null) {
	
	  switch ($event) {
        case 'cpnitobigetdata':
	               $this->get();
				   die();
		           break;	  
	  
        case 'nitobigetdata':
	               $this->remote_call();
				   die();
		           break;
	  
	    case 'a' : $this->event_global_db();  
		           $this->action_global_db();
				   die();
		           break;
		case 'b' : 
		default: $this->event_static_db();
		         $this->action_static_db();
				 die();
	  }
	}
	
	function action($action=null) {
	
	  /*switch ($action) {
	    case 'a' : $this->action_global_db();  break;
		case 'b' : 
		default: $this->action_static_db();	  
	  }*/
	}
	
	
	function get() {
       $db = GetGlobal('db');	
	   
	   	
	}
	
	
	function remote_call() {
	
	   if ($f = fopen("http://hermes:87/cp/cpvehicles.php?t=cpnitobigetdata",'rb')) {
	     $data = stream_get_contents($f);
		 fclose($f);
	   }	 
	   echo trim($data);
	 
	   if ($f = fopen("c:/php/webos/projects/auctionsbazaar/log/handler.txt",'w+')) {
	     fwrite($f,$data,strlen($data));
		 fclose($f);
	   }	 		
    }
	
    function event_static_db($event=null) {	
	
	   $whereClause='';
	   if (isset($_GET['id'])) {
		 $whereClause=" WHERE id=".$_GET["id"]." ";
	   }	 	

       $this->db = new sqlite($this->dbgpath."softhost.db");	
	   
	   $sSQL .= "select id,appname,user,pwd from dpcmodules ";
	   $sSQL .= $whereClause;
	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   
       $this->result = $this->db->dbp->Execute($sSQL,2);	
	   //echo $sSQL;
	   
	   /*$this->dbres = $this->db->dbp->fetch_array($this->result);	
	   echo "<pre>";	   
	   print_r($this->dbres);	   
	   echo "</pre>";		 	*/  
  	}	
	
	function event_global_db($event=null) {
       $db = GetGlobal('db');		
	
	   $whereClause='';
	   if (isset($_GET['id'])) {
		 $whereClause=" WHERE p_id=".$_GET["id"]." ";
	   }	 	

//p_id,date1,type,fipi,km,etosk,model,marka,kybismos,active,type2,id FROM abcproducts	   
	   $sSQL .= "select p_id,type,fipi,km,etosk,model from abcproducts ";
	   $sSQL .= $whereClause;
	   //$sSQL .= $this->datahandler->get_sql_order();
	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";
	   //echo $sSQL;	   
	   
       $this->result = $db->Execute($sSQL,2);	
   	
	}
	
    function action_static_db() {
	
	
	//*******************************************************************
	//Lets set up the output
	//*******************************************************************

	$getHandler = new EBAGetHandler();

	//First we define the columns we are sending in each record, and name each field.
	//We will do this by using the EBAGetHandler_DefineField function. We will name each
	//field of data after its column name in the database.


	$getHandler->DefineField("appname");	
	$getHandler->DefineField("user");
	$getHandler->DefineField("pwd");	
	$getHandler->DefineField("id");	
	$getHandler->DefineField("insdate");	
	$getHandler->DefineField("expire");		
	
	// *******************************************************************
	// Lets loop through our data and send it to the grid
	// *******************************************************************
    if (defined(_ADODB_))
	  $nrows = $this->db->dbp->Affected_Rows();
	else
	  $nrows = $this->db->dbp->num_Rows($this->result);
	//echo $nrows;
	
	for ( $counter = 0; $counter < $nrows; $counter++) {

				$row = $this->db->dbp->fetch_array($this->result,SQLITE_ASSOC);//mysql_fetch_array($result, MYSQL_ASSOC );

				$record = new EBARecord($row["id"]);
				$record->add("appname",	 $row["appname"]);
				$record->add("user",	 $row["user"]);
				$record->add("pwd", $row["pwd"]);
				$record->add("id",	 $row["id"]);
				$record->add("insdate", $row["insdate"]);
				$record->add("expire",	 $row["expire"]);
								
				$getHandler->add($record);


	}
	

    $getHandler->CompleteGet();	
	
	
	} 
	
    function action_global_db() {
       $db = GetGlobal('db');	
	
	//*******************************************************************
	//Lets set up the output
	//*******************************************************************

	$getHandler = new EBAGetHandler();

	//First we define the columns we are sending in each record, and name each field.
	//We will do this by using the EBAGetHandler_DefineField function. We will name each
	//field of data after its column name in the database.


	$getHandler->DefineField("p_id");	
	$getHandler->DefineField("type");
	$getHandler->DefineField("fipi");	
	$getHandler->DefineField("km");	
	$getHandler->DefineField("etosk");	
	$getHandler->DefineField("model");		
	
	// *******************************************************************
	// Lets loop through our data and send it to the grid
	// *******************************************************************
    if (defined(_ADODB_))
	  $nrows = $db->Affected_Rows();
	else
	  $nrows = $db->num_Rows($this->result);
	//echo $nrows;
	
	for ( $counter = 0; $counter < $nrows; $counter++) {

				$row = $db->fetch_array($this->result,SQLITE_ASSOC);//mysql_fetch_array($result, MYSQL_ASSOC );

				$record = new EBARecord($row["p_id"]);
				$record->add("p_id",	 $row["p_id"]);
				$record->add("type",	 $row["type"]);
				$record->add("fipi", $row["fipi"]);
				$record->add("km",	 $row["km"]);
				$record->add("etosk", $row["etosk"]);
				$record->add("model",	 $row["model"]);
								
				$getHandler->add($record);


	}
	

    $getHandler->CompleteGet();	
	
	
	}  	  
};
}
?>