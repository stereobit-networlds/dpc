<?php


/**********************************************************************
 *     class.daemon.php - Copyright, N.Suraj Kumar                    *
 *                                                                    *
 *     This code is released under the terms of the                   *
 *     GNU General Public License.                                    *
 *                                                                    *
 *     For more details read: http://www.gnu.org/licenses/gpl.html    *
 *                                                                    *
 **********************************************************************/

/* What does this class do?

        This class can help you create easy to use TCP Daemons that can
        listen on a specified port. 

        The main aim of writing this class was to help ppl easily define
        their own FTP-like protocols where they can create apps that can read
        commands and respond in return.

        See sample code at the end of the this file for more details.

*/      


define ("VERBOSE", true);
define ("VERBOSE_LEVEL", 2);//5
define ("DAEMON_BUFFER", 1024);
define ("MAX_CONNECTIONS", 4);
define ("SILENCE",1);//0

/* can be set to 'standalone' for listening on specified port. When run
 * as an inetd service, this class reads from stdin and outputs to
 * stdout. and hence the address/port doesn't make any sense in the
 * inetd context
 */

//define ("SERVER_TYPE", 'standalone'); //and oh, this can be _anything_
                                                                                                  //if you wanted this to do sockets...

//define ("SHOW_PROMPT", true); //should a prompt be displayed? 

class daemon {
        
        var $stdin;
        var $stdout;

        // and for the standalone version...
        var $socket;
        var $msg_socket;
        var $first_time = true;
        var $Address;
        var $Port;

        var $Header;
        var $PromptString = 'phpdac5> ';
		
		//added by me
		var $Echo = 1;//0;
		var $silent = 0;//client silent..server silence defined ..		
        protected $clientFD;	
		protected $cliendInfo;
		protected $clients;	
		protected $active;
		protected $timeout;
		protected $idle_timeout;
		
		var $cpool;
		
        function daemon($type='standalone',$prompt=true,$env=null) {
		
                define ("SERVER_TYPE", $type);//added by me!
                define ("SHOW_PROMPT", $prompt); //should a prompt be displayed? 
				$this->active = null;
				$this->timeout = 1*30;//=secs
				$this->idle_timeout = 1*30;//=secs				
				
		        if (!extension_loaded('sockets')) dl('php_sockets.dll');
                set_time_limit (0); 
                ob_implicit_flush ();

        }

        function verbose ($level, $msg) {
                if (VERBOSE && $level <= VERBOSE_LEVEL && SERVER_TYPE != 'inetd') {
                        echo str_repeat ("*", $level) . " $msg " . str_repeat ("*", $level)
                        . "\n";
                }
        }

        function setAddress ($ipaddr) {
                $this->Address = $ipaddr;
        }

        function setPort ($port) {
                $this->Port = $port;
        }
				
		function setEcho($echo,$id=null) {
		        if (!$id) $id = $this->active; 
				
		        //if ($this->clientInfo[$id])
			      //$this->clientInfo[$id]['Echo'] = $echo;			
				$this->cpool[$id]->session['Echo'] = $echo;  
        }		
		//hide messages and header (client usage)
		function setSilence($silence,$id=null) {
		        if (!$id) $id = $this->active; 
				
			    $this->cpool[$id]->session['Silent'] = $silence;			
        }		

        function start ($PromptString=null) {	
		
		        if ($PromptString) $this->PromptString = $PromptString;
                
                if (SERVER_TYPE == 'inetd') {
                  /* This daemon is already listening to a socket. Thanks to inetd.
                   * we just output to stdout and read from stdin.
                   */
                  $this->stdin = fopen ('php://stdin', 'r');
                } else {
				        //prepare pool
						for ($i=0;$i<MAX_CONNECTIONS;$i++)
				           $this->cpool[$i] = new connect_pool();	
						   
						   
                        $this->verbose (1, "Server Ready for connections");
                        /* This is being run as a standalone server. lets create a socket
                         */
                        $sock = socket_create (AF_INET, SOCK_STREAM, SOL_TCP);
                        $this->verbose (3, "Socket created");
                        if ($sock < 0) {
                                //error!
                                $this->sock_die ('Couldn\'t create a socket!', $sock);
                        }
                        socket_setopt($sock, SOL_SOCKET, SO_REUSEADDR, 1);
                        $this->verbose (3, "Making socket reuseable");

                        $ret = socket_bind ($sock, $this->Address, $this->Port);
                        if ($ret < 0) {
                                //error!
                                $this->sock_die ('Couldn\'t bind socket!', $ret);
                        }
                        $this->verbose (3, "Socket bind complete");

                        $ret = socket_listen ($sock, MAX_CONNECTIONS);
                        if ($ret < 0) {
                                //error!
                                $this->sock_die ('listen failed!', $ret);
                        }

                        $this->socket = $sock;
                        //$this->sock_message_socket_create ();//master
                }
        }

        function sock_message_socket_create ($i) {//=null) {second method
		
				// for ($i = 0; $i <= count($this->cpool); $i ++) {
                  //  if (!isset ($this->cpool[$i]->resource) || $this->cpool[$i]->resource == null) {
					  
                      $this->cpool[$i]->resource = socket_accept($this->socket);
                      if ($this->cpool[$i]->resource < 0) {
                        //error
                        $this->sock_die ('socket accept failed!', $this->cpool[$i]->resource);
                      }					  
					  
                      socket_setopt($this->cpool[$i]->resource, SOL_SOCKET, SO_REUSEADDR, 1);
                      $peer_host = "";
                      $peer_port = "";
                      socket_getpeername($this->cpool[$i]->resource, $peer_host, $peer_port);
                      $this->cpool[$i]->session = array ("host" => $peer_host, 
					                                     "port" => $peer_port, 
												   	     "connectOn" => time(),
													     "First_time"=>true,
					                                     "PromptString"=>$this->PromptString,
													     "Echo"=>$this->Echo,
														 "Silent"=>$this->silent);
					  //at connection....									 
					  $this->active = $i;
					  if ((!SILENCE) || (!$this->cpool[$i]->session['Silent'])) {
                        $this->ShowHeader($i);
					    if (SHOW_PROMPT) {
                          $this->showPrompt($i);
                        }
					  }
  					  //return $i; 
					//}
				  //}	                    

        }
		

        function sock_reset ($id) {

                $this->close ($id);
                $this->sock_message_socket_create($id);
        }

        function close ($id) {
		        //echo 'silence:',SILENCE,'<>',$this->cpool[$id]->session['Silent'],'<';
				
     			if ((!SILENCE) || (!$this->cpool[$id]->session['Silent']))//if no silence
                  $this->Println ('goodbye!',$id);		
		
		        if (SERVER_TYPE == 'inetd') {
                        exit;
                } 
				else {
                //if (SERVER_TYPE != 'inetd') {
				        //if (!$socket) $socket = $this->msg_socket;
						socket_getpeername ($this->cpool[$id]->resource, $peer_addr, $peer_port);
                        $this->verbose (1, "---------------Connection closed from $id>$peer_addr:$peer_port ------------");
                        //socket_shutdown ($this->clientFD[$id]);
						socket_close($this->cpool[$id]->resource);

                        //$this->clientFD[$id] = null;
                        //unset ($this->clientInfo[$id]);
                        //$this->clients--;
						
						//$this->cpool[$id]->reset_client();	
						$this->cpool[$id]->resource = null;
					    $this->cpool[$id]->session['First_time'] = true;//->reset_client();	                    
						

                }
        }
		//alias
		function closeConnection($id) {
		
		  $this->close($id);
		}
		
        function resetConnection ($id) {
		
		        //if (!$id) $id = $this->active;
			    if ((!SILENCE) || (!$this->cpool[$id]->session['Silent']))//if no silence
                  $this->Println ('goodbye!',$id);
				
                if (SERVER_TYPE == 'inetd') {
                        exit;
                } else {
                        $this->cpool[$id]->session['First_time'] = true;				
                        $this->sock_reset ($id);
                }
        }	
		
		function refuseConnection($id) {
		    
			   echo 'Connection refused!';
			   //$this->closeConnection();
		}	

        function shutdown () {
                if (SERVER_TYPE != 'inetd') { //because it just doesn't make sense
                                                                                                
  	           //      to have an 'inetd' service shut
               //      itself down... ;-/
			   
              /*          $maxFD = count($this->clientFD);
                        for ($i = 0; $i < $maxFD; $i ++) {
                            $this->close($i);
                        }	*/	
						for ($i=0;$i<MAX_CONNECTIONS;$i++) {
		                  if ($this->cpool[$i]->resource == '') {
			                break;
			              }   
			              else {
			               $this->closeConnection($i);
			              }
		                }	   
			   
                        //$this->println ('*** Server Shutting down ***');
						$this->BroadPrint ('*** Server Shutting down ***');
                        $this->verbose (1, '=======Server Shutdown=========');
                        //$this->close ();
						//socket_shutdown ($this->socket);
						socket_close($this->socket);
									 						
                } 
        }

        function sock_die ($msg, $return_code, $to_be_closed) {
                echo "$msg: " . socket_strerror ($return_code);
                if ($to_be_closed===true) {
                        socket_close($this->active);// ($this->msg_socket);//=master!!!!!!!!!!!!!!!!!
                }
				elseif ($to_be_closed) {//exist
				        socket_close ($to_be_closed);//=client
				}
                exit;
        }


        function Read ($id) {
                if (SERVER_TYPE == 'inetd') {
                        return trim (fgets ($this->stdin, DAEMON_BUFFER));
                } else {
				        //if (!$socket) $socket = $this->msg_socket;
                        /*if (FALSE == ($buf = socket_read ($this->cpool[$id]->resource,DAEMON_BUFFER))) {
                                //error reading socket
                                $this->sock_die ('Error Reading from socket!', $buf, $this->cpool[$id]->resource);
                                //true makes sock_die to close the socket in the end
                        } else {
                                $this->verbose (5, '<<' . $buf);
                                return trim ($buf);
                        }*/
						//added by me
						//2000!!!!!!
                        $data = "";
                        while (socket_recv($this->cpool[$id]->resource,$buf,DAEMON_BUFFER,0) !== false) {
						
						  $this->active = $id;
						  
                          $data .= $buf;
						  if ((!SILENCE) || (!$this->cpool[$id]->session['Silent'])) 
						    if ($this->cpool[$id]->session['Echo']) 
						      $this->_Print ($buf,$id);
						  
                          if (preg_match("'\r\n$'s", $data)) {
                                //$this->verbose (5, '<<' . $data);
                                return trim ($data);						  
						  }
                        }	
						$this->active = $id;
                        /*$data = '';//XP!!!!!!
                        // read data from socket
                        while ($buf = socket_read($this->cpool[$id]->resource, DAEMON_BUFFER, PHP_BINARY_READ)) {
                          $data .= $buf;

                          if ($buf == NULL || strlen($buf) < DAEMON_BUFFER) {
                            break;
                          }

                          if ($buf === false) {  
                          }
                        }*/

                        return $data;											
                }//else
        }

        function _Print ($string,$id=null) {

//              fputs ($this->stdout, $string);
                if (SERVER_TYPE == 'inetd') {
                        echo $string;
                } else {
						if ($id==null) $id = $this->active;//$this->msg_socket;
                        $this->verbose (5, '>>' . $string);
						
						if (is_resource($this->cpool[$id]->resource))
						  socket_write ($this->cpool[$id]->resource, $string, strlen ($string));
                        /*if (!@socket_write ($this->cpool[$id]->resource, $string, strlen ($string))) {
						  $this->resetConnection($id);
						  //$this->closeConnection($id);
						}*/
                }
        }
		
        function Println ($string,$id=null) {
                if ($id==null) $id = $this->active;
				
                $this->_Print ($string . "\n",$id);
        }		
		
        function BroadPrint($data, $exclude = array ()) {
          /*if (!empty ($exclude) && !is_array($exclude)) {
            $exclude = array ($exclude);
          }

          for ($i = 0; $i < count($this->clientFD); $i ++) {
            if (isset ($this->clientFD[$i]) && $this->clientFD[$i] != null && !in_array($i, $exclude)) {
                //if (!@ socket_write($this->clientFD[$i], $data)) {
                //}
				$this->Println($data,$i);
            }
          }*/
		  for ($i=0;$i<MAX_CONNECTIONS;$i++) {
		    if ($this->cpool[$i]->resource == '') {
			  break;
			}
			else {
			  $this->Println($data,$i);
			}
		  }
        }	
		
        function ShowHeader ($id) {
		
		        if (SERVER_TYPE != 'inetd') {

                  if ($this->cpool[$id]->session['First_time']==true) {
				        //if (!$socket) $socket = $this->msg_socket;
                        socket_getpeername ($this->cpool[$id]->resource, $peer_addr, $peer_port);
                        $this->verbose (1, "---------Connection from $id>$peer_addr:$peer_port-----------");
                        $this->Println ($this->Header,$id);
                  }
                  $this->cpool[$id]->session['First_time'] = false;
				}
				elseif ((!SILENCE) || (!$this->cpool[$id]->session['Silent']))//if no silence
				  $this->Println ($this->Header,$id);
        }			

        function showError ($Severity, $ErrorString,$id=null) {
				//if (!$socket) $socket = $this->msg_socket;
				
		        if (!$id) $id = $this->active;
				
				if ((!SILENCE) || (!$this->cpool[$id]->session['Silent']))//if no silence
                  $this->Println ($Severity . ':' . $ErrorString,$id);
        }
		
        function showPrompt ($id) {

 	            //if (!$id) $id = $this->active;
		        if (SERVER_TYPE != 'inetd') {				
				  if ($this->cpool[$id]->session['Echo']) {
                    $this->_Print ($this->cpool[$id]->session['PromptString'],$id);
				  }
			    }
				elseif ((!SILENCE) || (!$this->cpool[$id]->session['Silent']))//if no silence
				   $this->_Print ($this->PromptString,$id);	  
        }
		
		function changePrompt($newprompt='phpdac5>',$id=null) {
			
               if ($id==null) $id = $this->active;
			   
		       if (SERVER_TYPE != 'inetd') {				   
		         $this->cpool[$id]->session['PromptString'] = $newprompt;
			   }	 
			   else
			   	 $this->PromptString = $newprompt;
		}		
		
		

        function isValidCommand ($cmd) {        
                if (in_array (strtoupper ($cmd), $this->valid_commands)) {
                        return true;
                } else {
                        return false;
                }
        }

        function Tokenise ($command_line,$upper=null) {//upper added by me
                $raw_tokens = explode (' ', trim ($command_line));
                //the first one is the command
                $command = $raw_tokens[0];
                //the rest are all parameters to the command
                $params = array_slice ($raw_tokens, 1);
				
                $tokens['command'] = (isset($upper)? strtoupper ($command) : $command);
				
                $tokens['params'] = $params;

                return $tokens;
        }

        function Tokenize ($command_line) {
                //this function is just an alias for tokenise
                return $this->Tokenise ($command_line);
        }

        function setCommands ($array) {
                $this->valid_commands = array();
                foreach ($array as $item) {
                        $this->valid_commands[] = strtoupper ($item);
                }
        }
        
        function CommandAction ($command, $callback = false) {
                static $defined_functions;
        
                /* the function ($callback) that is registered will be called back
                   when the specified command is encountered.

                        callback_function (string $command, array $params, daemon
                        $this);

                        daemon $this can be used to perform more actions here.. such as
                        $this->CloseConnection(), etc.,
                */

                if ($this->isValidCommand ($command)) {
                        //command is valid. see if the name of a callback function was
                        //passed to us...
                        $command = strtoupper ($command);
						
						if (is_array($callback)) {//ADDED TO SUPPORT CLASS METHOD CALLS...
						        
                                $this->callbacks[$command][] = $callback;										
						}
                        elseif ($callback) {
                                if (!isset ($defined_functions)) {
                                        $defined_functions = get_defined_functions();
                                }
                                
                                if (in_array ($callback, $defined_functions['user'])) {
                                        $this->callbacks[$command][] = $callback;
                                        $this->callbacks[$command] = array_unique ($this->callbacks[$command]);
                                } else {
                                        $this->showError ('FATAL', 'Could not call `' . $callback . '()` Function not defined!');
                                        $this->closeConnection();
                                        exit;
                                }
                        }
						else {
                                //no call back function was passed. Let's return the list of
                                //callback functions that this command has...
                                if (empty ($this->callbacks[$command])) {
                                        return array();
                                } else {
                                        return $this->callbacks[$command];
                                }
                        }
                }
        }

		
		function dispatch($command_line,$id) {
		
                                $this->verbose (4, "Received $command_line");
                        
                                $command_set = $this->Tokenise ($command_line);//command called without strtoupper(!=default cmds) so upper cmd at execute (see below)
                                $cmd = $command_set['command'];
                                $params = $command_set['params'];
                                //$this->Println ( $command_set['command']);
                                //if ($this->isValidCommand ($cmd)) {
                                        //see if this is registered in our callback function set
                                        
                                        $callbacks = $this->CommandAction ($cmd);
	                                       //aded by me (isvalidcommand &&)
                                        if (($this->isValidCommand (strtoupper($cmd))) && (!empty ($callbacks))) {
                                                //has callback functions... lets call them one by one
                                                
                                                foreach ($callbacks as $function) {
												    
													if (is_array($function)) {//ADDED TO SUPPORT CLASS METHOD CALLS...
													    //echo 'zzz';
													    $status = $function[0]->$function[1] (strtoupper($command_set['command']), $command_set['params'], &$this); 
                                                        if (false == $status) {
                                                                //function says that we should exit...
                                                                //$this->resetConnection($id);
																$this->closeConnection($id);
                                                                //exit;
                                                        }														
													}
													else {//default global functions!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                                                        //call the callback function
                                                        $status = $function ($command_set['command'], $command_set['params'], &$this); 
                                                        if (false == $status) {
                                                                //function says that we should exit...
                                                                //$this->resetConnection($id);
																$this->closeConnection($id);
                                                                //exit;
                                                        }
												   } 		
                                                }

                                        } 
										elseif ($function = $this->CommandAction ("***")) {////check phpdac(kernel) dispatcher ADDED BY ME TO SUPPORT PHPDAC CMDS
										    //print_r($this->callbacks['***'][0]); 
											//$this->Println ( $function[0][0].'.'.$function[0][1]);
                                            $status = $function[0][0]->$function[0][1] ($command_set['command'], $command_set['params'], &$this); 
                                            if (false == $status) {
                                               //function says that we should exit...
                                               //$this->resetConnection($id);
											   $this->closeConnection($id);
                                               //exit;
                                            }	
										}
										else {
                                                //NO EVENTS... 
                                                $this->Println ('`' . $command_set['command'] . '\' defined but not implemented');
                                                $this->verbose (1, '`' . $command_set['command'] . '\'
                                                not implemented!');
                                        }
                                /*} else {
                                        $this->showError ('NOTIFY', 'Command `' .
                                        $command_set['command'] . '\' is unrecognized');
                                }*/		
		}
		
		//!!!!!! get the first active client id !!!!
		/*function getActive() {
		
               // check all clients for the first active 
               for ($i = 0; $i < count($this->clientFD); $i ++) {
                          if (!isset ($this->clientFD[$i])) {
                            continue;
                          }
                          return ($i); 
			   }  	  
		}*/
		
		function null_sort($ar1,$ar2) {
		
		  if ((is_resource($ar1->resource)) AND (is_resource($ar2->resource))) {
		    return 0;
		  }
		  elseif ((is_resource($ar1->resource)) AND (!is_resource($ar2->resource))) {
		    return -1;
		  }
		  else {
		    return 1;
		  }
		}

        function listen($timeout=null) {		
declare(ticks=10) {		
                if (SERVER_TYPE == 'inetd') {
		          $this->showHeader(null);
				  
                  while (true) {
						if (SHOW_PROMPT) {
							  $this->showPrompt(null);
                        }					
                        $command_line = $this->Read(null);
                        if (!empty ($command_line)) {
                               $this->dispatch($command_line,null);
                        }			
						//Gtk::main();
						while (Gtk::events_pending()) {
                         Gtk::main_iteration();//(false);
                        }
				  }	
				}
                else {		
				  //declare(ticks=2) {		
                  while (true) {
				        if (SERVER_TYPE == 'standalone') {  
				          while (Gtk::events_pending()) {
                           Gtk::main_iteration();//(false);
                          }
						}//else gtk error by the server (start))
				        //echo '0'; 
                        $current = array ();
                        array_push($current, $this->socket);
                        // fetch all clients that are awaiting connections
                        for ($i = 0; $i < count($this->cpool); $i ++) {
                          if (isset ($this->cpool[$i]->resource))
                            array_push($current, $this->cpool[$i]->resource);
							$this->active = $i;
                        } 	
				        /*unset($current);
						$current[0] = $this->socket;						
						for ($i=0;$i<MAX_CONNECTIONS;$i++) {
						  if ($this->cpool[$i]->resource!=null) {
						    $current[$i+1] = $this->cpool[$i]->resource;
							$this->active = $i;
						  } 
						}	*/										
						//echo '1'; 
                        // block and wait for data or new connection
                        $ready =  socket_select($current, $this->null, $this->null, $timeout);//null);
                        if ($ready === false) {
                          $this->shutdown();
                        }	
						//echo '2';
						// check for new connection
                        /*if (in_array($this->socket, $current)) {
						  $newclient = $this->sock_message_socket_create();
                          // check for maximum amount of connections
                          //if (count($current) > 0) {
                            if (count($current) > MAX_CONNECTIONS) {

                              $this->closeConnection($newclient);
                            }
                          //}

                          if (-- $ready <= 0) {
                            continue;
                          }
                        }*/
						if (in_array($this->socket,$current)) {
                          for ($i=0;$i<MAX_CONNECTIONS;$i++) {
				            if ($this->cpool[$i]->resource == null) {
					          $this->sock_message_socket_create($i);
							  break;
							}
							if ($i == MAX_CONNECTIONS -1) {
							  break;
							}
						  }	   
						}
						//sort nulls at end
						usort($this->cpool,array($this,'null_sort'));				
						//echo '3';
                        // check all clients for incoming data
                        /*for ($i = 0; $i < count($this->clientFD); $i ++) {
                          if (!isset ($this->clientFD[$i])) {
                            continue;
                          }
						  if (in_array($this->clientFD[$i], $readFDs)) {
						     						
						
						
                            $command_line = $this->Read($i);
                            if (!empty ($command_line)) {
                               $this->dispatch($command_line,$i);
                            }//no empty cmd
						  }//is in array
						}//for all clients*/
						for ($i=0;$i<MAX_CONNECTIONS;$i++) {
						  //all valid connections procceded, stop here
						  //if ($this->cpool[$i]->resource==null) {
						  if (!is_resource($this->cpool[$i]->resource)) {
						    break;
						  }
						  if (in_array($this->cpool[$i]->resource,$current)) {
	
                            $command_line = $this->Read($i);
                            if (!empty ($command_line)) {
                               $this->dispatch($command_line,$i);
                            }
							if ((!SILENCE) || (!$this->cpool[$i]->session['Silent'])) {
							  if (SHOW_PROMPT) {
                                if ($this->cpool[$i]->resource)//?????
								  $this->showPrompt($i);
							  }	  
                            }						  
						  }
						}
						//echo '4';
                  }//while
				  //}//declare
				}
}//declare
        }//end of function
		
		function RegisterAction($action) {
		
		  register_tick_function($action,true);
		}
		
		
        function show_connections() {
		
		  //echo "<pre>";
		  //print_r($this->cpool);
		  //echo "</pre>";
		  if (is_array($this->cpool)) {
            foreach ($this->cpool as $conn) {
		      if (is_array($conn->session))	
			    $sessions[] = $conn->session;
		    }	
		  
            echo "<pre>";
		    print_r($sessions);
		    echo "</pre>";
		  }		  	  
		  
		  return ($sessions);
        }		
//END OF CLASS
}

class connect_pool {

  var $login;
  var $password;
  var $id;
  var $connected;
  var $last_transmit;
  var $name;
  var $resource;
  var $session; 
  
  function connect_pool() {
    $this->resource = null;
  }
  
  function reset_client() {
  
    $this->login = null;
    $this->password = null;
    $this->id = null;
    $this->connected = null;	
    $this->last_transmit = null;
    $this->name = null;
    $this->resource = null;			
	$this->session = array();
  }
  
}

?>