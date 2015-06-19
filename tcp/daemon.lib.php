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
define ("VERBOSE_LEVEL", 2);
define ("DAEMON_BUFFER", 1024);
define ("MAX_CONNECTIONS", 4);

/* can be set to 'standalone' for listening on specified port. When run
 * as an inetd service, this class reads from stdin and outputs to
 * stdout. and hence the address/port doesn't make any sense in the
 * inetd context
 */

define ("SERVER_TYPE", 'standalone'); //and oh, this can be _anything_
                                                                                                  //if you wanted this to do sockets...

define ("SHOW_PROMPT", true); //should a prompt be displayed? 

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
        var $PromptString = 'foo> ';

        function daemon() {
		
		        dl('php_sockets.dll');
		
                /* we set the max. execution time to 0 (disable) to ensure that the php script doesnot end abr
ubptly
                 * while listening over a socket ... or while having a slow
                 * interaction with the client */
                set_time_limit (0); 

                /* Also we need to make sure that the data from / to the client
                 * isn't buffered. So we make all data go through with getting
                 * buffered. */
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

        function start () {
                
                if (SERVER_TYPE == 'inetd') {
                /* This daemon is already listening to a socket. Thanks to inetd.
                 * we just output to stdout and read from stdin.
                 */
                $this->stdin = fopen ('php://stdin', 'r');
                } else {
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
                        $this->sock_message_socket_create ();
                }
        }

        function sock_message_socket_create () {
                $this->msg_socket = socket_accept ($this->socket);
                if ($this->msg_socket < 0) {
                        //error
                        $this->sock_die ('socket accept failed!', $this->msg_socket);
                }
                socket_setopt($this->msg_socket, SOL_SOCKET, SO_REUSEADDR, 1);
        }

        function sock_reset () {
                $this->close ();
                $this->sock_message_socket_create ();
        }

        function close () {
                if (SERVER_TYPE != 'inetd') {
                        $this->verbose (1, "---------------Connection closed------------");
                        socket_shutdown ($this->msg_socket);
//                      socket_shutdown ($this->socket);
                }
        }

        function shutdown () {
                if (SERVER_TYPE != 'inetd') { //because it just doesn't make sense
                                                                                                
  	           //      to have an 'inetd' service shut
               //      itself down... ;-/
                        $this->println ('*** Server Shutting down ***');
                        $this->verbose (1, '=======Server Shutdown=========');
                        $this->close ();
                } 
        }

        function sock_die ($msg, $return_code, $to_be_closed) {
                echo "$msg: " . socket_strerror ($return_code);
                if ($to_be_closed) {
                        socket_close ($this->msg_socket);
                }
                exit;
        }

        function ShowHeader () {
                if ($this->first_time) {
                        socket_getpeername ($this->msg_socket, $peer_addr, $peer_port);
                        $this->verbose (1, "---------Connection from $peer_addr-----------");
                        $this->Println ($this->Header);
                }
                $this->first_time = false;
        }

        function Println ($string) {
//              fputs ($this->stdout, trim ($string) . "\n");
                $this->_Print ($string . "\n");
        }

        function Read () {
                if (SERVER_TYPE == 'inetd') {
                        return trim (fgets ($this->stdin, DAEMON_BUFFER));
                } else {
                        /*if (FALSE == ($buf = socket_read ($this->msg_socket,
                        DAEMON_BUFFER))) {
                                //error reading socket
                                $this->sock_die ('Error Reading from socket!', $buf, true);
                                //true makes sock_die to close the socket in the end
                        } else {
                                $this->verbose (5, '<<' . $buf);
                                return trim ($buf);
                        }*/
						//2 ENTERS REQUIRED!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        $data = "";
                        while (socket_recv($this->msg_socket,$buf,DAEMON_BUFFER,0) !== false) {
                          $data .= $buf;
						  //echo $data;
                          //$this->verbose (5, $data);
                          if (preg_match("'\r\n\r\n$'s", $data)) {
	
                                $this->verbose (5, '<<' . $data);
                                return trim ($data);						  
						  }
                        }							
                }
        }

        function _Print ($string) {
//              fputs ($this->stdout, $string);
                if (SERVER_TYPE == 'inetd') {
                        echo $string;
                } else {
                        $this->verbose (5, '>>' . $string);
                        socket_write ($this->msg_socket, $string, strlen ($string));
                }
        }

        function showError ($Severity, $ErrorString) {
                $this->Println ($Severity . ':' . $ErrorString);
        }

        function resetConnection () {
                $this->Println ('goodbye!');
                $this->first_time = true;
                if (SERVER_TYPE == 'inetd') {
                        exit;
                } else {
                        $this->sock_reset ();
                }
        }

        function isValidCommand ($cmd) {        
                if (in_array (strtoupper ($cmd), $this->valid_commands)) {
                        return true;
                } else {
                        return false;
                }
        }

        function Tokenise ($command_line) {
                $raw_tokens = explode (' ', trim ($command_line));
                //the first one is the command
                $command = $raw_tokens[0];
                //the rest are all parameters to the command
                $params = array_slice ($raw_tokens, 1);
                $tokens['command'] = strtoupper ($command);
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
                        if ($callback) {
                                if (!isset ($defined_functions)) {
                                        $defined_functions = get_defined_functions();
                                }
                                
                                if (in_array ($callback, $defined_functions['user'])) {
                                        $this->callbacks[$command][] = $callback;
                                        $this->callbacks[$command] = array_unique ($this->callbacks[$command]);
                                } else {
                                        $this->showError ('FATAL', 'Could not call `' . $callback . '()` Function not defined!');
                                        $this->resetConnection();
                                        exit;
                                }
                        } else {
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

        function showPrompt () {
                $this->_Print ($this->PromptString);
        }

        function listen() {
                /* This is the main loop that will listen for commands and call
                 * the respective callback functions. 
                 */
                //enter a listening loop
                while (true) {
                        $this->ShowHeader();
                        if (SHOW_PROMPT) {
                                $this->showPrompt();
                        }
                        $command_line = $this->Read();
                        if (!empty ($command_line)) {
                                $this->verbose (4, "Received $command_line");
                        
                                $command_set = $this->Tokenise ($command_line);
                                $cmd = $command_set['command'];
                                $params = $command_set['params'];
                                
                                if ($this->isValidCommand ($cmd)) {
                                        //see if this is registered in our callback function set
                                        
                                        $callbacks = $this->CommandAction ($cmd);
                                        if (!empty ($callbacks)) {
                                                //has callback functions... lets call them one by one
                                                
                                                foreach ($callbacks as $function) {
                                                        //call the callback function
                                                        $status = $function ($command_set['command'], $command_set['params'], &$this); 
                                                        if (false == $status) {
                                                                //function says that we should exit...
                                                                $this->resetConnection();
                                                                //exit;
                                                        }
                                                }

                                        } else {
                                                //NO EVENTS... 
                                                $this->Println ('`' . $command_set['command'] . '\' defined but not implemented');
                                                $this->verbose (1, '`' . $command_set['command'] . '\'
                                                not implemented!');
                                        }
                                } else {
                                        $this->showError ('NOTIFY', 'Command `' .
                                        $command_set['command'] . '\' is unrecognized');
                                }
                        }
                }
        }
//END OF CLASS
}

?>