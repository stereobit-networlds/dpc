<?php
if (!defined("CONTROLLER_DPC")) {
define("CONTROLLER_DPC",true);

require_once("sysdpc.lib.php");

//REGISTER PHPDAC protocol...
/*require_once("dacstream.lib.php");
$phpdac = stream_wrapper_register("phpdac","dacstream");
if (!$phpdac) _echo('CLI','Protocol failed to registered!');
         else _echo('CLI','Protocol registered!');
*/		
class controller extends sysdpc {

   var $server;
   var $shm;
   var $shm_id;
   var $shm_addr;
   var $shm_length;
   
   var $dac; //handles dac events,actions
   var $dacpost; //handles post routines

   function __construct($dac=null,$dacpost=null) {

      $this->dac = $dac;
	  $this->dacpost = $dacpost;
    
	  sysdpc::__construct();
	  
	  //$this->server = $this->exist_dpc_server('127.0.0.1',19123);	  
	  $this->shm = $this->exist_shm();
	  if ($this->shm) {
	    $this->shm_id = $this->load_shm_id();	 //echo $this->shm_id; 
	    $this->shm_addr = $this->load_shm_addr();	
	    $this->shm_length = $this->load_shm_length();	
			
		//REGISTER PHPDAC (server side) protocol...
        require_once("dacstream.lib.php");			
		$phpdac_s = stream_wrapper_register("phpdac","dacstream");
		if (!$phpdac_s) _echo('CLI',"Server protocol failed to registered!\n");
		           else _echo('CLI',"Server protocol registered!\n");
  
 		//REGISTER PHPDAC (client side)protocol...(used by webserver due to shm error!!!)
        require_once("dacstreamc.lib.php");			
		$phpdac_c = stream_wrapper_register("phpdac5","c_dacstream");
		if (!$phpdac_c) _echo('CLI',"Client protocol failed to registered!\n");
		           else _echo('CLI',"Client protocol registered!\n"); 
	  }
   }
   
   //special include/require selectable from filesystem of shm mem stream
   //server side
   //WARNING:SYSTEM(PHP) PATH NOT WORKING ..IT REQUIRES FULL PATH(filesystem ver)
   public function include_dpc($dpc) {

      //problem with scope....called as controller->include_dpc($dpc)
   
      if ($this->shm) {
	    if (GetGlobal('__USERAGENT')=='HTML')
	      require_once("phpdac5://127.0.0.1:19123/".$dpc);
		else	  
	      require_once("phpdac://".$dpc);
	  }	
	  else
	    require_once(_DPCPATH_."/".$dpc);
   }
   
   //return the file name or stream
   public function require_dpc($dpc) {

      //NO problem with scope....called as require_once(controler->require_dpc($dpc))
	  //problem calling directly as above
	  //no problem with call of type $x = controller->require(..)/require_once(x)
   
      if ($this->shm) {
	    if (GetGlobal('__USERAGENT')=='HTML')
	      $ret = "phpdac5://127.0.0.1:19123/".$dpc;
		else	  
	      $ret = "phpdac://".$dpc;
	  }	
	  else
	    $ret = _DPCPATH_."/".$dpc;
		
	  //echo '>',$ret,'<br>';	
	  return $ret;	
   }   
   
   //read project file ...
   private function compile($accelerated=0) { 
   
     //CAN ACCELERATE ??....
	 //tokens reding ..ok  
   	  
	 //if ($this->server) _echo('CLI','server live');
	 //else _echo('CLI','server down');
	 
	 if ($this->shm) {
	   _echo('CLI',"Shm live!\n"); 
	   //$rdpc = $this->load_shm_dpc('examples/example1.dpc.php',$this->shm_id,$this->shm_addr,$this->shm_length);
	   //$fp = fopen("phpdac://examples/example1.dpc.php", "r");  
	   //$rdpc = fread($fp,8500); 
	   //fclose($fp);
	   //echo $rdpc;
	 }	  
	 else 
	   _echo('CLI','shm down');	 
	  
     if (iniload('ID')) {
	 
       $projectfile = paramload('ID','fpath').paramload('ID','name').".php";
	   //echo ">>>>>>>>>",$projectfile;
       $c = unserialize(GetGlobal("__COMPILE")); //get global	   
	   if (($accelerated) && (is_array($c))) {//accelerate reading...!!!!
	     $token = $c;
		 //echo 'Accelerated';
	   }
	   else {//not accelerated
	     //echo 'NOT accelerated';
	     //if (file_exists($projectfile)) { //accelerate.. no check
		   if ($file = file($projectfile)) {
			//clean code by nulls and commends and hold it as array
			foreach ($file as $num=>$line) {
			  $trimedline = trim($line);
		      if (($trimedline) && //check if empty line			  
			      ($trimedline[0]!="#")) {  //check commends        
			     //echo $trimedline."<br>";			    
				 $lines[] = $trimedline;
			  }
			}
			//print_r($lines);
			//implode lines because one line may have more than one cmds sep by ;
			$toktext = implode("",$lines);
			//echo $toktext;
			//tokenize
			$token = explode(";",$toktext);
			//print_r($token); //last token = empty
			//echo $token;
			SetGlobal("__COMPILE",serialize($token)); //save the global....
		   }	
	       else
		     raise_error(3,'EXIT');
	     /*}
	     else
	       raise_error(0,'EXIT',"File '$projectfile' not exist\n"); */
	   }//not accel	   
		   
	   //then...read tokens  			
	   foreach ($token as $tid=>$tcmd) {
			  
			   $part = explode(' ',$tcmd);
			   switch ($part[0]) {
			     case 'system': //include and load a set of system lib dpc
				                $syslibs = explode(",",$part[1]);
						        //print_r($syslibs);
								foreach ($syslibs as $lid=>$lib) {
								  if (strstr($lib,'.')) 
								    $this->calldpc($lib,'lib');//if . exist select from a spec dir
								  else 
								    $this->calldpc("system.$lib",'lib'); //else libs dir = default
								}		 
				                break;			   
			   
			     case 'use'   : //include and load a set of lib dpc
				                $libs = explode(",",$part[1]);
						        //print_r($libs);
								foreach ($libs as $lid=>$lib) {
								  if (strstr($lib,'.')) 
								    $this->calldpc($lib,'lib');//if . exist select from a spec dir
								  else 
								    $this->calldpc("libs.$lib",'lib'); //else libs dir = default
								}		 
				                break;
				
				 case 'super' :	//include and load a set of dpc		
				                $dpcs = explode(",",$part[1]);
						        //print_r($dpcs);
								foreach ($dpcs as $did=>$dpc) {
								  if (strstr($dpc,'.')) $this->calldpc($dpc,'dpc');
								                   else $this->calldpc("$dpc.$dpc",'dpc');//same name for dir + class
								}		 
				                break;		
								
				 case 'include' ://include NOT load a set of dpc		
				                $dpcs = explode(",",$part[1]);
						        //print_r($dpcs);
								foreach ($dpcs as $did=>$dpc) {
								  if (strstr($dpc,'.')) $this->set_include($dpc,'dpc');
								                   else $this->set_include("$dpc.$dpc",'dpc');//same name for dir + class
								}		 
				                break;											 	
							  
			     case 'load_extension' : //include only NOT load a set of extensions dpc
								if (strstr(trim($part[1]),'.')) 
								  $this->set_extension(trim($part[1]),trim($part[3]),1);
								else //. not exist				 
				                  $this->set_extension(trim($part[1]).".".trim($part[1]),trim($part[3]),1);
				                break;	
								
				 case 'instance':if (strstr(trim($part[3]),'.'))		
				                   $this->set_instance($part[3],$part[1],$part[5]);
                                 else //. not exist				 
				                   $this->set_instance(trim($part[3]).".".trim($part[3]),$part[1],$part[5]);								   
								 break;		
								 				  
				 case 'member': $dpcmods[] = $part[1];
				                break;			  
								
				 default      : //only include and save dpc modules to load th objects by shell			  
			  		            if ($part[0]) {
								  $this->set_include($part[0],'dpc');
								//  calldpc_include($part[0],'dpc');	
					              $dpcmods[] = $part[0]; //hold dpc names												 
								} 
				                
			   }//switch
			   $i+=1; 
	   }//foreach		
	   return ($dpcmods); //return the array of included dpcs 
/*		 }
	     else
		   raise_error(3,'EXIT');
	   }
	   else
	     raise_error(0,'EXIT',"File '$projectfile' not exist\n"); */
	 }//if iniload
	 else
	   raise_error(1,'EXIT');   
   }
   
   //function calldpc_init() {
   public function init($accelerated=0) {        
   
      //ACCELERATE modules reading...
	  $t = new ktimer;
	  $t->start('compile');		  
      $modules = $this->compile($accelerated); //include and load project file's dpc lib,ext,dpc'  
	  $t->stop('compile');
	  echo "compile " , $t->value('compile');	  
	
	  if ($this->dacpost)
        $this->read_post_code(); //get batch readed post code as array to call after...
	  
	  //INCLUDE FIRST
	  $t->start('include');		  
	  foreach  ($modules as $id=>$dpc) {	  
	     //echo $dpc."<br>";
		 //$this->set_include($dpc,'dpc'); //MOVED TO SWITCH OF COMPILE 
		 
		 //post construct code
	     if (is_array(GetGlobal('__POSTCODE'))) {
		   $construct_function = create_function('$dpc',$this->get_code_of('construct',$dpc));
		   $construct_function($dpc);		    
		 }  
	  }
	  $t->stop('include');
	  echo "include " , $t->value('include');	  	    	 	  
   
       //ACCELERATE attributes reading...
	  $t->start('attr');	  
	  $this->load_attributes($accelerated); //overwrite build in attributes with db attr or file attr	   	 
	  $t->stop('attr');
	  echo "attr " , $t->value('attr');	  
	  
	  //INSTANCE CASE
	  $is_instance = paramload('ID','isinstance');
	  if ($is_instance) //must be include the clientdpc module
 	    $cdpc = new clientdpc;		  
			  	  
	  //SOME DPCS MUST EXECUTE THEIR COMMANDS BEFORE OTHER DPCS CONSTRUCTION
	  //(update vals at construct of these)
	  //so give a priorty to a dpc to be newed and execute their event(?) before new of others 	  
	  $action = GetGlobal('dispatcher')->get_command(1);//unofficial!!!!!
	  //echo ">>>>>",$action;	    
	  
	  foreach ($modules as $id=>$dpc) {

	    if ($this->get_attribute($dpc,$action,11)) {
		   //new it
		   //$this->_new($dpc,'dpc');
		   if (is_object($cdpc)) {
		       
		     if ($cdpc->is_client_dpc($dpc))
		       $this->_new($dpc,'dpc');   
		     else
		       die("$dpc not supported!");
		   }
		   else
		     $this->_new($dpc,'dpc'); 
		   
		   $this->event($action,$dpc);
		   $norenewdpc = $dpc;//used to overpass this dpc
		   //echo '()()))())(';
		}
	    //echo '.';
	  }
		 
	  //NEW AFTER (one by one as it writed in script)	 
	  //print_r($modules);
	  foreach  ($modules as $id=>$dpc) {
		 if ($dpc!=$norenewdpc) {
		   //$this->_new($dpc,'dpc'); 
		   if (is_object($cdpc))  {
		   
		     if ($cdpc->is_client_dpc($dpc))
		       $this->_new($dpc,'dpc');   
		     else
		       die("$dpc not supported!");
		   }
		   else
		     $this->_new($dpc,'dpc'); 
		 }  
		 //echo '>',$id,'=',$dpc;    
	  }		   
	 	  
   }


    //PUBLIC (replace require (see eventsrv.dpc))
	//       include the file and make a new object 
	//$dpc = name of dpc
	//$type = type (lib or dpc)
	//include and create a new dpc object (mode:one by one) 
	public function init_object($dpc,$type) {
      global $__DPC,$__DPCSEC,$__DPCMEM,$__ACTIONS,$__EVENTS,$__LOCALE,$__PARSECOM,
             $__BROWSECOM,$__BROWSEACT,$__PRIORITY,$__QUEUE,$__DPCATTR,$__DPCPROC;	

	  global $activeDPC,$info,$xerror,$GRX,$argdpc;  //IMPORTANT GLOBALS!!!
	  
	  global $__DPCOBJ; //holds objects of new approach of name of type xxx.yyy
	  global $__DPCID; //array of new name alias  	  	    	  	  

	  //SHARED MEM
	  /*if ($this->shm) {
	    $shm_text = $this->load_shm_dpc(str_replace(".","/",$dpc).".$type.php",$this->shm_id,$this->shm_addr,$this->shm_length);	  
		//echo $shm_text;
	  }	*/
      //echo $dpc,"\n";  

      $argdpc = _DPCPATH_;// paramload('DIRECTIVES','dpc_type');	  
	  //echo $argdpc,'>';
	  if ($this->shm) {//SHARED MEM 
	    if (GetGlobal('__USERAGENT')=='HTML')
	      $includer = "phpdac5://127.0.0.1:19123/" . str_replace(".","/",trim($dpc)) . "." . $type . ".php";
		else	  
	      $includer = "phpdac://" . str_replace(".","/",trim($dpc)) . "." . $type . ".php";
	  }	
	  else	  
	    $includer = $argdpc . "/" . str_replace(".","/",trim($dpc)) . "." . $type . ".php";
	  //echo $includer,'|',$argdpc,'|',_DPCPATH_,'<br>';	
	  require($includer);	//REQUIRE NOT REQUIRE ONCE DUE TO RE-INIT DPC
	  	  
	  //START THE OBJECT
      $parts = explode(".",trim($dpc)); 
	  $class = strtoupper($parts[1]).'_DPC';
	  
	  //update local table
      $this->make_local_table($class);	  
	  
	  if ((defined($class)) &&
	      (class_exists($__DPC[$class])) ) {
		//echo '>>>',strtoupper($parts[1]),'_DPC','=',$__DPC[strtoupper($parts[1]).'_DPC'];
	    //SetGlobal('__DPCMEM[$class]',& new GetGlobal('__DPC[$class]');
		$__DPCMEM[$class] =  & new $__DPC[$class]; 
		$__DPCOBJ[$dpc] =  & $__DPCMEM[$class];//alias of new name object array
		$__DPCID[$class] = $dpc; //new name index array
		return TRUE;
	  }	  
	
	  return FALSE;  	  
	}
		
	//alias of _new(parent).....init_object,,,......(mode:one by one)
	function calldpc($dpcexpression,$type='dpc') {
	   //echo $dpcexpression,"\n";
       return $this->init_object($dpcexpression,$type);
       //return $this->_new($dpcexpression,$type);	   
	}	
	
	
	//call a dpc method using params as use a+b+c
	function calldpc_method($dpcmethodequalvars,$noerror=null) {
	  $__DPCMEM = GetGlobal('__DPCMEM');
	  $__DPC = GetGlobal('__DPC');
	  $__ACTIONS = GetGlobal('__ACTIONS');		  
	  
	  $part = explode(" use ",$dpcmethodequalvars); //echo $dpcmethodequalvars,">>>>";
	  $dpcmethod = explode(".",trim($part[0]));	//echo $dpcmethod[0],">>>>";
	  $method = trim($dpcmethod[1]);	
	  $class = strtoupper(trim($dpcmethod[0])).'_DPC';
	  $var = explode("+",trim($part[1]));    
	  
	  if ((defined($class)) &&
	      (is_object($__DPCMEM[$class])) &&
	      (method_exists($__DPCMEM[$class],$method))) {
	  
	    //echo strtoupper(trim($dpcmethod[0])).'_DPC'; 
		$ret = $__DPCMEM[$class]->$method($var[0],$var[1],$var[2],$var[3],$var[4],$var[5],$var[6],$var[7],$var[8],$var[9],$var[10],$var[11],$var[12],$var[13],$var[14],$var[15],
		                                  $var[16],$var[17],$var[18],$var[19],$var[20],$var[21],$var[22],$var[23],$var[24],$var[25],$var[26],$var[27],$var[28],$var[29],$var[30]);
		
		return ($ret);
	  }
	  else {
	    //TRY TO FIND CALLS THAT INHERIT FROM CALLED METHOD...
		//get method form inherited class (automated)
		if ($child_class = $this->find_child_method($method,$__DPCMEM)) {
		  $inherited_class = strtoupper(trim($child_class)).'_DPC';
		  $ret = $__DPCMEM[$inherited_class]->$method($var[0],$var[1],$var[2],$var[3],$var[4],$var[5],$var[6],$var[7],$var[8],$var[9],$var[10],$var[11],$var[12],$var[13],$var[14],$var[15],
		                                              $var[16],$var[17],$var[18],$var[19],$var[20],$var[21],$var[22],$var[23],$var[24],$var[25],$var[26],$var[27],$var[28],$var[29],$var[30]);
		
		  return ($ret);		
		}
		  
	    if (!isset($noerror)) 
		  die("(".trim($dpcmethodequalvars).") is not a dpc object or method not exist !\n");
	  }	
	}	
			
	
	//call a dpc method using vars as array of pointers
	function calldpc_method_use_pointers($dpcmethod,$par,$noerror=null) {
	  $__DPCMEM = GetGlobal('__DPCMEM');
	  $__DPC = GetGlobal('__DPC');		  
	  
	  $part = explode(".",trim($dpcmethod));
	  $class = strtoupper(trim($part[0])) . '_DPC';
	  $method = trim($part[1]);	 
	  
	  if ((defined($class)) &&
	      (is_object($__DPCMEM[$class])) &&
	      (method_exists($__DPCMEM[$class],$method))) {
	  
		$ret = $__DPCMEM[$class]->$method($par[0],$par[1],$par[2],$par[3],$par[4],$par[5],$par[6],$par[7],$par[8],$par[9],$par[10],$par[11],$par[12],$par[13],$par[14],$par[15],
		                                  $par[16],$par[17],$par[18],$par[19],$par[20],$par[21],$par[22],$par[23],$par[24],$par[25],$par[26],$par[27],$par[28],$par[29],$par[30]);		
		
		return ($ret);
	  }
	  else {
	  
	    //TRY TO FIND CALLS THAT INHERIT FROM CALLED METHOD...
		//get method form inherited class (automated)
		if ($child_class = $this->find_child_method($method,$__DPCMEM)) {
		  $inherited_class = strtoupper(trim($child_class)).'_DPC';
		  $ret = $__DPCMEM[$inherited_class]->$method($par[0],$par[1],$par[2],$par[3],$par[4],$par[5],$par[6],$par[7],$par[8],$par[9],$par[10],$par[11],$par[12],$par[13],$par[14],$par[15],
		                                              $par[16],$par[17],$par[18],$par[19],$par[20],$par[21],$par[22],$par[23],$par[24],$par[25],$par[26],$par[27],$par[28],$par[29],$par[30]);
		
		  return ($ret);		
		}
			  
	    if (!isset($noerror)) 
		  die("(".trim($dpcmethod).' use *'.implode(',',$par).") is not a dpc object or method not exist !\n");
	  }	
	}		
	
	//return a dpc var
	function calldpc_var($dpcvar,$value=null) {
	  $__DPCMEM = GetGlobal('__DPCMEM');	  
	  
	  $part = explode(".",$dpcvar);
	  $classpart = strtoupper(trim($part[0])) . '_DPC';
	  $varpart = trim($part[1]);
	  
	  //if (defined(strtoupper(trim($part[0])).'_DPC')) {
	  if ((defined($classpart)) &&
	      (is_object($__DPCMEM[$classpart]))) {   
	  
	    //echo strtoupper($dpc).'_DPC';
		if ($value) {
		  
		  $__DPCMEM[$classpart]->$varpart = $value;
		  SetGlobal('__DPCMEM',$__DPCMEM);
		  return ($value); 
		}  
		else {
		
		  $ret = $__DPCMEM[$classpart]->$varpart;
		  return ($ret);
		}  
	  }	
	  else {
	  
	    //TRY TO FIND CALLS THAT INHERIT FROM CALLED METHOD...
		//get method form inherited class (automated)
		if ($child_class = $this->find_child_method($varpart,$__DPCMEM)) {
		  $inherited_class = strtoupper(trim($child_class)).'_DPC';
		  
		  if ($value) {
		    $__DPCMEM[$inherited_class]->$varpart = $value;
		    SetGlobal('__DPCMEM',$__DPCMEM);
		    return ($value); 		  		  
		  }
		  else {
		    $ret = $__DPCMEM[$inherited_class]->$varpart;
		    return ($ret);		
	      }		
		}	  
	  
	    die($part[0]." is not a dpc object !\n");	  	  
	  }	
	}
	
	//TRY TO FIND METHOD CALLS IN CODE like (inheritedclass.viewcart) THAT INHERIT FROM PARENTS CLASS (like parentclass.viewcart)
	//BUT STILL CALLED FROM RUNTIME CODE AS PARENTS COMMANDS...
	//THIS FUNCTION ON ERROR FIND THE INHERITED CLASS AUTOMATICALLY AND REPLACE CURRENT DPC COMMAND TO inheritedclass.viewcart
	function find_child_method($method,$dpcarray) {
	
	  foreach ($dpcarray as $class) {
	    if ((is_object($class)) && (method_exists($class,$method)))
		  return get_class($class);
	  }
	  
	  return null;
	}		
	
	//call a extension ext.php class file as is
    protected function set_extension($extension,$defname,$noerror=null) {
     //echo $extension,"\n";
	 if (!defined($defname)) {
	 
	   define($defname,'true');

	   /*if ($this->shm)	   //SHARED MEM ........PROBLEM WITH ADODB
	     $includer = "phpdac://" . "system/extensions/" . str_replace(".","/",$extension) . ".ext.php";
	   else*/ 		
	   	 //replaced below 	   
	     //$includer = "extensions/" . str_replace(".","/",$extension) . ".ext.php";	   
	   
        $argdpc = _DPCPATH_; //paramload('DIRECTIVES','dpc_type');
	  	 
	    if ($this->shm) {
	      if (GetGlobal('__USERAGENT')=='HTML')
	        $includer = "phpdac5://127.0.0.1:19123/" . str_replace(".","/",trim($extension)) .  ".ext.php";
		  else
		    $includer = "phpdac://" . str_replace(".","/",trim($extension)) . ".ext.php";  
	    }	
	    else 
	      $includer = $argdpc . "/system/extensions/" . str_replace(".","/",trim($extension)) . ".ext.php";	
		
	   //echo $defname;           
	   //echo $includer; 		
	   require_once($includer);	  		   
	   
	   return TRUE;
	 }
	 else {
	   if (!$noerror) die("$extension defined more than once!");  
	   return FALSE;
	 }  
    }	
	
	//just include the spec dpc (mode:batch)
	protected function set_include($dpc,$type) {
      global $__DPC,$__DPCSEC,$__DPCMEM,$__ACTIONS,$__EVENTS,$__LOCALE,$__PARSECOM,
             $__BROWSECOM,$__BROWSEACT,$__PRIORITY,$__QUEUE,$__DPCATTR,$__DPCPROC;	  

	  global $activeDPC,$info,$xerror,$GRX,$argdpc; 	//IMPORTANT GLOBALS!!!  
	
	  //echo $dpc,"\n";
      $argdpc = _DPCPATH_; //paramload('DIRECTIVES','dpc_type');
	  	 
	  if ($this->shm) {
	    if (GetGlobal('__USERAGENT')=='HTML')
	      $includer = "phpdac5://127.0.0.1:19123/" . str_replace(".","/",trim($dpc)) . "." . $type . ".php";
		else
		  $includer = "phpdac://" . str_replace(".","/",trim($dpc)) . "." . $type . ".php";  
	  }	
	  else 
	    $includer = $argdpc . "/" . str_replace(".","/",trim($dpc)) . "." . $type . ".php";

	  require($includer);	//REQUIRE NOT REQUIRE ONCE DUE TO RE-INIT DPC	
	  
	  //update local table
      $parts = explode(".",trim($dpc)); 
	  $class = strtoupper($parts[1]).'_DPC';	  
      $this->make_local_table($class);	  
	}
	
	
	//create a subclass of the parent object to re-define constructor
	protected function set_instance($dpc,$instname,$params=null) {
	  	
      global $__DPC,$__DPCSEC,$__DPCMEM,$__ACTIONS,$__EVENTS,$__LOCALE,$__PARSECOM,
             $__BROWSECOM,$__BROWSEACT,$__PRIORITY,$__QUEUE,$__DPCATTR,$__DPCPROC;	  

	  global $activeDPC,$info,$xerror,$GRX,$argdpc; 	//IMPORTANT GLOBALS!!!  
	  
	  $__DPC = GetGlobal('__DPC');	  
	  
      $parts = explode(".",trim($dpc)); 
	  $parentclass = strtoupper($parts[1]).'_DPC';	//echo $parentclass;  
	  $idpc =  $__DPC[$parentclass];	
	  //print_r($__DPC);
	  
	  //if parent class not exist load it!
	  if (!class_exists($idpc)) {
	    //$this->set_include($dpc,'dpc');//NOT WORK BECAUSE OF ACTION 
		//echo $dpc;
		$this->calldpc($dpc,'dpc');
		$__DPC = GetGlobal('__DPC');//re-loadit afer include	
		$idpc =  $__DPC[$parentclass];	  
	  }		
	  
	  if (class_exists($idpc)) {
	
        $x  = "class $instname extends $idpc" . ' {';
	    $x .= 'function __construct() {';
	    $x .= $parts[1]."::".$parts[1]."();";
	    //now we must pass the init params
		if (isset($params)) {
	      $params_array = explode(",",$params);
	      foreach ($params_array as $id=>$val) {
	        if (isset($val)) {   
	          $parts = explode('=',$val);
	          $x .= '$this->' . $parts[0] . " = '" . $parts[1] . "';";
		    }  
	      }
		}  	
	    $x .= '}';
        $x .= '};';
	  
	    //echo $x;
        @eval($x);	
	  
	    //$instname = new $instname;
        $this->_newinstance2($instname); 	
	  }
	  else
	    die("Error: There is not [$dpc] class to extend!");    	  
	}			
	
    //free dpc resources
    //function calldpc_free() {	
    public function free() {	
	  $__DPCMEM = GetGlobal('__DPCMEM');
	  $__DPCATTR = GetGlobal('__DPCATTR');		    
	  $__DPC = GetGlobal('__DPC');	  
	  $__DPCOBJ = GetGlobal('__DPCOBJ');  	  
	  $__DPCID = GetGlobal('__DPCID'); 	  
   
      if (is_array($__DPC)) {
	  reset($__DPC);
	  //print_r($__DPC);
      while (list ($dpc,$classname) = each ($__DPC)) {
		  
	      if ((defined($dpc)) &&
	          (is_object($__DPCMEM[$dpc])) &&
	          (method_exists($__DPCMEM[$dpc],'free'))) {
			  
		    //post? pre destruction code
		    if (is_array(GetGlobal('__POSTCODE'))) {
		      $destruct_function = create_function('$dpc',$this->get_code_of('destruct',$__DPCID[$dpc]));
		      $destruct_function($__DPCID[$dpc]);				  
			}  
			    
		    $__DPCMEM[$dpc]->free();	
          }				  
					  
		  $__DPCMEM[$dpc] = null; 
		  $__DPCATTR[$dpc] = null;
		  $__DPCOBJ[$dpc] =  null;//alias of new name object array	
		  $__DPCID[$dpc] = null;		  
		  //echo $dpc," unallocated\n";   		  		  	  
	      SetGlobal('__DPCMEM',$__DPCMEM);
	      SetGlobal('__DPCATTR',$__DPCATTR);	  		  
	      SetGlobal('__DPCOBJ',$__DPCOBJ);		  
		  SetGlobal('__DPCID',$__DPCID);		  	 	
	  } 
	  }
    } 	 
	
    //used at pcli 
	public function reset($action) { 	
	  $activeDPC = GetGlobal('activeDPC');	  
	  
      if ($this->get_attribute($activeDPC,$action,4)) {		
	        //echo $activeDPC;
		    $this->free();		   
		    $this->init();		
	  }
	}

	//if dpc_init is set then dpc has priority and executed before new of others 
    public function event($action,$dpc_init=null) {   
	   $__DPCMEM = GetGlobal('__DPCMEM');
	   $__DPC = GetGlobal('__DPC');		 
       $__EVENTS = GetGlobal('__EVENTS');		    
       $__DPCPROC = GetGlobal('__DPCPROC');	
       $__DPCID = GetGlobal('__DPCID');		   
	   
	   $i = 1;
	   $step = 0;
	   $EVENT_QUEUE = array(); //holds multiple commands	      
	   
       //select common events ordered by priority	   
       if ($action) {
	     if ($dpc_int) { //has init priority
		 
		   if ( (seclevel($dpc_init,decode(GetSessionParam('UserSecID')))) && //check if allowed
		        (in_array($action,$__EVENTS[$dpc_init])) &&  //check if action included in current dpc
		        (class_exists($__DPC[$dpc_init]))  ) {//check if dpc has initialized

				$__DPCMEM[$dpc_init]->event($action);
		   }
		 }
		 else {
	       reset($__EVENTS); //print_r($__EVENTS);
           foreach ($__EVENTS as $dpc_name => $command) {
		   
		     if (seclevel($dpc_name,decode(GetSessionParam('UserSecID')))) {//check if allowed		   		            
		   
			   if ((is_array($command)) && (in_array($action,$command))) {  //check if action included in current dpc
			     //print $val;				
				 
	             if (class_exists($__DPC[$dpc_name])) {  //check if dpc has initialized 		     
				 
	   	           //$__DPCMEM[$dpc_name]->event($action);
				   //if (!calldpc_var(strtolower(str_replace("_DPC","",$dpc_name))."._CONTINUE")) return 0;	//one event		
				   //if (!getdpc_attribute($dpc_name,$action,9)) return 0;	 //one action
				    
				   $p = $__DPCPROC[$dpc_name];
                   $q = (($p ?  $p : $i++)) * 1000; //priority 1000 = start
				   if (array_key_exists($q,$EVENT_QUEUE)) {
					 $step+=1;
					 $EVENT_QUEUE[$q+$step] = $dpc_name;				   
				   }  
				   else				   
				     $EVENT_QUEUE[$q] = $dpc_name;				   
				   
				   //one event or last event 
				   //if (!getdpc_attribute($dpc_name,$action,9)) break 1; //?????(no serial implementation in prj file)
				   
				   //event dac preset------------------------------------
				   if (isset($this->dac))
				     $this->dac_event($event,$__DPCID[$dpc_name],$__DPC[$dpc_name]);				   
				   	 			  
				 } 		 	 
		       }
			 }  
	       }
		   
		   //break =  end of multiple events or end of loop
		   
		   //start event queue
		   reset($EVENT_QUEUE); 
		   //execute by priority	
		   ksort($EVENT_QUEUE);	//print_r($EVENT_QUEUE);   
		   foreach ($EVENT_QUEUE as $priority=>$dpc_name) { 
			 //echo $dpc_name,$action,"<br>"; 		   
		     $__DPCMEM[$dpc_name]->event($action);
			 
			 //post event code
		     if (is_array(GetGlobal('__POSTCODE'))) {
			   $action_function = create_function('$dpc,$event',$this->get_code_of('event',$__DPCID[$dpc_name]));
			   $action_function($__DPCID[$dpc_name],$action);			 
			 }  
			 
		   }	
		}	 
		return 0;   
	  }
    }
	
	private function dac_event($event,$dpcname,$environment) {
	  	
	  //PRIVATE=PROJECT SAVED file = has priority
	  $path = paramload('SHELL','prpath'); //echo $path;
      $module_dac_file = $path . $dpcname;
	  //echo $module_dac_file;
	  //DEFAULT DPC SAVED file
      $path = paramload('DIRECTIVES','dpc_type');	  
	  $dpc_module_dac_file = $path . "/" . str_replace(".","/",$dpcname); 	  

	  //select extension per agent
	  switch (GetGlobal('__USERAGENT')) {
	    case 'HDML' : //same as html
	    case 'HTML' : $module_dac_file .= ".php-html"; 
		              $dpc_module_dac_file .= ".php-html";
		              break;
		case 'SH'   : //same as cli
		case 'TEXT' : //same as cli
	    case 'CLI'  : $module_dac_file .= ".php-cli"; 
		              $dpc_module_dac_file .= ".php-cli"; 
		              break;
		case 'SHGTK': //same as gtk
	    case 'GTK'  : $module_dac_file .= ".php-dac";//-gtk=stand alone apps	
		              $dpc_module_dac_file .= ".php-dac"; 
					  break;
					
		default     : //$module_dac_file .= ".php-html";
	  }
	  
	  $loaded=0;
	  if (is_file($module_dac_file)) {
  	        //echo $module_dac_file;
		    require_once($module_dac_file);
			$loaded=1;
	  }
	  elseif (is_file($dpc_module_dac_file)) {
  	        //echo $dpc_module_dac_file;
		    require_once($dpc_module_dac_file);
			$loaded=1;	  
	  }
	  if ($loaded) {		
			$classname = str_replace(".","_",$dpcname);
		    $this->module_dac = &new $classname(&$environment);
			//print_r(get_class_methods($this->module_dac));
			
			//$ver = explode(".",phpversion());
			//if (($ver[0]<5) && (method_exists($module_dac,'action'))) 
			if (method_exists($this->module_dac,'event')) {
			  //echo 'event:',$event;
			  $this->module_dac->event($event);
			}  
	  }	      
	}	
    	  
    public function action($action) {  
	       $__DPCMEM = GetGlobal('__DPCMEM');
	       $__DPC = GetGlobal('__DPC');		 
           $__DPCPROC = GetGlobal('__DPCPROC');		    
           $__ACTIONS = GetGlobal('__ACTIONS');	
           $__DPCID = GetGlobal('__DPCID');			   	   		   	

		   $ret = null;
		   $i = 1;
		   $step = 0;
	       $ACTION_QUEUE = array(); //holds multiple commands			      		    

		   //select common action ordered by priority
		   if ($action) {
	         reset($__ACTIONS); //print_r($__ACTIONS);
             foreach ($__ACTIONS as $dpc_name => $command) {		
			 	 		   
			   if (seclevel($dpc_name,decode(GetSessionParam('UserSecID')))) {//check if allowed

				 if ((is_array($command)) && (in_array($action,$command))) { //check if action included in current dpc
   		       							   
		           if (class_exists($__DPC[$dpc_name])) { //check if dpc has initialized

	   	             //$ret .= $__DPCMEM[$dpc_name]->action($action); //the above not work for all objects ??
					 //OLD METHOD TO CONTINUE
				     //if (!calldpc_var(strtolower(str_replace("_DPC","",$dpc_name))."._CONTINUE")) return ($ret);	 //one action	
					 //NEW METHOD
					 //if (!getdpc_attribute($dpc_name,$action,9)) return ($ret);	 //one action	
					 
					 $p = $__DPCPROC[$dpc_name];
                     $q = (($p ?  $p : $i++)) * 1000; //priority 1000=start
					 
					 if (array_key_exists($q,$ACTION_QUEUE)) {
					   $step+=1;
					   $ACTION_QUEUE[$q+$step] = $dpc_name;				   
					 }  
					 else  
				       $ACTION_QUEUE[$q] = $dpc_name;				   
				   
				     //one action or last action
				     //if (!getdpc_attribute($dpc_name,$action,9)) break 1;	//?????(no serial implementation in prj file)		 
					 
					 //action dac preset------------------------------------
					 if (isset($this->dac))
					   $ret .= $this->dac_action($action,$__DPCID[$dpc_name],$__DPC[$dpc_name]);
					 
				   }	 	    
		         }
			   } 
	         }
			 
		     //break =  end of multiple actions or end of loop
			 
			 //start action queue
		     reset($ACTION_QUEUE); 
			 //execute by priority
			 ksort($ACTION_QUEUE); //print_r($ACTION_QUEUE);
		     foreach ($ACTION_QUEUE as $priority=>$dpc_name) { 
			   //echo $dpc_name,$action,"<br>"; 
		       $ret .= $__DPCMEM[$dpc_name]->action($action);	
			   
			   //post action code
			   if (is_array(GetGlobal('__POSTCODE'))) {
			     $action_function = create_function('$dpc,$action',$this->get_code_of('action',$__DPCID[$dpc_name]));
			     $ret .= $action_function($__DPCID[$dpc_name],$action);
			   }
			   
			   //remote log
			   //$this->remote_log($dpc_name,'hermes',82);//MOVED TO SHELL
			 }  	 
			   
			 return ($ret); 
		   }
		   
		   return null;		   	      
    }	
	
	private function dac_action($action,$dpcname,$environment) {
	  
	  //if object has alredy newed at event... 
	  if ((is_object($this->module_dac) && 
	      (method_exists($this->module_dac,'action')))) {
		//echo 'action:',$action;  
	   	$ret = $this->module_dac->action($action);
		unset($this->module_dac);//destruct it
	  }	
	  else {//create it...
	    //PRIVATE=PROJECT SAVED file = has priority
	    $path = paramload('SHELL','prpath'); //echo $path;
        $module_dac_file = $path . $dpcname;
	    //echo $module_dac_file;
	    //DEFAULT DPC SAVED file
        $path = paramload('DIRECTIVES','dpc_type');	  
	    $dpc_module_dac_file = $path . "/" . str_replace(".","/",$dpcname); 	

	    //select extension per agent
	    switch (GetGlobal('__USERAGENT')) {
	      case 'HDML' : //same as html
	      case 'HTML' : $module_dac_file .= ".php-html"; 
		                $dpc_module_dac_file .= ".php-html";
		                break;
		  case 'SH'   : //same as cli
		  case 'TEXT' : //same as cli
	      case 'CLI'  : $module_dac_file .= ".php-cli"; 
		                $dpc_module_dac_file .= ".php-cli"; 
		                break;
		  case 'SHGTK': //same as gtk
	      case 'GTK'  : $module_dac_file .= ".php-dac";//-gtk=stand alone apps	
		                $dpc_module_dac_file .= ".php-dac"; 
			  		    break;
					
		  default     : //$module_dac_file .= ".php-html";
	    }
	  
	    $loaded=0;
	    if (is_file($module_dac_file)) {
  	        //echo $module_dac_file;
		    require_once($module_dac_file);
			$loaded=1;
	    }
	    elseif (is_file($dpc_module_dac_file)) {
  	        //echo $dpc_module_dac_file;
		    require_once($dpc_module_dac_file);
			$loaded=1;	  
	    }
	    if ($loaded) {	
			
			$classname = str_replace(".","_",$dpcname);
		    $this->module_dac = &new $classname(&$environment);
			
			//$ver = explode(".",phpversion());
			//if (($ver[0]<5) && (method_exists($module_dac,'action'))) 
			if (method_exists($this->module_dac,'action')) {
		      //echo 'action:',$action;  
	   	      $ret = $this->module_dac->action($action);
		      unset($this->module_dac);//destruct it
			}  
	    }	 	  
	  }
	  
 	  return ($ret);
	}	    
	

	//find dpc name based on current action
	//function calldpc_active($action) {  	
	public function active($action) {   

	   $__DPCMEM = GetGlobal('__DPCMEM');
	   $__DPC = GetGlobal('__DPC');		 
       $__EVENTS = GetGlobal('__EVENTS');		    
       $__ACTIONS = GetGlobal('__ACTIONS');		    	          
	   
       if ($action) {
	     reset($__EVENTS);
         foreach ($__EVENTS as $dpc_name=>$commarray ) {
		   if ((is_array($commarray)) && (in_array($action,$commarray))) {
		     //print_r($commarray);
			 //echo $dpc_name;
			 return ($dpc_name); 
		   }	 
         }
	     reset($__ACTIONS);
         foreach ($__ACTIONS as $dpc_name=>$commarray ) {
		   if ((is_array($commarray)) && (in_array($action,$commarray))) {
		     //print_r($commarray);
			 //echo $dpc_name;		 
			 return ($dpc_name); 
		   }	 
         }		 
       }
	   
	   return (false);
    }
	//return actions array based on dpc name
	protected function get_dpcactions_array($dpc) {
	
	  $dpcactions = GetGlobal('__ACTIONS');
	  if (!empty($dpcactions[$dpc]))
	    return ($dpcactions[$dpc]);
	  else
	    return ($x=array());	
	}
	//return actions array based on action name
	protected function get_actions_array($action) {
	
	  $dpc = $this->active($action); 
	  $dpcactions = GetGlobal('__ACTIONS');
	  return ($dpcactions[$dpc]);
	}	
	
	//get dpc command (execusion,appearance) attributes (ssl,fullscreen,etc)
	//0 = command name
	//1 = SSL support
	//2 = fullscreen
	//3 = frontapage fullscreen
	//4 = reset dpc
	//5 = reset shell
	//6 = exclude command
	//7 = supercache
	//8 = action in window
	//9 = continue ...
	//10= webservice
	//11= new and execute before others new at init
	//12= page cntl logic enabled = path
	function get_attribute($dpc,$cmd,$what=0) {
	  $__DPCATTR = GetGlobal('__DPCATTR');
	  $__DPCID = GetGlobal('__DPCID');	//print_r($__DPCID);?????  
	  
	  //DPC name COMPLIANCE
	  if (strstr($dpc,".")) {//. appeared name (new type compliance)
		  //dpcid has old dpc as key .. i want the opposite
		  //for now..
		  $p = explode('.',$dpc);
		  $dpc = strtoupper($p[1]).'_DPC';
	  }	  	  
	  
	  //if (is_array($__DPCATTR[$dpc])) {	  
	  if (isset($__DPCATTR[$dpc][$cmd])) {
	    //print_r($__DPCATTR[$dpc]);
	    $element = $__DPCATTR[$dpc][$cmd];  
		//echo $element;
	    $attr = explode(",",$element);
	    //print_r($attr); echo "<br>";
	   
	    return $attr[$what];
	  }	
	  
	  return null;
	}			
	
	
	//function for post code batch read at init...
	function read_post_code($dpc=null) {
	
	   $path = paramload('SHELL','prpath');
	   
	   if (is_dir($path)) {
          $mydir = dir($path);
		 
          while ($fileread = $mydir->read ()) {	
		  
		    if (strstr($fileread,".cnn")) { //all cnn files
			//if (strstr($fileread,"@".$dpc)) {  //only current action files
			
			  $parts = explode('@',$fileread); $method = $parts[0];
			  $idpcs = explode("^",$parts[1]); $cdpc = $idpcs[0];
			  
			  $postcode[$method][$cdpc][] = file_get_contents($path.$fileread);  
			} 
		  }
		  
		  //print_r($postcode);
		  $mydir->close();
		  
          SetGlobal('__POSTCODE',$postcode);		  
	   }	 
	}
	
	function get_code_of($method,$dpc) {
	  $postcode = GetGlobal('__POSTCODE');
	  
	  //echo $method,'@',$dpc;
	  //print_r($postcode);
	
	  //$file = paramload('SHELL','prpath') . $method . "@" . $dpc . "^";
	  //echo $file,"\n";
	  //if (is_file($code)) {
      //if (seclevel($dpc_name,decode(GetSessionParam('UserSecID')))) {//check if allowed	  
	  //}
	  
	  $ff = $postcode[$method][$dpc][0];
	  //print_r($postcode[$method][$dpc]);
	  if ($ff) echo $dpc,'->',$method,":",$ff,"<br>";
	  
	  return ($ff); 
	}
	
	//experimental
	function interpret_post_code($dpc,$code) {
	  $scope = GetGlobal('__SCOPE'); 
	  
	  $tokens = explode(";",$code);
	  
	  foreach ($tokens as $id=>$token) {
	  
	    $prev_token = $tokens[$id-1];
		$next_token = $tokens[$id+1];
		$curr_token = $token;
		
		if (substr($curr_token,0,1)=='$') {
		
		  $part = explode('=',$curr_token);  
		
		  switch ($prev_token) {
		    
			case 'local'   : break;
			case 'global'  : SetGlobal($part[0],$part[0]); break;
			case 'property': $scope[$dpc][$part[0]] = $part[1]; break;
			default   :  
		  }
		}
	  }
	  
	  SetGlobal('__SCOPE',$scope);
	  print_r($scope);
	}
	
	//remote dpc log (called by shell)
	function remote_log($dpc,$host,$port,$pxhost=null,$pxport=null) {
	
	  if ((defined("_HTTPCL_")) && ($dpc)) {
	  
	    //$t_remotelog = new ktimer;
	    //$t_remotelog->start('remotelog');	
	 	  
	    //echo 'remote log:',$dpc;
        $server_http_connection = new http_class;	    
        $error = $server_http_connection->Open(array("HostName"=>$host,
	                                                 "HostPort"=>$port,
													 "ProxyHostName"=>$pxhost,
													 "ProxyHostPort"=>$pxport));
        if (!$error) {
         $ret = $server_http_connection->SendRequest(array(
                                                          "RequestURI"=>"/?t=slog&dpc=$dpc",
                                                          "Headers"=>array(
                                                          "Host"=>"$host:$port",
                                                          "User-Agent"=>"phpdac",
                                                          "Pragma"=>"no-cache"
														 ))); 	
		}												 
        else {
		  echo $error;														 
		}  
		$server_http_connection->Close();												 
														 
 	    //$server_http_connection = new httpcl;	
		//$ret = $server_http_connection->request_get('phpdac','hermes:82',"t=slog&dpc=$dpc",0,0,82,1);    													   	  
		
	    //$t_remotelog->stop('remotelog');
   	    //if (seclevel('_TIMERS',$this->userLevelID))
		//echo "remotelog " , $t_remotelog->value('remotelog'); 			
	  }													 
	}	
	
	function __destruct() {
	
	  sysdpc::__destruct();
	}
};
}
?>