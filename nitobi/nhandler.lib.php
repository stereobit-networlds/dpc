<?php

$e = GetGlobal('controller')->require_dpc('nitobi/nitobi.xml.php');
require_once($e);

class nhandler {

    var $pagesize, $ordinalStart, $SortColumn, $SortDirection;

	function nhandler($pagesize=10,$sortcolumn='id',$sort='Asc') {

	  $this->pageSize=$pagesize;
	  if (isset($_GET['PageSize'])) {
		$this->pageSize = $_GET['PageSize'];
		if(empty($this->pageSize)){
			$this->pageSize=$pagesize;
		}
	  }
	  $this->ordinalStart=0;
	  if (isset($_GET['StartRecordIndex'])) {
		$this->ordinalStart = $_GET['StartRecordIndex'];
		if(empty($this->ordinalStart)){
			$this->ordinalStart=0;
		}
	  }
	  $this->sortColumn=$sortcolumn;
	  if (isset($_GET['SortColumn'])) {
		$this->sortColumn=$_GET["SortColumn"];
		if(empty($this->sortColumn)){
			$this->sortColumn=$sortcolumn;
		}
	  }

	  $this->sortDirection=$sort;
	  if (isset($_GET['SortDirection'])) {
		$this->sortDirection=$_GET["SortDirection"];
	  }
	}


	function handle_output($db_handler,$db_res,$fields_names_array,$id_name=null,$return=false,$encoding='UTF-8') {

	  //*******************************************************************
	  //Lets set up the output
	  //*******************************************************************

	  $getHandler = new EBAGetHandler();

	  //First we define the columns we are sending in each record, and name each field.
	  //We will do this by using the EBAGetHandler_DefineField function. We will name each
	  //field of data after its column name in the database.

	  foreach ($fields_names_array as $title) {
	    $getHandler->DefineField($title);
	  }

	  // *******************************************************************
	  // Lets loop through our data and send it to the grid
	  // *******************************************************************
      if (defined(_ADODB_))
	    $nrows = $db_handler->Affected_Rows();
	  else
	    $nrows = $db_handler->num_Rows($db_res);
	  //echo $nrows,'>>>>>>';

	  for ( $counter = 0; $counter < $nrows; $counter++) {

                if (defined(_ADODB_)) {
				  $row = $db_res->fields;
				  $db_res->MoveNext();
				}
				else
				  $row = $db_handler->fetch_array($db_res,SQLITE_ASSOC);//mysql_fetch_array($result, MYSQL_ASSOC );

				$id = $id_name?$row[$id_name]:$counter;

				$record = new EBARecord($id);

				reset($fields_names_array);
    	        foreach ($fields_names_array as $name) {
				  $record->add($name,	 $row[$name]);
				  //echo $name,'=',$row[$name],"<br>";
				}

				$getHandler->add($record);


	  }

      //header('Content-type: text/xml'); //no need .. handled by CompleteGet
	  //$getHandler->CompleteGet();
	  //exit();

	  if (!$return) {
	    $getHandler->CompleteGet($encoding);
	  }
	  else {
        $ret = trim($getHandler->CompleteGet($encoding,true));

	    if ((GetReq('debug')==1)&&($f = fopen("debug_handler.txt",'w+'))) {
	       fwrite($f,$ret,strlen($ret));
		   fclose($f);
	    }


	    return ($ret);
	  }

	  //not need anymore...
	  //$GLOBALS['DIE']=true;	//rprevent from pcntl page __destruct showing html footers
	  //exit();
	  if ((GetReq('debug')==1)&&($f = fopen("debug_handler.txt",'w+'))) {
	       fwrite($f,$ret,strlen($ret));
		   fclose($f);
	  }

	  die();
	}

	function get_sql_order() {

	   $sSQL .= " ORDER BY " . $this->sortColumn . " " . $this->sortDirection ." LIMIT ". $this->ordinalStart .",". ($this->pageSize) .";";

	   return ($sSQL);
	}


	//save handler (insert,update,delete)
	function handle_input($db_handler,$tablename,$fields_names_array,$id_name,$return=false,$encoding='UTF-8') {

	   $csvf = implode(",",$fields_names_array);
	   $max = count($fields_names_array)-1;
       /*   This file is used as a Save Handler for the Grid control. When the user clicks
	   the save button, a datagram is sent to this script.
	   The script in turn looks at each update in the datagram and processes them accordingly.
       */
       $saveHandler = new EBASaveHandler();
       $saveHandler->ProcessRecords();

       // ********************************************************** '
       // Begin by processing our inserts
       // ********************************************************** '
       $insertCount = $saveHandler->ReturnInsertCount();
       if ($insertCount > 0)
       {
	     // Yes there are INSERTs to perform...
	    for ($currentRecord = 0; $currentRecord < $insertCount; $currentRecord++)
	    {
		$myQuery  = "INSERT INTO $tablename ($csvf) VALUES (";// .
					//"'" . $saveHandler->ReturnInsertField($currentRecord) . "',";

		reset($fields_names_array);
    	foreach ($fields_names_array as $num=>$name) {

		  $myQuery  .=	"'" . $saveHandler->ReturnInsertField($currentRecord, $name)  . "'";
		  if ($num<$max)
		    $myQuery .= ", ";
		}

		$myQuery  .= "); ";

		// Now we execute this query
		//mysql_query($myQuery);
		if ($db_handler)
		  $db_handler->Execute($myQuery);
        $sSQL = $myQuery;

	    }
       }

       // ********************************************************** '
       // Continue by processing our updates
       // ********************************************************** '

       $updateCount = $saveHandler->ReturnUpdateCount();
       if ($updateCount > 0)
       {
	    // Yes there are UPDATEs to perform...
	    for ($currentRecord = 0; $currentRecord < $updateCount; $currentRecord++)
	    {
		$myQuery = "UPDATE $tablename SET ";

		reset($fields_names_array);
    	foreach ($fields_names_array as $num=>$name) {

		  $myQuery  .=	"$name = '" . $saveHandler->ReturnUpdateField($currentRecord,$name) . "'";
		  if ($num<$max)
		    $myQuery .= ", ";
		}

		$myQuery .= " WHERE $id_name = '" . $saveHandler->ReturnUpdateField($currentRecord) . "'" .	";";

		// Now we execute this query
		//mysql_query($myQuery);
		//$db_handler->set_charset('greek');

		if ($db_handler)
		  $db_handler->Execute($myQuery);
        $sSQL .= $myQuery;
	    }
       }

       // ********************************************************** '
       // Finish by processing our deletes
       // ********************************************************** '

       $deleteCount = $saveHandler->ReturnDeleteCount();
       if ($deleteCount > 0)
       {
	      // Yes there are DELETES to perform...
	      for ($currentRecord = 0; $currentRecord < $deleteCount; $currentRecord++)
	      {
		  $myQuery = "DELETE FROM $tablename WHERE $id_name = '" . $saveHandler->ReturnDeleteField($currentRecord)."';";

		  // Now we execute this query
		  //mysql_query($myQuery);
		  if ($db_handler)
		    $db_handler->Execute($myQuery);
		  $sSQL .= $myQuery;
	      }
       }

       //$db_handler->Execute($sSQL);

       $saveHandler->CompleteSave();
	   //echo $sSQL;
	   return ($sSQL);
	}
}
?>