<?php
//PHP5

//calldpc_extension('adodb.adodb','_ADODB_'); 

class dbconnect {

   private $dbtype;
   public  $db;
   public  $model;
   private $erid;
   private $ispersistent;
   
   var $sql_buffer;
   var $sql_depth;

   function __construct($type=null,$model=null,$errorid=null) {
   
     $this->dbname = $type;   
     $this->db = null;
	 $this->model = $model; 
	 $this->erid = $errorid;
	 $this->ispersistent = null;
	 
	 $this->sql_buffer = array();
	 $this->sql_depth = 0;
   }
   
   public function Connect($host,$user,$password,$name) {

	 if (($this->model=='ADODB') && (defined(_ADODB_))) { 
					
          $ADODB_CACHE_DIR = paramload('SHELL','cachepath');					
					
          $this->db = ADONewConnection($this->dbtype);
          $this->db->Connect($host, $user, $password, $name);
 	 } 
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {
	  
          $this->db = new OracleDriver($host, $user, $password);	  
	 } 
	 else { //sqlite = default
	   //echo $name;
       /*if ($this->db = sqlite_open($name, 0666, $sqliteerror)) { 
         echo extension_loaded('sqlite'),'xxx';
	     $a = get_loaded_extensions();
	     print_r($a);
	     echo $name,'>';
	   }*/
	 
	   try {
	     $this->db = new SQLiteDatabase($name, 0666, $sqliteerror); //OBJECT
	   
	     if ( ! file_exists( $name ) ) die( "Permission Denied ($name)!" );	
	    
	     if ($sqliteerror)	
	       echo $sqliteerror;	 
         else
	       $this->ispersistent = false;	  
		 //$this->db = new sqlite($name);  
	   }
	   catch (Exception $e) {
         echo 'Caught exception: ',  $e->getMessage(), "\n";
       }   
	 }  
	   
   }
   
   public function PConnect($host,$user,$password,$name) {
   
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {
		
          $ADODB_CACHE_DIR = paramload('SHELL','cachepath');		
					
          $this->db = ADONewConnection($this->dbtype);
          $this->db->PConnect($host, $user, $password, $name);
	 } 
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {
	  
          $this->db = new OracleDriver($host, $user, $password);	  
	 } 	  
	 else { //sqlite = default
	 
       //if ($this->db = sqlite_popen($name, 0666, $sqliteerror)) { 
       //echo extension_loaded('sqlite'),'xxx';
	   if ($this->db = new SQLiteDatabase($name, 0666, $sqliteerror)) { //OBJECT PERSITENT???	   

       if ( ! file_exists( $name ) ) die( "Permission Denied ($name)!" );	   
	   
	     if ($sqliteerror)	
	       echo $sqliteerror;	 
         else	   
           $this->ispersistent = true;
       }
	   else 
	     echo "General SQL class error";  
	 } 
   }
   
   public function Execute($sql,$execute=null,$adodb_compatibility=null,$debug=null) {
   
     //echo $sql,"<br>";  //SQL MONITOR................
	 
	 
     //if (!is_object($this->db)) die("zzz");
   
     //echo $sql,"<br>";
     if (($this->erid) || ($debug)) echo $this->erid . ":$sql<br>";
	 
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {
	 
	      $ret = $this->db->Execute($sql);
	 } 
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {

          if ($this->db->getConnStatus()) {	  
		  
             $ret = new OracleRecordset($this->db->execute($sql));		  
		  }
		  elseif ($debug)
		     echo "iOracleDriver:Connection error!!!";  
	 } 	 
	 else { //sqlite = default
          if ($execute==1)//resultless
			  //$ret = sqlite_exec($this->db, $sql);
			  //$ret = $this->db->exec($sql);!!!!!!!!error
			  //echo $sql;
			  $ret = $this->db->query($sql);
		  elseif ($execute==2) 
			  //$ret = sqlite_query($this->db, $sql);	//buffered
			  $ret = $this->db->query($sql);
		  elseif ($execute==3) //multiple ; sql
		  	  $ret = $this->db->queryExec($sql);		  
		  else 
			  //$ret = sqlite_unbuffered_query($this->db, $sql);	
			  $ret = $this->db->unbufferedQuery($sql);
		  
		  //echo $this->db->lastError(),'>>>>>>>>>>>>>>';
		  if ($debug) {
		    $ss = $this->db->lastError();
		    $msg = sqlite_error_string($ss)."\r\n";
		    $fpath = paramload('SHELL','prpath') . "log/dberror.txt";
	        if  ($f = fopen($fpath,'a+')) {
	         fwrite($f,$msg,strlen($msg));
		     fclose($f);
	        } 			  
		  }
		  
		  //print_r($ret->fetch());
		  
          if (($adodb_compatibility) && ($ret)) {
	        //$ret = sqlite_fetch_array($ret);
			$ret2 = $ret->fetch();	
			return ($ret2);
		  }
		  
		  /*$result = $this->db->dbp->Execute($sql,2);	
	      $ret = $this->db->dbp->fetch_array($result);		  
		  return ($ret);					   			  */
	 }		  			  	
	
	 $this->sql_buffer[] = $ret; 	   		   
	 //print_r($this->sql_buffer);
	 //print_r($ret);
	 return ($ret);
   }
   
   public function Execute_bulk($sql,$inputarr=false,$execute=null,$adodb_compatibility=null,$debug=null) {
   

     if (($this->erid) || ($debug)) echo $this->erid . ":$sql<br>";
	 
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {
	      //sql with ? and inputarr = data
	      $ret = $this->db->Execute($sql,$inputarr);
	 } 
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {

          if ($this->db->getConnStatus()) {	  
		  
             $ret = new OracleRecordset($this->db->execute($sql));		  
		  }
		  elseif ($debug)
		     echo "iOracleDriver:Connection error!!!";  
	 } 	 
	 else { //sqlite = default
          if ($execute==1)//resultless
			  //$ret = sqlite_exec($this->db, $sql);
			  //$ret = $this->db->exec($sql);!!!!!!!!error
			  //echo $sql;
			  $ret = $this->db->query($sql);
		  elseif ($execute==2) 
			  //$ret = sqlite_query($this->db, $sql);	//buffered
			  $ret = $this->db->query($sql);
		  elseif ($execute==3) //multiple ; sql
		  	  $ret = $this->db->queryExec($sql);		  
		  else 
			  //$ret = sqlite_unbuffered_query($this->db, $sql);	
			  $ret = $this->db->unbufferedQuery($sql);
		  
		  //echo $this->db->lastError(),'>>>>>>>>>>>>>>';
		  if ($debug) {
		    $ss = $this->db->lastError();
		    $msg = sqlite_error_string($ss)."\r\n";
		    $fpath = paramload('SHELL','prpath') . "log/dberror.txt";
	        if  ($f = fopen($fpath,'a+')) {
	         fwrite($f,$msg,strlen($msg));
		     fclose($f);
	        } 			  
		  }
		  //print_r($ret->fetch());
		  
          if (($adodb_compatibility) && ($ret)) {
	        //$ret = sqlite_fetch_array($ret);
			$ret2 = $ret->fetch();	
			
			return ($ret2);
		  }				   			  
	 }		  			  	
	
	 $this->sql_buffer[] = $ret; 	   		   
	 //print_r($this->sql_buffer);		   		   
	 //print_r($ret); 
	 return ($ret);
   }   
   
   public function PageExecute($sql,$numpages,$currentp,$execute=null,$adodb_compatibility=null) {
   
     if ($this->erid) echo $this->erid . ":$sql<br>";
	 
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {

	      $ret = $this->db->PageExecute($sql,$numpages,$currentp);
	 } 
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {

          if ($this->db->getConnStatus()) {	  
		  
             $ret = new OracleRecordset($this->db->execute($sql));		  
		  } 
	 } 	 
	 else { //sqlite = default
 
          if ($execute==1)
			  //$ret = sqlite_exec($this->db, $sql);
			  //$ret = $this->db->exec($sql);!!!!!!!!error
			  $ret = $this->db->query($sql);
		  elseif ($execute==2) 
			  //$ret = sqlite_query($this->db, $sql);	//buffered
			  $ret = $this->db->query($sql);			  
		  else
			  //$ret = sqlite_unbuffered_query($this->db, $sql);	
			  $ret = $this->db->unbufferedQuery($sql);
		  
	      if (($adodb_compatibility) && ($ret)) { 
		  
	        //$ret = sqlite_fetch_array($ret);
			$ret2 = $ret->fetch();
			return ($ret2);
	      }				  	
		   	
	 }	   
	 
	 $this->sql_buffer[] = (array) $ret->fields; 	   		   
	 print_r($this->sql_buffer);	 
	 
	 return ($ret);
   }   
   
   public function MoveNext($resource) {
   
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {
	   
	      $this->db->MoveNext();
	 }
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {

          $this->db->fetch();
	 } 	 
	 else { //sqlite = default
	 
		  if ($resource) {   
            //sqlite_next($resource);	 
			$resource->next();
		  }      
     }
   }	 
   
   //param = ASSOC or NUM (SQLITE_ASSOC,SQLITE_NUM,SQLITE_BOTH=default)   
   public function fetch_array($resource,$param=SQLITE_BOTH) {
   
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {
	   
	 }
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {
       
	    $line=0;
        while ($this->db->fetch()) {
		  
          for($i = 0; $i < $this->db->getNCols(); $i++) {
            $ret[$line][$i] = $this->db->getResult($i + 1);
          }
		  $line+=1;
        }
	 } 	 
	 else { //sqlite = default
	  
	   if ($resource) { 
            //$ret = sqlite_fetch_array($data);	
			
			$ret = $resource->fetch($param);
			
			//while ($rec = $resource->fetch()) 
			 //$ret[] = $rec;//$resource->fetch();array in array..multiple recs 
	   }	  
	 }	
	 
	 return ($ret);   
   }
   
   //param = ASSOC or NUM (SQLITE_ASSOC,SQLITE_NUM,SQLITE_BOTH=default)
   public function fetch_array_all($resource,$param=SQLITE_BOTH) {
   
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {
	   
	 }
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {
       
	    $line=0;
        while ($this->db->fetch()) {
		  
          for($i = 0; $i < $this->db->getNCols(); $i++) {
            $ret[$line][$i] = $this->db->getResult($i + 1);
          }
		  $line+=1;
        }
	 } 	 
	 else { //sqlite = default
	  
	   if ($resource) { 
            //$ret = sqlite_fetch_array($data);	
			
			while ($rec = $resource->fetch($param)) 
			 $ret[] = $rec;//$resource->fetch();array in array..multiple recs 
	   }	  
	 }	
	 
	 return ($ret);   
   }   
   
   public function qstr($data) {

	 if (($this->model=='ADODB') && (defined(_ADODB_))) {

	     $ret = $this->db->qstr($data);
	 }
	 else {   
	 
         $ret = "'" . $data . "'";
		 //if (is_string($data))
		   //$ret = sqlite_escape_string($data);
		   
	 }
   
     return ($ret);
   }
   
   public function Affected_Rows() {
   
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {

	     $ret = $this->db->Affected_Rows();
	 }
	 else {   
	 
         $ret = 1; //test
		  
		 //$ret = sqlite_changes();   
		 $ret = $this->db->changes();
	 }
   
     return ($ret);   
   }
   
   public function num_Rows($resource) {
   
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {

	     $ret = $this->db->numRows($resource);
	 }
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {

         $ret = $this->db->getNRows();
	 }	 
	 else {   
	 
         //$ret = sqlite_num_rows($resource);
		 $ret = $resource->numRows();
	 }
   
     return ($ret);     
   }
   
   public function MetaColumns($table) {
   
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {

	     $ret = $this->db->MetaColumns($table);
	 }
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {

         $ret = $this->db->getNCols();
	 }	 
	 else {   
	    /* $sql = 'select * from ' . $table . "where rowid=1";
		 //$result = sqlite_exec($sql);
		 $result = $this->db->query($sql);
		 
         //$ret = sqlite_num_fields($result); //test
		 $ret = $this->sql_num_fields($result);*/
		 //echo $table;
		 
         $q = $this->db->query("PRAGMA table_info('$table')");
		 $ret = $q->fetchAll();	 
		 
         //$result = $this->db->query("SELECT * FROM sqlite_master WHERE type='table'");// AND name='$table'");
         //$ret = $result->numRows();		 
		 //echo '>>>>>>>>>>>>>>>>>>',$ret;
		 //print_r($result);
		 
		 //$ret2 = $result->fetchAll();
		 //echo "<pre>";
		 //print_r($ret2);
		 //echo "</pre>";
	 }
   
     return ($ret);   
   }
   
   function sqlite_table_exists($db,$mytable) {
   
     /* counts the tables that match the name given */
     $result = sqlite_query($db,"SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='$mytable'");

     /* casts into integer */
     $count = intval(sqlite_fetch_single($result));

     /* returns true or false */
     return $count > 0;
   } 
   
   function disconnect() {
   
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {
	   //.....
	 }
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {
	 }	 
	 else {
	   
	     //sqlite_close($this->db);
		 	 
		 if (is_object($this->db)) { 
		   //$this->db->close();
		   unset($this->db);
		 }  
		 
		 $this->db = null;
	     echo 'persistent';	  
	 }	   
   }  
   
   function __destruct() {
	 
	 if (($this->model=='ADODB') && (defined(_ADODB_))) {
	   //.....
	 }
	 elseif (($this->model=='IORADRV') && (defined(_IORADRV_))) {
	 }	 
	 else {//sqlite

		 if (is_object($this->db)) { 
		   unset($this->db);
		   //$this->db->close();
		   //sqlite_close($this->db);	
		 }  
		 
		 $this->db = null;	  
	 }	 	  
   }
   
}   
?>