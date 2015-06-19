<?php
$__DPCSEC['RESOURCES_DPC']='1;1;1;1;1;1;1;1;2';

if (!defined("RESOURCES_DPC")) {
define("RESOURCES_DPC",true);

$__DPC['RESOURCES_DPC'] = 'resources';

class resources {

   var $_resources, $_resptr;
   var $ip2get, $port2get;
   var $env;
   var $daemon_port;

   function resources($env=null) {
   
      $this->env = $env;
   
      $this->_resources = array();   
      $this->_resptr = array();	
	  
	  $this->ip2get = $env->phpdac_ip; 
	  //echo 'zzzz',$this->ip2get,'ccccc',$this->port2get; 
	  $this->port2get = $env->phpdac_port; //dac server port 
	  $this->daemon_port = $env->daemon_port;//clients port
   }
  
   function set_resource($rname,$resource) {
   
      //in case of resource name with spaces
      $rname = str_replace(' ','_',$rname);

	  /*if (empty($resource)) {// network error
	      echo 'zzz';
	  	  print_r($this->_resources);
	  }   
	  else*/if (is_object($resource)) {

	    $this->_resources[$rname] = serialize($resource);//serialized object???
		$this->_resptr[$rname] = & $resource;//object instance		
		//print_r($this->_resources);	
	    $this->env->update_agent($this,'resources');			
	  }
	  elseif (is_resource($resource)) {

	    $type = get_resource_type($resource);
		$this->_resources[$type] = $rname;
		
		$this->_resptr[$type] = & $resource;
	    //print_r($this->_resptr);
		//print_r($this->_resources);
	    $this->env->update_agent($this,'resources');
   
		return true;
	  }
	  elseif (is_scalar($resource)) {//integer,float,string.boolean
	  
	    $this->_resources[$rname] = $resource;
	    $this->_resptr[$rname] = & $resource;	
		//print_r($this->_resources);		
	    $this->env->update_agent($this,'resources');			
	
	    return true;
	  }
      echo "WARNING:resource [$rname] failed to register!\r\n";
	  return false;
   }
   
   //delete local resource
   function del_resource($resource) {
   
     if (array_key_exists($resource,$this->_resources)) {
	 
	    unset($this->_resources[$resource]);
	    unset($this->_resptr[$resource]);
	 }
   }
   
   //local version
   //name called by stream of server
   function &get_resource($resource,$name=null) {
   
	   //print_r($this->_resources); 
	   //print_r($this->_resptr);	    
	    
       //auto get from net 
	   //if no resource or invalid resource...
       if (!array_key_exists($resource,$this->_resources)) {
		   
	     $r = $this->get_resourcec($resource,$this->ip2get,$this->port2get);
		 
		 if ($r) {
		   if ($rp = $this->create_resource($resource,$r))//if create physical resource
             $this->set_resource($r,$rp);//save it to local resource table		 
		   else //else scalar 
		     $this->set_resource($resource,$r);//save it to local resource table	
		    	 
		   echo 'remote:[',$this->ip2get,']:',$resource,"\n";
		 }
	   }
	   /*elseif ($res = file_get_contents("phpres5://192.168.1.35:19125/" . $resource)) {
	     echo 'ooouuura!!!!!';
		 echo 'remote:[','192.168.1.35',']:',$resource,"\n";
		 return ($res);
	   }*/
	   else {
	     if (!$this->_resptr[$resource])
	       $rp = $this->create_resource($resource,$this->_resources[$resource]); 
		 else  
		   $rp = $this->_resptr[$resource];
	     echo 'local:',$resource,"\n";
	   }	 
		 
	   //print_r($this->_resources);
	   //print_r($this->_resptr);	    		 	 
	    
	   if ($name!=null)
	     return ($this->_resources[$resource]); //get parameter to re-assign resource in client
	   else
		 return ($rp);
	 
	    	 
	   //else search the net for the specified resource.....	 
	   //or..autometed
	   //get it from server (default)
		 
	   return false;	 
   }
   
   //remote version
   function get_resourcec($resource,$from=null,$port=null) {
   
      $ip = ($from?$from:$this->ip2get);
	  $po = ($port?$port:$this->port2get);
	   
      //if (!$port)
	    //$port = '19123';//server port = default connection
		
	  //echo $from,':',$port;
      //get resource
      $f_res = @file_get_contents("phpres5://$ip:$po/" . $resource);
	  
	  $tokens = explode($resource,$f_res);//explode based on word of resource
	  $s_res = $tokens[1];
      //echo '>',$s_res,'<';   
	  
	  //copy it in local resources array
	  if ($r=trim($s_res)) {

	    //$this->create_resource($resource,$r); //PRINTER DLL?????
	    return ($r);
	  }
	  
	  return false;	
   }
   
   function unset_resource($resource_name) {
   
       //if (in_array($resource_name,$this->_resources))
	     //remove resource by value!!!!!
		 
	   return false;	 
   }
   
   function &create_resource($resource_name,$resource_string) {
   
      switch ($resource_name) {
	  
	    case 'printer' : $resource = printer_open($resource_string);//true;
	                     if (is_resource($resource) &&
		                   get_resource_type($resource)=='printer') {
	                       printer_set_option($resource, PRINTER_MODE, 'RAW'); 
		                   //echo ">>>>>>>>>>>>",$resource_string,">>>>\n";
		                  // $this->set_resource($resource_string,$resource);
	                     }	
		                 break; 
		
		case 'odbc link':				 
		case 'odbc link persistent' : echo 'zzz';break;
		
		case 'sqlite'  : $db_name = $this->get_resource('sqlite_name');
		                 $resource = new SQLiteDatabase($db_name, 0666, $sqliteerror);				  
						 if ($sqliteerror) echo 'ERROR:',$sqliteerror,'\n';
						 
		default        : //scalar
		                 //if (is_scalar($resource_string))
		                   //$this->set_resource($resource_name,$resource_string);	
						   
						 //DO NOTHING....  
						   			 
	  }
	  
	  return ($resource);
   } 
   
  //show the resources running ........ 
  function showresources() {
     //print_r($this->_resources);
	 //echo 'z';
     foreach ($this->_resources as $t=>$d) {
	   //$ret .= "[" . $t . "]\r\n";
	   $ret .= $t . "=" . $d . "...";
	   if (is_object($this->_resptr[$t]))
	     $ret .= get_class($this->_resptr[$t]) . "\r\n";
	   else
	     $ret .= $this->_resptr[$t] . "\r\n";
	 }  
  
     return ($ret);  
  } 
  
  function has_resource($resource) {
  
     if (!array_key_exists($resource,$this->_resources)) 
	   return false;
	 else
	   return true;  
  } 
  
  function findresource($resource,$showname=null) {
  
  
    /* if (!$res=$this->get_resource($resource,$showname)) {//local
  
       $connections = $this->env->show_connections(null,1);
	   //print_r($connections);
	   //echo "---------------\n";
	 
	   if (is_array($connections)) {//remote
	     foreach ($connections as $s=>$sd) {
	       $hosts[] = $sd['host'];
		 
		   if (($sd['host']!='127.0.0.1') && //localhost
		       ($sd['host']!=$this->ip2get) && //bypass remote master
			   ($sd['host']!='192.168.1.35')) {//real self address
	         if ($res = $this->get_resourcec($resource,$sd['host'],$this->daemon_port)) {
		     
		       echo $sd['host'],":",$res,"\r\n"; 		 
			   break;
		     }
		   }  
	     }
	   
	     echo implode("\r\n",array_unique($hosts));	
	   }
	 }//local*/
	 echo 'zzzzzzzzzz';
	 //$res = $this->get_resourcec($resource,'192.168.1.247','19125');
	 $res = file_get_contents("phpres5://192.168.1.247:19125/" . $resource);
	 
	 
		/*$socket = pfsockopen('192.168.1.247', '19125', $errno, $errstr, 5); 

		if (!$socket) {
		  echo $errstr,"(",$errno,")\n";
		  return false;
		}
		//stream_set_blocking($socket,1);
		$request = "getresource xx" . "\r\n\r\n";		
        fputs($socket, $request); 

        $res = ''; 
        while (!feof($socket)) { 
          $res .= fgets($socket, 1096);
        }  
        fclose($socket);*/  	 
	 
	 echo 'end';
	 
	 return ($res);
  
  }  
  
};
}
?>