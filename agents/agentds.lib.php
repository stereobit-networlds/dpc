<?php

class agentds extends network {

   var $daemon_ip;
   var $daemon_port;
   var $daemon_type;
   var $dmn;
   var $use;
   var $mysh;
   var $agent;
   
   var $agn_mem_type = 0;//shared vs convensional
   var $agn_mem_store;//string that holds serial data (string functions =0=)
   
   var $shm_id;
   var $agn_shm_id;
   
   var $agn_addr;
   var $agn_length;
   var $shared_buffer;//used by shared shm functions -1-
   var $agn_attr;
   
   //environment vars
   var $env;

   var $active_agent,$active_o_agent; 
   var $resources,$timer,$scheduler;
   
   var $gtkds_conn;//handle gtkds connection
   var $window, $agentbox;   

   function agentds($dtype,$ip='127.0.0.1',$port='19125',$dacip='127.0.0.1',$dacport='19123') { 
	  $argc = $GLOBALS['argc'];
	  $argv = $GLOBALS['argv'];
	  //print_r($argv);
	  
	  $this->shm_id= null;
	  $this->agn_shm_id = null;
	  $this->agn_attr = array();
	  $this->agn_length = array();
	  $this->shared_buffer = null;
	  
	  $this->use = null;
	  $this->agent = 'SH';//default
      
	  if (($dtype == '-inetd') || ($dtype=='-standalone'))
	    $this->daemon_type = str_replace("-","",$dtype);
	  $this->daemon_ip = $ip;//'192.168.4.203';
	  $this->daemon_port = $port;//19123;
	  
	  //dac server
	  if ((isset($argc)) && (isset($argv[1])))
	    $this->phpdac_ip = $argv[1];
	  else	
	    $this->phpdac_ip = $dacip;
		
	  $this->phpdac_port = $dacport;
	  
	  echo("Phpdac5 repository at $this->phpdac_ip\n"); 
	  //echo $this->phpdac_ip,'>>>>>';
	  
 	  //REGISTER PHPDAC (client side)protocol...	MOVED TO TOP...  
      //require_once("dacstreamc.lib.php");			
	  //$phpdac_c = stream_wrapper_register("phpdac5","c_dacstream");
	  //if (!$phpdac_c) echo("Client dac protocol failed to registered!\n");
		//         else echo("Client dac protocol registered!\n"); 	
				 
 	  //REGISTER PHPAGN (client side,interconnections) protocol...
      //require_once("agnstreamc.lib.php");			
	  require_once("phpdac5://$this->phpdac_ip:$this->phpdac_port/agents/agnstreamc.lib.php"); 
	  
	  $phpdac_c = stream_wrapper_register("phpagn5","c_agnstream");
	  if (!$phpdac_c) echo("Client agent protocol failed to registered!\n");
		         else echo("Client agent protocol registered!\n"); 	

 	  //REGISTER PHPRES (client side,resources) protocol...
      //require_once("resstream.lib.php");			
      require_once("phpdac5://$this->phpdac_ip:$this->phpdac_port/agents/resstream.lib.php"); 
	  
	  $phpdac_c = stream_wrapper_register("phpres5","c_resstream");
	  if (!$phpdac_c) echo("Client resource protocol failed to registered!\n");
		         else echo("Client resource protocol registered!\n"); 
				 				 
	  //INITIALIZE ENVIRONMENT
	  //var_dump($_SERVER);
	  $this->env['name'] = $_SERVER['COMPUTERNAME'];  			 
	  $this->env['os'] = $_SERVER['OS'];	  
	  $this->env['domain'] = $_SERVER['USERDNSDOMAIN'];				 
	  $this->env['appdata'] = $_SERVER['APPDATA'];	  
	  $this->env['homepath'] = $_SERVER['HOMEPATH'];	  
	  $this->env['name'] = $_SERVER['COMPUTERNAME'];	
	  $this->env['host'] = $_SERVER['REMOTE_ADDR'];  
	  var_dump($this->env);				 			   
	  
	  /* LOADED AS AGENTS...removed from agents.exe as libs include
	  $this->timer = new timer;	
      //register_tick_function(array(&$time,"showtime"),true);	
	  $this->scheduler = new scheduler(&$this);		  			
	  
	  //init resources
	  $this->resources = new resources(&$this);	  
	  */
	  
	  //initialize GTk connector (for calling proc from this ($env) class ...purposes)
      echo("GTK connector loaded!\n");	  
	  $this->gtkds_conn = new gtkds_connector();
	  
	  
      //////////////////////////////////// gtk win
      if ($argv[3]==='-gtk') new gtkds(&$this,0);//connector init is off ..bellow loaded!
      /////////////////////////////////////	  
	  
	  
	  //INITIALIZE AGENTS
	  $this->active_agent = null;
	  $this->active_o_agent = null;	  
	  //if ($this->exist_dpc_server('127.0.0.1','19123'))
	    $this->init_agents($this->phpdac_ip,$this->phpdac_port);
	  //else
	    //echo "DAC Server not on-line\n";			
	  //print_r($this->get_agent('scheduler'));
	  
      //register_tick_function(array($this->get_agent('scheduler'),"checkschedules"),true);	  
	  register_tick_function(array(&$this,"tick_dummy"),true);
	  //declare(ticks=2) {		  
	  
	  //initialize task from already loaded agents (BEWARE TO LOAD THE DEFAULT AGENTS)
      $this->get_agent('scheduler')->schedule('env.show_connections','every','20');
  	  
	  
	  $this->startdaemon();  
	  
	  //}
	  
   }
   
   function destroy() {
	 Gtk::main_quit();
   }   
   
   function tick_dummy() {
   }
   
   function startdaemon() {
   
      $this->dmn = new daemon($this->daemon_type,true);//'standalone',false);
      $this->dmn->setAddress ($this->daemon_ip);//'127.0.0.1');
      $this->dmn->setPort ($this->daemon_port);
      $this->dmn->Header = "PHPDAC5 Agent Server (v0.0.1a) at ". $this->env['name'];

      $this->dmn->start ('phpagn5>');  //this routine creates a socket	
	  
      $this->dmn->setCommands (array ("help", "quit", "date", "shutdown","echo","silence",
	                                  "ver", "callagent", "uncall", "callagentc", "call", "helo", "run", "net",
									  "create", "destroy", "show", "move", "accept", "print",  
									  "getresource", "getresourcec", "showresources", 
									  "findresource", "findresourcec", "setresource", "delresource",
									  "checkschedules", "showschedules", "who", "startgtk",
									  "***"));
      //list of valid commands that must be accepted by the server	
	  
      $this->dmn->CommandAction ("help", array($this,"command_handler")); //add callback
      $this->dmn->CommandAction ("quit", array($this,"command_handler")); // by calling 
      $this->dmn->CommandAction ("date", array($this,"command_handler")); //this routine
      $this->dmn->CommandAction ("shutdown", array($this,"command_handler"));
	  
      $this->dmn->CommandAction ("echo", array($this,"command_handler"));	  
      $this->dmn->CommandAction ("silence", array($this,"command_handler"));		  
	  
      $this->dmn->CommandAction ("ver", array($this,"command_handler"));	  	
      $this->dmn->CommandAction ("callagent", array($this,"command_handler"));	
      $this->dmn->CommandAction ("call", array($this,"command_handler"));//alias	    
      $this->dmn->CommandAction ("uncall", array($this,"command_handler"));	  
      $this->dmn->CommandAction ("callagentc", array($this,"command_handler"));//client version quit after		  
      $this->dmn->CommandAction ("helo", array($this,"command_handler"));	
      $this->dmn->CommandAction ("run", array($this,"command_handler"));
      $this->dmn->CommandAction ("net", array($this,"command_handler"));	  

      $this->dmn->CommandAction ("create", array($this,"command_handler"));	  
      $this->dmn->CommandAction ("destroy", array($this,"command_handler"));	  
      $this->dmn->CommandAction ("show", array($this,"command_handler"));
      $this->dmn->CommandAction ("move", array($this,"command_handler"));
      $this->dmn->CommandAction ("accept", array($this,"command_handler"));	  	  	  	
      $this->dmn->CommandAction ("print", array($this,"command_handler"));		  	   
      $this->dmn->CommandAction ("getresource", array($this,"command_handler"));	 	  	  
      $this->dmn->CommandAction ("getresourcec", array($this,"command_handler"));	  
      $this->dmn->CommandAction ("showresources", array($this,"command_handler"));		  
      $this->dmn->CommandAction ("findresource", array($this,"command_handler"));	  
      $this->dmn->CommandAction ("findresourcec", array($this,"command_handler"));			  
      $this->dmn->CommandAction ("setresource", array($this,"command_handler"));	  
      $this->dmn->CommandAction ("delresource", array($this,"command_handler"));		  
      $this->dmn->CommandAction ("checkschedules", array($this,"command_handler"));	
      $this->dmn->CommandAction ("showschedules", array($this,"command_handler"));
      $this->dmn->CommandAction ("who", array($this,"command_handler"));
      $this->dmn->CommandAction ("startgtk", array($this,"command_handler"));	  
	  	  	  	  	  	  	  	  
	  $this->dmn->CommandAction ("***", array(&$this,"agent_handler"));//handle everyting else...	  
	  
	  //$this->dmn->RegisterAction(array(&$this,'timer'));
	  
      $this->dmn->listen(1); 	    	       
   }
   
   //general purpose commands
   function command_handler ($command, $arguments, $dmn) {
   
     switch ($command) {
        case 'HELP':
                //commands are converted to uppercase by default. If you want to
                //disable this, look into tokenise().
                $commands = implode (' ', $dmn->valid_commands);
                $dmn->Println ('Valid Commands: ');
                $dmn->Println ($commands);
                return true;
                break;
        case 'QUIT':
		        $dmn->changePrompt();
				
				//only in inetd mode
				if ($this->daemon_type=='inetd') $this->shutdown();
                return false;
                break;
        case 'DATE':
                $dmn->Println (date ("H:i:s d/m/Y"));
                return true;
                break;
        case 'SHUTDOWN':
		        $dmn->changePrompt();
                $dmn->shutdown ();
				
				$this->shutdown();				
                exit;
                break;
				
        case 'ECHO':
                $dmn->setEcho($arguments[0]);
                return true;
                break;	
				
        case 'SILENCE':
                $dmn->setSilence($arguments[0]);
                return true;
                break;							
				
        case 'VER':
                $dmn->Println (implode(",",$arguments).':shell script engine V0.01 on PHP'.phpversion());
                return true;
                break;						
				
		case 'CALL'     ://alias		
		case 'CALLAGENT'://server version
		        $data = $this->call_agent($arguments[0],$dmn);
		        $dmn->Println ($data);
                return true;
                break;	
				
		case 'UNCALL'://server version
		        $data = $this->uncall_agent($dmn);
		        $dmn->Println ($data);
                return true;
                break;					
				
		case 'CALLAGENTC'://client version ... moves agent from server to client
		        $dmn->setEcho(0);//echo off
				//header from 1st command still appear...must set client silence off				
				$dmn->setSilence(1);//silence off...???
		        $data = $this->call_agentc($arguments[0]);
		        $dmn->Println ($data);
                return false;//and quit
                break;					
				
		case 'HELO':
                return false;
                break;		
				
		case 'RUN':
                return true;
                break;		
				
		case 'NET':
		        if (method_exists($this,$arguments[0])) {
                  $data = $this->{$arguments[0]}($arguments[1],$arguments[2],$arguments[3]);		
		          $dmn->Println ($data);		        
				}
                return true;
                break;					
				
		case 'SHOW':
		        $dmn->Println($this->show_agents());
                return true;
                break;
				
		case 'CREATE':
		        $data = $this->create_agent($arguments[0],$arguments[1],$arguments[2],$arguments[3]);
		        $dmn->Println ($data);			
                return true;
                break;
				
		case 'DESTROY':
		        $data = $this->destroy_agent($arguments[0]);
		        $dmn->Println ($data);			
                return true;
                break;								
				
		case 'MOVE':
		        $data = $this->move_agentc($arguments[0],$arguments[1]);
		        $dmn->Println ($data);			
                return true;
                break;
				
		case 'ACCEPT':	
		        $data = $this->accept_agentc($arguments[0],$arguments[1],$arguments[2]);
		        $dmn->Println ($data);		
                return true;
                break;																	

		case 'GETRESOURCE' :
		        $resource = $this->get_agent('resources')->get_resource($arguments[0],$arguments[1]);
		        $dmn->Println ($resource);
				//return true;
				return false;//and quit
		        break;				
				
		case 'PRINT':
		        $this->prn(implode(" ",$arguments));
                return true;
                break;						
				
		case 'GETRESOURCEC' :
		        $resource = $this->get_agent('resources')->get_resourcec($arguments[0],$this->phpdac_ip,$this->phpdac_port);
		        $dmn->Println ($resource);
				return true;
		        break;
				
		case 'SHOWRESOURCES':
		        $r = $this->get_agent('resources')->showresources();
				$dmn->Println ($r);
                return true;
                break;			
									 						
		case 'FINDRESOURCE':				
		case 'FINDRESOURCEC':
		        $r = $this->get_agent('resources')->findresource($arguments[0],1);
				$dmn->Println ($r);
                return true;
                break;	
				
		case 'SETRESOURCE':
		        $r = $this->get_agent('resources')->set_resource($arguments[0],$arguments[1]);
				$dmn->Println ($r);
                return true;
                break;		
				
		case 'DELRESOURCE':
		        $r = $this->get_agent('resources')->del_resource($arguments[0]);
				$dmn->Println ($r);
                return true;
                break;													
				
		case 'CHECKSCHEDULES':
		        $c = $this->get_agent('scheduler')->checkschedules();
				$dmn->Println ($c);
                return true;
                break;	
				
		case 'SHOWSCHEDULES':
		        $s = $this->get_agent('scheduler')->showschedules();
				$dmn->Println ($s);
                return true;
                break;	
				
		case 'WHO':
		        $sessions = $this->show_connections(1,$arguments[0]);//$dmn->show_connections();
				$dmn->Println($sessions);
				return true;
		        break;										
				
		case 'STARTGTK':
		        $c = "Starting GTK Console...\r\n";
				echo $c;
		        //if ($argv[3]==='-gtk') 
				new gtkds(&$this,0);
				$dmn->Println($c);
				return true;
		        break;					
        }		
   }  
   
   //agent command dispatcher (all *** commands)
   function agent_handler($command, $arguments, $dmn) {
   		
		//create command line from daemon			
		$shell_command = $command . " " . implode(' ',$arguments);			
		
		if (is_object($this->active_o_agent)) {
		  
		  if (method_exists($this->active_o_agent,$command))
		    $ret = $this->active_o_agent->$command($arguments[0],$arguments[1],$arguments[2]);
		  else
		    $ret = "Invalid command.\n" . var_dump(get_class_methods($this->active_o_agent));  
		  
		  $dmn->Println ($ret); 
		  return true;  
		}			
		else {			
		  $dmn->Println ($shell_command); 
		  return true;  
		}
   }   
   
   //WARNING::after exec crash at connections
  /* function exist_dpc_server($address,$port) {
	  
      $fp = @stream_socket_client("tcp://$address:$port", $errno, $errstr, 30);
	  
      if (!$fp) {
	  
        echo "$errstr ($errno)\n";
        return false;
      } 
	  else {
        $ret = fgets($fp,7);
        echo $ret;
        fclose($fp);
		
        if (stristr('phpdac5',$ret)) 
		  return true;
		else 
		  return false; 
      }   
   } */ 
   
   ////////////////////////////////////////////////////////// LOW-LEVEL AGENTS HANDLERS
   ////////////////////////////////////////////////////////// called by HI-LEVEL HANDLERS

   //buffer2 used as optional when reconf-clean mem  
   function openmemagn($buffer,$buffer2=null) {
   
      $shm_max = strlen($buffer) + strlen($buffer2);
   
      if ($this->agn_mem_type==1) {//shared
   
        $this->agn_shm_id = shmop_open(0xfff, "c", 0644, $shm_max);
	  
        if(!$this->agn_shm_id) 
          die("Couldn't create shared memory segment. System Halted.\n");
	    else {  
          $shm_bytes_written = shmop_write($this->agn_shm_id, $buffer, 0);
          if($shm_bytes_written != $shm_max) {
            echo "Couldn't write the entire length of data\n";
		    echo $shm_max,":",$shm_bytes_written,">",$buffer,"\n";
          }
		  else {	
		    $this->shared_buffer = $buffer . $buffer2;
		    $this->savestate($shm_max);
	        //echo "Ok!\n";
		  }  		
	    }	 
	  }
	  else {//default
	    
		$this->agn_mem_store = $buffer . $buffer2; 
		$this->savestate($shm_max);
	    //echo $this->agn_mem_store," Ok!\n";		
	  }  
   }
   
   function closememagn() {
   
      if ($this->agn_mem_type==1) {//shared	   
   	    if (!$this->agn_shm_id) return -1;
	  
        if(!shmop_delete($this->agn_shm_id)) {
          echo "Couldn't mark shared memory block for deletion.\n";
        }	  
	  
	    shmop_close($this->agn_shm_id);	
	    $this->agn_shm_id = null;  
		
	  }

	  //delete id file
	  if (is_file($agn.id)) {
	    echo "Deleting state..";
	    unlink("agn.id");
	  }
	  //echo "Ok!\n";   
   }   
   
   //save shared mem resource id and mem alloc arrays
   function savestate($shm_max_mem) {
   
		$fd = @fopen( "agn.id", "w" );

		if (!$fd) {
            echo "agn_id not saved!!!\n";
			return false;
		}

		echo "Saving state...";
		$data = $shm_max_mem ."^". serialize($this->agn_addr) . 
		                      "^". serialize($this->agn_length); 

		fwrite($fd, $data);

		fclose($fd);      
		
		return true;
   }   
   
   function addmemagn($agent,$agn_serialized) {
     
	  //if ($agent=='scheduler') {
      //echo "\n,.",strlen($this->shared_buffer)/*,$this->shared_buffer*/;
	  //echo "\n,.",strlen($this->agn_mem_store),$this->agn_mem_store;
	  //}
	  if ($this->agn_mem_type==1)   
        $a_index = strlen($this->shared_buffer); //echo $a_index,"\n";
	  else
	    $a_index = strlen($this->agn_mem_store);
	  
      $a_size = strlen($agn_serialized);
	  
      //extend agent info table
	  $this->agn_addr[$agent] = $a_index;			
	  $this->agn_length[$agent] = $a_size;		
	  echo "\nnew $agent ", $a_index,':',$a_size,"\n\n\n";
	  //var_dump($this->agn_addr);
	  		
      $shm_max = $a_index + $a_size;	
	  if ($this->agn_mem_type==1) 	
	    $this->shared_buffer .= $agn_serialized;
	  else
	    $this->agn_mem_store .= $agn_serialized;	
	  
      if ($this->agn_mem_type==1) {//shared	  
	    //extend and add the new agent at sh mem
   	    if ($this->agn_shm_id) { 
	      echo "Close shared memory segment ...\n";	  
	      $this->closememagn();	 
	      echo "Re-allocate shared memory segment ...\n";
	      $this->openmemagn($this->shared_buffer); 	  
	    }
	    else {
          echo "Allocate shared memory segment ...\n";
	      $this->openmemagn($this->shared_buffer); 	  	  
	    }
	  }
	  else {
	    echo "Close standart memory segment ...\n";	  
	    $this->closememagn();	   
        echo "Allocate standart memory segment ...\n";
	    $this->openmemagn($this->agn_mem_store);		
	  }	
   }
   
   function updatememagn($agent,$agn_serialized) {
   
      //replace agent info table  
	  $a_index = $this->agn_addr[$agent];			
	  $a_old_size = $this->agn_length[$agent];		
	  //echo "\nupdate old ",$a_index,':',$a_old_size,"\n";   
	  $a_new_size = strlen($agn_serialized);
	  //echo "update new ",$a_index,':',$a_new_size,"\n";	
	  
	  if ($a_old_size==$a_new_size) { //1st method
		
        if ($this->agn_mem_type==1) {//shared	  
		
          $this->shared_buffer = substr_replace($this->shared_buffer,$agn_serialized,$a_index,$a_old_size);    		
		  
	      //update and replace the new agent at sh mem
   	      if ($this->agn_shm_id) { 
	        echo "Close shared memory segment ...\n";	  
	        $this->closememagn();	 
	        echo "Re-allocate shared memory segment ...\n";
	        $this->openmemagn($this->shared_buffer); 	  
	      }
	      else {
            echo "Allocate shared memory segment ...\n";
	        $this->openmemagn($this->shared_buffer); 	  	  
	      }
	    }
	    else {
		
          $this->agn_mem_store = substr_replace($this->agn_mem_store,$agn_serialized,$a_index,$a_old_size);    		
		
	      echo "Close standart memory segment ...\n";	  
	      $this->closememagn();	   
          echo "Allocate standart memory segment ...\n";
	      $this->openmemagn($this->agn_mem_store);		
	    }
		
		return true;			
	  }	
	  else
	    echo "Dimension error!\n";
		
	  return false;	
   }
   
   function removememagn($agent) {
   
      $a_index = $this->agn_addr[$agent];   
      $a_size = $this->agn_length[$agent];
	  echo "\nremove ", $agent,'>',$a_index,':',$a_size,"\n";		  
	  
	  $deleted_agent = str_repeat('x',$a_size);
	  
      if ($this->agn_mem_type==1) {//shared		
	  
	    //update shared buffer
        $this->shared_buffer = substr_replace($this->shared_buffer,$deleted_agent,$a_index,$a_size);	      
		
	    if (!shmop_write($this->agn_shm_id,$deleted_agent,$a_index)) {
	      echo "[$agent] Couldn't mark shared memory block for writing.\n";
		  return false;
	    }  
	  }
	  else {
        //echo "\n",$this->agn_mem_store,"\n",strlen($this->agn_mem_store),"\n";	  
	    $this->agn_mem_store = substr_replace($this->agn_mem_store,$deleted_agent,$a_index,$a_size);
		//echo "\n",$this->agn_mem_store,"\n",strlen($this->agn_mem_store),"\n";
	  }
	  //if ckean remark this...
	  //unset($this->agn_addr[$agent]);
	  //unset($this->agn_length[$agent]);

	  if ($this->agn_mem_type==1) 
	    $this->cleanmemagn();
	  else 
	    $this->clean_mem_store();  	
	  
	  return true;
   }
   
   function clean_mem_store() {
      
	  //var_dump($this->agn_addr);
	  //var_dump($this->agn_length);	  
	  //echo "\n",$this->agn_mem_store,"\n",strlen($this->agn_mem_store),"\n";	
	  
	  reset($this->agn_addr);
      foreach ($this->agn_addr as $id=>$value) {

        $current_index = $value;				
	    $current_size = $this->agn_length[$id];		
		
		$s_agent = substr($this->agn_mem_store,$current_index,$current_size);
	    //echo '>',$s_agent,'<',strlen($s_agent); 		
		
		$removed_agent = str_repeat('x',$current_size);
		if ($s_agent==$removed_agent) {
		  //is a deleted agent
		  //echo "\nclean $id $current_index:$current_size\n";
		  $offset = strlen($s_agent);
		}
		else {
	      $a_size = $current_size;		
		  
		  $local_agn_addr[$id] = $current_index - $offset;
		  $local_agn_length[$id] = $a_size;
		  $local_agn_mem_store .= $s_agent;
		  
		  $a_index += $a_size;
		  $shm_max += $a_size;
		}
	  }
	  
	  //print_r($local_agn_addr);
	  //print_r($local_agn_length);

	  //$this->agn_mem_store = $local_agn_mem_store;
	  //echo strlen($this->agn_mem_store),">>>>>>>>\n";	  
	  
	  $this->agn_addr = (array)$local_agn_addr;
	  $this->agn_length = (array)$local_agn_length;
	  	    
	  $this->closememagn();	//reset shared buffer
	  $this->openmemagn('aek;',$local_agn_mem_store);
	  
	  //var_dump($this->agn_addr);
	  //var_dump($this->agn_length);		  
	  
   }
   
   function cleanmemagn() {
   
	  $shm_max = 0;
	  
	  reset($this->agn_addr);
      foreach ($this->agn_addr as $id=>$value) {

        $current_index = $value;				
	    $current_size = $this->agn_length[$id];		
			
	    $s_agent = shmop_read($this->agn_shm_id,$current_index,$current_size); 

	    echo '>',$s_agent,'<',strlen($s_agent); 		
	    //$s_agent= substr($this->shared_buffer,$value,$current_size);
		
		$removed_agent = str_repeat('x',$current_size);
		if ($s_agent==$removed_agent) {
		  //is a deleted agent
		  echo "remove\n";
		}
		else {
	      $a_size = $current_size;		
		  
		  $local_agn_addr[$id] = $current_index;
		  $local_agn_length[$id] = $a_size;
		  $local_shared_buffer .= $s_agent;
		  
		  $a_index += $a_size;
		  $shm_max += $a_size;
		}
	  }
	  //echo $local_shared_buffer;
	  //$this->shared_buffer = $local_shared_buffer;
	  $this->agn_addr = (array)$local_agn_addr;
	  $this->agn_length = (array)$local_agn_length;
	    
	  $this->closememagn();	//reset shared buffer
	  $this->openmemagn('aek;',$local_shared_buffer);  
   }
   
   /////////////////////////////////////////////// HI-LEVEL AGENT HANDLERS
   
   //reead file of agents to initialize
   private function init_agents($ip,$port) {   
   
	   $this->openmemagn('aek;');    

       if (!is_file("agentds.ini")) return -1;   
		  
       $code = file_get_contents("agentds.ini");
	   
	   if ($file = explode("\n",$code)) {
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
			//tokenize
			$token = explode(";",$toktext);		

		   
	        //then...read tokens  			
	        foreach ($token as $tid=>$tcmd) {
			  
			   $part = explode(' ',$tcmd);
			   switch ($part[0]) {	
			   
			     case 'system':	//run system cmds
				                $name = explode(".",trim($part[0]));
				                break;				  
								
			     case 'schedule':	//run scheduled cmds
				                $this->get_agent('scheduler')->schedule($part[1],$part[2],$part[3]); 
				                break;
								
				 case 'library' : if ($part[1]) {

								  $name = explode(".",trim($part[1]));
								  $this->create_agent($name[1],$part[2],null,null,'lib');
								} 
				                break; 													
							  
				 default      : //create agents  
			  		            if ($part[0]) {
								  //$includer = "phpdac5://$ip:$port/" . str_replace(".","/",trim($part[0])) . "." . 'dpc' . ".php";
								  //$this->load_agent($includer,$part[0]);
								  
								  $name = explode(".",trim($part[0]));
								  $this->create_agent($name[1],$part[1]);
								} 
				                
			   }//switch
			   $i+=1; 
	        }//foreach		 
         
	   }
   }  
   
   function create_agent($agent,$resident=null,$include_ip=null,$as_name=null,$type='dpc') {
      global $__DPC;   
   
      if (isset($include_ip))
	    require("phpdac5://$include_ip:$this->phpdac_port" . "/agents/$agent" . '.'.$type.'.php');    
	  else 
	    require("phpdac5://$this->phpdac_ip:$this->phpdac_port" . "/agents/$agent" . '.'.$type.'.php');    
	  
	  $class = strtoupper($agent).'_DPC';	 
	  //echo $class;
	  if ((defined($class)) &&
	      (class_exists($__DPC[$class])) ) {
		  
          $o_agent = & new $__DPC[$class](&$this);//this is the class as environment
		  		  
		  if (is_object($o_agent)) {
		    $s_agent = serialize($o_agent); 
			
			if (isset($as_name)) {
		      $this->addmemagn($as_name,$s_agent);
			  echo 'create agent ..',$agent," as $as_name\n";			
			}
			else {
		      $this->addmemagn($agent,$s_agent);
			  echo 'create agent ..',$agent,"\n";
			}  
			if ($resident=='RESIDENT') {
			  $this->agn_attr[$agent] = $resident;
			  echo "(resident)\n";
			}
	    
	        //var_dump($this->agn_addr);
	        //var_dump($this->agn_length);	  
	        //echo "\n",substr($this->agn_mem_store,0,256),"\n",strlen($this->agn_mem_store),"\n";				
		  			
		    return true;
		  }
		  else {
            echo 'loading agent ..',$agent,"..failed!\n";
		    return false;		  
		  }
	  }
      echo 'failed agent ..',$agent,"\n";
	  return false;	 	   
   } 
   
   function destroy_agent($agent) {
   
	  $o_agent = $this->get_agent($agent);
	  
      //seems to load the 1st agent in case of invalid agent name   
	  if (is_object($o_agent) && ($this->active_agent!=$agent)) {   
     
	    if ($this->removememagn($agent)) {
		
		  $o_agent->destroy();
		
	      unset($this->agn_attr[$agent]);//RESIDENT ATTRIBUTE
	      echo "[$agent] Destroyed.\n";
		  
		  return true;
	    }	
	    else {
	      echo "[$agent] NOT destroyed!\n";			
		  return false;
	    }	
	  
	  }
	  else
	    echo "Invalid object or activated!";
   }
   
   
   function __wakeup() {
   
     //echo "WWWWWWWWWWWWW";
   }
   
   //update the object data in shared mem
   function update_agent(&$o_agent,$agent) {
   
	  //var_dump($this->agn_addr);
	  //var_dump($this->agn_length);	  
	  //echo "\n",$this->agn_mem_store,"\n",strlen($this->agn_mem_store),"\n";   
      //echo "\n,.",strlen($this->shared_buffer),$this->shared_buffer;
	   
	  if (is_object($o_agent)) {
	  
	      $old_agent = $this->get_agent($agent,true);
          
		  //$o_agent->env = $this;
		  $s_agent = serialize($o_agent);    
		  
		  //if (strlen($old_agent)!=strlen($s_agent)) {
		  if (strcmp($old_agent,$s_agent)) {
		  
		    //echo '+++++',strcmp($old_agent,$s_agent),'++++'; 
		  
		    //1st method
		    //$this->updatememagn($agent,$s_agent);
		  
		    //2nd method
	        //remove and then insert 2ND METHOD
	  		//echo  strlen($this->agn_mem_store),">>>>>\n";			
	        $removed = $this->removememagn($agent);
	  		//echo  strlen($this->agn_mem_store),">>>>>\n";
   	        if ($removed) {//2nd method		
			  
		      $this->addmemagn($agent,$s_agent);
		      echo 'update agent ..',$agent,"\n";			
		    }
		    else
		     echo 'update agent ..',$agent,"..failed!\n";
		  }
		  else
		    echo 'update agent ..',$agent,"..not neccesery!\n";	 
	  }
	  
	  //var_dump($this->agn_addr);
	  //var_dump($this->agn_length);	  
	  //echo "\n",$this->agn_mem_store,"\n",strlen($this->agn_mem_store),"\n";		  		
   } 
   
   //return object pointer of agent OR serialized string of agent
   function get_agent($agent,$serialized=null) {

	  //echo $agent,"\n>>>>>>>>>>>>>";
      if (isset($this->agn_addr[$agent])) {
	  
	    $a_index = $this->agn_addr[$agent];
	    $a_size = $this->agn_length[$agent];
	    //echo $a_index,':',$a_size,"\n";
		//var_dump($this->agn_addr);
		//var_dump($this->agn_length);		
	  
        if ($this->agn_mem_type==1) {//shared	  
	      $s_agent = shmop_read($this->agn_shm_id,$a_index,$a_size); 
		}
		else {
		  $s_agent = substr($this->agn_mem_store,$a_index,$a_size);		
		}  
	    //echo '>',$s_agent,'<',strlen($s_agent); 

	    if ($serialized) {
		
		  //auto update ?????
		  
		  return ($s_agent);
		}
		else {  
	      $o_agent = unserialize($s_agent);
	  
		  //auto update
		  $o_agent->env = &$this;
		  
		  //echo get_class($o_agent),"\n";
		  return ($o_agent);
		}  
	  }
	  
	  return null;	   
   }    
   
   //call agent's methods from cmd line!!!
   function call_agent($agent,$daemon) {
   
		$o_agent = $this->get_agent($agent);
	  
        //seems to load the 1st agent in case of invalid agent name   
	    if (is_object($o_agent)) {
          //$daemon->changePrompt($agent.">");   
          if (method_exists($o_agent,'iam')) 
		    $ret = $o_agent->iam();
		  else 
		    $ret = "Ok!";
    
          $this->active_o_agent = $o_agent;
		  //var_dump(get_class_methods($this->active_o_agent));
		  $this->active_agent = $agent;
		  $daemon->changePrompt($agent.'>');
		
		  $ret = implode(".",get_class_methods($this->active_o_agent)) . "\n";
	    }
	    else {
	      //$this->active_o_agent = null;
	      $ret = "Invalid agent!";
	    }	
	
	  return ($ret);	      
   }
   
   //uncall agent from cmd
   function uncall_agent($daemon) {
   
	  if (is_object($this->active_o_agent)) {   
	  
        $this->active_o_agent = null;
		$this->active_agent = null;	
		$daemon->changePrompt();	
  	    $ret = "Ok!";					  
	  }
	  else
	    $ret = "Invalid agent!";	
		
	  return ($ret);			  
   }
   
   //call agent from sh mem buffer (client version)
   //use local
   function call_agentc($agent) {
   
		
		$s_agent = $this->get_agent($agent,1);
	  
	    //delete agent from host
		if ($this->agn_attr[$agent]!='RESIDENT')
	      $this->destroy_agent($agent);
	  	  
	    return ($s_agent);
   }
   
   //send agent to other agent station
   function move_agentc($agent,$to) {
   }   
   
   //accept agent from other agent station
   //$from = ip:port
   function accept_agentc($agent,$from,$include=null) {
   
      //get agent
      $f_agent = file_get_contents("phpagn5://$from:19125/" . $agent);//call callagentc from $from
	  $s_agent = substr($f_agent,68,strlen($f_agent)-68-1);//header of daemon OFF
      echo '>',$s_agent,'<';
	  /*for ($i=0;$i<68;$i++) {
	    echo ord($f_agent{$i}),'.';
		$x .= $f_agent{$i};
	  }	
	  echo $x;*/
	  
	  if (isset($s_agent)) {
	    //MUST BE INCLUDED TO UNSERIALZE OBJECTS
	    if (!isset($include)) {
		 if (isset($this->phpdac_ip)) 
		   $include = $this->phpdac_ip;
		 else
		   $include = $from;
		}   
        require("phpdac5://$include:19123/" . "agents/$agent." . 'dpc' . ".php");
	  	  
	    $o_agent = unserialize($s_agent);	  
      
	    if (is_object($o_agent)) {
          //$daemon->changePrompt($agent.">");   
          if (method_exists($o_agent,'iam')) 
		    $ret = $o_agent->iam();
		  else 
		    $ret = "Ok!";
		  
		  $this->addmemagn($agent,$s_agent);   
	    }
	    else
	      $ret = "Invalid agent!";	
	  }
	  else
	    $ret = "Invalid agent!";	
	  			
	  return ($ret);	   
   }
   
   function show_agents() {
   
      if (!is_array($this->agn_addr)) return -1;
	  
      //var_dump($this->agn_addr);	  
	  //var_dump($this->agn_length);  
   
      foreach ($this->agn_addr as $agn=>$addr) {
	    echo "\n $agn $addr";
        if ($this->agn_mem_type==1) {//shared	  
	      $s_agent = shmop_read($this->agn_shm_id,$addr,$this->agn_length[$agn]);  
		}
		else {
          $s_agent = substr($this->agn_mem_store,$addr,$this->agn_length[$agn]);  		
		} 
		echo ":",$this->agn_length[$agn],"\n";
		//echo $s_agent,"\n";
		
		$o_agent = unserialize($s_agent);	
		
	    if (is_object($o_agent)) {
          //$daemon->changePrompt($agent.">");   
          if (method_exists($o_agent,'iam')) 
		    $ret .= $o_agent->iam()."\n";
		  else 
		    $ret .= $agn." Ok!"."\n";
	    }
		else
		  $ret .= "Invalid agent!\n";		    
	  }
	  
	  return ($ret);
   }
   
   function free_agents() {
   
		$this->gtkds_conn->set_async_code("
		foreach (\$this->agentbox->children() as \$num=>\$child) {
		   \$this->agentbox->remove(\$child);
		}	
	    ");		      
   
      $this->closememagn();
   }
   
   function prn($message) {
   
      $printout = $this->get_agent('resources')->get_resource('printer');
   
      //$this->update_agent($this->get_agent('resources'),'resources');
   
	  if (is_resource($printout) &&
		  get_resource_type($printout)=='printer')
	     printer_write($printout,$message."\n\r");  	 
   }
   
   function show_connections($show=null,$dacserver=null) {
   
      if ($dacserver) {//get sesions from phpdac server's daemon...
	    $sret = $this->get_agent('resources')->get_resourcec('_sessions',$this->phpdac_ip,$this->phpdac_port);
		$ret = unserialize($sret);
	  }
	  else {//get session from this agentds daemon
        $ret = $this->dmn->show_connections();
	  
	    //save in resources
        $this->get_agent('resources')->set_resource('_sessions',serialize($ret));		  
	  }
	  
	  if ($show) {
	    if (!empty($ret)) {
	      //print out
	      foreach ($ret as $session)
	        $out .= implode("-",$session). "\r\n";	  
		}  
		return ($out);  
	  }
	  else
	    return ($ret);
	  	
	  	
   }      
   
   function shutdown() {
   
	  //unregister_tick_function($this->get_agent('scheduler'),'checkschedules');
	  //unregister_tick_function(array(&$this,'tick_dummy'));
	  
	  //is agents now
	  //unset($this->timer);
	  //unset($this->scheduler);
   
	  echo "\nShutdown....\n";
      $this->free_agents();
   } 
}

class rules {

   function rules() {
   }
   
   
};

?>