<?php
if (!defined("SYSDPC_DPC")) {
define("SYSDPC_DPC",true);

//require_once("socketconnector.lib.php");

class sysdpc {

    private $_actions;
	private $_events;
	private $_attr;
	private $_security;
    private $systemdb;

    function __construct() {
	
	  $this->_events  = array();	  	
	  $this->_actions = array();
	  $this->_attr    = array();
	  $this->_security= array();	  
	
	  //$this->systemdb = GetGlobal('db');
	  //var_dump($this->systemdb); echo 'ZZZZZ'; //????????????
    }
	
	//create a new dpc object (mode:batch)
	public function _new($dpc,$type) {
      global $__DPC,$__DPCSEC,$__DPCMEM,$__ACTIONS,$__EVENTS,$__LOCALE,$__PARSECOM,
             $__BROWSECOM,$__BROWSEACT,$__PRIORITY,$__QUEUE,$__DPCATTR,$__DPCPROC;	  

	  global $activeDPC,$info,$xerror,$GRX,$argdpc; //IMPORTANT GLOBALS!!!
	  
	  global $__DPCOBJ; //holds objects of new approach of name of type xxx.yyy
	  global $__DPCID; //array of new name alias	  
	  
	  $__DPCMEM = GetGlobal('__DPCMEM');
	  $__DPC = GetGlobal('__DPC');
	  
	  //START THE OBJECT
      $parts = explode(".",trim($dpc)); 
	  $class = strtoupper($parts[1]).'_DPC';
	  
	  //update local table
      $this->make_local_table($class);		  
	  
	  if ((defined($class)) &&
	      (class_exists($__DPC[$class])) ) {
		//echo '>>>',strtoupper($parts[1]),'_DPC','=',$__DPC[strtoupper($parts[1]).'_DPC'];
	    $__DPCMEM[$class] =  & new $__DPC[$class];
		$__DPCOBJ[$dpc] =  & $__DPCMEM[$class];//alias of new name object array
		$__DPCID[$class] = $dpc; //new name index array		 
		
		SetGlobal("_DPCMEM",$__DPCMEM);
		
		return TRUE;
	  }	  
	
	  return FALSE; 	  		
	}	
	
	//create a new dpc object instance based on a dpc as is
	public function _newinstance($instname,$dpc,$type) {
      global $__DPC,$__DPCSEC,$__DPCMEM,$__ACTIONS,$__EVENTS,$__LOCALE,$__PARSECOM,
             $__BROWSECOM,$__BROWSEACT,$__PRIORITY,$__QUEUE,$__DPCATTR,$__DPCPROC;	  

	  global $activeDPC,$info,$xerror,$GRX,$argdpc; //IMPORTANT GLOBALS!!!
	  
	  global $__DPCOBJ; //holds objects of new approach of name of type xxx.yyy
	  global $__DPCID; //array of new name alias	  
	  
	  $__DPCMEM = GetGlobal('__DPCMEM');
	  $__DPC = GetGlobal('__DPC');
	  
	  //START THE OBJECT
      $parts = explode(".",trim($dpc)); 
	  $class = strtoupper($parts[1]).'_DPC';
	  
	  $idpc = $parts[0].".".$instname;
	  $iclass = strtoupper($instname).'_DPC';
	  
	  //update local table
      //$this->make_local_table($class);		  
	  
	  if ((defined($class)) &&
	      (is_object($__DPCMEM[$class])) ) {
		  
		//echo '>>>',strtoupper($parts[1]),'_DPC','=',$__DPC[strtoupper($parts[1]).'_DPC'];
		//echo " ",$iclass,">>>",$idpc;
	    if ((!defined($iclass)) &&
	        (!isset($__DPCMEM[$iclass])) ) {		
		  
		  define($iclass,true); //define instance
		
	      $__DPCMEM[$iclass] =  & new $__DPC[$class];
		  $__DPCOBJ[$idpc] =  & $__DPCMEM[$iclass];//alias of new name object array
		  $__DPCID[$iclass] = $idpc; //new name index array		 
		
		  SetGlobal("_DPCMEM",$__DPCMEM);
		
		  return TRUE;
		}
		else
		  die("Instance error! Name conflicts,");  
	  }	  
	
	  return FALSE; 	  		
	}	
	
	//create a new dpc object instance based on a subclass of a dpc where construct diferrent	
	function _newinstance2($instname) {
      global $__DPC,$__DPCSEC,$__DPCMEM,$__ACTIONS,$__EVENTS,$__LOCALE,$__PARSECOM,
             $__BROWSECOM,$__BROWSEACT,$__PRIORITY,$__QUEUE,$__DPCATTR,$__DPCPROC;	  

	  global $activeDPC,$info,$xerror,$GRX,$argdpc; //IMPORTANT GLOBALS!!!
	  
	  global $__DPCOBJ; //holds objects of new approach of name of type xxx.yyy
	  global $__DPCID; //array of new name alias		
	
	  $idpc = $parts[0].".".$instname;
	  $iclass = strtoupper($instname).'_DPC';	
	
	  if ((!defined($iclass)) &&
	      (!isset($__DPCMEM[$iclass])) ) {		
		  
		  define($iclass,true); //define instance
		
	      $__DPCMEM[$iclass] =  & new $instname;
		  $__DPCOBJ[$idpc] =  & $__DPCMEM[$iclass];//alias of new name object array
		  $__DPCID[$iclass] = $idpc; //new name index array		 
		
		  SetGlobal("_DPCMEM",$__DPCMEM);
		  //echo "OK";
		  return TRUE;
	  }
      else
		  die("Instance error! Name conflicts,"); 	 	
	}	
   
	//under construction ??????????
	public function set_proccess($dpc,$proc) {
	  $__DPCPROC = GetGlobal('__DPCPROC');
	  $__DPC = GetGlobal('__DPC');	  
	  
	 /* $rest_array = array();
	  $priority_array = array();
	  
      while (list ($dpc,$classname) = each ($__DPC)) {
	     if (isset($__PRIORITY[$dpc])) $priority_array[$dpc] = $classname; 
		                          else $rest_array[$dpc] = $classname;
	  }
	  $final = array_merge($priority_array,$rest_array);
	  //print_r($final);
	  return ($final);*/
	  
	  if (class_exists($__DPC[$dpc])) {
	     $__DPCPROC[$dpc] = $proc;
		 return 1;
	  }
	  else
	    return 0;
	  
	}
	
	public function get_proccess($dpc) {
	  $__DPCPROC = GetGlobal('__DPCPROC');
	  $__DPC = GetGlobal('__DPC');		
	
	  if (class_exists($__DPC[$dpc])) {
	     $ret = $__DPCPROC[$dpc];
		 
		 if ($ret) return ($ret);
		      else return 0;
	  }
	  else
	    return -1;	
	}	
	
	//transfer events,action,attributes,locales from parent to child
	//it used when a dpc inherit from other dpc and
	//parent dpc just included where child dpc loaded by script 
	function get_parent($parent,$child) {
	  
	  
	  $GLOBALS["__EVENTS"][$child] = $GLOBALS["__EVENTS"][$parent];
	  $GLOBALS["__EVENTS"][$parent] = array();
	  $GLOBALS["__ACTIONS"][$child] = $GLOBALS["__ACTIONS"][$parent];
	  $GLOBALS["__ACTIONS"][$parent] = array();
	  $GLOBALS["__DPCATTR"][$child] = $GLOBALS["__DPCATTR"][$parent];
	  $GLOBALS["__DPCATTR"][$parent] = array();	  
	  
	  //compatibility for script parser commands
	  $GLOBALS["__PARSECOM"][$child] = $GLOBALS["__PARSECOM"][$parent];
	  $GLOBALS["__PARSECOM"][$parent] = array();	  
	    
	  
	  //PARENT LOCALES TO MEMORY	  
	  $this->make_local_table($parent);
	}	
	
    function get_attribute($modulename,$modulecmd,$param=0) {

       $db = GetGlobal('db');	   
	   
       ///////////////////////////////////////////////////memory in!!!!
       $attr = GetGlobal('__DPCATTR');
       //echo count($loc),'>>>';
       if (isset($attr[$modulename])) {
         //echo "<br>",$modulename,"<br>";   
         $parts = explode(";",$attr[$modulename]);
		 
		 if ($parts[0]==$modulecmd)
		   return ($parts[$param]);
       }
	   
	   //echo '>>>>>>>>>>>>>>>>>',$modulecmd.'>><BR>';	   

       if ((!paramload('SHELL','attrdb')) || (!$db)) { //text file  
	      //echo "$modulename>";
          $level_file = file (paramload('SHELL','prpath') . "attr.csv");

          //while (list ($line_num, $line) = each ($level_file)) {
          foreach ($level_file as $line_num => $line) {	 
		     $split = explode (";", $line);

		     if  (($split[0] == $modulename) && 
		          ($split[1] == $modulecmd)) { 
				
               $ret = explode($split[2]);
			   if ($param) return ($ret[$param]);//spec attr 
			          else return ($ret);        //array of attrs
             }
          }
       }
       else { //db table
          $sSQL = "select attr from attributes WHERE name=" . $db->qstr($modulename) .
	                                     " AND cmd=" . $db->qstr($modulecmd);

          $result = $db->Execute($sSQL,null,1); //print_r ($result);	  

	      if ($db->model=='ADODB') {	
	  
            if ($ret=explode($result->fields['attr'])) { 

			   if ($param) return ($ret[$param]);//spec attr 
			          else return ($ret);        //array of attrs
	        }
	      }
	      else {
	  
            if ($ret=explode($result['attr'])) { 

			   if ($param) return ($ret[$param]);//spec attr 
			          else return ($ret);        //array of attrs
	        }	  
	      } 	 
       }
   }	
	
   //load all dpc's attributes over system (build-in) attributes
   function load_attributes($accelerated=0) {
      $__DPCATTR = GetGlobal('__DPCATTR'); //get global
	  
	  //what if login.....
	  //global __DPCATTR has defined during includes????
	  //if (($accelerated) && (is_array($__DPCATTR))) return ($__DPCATTR); //accelerate...!!!!!!!!!!!!!!!!!!!
   
      $db = GetGlobal('db');
   
      //echo $db,paramload('SHELL','attrdb');
   
      if ((!paramload('SHELL','attrdb')) || (!isset($db))) { //text file  

          $level_file = file (paramload('SHELL','prpath') . "attr.csv");

         //while (list ($line_num, $line) = each ($level_file)) {
         foreach ($level_file as $line_num => $line) {	 
  		   $split = explode (";", $line);

		   $modulename = $split[0];
		   $modulecmd  = $split[1];
		   $attributes = $split[2];

		   $__DPCATTR[$modulename][$modulecmd] = $attributes;		   
         }
      }
      else { //db table load
         $sSQL = "select name,cmd,attr from attributes";

         $result = $db->Execute($sSQL); //print_r ($result);
         if ($result) {

	       if ($db->model=='ADODB') {		   
	   
              while(!$result->EOF) {
	   
		        $modulename = $result->fields[0];
		        $modulecmd  = $result->fields[1];
		        $attributes = $result->fields[2];   
		   
		        $__DPCATTR[$modulename][$modulecmd] = $attributes;

  	            $result->MoveNext();		  
	          }
		   }
		   else {
		 
		     while ($r = $db->fetch_array($result)) {	
			
		        $modulename = $r[0];
		        $modulecmd  = $r[1];
		        $attributes = $r[2];   
		   
		        $__DPCATTR[$modulename][$modulecmd] = $attributes;				 
			 }
		   }	  	  	  
	     }
      }  
      //print_r($__DPCATTR);
      SetGlobal('__DPCATTR',$__DPCATTR); //save the global
    }
	
	public function set_attribute($action,$attr,$dpc=null) {
      $__DPCATTR = GetGlobal('__DPCATTR');
	  	
	  if ($dpc) {
		
		$dpcp = explode("\\",$dpc); 
		$max = count($dpcp)-1;
		$dpcname = str_replace(".DPC.PHP","_DPC",strtoupper($dpcp[$max]));
		
		$__DPCATTR[$dpcname][$action] = $action . "," . $attr;		
		
		SetGlobal('__DPCATTR',$__DPCATTR);		
	  }	
	  else {
	  
		$this->_attr['system'][$action] = $action . "," . $attr;
	  }		
	}
	
	public function set_event($event,$dpc=null) {
      $__EVENTS = GetGlobal('__EVENTS');	
	
	  if ($dpc) {
		
		$dpcp = explode("\\",$dpc); //print_r($dpcp);
		$max = count($dpcp)-1;
		$dpcname = str_replace(".DPC.PHP","_DPC",strtoupper($dpcp[$max]));
		//echo $dpcname;
		
		$__EVENTS[$dpcname][] = $event;
		
		SetGlobal('__EVENTS',$__EVENTS);
	  }	
	  else {
	  
	    $this->_events['system'][] = $event;
	  }
	}	
	
	public function set_action($action,$attr=null,$dpc=null) {
      $__ACTIONS = GetGlobal('__ACTIONS');	
      $__DPCATTR = GetGlobal('__DPCATTR');		  
	
	  if ($dpc) {
		
		$dpcp = explode("\\",$dpc); 
		$max = count($dpcp)-1;
		$dpcname = str_replace(".DPC.PHP","_DPC",strtoupper($dpcp[$max]));
		//echo $dpcname;
		
		$__ACTIONS[$dpcname][] = $action;
		if ($attr) $__DPCATTR[$dpcname][$action] = $action . "," . $attr;		
		
		SetGlobal('__ACTIONS',$__ACTIONS);
		SetGlobal('__DPCATTR',$__DPCATTR);		
	  }	
	  else {
	  
	    $this->_actions['system'][] = $action;
		if ($attr) $this->_attr['system'][$action] = $action . "," . $attr;
	  }
	  	
	}
	
	//combine set_event and set_action
	public function set_command($command,$attr=null,$dpc=null) {
	
	  $this->set_event($command,$dpc);
	  $this->set_action($command,$attr,$dpc);	  
	}	
	
	public function set_security($secparam,$secstr,$dpc=null) {
      $__DPCSEC = GetGlobal('__DPCSEC');	
	
	  if ($dpc) {
		
		$dpcp = explode("\\",$dpc); //print_r($dpcp);
		$max = count($dpcp)-1;
		$dpcname = str_replace(".DPC.PHP","_DPC",strtoupper($dpcp[$max]));
		//echo $dpcname;
		
		$__DPCSEC[$secparam] = $secstr;
		SetGlobal('__DPCSEC',$__DPCSEC);
	  }	
	  else {
	  
		$__DPCSEC[$secparam] = $secstr;
		SetGlobal('__DPCSEC',$__DPCSEC);
			  
	    $this->_security['system'][$secparam] = $secstr;
	  }	
	}	
	
	
    //special fun to make all entries in locales root enabled
    function make_local_table($dpc=null,$debug=null) {
        $loc = GetGlobal('__LOCALE');
        $lr = GetGlobal('__DPCLOCALE');
   
	    //echo $dpc,">>><br>";
	    if (is_array($loc[$dpc])) {
		
	        foreach ($loc[$dpc] as $id=>$explain) {
	         $parts = explode(";",$explain);
		     $lr[$parts[0]] = $parts[1].";".$parts[2].";".$parts[3];
	        }		 
			
			if ($debug) {
	          echo $dpc,'<br>';
		      print_r($lr);
		      echo '<br>';
			}			
	    }
	 
	    SetGlobal('__DPCLOCALE',$lr);
    }
	
	/////////////////////////////////////////////////// REMOTE & SHARED MEM
	
	function exist_dpc_server($address,$port) {
	
	/*  $scon = new socket_connector;
	  if (!$scon->opensocket($address,$port)) {
	    echo 'Socket Error!';
        return false;
	  }
	  else {
	    echo "Socket Ok!";    
	    $scon->writetosocket("quit\0");
        //echo $scon->ReadAll();	
		echo $scon->ReadFromSocket(64),'>>>>>+++';
	    $scon->closesocket();
	    unset($scon);
		return true;			  
	  }	*/
	  
	  
      $fp = @stream_socket_client("tcp://$address:$port", $errno, $errstr, 30);
	  
      if (!$fp) {
	  
        //echo "$errstr ($errno)<br />\n";
        return false;
      } 
	  else {
        //stream_set_timeout($fp,1);
		//stream_set_blocking($fp,false);	
		//stream_set_write_buffer($fp,0); //unbuffered
		  
        //fwrite($fp, "helo\n");
        /*while (!feof($fp)) {
          var_dump(fgets($fp, 1024));
        }*/
        $ret = fgets($fp,7);
        //echo $ret;
        fclose($fp);
		
        if (stristr('phpdac5',$ret)) 
		  return true;
		else 
		  return false;
		//return true;  
      }
	   
		
	/*	echo '0';
		$socket = socket_create (AF_INET, SOCK_STREAM, SOL_TCP);
		echo '1';
		socket_connect($socket, $address, $port);
		echo '2';
        $string = "ver\0"; 
		echo '3';
        socket_write($socket, $string);
        echo '4';
        //now you can read from...
        $line = trim(socket_read($socket, 7));
		echo '5';
		socket_close($socket);
		echo '6';
		echo $line;*/
		
		
		
/*$fp = fsockopen($address, $port, $errno, $errstr, 30);

if (!$fp) {
   echo "$errstr ($errno)<br />\n";
} else {

   stream_set_timeout($fp,1);

   $out = "GET / HTTP/1.1\r\n";
   $out .= "Host: www.example.com\r\n";
   $out .= "Connection: Close\r\n\r\n";
   echo 'write:';
   fwrite($fp, $out);
   echo 'read:';
   while (!feof($fp)) {
       echo fgets($fp, 128);
   }
   //$a =  fread($fp,1);
   echo $a;
   fclose($fp);
}*/
		    
	}
	
	function query_dpc_server($query,$address,$port,$feedback=7,$blocking=true) {
	  
      if (!$fp=@stream_socket_client("tcp://$address:$port", $errno, $errstr, 30)) {
        return false;
      } 
	  else {
        //stream_set_timeout($fp,1);
		//stream_set_write_buffer($fp,0); //unbuffered	  
	  
	  	if ($blocking) stream_set_blocking($fp,$blocking);	
		
        fwrite($fp, $query."\n");
        $ret = fgets($fp,$feedback);
        //echo $ret;
        fclose($fp);
		
        return $ret; 
      }	
	}
	
	function load_tcp_dpc($dpc,$address,$port) {
	
	}
	
	function exist_shm() {	  
	  
	  return (is_file(_DPCPATH_."/shm.id"));
	}
	
	function load_shm_id() {
	
		$data = @file_get_contents(_DPCPATH_."/shm.id");
		$parts = explode("^",$data);
				
		return ($parts[0]);
	}
	
	function load_shm_addr() {
	
		$data = @file_get_contents(_DPCPATH_."/shm.id");
		$parts = explode("^",$data);

		return (unserialize($parts[1]));	
	}
	
	function load_shm_length() {
	
		$data = @file_get_contents(_DPCPATH_."/shm.id");
		$parts = explode("^",$data);
		
		return (unserialize($parts[2]));	
	}
	
	//cgi do it ...iis ?????
	function load_shm_dpc($dpc,$shm_max,$dpc_addr,$dpc_length) {
	    
      if (isset($dpc_addr[$dpc])) {

	    $shm_id = $this->dpc_shm_id = shmop_open(0xfff, "a",0,0);//, 0644, $shm_max);
   
        if ($shm_id) 
		  $ret = shmop_read($shm_id,$dpc_addr[$dpc],$dpc_length[$dpc]);
		//echo $dpc,'>',$dpc_addr[$dpc],':',$dpc_length[$dpc],"\n";
	  }
	  else
	    $ret = null;
			
	  return ($ret);		           
	}	
	
	function __destruct() {
	}
};
}

?>